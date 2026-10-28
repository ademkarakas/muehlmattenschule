<?php

declare(strict_types=1);

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Stellt sicher, dass die Startseite erfolgreich als Inertia-Seite geladen wird.
     */
    public function test_the_home_page_renders_Successfully(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('home')
                ->where('schoolName', 'Mühlmattenschule')
            );
    }
}
