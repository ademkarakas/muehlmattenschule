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
use App\Models\Event;
use App\Models\Page;
use App\Models\Person;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Befüllt die Datenbank mit deutschen Basis- und Beispielinhalten der Mühlmattenschule.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@muehlmattenschule.de'],
            [
                'name' => 'Systemadministration',
                'role' => UserRole::Admin,
                'password' => Hash::make('Muehlmatten!2026'),
                'email_verified_at' => now(),
            ],
        );

        $schulleitung = User::query()->updateOrCreate(
            ['email' => 'schulleitung@muehlmattenschule.de'],
            [
                'name' => 'Diana Blum (Schulleitung)',
                'role' => UserRole::Schulleitung,
                'password' => Hash::make('Schulleitung!2026'),
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'sekretariat@muehlmattenschule.de'],
            [
                'name' => 'Sekretariat',
                'role' => UserRole::Sekretariat,
                'password' => Hash::make('Sekretariat!2026'),
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'klasse2a@muehlmattenschule.de'],
            [
                'name' => 'Klassenleitung 2a',
                'role' => UserRole::Lehrkraft,
                'class_name' => 'Klasse 2a',
                'password' => Hash::make('Lehrkraft!2026'),
                'email_verified_at' => now(),
            ],
        );

        Setting::setValue('school', [
            'name' => 'Mühlmattenschule',
            'subtitle' => 'Grundschule Freiburg-Hochdorf',
            'street' => 'Zu den Mühlmatten 8',
            'zip_city' => '79108 Freiburg im Breisgau',
            'phone' => '0761 / 201-7612',
            'email' => 'sekretariat.mms@freiburg.de',
            'office_hours' => 'Mo–Fr 07:45–12:00 Uhr',
        ]);

        $catSchulleben = Category::query()->updateOrCreate(
            ['slug' => 'schulleben'],
            ['name' => 'Schulleben', 'color' => '#14b8a6'],
        );
        $catAusflug = Category::query()->updateOrCreate(
            ['slug' => 'ausflug'],
            ['name' => 'Ausflug & Natur', 'color' => '#0d9488'],
        );
        $catFest = Category::query()->updateOrCreate(
            ['slug' => 'feste-feiern'],
            ['name' => 'Feste & Feiern', 'color' => '#f59e0b'],
        );
        Category::query()->updateOrCreate(
            ['slug' => 'projekte'],
            ['name' => 'Projekte & AGs', 'color' => '#0f766e'],
        );

        Announcement::query()->updateOrCreate(
            ['text' => 'Herzlich willkommen auf der neuen Website der Mühlmattenschule Freiburg-Hochdorf!'],
            [
                'level' => AnnouncementLevel::Info,
                'link_url' => '/unsere-schule/schulportrait',
                'link_label' => 'Mehr über unsere Schule',
                'visible_from' => now()->subDay(),
                'visible_until' => now()->addMonths(2),
                'is_active' => true,
            ],
        );

        $albumHerbst = Album::query()->updateOrCreate(
            ['slug' => 'herbstwanderung-im-mooswald'],
            [
                'title' => 'Herbstwanderung im Mooswald',
                'event_date' => now()->subDays(10)->toDateString(),
                'description' => 'Eindrücke von unserem gemeinsamen Wandertag durch den bunten Hochdorfer Mooswald.',
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(9),
            ],
        );

        Post::query()->updateOrCreate(
            ['slug' => 'gemeinsamer-herbstwandertag-durch-den-mooswald'],
            [
                'title' => 'Gemeinsamer Herbstwandertag durch den Mooswald',
                'excerpt' => 'Bei goldenem Herbstwetter machten sich unsere Klassen auf den Weg in den Mooswald rund um Hochdorf.',
                'body' => '<p>Bei herrlichem Sonnenschein starteten alle Klassen morgens gemeinsam auf dem Schulhof. Unterwegs sammelten die Kinder bunte Blätter, Kastanien und lernten die Bäume unserer Umgebung kennen.</p><p>Nach einem gemütlichen Vesper auf der Waldlichtung kehrten alle glücklich an die Mühlmattenschule zurück.</p>',
                'category_id' => $catAusflug->id,
                'album_id' => $albumHerbst->id,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(8),
            ],
        );

        Post::query()->updateOrCreate(
            ['slug' => 'vorlesetag-an-der-muehlmattenschule'],
            [
                'title' => 'Bundesweiter Vorlesetag an der Mühlmattenschule',
                'excerpt' => 'Spannende Geschichten in allen Klassenzimmern: Unser Kollegium und Lesepatinnen lasen aus ihren Lieblingsbüchern vor.',
                'body' => '<p>Am Vorlesetag verwandelte sich unser Schulhaus in eine große Leseinsel. Die Kinder durften sich im Vorfeld ein Buchthema aussuchen und lauschten gebannt den Geschichten.</p>',
                'category_id' => $catSchulleben->id,
                'author_id' => $admin->id,
                'is_featured' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(3),
            ],
        );

        Post::query()->updateOrCreate(
            ['slug' => 'st-martins-und-laternenfest-auf-dem-schulhof'],
            [
                'title' => 'Einladung zum Laternenfest auf dem Schulhof',
                'excerpt' => 'Gemeinsam mit dem Elternbeirat und dem Förderverein laden wir herzlich zu unserem traditionellen Lichterfest ein.',
                'body' => '<p>In den letzten Wochen wurde in allen Klassen fleißig gebastelt. Nun freuen wir uns darauf, die bunten Laternen gemeinsam zum Leuchten zu bringen.</p>',
                'category_id' => $catFest->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDay(),
            ],
        );

        Event::query()->updateOrCreate(
            ['title' => 'Laternenfest auf dem Schulhof'],
            [
                'starts_at' => now()->addDays(7)->setTime(17, 0),
                'ends_at' => now()->addDays(7)->setTime(19, 30),
                'all_day' => false,
                'location' => 'Schulhof der Mühlmattenschule',
                'description' => 'Gemeinsames Singen, Laternenumzug und Kinderpunsch mit dem Förderverein.',
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
                'description' => 'Austausch zu aktuellen Themen im Schuljahr.',
                'is_published' => true,
            ],
        );

        Event::query()->updateOrCreate(
            ['title' => 'Adventsbasteln in den Klassen'],
            [
                'starts_at' => now()->addDays(25)->startOfDay(),
                'ends_at' => null,
                'all_day' => true,
                'location' => 'Klassenzimmer',
                'description' => 'Vormittags gestalten die Klassen den winterlichen Schulhausschmuck.',
                'is_published' => true,
            ],
        );

        Person::query()->updateOrCreate(
            ['name' => 'Diana Blum'],
            [
                'role_title' => 'Rektorin',
                'group' => PersonGroup::Schulleitung,
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        Person::query()->updateOrCreate(
            ['name' => 'Sekretariat Mühlmattenschule'],
            [
                'role_title' => 'Schulsekretariat',
                'email' => 'sekretariat.mms@freiburg.de',
                'group' => PersonGroup::Verwaltung,
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        $pages = [
            [
                'title' => 'Schulportrait',
                'slug' => 'schulportrait',
                'section' => 'unsere-schule',
                'menu_order' => 1,
                'summary' => 'Geschichte, Architektur und Kennzahlen unserer Grundschule in Freiburg-Hochdorf.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Lernen unter einem hellen Holzdach',
                            'body' => '<p>Die Mühlmattenschule ist die Grundschule für die Freiburger Stadtteile Hochdorf und Benzhausen. Unser 1991 bezogenes Schulgebäude zeichnet sich durch viel Holz, Tageslicht und großzügige Gemeinschaftsflächen aus.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulleitung & Verwaltung',
                'slug' => 'schulleitung-und-verwaltung',
                'section' => 'unsere-schule',
                'menu_order' => 2,
                'summary' => 'Ansprechpartnerinnen und Öffnungszeiten des Sekretariats.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Wir sind gerne für Sie da',
                            'body' => '<p>Unser Sekretariat erreichen Sie vormittags telefonisch oder per E-Mail. Gesprächstermine mit der Schulleitung vereinbaren Sie bitte vorab über das Sekretariat.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Kollegium',
                'slug' => 'kollegium',
                'section' => 'unsere-schule',
                'menu_order' => 3,
                'summary' => 'Lehrkräfte und pädagogisches Team der Mühlmattenschule.',
                'content' => [
                    [
                        'type' => 'people_list',
                        'data' => ['group' => 'all'],
                    ],
                ],
            ],
            [
                'title' => 'Schulsozialarbeit',
                'slug' => 'schulsozialarbeit',
                'section' => 'unsere-schule',
                'menu_order' => 4,
                'summary' => 'Beratung und Unterstützung für Kinder, Eltern und Lehrkräfte.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Vertrauliche Beratung und Projekte',
                            'body' => '<p>Die Schulsozialarbeit begleitet die Kinder im Schulalltag, stärkt das soziale Miteinander in den Klassen und berät Eltern vertraulich in Erziehungs- und Lebensfragen.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Elternbeirat & Förderverein',
                'slug' => 'elternbeirat',
                'section' => 'unsere-schule',
                'menu_order' => 5,
                'summary' => 'Gemeinsam Schule gestalten: Elternvertretung und Förderverein der Mühlmattenschule.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Engagierte Elternschaft',
                            'body' => '<p>Der Elternbeirat und der Förderverein unterstützen schulische Projekte, Ausflüge, Feste und besondere Anschaffungen für unsere Schülerinnen und Schüler.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulkindbetreuung & AGs',
                'slug' => 'betreuung-und-ags',
                'section' => 'schulleben',
                'menu_order' => 1,
                'summary' => 'Verlässliche Grundschule, Nachmittagsbetreuung und Arbeitsgemeinschaften.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Betreuungsangebote und Arbeitsgemeinschaften',
                            'body' => '<p>Im Anschluss an den Unterricht bieten wir vielfältige Arbeitsgemeinschaften sowie eine verlässliche Betreuung am Vor- und Nachmittag an.</p>',
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
}
