<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Erzeugt standardisierte SEO-Metadaten, Open-Graph-/Twitter-Tags, Brotkrumen-Navigation
 * und Schema.org-JSON-LD-Strukturdaten (ElementarySchool, WebSite, BreadcrumbList, NewsArticle, Event, ImageGallery)
 * sowohl für das serverseitige Blade-Rendering als auch für die clientseitige Inertia-Navigation.
 */
final class SeoData
{
    /**
     * Liefert die Schulstammdaten inklusive Standardwerten (inkl. Wartungsmodus, Pausenzeiten & Hero-Bildern).
     *
     * @return array{
     *     name: string,
     *     subtitle: string,
     *     street: string,
     *     zip_city: string,
     *     phone: string,
     *     fax: string,
     *     email: string,
     *     office_hours: string,
     *     betreuung_phone: string,
     *     betreuung_mobile: string,
     *     betreuung_email: string,
     *     welcome_title: string,
     *     welcome_text: string,
     *     maintenance_active: bool,
     *     maintenance_title: string,
     *     maintenance_message: string,
     *     maintenance_until: string,
     *     maintenance_show_contact: bool,
     *     bell_schedule: list<array{time: string, label: string, type: string}>,
     *     hero_slides: list<array{url: string, alt: string}>,
     *     quick_links: list<array{label: string, url: string, subtitle: string}>
     * }
     */
    public static function schoolSettings(): array
    {
        $defaultSchool = [
            'name' => 'Mühlmattenschule',
            'subtitle' => 'Grundschule Freiburg-Hochdorf',
            'street' => 'Zu den Mühlmatten 8',
            'zip_city' => '79108 Freiburg im Breisgau',
            'phone' => '07665 2232',
            'fax' => '07665 932081',
            'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
            'office_hours' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
            'welcome_title' => 'Willkommen an der Mühlmattenschule',
            'welcome_text' => 'Die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen – ein Ort zum Lernen, Wachsen und Entdecken.',
            'maintenance_active' => false,
            'maintenance_title' => 'Unsere Website wird gerade für Sie aktualisiert',
            'maintenance_message' => 'Wir führen aktuell planmäßige Wartungsarbeiten durch und aktualisieren Inhalte unserer Schulwebsite. In Kürze sind wir wieder wie gewohnt für Sie erreichbar.',
            'maintenance_until' => '',
            'maintenance_show_contact' => true,
            'bell_schedule' => self::defaultBellSchedule(),
            'hero_slides' => self::defaultHeroSlides(),
            'quick_links' => self::defaultQuickLinks(),
        ];

        $savedSchool = Schema::hasTable('settings')
            ? Setting::getValue('school', [])
            : [];

        $merged = array_merge($defaultSchool, is_array($savedSchool) ? $savedSchool : []);
        $merged['maintenance_active'] = (bool) ($merged['maintenance_active'] ?? false);
        $merged['maintenance_show_contact'] = (bool) ($merged['maintenance_show_contact'] ?? true);
        $merged['maintenance_title'] = trim((string) ($merged['maintenance_title'] ?? '')) !== ''
            ? (string) $merged['maintenance_title']
            : $defaultSchool['maintenance_title'];
        $merged['maintenance_message'] = trim((string) ($merged['maintenance_message'] ?? '')) !== ''
            ? (string) $merged['maintenance_message']
            : $defaultSchool['maintenance_message'];
        $merged['maintenance_until'] = (string) ($merged['maintenance_until'] ?? '');

        if (empty($merged['bell_schedule']) || ! is_array($merged['bell_schedule'])) {
            $merged['bell_schedule'] = self::defaultBellSchedule();
        }
        if (empty($merged['hero_slides']) || ! is_array($merged['hero_slides'])) {
            $merged['hero_slides'] = self::defaultHeroSlides();
        }
        if (empty($merged['quick_links']) || ! is_array($merged['quick_links'])) {
            $merged['quick_links'] = self::defaultQuickLinks();
        }

        /** @var array{name: string, subtitle: string, street: string, zip_city: string, phone: string, fax: string, email: string, office_hours: string, betreuung_phone: string, betreuung_mobile: string, betreuung_email: string, welcome_title: string, welcome_text: string, maintenance_active: bool, maintenance_title: string, maintenance_message: string, maintenance_until: string, maintenance_show_contact: bool, bell_schedule: list<array{time: string, label: string, type: string}>, hero_slides: list<array{url: string, alt: string}>, quick_links: list<array{label: string, url: string, subtitle: string}>} $merged */
        return $merged;
    }

