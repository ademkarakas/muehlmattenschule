<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Album;
use App\Models\Category;
use App\Models\Download;
use App\Models\Event;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EdupageFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prüft, dass die neue globale Suchseite (/suche) Beiträge, Schulseiten, Alben, Termine und Downloads findet.
     */
    public function test_site_wide_search_returns_matching_content_across_sections(): void
    {
        $category = Category::factory()->create([
            'name' => 'Sport & Wettbewerbe',
            'slug' => 'sport-bewegung',
        ]);

        Post::factory()->create([
            'title' => 'Großer Crosslauf am Mühlbach',
            'slug' => 'grosser-crosslauf-am-muehlbach',
            'category_id' => $category->id,
            'status' => PublishStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        Page::query()->create([
            'title' => 'Crosslauf & Sponsorenlauf',
            'slug' => 'crosslauf',
            'section' => 'unsere-schule',
            'menu_group' => 'Gremien & Partner',
            'menu_order' => 1,
            'content' => [],
            'status' => PublishStatus::Published,
        ]);

        Album::factory()->create([
            'title' => 'Crosslauf Impressionen 2024',
            'slug' => 'crosslauf-impressionen-2024',
            'gallery_images' => ['/images/school/crosslauf-full-01.jpg', '/images/school/crosslauf-full-02.jpg'],
            'consent_confirmed' => true,
            'status' => PublishStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        Event::factory()->create([
            'title' => 'Nächster Crosslauf Start',
            'starts_at' => now()->addDays(10),
            'is_published' => true,
        ]);

        Download::query()->create([
            'title' => 'Crosslauf Streckenplan PDF',
            'category' => 'Elternbriefe',
            'description' => 'Offizieller Streckenplan des Crosslaufs',
            'is_published' => true,
        ]);

        $this->get('/suche?q=Crosslauf')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('search/index')
                ->where('query', 'Crosslauf')
                ->where('totalCount', 4)
                ->has('results.posts', 1)
                ->has('results.pages', 1)
                ->has('results.events', 1)
                ->has('results.downloads', 1)
            );
    }

    /**
     * Prüft, dass die neuen Menü-Rubriken (/konzepte-projekte/{slug} und /angebote/{slug}) erreichbar sind
     * und in den geteilten Inertia-Navigationsdaten inklusive menuGroup ausgeliefert werden.
     */
    public function test_konzepte_projekte_and_angebote_pages_are_accessible_and_shared_in_navigation(): void
    {
        Page::query()->create([
            'title' => 'Unser Leitbild',
            'slug' => 'leitbild',
            'section' => 'konzepte-projekte',
            'menu_group' => 'Pädagogisches Konzept',
            'menu_order' => 1,
            'content' => [],
            'status' => PublishStatus::Published,
        ]);

        Page::query()->create([
            'title' => 'Arbeitsgemeinschaften (AGs)',
            'slug' => 'arbeitsgemeinschaften',
            'section' => 'angebote',
            'menu_group' => 'AGs & Leseförderung',
            'menu_order' => 2,
            'content' => [],
            'status' => PublishStatus::Published,
        ]);

        $this->get('/konzepte-projekte/leitbild')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('pages/show')
                ->where('page.slug', 'leitbild')
                ->where('page.section', 'konzepte-projekte')
                ->has('navigationPages.konzepteProjekte', 1)
                ->has('navigationPages.angebote', 1)
            );

        $this->get('/angebote/arbeitsgemeinschaften')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('pages/show')
                ->where('page.slug', 'arbeitsgemeinschaften')
                ->where('page.section', 'angebote')
            );
    }

    /**
     * Prüft, dass das „Beiträge nach Rubriken“-Widget über die geteilten Inertia-Props Kategorien und Klassen mit Beitragszählern liefert.
     */
    public function test_shared_rubriken_props_provide_category_and_class_post_counts(): void
    {
        $category = Category::factory()->create([
            'name' => 'Schulleben',
            'slug' => 'schulleben',
        ]);

        Post::factory()->count(2)->create([
            'category_id' => $category->id,
            'class_name' => 'Klasse 3a & 3b',
            'status' => PublishStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->has('rubriken.categories', 1)
                ->where('rubriken.categories.0.slug', 'schulleben')
                ->where('rubriken.categories.0.count', 2)
                ->has('rubriken.classes', 1)
                ->where('rubriken.classes.0.name', 'Klasse 3a & 3b')
                ->where('rubriken.classes.0.count', 2)
            );
    }

    /**
     * Prüft, dass sowohl der gesamte Schulkalender (/termine.ics) als auch einzelne Termine (/termine/{event}/ical)
     * als standardkonforme iCalendar-Dateien (RFC 5545) heruntergeladen werden können.
     */
    public function test_ical_endpoints_export_valid_vcalendar_files(): void
    {
        $event = Event::factory()->create([
            'title' => 'Sommerfest an der Mühlmattenschule',
            'location' => 'Schulhof Hochdorf',
            'class_name' => 'Alle Klassen',
            'category' => 'fest',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7)->addHours(3),
            'all_day' => false,
            'is_published' => true,
        ]);

        $singleResponse = $this->get("/termine/{$event->id}/ical");
        $singleResponse->assertOk();
        $this->assertStringContainsString('text/calendar', (string) $singleResponse->headers->get('Content-Type'));
        $this->assertStringContainsString('BEGIN:VCALENDAR', (string) $singleResponse->getContent());
        $this->assertStringContainsString('SUMMARY:Sommerfest an der Mühlmattenschule (Alle Klassen)', (string) $singleResponse->getContent());

        $allResponse = $this->get('/termine.ics');
        $allResponse->assertOk();
        $this->assertStringContainsString('BEGIN:VCALENDAR', (string) $allResponse->getContent());
        $this->assertStringContainsString('BEGIN:VEVENT', (string) $allResponse->getContent());
    }

    /**
     * Prüft, dass statische Galerie-Pfade (gallery_images) sowohl in Fotoalben als auch in Beiträgen aufgelöst werden.
     */
    public function test_album_and_post_resolve_gallery_images_json_correctly(): void
    {
        $album = Album::factory()->create([
            'title' => 'Adventsbasteln',
            'slug' => 'adventsbasteln',
            'gallery_images' => [
                '/images/school/basteln-full-01.jpg',
                '/images/school/basteln-full-02.jpg',
                '/images/school/basteln-full-03.jpg',
            ],
            'consent_confirmed' => true,
            'status' => PublishStatus::Published,
            'published_at' => now()->subHour(),
        ]);

        $post = Post::factory()->create([
            'title' => 'Adventsbasteln Bericht',
            'slug' => 'adventsbasteln-bericht',
            'album_id' => $album->id,
            'gallery_images' => [
                '/images/school/basteln-full-01.jpg',
                '/images/school/basteln-full-02.jpg',
            ],
            'status' => PublishStatus::Published,
            'published_at' => now()->subHour(),
        ]);

        $this->assertSame(3, $album->totalPhotoCount());
        $this->assertCount(2, $post->resolvedGalleryPhotos());

        $this->get("/aktuelles/{$post->slug}")
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('posts/show')
                ->has('post.galleryPhotos', 2)
                ->where('post.linkedAlbum.photosCount', 3)
            );
    }
}
