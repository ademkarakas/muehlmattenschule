<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PublishStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Definiert den Standardzustand eines Beitrags.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->text(180),
            'body' => '<p>'.fake()->paragraph(4).'</p>',
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
            'is_featured' => false,
            'status' => PublishStatus::Published,
            'published_at' => now()->subHour(),
        ];
    }
}