    /**
     * Standard-Unterrichts- und Pausenzeiten der Mühlmattenschule (wie ghsfreiburg.edupage.org „Pausen“-Widget).
     *
     * @return list<array{time: string, label: string, type: string}>
     */
    public static function defaultBellSchedule(): array
    {
        return [
            ['time' => '07:30 – 07:50', 'label' => 'Offener Anfang / Frühbetreuung', 'type' => 'betreuung'],
            ['time' => '07:50 – 08:35', 'label' => '1. Unterrichtsstunde', 'type' => 'lesson'],
            ['time' => '08:35 – 09:20', 'label' => '2. Unterrichtsstunde', 'type' => 'lesson'],
            ['time' => '09:20 – 09:45', 'label' => 'Große Bewegungs- & Vesperpause', 'type' => 'break'],
            ['time' => '09:45 – 10:30', 'label' => '3. Unterrichtsstunde', 'type' => 'lesson'],
            ['time' => '10:30 – 11:15', 'label' => '4. Unterrichtsstunde', 'type' => 'lesson'],
            ['time' => '11:15 – 11:30', 'label' => '2. Pause', 'type' => 'break'],
            ['time' => '11:30 – 12:15', 'label' => '5. Unterrichtsstunde', 'type' => 'lesson'],
            ['time' => '12:15 – 13:00', 'label' => '6. Stunde / Arbeitsgemeinschaften (AGs)', 'type' => 'lesson'],
            ['time' => '12:15 – 17:00', 'label' => 'Schulkindbetreuung & Mittagessen (Caritas)', 'type' => 'betreuung'],
        ];
    }

    /**
     * Standard-Hero-Fotos für die Startseite.
     *
     * @return list<array{url: string, alt: string}>
     */
    public static function defaultHeroSlides(): array
    {
        return [
            [
                'url' => '/images/school/about-main.jpg',
                'alt' => 'Schulgebäude und Pausenhof der Mühlmattenschule Freiburg-Hochdorf',
            ],
            [
                'url' => '/images/school/gebaeude-wide.jpg',
                'alt' => 'Ansicht des Schulhauses im Grünen',
            ],
            [
                'url' => '/images/school/hero.jpg',
                'alt' => 'Eingangsbereich und Holzdach der Mühlmattenschule',
            ],
        ];
    }

    /**
     * Standard-Schnellzugriffe für die Startseiten-Sidebar.
     *
     * @return list<array{label: string, url: string, subtitle: string}>
     */
    public static function defaultQuickLinks(): array
    {
        return [
            [
                'label' => 'Ferienplan 2024/25 (PDF)',
                'url' => '/files/school/Ferienplan-2024-25.pdf',
                'subtitle' => 'Alle Ferientage & beweglichen Ferientage',
            ],
            [
                'label' => 'Sicherer Schulweg (Schulwegeplan)',
                'url' => '/angebote/schulwegeplan',
                'subtitle' => 'Empfohlene Fußwege & Querungshilfen',
            ],
            [
                'label' => 'Schulkindbetreuung (7:30–17:00 Uhr)',
                'url' => '/angebote/schulkindbetreuung',
                'subtitle' => 'Caritas Betreuung & Mittagessen',
            ],
            [
                'label' => 'Schulsozialarbeit & Beratung',
                'url' => '/unsere-schule/schulsozialarbeit',
                'subtitle' => 'Philipp Markert & Nina Ponzelar',
            ],
            [
                'label' => 'Förderverein & Beitritt',
                'url' => '/unsere-schule/foerderverein',
                'subtitle' => 'Projekte, Crosslauf & Mitgliedschaft',
            ],
            [
                'label' => 'Elternbriefe & Formulare',
                'url' => '/downloads',
                'subtitle' => 'Wichtige Dokumente zum Herunterladen',
            ],
        ];
    }

