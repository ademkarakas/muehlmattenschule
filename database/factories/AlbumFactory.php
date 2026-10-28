<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PublishStatus;
use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Definiert den Standardzustand eines Fotoalbums.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'event_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'description' => fake()->paragraph(),
            'consent_confirmed' => true,
            'status' => PublishStatus::Published,
            'published_at' => now()->subDay(),
        ];
    }
}

