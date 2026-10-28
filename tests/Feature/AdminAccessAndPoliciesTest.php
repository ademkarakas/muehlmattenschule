<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Album;
use App\Models\Announcement;
use App\Models\Page;
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
}
