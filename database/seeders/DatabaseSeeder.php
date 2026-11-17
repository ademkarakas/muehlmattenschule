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
                'name' => 'Stefanie Kremer (Sekretariat)',
                'role' => UserRole::Sekretariat,
                'password' => Hash::make($isProd ? (string) env('INITIAL_OFFICE_PASSWORD', Str::password(24)) : 'Sekretariat!2026'),
                'email_verified_at' => now(),
            ],
        );

        $lehrkraft2b = User::query()->updateOrCreate(
            ['email' => 'klasse2a@muehlmattenschule.de'],
            [
                'name' => 'Melanie Jakoby (Klasse 3b)',
                'role' => UserRole::Lehrkraft,
                'class_name' => 'Klasse 3b',
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

        // --- Komplette reale Personenliste der Mühlmattenschule ---
        $staffList = [
            [
                'name' => 'Diana Blum',
                'role_title' => 'Rektorin / Schulleiterin',
                'class_name' => null,
                'email' => 'schulleitung.mmsvn@freiburger-schulen.bwl.de',
                'group' => PersonGroup::Schulleitung,
                'sort_order' => 1,
                'portrait' => 'Frau_Blum_2026.jpg',
            ],
            [
                'name' => 'Sabine Caputo',
                'role_title' => 'Konrektorin',
                'class_name' => 'Klasse 2a · Bienenklasse',
                'email' => 'Konrektorat.MMSVN@freiburger-schulen.bwl.de',
                'group' => PersonGroup::Schulleitung,
                'sort_order' => 2,
                'portrait' => 'teacher-caputo.jpg',
            ],
            [
                'name' => 'Stefanie Kremer',
                'role_title' => 'Schulsekretärin',
                'class_name' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
                'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
                'group' => PersonGroup::Verwaltung,
                'sort_order' => 3,
                'portrait' => null,
            ],
            [
                'name' => 'Reinhard Frei',
                'role_title' => 'Hausmeister',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Verwaltung,
                'sort_order' => 4,
                'portrait' => null,
            ],
            [
                'name' => 'Raimund Früh',
                'role_title' => 'Klassenlehrer & BiSS-Ansprechperson',
                'class_name' => 'Klasse 1a · Chamäleonklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 10,
                'portrait' => null,
            ],
            [
                'name' => 'Elisabeth Borgas',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 1a · Chamäleonklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 11,
                'portrait' => null,
            ],
            [
                'name' => 'Elvira Piess',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 1b · Igelklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 12,
                'portrait' => null,
            ],
            [
                'name' => 'Saskia Jungfleisch',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 1c · Otterklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 13,
                'portrait' => null,
            ],
            [
                'name' => 'Daniela Bayer',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 2a · Bienenklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 14,
                'portrait' => null,
            ],
            [
                'name' => 'Alexa Götten',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 2b · Koalaklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 15,
                'portrait' => null,
            ],
            [
                'name' => 'Britta Sterzik',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 3a · Erdmännchenklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 16,
                'portrait' => null,
            ],
            [
                'name' => 'Melanie Jakoby',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 3b · Pinguinklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 17,
                'portrait' => null,
            ],
            [
                'name' => 'Jutta Garrido',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 3c · Eisbärenklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 18,
                'portrait' => null,
            ],
            [
                'name' => 'Marion Huber',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 4a · Elefantenklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 19,
                'portrait' => null,
            ],
            [
                'name' => 'Katja Bürger',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 4a · Elefantenklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 20,
                'portrait' => null,
            ],
            [
                'name' => 'Nina Neumann',
                'role_title' => 'Klassenlehrerin',
                'class_name' => 'Klasse 4b · Fuchsklasse',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 21,
                'portrait' => null,
            ],
            [
                'name' => 'Ira May',
                'role_title' => 'Referendarin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 22,
                'portrait' => null,
            ],
            [
                'name' => 'Julia Marquardt',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 23,
                'portrait' => null,
            ],
            [
                'name' => 'Verena Röber',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 24,
                'portrait' => null,
            ],
            [
                'name' => 'Luica Graab',
                'role_title' => 'Fachlehrerin',
                'class_name' => null,
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
                'summary' => 'Geschichte, Lage am Naturschutzgebiet Mühlmatten, Holzarchitektur und Schulgelände der Mühlmattenschule in Freiburg-Hochdorf.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Unsere Schule in Freiburg-Hochdorf',
                            'image_url' => '/images/school/logo-mms-2023.png',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Das offizielle Schullogo der Mühlmattenschule Freiburg-Hochdorf.',
                            'body' => '<p>Alle Kinder aus dem Freiburger Stadtteil Hochdorf (mit den Ortschaften Benzhausen, Hochdorf und dem Industrie- und Gewerbegebiet Hochdorf, zusammen aktuell ca. 5.200 Ew.) besuchen die Mühlmattenschule.</p>'
                                .'<p>Infolge der Eingemeindung Anfang der 70er Jahre stieg die Einwohnerzahl der ehemaligen Gemeinde Hochdorf stark an. Durch Zuzug vieler junger Familien wurde die Dorfschule, die „Michael-Denzlinger-Schule“, zu klein. Eine neue Schule wurde gebaut und 1991 bezogen. Benannt wurde sie nach dem Gewann, in der sie liegt: „Mühlmattenschule“. In schneller Folge musste die Schule zweimal erweitert werden (1993/94 und 1996/97). Im Schuljahr 1997/98 wurden 344 Kinder in 14 Klassen unterrichtet.</p>'
                                .'<p>Mittlerweile ist die Zahl der Schüler stark zurückgegangen. Dieses Schuljahr werden 187 Kinder in <a href="/unsere-schule/unsere-klassen">9 Klassen</a> und einer Außenklasse von <a href="/unsere-schule/kollegium">20 Lehrerinnen und Lehrern</a> unterrichtet.</p>',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Lage',
                            'image_url' => '/images/school/unsere-schule-karte.jpg',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Lageplan der Mühlmattenschule in Freiburg-Hochdorf (zum Vergrößern anklicken).',
                            'body' => '<p>Die Grundschule Mühlmattenschule befindet sich in Hochdorf am Rande des neuen Ortszentrums. Sie grenzt unmittelbar an das Naturschutzgebiet „Mühlmatten“. Damit ist die Schule sehr ruhig gelegen, dennoch gut erreichbar für alle Hochdorfer.</p>'
                                .'<p>Optimal auch: Hallenbad und Turnhalle liegen keine hundert Meter von der Schule – können somit zu Fuß in wenigen Minuten erreicht werden.</p>',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulgebäude und -gelände',
                            'image_url' => '/images/school/unsere-schule-innen.jpg',
                            'image_position' => 'left',
                            'image_fit' => 'cover',
                            'image_caption' => 'Heller Flur mit warmer Holzarchitektur, durchgängigen Oberlichtern und Glastür zur Schulkindbetreuung.',
                            'body' => '<p>Holz ist das optisch dominierende Element der Architektur des einstöckigen Schulgebäudes. Dank der durchgängigen Oberlichter im Flur gelangt, auch wenn die Sonne nicht scheint und in der dunklen Jahreszeit, immer viel natürliches Tageslicht ins Innere der Schule. Helligkeit und Holz – beides zusammen schafft eine warme, angenehme und freundliche Atmosphäre, die wirkt, sobald man in die Schule eintritt.</p>'
                                .'<p>Am südlichen Ende des Gebäudes befinden sich, abgetrennt durch eine Glastür (siehe Bild links), die Räume der <a href="/angebote/schulkindbetreuung">Schulkindbetreuung</a>.</p>'
                                .'<p>Auf dem Schulgelände gibt es viel Platz und Angebote zum Austoben in den Pausenzeiten und während der Nachmittagsbetreuung, zum Beispiel eine Spielanlage mit Edelstahlrutsche und Kletterwand.</p>'
                                .'<p>Besonders in der Sommerzeit spielen die Kinder gerne am Bach, der die Schule nach Osten hin begrenzt. Oder es wird gekickt auf dem Schulsportgelände.</p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Schulgelände und Umgebung aus der Vogelperspektive',
                            'image_1_url' => '/images/school/unsere-schule-umgegend.jpg',
                            'image_1_caption' => 'Luftaufnahme der Mühlmattenschule direkt am Naturschutzgebiet „Mühlmatten“ in Freiburg-Hochdorf.',
                            'image_2_url' => '/images/school/gebaeude-full.jpg',
                            'image_2_caption' => 'Das einstöckige Schulgebäude mit Pausenhof, Spielanlage, Schulsportgelände und Bachlauf.',
                            'caption' => 'Die Luftbilder hat uns freundlicherweise das Ballonteam Norbert Blau zur Verfügung gestellt.',
                            'source_label' => 'Ballonteam Norbert Blau',
                            'source_url' => 'http://www.freiburg-schwarzwald.net',
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
                'summary' => 'Schulleiterin Diana Blum, Konrektorin Sabine Caputo, Schulsekretärin Stefanie Kremer und Hausmeister Reinhard Frei.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulleitung und Verwaltung',
                            'body' => '<p>Die Schulleitung, das Schulsekretariat und unser Hausmeister sind die zentralen Anlaufstellen für Eltern, Schülerinnen und Schüler sowie Kooperationspartner der Mühlmattenschule.</p>'
                                .'<p>Unser Sekretariat ist <strong>montags, mittwochs und freitags von 7:30 bis 12:00 Uhr</strong> für Sie erreichbar.</p>',
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
                            'heading' => 'Sekretariat & Hausmeister',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/Kollegium_2026.jpg',
                            'caption' => 'Das Kollegium der Mühlmattenschule Freiburg-Hochdorf',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Kollegium',
                            'body' => '<p>An der Mühlmattenschule arbeiten Klassenlehrkräfte, Fachlehrkräfte, eine Referendarin, Schulleitung, Verwaltung sowie Schulsozialarbeit Hand in Hand für unsere Schülerinnen und Schüler.</p>',
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
                            'heading' => 'Sekretariat & Hausmeister',
                            'group' => PersonGroup::Verwaltung->value,
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
                'summary' => 'Zehn Grundschulklassen (1a–4b) mit eigenen Klassentieren – eine starke Schulgemeinschaft.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Unsere Klassen',
                            'body' => '<p>An der Mühlmattenschule lernen derzeit zehn Grundschulklassen in den Jahrgangsstufen 1 bis 4. Jede Klasse hat ihr eigenes Klassentier:</p>'
                                .'<ul>'
                                .'<li><strong>Klasse 1a – Chamäleonklasse:</strong> Herr Raimund Früh und Frau Elisabeth Borgas</li>'
                                .'<li><strong>Klasse 1b – Igelklasse:</strong> Frau Elvira Piess</li>'
                                .'<li><strong>Klasse 1c – Otterklasse:</strong> Frau Saskia Jungfleisch</li>'
                                .'<li><strong>Klasse 2a – Bienenklasse:</strong> Frau Daniela Bayer und Frau Sabine Caputo</li>'
                                .'<li><strong>Klasse 2b – Koalaklasse:</strong> Frau Alexa Götten</li>'
                                .'<li><strong>Klasse 3a – Erdmännchenklasse:</strong> Frau Britta Sterzik</li>'
                                .'<li><strong>Klasse 3b – Pinguinklasse:</strong> Frau Melanie Jakoby</li>'
                                .'<li><strong>Klasse 3c – Eisbärenklasse:</strong> Frau Jutta Garrido</li>'
                                .'<li><strong>Klasse 4a – Elefantenklasse:</strong> Frau Marion Huber und Frau Katja Bürger</li>'
                                .'<li><strong>Klasse 4b – Fuchsklasse:</strong> Frau Nina Neumann</li>'
                                .'</ul>',
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
                            'image_url' => '/images/school/logo-foerderverein.png',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V.',
                            'body' => '<p>Der Förderverein der Mühlmattenschule wurde im Jahr 1996 gegründet. Wir sind im Vereinsregister Freiburg Nr. 2943 als gemeinnütziger Verein eingetragen. Der Förderverein bietet engagierten Familien sowie Firmen die Möglichkeit, sich für die Schule und ihre Kinder zu beteiligen.</p>'
                                .'<h3>Was ist der Zweck des Vereins?</h3>'
                                .'<p>Wir haben das Ziel der ideellen und materiellen Förderung der Bildung und Erziehung der Kinder der Mühlmattenschule. Wir unterstützen die Schulgemeinschaft und die Lehrkräfte. Die Zusammengehörigkeit zwischen Schule, Eltern und Schülern soll unterstützt und erhalten bleiben.</p>'
                                .'<h3>Was wollen wir fördern und unterstützen wir?</h3>'
                                .'<ul>'
                                .'<li>Stärkung des WIR-Gefühls der Grundschüler</li>'
                                .'<li>Förderung des Sports</li>'
                                .'<li>Gestaltung der Schule und des Schulhofs</li>'
                                .'<li>Die Förderung von Arbeitsgemeinschaften</li>'
                                .'<li>Unterstützung kultureller Projekte und Veranstaltungen</li>'
                                .'<li>Finanzielle Unterstützung von Klassenaktivitäten</li>'
                                .'</ul>'
                                .'<h3>Aktuelle geförderte Projekte, Maßnahmen und Aktivitäten</h3>'
                                .'<ul>'
                                .'<li>Organisation und Durchführung des <a href="/unsere-schule/crosslauf"><strong>Crosslaufs</strong></a></li>'
                                .'<li>Einschulungsgeschenk</li>'
                                .'<li>Geburtstagsgeschenk für alle Schulkinder</li>'
                                .'<li>Adventskranz für die Schule</li>'
                                .'<li>Finanzielle Unterstützung'
                                .'<ul>'
                                .'<li>Finanzielle Unterstützung von Autorenlesungen</li>'
                                .'<li>Beteiligung an den Kosten der Schulprojekte</li>'
                                .'<li>Finanzielle Unterstützung für Familien, bei welchen die Teilnahme an schulischen Aktivitäten (Klassenfahrten, Arbeitsgemeinschaften) den Rahmen der eigenen Möglichkeiten übersteigt.</li>'
                                .'<li>Finanzielle Unterstützung von kulturellen Veranstaltungen wie Theaterbesuchen aller Klassen.</li>'
                                .'<li>Übernahme der Getränkekosten für den Schüleraustausch mit Colmar.</li>'
                                .'</ul>'
                                .'</li>'
                                .'</ul>',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Wer sind Ihre Ansprechpartner & wie können Sie fördern?',
                            'image_url' => '/images/school/foerderverein-vorstand.jpg',
                            'image_position' => 'right',
                            'image_fit' => 'cover',
                            'image_caption' => 'Der Vorstand des Fördervereins der Mühlmattenschule.',
                            'body' => '<p><strong>Vorstand des Fördervereins:</strong><br>'
                                .'Giuseppe Pastore, 1. Vorsitzender<br>'
                                .'Verena Prestel, 2. Vorsitzende<br>'
                                .'Jan Gläser, Kassenwart<br>'
                                .'Steffie Nowak, Schriftführerin<br>'
                                .'Florian Gutgsell, Beisitzer<br>'
                                .'Ali Nemer, Beisitzer</p>'
                                .'<p><strong><a href="/aktuelles/der-vorstand-des-foerdervereins-stellt-sich-vor">Portrait des Vorstands</a></strong></p>'
                                .'<h3>Wie können Sie fördern?</h3>'
                                .'<p>Die Mittel bezieht der Förderverein vornehmlich aus Mitgliedsbeiträgen sowie aus Spenden und Bewirtungsüberschüssen von Veranstaltungen.</p>'
                                .'<p>Ihren Mitgliedsbeitrag können Sie selber bestimmen. Der jährliche Mindestbeitrag beträgt 17,– Euro. Jeder Mitgliedsbeitrag ist voll steuerlich absetzbar im Sinne des § 10 EStG. Sie können auch aktiv fördern, indem Sie bei Veranstaltungen des Vereines mitwirken.</p>'
                                .'<p>Mitglied werden ist ganz einfach: Beitrittserklärung <a href="https://easyverein.com/public/FoeveMms/applicationform/7095" target="_blank" rel="noopener noreferrer"><strong>hier</strong></a> online ausfüllen oder <strong><a href="/files/school/Beitrittserklaerung-Foerderverein-MMS.pdf">Beitrittserklärung</a></strong> herunterladen, ausfüllen und unterschreiben und im Schulbriefkasten einwerfen oder im Sekretariat oder bei einem der Vorstandsmitglieder persönlich abgeben.</p>'
                                .'<p><strong>Kontaktadresse:</strong><br>'
                                .'Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V.<br>'
                                .'Zu den Mühlmatten 8 · 79108 Freiburg i. Br.<br>'
                                .'Tel.: 07665/2232 · E-Mail: foeve.mms{at}gmail.com</p>',
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
                            'image_url' => '/images/school/crosslauf-illu.png',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Traditionelles Schulsportfest für die ganze Familie seit 1998.',
                            'body' => '<p>Der Crosslauf ist ein Schulsportfest für die ganze Familie, veranstaltet vom Förderverein in Kooperation mit der Mühlmattenschule. Es findet in jedem Schuljahr im Mai oder Juni an einem Samstag statt. Der Crosslauf ist der Schülerlauf. Hier geht es zu wie bei Olympia: Die besten Drei eines Jahrgangs bekommen Medaillen. Nach dem Schülerwettbewerb gibt es den Sponsorenlauf. Hier laufen alle, die wollen, für einen guten Zweck. Neben den Läufen gibt es eine große Tombola und natürlich auch für das leibliche Wohl wird gesorgt.</p>'
                                .'<p>Das Fest findet seit 1998 statt und erfreut sich bei Jung und Alt sehr großer Beliebtheit!</p>',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Crosslauf (Schülerwettlauf)',
                            'image_url' => '/images/school/crosslauf-karte-2023.png',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Streckenkarte des ca. 450 m langen Rundkurses entlang des Mühlbachs (zum Vergrößern anklicken).',
                            'body' => '<p>Der Crosslauf ist ein klassischer Wettlauf. Teilnehmen können alle Schüler der Mühlmattenschule. Die Laufstrecke ist ein ca. 450 m langer Rundkurs entlang des Mühlbachs (siehe Karte). Die Kinder trainieren im Rahmen des Sportunterrichts und lernen dort auch die Strecke kennen.</p>'
                                .'<h3>Ablauf</h3>'
                                .'<p>Die Kinder laufen jahrgangsweise, Jungs und Mädchen getrennt, in kleinen Gruppen (sind viele Jungs oder Mädchen in einem Jahrgang, starten sie getrennt in mehreren Gruppen).</p>'
                                .'<h3>Wertung</h3>'
                                .'<p>Jedes Kind, welches das Ziel erreicht, erhält eine Urkunde und ein Gratislos für die Tombola. Die ersten drei Jungs und Mädchen pro Jahrgang erhalten zusätzlich eine Medaille, Gold-Silber-Bronze – wie bei der Olympiade! Obendrauf erhält die teilnehmerstärkste Klasse den „Mühlmattenpokal“ – eine begehrte Trophäe gefüllt mit Süßigkeiten!</p>'
                                .'<h3>Rahmenprogramm</h3>'
                                .'<p>Verkauf von Heiß- und Kaltgetränken, Würstchen und Kuchen und große Tombola!</p>',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Sponsorenlauf',
                            'image_url' => '/images/school/sponsorenlauf-illu.png',
                            'image_position' => 'left',
                            'image_fit' => 'contain',
                            'image_caption' => 'Beim Sponsorenlauf zählen jede Runde und jeder Stempel für den guten Zweck.',
                            'body' => '<p>Auf den Crosslauf folgt der Sponsorenlauf. Der Sponsorenlauf ist ein Benefizrennen. Hier geht es nicht darum, der Schnellste zu sein, sondern dass viele Läufer zusammen möglichst viele Runden laufen. Denn dann kommt viel Geld für einen guten Zweck zusammen! Mitlaufen darf jeder: Eltern, Großeltern, Geschwister, Freunde, Lehrer usw., und natürlich die Schüler selbst auch.</p>'
                                .'<h3>Läufer, Sponsoren, Sponsorenvertrag, Laufkarte</h3>'
                                .'<p>Jeder Läufer hat einen oder mehrere Sponsoren. Ein Sponsor ist ein „Pate“ des Läufers, der für jede von letzterem vollendete Runde einen Betrag spendet. Wie hoch dieser Betrag ist, legt jeder Sponsor selbst fest. Vor dem Rennen erhält jeder Läufer einen Sponsorenvertrag, in dem der Läufer angibt, für welchen Schüler er läuft (das ist relevant für die Klassenwertung, siehe unten), wer seine Sponsoren sind und welchen Rundenbetrag diese spenden. Dazu bekommt der Läufer eine Laufkarte, die er beim Lauf mit sich führt.</p>'
                                .'<h3>Laufen, Stempeln, Laufen, Stempeln</h3>'
                                .'<p>Dann kann es losgehen: Runde für Runde drehen die Läufer um die Schule (die Strecke ist deutlich kürzer als die des Crosslaufs). Am Ende einer jeden Runde erhalten die Läuferinnen und Läufer einen Stempel auf ihre Laufkarte. Insgesamt haben sie etwa eine Stunde Zeit, so viele Stempel wie möglich einzusammeln.</p>'
                                .'<h3>Am Ende wird gezählt & Wertungen</h3>'
                                .'<p>Nach Ablauf der Zeit wird gezählt: Für jeden Stempel entrichten die Sponsoren später den Rundenbetrag. Gerechnet wird beispielsweise wie folgt: Ein Läufer schafft 15 Runden und hat 2 Sponsoren, der eine hat sich für 50 Cent, der andere für 25 Cent je Runde verpflichtet. In diesem Beispiel wäre der erlaufene Spendenbetrag dieses Läufers 15 × 0,50 + 15 × 0,25 = 11,25 Euro. Hinweis für Sponsoren: Bitte beachten Sie bei der Festlegung der Rundenspende, dass man in der Zeit locker 20 Runden laufen kann!</p>'
                                .'<p>Eine Belohnung gibt es für die beste Schulklasse: Alle Runden der Läufer für eine Schulklasse werden addiert. Die Klasse mit den meisten gelaufenen Runden erhält als Sonderpreis einen Betrag für die Klassenkasse. Im Jahr 2022 traten 82 Läuferinnen und Läufer an und liefen 830 Runden und somit ungefähr 400 km.</p>',
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
                            'image_url' => '/images/school/signet-hochschulpartnerschule.jpg',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Offizielles Signet „Hochschulpartnerschule“ im Praxiskolleg von FACE (PH & Universität Freiburg).',
                            'body' => '<p>Die Bezeichnung „Hochschulpartnerschule“ erhalten Schulen, die mit der Pädagogischen Hochschule Freiburg und/oder der Albert-Ludwigs-Universität Freiburg eng zusammenarbeiten. Gebündelt werden diese Kooperationen im sog. „Praxiskolleg“, das unter dem Dach des Freiburg Advanced Center of Education (FACE) angesiedelt ist. Das Praxiskolleg fungiert als Plattform für eine intensive Vernetzung zwischen der Schulpraxis und der Bildungsforschung sowie im Bereich der Aus- und Fortbildung von Lehrkräften.</p>'
                                .'<p>Mit der Schaffung von Hochschulpartnerschulen sollen schon länger bestehende Partnerschaften (z. B. im Bereich der schulischen Praktika) stärker herausgestellt werden. Darüber hinaus gilt es, weitere verbindliche Partnerschaften (z. B. im Bereich der Forschung) zwischen den Hochschulen und den Schulen aufzubauen. Die Zusammenarbeit kann somit über verschiedene Schwerpunkte ausgestaltet werden. Die Vergabe des Status „Hochschulpartnerschule“ ist je nach Schwerpunkt an unterschiedliche Kriterien gebunden.</p>'
                                .'<h2>Kooperation im Bereich Praxisphasenbetreuung</h2>'
                                .'<p>Das „Integrierte Semesterpraktikum“ (ISP) ist seit 2011 ein verpflichtender Bestandteil für das Studium der Lehrämter an den Pädagogischen Hochschulen des Landes Baden-Württemberg. Im Rahmen des Kooperationsschwerpunktes erhalten Ausbildungsschulen den Status „Hochschulpartnerschule“, wenn diese bereits vier oder mehr Semester mit der Pädagogischen Hochschule Freiburg in der Betreuung von Studierenden im ISP zusammengearbeitet haben (siehe auch Webseite des Zentrums für Schulpraktische Studien (ZfS)). Voraussetzung, ISP-Ausbildungsschule zu werden, ist – neben der räumlichen Nähe zu Freiburg – eine Lehrkraft, welche die Rolle der Ausbildungsberaterin bzw. des Ausbildungsberaters übernimmt und sich hierzu (weiter-)qualifiziert. Die Qualifizierungsmaßnahmen finden i. d. R. – koordiniert durch das ZfS – an der Pädagogischen Hochschule Freiburg statt. Die Bestellung zur Ausbildungsberaterin bzw. zum Ausbildungsberater erfolgt durch das Schulamt Freiburg.</p>'
                                .'<h2>Umsetzung der Kooperation im ISP an unserer Schule</h2>'
                                .'<p><strong>Wir, die Mühlmattenschule, betreuen seit März 2013 Studierende der Pädagogischen Hochschule Freiburg in ihrem „Integrierten Semesterpraktikum“.</strong><br>'
                                .'Als ISP-Ausbildungsschule gestalten wir das 15 bis 16 Wochen andauernde Praktikum in intensiver Zusammenarbeit mit der Pädagogischen Hochschule. Die Studierenden werden sowohl durch die Ausbildungsberaterin bzw. den Ausbildungsberater als auch durch mehrere Lehrkräfte des Kollegiums (die sog. Ausbildungslehrkräfte) aktiv in den Schulalltag und das Unterrichten eingebunden. Die Praxisphase wird dabei mit integrierten fachdidaktisch und bildungswissenschaftlich akzentuierten Anteilen an der Schule durchgeführt und durch Lehrende der Pädagogischen Hochschule in sogenannten Tagesfachpraktikumsgruppen regelmäßig vor Ort begleitet. Das ISP dient der Berufsorientierung der Studierenden und der Stärkung des Bezugs zur Schulpraxis. Es ermöglicht ein frühzeitiges Kennenlernen des gesamten Tätigkeitsfeldes Schule, insbesondere unter dem Blickwinkel der individuellen Förderung von Schülerinnen und Schülern. Im ISP soll festgestellt werden, ob im Hinblick auf eine spätere Berufstätigkeit die dem Ausbildungsstand entsprechenden Grundlagen didaktisch-methodischer und erzieherischer Kompetenzen und eine sich ausprägende Lehrpersönlichkeit in hinreichender Weise erkennbar sind.<br>'
                                .'Ansprechpartnerin für das ISP an unserer Schule: <strong>Elvira Piess</strong>.</p>'
                                .'<h2>Mehrwert der Kooperation im ISP für die Beteiligten</h2>'
                                .'<p>Durch die Beteiligung am ISP wirken die betreffenden Ausbildungsschulen an einer professionellen, an aktuellen Forschungsergebnissen ausgerichteten Ausbildung der Lehrerinnen und Lehrer von morgen mit. Neben der Möglichkeit, sich aktiv an der Ausbildung von Lehrkräften zu beteiligen, eröffnet die Zusammenarbeit mit der Pädagogischen Hochschule auch Perspektiven für die qualitative Weiterentwicklung von Schule und Unterricht. Darüber hinaus wird die – auch in vielen nicht lehramtsbezogenen Berufsfeldern relevante – Verzahnung von Theorie und Praxis über den Dialog zwischen den Beteiligten aufrechterhalten. Dies trägt auch dazu bei, dass bei den zukünftig für die Bildung und Erziehung von Schülerinnen und Schülern zuständigen Lehrkräften frühzeitig ein Professionalisierungsprozess angebahnt wird.</p>',
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
                            'heading' => 'Leitbild der Mühlmattenschule',
                            'image_url' => '/images/school/leitbild-schema.jpg',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Das Leitbild der Mühlmattenschule im Überblick (zum Vergrößern anklicken).',
                            'body' => '<p>In den Schuljahren 2006/07 und 2007/08 haben die Mitglieder der Schulkonferenz – Eltern und Lehrer gemeinsam – unter Anleitung einer Prozessbegleiterin das Leitbild der Mühlmattenschule erarbeitet.</p>'
                                .'<p>Seit dieser Zeit entwickelt das Kollegium zu den Leitsätzen konkrete Projekte und Maßnahmen, um diese auch im Schulalltag Wirklichkeit werden zu lassen:</p>'
                                .'<ul>'
                                .'<li>Kompetenzraster</li>'
                                .'<li>Stärken stärken – Umgang mit Heterogenität</li>'
                                .'<li>Regelmäßige Mühlmatten-Treffen (Treffen aller Schüler und Schülerinnen)</li>'
                                .'<li>Klassenrat</li>'
                                .'<li>Projekthafter und klassenübergreifender Unterricht</li>'
                                .'</ul>',
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
                            'image_url' => '/images/school/klassenrat-symbol.jpg',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => 'Wöchentliche Gesprächsrunde in allen Klassen der Mühlmattenschule.',
                            'body' => '<p>Im Herbst 2009 wurde an unserer Schule für alle Klassen der Klassenrat eingeführt. Er ist eine wöchentlich stattfindende Gesprächsrunde, in der konkrete Anliegen aus dem Schulalltag wie Ausflüge, Projekte, Regeln, Dienste, Probleme und Konflikte zur Sprache kommen.</p>'
                                .'<p>Der Schwerpunkt des Klassenrates liegt im sozialen Lernen, in der Gewaltprävention und der Entwicklung einer demokratischen Schulkultur.</p>'
                                .'<p>Die Schüler lernen hier:</p>'
                                .'<ul>'
                                .'<li>Verantwortung zu übernehmen,</li>'
                                .'<li>Entscheidungen zu fällen und mitzutragen,</li>'
                                .'<li>Lösungen zu finden,</li>'
                                .'<li>angemessenes Verhalten bei Konflikten,</li>'
                                .'<li>sich ihre eigene Meinung zu bilden und diese zu vertreten,</li>'
                                .'<li>zu argumentieren,</li>'
                                .'<li>Aufgaben zu übernehmen,</li>'
                                .'<li>mit Kritik umzugehen</li>'
                                .'<li>und viele weitere soziale Kompetenzen.</li>'
                                .'</ul>'
                                .'<p>Der Klassenrat stärkt das Wir-Gefühl und schafft ein positives Schul-, Klassen- und Lernklima.</p>',
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
                            'heading' => 'Über die Einrichtung – Midlands Children Home Rosedale',
                            'image_url' => '/images/school/rosedale-kinder-afrika.jpg',
                            'image_position' => 'right',
                            'image_fit' => 'cover',
                            'image_caption' => 'Kinder im Midlands Children Home Rosedale in Gweru, Simbabwe.',
                            'body' => '<p>Seit 1995 pflegt unsere Schule eine Patenschaft mit dem Kinderheim und Waisenhaus <strong>„Midlands Children Home Rosedale“</strong> in Gweru/Simbabwe.</p>'
                                .'<p>Vor fast 35 Jahren wurde das Heim von Gertrud Scheu, einer mittlerweile verstorbenen, kirchlichen Sozialarbeiterin aus der Schweiz und anderen engagierten Menschen als Verein gegründet. Die Stadt Gweru, in der sich das Heim befindet, liegt ziemlich genau in der Mitte des Landes. Das Haus Rosedale ist heute Heimat von ca. 60 Kindern von 0 bis 17 Jahren.</p>'
                                .'<p>Es handelt sich überwiegend um Findelkinder, die als Säuglinge ausgesetzt wurden, z. B. weil die Mütter an Aids starben oder wegen sonstiger Notsituationen und deren Angehörige nicht zu ermitteln waren. Schwere körperliche und seelische Vergewaltigungen sind der Einweisungsgrund für die restlichen Kinder. Die Kinder besuchen ab 5 Jahren den öffentlichen Kindergarten und ab 7 Jahren die Schule. Vor einigen Jahren wurde zusätzlich ein Gelände erworben, wo Jugendliche nach Abschluss der Schule leben und Feldarbeit erlernen können.</p>'
                                .'<p>Dank des Patenschaftsprogramms kommen der Einrichtung regelmäßig finanzielle Mittel aus der Schweiz und anderen Ländern zugute, sodass der Unterhalt und Betrieb der Einrichtung derzeit gesichert ist.</p>'
                                .'<p>Weitere Informationen zum MCH Rosedale finden Sie auf den <a href="http://www.kath.ch/kleindoettingen/default/index/index/id/1783" target="_blank" rel="noopener noreferrer">Seiten der St. Antoniuspfarrei in Kleindöttingen (Schweiz)</a>.</p>',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Trommeln für Rosedale',
                            'image_url' => '/images/school/rosedale-trommeln.png',
                            'image_position' => 'left',
                            'image_fit' => 'contain',
                            'image_caption' => 'Unsere Schülerinnen und Schüler singen und trommeln bei Auftritten und Schulfesten für Rosedale.',
                            'body' => '<p>Das ganze Jahr über finden wir Gelegenheiten, bei denen wir Geld „zusammen trommeln“ können. Die Kinder singen und trommeln bei ihren Straßenmusik-Auftritten in der Freiburger Innenstadt und bei unseren Schulfesten.</p>'
                                .'<p>Es freut uns, dass unsere Schülerinnen und Schüler auf diese Weise erleben können, wie befriedigend es ist, anderen Menschen hilfreich zu sein.</p>',
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
                            'image_url' => '/images/school/bibfit-buecherei.jpg',
                            'image_position' => 'right',
                            'image_fit' => 'contain',
                            'image_caption' => '„Ich bin Bibfit!" – Der Lesekompass in der Bücherei St. Martin Hochdorf.',
                            'body' => '<p>Seit 2010 besteht zwischen der Mühlmattenschule und der öffentlichen Bücherei in Hochdorf das Bibfit-Programm. Bibfit bedeutet einfach „bibliotheks-fit".</p>'
                                .'<p>Die Kinder sollen durch das Programm selbstständig in der öffentlichen Bücherei der katholischen Pfarrgemeinde St. Martin Bücher und viele andere Medien ausleihen können. Die Bücherei befindet sich in der Benzhauser Str. 3 hinter dem Pfarrer-Meybrunn-Haus.</p>'
                                .'<p>Viermal innerhalb des 1. und 2. Grundschuljahres besuchen die Schülerinnen und Schüler der Mühlmattenschule unsere Bibliothek. Natürlich können die Kinder auch zwischen diesen Klassenbesuchen jederzeit privat etwas ausleihen. Am Ende des zweiten Schuljahres werden die nun fitten Leseratten mit einer Urkunde belohnt.</p>'
                                .'<p>Die Klassenbesuche werden vom Bibfit-Team der Bücherei vorbereitet und durchgeführt. Ziel ist es, die Kinder mit dem Medienangebot der Bücherei Hochdorf auf spielerische Weise vertraut zu machen, zur Leseförderung beizutragen und die Begeisterung fürs Lesen zu wecken.</p>'
                                .'<p>Jedes Kind, das von den Eltern die Erlaubnis zur Ausleihe bekommt, erhält einen Leseausweis. Die Bücherei hat ein umfangreiches Angebot an Medien für alle Altersgruppen.</p>'
                                .'<p><em>Das Bibfit-Team</em></p>',
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
