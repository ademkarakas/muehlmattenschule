<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Album;
use App\Models\Category;
use App\Models\Event;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SeoBestPracticesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prüft, dass die Startseite serverseitig vollständige Meta-Tags, Canonical-URL,
     * Open-Graph-Tags sowie das Schema.org ElementarySchool & WebSite JSON-LD rendert.
     */
    public function test_home_page_renders_server_side_seo_tags_and_school_json_ld(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">', false)
            ->assertSee('<meta name="theme-color" content="#029786">', false)
            ->assertSee('<link rel="manifest" href="/site.webmanifest">', false)
            ->assertSee('<title data-inertia>Mühlmattenschule – Grundschule Freiburg-Hochdorf</title>', false)
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('<meta property="og:locale" content="de_DE"', false)
            ->assertSee('<meta property="og:type" content="website"', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image"', false)
            ->assertSee('"@type":"ElementarySchool"', false)
            ->assertSee('"@type":"WebSite"', false);
    }

    /**
     * Prüft, dass eine Beitrags-Detailseite den Artikel-Titel, die Beschreibung,
     * Open-Graph-Article-Tags, BreadcrumbList und NewsArticle-JSON-LD im HTML enthält.
     */
    public function test_post_detail_page_renders_article_meta_and_news_article_json_ld(): void
    {
        $category = Category::factory()->create([
            'name' => 'Klassenprojekte',
            'slug' => 'klassenprojekte',
        ]);

        $post = Post::factory()->create([
            'title' => 'Besuch auf dem Mundenhof',
            'slug' => 'besuch-auf-dem-mundenhof',
            'excerpt' => 'Die Klasse 2a besuchte gemeinsam den Mundenhof in Freiburg.',
            'body' => '<p>Ausführlicher Bericht über unseren Ausflug.</p>',
            'category_id' => $category->id,
            'class_name' => 'Klasse 2a',
            'status' => PublishStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/aktuelles/'.$post->slug);

        $response->assertOk()
            ->assertSee('<title data-inertia>Besuch auf dem Mundenhof – Mühlmattenschule</title>', false)
            ->assertSee('Die Klasse 2a besuchte gemeinsam den Mundenhof in Freiburg.', false)
            ->assertSee('<meta property="og:type" content="article"', false)
            ->assertSee('<meta property="article:published_time"', false)
            ->assertSee('<meta property="article:section" content="Klassenprojekte"', false)
            ->assertSee('"@type":"NewsArticle"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('posts/show')
                ->where('seo.title', 'Besuch auf dem Mundenhof')
                ->where('seo.ogType', 'article')
                ->has('seo.breadcrumbs', 3)
            );
    }

    /**
     * Prüft, dass paginierte Übersichtsseiten rel="prev" und rel="next" Links im HTML-Head ausgeben.
     */
    public function test_paginated_posts_index_outputs_rel_prev_and_next_links(): void
    {
        Post::factory()->count(12)->create([
            'status' => PublishStatus::Published,
            'published_at' => now()->subHour(),
        ]);

        $pageOne = $this->get('/aktuelles');
        $pageOne->assertOk()
            ->assertSee('<link rel="next"', false)
            ->assertDontSee('<link rel="prev"', false);

        $pageTwo = $this->get('/aktuelles?page=2');
        $pageTwo->assertOk()
            ->assertSee('<link rel="prev"', false)
            ->assertSee('(Seite 2)', false);
    }

    /**
     * Prüft, dass die Terminübersicht Schema.org Event-Knoten im JSON-LD erzeugt
     * und die Galerie-Detailseite ImageGallery-JSON-LD ausgibt.
     */
    public function test_events_and_gallery_pages_output_structured_data(): void
    {
        Event::factory()->create([
            'title' => 'Sommerfest auf dem Schulhof',
            'starts_at' => now()->addDays(5),
            'is_published' => true,
        ]);

        $album = Album::factory()->create([
            'title' => 'Einschulung 2026',
            'slug' => 'einschulung-2026',
            'description' => 'Feierliche Einschulung unserer ersten Klassen.',
            'consent_confirmed' => true,
            'status' => PublishStatus::Published,
        ]);

        $this->get('/termine')
            ->assertOk()
            ->assertSee('"@type":"Event"', false)
            ->assertSee('Sommerfest auf dem Schulhof', false);

        $this->get('/galerie/'.$album->slug)
            ->assertOk()
            ->assertSee('"@type":"ImageGallery"', false)
            ->assertSee('Einschulung 2026 – Fotogalerie – Mühlmattenschule', false);
    }

    /**
     * Prüft, dass die XML-Sitemap <loc>, <lastmod>, <changefreq> und <priority> enthält
     * und Entwürfe sowie nicht freigegebene Alben ausschließt.
     */
    public function test_sitemap_xml_contains_lastmod_changefreq_priority_and_excludes_drafts(): void
    {
        $publishedPost = Post::factory()->create([
            'slug' => 'veroeffentlichter-beitrag',
            'status' => PublishStatus::Published,
            'published_at' => now()->subHour(),
        ]);

        $draftPost = Post::factory()->create([
            'slug' => 'geheimer-entwurf',
            'status' => PublishStatus::Draft,
        ]);

        Page::query()->create([
            'title' => 'Ganztagsschule',
            'slug' => 'ganztagsschule',
            'section' => 'schulleben',
            'menu_order' => 1,
            'content' => [],
            'status' => PublishStatus::Published,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<lastmod>', false)
            ->assertSee('<changefreq>', false)
            ->assertSee('<priority>', false)
            ->assertSee('/aktuelles/'.$publishedPost->slug, false)
            ->assertSee('/schulleben/ganztagsschule', false)
            ->assertDontSee($draftPost->slug, false);
    }
}
