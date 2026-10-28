<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AnnouncementLevel;
use App\Enums\PublishStatus;
use App\Enums\UserRole;
use App\Models\Album;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentModelScopesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Stellt sicher, dass nur veröffentlichte und nicht in der Zukunft geplante Beiträge im Scope erscheinen.
     */
    public function test_post_published_scope_filters_drafts_and_future_posts(): void
    {
        $visible = Post::factory()->create([
            'status' => PublishStatus::Published,
            'published_at' => now()->subMinute(),
        ]);

        Post::factory()->create([
            'status' => PublishStatus::Draft,
            'published_at' => now()->subMinute(),
        ]);

        Post::factory()->create([
            'status' => PublishStatus::Published,
            'published_at' => now()->addDay(),
        ]);

        $results = Post::query()->published()->pluck('id')->all();

        $this->assertSame([$visible->id], $results);
    }

    /**
     * Stellt sicher, dass Fotoalben ohne DSGVO-Einwilligung niemals öffentlich erscheinen.
     */
    public function test_album_published_scope_requires_consent_confirmation(): void
    {
        $withConsent = Album::factory()->create([
            'status' => PublishStatus::Published,
            'consent_confirmed' => true,
        ]);

        Album::factory()->create([
            'status' => PublishStatus::Published,
            'consent_confirmed' => false,
        ]);

        $results = Album::query()->published()->pluck('id')->all();

        $this->assertSame([$withConsent->id], $results);
    }

    /**
     * Stellt sicher, dass abgelaufene Ankündigungen automatisch ausgeblendet werden.
     */
    public function test_announcement_currently_visible_scope_hides_expired_entries(): void
    {
        $active = Announcement::factory()->create([
            'level' => AnnouncementLevel::Important,
            'visible_from' => now()->subHour(),
            'visible_until' => now()->addHour(),
            'is_active' => true,
        ]);

        Announcement::factory()->create([
            'visible_from' => now()->subDays(5),
            'visible_until' => now()->subHour(),
            'is_active' => true,
        ]);

        $results = Announcement::query()->currentlyVisible()->pluck('id')->all();

        $this->assertSame([$active->id], $results);
    }

    /**
     * Stellt sicher, dass vergangene Termine nicht im Upcoming-Scope auftauchen.
     */
    public function test_event_upcoming_scope_excludes_past_events(): void
    {
        $upcoming = Event::factory()->create([
            'starts_at' => now()->addDays(2),
            'ends_at' => now()->addDays(2)->addHours(2),
            'is_published' => true,
        ]);

        Event::factory()->create([
            'starts_at' => now()->subDays(3),
            'ends_at' => now()->subDays(3)->addHour(),
            'is_published' => true,
        ]);

        $results = Event::query()->upcoming()->pluck('id')->all();

        $this->assertSame([$upcoming->id], $results);
    }

    /**
     * Prüft die Schlüssel-Wert-Speicherung in Setting und die Rollenprüfung am User-Modell.
     */
    public function test_settings_and_user_roles_work_as_expected(): void
    {
        Setting::setValue('school', ['name' => 'Mühlmattenschule']);
        $this->assertSame(['name' => 'Mühlmattenschule'], Setting::getValue('school'));

        $leitung = User::factory()->schulleitung()->create();
        $sekretariat = User::factory()->create(['role' => UserRole::Sekretariat]);

        $this->assertTrue($leitung->isAdminOrLeadership());
        $this->assertFalse($sekretariat->isAdminOrLeadership());
    }
}

