<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Page;
use App\Models\Person;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Rendert eine dynamisch über das Admin-Panel gepflegte Schulseite (z. B. Schulportrait, Kollegium).
     */
    public function show(string $section, string $slug): Response
    {
        $page = Page::query()
            ->published()
            ->where('section', $section)
            ->where('slug', $slug)
            ->firstOrFail();

        $people = Person::query()
            ->where('is_active', true)
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Person $person): array => [
                'id' => $person->id,
                'name' => $person->name,
                'roleTitle' => $person->role_title,
                'className' => $person->class_name,
                'email' => $person->email,
                'group' => $person->group->value,
                'groupLabel' => $person->group->label(),
                'avatarUrl' => $person->getFirstMediaUrl('portrait') ?: null,
            ])
            ->all();

        return Inertia::render('pages/show', [
            'page' => [
                'title' => $page->title,
                'slug' => $page->slug,
                'section' => $page->section,
                'summary' => $page->summary,
                'content' => $page->content ?? [],
            ],
            'people' => $people,
        ]);
    }

    /**
     * Zeigt die Download-Seite für Formulare und Elternbriefe.
     */
    public function downloads(): Response
    {
        $downloads = Download::query()
            ->where('is_published', true)
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->map(fn (Download $download): array => [
                'id' => $download->id,
                'title' => $download->title,
                'category' => $download->category,
                'description' => $download->description,
                'fileUrl' => $download->getFirstMediaUrl('file') ?: null,
            ])
            ->all();

        return Inertia::render('downloads/index', [
            'downloads' => $downloads,
        ]);
    }

    /**
     * Zeigt die Kontaktseite der Schule.
     */
    public function contact(): Response
    {
        return Inertia::render('legal/contact');
    }

    /**
     * Zeigt das Impressum.
     */
    public function impressum(): Response
    {
        return Inertia::render('legal/impressum');
    }

    /**
     * Zeigt die Datenschutzerklärung.
     */
    public function datenschutz(): Response
    {
        return Inertia::render('legal/datenschutz');
    }
}
