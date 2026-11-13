<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Album;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Download;
use App\Models\Event;
use App\Models\Page;
use App\Models\Person;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessAndPoliciesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Stellt sicher, dass das Admin-Login unter /admin/login erreichbar ist.
     */
    public function test_admin_login_page_is_reachable(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
    }

    /**
     * Stellt sicher, dass Schulleitung, Sekretariat und Lehrkräfte das Admin-Dashboard öffnen können.
     */
    public function test_all_school_staff_roles_can_access_admin_dashboard(): void
    {
        $leitung = User::factory()->schulleitung()->create();
        $sekretariat = User::factory()->create(['role' => UserRole::Sekretariat]);
        $lehrkraft = User::factory()->lehrkraft('Klasse 2a')->create();

        $this->actingAs($leitung)->get('/admin')->assertOk();
        $this->actingAs($sekretariat)->get('/admin')->assertOk();
        $this->actingAs($lehrkraft)->get('/admin')->assertOk();
    }

    /**
     * Prüft, dass Lehrkräfte eigene Klassenbeiträge und Fotoalben verwalten dürfen,
     * aber keine fremden Beiträge oder Schulseiten ändern können.
     */
    public function test_teacher_permissions_are_scoped_to_own_posts_and_albums(): void
    {
        $lehrkraftA = User::factory()->lehrkraft('Klasse 2a')->create();
        $lehrkraftB = User::factory()->lehrkraft('Klasse 3b')->create();
        $sekretariat = User::factory()->create(['role' => UserRole::Sekretariat]);
        $schulleitung = User::factory()->schulleitung()->create();

        $ownPost = Post::factory()->create([
            'author_id' => $lehrkraftA->id,
            'class_name' => 'Klasse 2a',
        ]);
        $otherPost = Post::factory()->create([
            'author_id' => $lehrkraftB->id,
            'class_name' => 'Klasse 3b',
        ]);

        $ownAlbum = Album::factory()->create([
            'author_id' => $lehrkraftA->id,
            'class_name' => 'Klasse 2a',
        ]);
        $otherAlbum = Album::factory()->create([
            'author_id' => $lehrkraftB->id,
            'class_name' => 'Klasse 3b',
        ]);

        $this->assertTrue($lehrkraftA->can('create', Post::class));
        $this->assertTrue($lehrkraftA->can('update', $ownPost));
        $this->assertTrue($lehrkraftA->can('delete', $ownPost));
        $this->assertFalse($lehrkraftA->can('update', $otherPost));
        $this->assertFalse($lehrkraftA->can('delete', $otherPost));

        $this->assertTrue($lehrkraftA->can('create', Album::class));
        $this->assertTrue($lehrkraftA->can('update', $ownAlbum));
        $this->assertFalse($lehrkraftA->can('delete', $otherAlbum));

        // Sekretariat darf Beiträge aller Lehrkräfte bearbeiten
        $this->assertTrue($sekretariat->can('update', $otherPost));
        $this->assertTrue($sekretariat->can('delete', $otherAlbum));

        // Lehrkräfte dürfen keine Ankündigungen, festen Seiten oder Benutzerkonten verwalten
        $this->assertFalse($lehrkraftA->can('viewAny', Announcement::class));
        $this->assertFalse($lehrkraftA->can('viewAny', Page::class));
        $this->assertFalse($lehrkraftA->can('viewAny', User::class));

        // Schulleitung darf feste Seiten und Benutzerkonten verwalten, aber keinen Admin löschen
        $admin = User::factory()->admin()->create();
        $this->assertTrue($schulleitung->can('viewAny', Page::class));
        $this->assertTrue($schulleitung->can('delete', $lehrkraftA));
        $this->assertFalse($schulleitung->can('delete', $admin));
    }

    /**
     * Prüft, dass die Ressourcen-Listen im Admin-Panel für die Schulleitung erfolgreich laden.
     */
    public function test_filament_resource_pages_render_for_leadership(): void
    {
        $schulleitung = User::factory()->schulleitung()->create();

        $this->actingAs($schulleitung)->get('/admin/posts')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/albums')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/events')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/announcements')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/pages')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/people')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/downloads')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/categories')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/users')->assertOk();
        $this->actingAs($schulleitung)->get('/admin/manage-school-settings')->assertOk();
    }

    /**
     * Prüft, dass Benutzerkonten sowie alle weiteren Verwaltungs-Ressourcen auf eigenen Seiten
     * statt in einem Popup angelegt und bearbeitet werden.
     */
    public function test_user_create_and_edit_pages_render_as_full_pages(): void
    {
        $schulleitung = User::factory()->schulleitung()->create();
        $lehrkraft = User::factory()->create(['role' => UserRole::Lehrkraft]);
        $announcement = Announcement::factory()->create(['text' => 'Wichtige Schulinfo']);
        $person = Person::query()->create([
            'name' => 'Diana Blum',
            'role_title' => 'Rektorin',
            'group' => 'schulleitung',
            'is_active' => true,
        ]);
        $download = Download::query()->create([
            'title' => 'Schulwegeplan PDF',
            'category' => 'Formulare',
            'is_published' => true,
        ]);
        $category = Category::factory()->create(['name' => 'Ausflüge']);
        $event = Event::factory()->create(['title' => 'Sommerfest']);

        $this->actingAs($schulleitung)->get('/admin/users/create')->assertOk();
        $this->actingAs($schulleitung)->get("/admin/users/{$lehrkraft->id}/edit")
            ->assertOk()
            ->assertSee($lehrkraft->email);

        $this->actingAs($schulleitung)->get('/admin/announcements/create')->assertOk();
        $this->actingAs($schulleitung)->get("/admin/announcements/{$announcement->id}/edit")
            ->assertOk()
            ->assertSee('Wichtige Schulinfo');

        $this->actingAs($schulleitung)->get('/admin/people/create')->assertOk();
        $this->actingAs($schulleitung)->get("/admin/people/{$person->id}/edit")
            ->assertOk()
            ->assertSee('Diana Blum');

        $this->actingAs($schulleitung)->get('/admin/downloads/create')->assertOk();
        $this->actingAs($schulleitung)->get("/admin/downloads/{$download->id}/edit")
            ->assertOk()
            ->assertSee('Schulwegeplan PDF');

        $this->actingAs($schulleitung)->get('/admin/categories/create')->assertOk();
        $this->actingAs($schulleitung)->get("/admin/categories/{$category->id}/edit")
            ->assertOk()
            ->assertSee('Ausflüge');

        $this->actingAs($schulleitung)->get('/admin/events/create')
            ->assertOk()
            ->assertSee('Termindaten')
            ->assertSee('Zurück');
        $this->actingAs($schulleitung)->get("/admin/events/{$event->id}/edit")
            ->assertOk()
            ->assertSee('Sommerfest')
            ->assertSee('Zurück');

        $post = Post::factory()->create(['title' => 'Projektwoche im Frühling']);
        $album = Album::factory()->create(['title' => 'Sportfest Album']);
        $page = Page::query()->create([
            'title' => 'Unser Leitbild',
            'slug' => 'unser-leitbild',
            'section' => 'unsere-schule',
            'menu_order' => 1,
            'status' => 'published',
            'content' => [],
        ]);

        $this->actingAs($schulleitung)->get('/admin/posts/create')
            ->assertOk()
            ->assertSee('Neuer Beitrag')
            ->assertSee('Beitragsinhalt')
            ->assertSee('Zurück');
        $this->actingAs($schulleitung)->get("/admin/posts/{$post->id}/edit")
            ->assertOk()
            ->assertSee('Projektwoche im Frühling')
            ->assertSee('Auf Website ansehen')
            ->assertSee('Zurück');

        $this->actingAs($schulleitung)->get('/admin/albums/create')
            ->assertOk()
            ->assertSee('Neues Fotoalbum')
            ->assertSee('Album-Angaben')
            ->assertSee('Zurück');
        $this->actingAs($schulleitung)->get("/admin/albums/{$album->id}/edit")
            ->assertOk()
            ->assertSee('Sportfest Album')
            ->assertSee('Auf Website ansehen')
            ->assertSee('Zurück');

        $this->actingAs($schulleitung)->get('/admin/pages/create')
            ->assertOk()
            ->assertSee('Neue Schulseite')
            ->assertSee('Seiten-Einstellungen')
            ->assertSee('Zurück');
        $this->actingAs($schulleitung)->get("/admin/pages/{$page->id}/edit")
            ->assertOk()
            ->assertSee('Unser Leitbild')
            ->assertSee('Auf Website ansehen')
            ->assertSee('Zurück');
    }

    /**
     * Prüft, dass die Ressourcen-Seiten die dezenten Zähler unter dem Titel anzeigen.
     */
    public function test_resource_pages_render_subheadings_with_counts(): void
    {
        $schulleitung = User::factory()->schulleitung()->create();
        Post::factory()->count(3)->create();
        Event::factory()->count(2)->create(['starts_at' => now()->addDays(3)]);

        $postsResponse = $this->actingAs($schulleitung)->get('/admin/posts');
        $postsResponse->assertOk();
        $postsResponse->assertSee('Insgesamt 3 Beiträge');

        $eventsResponse = $this->actingAs($schulleitung)->get('/admin/events');
        $eventsResponse->assertOk();
        $eventsResponse->assertSee('2 anstehende Termine');
    }

    /**
     * Prüft das modernisierte Admin-Layout: Topbar mit Avatar/Name/Rolle, lokaler DSGVO-SVG-Avatar,
     * „Zur Website“-Schnelllink, Türkis-Sidebar-Theme und Dashboard ohne redundantes AccountWidget.
     */
    public function test_admin_dashboard_renders_modern_topbar_user_pill_and_local_avatar(): void
    {
        $schulleitung = User::factory()->schulleitung()->create([
            'name' => 'Diana Blum (Schulleitung)',
        ]);

        $response = $this->actingAs($schulleitung)->get('/admin');

        $response->assertOk();
        $response->assertSee('Übersicht & Schnellzugriff');
        $response->assertSee('mms-topbar-user-pill', false);
        $response->assertSee('Diana Blum');
        $response->assertSee('Schulleitung');
        $response->assertSee('Zur Website');
        $response->assertSee('data:image/svg+xml;utf8,', false);
        $response->assertDontSee('ui-avatars.com', false);
    }
}
