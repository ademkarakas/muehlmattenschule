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
use Spatie\MediaLibrary\HasMedia;

class DatabaseSeeder extends Seeder
{
    /**
     * Befüllt die Datenbank mit den Original-Inhalten, Fotos und dem Kollegium
     * der Mühlmattenschule Freiburg-Hochdorf.
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

        $sekretariat = User::query()->updateOrCreate(
            ['email' => 'sekretariat@muehlmattenschule.de'],
            [
                'name' => 'Mechthild Vennebusch (Sekretariat)',
                'role' => UserRole::Sekretariat,
                'password' => Hash::make('Sekretariat!2026'),
                'email_verified_at' => now(),
            ],
        );

        $lehrkraft2a = User::query()->updateOrCreate(
            ['email' => 'klasse2a@muehlmattenschule.de'],
            [
                'name' => 'Frau Eder (Klasse 2a)',
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
            'phone' => '07665 2232',
            'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
            'office_hours' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
            'welcome_title' => 'Willkommen an der Mühlmattenschule',
            'welcome_text' => 'Die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen – ein Ort zum Lernen, Wachsen und Entdecken.',
        ]);

        $catSchulleben = Category::query()->updateOrCreate(
            ['slug' => 'schulleben'],
            ['name' => 'Schulleben', 'color' => '#0096c7'],
        );
        $catElterninfo = Category::query()->updateOrCreate(
            ['slug' => 'elterninfos'],
            ['name' => 'Elterninfos', 'color' => '#0277bd'],
        );
        $catFest = Category::query()->updateOrCreate(
            ['slug' => 'feste-feiern'],
            ['name' => 'Schulfeste & Feiern', 'color' => '#f59e0b'],
        );
        $catSport = Category::query()->updateOrCreate(
            ['slug' => 'sport-bewegung'],
            ['name' => 'Sport & Bewegung', 'color' => '#00b4d8'],
        );
        $catProjekte = Category::query()->updateOrCreate(
            ['slug' => 'projekte'],
            ['name' => 'Konzepte & Projekte', 'color' => '#01579b'],
        );

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

        // --- Fotoalben mit echten Bildern aus der bestehenden Website ---
        $albumCampus = Album::query()->updateOrCreate(
            ['slug' => 'unser-schulgebaeude-und-campus'],
            [
                'title' => 'Unser Schulgebäude & Pausenhof im Grünen',
                'event_date' => now()->subMonths(2)->toDateString(),
                'description' => 'Helle Flure mit Holzelementen, freundliche Klassenzimmer und unser weitläufiges Schulgelände in Freiburg-Hochdorf.',
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subMonths(2),
            ],
        );
        $this->attachSingleMedia($albumCampus, 'cover', 'about-main.jpg');
        $this->attachGalleryMedia($albumCampus, 'photos', [
            'about-main.jpg',
            'gebaeude-wide.jpg',
            'gebaeude-full.jpg',
            'unsere-schule-innen.jpg',
            'unsere-schule-umgegend.jpg',
            'schule-bei-abend.jpg',
            'slider-schuleinnen.jpg',
        ]);

        $albumAdvent = Album::query()->updateOrCreate(
            ['slug' => 'adventszeit-und-basteln'],
            [
                'title' => 'Adventssingen & Adventsbasteln an der Mühlmattenschule',
                'event_date' => now()->subDays(25)->toDateString(),
                'description' => 'Ein Nachmittag voller Kerzen, Papier und Musik: Eindrücke vom Adventsbasteln und dem gemeinsamen Adventssingen.',
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(24),
            ],
        );
        $this->attachSingleMedia($albumAdvent, 'cover', 'news-advent-1.jpg');
        $this->attachGalleryMedia($albumAdvent, 'photos', [
            'news-advent-1.jpg',
            'news-basteln-1.jpg',
            'news-plaetzchen.jpg',
        ]);

        $albumSport = Album::query()->updateOrCreate(
            ['slug' => 'sport-bewegung-und-schulleben'],
            [
                'title' => 'Sport, Bewegungstage & Lernen im Schulalltag',
                'event_date' => now()->subDays(14)->toDateString(),
                'description' => 'Tanzaktion mit der Tanzschule Gutmann, Crosslauf und gemeinsames Lernen in unseren Klassen.',
                'author_id' => $lehrkraft2a->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(14),
            ],
        );
        $this->attachSingleMedia($albumSport, 'cover', 'sport.jpg');
        $this->attachGalleryMedia($albumSport, 'photos', [
            'sport.jpg',
            'news-tanzen.jpg',
            'about-kids.jpg',
            'konzepte.jpg',
            'slider-elternhelfenmit.jpg',
        ]);

        $albumRosedale = Album::query()->updateOrCreate(
            ['slug' => 'patenschaft-kinderheim-rosedale-simbabwe'],
            [
                'title' => 'Patenschaft Midlands Children Home Rosedale (Simbabwe)',
                'event_date' => now()->subMonths(1)->toDateString(),
                'description' => 'Seit 1995 pflegt die Mühlmattenschule eine lebendige Patenschaft mit dem Kinderheim Rosedale in Gweru, Simbabwe.',
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subMonths(1),
            ],
        );
        $this->attachSingleMedia($albumRosedale, 'cover', 'rosedale-kinder.jpg');
        $this->attachGalleryMedia($albumRosedale, 'photos', [
            'rosedale-kinder.jpg',
            'trommeln-rosedale.jpg',
        ]);

        // --- Aktuelle Beiträge aus der bestehenden Website ---
        $postMundenhof = Post::query()->updateOrCreate(
            ['slug' => 'ausflug-der-4-klassen-zur-gruenholzwerkstatt-auf-dem-mundenhof'],
            [
                'title' => 'Ausflug der 4. Klassen zur Grünholzwerkstatt auf dem Mundenhof',
                'class_name' => 'Klasse 4a & 4b',
                'excerpt' => 'Die Klassen 4a und 4b waren auf dem Mundenhof bei der Grünholzwerkstatt des Spielmobils – es wurde gedrechselt, gesägt und gebaut.',
                'body' => '<p>Die Klassen 4a und 4b besuchten gemeinsam die Grünholzwerkstatt des Spielmobils auf dem Freiburger Mundenhof. Mit frischem Holz aus dem Wald und echtem Werkzeug durften die Kinder nach Herzenslust sägen, schnitzen, bohren und an der Wippdrehbank arbeiten.</p><p>Im Anschluss haben die Schülerinnen und Schüler eigene Berichte über ihre Erlebnisse und ihre selbstgebauten Holzwerke verfasst.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumSport->id,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(2),
            ],
        );
        $this->attachSingleMedia($postMundenhof, 'cover', 'about-kids.jpg');

        $postJubilaeum = Post::query()->updateOrCreate(
            ['slug' => '25-jaehriges-dienstjubilaeum-von-diana-blum'],
            [
                'title' => '25-jähriges Dienstjubiläum von Rektorin Diana Blum',
                'excerpt' => 'Rektorin Diana Blum feierte ihr 25-jähriges Dienstjubiläum. Sie ist seit September 2004 an der Mühlmattenschule und leitet sie seit dem Schuljahr 2017/2018.',
                'body' => '<p>Mit einer herzlichen Überraschungsfeier ehrte die gesamte Schulgemeinschaft unsere Rektorin Diana Blum zu ihrem 25-jährigen Dienstjubiläum.</p><p>Frau Blum unterrichtet bereits seit September 2004 an der Mühlmattenschule und prägt sie seit dem Schuljahr 2017/2018 als Schulleiterin mit großem Engagement und Herzblut.</p>',
                'category_id' => $catFest->id,
                'author_id' => $sekretariat->id,
                'is_featured' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(5),
            ],
        );
        $this->attachSingleMedia($postJubilaeum, 'cover', 'news-jubilaeum.jpg');

        $postFoerderverein = Post::query()->updateOrCreate(
            ['slug' => 'der-vorstand-des-foerdervereins-stellt-sich-vor'],
            [
                'title' => 'Der Vorstand des Fördervereins stellt sich vor',
                'excerpt' => 'Nach der Verabschiedung von Nils Henkel und Fabian Baumann stellt sich das neue Vorstandsteam des Fördervereins der Schulgemeinschaft vor.',
                'body' => '<p>Nach langjähriger, engagierter Unterstützung des Fördervereins wurden Nils Henkel und Fabian Baumann herzlich verabschiedet. Der neue Vorstand freut sich darauf, auch in Zukunft kulturelle und pädagogische Projekte, das gesunde Frühstück, die Spielgeräteausleihe und besondere Anschaffungen an der Mühlmattenschule tatkräftig zu fördern.</p>',
                'category_id' => $catElterninfo->id,
                'author_id' => $admin->id,
                'is_featured' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(8),
            ],
        );
        $this->attachSingleMedia($postFoerderverein, 'cover', 'foerderverein-vorstand.jpg');

        $postAdvent = Post::query()->updateOrCreate(
            ['slug' => 'weihnachtliche-muehlmattenschule-und-adventssingen'],
            [
                'title' => 'Weihnachtliche Mühlmattenschule & gemeinsames Adventssingen',
                'excerpt' => 'Weihnachtliche Stimmung und Adventssingen in der Aula der Mühlmattenschule – Bilder und Eindrücke aus der Vorweihnachtszeit.',
                'body' => '<p>In der Vorweihnachtszeit versammelten sich alle Klassen unter dem geschmückten Weihnachtsbaum in unserer Aula zum gemeinsamen Adventssingen. Mit Flöten, Klavierbegleitung und viel Gesang stimmten sich Kinder und Lehrkräfte auf die Feiertage ein.</p>',
                'category_id' => $catFest->id,
                'album_id' => $albumAdvent->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(12),
            ],
        );
        $this->attachSingleMedia($postAdvent, 'cover', 'news-advent-1.jpg');

        $postBasteln = Post::query()->updateOrCreate(
            ['slug' => 'adventsbasteln-in-den-klassen-die-bilder'],
            [
                'title' => 'Adventsbasteln im Schulhaus – Eindrücke aus den Klassen',
                'excerpt' => 'Ein Vormittag voller Kerzen, Papier, Tannenzweige und Klebstoff: Eindrücke vom diesjährigen Adventsbasteln mit Unterstützung vieler Eltern.',
                'body' => '<p>Beim traditionellen Adventsbasteln verwandelten sich alle Klassenzimmer in kleine Weihnachtswerkstätten. Dank der tatkräftigen Hilfe zahlreicher Eltern entstanden leuchtende Fensterbilder, Sterne und Adventskränze für unser gesamtes Schulhaus.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumAdvent->id,
                'author_id' => $lehrkraft2a->id,
                'is_featured' => false,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(15),
            ],
        );
        $this->attachSingleMedia($postBasteln, 'cover', 'news-basteln-1.jpg');

        $postPlaetzchen = Post::query()->updateOrCreate(
            ['slug' => 'weihnachtsplaetzchenverkauf-auf-dem-hochdorfer-markt'],
            [
                'title' => 'Weihnachtsplätzchenverkauf auf dem Hochdorfer Markt',
                'excerpt' => 'Der Elternbeirat verkaufte auch dieses Jahr wieder auf dem Hochdorfer Markt selbstgebackene Weihnachtsplätzchen für die Elternbeiratskasse.',
                'body' => '<p>Viele Familien unserer Schule haben fleißig gebacken und gespendet, sodass der Elternbeirat auf dem Hochdorfer Markt wieder ein buntes Sortiment an Weihnachtsplätzchen anbieten konnte. Der Erlös kommt direkt den Projekten unserer Schülerinnen und Schüler zugute.</p>',
                'category_id' => $catFest->id,
                'author_id' => $sekretariat->id,
                'is_featured' => false,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(18),
            ],
        );
        $this->attachSingleMedia($postPlaetzchen, 'cover', 'news-plaetzchen.jpg');

        $postTanzen = Post::query()->updateOrCreate(
            ['slug' => 'bewegungs-und-gesundheitstage-tanzen'],
            [
                'title' => 'Bewegungs- und Gesundheitstage: Tanzen in der Sporthalle',
                'excerpt' => 'Tanzaktion mit der Tanzschule Gutmann – einer der Höhepunkte unserer Bewegungs- und Gesundheitstage an der Mühlmattenschule.',
                'body' => '<p>Im Rahmen unserer Bewegungs- und Gesundheitstage erlebten alle Klassen mitreißende Tanzstunden in der Sporthalle. Gemeinsam mit den Tanzlehrerinnen und Tanzlehrern der Tanzschule Gutmann studierten die Kinder schwungvolle Choreografien ein.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumSport->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(22),
            ],
        );
        $this->attachSingleMedia($postTanzen, 'cover', 'news-tanzen.jpg');

        // --- Termine ---
        Event::query()->updateOrCreate(
            ['title' => 'Laternen- & St.-Martins-Treffen auf dem Schulhof'],
            [
                'starts_at' => now()->addDays(7)->setTime(17, 0),
                'ends_at' => now()->addDays(7)->setTime(19, 30),
                'all_day' => false,
                'location' => 'Schulhof der Mühlmattenschule',
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
                'description' => 'Einführung in die Bücherei im Rahmen des Lesekompass-Programms.',
                'is_published' => true,
            ],
        );

        // --- Komplette reale Personenliste (Schulleitung, Sekretariat, Kollegium, Sozialarbeit, Betreuung) ---
        Person::query()->whereNotIn('name', [
            'Diana Blum',
            'Sabine Caputo',
            'Mechthild Vennebusch',
            'Frau Bett',
            'Frau Götten',
            'Frau Eder',
            'Frau Jakoby',
            'Frau Bürger',
            'Frau Piess',
            'Frau Huber',
            'Frau Garrido',
            'Frau Sterzik',
            'Herr Früh',
            'Frau Pfattner',
            'Frau Wastell',
            'Frau Neumann',
            'Frau Marquardt',
            'Frau Borgas',
            'Frau Dingler',
            'Herr Markert',
            'Lisa Jortzik',
        ])->delete();

        $staffList = [
            // Schulleitung
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
            // Sekretariat / Verwaltung
            [
                'name' => 'Mechthild Vennebusch',
                'role_title' => 'Schulsekretärin',
                'class_name' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
                'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
                'group' => PersonGroup::Verwaltung,
                'sort_order' => 3,
                'portrait' => 'teacher-vennebusch.jpg',
            ],
            // Klassenlehrerinnen & Klassenlehrer
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
                'role_title' => 'Klassenlehrer',
                'class_name' => 'Klasse 4c',
                'email' => null,
                'group' => PersonGroup::Kollegium,
                'sort_order' => 19,
                'portrait' => null,
            ],
            // Fachlehrerinnen & Außenklasse
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
            // Schulsozialarbeit
            [
                'name' => 'Herr Markert',
                'role_title' => 'Schulsozialarbeiter',
                'class_name' => 'Schulsozialarbeit',
                'email' => null,
                'group' => PersonGroup::Sozialarbeit,
                'sort_order' => 30,
                'portrait' => null,
            ],
            // Schulkindbetreuung
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

        // --- Schulseiten mit Originaltexten und Fotos ---
        $pages = [
            [
                'title' => 'Schulportrait',
                'slug' => 'schulportrait',
                'section' => 'unsere-schule',
                'menu_order' => 1,
                'summary' => 'Eine junge Schule im Grünen: Geschichte, Architektur und Schulleben der Mühlmattenschule in Freiburg-Hochdorf.',
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
                            'heading' => 'Lage und Geschichte',
                            'body' => '<p>Die Mühlmattenschule ist die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen. Sie ist eine verhältnismäßig junge Schule: Im Jahr <strong>1995</strong> wurde der erste Bauabschnitt eingeweiht, im Jahr <strong>2000</strong> folgte der zweite Bauabschnitt. Seitdem verfügt die Schule über die Räumlichkeiten für eine bis zu dreizügige Grundschule.</p><p>Aktuell lernen an unserer Schule rund <strong>187 Kinder</strong> in <strong>9 Klassen</strong> sowie einer <strong>Außenklasse</strong>, begleitet von <strong>20 Lehrkräften</strong>.</p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Einblicke in unser Schulhaus und die Umgebung',
                            'image_1_url' => '/images/school/unsere-schule-innen.jpg',
                            'image_1_caption' => 'Helle Flure mit Holzelementen prägen das Innere der Schule.',
                            'image_2_url' => '/images/school/unsere-schule-umgegend.jpg',
                            'image_2_caption' => 'Luftbild der Mühlmattenschule am grünen Ortsrand von Hochdorf.',
                            'image_3_url' => '/images/school/about-main.jpg',
                            'image_3_caption' => 'Weitläufiger Pausenhof mit viel Platz für Bewegung und Spiel.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Räume und Ausstattung',
                            'body' => '<p>Neben den großen und hellen Klassenzimmern verfügt die Schule über zahlreiche Fach- und Gemeinschaftsräume: eine Schülerbücherei, einen Computerraum, einen Musiksaal, Werk- und Kunsträume sowie die direkt benachbarte Sporthalle.</p><p>Im Erdgeschoss befinden sich zudem die Räumlichkeiten der Schulkindbetreuung mit Mensa, Spiel- und Ruheräumen.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulleitung & Verwaltung',
                'slug' => 'schulleitung-und-verwaltung',
                'section' => 'unsere-schule',
                'menu_order' => 2,
                'summary' => 'Schulleiterin Diana Blum, Konrektorin Sabine Caputo und Schulsekretärin Mechthild Vennebusch.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/verwaltung-wide.jpg',
                            'caption' => 'Schulleitung und Sekretariat der Mühlmattenschule.',
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
                    [
                        'type' => 'info_box',
                        'data' => [
                            'title' => 'Öffnungszeiten & Erreichbarkeit des Sekretariats',
                            'text' => 'Montag, Mittwoch und Freitag von 7:30 bis 12:00 Uhr · Telefon: 07665 2232 · E-Mail: sekretariat.mmsvn@freiburger-schulen.bwl.de',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Kollegium',
                'slug' => 'kollegium',
                'section' => 'unsere-schule',
                'menu_order' => 3,
                'summary' => 'Ein engagiertes Team aus 20 Lehrkräften, Sonderpädagogik und Schulsozialarbeit begleitet die rund 187 Kinder unserer Schule.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/kollegium-wide.jpg',
                            'caption' => 'Das Kollegium der Mühlmattenschule Freiburg-Hochdorf.',
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
                            'heading' => 'Schulsozialarbeit & Schulkindbetreuung',
                            'group' => PersonGroup::Sozialarbeit->value,
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unsere Klassen',
                'slug' => 'unsere-klassen',
                'section' => 'unsere-schule',
                'menu_order' => 4,
                'summary' => 'Neun Klassen und eine Außenklasse – eine starke Schulgemeinschaft.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/slider-schuleinnen.jpg',
                            'caption' => 'Freundliche und helle Lernumgebung an der Mühlmattenschule.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Klassenstruktur an der Mühlmattenschule',
                            'body' => '<p>Die Mühlmattenschule hat aktuell neun Grundschulklassen von Jahrgangsstufe 1 bis 4 sowie eine kooperative Außenklasse. Jede Klasse wird von einer festen Klassenlehrerin oder einem festen Klassenlehrer begleitet und durch Fachlehrkräfte unterstützt.</p>',
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
                'menu_order' => 5,
                'summary' => 'Beratung, Begleitung und Stärkung des sozialen Miteinanders an der Mühlmattenschule.',
                'content' => [
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Ansprechpartner Schulsozialarbeit',
                            'group' => PersonGroup::Sozialarbeit->value,
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Aufgaben und Angebote der Schulsozialarbeit',
                            'body' => '<p>Die Schulsozialarbeit an der Mühlmattenschule ist ein vertrauliches und kostenfreies Angebot für alle Schülerinnen und Schüler, Eltern sowie Lehrkräfte. Sie unterstützt Kinder bei der Lösung von Konflikten, begleitet den wöchentlichen Klassenrat und berät Familien in Erziehungs- und Lebensfragen.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Elternbeirat & Förderverein',
                'slug' => 'elternbeirat',
                'section' => 'unsere-schule',
                'menu_order' => 6,
                'summary' => 'Aktive Elternarbeit und der Förderverein bereichern unser Schulleben.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/foerderverein-vorstand.jpg',
                            'caption' => 'Engagiert für die Kinder der Mühlmattenschule: unser Förderverein und Elternbeirat.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Gemeinsam Schule gestalten',
                            'body' => '<p>Elternbeirat und Förderverein bringen sich aktiv in das Schulleben der Mühlmattenschule ein – vom gesunden Schulfrühstück über das Sommer- und Einschulungsfest bis zur Finanzierung besonderer Spielgeräte, Bücher und Ausflüge.</p>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Konzepte & Projekte',
                'slug' => 'konzepte-und-projekte',
                'section' => 'schulleben',
                'menu_order' => 1,
                'summary' => 'Leitbild, Vereinbarung „So ist es bei uns", wöchentlicher Klassenrat und unsere Patenschaft nach Simbabwe.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/konzepte.jpg',
                            'caption' => 'Gemeinsames Lernen und individuelle Förderung an der Mühlmattenschule.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Unser Leitbild & „So ist es bei uns"',
                            'body' => '<p>Eltern und Lehrkräfte haben gemeinsam das Leitbild der Mühlmattenschule sowie unseren Leitfaden <strong>„So ist es bei uns"</strong> entwickelt – ein Heft, das von zwei Seiten lesbar ist: einmal für Eltern und einmal kindgerecht für unsere Schülerinnen und Schüler.</p><h3>Klassenrat</h3><p>Seit Herbst 2009 findet in jeder Klasse wöchentlich der Klassenrat statt. Soziales Lernen, Gewaltprävention und demokratische Mitbestimmung stehen dabei im Mittelpunkt.</p><h3>Patenschaft mit dem Kinderheim Rosedale in Simbabwe</h3><p>Seit 1995 pflegen wir eine enge Patenschaft mit dem Kinderheim <em>Midlands Children Home Rosedale</em> in Gweru, Simbabwe, in dem rund 60 Kinder ein Zuhause finden.</p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Eindrücke aus unseren Projekten',
                            'image_1_url' => '/images/school/rosedale-kinder.jpg',
                            'image_1_caption' => 'Unsere Patenschaft mit dem Kinderheim Rosedale in Gweru, Simbabwe.',
                            'image_2_url' => '/images/school/trommeln-rosedale.jpg',
                            'image_2_caption' => 'Musik- und Trommelprojekte zugunsten unserer Partnerschaft.',
                            'image_3_url' => '/images/school/slider-elternhelfenmit.jpg',
                            'image_3_caption' => 'Gemeinsame Projekttage mit Unterstützung der Elternschaft.',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulkindbetreuung & AGs',
                'slug' => 'betreuung-und-ags',
                'section' => 'schulleben',
                'menu_order' => 2,
                'summary' => 'Schulkindbetreuung durch die Caritas von 7:30 bis 17:00 Uhr sowie vielfältige Arbeitsgemeinschaften und Lesepatenschaften.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/about-kids.jpg',
                            'caption' => 'Lernen, Spielen und Gemeinschaft in der Schulkindbetreuung und den AGs.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulkindbetreuung an der Mühlmattenschule (7:30 – 17:00 Uhr)',
                            'body' => '<p>Die Schulkindbetreuung wird von der <strong>Caritas Schulkindbetreuung e.V.</strong> direkt in den Räumen der Mühlmattenschule angeboten. Am südlichen Ende des Schulgebäudes, abgetrennt durch eine Glastür, finden die Kinder ihre eigenen Räume zum Lernen, Spielen und Essen.</p><p>Die Betreuung ist von <strong>Montag bis Freitag von 7:30 bis 17:00 Uhr</strong> in verschiedenen Modulen flexibel buchbar – inklusive warmem Mittagessen und Ferienbetreuung.</p>',
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Leitung der Schulkindbetreuung',
                            'group' => PersonGroup::Betreuung->value,
                        ],
                    ],
                    [
                        'type' => 'info_box',
                        'data' => [
                            'title' => 'Kontakt Schulkindbetreuung (Caritasverband Freiburg-Stadt e.V.)',
                            'text' => 'Leitung: Lisa Jortzik · Festnetz: 07665 932 92 21 · Mobil: 0152 02751785 · E-Mail: schulkindbetreuung-muehlmattenschule@caritas-freiburg.de · Telefonische Sprechzeiten: 7:30 – 8:30 Uhr & 12:15 – 17:00 Uhr',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Arbeitsgemeinschaften (AGs), Lesepatenschaften & Bibfit',
                            'body' => '<p>Über den Unterricht hinaus bieten wir ein breites AG-Angebot für alle Klassenstufen an: <strong>Sport, Computer, Flöte, Chor, Fußball, Englisch und Melodika</strong> sowie Pausenangebote wie Ringen & Raufen oder Yoga.</p><p>Darüber hinaus begleiten ehrenamtliche <strong>Leselernpatinnen</strong> unsere Kinder beim Lesenlernen, und gemeinsam mit der Bücherei St. Martin führen wir für die 1. und 2. Klassen den <strong>Bibfit-Lesekompass</strong> durch.</p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Sport & Bewegung an unserer Schule',
                            'image_1_url' => '/images/school/sport.jpg',
                            'image_1_caption' => 'Kinder beim Crosslauf und den Bewegungstagen.',
                            'image_2_url' => '/images/school/news-tanzen.jpg',
                            'image_2_caption' => 'Tanzaktion in unserer Sporthalle.',
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

    /**
     * Hängt mehrere Bilddateien aus public/images/school an eine Galerie-Collection an.
     *
     * @param  array<int, string>  $filenames
     */
    private function attachGalleryMedia(HasMedia $model, string $collection, array $filenames): void
    {
        if ($model->getMedia($collection)->isNotEmpty()) {
            $model->clearMediaCollection($collection);
        }

        foreach ($filenames as $filename) {
            $path = public_path('images/school/'.$filename);
            if (! is_file($path)) {
                continue;
            }

            $model->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection($collection);
        }
    }
}
