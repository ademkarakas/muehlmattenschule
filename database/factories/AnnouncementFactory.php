<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnnouncementLevel;
use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Definiert den Standardzustand einer Ankündigung.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => fake()->sentence(8),
            'level' => AnnouncementLevel::Info,
            'visible_from' => now()->subHour(),
            'visible_until' => now()->addWeek(),
            'is_active' => true,
        ];
    }
}
