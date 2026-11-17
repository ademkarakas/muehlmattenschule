<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prüft, dass Besucher im Wartungsmodus eine informative HTTP-503-Seite mit Kontaktdaten erhalten.
     */
    public function test_guests_receive_informative_503_page_when_maintenance_mode_is_active(): void
    {
        Setting::setValue('school', [
            'name' => 'Mühlmattenschule',
            'subtitle' => 'Grundschule Freiburg-Hochdorf',
            'phone' => '07665 2232',
            'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
            'office_hours' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
            'maintenance_active' => true,
            'maintenance_title' => 'Wartungsarbeiten an unserer Schulwebsite',
            'maintenance_message' => 'Wir aktualisieren aktuell die Seiten für das neue Schuljahr.',
            'maintenance_until' => 'Montag, 08:00 Uhr',
            'maintenance_show_contact' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(503);
        $response->assertHeader('Retry-After', '3600');
        $response->assertSee('Wartungsarbeiten an unserer Schulwebsite');
        $response->assertSee('Wir aktualisieren aktuell die Seiten für das neue Schuljahr.');
        $response->assertSee('Montag, 08:00 Uhr');
        $response->assertSee('07665 2232');
        $response->assertSee('sekretariat.mmsvn@freiburger-schulen.bwl.de');
        $response->assertSee('Caritas Schulkindbetreuung');
        $response->assertDontSee('mailto:');
        $response->assertDontSee('tel:');
        $response->assertDontSee('<button', false);
    }

    /**
     * Prüft, dass angemeldete Mitarbeitende die Website im Wartungsmodus weiterhin als Vorschau sehen können.
     */
    public function test_authenticated_staff_can_preview_website_during_maintenance_mode(): void
    {
        Setting::setValue('school', [
            'maintenance_active' => true,
            'maintenance_title' => 'Wartung aktiv',
        ]);

        $user = User::factory()->create([
            'role' => UserRole::Schulleitung,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('"maintenancePreview":true', false);
    }

    /**
     * Prüft, dass angemeldete Mitarbeitende die Wartungsseite gezielt über ?wartungsmodus-vorschau=1 prüfen können,
     * während Gäste immer die reguläre 503-Antwort ohne Vorschaubalken erhalten.
     */
    public function test_authenticated_staff_can_preview_maintenance_page_with_query_flag(): void
    {
        Setting::setValue('school', [
            'maintenance_active' => true,
            'maintenance_title' => 'Wartung aktiv',
        ]);

        $user = User::factory()->create([
            'role' => UserRole::Schulleitung,
        ]);

        $staffPreview = $this->actingAs($user)->get('/?wartungsmodus-vorschau=1');
        $staffPreview->assertOk();
        $staffPreview->assertSee('Vorschau der Wartungsseite');
        $staffPreview->assertSee('Wartung aktiv');

        auth()->logout();

        $guestResponse = $this->get('/?wartungsmodus-vorschau=1');
        $guestResponse->assertStatus(503);
        $guestResponse->assertDontSee('Vorschau der Wartungsseite');
    }

    /**
     * Prüft, dass das Admin-Login auch bei aktivem Wartungsmodus erreichbar bleibt.
     */
    public function test_admin_login_remains_accessible_when_maintenance_mode_is_active(): void
    {
        Setting::setValue('school', [
            'maintenance_active' => true,
        ]);

        $response = $this->get('/admin/login');

        $response->assertOk();
    }
}
