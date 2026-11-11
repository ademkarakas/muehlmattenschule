<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Page;
use App\Models\Person;
use App\Support\SeoData;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Rendert eine dynamisch über das Admin-Panel gepflegte Schulseite (z. B. Schulportrait, Kollegium).
     */
    public function show(string $section, string $slug): Response
    {
        $allowedSections = $section === 'schulleben'
            ? ['schulleben', 'konzepte-projekte', 'angebote']
            : [$section];

        $page = Page::query()
            ->published()
            ->whereIn('section', $allowedSections)
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

        $blocks = is_array($page->content) ? $page->content : [];
        $firstImage = null;
        $firstRichText = null;

        foreach ($blocks as $block) {
            if (! is_array($block)) {
                continue;
            }
            $type = $block['type'] ?? '';
            $data = is_array($block['data'] ?? null) ? $block['data'] : [];

            if ($firstImage === null && $type === 'image_banner' && ! empty($data['image_url']) && is_string($data['image_url'])) {
                $firstImage = $data['image_url'];
            }
            if ($firstImage === null && $type === 'image_gallery' && ! empty($data['image_1_url']) && is_string($data['image_1_url'])) {
                $firstImage = $data['image_1_url'];
            }
            if ($firstRichText === null && $type === 'rich_text' && ! empty($data['body']) && is_string($data['body'])) {
                $firstRichText = $data['body'];
            }
        }

        $sectionLabel = match ($page->section) {
            'unsere-schule' => 'Unsere Schule',
            'konzepte-projekte' => 'Konzepte & Projekte',
            'angebote' => 'Angebote & Betreuung',
            default => 'Schulleben & Betreuung',
        };

        $canonicalPath = "/{$page->section}/{$page->slug}";
        $description = SeoData::cleanDescription(
            $page->summary ?: $firstRichText,
            "{$page->title} – Informationen der Mühlmattenschule Freiburg-Hochdorf ({$sectionLabel})."
        );

        return Inertia::render('pages/show', [
            'page' => [
                'title' => $page->title,
                'slug' => $page->slug,
                'section' => $page->section,
                'menuGroup' => $page->menu_group,
                'summary' => $page->summary,
                'content' => $blocks,
            ],
            'people' => $people,
            'downloads' => $downloads,
            'seo' => SeoData::make([
                'title' => $page->title,
                'description' => $description,
                'canonicalPath' => $canonicalPath,
                'ogImage' => $firstImage,
                'ogImageAlt' => $page->title,
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => $sectionLabel, 'href' => null],
                    ['label' => $page->title, 'href' => null],
                ],
            ]),
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
            'seo' => SeoData::make([
                'title' => 'Elternbriefe, Formulare & Downloads',
                'description' => 'Wichtige Formulare, Elternbriefe, Schulordnung, Materiallisten und Dokumente der Mühlmattenschule Freiburg-Hochdorf als PDF zum Herunterladen.',
                'canonicalPath' => '/downloads',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Elternbriefe & Formulare', 'href' => null],
                ],
            ]),
        ]);
    }

    /**
     * Zeigt die Kontaktseite der Schule.
     */
    public function contact(): Response
    {
        $school = SeoData::schoolSettings();
        $canonicalUrl = SeoData::absoluteUrl('/kontakt');

        return Inertia::render('legal/contact', [
            'seo' => SeoData::make([
                'title' => 'Kontakt, Sekretariat & Anfahrt',
                'description' => "Kontakt zur {$school['name']} ({$school['subtitle']}): {$school['street']}, {$school['zip_city']} · Telefon {$school['phone']} · Sekretariat {$school['office_hours']}.",
                'canonicalPath' => '/kontakt',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Kontakt & Anfahrt', 'href' => null],
                ],
                'schemaNodes' => [
                    [
                        '@type' => 'ContactPage',
                        '@id' => $canonicalUrl.'#contactpage',
                        'name' => "Kontakt & Anfahrt – {$school['name']}",
                        'url' => $canonicalUrl,
                        'inLanguage' => 'de-DE',
                        'mainEntity' => [
                            '@id' => url('/').'#school',
                        ],
                    ],
                ],
            ]),
        ]);
    }

    /**
     * Zeigt das Impressum.
     */
    public function impressum(): Response
    {
        return Inertia::render('legal/impressum', [
            'seo' => SeoData::make([
                'title' => 'Impressum',
                'description' => 'Offizielles Impressum der Mühlmattenschule (Grundschule Freiburg-Hochdorf) gemäß § 5 DDG: Schulleitung, Schulträger Stadt Freiburg und Aufsichtsbehörde.',
                'canonicalPath' => '/impressum',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Impressum', 'href' => null],
                ],
            ]),
        ]);
    }

    /**
     * Zeigt die Datenschutzerklärung.
     */
    public function datenschutz(): Response
    {
        return Inertia::render('legal/datenschutz', [
            'seo' => SeoData::make([
                'title' => 'Datenschutzerklärung',
                'description' => 'Datenschutzerklärung der Mühlmattenschule Freiburg-Hochdorf gemäß DSGVO, LDSG Baden-Württemberg und TDDDG sowie Kontaktdaten des Datenschutzbeauftragten.',
                'canonicalPath' => '/datenschutz',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Datenschutzerklärung', 'href' => null],
                ],
            ]),
        ]);
    }
}
