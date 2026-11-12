<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AnnouncementLevel;
use App\Enums\PersonGroup;
use App\Enums\PublishStatus;
use App\Enums\UserRole;
use App\Models\Album;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Download;
use App\Models\Event;
use App\Models\Page;
use App\Models\Person;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

class DatabaseSeeder extends Seeder
{
    /**
     * Befüllt die Datenbank 1:1 ungekürzt mit sämtlichen 179 Original-Beiträgen,
     * allen Rubriken, 22 Schulseiten, Fotoalben, PDF-Downloads und dem Kollegium von muehlmattenschule.de.
     */
    public function run(): void
    {
        $isProd = app()->isProduction();

        User::query()->updateOrCreate(
            ['email' => 'admin@muehlmattenschule.de'],
            [
                'name' => 'Systemadministration',
                'role' => UserRole::Admin,
                'password' => Hash::make($isProd ? (string) env('INITIAL_ADMIN_PASSWORD', Str::password(24)) : 'Muehlmatten!2026'),
                'email_verified_at' => now(),
            ],
        );

        $mmsAdmin = User::query()->updateOrCreate(
            ['email' => 'mmsadmin@muehlmattenschule.de'],
            [
                'name' => 'mmsadmin',
                'role' => UserRole::Admin,
                'password' => Hash::make($isProd ? (string) env('INITIAL_ADMIN_PASSWORD', Str::password(24)) : 'Muehlmatten!2026'),
                'email_verified_at' => now(),
            ],
        );

        $schulleitung = User::query()->updateOrCreate(
            ['email' => 'schulleitung@muehlmattenschule.de'],
            [
                'name' => 'Diana Blum (Schulleitung)',
                'role' => UserRole::Schulleitung,
                'password' => Hash::make($isProd ? (string) env('INITIAL_LEADERSHIP_PASSWORD', Str::password(24)) : 'Schulleitung!2026'),
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'sekretariat@muehlmattenschule.de'],
            [
                'name' => 'Mechthild Vennebusch (Sekretariat)',
                'role' => UserRole::Sekretariat,
                'password' => Hash::make($isProd ? (string) env('INITIAL_OFFICE_PASSWORD', Str::password(24)) : 'Sekretariat!2026'),
                'email_verified_at' => now(),
            ],
        );

        $lehrkraft2b = User::query()->updateOrCreate(
            ['email' => 'klasse2a@muehlmattenschule.de'],
            [
                'name' => 'Frau Jakoby (Klasse 2b)',
                'role' => UserRole::Lehrkraft,
                'class_name' => 'Klasse 2b',
                'password' => Hash::make($isProd ? (string) env('INITIAL_TEACHER_PASSWORD', Str::password(24)) : 'Lehrkraft!2026'),
                'email_verified_at' => now(),
            ],
        );

        Setting::setValue('school', [
            'name' => 'Mühlmattenschule',
            'subtitle' => 'Grundschule Freiburg-Hochdorf',
            'street' => 'Zu den Mühlmatten 8',
            'zip_city' => '79108 Freiburg im Breisgau',
            'phone' => '07665 2232',
            'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
            'office_hours' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
            'welcome_title' => 'Willkommen an der Mühlmattenschule',
            'welcome_text' => 'Die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen – ein Ort zum Lernen, Wachsen und Entdecken.',
        ]);

        // --- 1:1 Original-Rubriken von muehlmattenschule.de ---
        $categoriesConfig = [
            'alle-beitraege' => ['name' => 'Alle Beiträge', 'color' => '#029786'],
            'elterninfos' => ['name' => 'Elterninfos', 'color' => '#007f70'],
            'ferienkalender' => ['name' => 'Ferienkalender', 'color' => '#00695c'],
            'klasse-4a' => ['name' => 'Klasse 4a', 'color' => '#0d9488'],
            'klasse-4b' => ['name' => 'Klasse 4b', 'color' => '#0f766e'],
            'konzept-didaktik' => ['name' => 'Konzept & Didaktik', 'color' => '#00544a'],
            'schulfeste-feiern' => ['name' => 'Schulfeste & Feiern', 'color' => '#f59e0b'],
            'schulleben' => ['name' => 'Schulleben', 'color' => '#029786'],
            'sport' => ['name' => 'Sport & Wettbewerbe', 'color' => '#13b5a2'],
        ];

        Category::query()->whereNotIn('slug', array_keys($categoriesConfig))->delete();

        /** @var array<string, Category> $categoriesBySlug */
        $categoriesBySlug = [];
        foreach ($categoriesConfig as $slug => $meta) {
            $categoriesBySlug[$slug] = Category::query()->updateOrCreate(
                ['slug' => $slug],
                $meta,
            );
        }

        Announcement::query()->updateOrCreate(
            ['text' => 'Herzlich willkommen auf dem neuen Schulportal der Mühlmattenschule Freiburg-Hochdorf!'],
            [
                'level' => AnnouncementLevel::Info,
                'link_url' => '/unsere-schule/schulportrait',
                'link_label' => 'Mehr über unsere Schule',
                'visible_from' => now()->subDay(),
                'visible_until' => now()->addMonths(2),
                'is_active' => true,
            ],
        );

        $crosslaufUrls = $this->buildOddRangeUrls('crosslauf-full-', 43);
        $spielmobilUrls = $this->buildRangeUrls('spielmobil-full-', 13);
        $slowmobilUrls = [
            '/images/school/slowmobil-full-01.jpg',
            ...array_map(fn (int $i): string => sprintf('/images/school/slowmobil-full-%02d.jpg', $i), range(3, 12)),
        ];
        $tanzenUrls = $this->buildRangeUrls('tanzen-full-', 3);
        $fruehstueckUrls = $this->buildOddRangeUrls('fruehstueck-full-', 19);
        $adventUrls = $this->buildOddRangeUrls('advent-full-', 11);
        $bastelnUrls = $this->buildOddRangeUrls('basteln-full-', 25);
        $plaetzchenUrls = $this->buildRangeUrls('plaetzchen-full-', 5);
        $jubilaeum25Urls = $this->buildRangeUrls('schuljubilaeum-25j-', 7);
        $muehlmattentreffenUrls = $this->buildRangeUrls('muehlmattentreffen-', 3);
        $blumJubilaeumUrls = [
            '/images/school/blum-jubilaeum-01.jpg',
            '/images/school/blum-jubilaeum-03.jpg',
        ];

        // --- 7 Reale Fotoalben mit allen Originalfotos von muehlmattenschule.de ---
        $albumCrosslauf = Album::query()->updateOrCreate(
            ['slug' => 'crosslauf-der-muehlmattenschule'],
            [
                'title' => 'Bilder vom Crosslauf & Sponsorenlauf',
                'event_date' => '2023-06-24',
                'description' => 'Unser großes Schulsportfest für die ganze Familie, veranstaltet vom Förderverein in Kooperation mit der Mühlmattenschule.',
                'gallery_images' => $crosslaufUrls,
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => '2023-07-14 08:30:00',
            ],
        );
        $this->attachSingleMedia($albumCrosslauf, 'cover', 'crosslauf-1.jpg');

        $albumBewegung = Album::query()->updateOrCreate(
            ['slug' => 'bewegungs-und-gesundheitstage'],
            [
                'title' => 'Bewegungs- & Gesundheitstage: Spielmobil, Slow-Mobil & Tanzen',
                'event_date' => '2023-10-24',
                'description' => 'Kochen im Slow-Mobil, Spielstationen mit dem Spielmobil auf dem Pausenhof und Tanzaktion mit der Tanzschule Gutmann.',
                'gallery_images' => [...$spielmobilUrls, ...$slowmobilUrls, ...$tanzenUrls],
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => '2023-10-24 14:00:00',
            ],
        );
        $this->attachSingleMedia($albumBewegung, 'cover', 'slowmobil-1.jpg');

        $albumErnaehrung = Album::query()->updateOrCreate(
            ['slug' => 'gesundes-fruehstueck-obstsalat-und-brotgesichter'],
            [
                'title' => 'Gesunde Ernährung in den 2. Klassen: Frühstück, Obstsalat & Brotgesichter',
                'class_name' => 'Klasse 2a & 2b',
                'event_date' => '2023-10-20',
                'description' => 'Die 2. Klassen bereiten ein gesundes Frühstück, frischen Obstsalat für Kater Cook und lustige Brotgesichter zu.',
                'gallery_images' => [
                    ...$fruehstueckUrls,
                    '/images/school/gesunde-ernaehrung-fuer-kater-cook-obstsalat-in-der-2b-01.jpg',
                    '/images/school/gesunde-ernaehrung-fuer-kater-cook-obstsalat-in-der-2b-02.jpg',
                    '/images/school/gesunde-ernaehrung-fuer-kater-cook-obstsalat-in-der-2b-03.jpg',
                    '/images/school/lustige-brotgesichter-in-der-2b-01.jpg',
                    '/images/school/lustige-brotgesichter-in-der-2b-02.jpg',
                    '/images/school/lustige-brotgesichter-in-der-2b-03.jpg',
                ],
                'author_id' => $lehrkraft2b->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => '2023-10-20 12:00:00',
            ],
        );
        $this->attachSingleMedia($albumErnaehrung, 'cover', 'fruehstueck-2kl-1.jpg');

        $albumAdvent = Album::query()->updateOrCreate(
            ['slug' => 'adventszeit-und-basteln'],
            [
                'title' => 'Adventssingen, Adventsbasteln & Plätzchenverkauf',
                'event_date' => '2023-12-19',
                'description' => 'Weihnachtliche Stimmung, Adventsbasteln in allen Klassen und der Plätzchenverkauf auf dem Hochdorfer Markt.',
                'gallery_images' => [...$adventUrls, ...$bastelnUrls, ...$plaetzchenUrls],
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => '2023-12-19 16:00:00',
            ],
        );
        $this->attachSingleMedia($albumAdvent, 'cover', 'news-advent-1.jpg');

        $albumCampus = Album::query()->updateOrCreate(
            ['slug' => 'unser-schulgebaeude-und-campus'],
            [
                'title' => 'Unser Schulgebäude & Pausenhof im Grünen',
                'event_date' => '2023-09-15',
                'description' => 'Helle Flure mit Holzelementen, freundliche Klassenzimmer und unser weitläufiges Schulgelände in Freiburg-Hochdorf.',
                'gallery_images' => [
                    '/images/school/about-main.jpg',
                    '/images/school/gebaeude-wide.jpg',
                    '/images/school/gebaeude-full.jpg',
                    '/images/school/slider-schulgebaeude1.jpg',
                    '/images/school/unsere-schule-innen.jpg',
                    '/images/school/unsere-schule-umgegend.jpg',
                    '/images/school/schule-bei-abend.jpg',
                    '/images/school/slider-schuleinnen.jpg',
                ],
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => '2023-09-15 10:00:00',
            ],
        );
        $this->attachSingleMedia($albumCampus, 'cover', 'about-main.jpg');

        $albumRosedale = Album::query()->updateOrCreate(
            ['slug' => 'patenschaft-kinderheim-rosedale-simbabwe'],
            [
                'title' => 'Patenschaft Midlands Children Home Rosedale (Simbabwe)',
                'event_date' => '2023-05-10',
                'description' => 'Seit 1995 pflegt die Mühlmattenschule eine lebendige Patenschaft mit dem Kinderheim Rosedale in Gweru, Simbabwe.',
                'gallery_images' => [
                    '/images/school/rosedale-kinder.jpg',
                    '/images/school/trommeln-rosedale.jpg',
                    '/images/school/rosedale-kinder-afrika.jpg',
                    '/images/school/rosedale-trommeln.png',
                ],
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => '2023-05-10 10:00:00',
            ],
        );
        $this->attachSingleMedia($albumRosedale, 'cover', 'rosedale-kinder.jpg');

        $albumJubilaeum = Album::query()->updateOrCreate(
            ['slug' => '25-jahre-muehlmattenschule-und-schulfeste'],
            [
                'title' => '25 Jahre Mühlmattenschule, Mühlmattentreffen & Jubiläen',
                'event_date' => '2024-04-15',
                'description' => 'Impressionen vom 25-jährigen Schuljubiläum, dem 1. Mühlmattentreffen in der Aula und dem Dienstjubiläum von Rektorin Diana Blum.',
                'gallery_images' => [...$jubilaeum25Urls, ...$muehlmattentreffenUrls, ...$blumJubilaeumUrls],
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => '2024-04-15 10:00:00',
            ],
        );
        $this->attachSingleMedia($albumJubilaeum, 'cover', 'schuljubilaeum-25j-01.jpg');

        $albumsByKey = [
            'crosslauf-impressionen' => $albumCrosslauf->id,
            'adventszeit-weihnachtsbasteln' => $albumAdvent->id,
            'bewegungs-und-gesundheitstage' => $albumBewegung->id,
            'gesunde-ernaehrung-klasse-2' => $albumErnaehrung->id,
            'schulgebaeude-campus-hochdorf' => $albumCampus->id,
        ];

        // --- Sämtliche 179 Original-Beiträge von muehlmattenschule.de (1:1 ungekürzt mit Originaldatum) ---
        $verbatimPostsPath = __DIR__.'/data/wp_posts_verbatim.json';
        /** @var array<int, array<string, mixed>> $verbatimPosts */
        $verbatimPosts = is_file($verbatimPostsPath)
            ? (array) json_decode((string) file_get_contents($verbatimPostsPath), true)
            : [];

        $validSlugs = array_column($verbatimPosts, 'slug');
        if ($validSlugs !== []) {
            Post::query()->whereNotIn('slug', $validSlugs)->delete();
        }
        DB::table('category_post')->delete();

        $pivotRows = [];
        foreach ($verbatimPosts as $idx => $postData) {
            $primarySlug = (string) ($postData['primary_category'] ?? 'schulleben');
            $primaryCategory = $categoriesBySlug[$primarySlug] ?? $categoriesBySlug['schulleben'];
            $albumKey = isset($postData['album_slug']) && is_string($postData['album_slug']) ? $postData['album_slug'] : null;
            $albumId = $albumKey !== null && isset($albumsByKey[$albumKey]) ? $albumsByKey[$albumKey] : null;
            $galleryImgs = isset($postData['gallery_images']) && is_array($postData['gallery_images']) && $postData['gallery_images'] !== []
                ? $postData['gallery_images']
                : null;

            $post = Post::withoutEvents(function () use ($postData, $primaryCategory, $albumId, $galleryImgs, $mmsAdmin): Post {
                return Post::query()->updateOrCreate(
                    ['slug' => (string) $postData['slug']],
                    [
                        'title' => (string) $postData['title'],
                        'excerpt' => (string) ($postData['excerpt'] ?? ''),
                        'body' => (string) $postData['body'],
                        'category_id' => $primaryCategory->id,
                        'class_name' => isset($postData['class_name']) ? (string) $postData['class_name'] : null,
                        'album_id' => $albumId,
                        'gallery_images' => $galleryImgs,
                        'author_id' => $mmsAdmin->id,
                        'is_featured' => (bool) ($postData['is_featured'] ?? false),
                        'status' => PublishStatus::Published,
                        'published_at' => (string) $postData['published_at'],
                    ],
                );
            });

            // Attach Spatie cover media for featured/recent posts
            if ($idx < 14 && ! empty($postData['cover_file']) && is_string($postData['cover_file'])) {
                $this->attachSingleMedia($post, 'cover', $postData['cover_file']);
            }

            /** @var array<int, string> $catSlugs */
            $catSlugs = is_array($postData['categories'] ?? null) ? $postData['categories'] : [$primarySlug];
            foreach ($catSlugs as $catSlug) {
                if (isset($categoriesBySlug[$catSlug])) {
                    $pivotRows[] = [
                        'category_id' => $categoriesBySlug[$catSlug]->id,
                        'post_id' => $post->id,
                    ];
                }
            }
        }

        if ($pivotRows !== []) {
            foreach (array_chunk($pivotRows, 200) as $chunk) {
                DB::table('category_post')->insertOrIgnore($chunk);
            }
        }

        // --- Reale PDF-Downloads von muehlmattenschule.de ---
        $downloadsData = [
            [
                'title' => 'Ferienplan Schuljahr 2024/25',
                'category' => 'Ferienkalender',
                'description' => 'Übersicht aller Ferientage, beweglichen Ferientage und Feiertage im Schuljahr 2024/25 an der Mühlmattenschule.',
                'sort_order' => 1,
                'file' => 'Ferienplan-2024-25.pdf',
            ],
            [
                'title' => 'Beitrittserklärung Förderverein der Mühlmattenschule e.V.',
                'category' => 'Förderverein & Formulare',
                'description' => 'Aufnahmeantrag für den Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V.',
                'sort_order' => 2,
                'file' => 'Beitrittserklaerung-Foerderverein-MMS.pdf',
            ],
            [
                'title' => 'Elterninfo zur Schul-App „Stay Informed"',
                'category' => 'Elternbriefe & Info',
                'description' => 'Wichtige Informationen zum Erhalt digitaler Elternbriefe über unsere Schul-App.',
                'sort_order' => 3,
                'file' => 'Stay-Informed-Elterninfo.pdf',
            ],
            [
                'title' => 'Anleitung & Registrierung Schul-App',
                'category' => 'Elternbriefe & Info',
                'description' => 'Schritt-für-Schritt-Anleitung zur Installation und Anmeldung in der Schul-App der Mühlmattenschule.',
                'sort_order' => 4,
                'file' => 'Schul-App-Infoblatt.pdf',
            ],
            [
                'title' => 'Elternflyer EU-Schulprogramm (Obst & Gemüse)',
                'category' => 'Schulleben & Ernährung',
                'description' => 'Informationen des Ministeriums zum EU-Schulprogramm für frisches Obst und Gemüse an Grundschulen.',
                'sort_order' => 5,
                'file' => 'Elternflyer-EU-Schulprogramm.pdf',
            ],
            [
                'title' => 'Schulwegeplan Hochdorf & Benzhausen (Karte)',
                'category' => 'Schulweg & Sicherheit',
                'description' => 'Offizieller Schulwegeplan mit empfohlenen Fußwegen, sicheren Querungshilfen und Ampeln für Hochdorf, Benzhausen und das Gewerbegebiet.',
                'sort_order' => 6,
                'file' => 'Schulwegeplan-Hochdorf-Karte.pdf',
            ],
            [
                'title' => 'Schulwegeplan Hochdorf (Wichtige Regeln & Gefahrenstellen)',
                'category' => 'Schulweg & Sicherheit',
                'description' => 'Erläuterungen zum sicheren Schulweg, Haltestellenverhalten und Hinweise für Eltern zur Hol- und Bringzone.',
                'sort_order' => 7,
                'file' => 'Schulwegeplan-Hochdorf-Regeln.pdf',
            ],
            [
                'title' => 'Leitfaden & Geschäftsordnung für Gesamtelternbeiratssitzungen',
                'category' => 'Elternbeirat & Formulare',
                'description' => 'Organisatorischer Leitfaden für die Durchführung der Elternbeiratssitzungen an der Mühlmattenschule.',
                'sort_order' => 8,
                'file' => 'Gesamtelternbeiratssitzung-Leitfaden.pdf',
            ],
            [
                'title' => 'Kurzprotokoll Elternbeiratssitzung',
                'category' => 'Elternbeirat & Formulare',
                'description' => 'Muster- und Kurzprotokoll aus den Sitzungen des Elternbeirats.',
                'sort_order' => 9,
                'file' => 'Elternbeiratssitzung-Kurzprotokoll.pdf',
            ],
        ];

        foreach ($downloadsData as $dlItem) {
            $pdfFile = $dlItem['file'];
            unset($dlItem['file']);

            $download = Download::query()->updateOrCreate(
                ['title' => $dlItem['title']],
                [
                    ...$dlItem,
                    'is_published' => true,
                ],
            );

            $pdfPath = public_path('files/school/'.$pdfFile);
            if (is_file($pdfPath)) {
                if ($download->getFirstMedia('file') !== null) {
                    $download->clearMediaCollection('file');
                }
                $download->addMedia($pdfPath)
                    ->preservingOriginal()
                    ->toMediaCollection('file');
            }
        }

        // --- Termine ---
        Event::query()->updateOrCreate(
            ['title' => 'Laternen- & St.-Martins-Treffen auf dem Schulhof'],
            [
                'starts_at' => now()->addDays(7)->setTime(17, 0),
                'ends_at' => now()->addDays(7)->setTime(19, 30),
                'all_day' => false,
                'location' => 'Schulhof der Mühlmattenschule',
                'class_name' => 'Alle Klassen',
                'category' => 'Schulfest',
                'description' => 'Gemeinsames Singen, Laternenausstellung und Kinderpunsch mit dem Förderverein.',
                'is_published' => true,
            ],
        );

        Event::query()->updateOrCreate(
            ['title' => 'Elternbeiratssitzung'],
            [
                'starts_at' => now()->addDays(14)->setTime(19, 30),
                'ends_at' => now()->addDays(14)->setTime(21, 0),
                'all_day' => false,
                'location' => 'Musikraum (Erdgeschoss)',
                'class_name' => 'Elternbeirat',
                'category' => 'Gremien',
                'description' => 'Austausch der gewählten Elternvertreterinnen und Elternvertreter mit der Schulleitung.',
                'is_published' => true,
            ],
        );

        Event::query()->updateOrCreate(
            ['title' => 'Adventsbasteln in allen Klassen'],
            [
                'starts_at' => now()->addDays(25)->startOfDay(),
                'ends_at' => null,
                'all_day' => true,
                'location' => 'Klassenzimmer',
                'class_name' => 'Klasse 1–4',
                'category' => 'Projekttag',
                'description' => 'Vormittags gestalten die Kinder gemeinsam mit helfenden Eltern den winterlichen Schulhausschmuck.',
                'is_published' => true,
            ],
        );

        Event::query()->updateOrCreate(
            ['title' => 'Bibfit-Besuch der 1. und 2. Klassen'],
            [
                'starts_at' => now()->addDays(32)->setTime(9, 0),
                'ends_at' => now()->addDays(32)->setTime(11, 30),
                'all_day' => false,
                'location' => 'Bücherei St. Martin Hochdorf',
                'class_name' => 'Klasse 1 & 2',
                'category' => 'Exkursion',
                'description' => 'Einführung in die Bücherei im Rahmen des Lesekompass-Programms.',
                'is_published' => true,
            ],
        );

        Event::query()->updateOrCreate(
            ['title' => 'Crosslauf & Sponsorenlauf der Mühlmattenschule'],
            [
                'starts_at' => now()->addDays(45)->setTime(10, 0),
                'ends_at' => now()->addDays(45)->setTime(14, 30),
                'all_day' => false,
                'location' => 'Rundkurs am Mühlbach & Schulhof',
                'class_name' => 'Alle Klassen',
                'category' => 'Sportfest',
                'description' => 'Traditioneller Schülerlauf, Sponsorenlauf und Familienfest mit großer Tombola des Fördervereins.',
                'is_published' => true,
            ],
        );

        // --- Komplette reale Personenliste von muehlmattenschule.de ---
        $staffList = [
            [
                'name' => 'Diana Blum',
                'role_title' => 'Rektorin / Schulleiterin',
                'class_name' => null,
                'email' => 'schulleitung.mmsvn@freiburger-schulen.bwl.de',
                'group' => PersonGroup::Schulleitung,
                'sort_order' => 1,
                'portrait' => 'teacher-blum.jpg',
            ],
            [
                'name' => 'Sabine Caputo',
                'role_title' => 'Konrektorin',
                'class_name' => null,
                'email' => 'Konrektorat.MMSVN@freiburger-schulen.bwl.de',
                'group' => PersonGroup::Schulleitung,
                'sort_order' => 2,
                'portrait' => 'teacher-caputo.jpg',
            ],
            [
                'name' => 'Mechthild Vennebusch',
                'role_title' => 'Schulsekretärin',
                'class_name' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
                'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
                'group' => PersonGroup::Verwaltung,
                'sort_order' => 3,
                'portrait' => 'teacher-vennebusch.jpg',
            ],
            [
                'name' => 'Frau Bett',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 1a',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 10,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Götten',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 1b',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 11,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Eder',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 2a',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 12,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Jakoby',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 2b',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 13,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Bürger',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 3a',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 14,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Piess',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 3b',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 15,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Huber',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 3c',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 16,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Garrido',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 4a',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 17,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Sterzik',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 4b',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 18,
                'portrait' => null,
            ],
            [
                'name' => 'Herr Früh',
                'role_title' => 'Klassenlehrer & BiSS-Ansprechperson',
                'class_name' => 'Klasse 4c',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 19,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Pfattner',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 20,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Wastell',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 21,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Neumann',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 22,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Marquardt',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 23,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Borgas',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 24,
                'portrait' => null,
            ],
            [
                'name' => 'Frau Dingler',
                'role_title' => 'Sonderpädagogin',
                'class_name' => 'Außenklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 25,
                'portrait' => null,
            ],
            [
                'name' => 'Philipp Markert',
                'role_title' => 'Schulsozialarbeiter (Caritasverband Freiburg-Stadt e.V.)',
                'class_name' => 'Tel.: 07665-9328305 · Mobil: 0162-2092981',
                'email' => 'philipp.markert@caritas-freiburg.de',
                'group' => PersonGroup::Sozialarbeit,
                'sort_order' => 30,
                'portrait' => 'ssa-team-markert-ponzelar.jpg',
            ],
            [
                'name' => 'Nina Ponzelar',
                'role_title' => 'Schulsozialarbeiterin (Caritasverband Freiburg-Stadt e.V.)',
                'class_name' => 'Mobil: 0160-97226351',
                'email' => 'nina.ponzelar@caritas-freiburg.de',
                'group' => PersonGroup::Sozialarbeit,
                'sort_order' => 31,
                'portrait' => 'ssa-team-markert-ponzelar.jpg',
            ],
            [
                'name' => 'Lisa Jortzik',
                'role_title' => 'Leiterin der Schulkindbetreuung (Caritas)',
                'class_name' => 'Mo–Fr 7:30 – 17:00 Uhr',
                'email' => 'schulkindbetreuung-muehlmattenschule@caritas-freiburg.de',
                'group' => PersonGroup::Betreuung,
                'sort_order' => 40,
                'portrait' => null,
            ],
        ];

        $validNames = array_column($staffList, 'name');
        Person::query()->whereNotIn('name', $validNames)->delete();

        foreach ($staffList as $staff) {
            $portraitFile = $staff['portrait'];
            unset($staff['portrait']);

            $person = Person::query()->updateOrCreate(
                ['name' => $staff['name']],
                [
                    ...$staff,
                    'is_active' => true,
                ],
            );

            if ($portraitFile !== null) {
                $this->attachSingleMedia($person, 'portrait', $portraitFile);
            }
        }

        // --- Sämtliche 22 Schulseiten 1:1 ungekürzt mit Originaltexten von muehlmattenschule.de ---
        $wpPagesPath = __DIR__.'/data/wp_pages_verbatim.json';
        /** @var array<string, array{title: string, body: string}> $wpPages */
        $wpPages = is_file($wpPagesPath)
            ? (array) json_decode((string) file_get_contents($wpPagesPath), true)
            : [];

        $pages = [
            // =========================================================================
            // RUBRIK 1: UNSERE SCHULE
            // =========================================================================
            [
                'title' => 'Schulportrait',
                'slug' => 'schulportrait',
                'section' => 'unsere-schule',
                'menu_group' => 'Profil & Menschen',
                'menu_order' => 1,
                'summary' => 'Geschichte, Lage am Naturschutzgebiet Mühlmatten, Holzarchitektur und Hochschulpartnerschaft der Mühlmattenschule in Freiburg-Hochdorf.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/gebaeude-full.jpg',
                            'caption' => 'Das Schulgebäude der Mühlmattenschule in Freiburg-Hochdorf.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulportrait',
                            'body' => $wpPages['schulportrait']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulleitung & Verwaltung',
                'slug' => 'schulleitung-und-verwaltung',
                'section' => 'unsere-schule',
                'menu_group' => 'Profil & Menschen',
                'menu_order' => 2,
                'summary' => 'Schulleiterin Diana Blum, Konrektorin Sabine Caputo und Schulsekretärin Mechthild Vennebusch.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulleitung und Verwaltung',
                            'body' => $wpPages['schulleitung-und-verwaltung']['body'] ?? '',
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Schulleitung',
                            'group' => PersonGroup::Schulleitung->value,
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Schulsekretariat',
                            'group' => PersonGroup::Verwaltung->value,
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Kollegium',
                'slug' => 'kollegium',
                'section' => 'unsere-schule',
                'menu_group' => 'Profil & Menschen',
                'menu_order' => 3,
                'summary' => 'Das pädagogische Team der Mühlmattenschule Freiburg-Hochdorf.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Kollegium',
                            'body' => $wpPages['kollegium']['body'] ?? '',
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Schulleitung & Sekretariat',
                            'group' => PersonGroup::Schulleitung->value,
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Klassen- und Fachlehrkräfte',
                            'group' => PersonGroup::Kollegium->value,
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Schulsozialarbeit',
                            'group' => PersonGroup::Sozialarbeit->value,
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unsere Klassen',
                'slug' => 'unsere-klassen',
                'section' => 'unsere-schule',
                'menu_group' => 'Profil & Menschen',
                'menu_order' => 4,
                'summary' => 'Neun Grundschulklassen und eine kooperative Außenklasse – eine starke Schulgemeinschaft.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Unsere Klassen',
                            'body' => $wpPages['unsere-klassen']['body'] ?? '',
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Unsere Klassen- und Fachlehrkräfte im Überblick',
                            'group' => PersonGroup::Kollegium->value,
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulsozialarbeit',
                'slug' => 'schulsozialarbeit',
                'section' => 'unsere-schule',
                'menu_group' => 'Profil & Menschen',
                'menu_order' => 5,
                'summary' => 'Philipp Markert und Nina Ponzelar (Caritasverband Freiburg-Stadt e.V.) – Beratung, Klassenprojekte und soziales Lernen.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/ssa-team-markert-ponzelar.jpg',
                            'caption' => 'Philipp Markert und Nina Ponzelar – Schulsozialarbeit an der Mühlmattenschule (Caritasverband Freiburg-Stadt e.V.).',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulsozialarbeit an der Mühlmattenschule',
                            'body' => $wpPages['schulsozialarbeit']['body'] ?? '',
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Team & Kontakt der Schulsozialarbeit',
                            'group' => PersonGroup::Sozialarbeit->value,
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Arbeitsfelder der Schulsozialarbeit an der Mühlmattenschule',
                            'image_1_url' => '/images/school/ssa-wortwolke.jpg',
                            'image_1_caption' => 'Wofür Schulsozialarbeit an der Mühlmattenschule steht.',
                            'image_2_url' => '/images/school/ssa-klassen-und-gruppen.png',
                            'image_2_caption' => 'Soziales Lernen in Klassen- und Gruppenangeboten.',
                            'image_3_url' => '/images/school/ssa-beratung.png',
                            'image_3_caption' => 'Vertrauliche Beratung für Kinder, Eltern und Lehrkräfte.',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Elternbeirat',
                'slug' => 'elternbeirat',
                'section' => 'unsere-schule',
                'menu_group' => 'Gremien & Partner',
                'menu_order' => 6,
                'summary' => 'Bindeglied zwischen Elternschaft und Schule nach § 57 Schulgesetz BW.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Elternbeirat',
                            'body' => $wpPages['elternbeirat']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Aufgaben des Elternbeirats',
                'slug' => 'aufgaben-des-elternbeirats',
                'section' => 'unsere-schule',
                'menu_group' => 'Gremien & Partner',
                'menu_order' => 7,
                'summary' => 'Beratende Funktion, Jour Fixe mit der Schulleitung, Schulkonferenz und städtischer Gesamtelternbeirat.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Aufgaben des Elternbeirats',
                            'body' => $wpPages['aufgaben-des-elternbeirats']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Aktivitäten des Elternbeirats',
                'slug' => 'aktivitaeten-des-elternbeirats',
                'section' => 'unsere-schule',
                'menu_group' => 'Gremien & Partner',
                'menu_order' => 8,
                'summary' => 'Musiknachmittag, Fastnacht, Nikolauspäckchen, Plätzchenverkauf und Einschulungscafé.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Aktivitäten des Elternbeirats',
                            'body' => $wpPages['aktivitaeten']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Förderverein',
                'slug' => 'foerderverein',
                'section' => 'unsere-schule',
                'menu_group' => 'Gremien & Partner',
                'menu_order' => 9,
                'summary' => 'Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V. – seit 1996 ehrenamtlich engagiert für unsere Schulkinder.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V.',
                            'body' => $wpPages['foerderverein']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Crosslauf & Sponsorenlauf',
                'slug' => 'crosslauf',
                'section' => 'unsere-schule',
                'menu_group' => 'Gremien & Partner',
                'menu_order' => 10,
                'summary' => 'Das traditionelle Schulsportfest für die ganze Familie – seit 1998 alljährlich veranstaltet vom Förderverein in Kooperation mit der Mühlmattenschule.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Crosslauf & Sponsorenlauf',
                            'body' => $wpPages['crosslauf']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Hochschulpartnerschule',
                'slug' => 'hochschulpartnerschule',
                'section' => 'unsere-schule',
                'menu_group' => 'Gremien & Partner',
                'menu_order' => 11,
                'summary' => 'Offizielle Kooperationsschule im Praxiskolleg des Freiburg Advanced Center of Education (FACE) mit PH und Universität Freiburg.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Hochschulpartnerschule',
                            'body' => $wpPages['hochschulpartnerschule']['body'] ?? '',
                        ],
                    ],
                ],
            ],

            // =========================================================================
            // RUBRIK 2: KONZEPTE & PROJEKTE
            // =========================================================================
            [
                'title' => 'Unser Leitbild',
                'slug' => 'leitbild',
                'section' => 'konzepte-projekte',
                'menu_group' => 'Pädagogisches Konzept',
                'menu_order' => 1,
                'summary' => 'Das gemeinsam von Eltern und Lehrkräften in der Schulkonferenz entwickelte Leitbild der Mühlmattenschule.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Leitbild',
                            'body' => $wpPages['leitbild-2']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Leitfaden „So ist es bei uns"',
                'slug' => 'so-ist-es-bei-uns',
                'section' => 'konzepte-projekte',
                'menu_group' => 'Pädagogisches Konzept',
                'menu_order' => 2,
                'summary' => 'Unser zweiseitig lesbares Vereinbarungsheft für Kinder, Eltern und Lehrkräfte für ein friedliches Miteinander.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Leitfaden „So ist es bei uns"',
                            'body' => $wpPages['so-ist-es-bei-uns']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Klassenrat',
                'slug' => 'klassenrat',
                'section' => 'konzepte-projekte',
                'menu_group' => 'Pädagogisches Konzept',
                'menu_order' => 3,
                'summary' => 'Wöchentliche Gesprächsrunde in allen Klassen für soziales Lernen, Gewaltprävention und gelebte Demokratie.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Klassenrat',
                            'body' => $wpPages['klassenrat']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Patenschaft Simbabwe (Rosedale)',
                'slug' => 'patenschaft-simbabwe',
                'section' => 'konzepte-projekte',
                'menu_group' => 'Projekte & Förderung',
                'menu_order' => 4,
                'summary' => 'Unsere seit 1995 bestehende Schulpatenschaft mit dem Kinderheim „Midlands Children Home Rosedale" in Gweru/Simbabwe.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Patenschaft Simbabwe',
                            'body' => $wpPages['patenschaft-simbabwe']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'BiSS-Transfer & Inklusion',
                'slug' => 'biss-transfer-und-inklusion',
                'section' => 'konzepte-projekte',
                'menu_group' => 'Projekte & Förderung',
                'menu_order' => 5,
                'summary' => 'Systematische Sprach- und Leseförderung (BiSS-Transfer) sowie kooperative inklusive Beschulung an der Mühlmattenschule.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/biss-transfer.jpg',
                            'caption' => 'BiSS-Transfer: Gezielte Stärkung der Lese- und Schreibkompetenzen.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Transfer von Sprachbildung, Lese- und Schreibförderung (BiSS-Transfer)',
                            'body' => '<p>Mit Beginn des Schuljahres 2023/2024 gehört die Mühlmattenschule zu den insgesamt 2033 Grundschulen in Baden-Württemberg, die an der Initiative <strong>BiSS-Transfer</strong> teilnehmen.</p>'
                                .'<p>BiSS-Transfer ist eine gemeinsame Initiative des Bundesministeriums für Bildung und Forschung (BMBF) und der Kultusministerkonferenz (KMK) zum Transfer von Sprachbildung, Lese- und Schreibförderung in Schulen und Kitas (<a href="https://www.biss-sprachbildung.de" target="_blank" rel="noopener noreferrer">www.biss-sprachbildung.de</a>). Ziel in Baden-Württemberg ist die <strong>Stärkung der Basiskompetenzen der Schülerinnen und Schüler im Bereich des Lesens</strong>. Im Mittelpunkt steht die Förderung der Lesefertigkeiten (Lesetechnik, Leseflüssigkeit) sowie der Leseverstehensstrategien.</p>'
                                .'<p>Auf der Basis eines einheitlichen und systematischen Lesecurriculums setzen die BiSS-Grundschulen Schritt für Schritt nachweislich lernförderliche Elemente im Leseunterricht um, wie das Training der Leseflüssigkeit mithilfe von Lautlesetandems und den Einsatz ausgewählter Lesestrategien. Damit die Umsetzung in allen Klassenstufen gut gelingt, hat jede BiSS-Grundschule eine eigene Projektgruppe eingerichtet, die das Kollegium im Rahmen der Implementierung begleitet. Unsere BiSS-Ansprechperson ist <strong>Raimund Früh</strong>.</p>'
                                .'<p>Außerdem gehört unsere Schule dem <strong>BiSS-Verbund Freiburg</strong> an. Dieser Verbund dient der Vernetzung, dem Austausch und der gegenseitigen Unterstützung aller teilnehmenden Schulen vor Ort (Informationen zu den Verbünden unter: <a href="http://www.biss-sprachbildung.de/biss-vor-ort" target="_blank" rel="noopener noreferrer">www.biss-sprachbildung.de/biss-vor-ort</a>).</p>'
                                .'<h3>Inklusives Beschulungsmodell (Kooperative Organisationsform)</h3>'
                                .'<p>Im Schuljahr 2018/2019 begann für 23 Schülerinnen und Schüler die Schulzeit als „Chamäleonklasse". Mit 18 Schülern in der Grundschulklasse der Mühlmattenschule (grüne Chamäleons) und 5 Schülern der Richard-Mittermaier-Schule (gelbe Chamäleons). Es ist bereits der dritte Durchgang dieses Modells an der Mühlmattenschule.</p>'
                                .'<p>Das Team besteht aus einem Grundschullehrer und zwei Sonderpädagoginnen und von einer Praktikantin im Freiwilligen Sozialen Jahr. Die Schüler der Kooperativen Organisationsform von der Richard-Mittermaier-Schule haben einen sonderpädagogischen Bildungsanspruch im Sinne der Schule für Geistigbehinderte. Ein Schüler mit Autismus hat die ganze Woche eine Schulbegleiterin als Unterstützung mit im Unterricht.</p>'
                                .'<p>In der gemeinsamen Klasse lernen alle Schüler und Schülerinnen miteinander und vor allem voneinander. Alle Kinder sind verschieden, nicht nur die Kinder mit sonderpädagogischem Bildungsanspruch. Es ist unser Ziel, dass alle Kinder nach ihren Bedürfnissen und Fähigkeiten lernen dürfen und unterrichtet werden. Unsere Aufgabe als Sonderpädagoginnen ist es, Differenzierungsmöglichkeiten und konkret anschauliches Material anzubieten. Unser Leitgedanke: <strong>„So viel gemeinsamer Unterricht wie möglich und so viel Individualförderung wie nötig"</strong>.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Konzepte & Projekte im Überblick',
                'slug' => 'konzepte-und-projekte',
                'section' => 'schulleben',
                'menu_group' => 'Pädagogisches Konzept',
                'menu_order' => 6,
                'summary' => 'Gesamtübersicht über Leitbild, „So ist es bei uns", Klassenrat, BiSS-Transfer und unsere Patenschaft nach Simbabwe.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Konzepte & Projekte',
                            'body' => $wpPages['konzepte-projekte']['body'] ?? '',
                        ],
                    ],
                ],
            ],

            // =========================================================================
            // RUBRIK 3: ANGEBOTE & BETREUUNG
            // =========================================================================
            [
                'title' => 'Schulkindbetreuung (Caritas)',
                'slug' => 'schulkindbetreuung',
                'section' => 'angebote',
                'menu_group' => 'Betreuung & Schulweg',
                'menu_order' => 1,
                'summary' => 'Verlässliche Schulkindbetreuung durch den Caritasverband Freiburg-Stadt e.V. von 7:30 bis 17:00 Uhr inklusive Mittagessen und Ferienbetreuung.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulkindbetreuung an der Mühlmattenschule',
                            'body' => $wpPages['betreuung']['body'] ?? '',
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Ansprechpartnerin der Schulkindbetreuung',
                            'group' => PersonGroup::Betreuung->value,
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Arbeitsgemeinschaften (AGs)',
                'slug' => 'arbeitsgemeinschaften',
                'section' => 'angebote',
                'menu_group' => 'AGs & Leseförderung',
                'menu_order' => 2,
                'summary' => 'Sport-AG, Computer-AG, Flöten-AG, Schulchor, Fußball-AG, Englisch-AG, Melodika sowie bewegte und entspannte Pausenangebote.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Arbeitsgemeinschaften',
                            'body' => $wpPages['arbeitsgemeinschaften']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Lesepatenschaften',
                'slug' => 'lesepatenschaften',
                'section' => 'angebote',
                'menu_group' => 'AGs & Leseförderung',
                'menu_order' => 3,
                'summary' => 'Ehrenamtliche Leselernpatinnen und -paten begleiten Kleingruppen beim Lesenlernen und wecken Lesefreude.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Lesepatenschaften',
                            'body' => $wpPages['lesepatenschaften']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Bibfit – Der Lesekompass',
                'slug' => 'bibfit',
                'section' => 'angebote',
                'menu_group' => 'AGs & Leseförderung',
                'menu_order' => 4,
                'summary' => 'Bibliotheksführerschein für die 1. und 2. Grundschulklassen in Kooperation mit der öffentlichen Bücherei St. Martin in Hochdorf.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Bibfit für die 1. und 2. Grundschulklasse',
                            'body' => $wpPages['der-bibfit-lesekompass-fuer-die-1-und-2-grundschulklasse']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Kooperationen',
                'slug' => 'kooperationen',
                'section' => 'angebote',
                'menu_group' => 'AGs & Leseförderung',
                'menu_order' => 5,
                'summary' => 'Stark vernetzt in Freiburg-Hochdorf: Musikschule Freiburg, Chorvereinigung Hochdorf, SV Hochdorf, Akkordeon-Club und Bücherei St. Martin.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Kooperationen',
                            'body' => $wpPages['kooperationen']['body'] ?? '',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Sicherer Schulweg (Schulwegeplan)',
                'slug' => 'schulwegeplan',
                'section' => 'angebote',
                'menu_group' => 'Betreuung & Schulweg',
                'menu_order' => 6,
                'summary' => 'Schulwegeplan für Freiburg-Hochdorf, Benzhausen und das Gewerbegebiet – sichere Gehwege, Haltestellen und Hol-/Bringzonen.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulwegeplan Hochdorf, Benzhausen und Gewerbegebiet Hochdorf',
                            'body' => '<p><img src="/images/school/schulwegeplan-hochdorf-benzhausen-und-gewerbegebiet-hochdorf-01.jpg" alt="Schulwegeplan Karte" width="1024" height="726"></p>'
                                .'<p><a href="/files/school/Schulwegeplan-Hochdorf-Karte.pdf" target="_blank" rel="noopener noreferrer">Schulwegeplan als PDF herunterladen</a></p>'
                                .'<p><img src="/images/school/schulwegeplan-hochdorf-benzhausen-und-gewerbegebiet-hochdorf-02.jpg" alt="Schulwegeplan Regeln" width="1024" height="724"></p>'
                                .'<p><a href="/files/school/Schulwegeplan-Hochdorf-Regeln.pdf" target="_blank" rel="noopener noreferrer">Schulwegeplan-Regeln als PDF herunterladen</a></p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulkindbetreuung & AGs (Gesamtübersicht)',
                'slug' => 'betreuung-und-ags',
                'section' => 'schulleben',
                'menu_group' => 'Betreuung & Schulweg',
                'menu_order' => 7,
                'summary' => 'Übersicht über die Caritas-Schulkindbetreuung (7:30 – 17:00 Uhr) sowie unsere Arbeitsgemeinschaften, Lesepatenschaften und Bibfit.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Angebote',
                            'body' => $wpPages['angebote']['body'] ?? '',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            Page::query()->updateOrCreate(
                ['slug' => $pageData['slug']],
                [
                    ...$pageData,
                    'status' => PublishStatus::Published,
                ],
            );
        }
    }

    /**
     * Erzeugt nummerierte Bildpfade (z. B. /images/school/plaetzchen-full-01.jpg bis -05.jpg).
     *
     * @return array<int, string>
     */
    private function buildRangeUrls(string $prefix, int $count, string $extension = 'jpg'): array
    {
        $urls = [];
        for ($i = 1; $i <= $count; $i++) {
            $urls[] = sprintf('/images/school/%s%02d.%s', $prefix, $i, $extension);
        }

        return $urls;
    }

    /**
     * Erzeugt ungerade nummerierte Vollbildpfade (01, 03, 05, ...), um Thumbnail-Duplikate auszuschließen.
     *
     * @return array<int, string>
     */
    private function buildOddRangeUrls(string $prefix, int $maxOdd, string $extension = 'jpg'): array
    {
        $urls = [];
        for ($i = 1; $i <= $maxOdd; $i += 2) {
            $urls[] = sprintf('/images/school/%s%02d.%s', $prefix, $i, $extension);
        }

        return $urls;
    }

    /**
     * Hängt eine Bilddatei aus public/images/school an eine Single-File-Collection an,
     * ohne die Quelldatei im public-Verzeichnis zu löschen.
     */
    private function attachSingleMedia(HasMedia $model, string $collection, string $filename): void
    {
        $path = public_path('images/school/'.$filename);
        if (! is_file($path)) {
            return;
        }

        if ($model->getFirstMedia($collection) !== null) {
            $model->clearMediaCollection($collection);
        }

        $model->addMedia($path)
            ->preservingOriginal()
            ->toMediaCollection($collection);
    }
}
