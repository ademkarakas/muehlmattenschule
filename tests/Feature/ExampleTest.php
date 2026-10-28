<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Album;
use App\Models\Event;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Stellt sicher, dass die Startseite erfolgreich als Inertia-Seite geladen wird.
     */
    public function test_the_home_page_renders_successfully(): void
    {
        Post::factory()->create(['status' => PublishStatus::Published, 'class_name' => 'Klasse 2a']);
        Event::factory()->create(['is_published' => true]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('home')
                ->has('school')
                ->has('posts', 1)
                ->has('upcomingEvents', 1)
            );
    }

    /**
     * Prüft, dass alle öffentlichen Unterseiten (Aktuelles, Termine, Galerie, Seiten, Downloads, Rechtliches) laden.
     */
    public function test_all_public_pages_render_successfully(): void
    {
        $post = Post::factory()->create([
            'slug' => 'ausflug-klasse-2a',
            'class_name' => 'Klasse 2a',
            'status' => PublishStatus::Published,
        ]);

        $album = Album::factory()->create([
            'slug' => 'sommerfest-2026',
            'consent_confirmed' => true,
            'status' => PublishStatus::Published,
        ]);

        Page::query()->create([
            'title' => 'Schulportrait',
            'slug' => 'schulportrait',
            'section' => 'unsere-schule',
            'menu_order' => 1,
            'content' => [['type' => 'rich_text', 'data' => ['heading' => 'Willkommen', 'body' => '<p>Text</p>']]],
            'status' => PublishStatus::Published,
        ]);

        $this->get('/aktuelles')->assertOk();
        $this->get('/aktuelles?klasse=Klasse+2a')->assertOk();
        $this->get('/aktuelles/'.$post->slug)->assertOk();
        $this->get('/termine')->assertOk();
        $this->get('/galerie')->assertOk();
        $this->get('/galerie/'.$album->slug)->assertOk();
        $this->get('/unsere-schule/schulportrait')->assertOk();
        $this->get('/downloads')->assertOk();
        $this->get('/kontakt')->assertOk();
        $this->get('/impressum')->assertOk();
        $this->get('/datenschutz')->assertOk();
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    }
}
