<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AnnouncementLevel;
use App\Enums\PublishStatus;
use App\Enums\UserRole;
use App\Models\Album;
use App\Models\Announcement;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Support\ImageExifStripper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prüft, dass alle sicherheitsrelevanten HTTP-Header (inkl. Content-Security-Policy) gesetzt werden.
     */
    public function test_security_headers_are_applied_to_public_and_admin_routes(): void
    {
        $publicResponse = $this->get('/');

        $publicResponse->assertOk();
        $publicResponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $publicResponse->assertHeader('X-Content-Type-Options', 'nosniff');
        $publicResponse->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $publicResponse->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
        $publicResponse->assertHeader('X-Permitted-Cross-Domain-Policies', 'none');

        $csp = (string) $publicResponse->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);

        $adminResponse = $this->get('/admin/login');
        $adminResponse->assertOk();
        $adminResponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $adminResponse->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Prüft, dass gefährliche XSS-Payloads beim Speichern eines Beitrags automatisch entfernt werden.
     */
    public function test_post_body_strips_xss_scripts_and_event_handlers(): void
    {
        $post = Post::factory()->create([
            'body' => '<p onclick="alert(1)">Sicherer Text</p><script>alert("xss")</script>'
                .'<a href="javascript:alert(2)" target="_blank">Böser Link</a>'
                .'<a href="/aktuelles" target="_blank">Guter Link</a>'
                .'<img src="x" onerror="alert(3)">',
        ]);

        $this->assertStringNotContainsString('<script', $post->body);
        $this->assertStringNotContainsString('onclick', $post->body);
        $this->assertStringNotContainsString('onerror', $post->body);
        $this->assertStringNotContainsString('javascript:', $post->body);
        $this->assertStringContainsString('<p>Sicherer Text</p>', $post->body);
        $this->assertStringContainsString('href="/aktuelles"', $post->body);
        $this->assertStringContainsString('rel="noopener noreferrer"', $post->body);
    }

    /**
     * Prüft, dass auch Schulseiten-Blöcke (Page content) gegen Stored-XSS und javascript:-URLs geschützt sind.
     */
    public function test_page_content_blocks_are_sanitized_on_save(): void
    {
        $page = Page::query()->create([
            'title' => 'Schulordnung',
            'slug' => 'schulordnung',
            'section' => 'unsere-schule',
            'menu_order' => 1,
            'status' => PublishStatus::Published,
            'content' => [
                [
                    'type' => 'rich_text',
                    'data' => [
                        'heading' => 'Schulordnung',
                        'body' => '<p>Willkommen</p><iframe src="https://evil.example"></iframe><script>evil()</script>',
                    ],
                ],
                [
                    'type' => 'image_banner',
                    'data' => [
                        'image_url' => 'javascript:alert(document.cookie)',
                        'caption' => 'Testbild',
                    ],
                ],
            ],
        ]);

        /** @var array<int, array{type: string, data: array<string, string>}> $blocks */
        $blocks = $page->content;

        $this->assertStringNotContainsString('<script', $blocks[0]['data']['body']);
        $this->assertStringNotContainsString('<iframe', $blocks[0]['data']['body']);
        $this->assertStringContainsString('<p>Willkommen</p>', $blocks[0]['data']['body']);
        $this->assertSame('', $blocks[1]['data']['image_url']);
    }

    /**
     * Prüft, dass Ankündigungs-Links keine gefährlichen URL-Schemata oder Open-Redirect-Tricks akzeptieren.
     */
    public function test_announcement_link_url_rejects_dangerous_protocols(): void
    {
        $xssAnnouncement = Announcement::query()->create([
            'text' => 'Test',
            'level' => AnnouncementLevel::Info,
            'link_url' => 'javascript:alert(1)',
            'is_active' => true,
        ]);

        $protocolRelative = Announcement::query()->create([
            'text' => 'Test 2',
            'level' => AnnouncementLevel::Info,
            'link_url' => '//evil.example/phishing',
            'is_active' => true,
        ]);

        $safeInternal = Announcement::query()->create([
            'text' => 'Test 3',
            'level' => AnnouncementLevel::Info,
            'link_url' => '/downloads',
            'is_active' => true,
        ]);

        $this->assertNull($xssAnnouncement->link_url);
        $this->assertNull($protocolRelative->link_url);
        $this->assertSame('/downloads', $safeInternal->link_url);
    }

    /**
     * Stellt sicher, dass ein Entwurfs-Fotoalbum nicht über einen verknüpften Beitrag öffentlich geleakt wird.
     */
    public function test_draft_linked_album_is_not_exposed_on_public_post_page(): void
    {
        $draftAlbum = Album::factory()->create([
            'status' => PublishStatus::Draft,
            'consent_confirmed' => true,
        ]);

        $post = Post::factory()->create([
            'status' => PublishStatus::Published,
            'published_at' => now()->subHour(),
            'album_id' => $draftAlbum->id,
        ]);

        $this->get("/aktuelles/{$post->slug}")
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('posts/show')
                ->where('post.linkedAlbum', null)
            );
    }

    /**
     * Stellt sicher, dass Nicht-Administratoren (z. B. Schulleitung oder Lehrkräfte)
     * keine Konten auf die Rolle Admin hochstufen können.
     */
    public function test_non_admin_cannot_escalate_user_role_to_admin(): void
    {
        $schulleitung = User::factory()->schulleitung()->create();
        $lehrkraft = User::factory()->lehrkraft('Klasse 1a')->create();

        $this->actingAs($schulleitung);

        $this->expectException(AuthorizationException::class);

        $lehrkraft->update([
            'role' => UserRole::Admin,
        ]);
    }

    /**
     * Prüft, dass der ImageExifStripper JPEG-Dateien erfolgreich neu kodiert.
     */
    public function test_image_exif_stripper_reencodes_jpeg_cleanly(): void
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not available.');
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'mms_exif_').'.jpg';
        $img = imagecreatetruecolor(32, 32);
        imagejpeg($img, $tempFile, 95);
        imagedestroy($img);

        $this->assertTrue(ImageExifStripper::stripFile($tempFile, 'image/jpeg'));
        $this->assertFileExists($tempFile);

        @unlink($tempFile);
    }
}