    /**
     * Erstellt ein vollständiges SEO-Datenpaket für eine öffentliche Seite.
     *
     * @param  array{
     *     title?: string,
     *     description?: string|null,
     *     canonicalPath?: string,
     *     robots?: string,
     *     ogType?: 'website'|'article',
     *     ogImage?: string|null,
     *     ogImageAlt?: string|null,
     *     publishedTime?: string|null,
     *     modifiedTime?: string|null,
     *     section?: string|null,
     *     prevUrl?: string|null,
     *     nextUrl?: string|null,
     *     breadcrumbs?: list<array{label: string, href?: string|null}>,
     *     schemaNodes?: list<array<string, mixed>>
     * }  $options
     * @return array{
     *     title: string,
     *     fullTitle: string,
     *     siteName: string,
     *     description: string,
     *     canonicalUrl: string,
     *     robots: string,
     *     locale: string,
     *     ogType: 'website'|'article',
     *     ogImage: string,
     *     ogImageAlt: string,
     *     twitterCard: 'summary_large_image'|'summary',
     *     publishedTime: string|null,
     *     modifiedTime: string|null,
     *     section: string|null,
     *     prevUrl: string|null,
     *     nextUrl: string|null,
     *     breadcrumbs: list<array{label: string, href: string|null}>,
     *     jsonLd: string
     * }
     */
    public static function make(array $options = []): array
    {
        $school = self::schoolSettings();
        $siteName = trim($school['name'].' – '.$school['subtitle'], ' –');
        $rawTitle = trim($options['title'] ?? 'Startseite');

        $fullTitle = ($rawTitle === '' || $rawTitle === 'Startseite')
            ? $siteName
            : "{$rawTitle} – {$school['name']}";

        $defaultDescription = $school['welcome_text'] !== ''
            ? $school['welcome_text']
            : 'Offizielle Website der Mühlmattenschule (Grundschule Freiburg-Hochdorf mit Benzhausen): Aktuelle Nachrichten, Termine, Elternbriefe, Schulkindbetreuung und Informationen zum Schulleben.';

        $description = self::cleanDescription($options['description'] ?? null, $defaultDescription);

        $canonicalPath = $options['canonicalPath'] ?? request()->getPathInfo();
        $canonicalUrl = self::absoluteUrl($canonicalPath);

        $ogImage = self::absoluteUrl($options['ogImage'] ?? '/images/school/about-main.jpg');
        $ogImageAlt = trim($options['ogImageAlt'] ?? "Schulgebäude der {$school['name']} ({$school['subtitle']})");

        $breadcrumbs = [];
        foreach ($options['breadcrumbs'] ?? [] as $crumb) {
            $breadcrumbs[] = [
                'label' => $crumb['label'],
                'href' => isset($crumb['href']) && $crumb['href'] !== null && $crumb['href'] !== ''
                    ? $crumb['href']
                    : null,
            ];
        }

        $graph = [
            self::schoolOrganizationSchema($school),
            self::websiteSchema($school, $siteName, $defaultDescription),
        ];

        if ($breadcrumbs !== []) {
            $graph[] = self::breadcrumbSchema($breadcrumbs, $canonicalUrl);
        }

        foreach ($options['schemaNodes'] ?? [] as $schemaNode) {
            $graph[] = $schemaNode;
        }

        $jsonLd = (string) json_encode(
            [
                '@context' => 'https://schema.org',
                '@graph' => $graph,
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        return [
            'title' => $rawTitle,
            'fullTitle' => $fullTitle,
            'siteName' => $siteName,
            'description' => $description,
            'canonicalUrl' => $canonicalUrl,
            'robots' => $options['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'locale' => 'de_DE',
            'ogType' => $options['ogType'] ?? 'website',
            'ogImage' => $ogImage,
            'ogImageAlt' => $ogImageAlt,
            'twitterCard' => 'summary_large_image',
            'publishedTime' => $options['publishedTime'] ?? null,
            'modifiedTime' => $options['modifiedTime'] ?? null,
            'section' => $options['section'] ?? null,
            'prevUrl' => $options['prevUrl'] ?? null,
            'nextUrl' => $options['nextUrl'] ?? null,
            'breadcrumbs' => $breadcrumbs,
            'jsonLd' => $jsonLd,
        ];
    }

    /**
     * Bereinigt HTML-Texte und kürzt sie auf eine optimale Meta-Description-Länge (max. 160 Zeichen).
     */
    public static function cleanDescription(?string $text, string $fallback = ''): string
    {
        $source = trim(strip_tags((string) ($text ?? '')));
        $source = (string) preg_replace('/\s+/u', ' ', html_entity_decode($source, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if ($source === '') {
            $source = $fallback;
        }

        return Str::limit($source, 160, '…');
    }

    /**
     * Wandelt einen relativen Pfad oder eine URL in eine absolute URL um.
     */
    public static function absoluteUrl(?string $pathOrUrl): string
    {
        $value = trim((string) ($pathOrUrl ?? '/'));
        if ($value === '') {
            return url('/');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return url($value);
    }

    /**
     * Erzeugt den Schema.org-Knoten für die Grundschule (ElementarySchool / EducationalOrganization).
     *
     * @param  array<string, string>  $school
     * @return array<string, mixed>
     */
    public static function schoolOrganizationSchema(array $school): array
    {
        return [
            '@type' => 'ElementarySchool',
            '@id' => url('/').'#school',
            'name' => $school['name'],
            'alternateName' => "{$school['name']} – {$school['subtitle']}",
            'description' => $school['welcome_text'],
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => url('/images/logo.svg'),
            ],
            'image' => url('/images/school/about-main.jpg'),
            'telephone' => '+49-7665-2232',
            'faxNumber' => '+49-7665-932081',
            'email' => $school['email'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $school['street'],
                'postalCode' => '79108',
                'addressLocality' => 'Freiburg im Breisgau',
                'addressRegion' => 'Baden-Württemberg',
                'addressCountry' => 'DE',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 48.0558,
                'longitude' => 7.7997,
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Wednesday', 'Friday'],
                    'opens' => '07:30',
                    'closes' => '12:00',
                ],
            ],
            'parentOrganization' => [
                '@type' => 'GovernmentOrganization',
                'name' => 'Stadt Freiburg im Breisgau – Amt für Schule und Bildung',
            ],
        ];
    }

    /**
     * Erzeugt den Schema.org-Knoten für die Website.
     *
     * @param  array<string, string>  $school
     * @return array<string, mixed>
     */
    private static function websiteSchema(array $school, string $siteName, string $description): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => $siteName,
            'alternateName' => $school['name'],
            'description' => $description,
            'inLanguage' => 'de-DE',
            'publisher' => [
                '@id' => url('/').'#school',
            ],
        ];
    }

    /**
     * Erzeugt den Schema.org-Knoten für die Brotkrumen-Navigation (BreadcrumbList).
     *
     * @param  list<array{label: string, href: string|null}>  $breadcrumbs
     * @return array<string, mixed>
     */
    private static function breadcrumbSchema(array $breadcrumbs, string $canonicalUrl): array
    {
        $items = [];
        foreach ($breadcrumbs as $index => $crumb) {
            $itemUrl = $crumb['href'] !== null ? self::absoluteUrl($crumb['href']) : $canonicalUrl;
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['label'],
                'item' => $itemUrl,
            ];
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $canonicalUrl.'#breadcrumb',
            'itemListElement' => $items,
        ];
    }
}
