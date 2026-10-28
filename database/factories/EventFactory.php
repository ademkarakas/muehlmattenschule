<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Definiert den Standardzustand eines Schultermins.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $starts = fake()->dateTimeBetween('+1 day', '+2 months');

        return [
            'title' => fake()->sentence(3),
            'starts_at' => $starts,
            'ends_at' => (clone $starts)->modify('+2 hours'),
            'all_day' => false,
            'location' => 'Mühlmattenschule',
            'description' => fake()->sentence(10),
            'is_published' => true,
        ];
    }
}

