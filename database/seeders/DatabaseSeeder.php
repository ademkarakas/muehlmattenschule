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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

class DatabaseSeeder extends Seeder
{
    /**
     * Befüllt die Datenbank mit sämtlichen Original-Inhalten, Aktivitäten,
     * Fotoalben, PDF-Downloads und dem Kollegium von muehlmattenschule.de.
     */
    public function run(): void
    {
        $isProd = app()->isProduction();

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@muehlmattenschule.de'],
            [
                'name' => 'Systemadministration',
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

        $sekretariat = User::query()->updateOrCreate(
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

        $catSchulleben = Category::query()->updateOrCreate(
            ['slug' => 'schulleben'],
            ['name' => 'Schulleben', 'color' => '#029786'],
        );
        $catElterninfo = Category::query()->updateOrCreate(
            ['slug' => 'elterninfos'],
            ['name' => 'Elterninfos', 'color' => '#007f70'],
        );
        $catFerien = Category::query()->updateOrCreate(
            ['slug' => 'ferienkalender'],
            ['name' => 'Ferienkalender', 'color' => '#00695c'],
        );
        $catFest = Category::query()->updateOrCreate(
            ['slug' => 'feste-feiern'],
            ['name' => 'Schulfeste & Feiern', 'color' => '#f59e0b'],
        );
        $catSport = Category::query()->updateOrCreate(
            ['slug' => 'sport-bewegung'],
            ['name' => 'Sport & Wettbewerbe', 'color' => '#13b5a2'],
        );
        $catProjekte = Category::query()->updateOrCreate(
            ['slug' => 'projekte'],
            ['name' => 'Konzept & Didaktik', 'color' => '#00544a'],
        );
        $catInklusion = Category::query()->updateOrCreate(
            ['slug' => 'inklusive-beschulung'],
            ['name' => 'Inklusive Beschulung', 'color' => '#0d9488'],
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

        $crosslaufUrls = $this->buildRangeUrls('crosslauf-full-', 44);
        $spielmobilUrls = $this->buildRangeUrls('spielmobil-full-', 20);
        $slowmobilUrls = $this->buildRangeUrls('slowmobil-full-', 13);
        $tanzenUrls = $this->buildRangeUrls('tanzen-full-', 3);
        $fruehstueckUrls = $this->buildRangeUrls('fruehstueck-full-', 20);
        $adventUrls = $this->buildRangeUrls('advent-full-', 12);
        $bastelnUrls = $this->buildRangeUrls('basteln-full-', 26);
        $plaetzchenUrls = $this->buildRangeUrls('plaetzchen-full-', 5);
        $jubilaeum25Urls = $this->buildRangeUrls('schuljubilaeum-25j-', 7);
        $muehlmattentreffenUrls = $this->buildRangeUrls('muehlmattentreffen-', 3);
        $blumJubilaeumUrls = $this->buildRangeUrls('blum-jubilaeum-', 3);

        // --- 7 Reale Fotoalben mit allen 190+ Originalfotos von muehlmattenschule.de ---
        $albumCrosslauf = Album::query()->updateOrCreate(
            ['slug' => 'crosslauf-der-muehlmattenschule'],
            [
                'title' => 'Bilder vom Crosslauf & Sponsorenlauf',
                'event_date' => '2024-06-24',
                'description' => 'Unser großes Schulsportfest für die ganze Familie, veranstaltet vom Förderverein in Kooperation mit der Mühlmattenschule (alle 44 Originalaufnahmen).',
                'gallery_images' => $crosslaufUrls,
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(5),
            ],
        );
        $this->attachSingleMedia($albumCrosslauf, 'cover', 'crosslauf-1.jpg');
        $this->attachGalleryMedia($albumCrosslauf, 'photos', [
            'crosslauf-full-01.jpg',
            'crosslauf-full-02.jpg',
            'crosslauf-full-03.jpg',
        ]);

        $albumBewegung = Album::query()->updateOrCreate(
            ['slug' => 'bewegungs-und-gesundheitstage'],
            [
                'title' => 'Bewegungs- & Gesundheitstage: Spielmobil, Slow-Mobil & Tanzen',
                'event_date' => '2024-10-24',
                'description' => 'Kochen im Slow-Mobil, Spielstationen mit dem Spielmobil auf dem Pausenhof und Tanzaktion mit der Tanzschule Gutmann (alle 36 Originalaufnahmen).',
                'gallery_images' => [...$spielmobilUrls, ...$slowmobilUrls, ...$tanzenUrls],
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(10),
            ],
        );
        $this->attachSingleMedia($albumBewegung, 'cover', 'slowmobil-1.jpg');
        $this->attachGalleryMedia($albumBewegung, 'photos', [
            'spielmobil-full-01.jpg',
            'slowmobil-full-01.jpg',
            'tanzen-full-01.jpg',
        ]);

        $albumErnaehrung = Album::query()->updateOrCreate(
            ['slug' => 'gesundes-fruehstueck-obstsalat-und-brotgesichter'],
            [
                'title' => 'Gesunde Ernährung in den 2. Klassen: Frühstück, Obstsalat & Brotgesichter',
                'class_name' => 'Klasse 2a & 2b',
                'event_date' => '2024-10-20',
                'description' => 'Die 2. Klassen bereiten ein gesundes Frühstück, frischen Obstsalat für Kater Cook und lustige Brotgesichter zu (alle 26 Originalaufnahmen).',
                'gallery_images' => [
                    ...$fruehstueckUrls,
                    '/images/school/obstsalat-2b-1.jpg',
                    '/images/school/obstsalat-2b-2.jpg',
                    '/images/school/obstsalat-2b-3.jpg',
                    '/images/school/brotgesichter-2b-1.jpg',
                    '/images/school/brotgesichter-2b-2.jpg',
                    '/images/school/brotgesichter-2b-3.jpg',
                ],
                'author_id' => $lehrkraft2b->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(15),
            ],
        );
        $this->attachSingleMedia($albumErnaehrung, 'cover', 'fruehstueck-2kl-1.jpg');
        $this->attachGalleryMedia($albumErnaehrung, 'photos', [
            'fruehstueck-full-01.jpg',
            'obstsalat-2b-1.jpg',
            'brotgesichter-2b-1.jpg',
        ]);

        $albumAdvent = Album::query()->updateOrCreate(
            ['slug' => 'adventszeit-und-basteln'],
            [
                'title' => 'Adventssingen, Adventsbasteln & Plätzchenverkauf',
                'event_date' => '2024-12-19',
                'description' => 'Weihnachtliche Stimmung, Adventsbasteln in allen Klassen und der Plätzchenverkauf auf dem Hochdorfer Markt (alle 43 Originalaufnahmen).',
                'gallery_images' => [...$adventUrls, ...$bastelnUrls, ...$plaetzchenUrls],
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(20),
            ],
        );
        $this->attachSingleMedia($albumAdvent, 'cover', 'news-advent-1.jpg');
        $this->attachGalleryMedia($albumAdvent, 'photos', [
            'advent-full-01.jpg',
            'basteln-full-01.jpg',
            'plaetzchen-full-01.jpg',
        ]);

        $albumCampus = Album::query()->updateOrCreate(
            ['slug' => 'unser-schulgebaeude-und-campus'],
            [
                'title' => 'Unser Schulgebäude & Pausenhof im Grünen',
                'event_date' => '2024-09-15',
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
                'published_at' => now()->subMonths(1),
            ],
        );
        $this->attachSingleMedia($albumCampus, 'cover', 'about-main.jpg');
        $this->attachGalleryMedia($albumCampus, 'photos', [
            'about-main.jpg',
            'gebaeude-wide.jpg',
            'unsere-schule-innen.jpg',
        ]);

        $albumRosedale = Album::query()->updateOrCreate(
            ['slug' => 'patenschaft-kinderheim-rosedale-simbabwe'],
            [
                'title' => 'Patenschaft Midlands Children Home Rosedale (Simbabwe)',
                'event_date' => '2024-05-10',
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
                'published_at' => now()->subMonths(2),
            ],
        );
        $this->attachSingleMedia($albumRosedale, 'cover', 'rosedale-kinder.jpg');
        $this->attachGalleryMedia($albumRosedale, 'photos', [
            'rosedale-kinder.jpg',
            'trommeln-rosedale.jpg',
        ]);

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
                'published_at' => now()->subMonths(3),
            ],
        );
        $this->attachSingleMedia($albumJubilaeum, 'cover', 'schuljubilaeum-25j-01.jpg');
        $this->attachGalleryMedia($albumJubilaeum, 'photos', [
            'schuljubilaeum-25j-01.jpg',
            'muehlmattentreffen-01.jpg',
            'blum-jubilaeum-01.jpg',
        ]);

        // --- Sämtliche 23 Original-Beiträge & Klassenaktivitäten von muehlmattenschule.de (ungekürzt) ---
        Post::query()->whereIn('slug', [
            'gemeinsamer-herbstwandertag-durch-den-mooswald',
            'vorlesetag-an-der-muehlmattenschule',
            'st-martins-und-laternenfest-auf-dem-schulhof',
        ])->delete();

        $postsData = [
            [
                'slug' => 'ausflug-der-4-klassen-zur-gruenholzwerkstatt-auf-dem-mundenhof',
                'title' => 'Ausflug der 4. Klassen zur Grünholzwerkstatt auf dem Mundenhof',
                'class_name' => 'Klasse 4a & 4b',
                'excerpt' => 'Die Klassen 4a und 4b waren im Januar auf dem Mundenhof bei der Grünholzwerkstatt des Spielmobils. Die Kinder durften drechseln, schnitzen und haben fünf eigene Berichte geschrieben.',
                'body' => '<p>Die Klassen 4a und 4b waren im Januar auf dem Mundenhof bei der Grünholzwerkstatt des Spielmobils. Die Kinder haben dazu eigene Berichte geschrieben:</p>'
                    .'<h3>Die Grünholzwerkstatt (Bericht von B. E.)</h3>'
                    .'<p>„Am 24. Januar war ich mit meiner Klasse in der Grünholzwerkstatt. Diese Werkstatt wurde vom Spielmobil veranstaltet. Wir durften drechseln, schnitzen und auf einem Schnitzesel mit einem Ziehmesser Stöcke in die richtige Form schneiden. Das Drechseln war auch ziemlich cool. Wir durften sogar mit dem Beil arbeiten. Aber mit diesen ganzen scharfen Sachen zu arbeiten ist eben auch nicht ungefährlich. Man kann sich dabei verletzen. Doch vor allem hatten wir sehr viel Spaß."</p>'
                    .'<h3>Die Grünholzwerkstatt (Bericht von F. W.)</h3>'
                    .'<p>„Am 24. Januar 2025 haben meine Klasse und ich einen Ausflug in die Grünholzwerkstatt gemacht. Dort gab es viele interessante Holzmaschinen, die beim Schnitzen halfen. Zuerst wurden uns die Regeln erklärt. Zum Beispiel nie zu unserem Körper schnitzen. Als wir endlich loslegen durften, rannte ich sofort zur Drechsel. Mit ihr kann man Vertiefungen ins Holz schnitzen und es machte mir viel Spaß. Ich wollte mir einen Zauberstab machen, aber am Ende wurde es ein Messer. Als wir gehen mussten, habe ich noch schnell mein Messer fertig gemacht. Als der Bus endlich kam, sind wir zur Schule zurückgefahren."</p>'
                    .'<h3>Die Grünholzwerkstatt (Bericht von L. T.)</h3>'
                    .'<p>„Es war der 24. Januar 2025. Die Klasse 4b ist ganz früh zum Mundenhof gefahren. Als die 4b an der Grünholzwerkstatt angekommen ist, haben wir uns auf Bierkisten gesetzt. Dann haben Torsten, Georg und Jonas uns was zum Schnitzen erklärt. Anschließend haben wir uns einen Stock genommen und losgeschnitzt. Georg hat uns dann noch eine andere Technik gezeigt. Das haben wir dann ausprobiert. Dann sind wir an die Geräte gegangen. Zuerst bin ich an den Drechsler gegangen. Da hat man einen Stock in zwei Schrauben eingeklemmt. Und da war auch so ein Brett, da musste man drauftreten. Man hatte dann auch Werkzeug, mit dem man so geschnitzt hat. Dann gab es noch das Ziehmesser. Da hat man den Stock zwischen zwei Holzhälften eingeklemmt und dann mit dem Ziehmesser Holzscheiben abgezogen. Dann hatte ich ein schönes Messer. Es gab dann noch so einen Bohrer. Da konnte man am Tisch einen Stock einklemmen und zum Beispiel was abschneiden oder was bohren. Es gab auch noch eine Axt und eine Station, wo man sich Stöcke zurechtsägen konnte. Ich fand alle Stationen toll."</p>'
                    .'<h3>In der Grünholzwerkstatt (Bericht von E. N.)</h3>'
                    .'<p>„Am 24. Januar hat das Spielmobil am Mundenhof die Grünholzwerkstatt für die Schule gezeigt. Sie haben mit den Kindern geschnitzt, gedrechselt und gesägt. Die Kinder hatten Spaß, waren sehr kreativ und jeder hat etwas anderes gemacht. Wir haben viel über Holz und Schnitzen gelernt."</p>'
                    .'<h3>Ausflug in den Mundenhof (Bericht von R. A.)</h3>'
                    .'<p>„Am 24. Januar lud das Spielmobil die Klasse 4b in die Grünholzwerkstatt ein. Die 4b schnitzte mit dem Messer, mit der Drechsel und mit anderen Geräten. Die 4b hat Messer, Dolche und Harry-Potter-Zauberstäbe geschnitzt. Sie hatten viel Spaß. Die drei Betreuer Georg, Jonas und Torsten staunten über die Werke und lobten sie, dass sie so ruhig und selbstständig gearbeitet hätten. Danach verabschiedeten sie sich von den drei Betreuern und stiegen in den Bus. Die geschnitzten Sachen nahmen sie natürlich mit. Als sie wieder an der Mühlmattenschule angekommen waren, hatten sie schöne Erinnerungen."</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => null,
                'gallery_images' => null,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 1,
                'cover' => 'about-kids.jpg',
            ],
            [
                'slug' => 'ferienplan-schuljahr-24-25',
                'title' => 'Ferienplan Schuljahr 2024/25',
                'class_name' => null,
                'excerpt' => 'Der aktuelle Ferienkalender der Mühlmattenschule steht ab sofort zum Ansehen und im Bereich Downloads als PDF bereit.',
                'body' => '<p>Der aktuelle Ferienkalender mit allen Ferienzeiten, beweglichen Ferientagen und Feiertagen steht für alle Familien bereit. Sie finden die druckbare PDF-Version jederzeit unter <a href="/downloads">Elternbriefe &amp; Formulare</a> oder direkt als <a href="/files/school/Ferienplan-2024-25.pdf" target="_blank" rel="noopener noreferrer">Ferienplan 2024/25 (PDF)</a>.</p>',
                'category_id' => $catFerien->id,
                'album_id' => null,
                'gallery_images' => ['/images/school/ferienplan-24-25.jpg'],
                'author_id' => $sekretariat->id,
                'is_featured' => true,
                'days_ago' => 3,
                'cover' => 'ferienplan-24-25.jpg',
            ],
            [
                'slug' => 'elterninfo-zum-schuljahresende-und-schuljahresbeginn',
                'title' => 'Elterninfo zum Schuljahresende und Start ins neue Schuljahr',
                'class_name' => null,
                'excerpt' => 'Wichtige Informationen zur Zeugnisausgabe, zum Unterrichtsschluss vor den Ferien sowie zu den Terminen in der ersten Schulwoche.',
                'body' => '<p>Liebe Eltern, mit diesem Elternbrief informieren wir Sie über den Ablauf der letzten Schultage vor den Sommerferien, die Ausgabe der Zeugnisse und Schulberichte in den Klassen 1 bis 4 sowie den Unterrichtsbeginn im neuen Schuljahr und die Einschulungsfeier unserer neuen Erstklässlerinnen und Erstklässler.</p><p>Alle aktuellen Elternbriefe und Informationen zur Schul-App „Stay Informed" stehen Ihnen jederzeit im Bereich <a href="/downloads">Elternbriefe &amp; Formulare</a> zur Verfügung.</p>',
                'category_id' => $catElterninfo->id,
                'album_id' => null,
                'gallery_images' => null,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 4,
                'cover' => 'slider-schuleinnen.jpg',
            ],
            [
                'slug' => '25-jaehriges-dienstjubilaeum-von-diana-blum',
                'title' => '25-jähriges Dienstjubiläum von Rektorin Diana Blum',
                'class_name' => null,
                'excerpt' => 'Rektorin Diana Blum feierte ihr 25-jähriges Dienstjubiläum. Sie ist seit September 2004 an der Mühlmattenschule und leitet diese seit dem Schuljahr 2017/2018.',
                'body' => '<p>Am 15. April feierte unsere Rektorin Diana Blum ihr 25-jähriges Dienstjubiläum im Schuldienst des Landes Baden-Württemberg. Sie unterrichtet bereits seit September 2004 an der Mühlmattenschule und leitet sie seit dem Schuljahr 2017/2018 mit großem Engagement. Das gesamte Kollegium und die Kinder gratulierten herzlich!</p>',
                'category_id' => $catFest->id,
                'album_id' => $albumJubilaeum->id,
                'gallery_images' => $blumJubilaeumUrls,
                'author_id' => $sekretariat->id,
                'is_featured' => true,
                'days_ago' => 6,
                'cover' => 'news-jubilaeum.jpg',
            ],
            [
                'slug' => 'der-vorstand-des-foerdervereins-stellt-sich-vor',
                'title' => 'Der Vorstand des Fördervereins stellt sich vor',
                'class_name' => null,
                'excerpt' => 'Im Rahmen der Jahreshauptversammlung wurden ein neuer Vorstand sowie ein neuer Beirat des Fördervereins der Mühlmattenschule gewählt.',
                'body' => '<p>Nach langjähriger aktiver ehrenamtlicher Unterstützung des Fördervereins wurden unsere bisherigen Vorstände Nils Henkel und Fabian Baumann im Rahmen der Jahreshauptversammlung feierlich verabschiedet. Gleichzeitig wurden ein neuer Vorstand sowie ein neuer Beirat gewählt: Giuseppe Pastore (1. Vorsitzender), Verena Prestel (2. Vorsitzende), Jan Gläser (Kassenwart), Steffie Nowak (Schriftführerin) sowie Florian Gutgsell und Ali Nemer (Beisitzer).</p>',
                'category_id' => $catElterninfo->id,
                'album_id' => null,
                'gallery_images' => ['/images/school/foerderverein-vorstand.jpg'],
                'author_id' => $admin->id,
                'is_featured' => true,
                'days_ago' => 8,
                'cover' => 'foerderverein-vorstand.jpg',
            ],
            [
                'slug' => 'fsj-stelle-sport-und-schule-an-der-muehlmattenschule',
                'title' => 'FSJ-Stelle „Sport und Schule" an der Mühlmattenschule',
                'class_name' => null,
                'excerpt' => 'Wir bieten engagierten jungen Menschen ein Freiwilliges Soziales Jahr (FSJ) im Bereich Sport und Schule an der Mühlmattenschule an.',
                'body' => '<p>Du hast Lust auf Bewegung, Sport und die Arbeit mit Grundschulkindern? An der Mühlmattenschule bieten wir eine FSJ-Stelle „Sport und Schule" an – mit Begleitung des Sportunterrichts, Unterstützung bei AGs, Pausenangeboten und Schulveranstaltungen wie unserem Crosslauf. Bewerbungen nimmt unser Sekretariat gerne entgegen.</p>',
                'category_id' => $catSport->id,
                'album_id' => null,
                'gallery_images' => ['/images/school/fsj-sport.jpg'],
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 10,
                'cover' => 'fsj-sport.jpg',
            ],
            [
                'slug' => 'weihnachtliche-muehlmattenschule-und-adventssingen',
                'title' => 'Weihnachtliche Mühlmattenschule & gemeinsames Adventssingen',
                'class_name' => null,
                'excerpt' => 'Weihnachtliche Stimmung und gemeinsames Adventssingen in der Mühlmattenschule – alle 12 Originalbilder und Eindrücke aus der Vorweihnachtszeit.',
                'body' => '<p>In der Vorweihnachtszeit verwandelte sich unser Schulhaus in eine festlich geschmückte Weihnachtswelt. Beim gemeinsamen Adventssingen unter dem großen Weihnachtsbaum musizierten und sangen alle Klassen gemeinsam.</p>',
                'category_id' => $catFest->id,
                'album_id' => $albumAdvent->id,
                'gallery_images' => $adventUrls,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 12,
                'cover' => 'news-advent-1.jpg',
            ],
            [
                'slug' => 'adventsbasteln-in-den-klassen-die-bilder',
                'title' => 'Adventsbasteln am 12. Dezember – alle 26 Bilder',
                'class_name' => null,
                'excerpt' => 'Ein Vormittag voller Kerzen, Papier, Tannenzweige und Klebstoff: Alle 26 Originalaufnahmen vom Adventsbasteln in den Klassen.',
                'body' => '<p>Beim traditionellen Adventsbasteln am 12. Dezember wurde in allen Klassenzimmern geschnitten, gefaltet und geklebt. Mit tatkräftiger Unterstützung vieler Eltern entstanden wunderschöne Dekorationen für die Adventszeit.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumAdvent->id,
                'gallery_images' => $bastelnUrls,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 14,
                'cover' => 'news-basteln-1.jpg',
            ],
            [
                'slug' => 'weihnachtsplaetzchenverkauf-auf-dem-hochdorfer-markt',
                'title' => 'Weihnachtsplätzchenverkauf auf dem Hochdorfer Markt',
                'class_name' => null,
                'excerpt' => 'Der Elternbeirat verkaufte auch dieses Jahr wieder auf dem Hochdorfer Markt selbstgebackene Weihnachtsplätzchen für die Elternbeiratskasse.',
                'body' => '<p>Dank der vielen Plätzchenspenden aus der Elternschaft konnte der Elternbeirat auf dem Hochdorfer Markt wieder zahlreiche Tüten selbstgebackener Weihnachtsplätzchen verkaufen. Der Erlös fließt direkt in Projekte für unsere Schülerinnen und Schüler.</p>',
                'category_id' => $catFest->id,
                'album_id' => $albumAdvent->id,
                'gallery_images' => $plaetzchenUrls,
                'author_id' => $sekretariat->id,
                'is_featured' => true,
                'days_ago' => 16,
                'cover' => 'news-plaetzchen.jpg',
            ],
            [
                'slug' => 'bewegungs-und-gesundheitstage-tanzen',
                'title' => 'Bewegungs- und Gesundheitstage (3): Tanzen mit der Tanzschule Gutmann',
                'class_name' => null,
                'excerpt' => 'Tanzaktion mit der Tanzschule Gutmann – einer der Höhepunkte unserer Bewegungs- und Gesundheitstage an der Mühlmattenschule.',
                'body' => '<p>Im Rahmen unserer Bewegungs- und Gesundheitstage fand eine mitreißende Tanzaktion mit der Tanzschule Gutmann statt. Alle Klassen studierten gemeinsam schwungvolle Tänze in der Sporthalle ein.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumBewegung->id,
                'gallery_images' => $tanzenUrls,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 18,
                'cover' => 'news-tanzen.jpg',
            ],
            [
                'slug' => 'bewegungs-und-gesundheitstage-kochen-mit-dem-slow-mobil',
                'title' => 'Bewegungs- und Gesundheitstage (2): Kochen und Essen mit dem Slow-Mobil',
                'class_name' => null,
                'excerpt' => 'Im bunten Küchenwagen des Slow-Mobils schnippelten, kochten und probierten die Kinder frische regionale Gerichte (alle 13 Fotos).',
                'body' => '<p>Ein besonderes Erlebnis unserer Bewegungs- und Gesundheitstage war der Besuch des Freiburger Slow-Mobils auf unserem Schulhof. In der rollenden Kinderküche bereiteten die Schülerinnen und Schüler aus frischem Gemüse köstliche Mahlzeiten zu und aßen anschließend gemeinsam an einer langen Tafel.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumBewegung->id,
                'gallery_images' => $slowmobilUrls,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 20,
                'cover' => 'slowmobil-1.jpg',
            ],
            [
                'slug' => 'bewegungs-und-gesundheitstage-spielmobil',
                'title' => 'Bewegungs- und Gesundheitstage (1): Aktionstag mit dem Spielmobil',
                'class_name' => null,
                'excerpt' => 'Nicht nur die Kinder, sondern auch die Lehrerinnen hatten großen Spaß mit den Bewegungs- und Geschicklichkeitsspielen des Spielmobils (alle 20 Fotos).',
                'body' => '<p>Alle zwei Jahre finden an der Mühlmattenschule die Bewegungs- und Gesundheitstage statt. Zum Auftakt verwandelte das Freiburger Spielmobil unseren Pausenhof in einen großen Bewegungsparcours mit Pedalos, Stelzen, Rollenrutsche und Teamspielen.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumBewegung->id,
                'gallery_images' => $spielmobilUrls,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 22,
                'cover' => 'spielmobil-2.jpg',
            ],
            [
                'slug' => 'gesundes-fruehstueck-bei-den-2-klassen',
                'title' => 'Gesundes Frühstück bei den 2. Klassen (alle 20 Bilder)',
                'class_name' => 'Klasse 2a & 2b',
                'excerpt' => 'Die 2. Klassen bereiteten gemeinsam im Unterricht ein buntes, gesundes Schulfrühstück mit frischem Obst, Gemüse und Vollkornbrot zu.',
                'body' => '<p>Im Sachunterricht beschäftigten sich die 2. Klassen intensiv mit gesunder Ernährung. Gemeinsam schnitten die Kinder Rohkost, richteten bunte Platten an und genossen das selbst zubereitete gesunde Frühstück im Klassenzimmer.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumErnaehrung->id,
                'gallery_images' => $fruehstueckUrls,
                'author_id' => $lehrkraft2b->id,
                'is_featured' => true,
                'days_ago' => 25,
                'cover' => 'fruehstueck-2kl-1.jpg',
            ],
            [
                'slug' => 'transfer-von-sprachbildung-lese-und-schreibfoerderung-biss-transfer',
                'title' => 'Transfer von Sprachbildung, Lese- und Schreibförderung (BiSS-Transfer)',
                'class_name' => null,
                'excerpt' => 'Die Mühlmattenschule nimmt an der bundesweiten Initiative BiSS-Transfer zur systematischen Stärkung der Lese- und Schreibkompetenzen teil.',
                'body' => '<p>BiSS-Transfer ist eine gemeinsame Initiative des Bundesministeriums für Bildung und Forschung (BMBF) und der Kultusministerkonferenz (KMK) zum Transfer von Sprachbildung, Lese- und Schreibförderung in Schulen und Kitas (<a href="https://www.biss-sprachbildung.de" target="_blank" rel="noopener noreferrer">www.biss-sprachbildung.de</a>).</p>'
                    .'<p>Ziel in Baden-Württemberg ist die <strong>Stärkung der Basiskompetenzen</strong> der Schülerinnen und Schüler im Bereich des Lesens. Im Mittelpunkt steht die <strong>Förderung der Lesefertigkeiten</strong> (Lesetechnik, Leseflüssigkeit) sowie der <strong>Lesefähigkeiten</strong> (Leseverstehen, Lesestrategien). Auf der Basis eines <strong>einheitlichen und systematischen Lesecurriculums</strong> setzen die BiSS-Grundschulen Schritt für Schritt nachweislich lernförderliche Elemente im Leseunterricht um, wie z. B. das Training der Leseflüssigkeit mithilfe von Lautlesetandems und den Einsatz ausgewählter Lesestrategien.</p>'
                    .'<p>Damit die Umsetzung in allen Klassenstufen gut funktioniert, hat jede BiSS-Grundschule eine eigene Projektgruppe eingerichtet, die sich regelmäßig trifft, um über nächste Schritte in der Umsetzung zu sprechen. Jede Schule hat zudem eine BiSS-Ansprechperson, die gemeinsam mit der Schulleitung das Kollegium an der Schule dabei unterstützt, das BiSS-Leseförderkonzept umzusetzen. Unsere BiSS-Ansprechperson an der Schule heißt <strong>Raimund Früh</strong>.</p>'
                    .'<p>In Baden-Württemberg nehmen insgesamt über 2.030 Grundschulen teil, die in 21 BiSS-Verbünden organisiert sind. Unsere Mühlmattenschule ist auch mit dabei und gehört dem Verbund Freiburg an.</p>',
                'category_id' => $catProjekte->id,
                'album_id' => null,
                'gallery_images' => ['/images/school/biss-transfer.jpg'],
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 28,
                'cover' => 'konzepte.jpg',
            ],
            [
                'slug' => 'bilder-vom-crosslauf-der-muehlmattenschule',
                'title' => 'Bilder vom Crosslauf & Sponsorenlauf der Mühlmattenschule (alle 44 Fotos)',
                'class_name' => null,
                'excerpt' => 'Olympische Stimmung in Hochdorf: Beim Schülerlauf und anschließenden Sponsorenlauf gaben alle Kinder ihr Bestes – hier sind alle 44 Bilder.',
                'body' => '<p>Beim traditionellen Crosslauf der Mühlmattenschule liefen alle Jahrgangsstufen um die Wette, angefeuert von zahlreichen Eltern, Großeltern und Lehrkräften. Nach der Siegerehrung folgte der Sponsorenlauf für den guten Zweck sowie ein gemütliches Beisammensein mit Tombola.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumCrosslauf->id,
                'gallery_images' => $crosslaufUrls,
                'author_id' => $schulleitung->id,
                'is_featured' => true,
                'days_ago' => 30,
                'cover' => 'crosslauf-1.jpg',
            ],
            [
                'slug' => 'gesunde-ernaehrung-fuer-kater-cook-obstsalat-in-der-2b',
                'title' => 'Gesunde Ernährung für Kater Cook – Obstsalat in der 2b',
                'class_name' => 'Klasse 2b',
                'excerpt' => 'Die Klasse 2b hat im Rahmen des Themas gesunde Ernährung für Kater Cook nach viel Schnippelarbeit einen leckeren Obstsalat zubereitet.',
                'body' => '<p>Die Klasse 2b hat im Rahmen des Unterrichtsthemas „Gesunde Ernährung für Kater Cook" ein weiteres Rezept ausprobiert. Nach viel fleißiger Schnippelarbeit haben die Kinder einen frischen Obstsalat zubereitet und dazu das passende Rezept im Unterricht aufgeschrieben.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumErnaehrung->id,
                'gallery_images' => [
                    '/images/school/obstsalat-2b-1.jpg',
                    '/images/school/obstsalat-2b-2.jpg',
                    '/images/school/obstsalat-2b-3.jpg',
                ],
                'author_id' => $lehrkraft2b->id,
                'is_featured' => true,
                'days_ago' => 35,
                'cover' => 'obstsalat-2b-2.jpg',
            ],
            [
                'slug' => 'lustige-brotgesichter-in-der-2b',
                'title' => 'Lustige Brotgesichter in der Klasse 2b',
                'class_name' => 'Klasse 2b',
                'excerpt' => 'Die Klasse 2b hat im Unterricht lustige Brotgesichter gestaltet – die sahen nicht nur gut aus, sondern haben auch tierisch lecker geschmeckt.',
                'body' => '<p>Mit Vollkornbrot, Frischkäse, Gurken, Paprika, Karotten und Schnittlauch gestalteten die Kinder der Klasse 2b fantasievolle und lustige Brotgesichter. Sie sahen nicht nur toll aus, sondern schmeckten beim gemeinsamen Essen auch hervorragend!</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumErnaehrung->id,
                'gallery_images' => [
                    '/images/school/brotgesichter-2b-1.jpg',
                    '/images/school/brotgesichter-2b-2.jpg',
                    '/images/school/brotgesichter-2b-3.jpg',
                ],
                'author_id' => $lehrkraft2b->id,
                'is_featured' => true,
                'days_ago' => 40,
                'cover' => 'brotgesichter-2b-1.jpg',
            ],
            [
                'slug' => 'die-chorvereinigung-hochdorf-informiert',
                'title' => 'Kooperation mit der Chorvereinigung Hochdorf',
                'class_name' => null,
                'excerpt' => 'Seit 2018 besteht zwischen der Mühlmattenschule und der Chorvereinigung Hochdorf eine lebendige Kooperation, um Grundschulkinder fürs Singen zu begeistern.',
                'body' => '<p>Liebe Eltern, seit 2018 besteht zwischen der Mühlmattenschule und der Chorvereinigung Hochdorf eine Kooperation. Ziel dieser Kooperation ist, Kinder für das Singen zu begeistern.</p>'
                    .'<p>Wir freuen uns, dass wir zur Unterstützung von Frau Caputo die Gesangslehrerin Annette Riesterer aus Freiburg gewinnen konnten. Der Zuspruch ist groß und es macht allen viel Spaß. Ein Einstieg ist jederzeit möglich. Wir hoffen, dass weiterhin viele Kinder daran teilnehmen und dass sich die Gruppe weiter vergrößert.</p>'
                    .'<p>Wir haben großes Interesse daran, dass Kinder Freude am Singen erlangen. Vielleicht treten sie später aufgrund dieses Projektes einem Chor bei, wo immer sie sich niederlassen. Beim großen Jahreskonzert in der Werkshalle in Benzhausen treten die Kinder gemeinsam mit 80 Sängerinnen und Sängern auf.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => null,
                'gallery_images' => ['/images/school/ag-chor.jpg'],
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 50,
                'cover' => 'ag-chor.jpg',
            ],
            [
                'slug' => 'schulwegeplan-hochdorf-benzhausen-und-gewerbegebiet-hochdorf',
                'title' => 'Schulwegeplan für Hochdorf, Benzhausen und das Gewerbegebiet',
                'class_name' => null,
                'excerpt' => 'Sicher zur Schule: Der Schulwegeplan der Mühlmattenschule zeigt empfohlene Fußwege, Ampeln und sichere Querungshilfen im gesamten Schulbezirk.',
                'body' => '<p>Ein sicherer Schulweg gibt Eltern und Kindern gleichermaßen Sicherheit. Die Mühlmattenschule stellt allen Familien den detaillierten Schulwegeplan für Hochdorf, Benzhausen und das Gewerbegebiet bereit. Sie finden die Übersichtskarte sowie die Sicherheitshinweise im Bereich <a href="/downloads">Downloads</a> als PDF-Dokumente.</p>',
                'category_id' => $catElterninfo->id,
                'album_id' => null,
                'gallery_images' => ['/images/school/unsere-schule-karte.jpg'],
                'author_id' => $sekretariat->id,
                'is_featured' => false,
                'days_ago' => 60,
                'cover' => 'unsere-schule-karte.jpg',
            ],
            [
                'slug' => 'zeitung-in-der-schule-artikel-der-klassen-4a-und-4b',
                'title' => 'Projekt „Zeitung in der Schule": Artikel der Klassen 4a und 4b',
                'class_name' => 'Klasse 4a & 4b',
                'excerpt' => 'Im Rahmen des Zisch-Projekts der Badischen Zeitung recherchierten und verfassten die Klassen 4a und 4b eigene Zeitungsartikel über die Mühlmattenschule.',
                'body' => '<p>Am Zeitungsprojekt „Zisch – Zeitung in der Schule" der Badischen Zeitung nahmen unsere vierten Klassen mit großer Begeisterung teil. Die Schülerinnen und Schüler lasen täglich die Tageszeitung, lernten journalistische Darstellungsformen kennen und verfassten eigene Zeitungsbeiträge über unsere Schule und das Leben in Hochdorf.</p>',
                'category_id' => $catProjekte->id,
                'album_id' => null,
                'gallery_images' => [
                    '/images/school/zeitung-4a-duttlinger.jpg',
                    '/images/school/zeitung-4b-jung.jpg',
                ],
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 65,
                'cover' => 'zeitung-4a-duttlinger.jpg',
            ],
            [
                'slug' => 'sonderpreis-fuer-aussergewoehnlichen-unterricht-im-home-schooling-fuer-johannes-stang',
                'title' => 'Sonderpreis für außergewöhnlichen Unterricht für Johannes Stang',
                'class_name' => null,
                'excerpt' => 'Unser Kollege Johannes Stang wurde beim Wettbewerb „Freiburgs Lehrer*in des Jahres" mit einem Sonderpreis für digitale Unterrichtsinnovation ausgezeichnet.',
                'body' => '<p>Unser Kollege Johannes Stang erhielt beim Wettbewerb „Freiburgs Lehrer*in des Jahres" – vom Magazin f79 des chilli-Verlages und dem Radiosender baden.FM vergeben – einen von drei Sonderpreisen für digitale Innovation. Herr Stang hat Dritt- und Viertklässler mit selbst gedrehten Kurzfilmen unterrichtet und witzige Lerninhalte präsentiert. Herzlichen Glückwunsch!</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => null,
                'gallery_images' => [
                    '/images/school/sonderpreis-fuer-aussergewoehnlichen-unterricht-im-home-schooling-fuer-johannes-stang.jpg',
                ],
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 70,
                'cover' => 'sonderpreis-fuer-aussergewoehnlichen-unterricht-im-home-schooling-fuer-johannes-stang.jpg',
            ],
            [
                'slug' => 'inklusives-beschulungsmodell-kooperative-organisationsform',
                'title' => 'Inklusives Beschulungsmodell: Kooperative Organisationsform (Chamäleonklasse)',
                'class_name' => 'Außenklasse',
                'excerpt' => 'Gemeinsam lernen und voneinander profitieren: In der kooperativen Organisationsform lernen Grundschulkinder und Kinder der Richard-Mittermaier-Schule zusammen.',
                'body' => '<p>An der Mühlmattenschule lernen Schülerinnen und Schüler der Grundschule (grüne Chamäleons) und Kinder der Richard-Mittermaier-Schule (gelbe Chamäleons) in einer kooperativen Organisationsform gemeinsam.</p>'
                    .'<p>Das Team besteht aus Grundschullehrkräften, Sonderpädagoginnen (Frau Dingler) und einer Praktikantin im Freiwilligen Sozialen Jahr. In der gemeinsamen Klasse lernen alle Schülerinnen und Schüler miteinander und vor allem voneinander. Alle Kinder sind verschieden – es ist unser Ziel, dass alle Kinder nach ihren Bedürfnissen und Fähigkeiten lernen dürfen und unterrichtet werden. Unser Leitgedanke lautet:</p>'
                    .'<p><strong><em>„So viel gemeinsamer Unterricht wie möglich und so viel Individualförderung wie nötig."</em></strong></p>'
                    .'<p>Offene Unterrichtsmethoden wie projektorientierter Unterricht mit dem Arbeiten an Lernstationen, Freiarbeitsmaterialien oder Wochenplanarbeit kommen allen Kindern im gemeinsamen Unterricht sehr entgegen: <em>„Jeder Mensch ist einzigartig, jeder hat Stärken und Schwächen – nur durch Verschiedenheit können wir eine bunte Gesellschaft werden."</em></p>',
                'category_id' => $catInklusion->id,
                'album_id' => null,
                'gallery_images' => ['/images/school/slider-schuleinnen.jpg'],
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 80,
                'cover' => 'slider-schuleinnen.jpg',
            ],
            [
                'slug' => '40-jaehriges-dienstjubilaeum-von-edith-jakob',
                'title' => '40-jähriges Dienstjubiläum von Edith Jakob & Frau Weis',
                'class_name' => null,
                'excerpt' => 'Feierliche Übergabe der Dankesurkunden des Landes Baden-Württemberg für 40 Jahre engagierten Dienst als Lehrerinnen in Hochdorf.',
                'body' => '<p>Feierlich wurden im Lehrerzimmer der Mühlmattenschule die Dankesurkunden für das 40-jährige Dienstjubiläum an unsere langjährigen Kolleginnen Frau Edith Jakob und Frau Weis übergeben. Auch das Staatliche Schulamt Freiburg, die Schulleitung und das gesamte Kollegium gratulierten herzlich für Jahrzehnte engagierter pädagogischer Arbeit in Hochdorf.</p>',
                'category_id' => $catFest->id,
                'album_id' => null,
                'gallery_images' => [
                    '/images/school/40-jaehriges-dienstjubilaeum-von-edith-jakob.jpg',
                    '/images/school/40jaehriges-dienstjubilaeum-frau-weis.jpg',
                ],
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 85,
                'cover' => '40-jaehriges-dienstjubilaeum-von-edith-jakob.jpg',
            ],
            [
                'slug' => '25-jahre-muehlmattenschule',
                'title' => '25 Jahre Mühlmattenschule & 1. Mühlmattentreffen in der Aula',
                'class_name' => 'Klasse 3a',
                'excerpt' => 'Mit einem großen Schulfest, Zirkusvorstellung der Klasse 2a, Festakt, Mühlmattentreffen und Schülerläufen feierte die Mühlmattenschule ihr Jubiläum.',
                'body' => '<p>Am 25. Juni feierte die Mühlmattenschule ihr 25-jähriges Jubiläum. Zuerst gab es einen Festakt, bei dem viele Kinder etwas vorführten. Die Klasse 2a hat eine tolle Zirkusvorstellung aufgeführt, andere Klassen haben gesungen und getanzt. Danach gab es Sekt für die Erwachsenen und Saft für die Kinder. Anschließend fand wie jedes Jahr der Crosslauf und gleichzeitig eine Tombola statt. Es gab Würstchen, Salate, Kaffee und Kuchen. Nach dem Crosslauf begann die Siegerehrung und die Medaillen und der Klassenpokal wurden verteilt. Zum Schluss fand noch der Sponsorenlauf statt, bei dem viele Kinder und Erwachsene mitgemacht haben. Trotz Unwetter in der Nacht davor war es ein tolles Fest! <em>(Geschrieben von Anna, Elena und Julius, Klasse 3a)</em></p>'
                    .'<h3>1. Mühlmattentreffen in der Aula</h3>'
                    .'<p>Bei den regelmäßigen Mühlmattentreffen kommen alle Kinder und Lehrkräfte in der Aula zusammen, um das Gemeinschaftsgefühl zu stärken – mit dem Mühlmattenlied, Vorlesebeiträgen der Klassen, Urkunden für gesammelte Bonuspunkte und Auftritten unseres Schulchors.</p>',
                'category_id' => $catFest->id,
                'album_id' => $albumJubilaeum->id,
                'gallery_images' => [...$jubilaeum25Urls, ...$muehlmattentreffenUrls],
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 90,
                'cover' => 'schuljubilaeum-25j-01.jpg',
            ],
        ];

        foreach ($postsData as $postItem) {
            $coverFile = $postItem['cover'];
            $daysAgo = $postItem['days_ago'];
            unset($postItem['cover'], $postItem['days_ago']);

            $post = Post::query()->updateOrCreate(
                ['slug' => $postItem['slug']],
                [
                    ...$postItem,
                    'status' => PublishStatus::Published,
                    'published_at' => now()->subDays($daysAgo),
                ],
            );

            $this->attachSingleMedia($post, 'cover', $coverFile);
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
                'role_title' => 'Klassenlehrer & BiSS-Ansprechperson',
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
            // Schulsozialarbeit (aktualisiert gem. /wp-content/Schulsozialarbeit/index.html)
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

        // --- Sämtliche 22 Schulseiten (alle Hauptmenü- und Untermenüpunkte von muehlmattenschule.de ungekürzt) ---
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
                            'heading' => 'Geschichte und Entwicklung der Mühlmattenschule',
                            'body' => '<p>Alle Kinder aus dem Freiburger Stadtteil Hochdorf (mit den Ortschaften Benzhausen, Hochdorf und dem Industrie- und Gewerbegebiet Hochdorf, zusammen ca. 5.200 Einwohnerinnen und Einwohner) besuchen die Mühlmattenschule.</p>'
                                .'<p>Infolge der Eingemeindung Anfang der 1970er-Jahre stieg die Einwohnerzahl der ehemaligen Gemeinde Hochdorf stark an. Durch den Zuzug vieler junger Familien wurde die frühere Dorfschule, die „Michael-Denzlinger-Schule", zu klein. Eine neue Schule wurde gebaut und <strong>1991</strong> bezogen. Benannt wurde sie nach dem Gewann, in dem sie liegt: <strong>„Mühlmattenschule"</strong>. In schneller Folge musste die Schule zweimal erweitert werden (1993/94 und 1996/97). Im Schuljahr 1997/98 wurden zu Spitzenzeiten 344 Kinder in 14 Klassen unterrichtet.</p>'
                                .'<p>Aktuell werden rund 187 Kinder in neun Grundschulklassen und einer kooperativen Außenklasse von 20 Lehrerinnen und Lehrern sowie Lehramtsanwärterinnen und -anwärtern unterrichtet.</p>',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Lage, Schulgebäude und Gelände',
                            'body' => '<p>Die Grundschule Mühlmattenschule befindet sich in Hochdorf am Rande des Ortszentrums und grenzt unmittelbar an das Naturschutzgebiet „Mühlmatten". Damit ist die Schule sehr ruhig gelegen und dennoch für alle Hochdorfer Kinder sicher erreichbar. Optimal ist auch die unmittelbare Nähe zu den Sportstätten: Hallenbad und Mooswaldhalle liegen keine hundert Meter von der Schule entfernt und können zu Fuß in wenigen Minuten erreicht werden.</p>'
                                .'<p><strong>Helligkeit und Holz:</strong> Holz ist das optisch dominierende Element der Architektur des einstöckigen Schulgebäudes. Dank der durchgängigen Oberlichter im Flur gelangt – auch wenn die Sonne nicht scheint und in der dunklen Jahreszeit – immer viel natürliches Tageslicht ins Innere der Schule. Helligkeit und Holz schaffen zusammen eine warme, angenehme und freundliche Atmosphäre, die wirkt, sobald man in die Schule eintritt. Am südlichen Ende des Gebäudes befinden sich, abgetrennt durch eine Glastür, die Räume der Schulkindbetreuung.</p>'
                                .'<p>Auf dem weitläufigen Schulgelände gibt es viel Platz und Angebote zum Austoben in den Pausenzeiten und während der Nachmittagsbetreuung, darunter eine Spielanlage mit Edelstahlrutsche, Kletterwand, Schulsportgelände und der Bachlauf am östlichen Rand des Schulhofs. <em>(Die Luftbilder wurden uns freundlicherweise vom Ballonteam Norbert Blau zur Verfügung gestellt.)</em></p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Schulbezirk, Schulhaus und Umgebung',
                            'image_1_url' => '/images/school/unsere-schule-karte.jpg',
                            'image_1_caption' => 'Übersichts- und Schulbezirkskarte Hochdorf mit Benzhausen.',
                            'image_2_url' => '/images/school/unsere-schule-innen.jpg',
                            'image_2_caption' => 'Helle Flure mit Holzelementen und Oberlichtern prägen das Innere der Schule.',
                            'image_3_url' => '/images/school/unsere-schule-umgegend.jpg',
                            'image_3_caption' => 'Luftbild der Mühlmattenschule am grünen Ortsrand von Hochdorf.',
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
                            'title' => 'Fragen zu unserer Schule?',
                            'text' => 'Unser Sekretariat ist Montag, Mittwoch und Freitag von 7:30 bis 12:00 Uhr erreichbar · Telefon: 07665 2232 · E-Mail: sekretariat.mmsvn@freiburger-schulen.bwl.de',
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
                            'image_url' => '/images/school/kollegium-wide.jpg',
                            'caption' => 'Das Kollegium der Mühlmattenschule Freiburg-Hochdorf.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Unser Kollegium auf dem Gruppenbild',
                            'body' => '<p><em>Vordere Reihe (von links nach rechts):</em> Frau Sterzik (4b), Frau Garrido (4a), Frau Bett (1a), Frau Pfattner, Frau Wastell.</p>'
                                .'<p><em>Mittlere Reihe:</em> Frau Blum (Rektorin), Herr Früh (4c), Frau Piess (3b), Frau Jakoby (2b), Frau Caputo (Konrektorin), Frau Bürger (3a), Frau Neumann.</p>'
                                .'<p><em>Hintere Reihe:</em> Frau Götten (1b), Frau Marquardt, Frau Borgas, Frau Huber (3c), Frau Eder (2a), Frau Dingler (Außenklasse), Herr Markert (Schulsozialarbeiter).</p>',
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
                            'body' => '<p>Die Mühlmattenschule umfasst neun Grundschulklassen von Jahrgangsstufe 1 bis 4 sowie eine kooperative Außenklasse. Jede Klasse wird von einer festen Klassenlehrerin oder einem festen Klassenlehrer begleitet und durch Fachlehrkräfte unterstützt.</p>',
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
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Team & Kontakt der Schulsozialarbeit',
                            'group' => PersonGroup::Sozialarbeit->value,
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Herzlich willkommen bei der Schulsozialarbeit!',
                            'body' => '<p>Wir heißen <strong>Philipp Markert</strong> und <strong>Nina Ponzelar</strong> und sind an der Mühlmattenschule als Schulsozialarbeiter tätig. Träger der Schulsozialarbeit ist der <strong>Caritasverband Freiburg-Stadt e.V.</strong></p>'
                                .'<h3>1. Klassen- und Gruppenangebote</h3>'
                                .'<p>Wir begleiten die Klassen im Schulalltag und führen sozialpädagogische Projekte zur Stärkung der Klassengemeinschaft, zum Umgang mit Gefühlen, zur Konfliktlösung und zur Gewaltprävention durch. Darüber hinaus gestalten wir den wöchentlichen Klassenrat sowie Arbeitsgemeinschaften und bewegte Pausenangebote (z. B. Sport-AG, Computer-AG, Ringen &amp; Raufen, Entspannungspause).</p>'
                                .'<h3>2. Vertrauliche Beratung für Kinder, Eltern und Lehrkräfte</h3>'
                                .'<p>Kinder können jederzeit mit kleinen und großen Sorgen zu uns kommen – etwa bei Streit in der Pause, Problemen in der Klasse oder Kummer zu Hause. Eltern beraten wir vertraulich bei Erziehungs- und Lebensfragen, beim Übergang in weiterführende Schulen oder bei der Vermittlung passender Unterstützungsangebote (z. B. Bildung und Teilhabe / BuT).</p>'
                                .'<h3>3. Kooperation und Netzwerkarbeit</h3>'
                                .'<p>Als Schulsozialarbeiter unterliegen wir der gesetzlichen Schweigepflicht (§ 203 StGB) und behandeln alle Gespräche vertraulich. Wir arbeiten eng mit Schulleitung, Lehrkräften, der Schulkindbetreuung sowie Beratungsstellen und Vereinen im Stadtteil Hochdorf zusammen. Unser Büro befindet sich in <strong>Zimmer 18</strong> – Sprechzeiten gerne nach Vereinbarung.</p>',
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
            // --- Gremien, Eltern & Partner unter Unsere Schule ---
            [
                'title' => 'Elternbeirat',
                'slug' => 'elternbeirat',
                'section' => 'unsere-schule',
                'menu_group' => 'Gremien & Partner',
                'menu_order' => 6,
                'summary' => 'Bindeglied zwischen Elternschaft und Schule nach § 57 Schulgesetz BW.',
                'content' => [
                    [
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/elternbeirat-team-historisch.jpg',
                            'caption' => 'Der Elternbeirat der Mühlmattenschule im Einsatz für die Schulgemeinschaft.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Über den Elternbeirat der Mühlmattenschule',
                            'body' => '<p>Als <strong>Bindeglied zwischen Eltern und Schule</strong> nimmt der Elternbeirat Anregungen und Kritik aus der Elternschaft auf, berät sie und trägt sie der Schulleitung vor. Die gewählten Klassenelternvertreterinnen und -vertreter aller Klassen bilden zusammen den Gesamtelternbeirat.</p>'
                                .'<p>Erfahren Sie mehr auf unseren beiden Detailseiten zu den <a href="/unsere-schule/aufgaben-des-elternbeirats">Aufgaben des Elternbeirats</a> sowie zu den vielfältigen <a href="/unsere-schule/aktivitaeten-des-elternbeirats">Aktivitäten des Elternbeirats</a> im Schuljahresverlauf.</p>',
                        ],
                    ],
                    [
                        'type' => 'info_box',
                        'data' => [
                            'title' => 'Leitfaden & Protokolle für Elternbeiratssitzungen',
                            'text' => 'Der offizielle Leitfaden für Gesamtelternbeiratssitzungen und Kurzprotokolle stehen im Bereich Downloads als PDF bereit.',
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
                            'heading' => 'Aufgaben des Elternbeirats (§ 57 Schulgesetz BW)',
                            'body' => '<p>Als <strong>Bindeglied zwischen Eltern und Schule</strong> nimmt der Elternbeirat Anregungen und Kritik aus der Elternschaft auf, berät sie und trägt sie der Schulleitung vor.</p>'
                                .'<p>Der Elternbeirat hat <strong>beratende Funktion in allgemeinen Schulangelegenheiten</strong>. Er entsendet Vertreterinnen und Vertreter zur Schulkonferenz und zum städtischen Gesamtelternbeirat der Stadt Freiburg.</p>'
                                .'<p>Die Vorsitzenden des Elternbeirats treten in der Regel <strong>alle zwei Monate zu einem Jour Fixe mit der Schulleitung</strong> zusammen. Diese Sitzungen dienen der Besprechung der aktuellen Lage, dem Informationsaustausch, dem Entwickeln neuer Ideen und der Lösung von Anliegen an der Mühlmattenschule.</p>'
                                .'<p>Die Elternbeiräte treffen sich mindestens zweimal im Schuljahr zur <strong>Gesamtelternbeiratssitzung</strong>. Viele Veranstaltungen und Aktivitäten werden vom Elternbeirat mitgestaltet oder unterstützt.</p>',
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
                            'heading' => 'Aktivitäten des Elternbeirats im Jahreslauf',
                            'body' => '<p>Der Elternbeirat organisiert und wirkt bei folgenden traditionellen Aktivitäten der Mühlmattenschule mit:</p>'
                                .'<h3>Musiknachmittag</h3>'
                                .'<p>Der Musiknachmittag wurde vom Elternbeirat der Mühlmattenschule ins Leben gerufen. Kinder, die ein Instrument spielen, im Schulchor singen oder sich anderweitig musikalisch einbringen wollen, haben an diesem Nachmittag die Möglichkeit, ihr Können in vertrautem Rahmen dem Publikum zu präsentieren. Für das leibliche Wohl wird in den Pausen und nach der Veranstaltung vom Elternbeirat bestens gesorgt.</p>'
                                .'<h3>Fastnacht</h3>'
                                .'<p>Beim beliebten und bunten Fastnachtstreiben an der Mühlmattenschule sorgt der Elternbeirat zusammen mit helfenden Eltern für das leibliche Wohl und unterstützt die Lehrkräfte bei der Betreuung der verschiedenen Spielstationen in den Klassenzimmern.</p>'
                                .'<h3>Nikolauspäckchen &amp; Plätzchenverkauf</h3>'
                                .'<p>Das alljährliche Packen und Verteilen von Nikolauspäckchen für alle Schülerinnen, Schüler und Lehrkräfte durch den Elternbeirat gehört an der Mühlmattenschule zur guten Tradition. Zudem verkauft der Elternbeirat im Advent selbstgebackene Weihnachtsplätzchen auf dem Hochdorfer Markt zugunsten der Elternbeiratskasse.</p>'
                                .'<h3>Einschulungsfeier</h3>'
                                .'<p>Bei der Einschulung der neuen Erstklässlerinnen und Erstklässler übernimmt der Elternbeirat traditionell die Bewirtung der Elternschaft und der Gäste mit Sekt, Säften, Laugenteilen und Zopf während der ersten Unterrichtsstunde der Kinder.</p>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/foerderverein-vorstand.jpg',
                            'caption' => 'Der Vorstand des Fördervereins der Mühlmattenschule.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V.',
                            'body' => '<p>Der Förderverein der Mühlmattenschule wurde im Jahr 1996 gegründet und ist im Vereinsregister Freiburg Nr. 2943 als gemeinnütziger Verein eingetragen. Wir haben das Ziel der ideellen und materiellen Förderung der Bildung und Erziehung der Kinder der Mühlmattenschule.</p>'
                                .'<h3>Was wollen wir fördern und was unterstützen wir?</h3>'
                                .'<ul>'
                                .'<li>Stärkung des WIR-Gefühls der Grundschülerinnen und Grundschüler</li>'
                                .'<li>Förderung des Sports sowie Organisation und Durchführung des jährlichen <strong>Crosslaufs</strong></li>'
                                .'<li>Gestaltung der Schule und des Schulhofs sowie Förderung von Arbeitsgemeinschaften</li>'
                                .'<li>Einschulungsgeschenk, Geburtstagsgeschenk für alle Schulkinder und Adventskranz für die Schule</li>'
                                .'<li>Finanzielle Unterstützung von Autorenlesungen, Theaterbesuchen aller Klassen, Schulprojekten und dem Schüleraustausch mit Colmar</li>'
                                .'<li>Unbürokratische finanzielle Unterstützung für Familien bei Klassenfahrten und schulischen Aktivitäten</li>'
                                .'</ul>'
                                .'<h3>Vorstand &amp; Ansprechpartner</h3>'
                                .'<p><strong>1. Vorsitzender:</strong> Giuseppe Pastore · <strong>2. Vorsitzende:</strong> Verena Prestel · <strong>Kassenwart:</strong> Jan Gläser · <strong>Schriftführerin:</strong> Steffie Nowak · <strong>Beisitzer:</strong> Florian Gutgsell, Ali Nemer.<br />Kontakt: Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V., Zu den Mühlmatten 8, 79108 Freiburg i. Br., Tel.: 07665 / 2232, E-Mail: foeve.mms@gmail.com</p>'
                                .'<h3>Wie können Sie fördern?</h3>'
                                .'<p>Ihren Mitgliedsbeitrag können Sie selbst bestimmen (der jährliche Mindestbeitrag beträgt 17,– Euro und ist gemäß § 10 EStG steuerlich absetzbar). Die Beitrittserklärung finden Sie jederzeit im Bereich <a href="/downloads">Elternbriefe &amp; Formulare</a> zum Herunterladen.</p>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/crosslauf-1.jpg',
                            'caption' => 'Start beim traditionellen Crosslauf der Mühlmattenschule.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Crosslauf – Das große Schulsportfest seit 1998',
                            'body' => '<p>Der Crosslauf ist ein Schulsportfest für die ganze Familie, veranstaltet vom Förderverein in Kooperation mit der Mühlmattenschule. Er findet in jedem Schuljahr im Mai oder Juni an einem Samstag statt. Die Laufstrecke ist ein ca. 450 Meter langer Rundkurs entlang des Mühlbachs. Die Kinder trainieren im Rahmen des Sportunterrichts und laufen jahrgangsweise, Jungen und Mädchen getrennt.</p>'
                                .'<h3>Wertung &amp; Mühlmattenpokal</h3>'
                                .'<p>Jedes Kind, das das Ziel erreicht, erhält eine Urkunde und ein Gratislos für die große Tombola. Die ersten drei Jungen und Mädchen pro Jahrgang erhalten zusätzlich eine Medaille (Gold, Silber, Bronze – wie bei Olympia!). Obendrauf erhält die teilnehmerstärkste Klasse den begehrten <strong>„Mühlmattenpokal"</strong> gefüllt mit Süßigkeiten.</p>'
                                .'<h3>Sponsorenlauf – Laufen, Stempeln, Gutes tun</h3>'
                                .'<p>Auf den Crosslauf folgt der Sponsorenlauf als Benefizrennen: Eltern, Großeltern, Geschwister, Lehrkräfte und Kinder drehen eine Stunde lang Runde für Runde um die Schule und sammeln pro Runde einen Stempel auf ihrer Laufkarte. Die Klasse mit den meisten gelaufenen Runden erhält einen Sonderpreis für die Klassenkasse. Allein beim Lauf 2022 traten 82 Läuferinnen und Läufer an und liefen zusammen 830 Runden (rund 400 km)!</p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Streckenkarte und Eindrücke vom Schulsportfest',
                            'image_1_url' => '/images/school/crosslauf-karte-2023.png',
                            'image_1_caption' => 'Aktuelle Strecken- und Rundkurskarte des Crosslaufs.',
                            'image_2_url' => '/images/school/crosslauf-postecollage.png',
                            'image_2_caption' => 'Stationen und Impressionen entlang der Laufstrecke.',
                            'image_3_url' => '/images/school/crosslauf-3.jpg',
                            'image_3_caption' => 'Voller Einsatz beim Schüler- und Sponsorenlauf.',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/signet-hochschulpartnerschule.jpg',
                            'caption' => 'Offizielles Signet der Hochschulpartnerschulen (FACE Praxiskolleg).',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'FACE Praxiskolleg & Integriertes Semesterpraktikum (ISP)',
                            'body' => '<p>Die Bezeichnung <strong>„Hochschulpartnerschule"</strong> erhalten Schulen, die mit der Pädagogischen Hochschule Freiburg und der Albert-Ludwigs-Universität Freiburg im Rahmen des „Praxiskollegs" unter dem Dach des <em>Freiburg Advanced Center of Education (FACE)</em> eng zusammenarbeiten.</p>'
                                .'<p><strong>Umsetzung an unserer Schule:</strong> Wir, die Mühlmattenschule, betreuen bereits seit März 2013 Studierende der Pädagogischen Hochschule Freiburg in ihrem 15- bis 16-wöchigen „Integrierten Semesterpraktikum" (ISP). Die Studierenden werden durch unsere Ausbildungsberaterin <strong>Elvira Piess</strong> sowie die Ausbildungslehrkräfte des Kollegiums aktiv in den Schulalltag und das Unterrichten eingebunden.</p>'
                                .'<p>Durch die Verzahnung von aktueller Bildungsforschung und gelebter Schulpraxis wirken wir aktiv an einer fundierten Ausbildung der Lehrerinnen und Lehrer von morgen mit und gewinnen zugleich wertvolle Impulse für unsere eigene Schul- und Unterrichtsentwicklung.</p>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/leitbild-schema.jpg',
                            'caption' => 'Das pädagogische Leitbild der Mühlmattenschule Freiburg-Hochdorf.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Leitbild der Mühlmattenschule',
                            'body' => '<p>Die Mitglieder der Schulkonferenz – Eltern sowie Lehrerinnen und Lehrer gemeinsam – haben unter Anleitung einer Prozessbegleiterin das Leitbild der Mühlmattenschule erarbeitet. Zu den Leitsätzen entwickelt das Kollegium kontinuierlich konkrete Projekte und Maßnahmen, damit diese jeden Tag im Schulalltag lebendig werden:</p>'
                                .'<ul>'
                                .'<li><strong>Kompetenzraster &amp; individuelle Lernwege:</strong> Kinder dort abholen, wo sie stehen</li>'
                                .'<li><strong>Stärken stärken – Umgang mit Heterogenität:</strong> Vielfalt als Bereicherung erleben</li>'
                                .'<li><strong>Regelmäßige Mühlmattentreffen:</strong> Gemeinsame Versammlungen aller Schülerinnen, Schüler und Lehrkräfte in der Aula</li>'
                                .'<li><strong>Wöchentlicher Klassenrat:</strong> Demokratie und Mitbestimmung in allen Klassen</li>'
                                .'<li><strong>Projekthafter und klassenübergreifender Unterricht:</strong> Gemeinsames Entdecken und Forschen</li>'
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/konzepte.jpg',
                            'caption' => 'Verlässliche Regeln und gelebte Erziehungspartnerschaft an der Mühlmattenschule.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Ziele und Inhalte von „So ist es bei uns"',
                            'body' => '<p>Mit der Einschulung werden die Kinder in die Schulgemeinschaft der Mühlmattenschule aufgenommen. Ein friedliches Miteinander im Schulalltag soll den Kindern ein produktives und geborgenes Lernumfeld ermöglichen. Dazu sind klare Vereinbarungen, gemeinsame Regeln und eine enge Zusammenarbeit mit den Eltern wichtig.</p>'
                                .'<p>In dem Leitfaden <strong>„So ist es bei uns"</strong> haben wir die wichtigsten Vereinbarungen und Regeln zusammengetragen. Das Besondere: <strong>Das Heft ist von zwei Seiten lesbar!</strong></p>'
                                .'<ul>'
                                .'<li>Von der einen Seite gelesen ist es für die <strong>Eltern</strong> geschrieben: Dort werden die Ziele, unsere Erwartungen an eine fruchtbare Zusammenarbeit sowie Vereinbarungen und Maßnahmen bei Konflikten dargestellt.</li>'
                                .'<li>Dreht man das Heft herum, ist es an die <strong>Kinder</strong> adressiert und die Inhalte werden anschaulich und kindgerecht präsentiert.</li>'
                                .'</ul>'
                                .'<p>Beide Teile des Heftes werden von uns als gemeinsamer <strong>Vertrag</strong> verstanden, der von den Eltern, den Kindern, den Lehrkräften sowie der Schulleitung unterschrieben wird – als Zeichen unseres gemeinsamen Bemühens um eine harmonische Grundschulzeit.</p>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/klassenrat-symbol.jpg',
                            'caption' => 'Der Klassenrat – Demokratie und Mitbestimmung im Klassenzimmer.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Soziales Lernen und Demokratie im Klassenrat',
                            'body' => '<p>Seit Herbst 2009 ist an unserer Schule für alle Klassen der <strong>Klassenrat</strong> fest eingeführt. Er ist eine wöchentlich stattfindende Gesprächsrunde, in der konkrete Anliegen aus dem Schulalltag wie Ausflüge, Projekte, Klassenregeln, Dienste, Wünsche, Probleme und Konflikte besprochen werden.</p>'
                                .'<p>Der Schwerpunkt des Klassenrates liegt im sozialen Lernen, in der Gewaltprävention und in der Entwicklung einer demokratischen Schulkultur. Die Schülerinnen und Schüler lernen hier:</p>'
                                .'<ul>'
                                .'<li>Verantwortung für sich und die Gemeinschaft zu übernehmen,</li>'
                                .'<li>gemeinsam Entscheidungen zu fällen und mitzutragen,</li>'
                                .'<li>faire Lösungen für Konflikte zu finden,</li>'
                                .'<li>sich eine eigene Meinung zu bilden, diese zu vertreten und sachlich zu argumentieren,</li>'
                                .'<li>Ämter und Aufgaben (z. B. Vorsitz, Protokoll, Zeitwächter) zu übernehmen und konstruktiv mit Kritik umzugehen.</li>'
                                .'</ul>'
                                .'<p>Der Klassenrat stärkt das Wir-Gefühl nachhaltig und schafft ein positives Schul-, Klassen- und Lernklima.</p>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/rosedale-kinder-afrika.jpg',
                            'caption' => 'Kinder im Midlands Children Home Rosedale in Gweru, Simbabwe.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Patenschaft mit dem Kinderheim Rosedale in Gweru/Simbabwe',
                            'body' => '<p>Seit <strong>1995</strong> pflegt unsere Schule eine lebendige Patenschaft mit dem Kinderheim und Waisenhaus <strong>„Midlands Children Home Rosedale"</strong> in Gweru/Simbabwe.</p>'
                                .'<h3>Über die Einrichtung</h3>'
                                .'<p>Das Heim wurde von Gertrud Scheu, einer kirchlichen Sozialarbeiterin aus der Schweiz, und weiteren engagierten Menschen als Verein gegründet. Die Stadt Gweru liegt im Herzen von Simbabwe. Das Haus Rosedale ist heute ein liebevolles Zuhause für rund 60 Kinder und Jugendliche im Alter von 0 bis 17 Jahren. Die Kinder besuchen ab 5 Jahren den öffentlichen Kindergarten und ab 7 Jahren die Schule; für Jugendliche gibt es zudem ein Ausbildungsprojekt in der Landwirtschaft.</p>'
                                .'<h3>„Trommeln für Rosedale"</h3>'
                                .'<p>Das ganze Jahr über finden wir Gelegenheiten, bei denen wir Spenden „zusammentrommeln" können: Unsere Schülerinnen und Schüler singen und trommeln bei ihren Straßenmusik-Auftritten in der Freiburger Innenstadt und bei unseren Schulfesten. Es freut uns sehr, dass unsere Kinder auf diese Weise unmittelbar erfahren können, wie bereichernd es ist, anderen Kindern auf der Welt zu helfen.</p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Eindrücke aus Rosedale und unseren Trommel-Aktionen',
                            'image_1_url' => '/images/school/rosedale-kinder.jpg',
                            'image_1_caption' => 'Gemeinschaft im Kinderheim Rosedale in Gweru.',
                            'image_2_url' => '/images/school/trommeln-rosedale.jpg',
                            'image_2_caption' => 'Straßenmusik- und Trommelauftritt unserer Schulkinder für Rosedale.',
                            'image_3_url' => '/images/school/rosedale-trommeln.png',
                            'image_3_caption' => 'Musik verbindet über Kontinente hinweg.',
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
                            'heading' => 'BiSS-Transfer: Sprachbildung, Lese- und Schreibförderung',
                            'body' => '<p>Die Mühlmattenschule gehört dem Freiburger BiSS-Verbund an. Auf Basis eines einheitlichen und systematischen Lesecurriculums setzen wir Schritt für Schritt nachweislich lernförderliche Elemente im Leseunterricht um – darunter das Training der Leseflüssigkeit mithilfe von <strong>Lautlesetandems</strong> und den gezielten Einsatz von <strong>Lesestrategien</strong>. BiSS-Ansprechperson an unserer Schule ist <strong>Raimund Früh</strong>.</p>'
                                .'<h3>Inklusives Beschulungsmodell (Kooperative Organisationsform)</h3>'
                                .'<p>In unserer kooperativen Organisationsform lernen Grundschulkinder und Schülerinnen und Schüler der Richard-Mittermaier-Schule gemeinsam im Unterricht, begleitet von Grundschullehrkräften und Sonderpädagoginnen (Frau Dingler) nach dem Leitgedanken: <em>„So viel gemeinsamer Unterricht wie möglich und so viel Individualförderung wie nötig."</em></p>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/konzepte.jpg',
                            'caption' => 'Gemeinsames Lernen und individuelle Förderung an der Mühlmattenschule.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Unsere pädagogischen Säulen',
                            'body' => '<p>In der Rubrik <strong>Konzepte &amp; Projekte</strong> stellen wir Ihnen die pädagogischen Grundpfeiler unseres Schullebens vor:</p>'
                                .'<ul>'
                                .'<li><a href="/konzepte-projekte/leitbild">Unser Leitbild</a> – gemeinsam entwickelt von Eltern und Lehrkräften</li>'
                                .'<li><a href="/konzepte-projekte/so-ist-es-bei-uns">Leitfaden „So ist es bei uns"</a> – unser zweiseitig lesbares Vereinbarungsheft</li>'
                                .'<li><a href="/konzepte-projekte/klassenrat">Wöchentlicher Klassenrat</a> – soziales Lernen und Demokratie</li>'
                                .'<li><a href="/konzepte-projekte/patenschaft-simbabwe">Patenschaft Simbabwe</a> – seit 1995 für das Kinderheim Rosedale in Gweru</li>'
                                .'<li><a href="/konzepte-projekte/biss-transfer-und-inklusion">BiSS-Transfer &amp; Inklusion</a> – Leseförderung und kooperative Außenklasse</li>'
                                .'</ul>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/about-kids.jpg',
                            'caption' => 'Schulkindbetreuung in den Räumen der Mühlmattenschule.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Schulkindbetreuung an der Mühlmattenschule (07:30 – 17:00 Uhr)',
                            'body' => '<p>Offen für alle Schülerinnen und Schüler der Mühlmattenschule ist die Schulkindbetreuung. Zwischen <strong>07:30 Uhr und 17:00 Uhr</strong> – auch während eines Großteils der Schulferien – wird Betreuung in verschiedenen flexibel wählbaren Modulen von der <strong>Caritas Schulkindbetreuung (Caritasverband Freiburg-Stadt e.V.)</strong> direkt in den Räumen am südlichen Ende der Mühlmattenschule angeboten – inklusive warmem Mittagessen und Hausaufgabenbegleitung.</p>',
                        ],
                    ],
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Ansprechpartnerin der Schulkindbetreuung',
                            'group' => PersonGroup::Betreuung->value,
                        ],
                    ],
                    [
                        'type' => 'info_box',
                        'data' => [
                            'title' => 'Kontaktadresse & Sprechzeiten Schulkindbetreuung',
                            'text' => 'Caritasverband Freiburg-Stadt e.V. · Schulkindbetreuung Mühlmattenschule · Zu den Mühlmatten 8, 79108 Freiburg i. Br. · Leitung: Lisa Jortzik · Tel. (Festnetz): 07665 / 932 92 21 · Tel. (Mobil): 0152 02751785 · E-Mail: schulkindbetreuung-muehlmattenschule@caritas-freiburg.de · Telefonische Sprechzeiten: 07:30 – 08:30 Uhr und 12:15 – 17:00 Uhr',
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
                            'heading' => 'Arbeitsgemeinschaften und Angebote an der Mühlmattenschule',
                            'body' => '<p><strong>Hinweis:</strong> Die Teilnahme an einer Arbeitsgemeinschaft erfordert eine Anmeldung. Eine AG von externen Kooperationspartnern kann kostenpflichtig sein.</p>'
                                .'<ul>'
                                .'<li><strong>Sport-AG (Montag):</strong> Jeden Montag bietet Herr Markert von der Schulsozialarbeit die Sport-AG in der Mooswaldhalle an (im 1. Schulhalbjahr für die 3. und 4. Klassen, im 2. Schulhalbjahr für die 1. und 2. Klassen).</li>'
                                .'<li><strong>Computer-AG (Dienstag, 14:00 – 16:15 Uhr):</strong> Findet in zwei Gruppen mit Herrn Markert (Schulsozialarbeiter) im Computerraum (Zimmer 12) statt.</li>'
                                .'<li><strong>Flöten-AG (Mittwoch, 12:15 – 13:00 Uhr):</strong> Für Schülerinnen und Schüler ab der 1. Klasse wird Blockflötenunterricht über die Musikschule Freiburg an unserer Schule angeboten.</li>'
                                .'<li><strong>Schulchor (Donnerstag, 15:00 – 15:45 Uhr):</strong> Für Schülerinnen und Schüler der 2. bis 4. Klassen bei Konrektorin Sabine Caputo in Kooperation mit der Chorvereinigung Hochdorf. Jedes Kind, das Lust am Singen hat, ist herzlich eingeladen!</li>'
                                .'<li><strong>Fußball-AG (Donnerstag, 16:00 – 17:00 Uhr):</strong> Für die Klassen 3 und 4 unter der Leitung von Sabine Caputo und Luis Fesenmayr vom SV Hochdorf in der Sporthalle. Alle Mädchen und Jungen sind willkommen!</li>'
                                .'<li><strong>Englisch-AG (Donnerstag, 15:15 – 16:00 Uhr):</strong> Spielerischer Einstieg in die englische Sprache in Zimmer 14.</li>'
                                .'<li><strong>„Wir lernen Melodika" (Freitag):</strong> Jeden Freitag gibt es das Angebot des Akkordeon-Clubs Hochdorf, an der Schule Melodika zu lernen.</li>'
                                .'</ul>'
                                .'<h3>Pausenangebote der Schulsozialarbeit</h3>'
                                .'<ul>'
                                .'<li><strong>Ringen &amp; Raufen:</strong> Mehrmals pro Woche können Kinder in der Pause fair und nach klaren Regeln miteinander kämpfen.</li>'
                                .'<li><strong>Entspannungspause (Donnerstag):</strong> Jeden Donnerstag bietet die Schulsozialarbeit in den Pausen Yoga und Entspannung an – für eine ruhige Auszeit im Schulalltag.</li>'
                                .'</ul>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Einblicke in unsere Arbeitsgemeinschaften',
                            'image_1_url' => '/images/school/ag-computer.jpg',
                            'image_1_caption' => 'Computer-AG in Zimmer 12: Medienkompetenz erlernen.',
                            'image_2_url' => '/images/school/ag-chor.jpg',
                            'image_2_caption' => 'Chor-AG: Gemeinsam singen und auftreten.',
                            'image_3_url' => '/images/school/ag-fussball.jpg',
                            'image_3_caption' => 'Fußball- und Sport-AG in der Mooswaldhalle.',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Musik & Fairplay in den Pausen',
                            'image_1_url' => '/images/school/ag-floete.jpg',
                            'image_1_caption' => 'Flöten-AG mit der Musikschule Freiburg.',
                            'image_2_url' => '/images/school/ag-raufen.jpg',
                            'image_2_caption' => 'Ringen & Raufen nach festen Fairplay-Regeln.',
                            'image_3_url' => '/images/school/ag-stille-pause.jpg',
                            'image_3_caption' => 'Entspannungspause und Ruheinsel im Schulalltag.',
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
                            'heading' => 'Leselernpatinnen und -paten an der Mühlmattenschule',
                            'body' => '<p>„Leselernpatinnen und -paten" bezeichnet ein bewährtes Kooperationsprojekt der Stadt Freiburg mit den in ihrer Trägerschaft stehenden Schulen. Ehrenamtlich tätige Leselernpatinnen und Leselernpaten kümmern sich an unserer Schule liebevoll um Kleingruppen von jeweils zwei Kindern und begleiten diese beim Lesenlernen.</p>'
                                .'<p>An der Mühlmattenschule nehmen derzeit fünf engagierte Lesepatinnen diese wertvolle Aufgabe wahr – darüber freuen wir uns sehr! Die Kinder kommen jeweils einmal pro Woche zum individuellen Lesetraining in die Schule. Die Lesepatenschaft entlastet die Familien, weckt bei den Kindern nachhaltige Lesefreude und steigert spürbar die Lesefertigkeit.</p>',
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
                            'heading' => 'Bibfit für die 1. und 2. Grundschulklasse („bibliotheks-fit")',
                            'body' => '<p>Seit 2010 besteht zwischen der Mühlmattenschule und der öffentlichen Bücherei in Hochdorf das <strong>Bibfit-Programm</strong>. Bibfit bedeutet einfach „bibliotheks-fit": Die Kinder lernen durch das Programm, selbstständig in der öffentlichen Bücherei der katholischen Pfarrgemeinde St. Martin (Benzhauser Str. 3, hinter dem Pfarrer-Meybrunn-Haus) Bücher und viele andere Medien auszuleihen.</p>'
                                .'<p>Viermal innerhalb des 1. und 2. Grundschuljahres besuchen die Schülerinnen und Schüler der Mühlmattenschule gemeinsam mit ihren Klassenlehrkräften die Bibliothek. Natürlich können die Kinder auch zwischen diesen Klassenbesuchen jederzeit privat Medien ausleihen. Am Ende des zweiten Schuljahres werden die nun „fitten Leseratten" feierlich mit einer Bibfit-Urkunde belohnt.</p>'
                                .'<p>Die Klassenbesuche werden vom ehrenamtlichen Bibfit-Team der Bücherei vorbereitet und durchgeführt, um die Kinder spielerisch mit dem vielfältigen Medienangebot vertraut zu machen und die Begeisterung fürs Lesen zu wecken. Jedes Kind erhält mit Erlaubnis der Eltern einen eigenen kostenlosen Leseausweis.</p>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/partner-musikschule-freiburg.jpg',
                            'caption' => 'Unser Kooperationspartner: Musikschule Freiburg.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Unsere außerschulischen Kooperationspartner',
                            'body' => '<p>Die Mühlmattenschule pflegt ein lebendiges Netzwerk mit Vereinen, Kultureinrichtungen und Bildungsinstitutionen in Hochdorf und Freiburg:</p>'
                                .'<ul>'
                                .'<li><strong>Musikschule Freiburg (<a href="https://www.musikschule-freiburg.de" target="_blank" rel="noopener noreferrer">www.musikschule-freiburg.de</a>):</strong> Wöchentlicher Flöten- und Instrumentalunterricht direkt in den Räumen der Mühlmattenschule.</li>'
                                .'<li><strong>Chorvereinigung Hochdorf:</strong> Seit 2018 gemeinsame Chorprojekte und Konzerte mit Gesangslehrerin Annette Riesterer und Konrektorin Sabine Caputo.</li>'
                                .'<li><strong>SV Hochdorf:</strong> Gemeinsame Fußball-AG für Mädchen und Jungen der 3. und 4. Klassen in der Sporthalle.</li>'
                                .'<li><strong>Akkordeon-Club Hochdorf:</strong> Musikprojekt „Wir lernen Melodika" immer freitags an der Schule.</li>'
                                .'<li><strong>Katholische Öffentliche Bücherei St. Martin Hochdorf:</strong> Durchführung des Lesekompass-Programms „Bibfit" für alle 1. und 2. Klassen.</li>'
                                .'<li><strong>Kindertagesstätten in Hochdorf &amp; Benzhausen:</strong> Enge Kooperation für einen gelingenden Übergang vom Kindergarten in die Grundschule.</li>'
                                .'</ul>',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/unsere-schule-karte.jpg',
                            'caption' => 'Übersichtskarte des Schulbezirks Hochdorf und Benzhausen.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Zu Fuß zur Schule – sicher und selbstständig',
                            'body' => '<p>Der tägliche Schulweg zu Fuß stärkt die Selbstständigkeit, Fitness und das Sozialverhalten der Kinder. Gemeinsam mit der Stadt Freiburg, der Polizei und dem Elternbeirat wurde der offizielle <strong>Schulwegeplan der Mühlmattenschule</strong> ausgearbeitet. Er empfiehlt verkehrssichere Routen mit Fußgängerampeln, Zebrastreifen und Gehwegen für Hochdorf, Benzhausen und das Gewerbegebiet.</p>'
                                .'<ul>'
                                .'<li><strong>Schulweg üben:</strong> Gehen Sie den Schulweg vor der Einschulung mehrfach zu verschiedenen Tageszeiten gemeinsam mit Ihrem Kind ab.</li>'
                                .'<li><strong>Helle Kleidung &amp; Reflektoren:</strong> Sorgen Sie in der Dämmerung und im Winter für gute Sichtbarkeit.</li>'
                                .'<li><strong>Verkehrssichere Bushaltestellen:</strong> Besprechen Sie das Verhalten an den Haltestellen (kein Schubsen, erst nach Abfahrt des Busses die Straße überqueren).</li>'
                                .'<li><strong>Rücksichtsvolle Hol- und Bringzone:</strong> Nutzen Sie bitte die offiziellen Parkbuchten und vermeiden Sie das gefährliche Halten direkt vor dem Schultor.</li>'
                                .'</ul>',
                        ],
                    ],
                    [
                        'type' => 'info_box',
                        'data' => [
                            'title' => 'Schulwegeplan als PDF-Download verfügbar',
                            'text' => 'Die offizielle Karte (Schulwegeplan-Hochdorf-Karte.pdf) und das Merkblatt mit Verkehrsregeln (Schulwegeplan-Hochdorf-Regeln.pdf) stehen jederzeit im Bereich Downloads als druckbare PDF-Dateien zur Verfügung.',
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
                        'type' => 'image_banner',
                        'data' => [
                            'image_url' => '/images/school/about-kids.jpg',
                            'caption' => 'Lernen, Spielen und Gemeinschaft in der Schulkindbetreuung und den AGs.',
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Betreuung und außerunterrichtliche Angebote',
                            'body' => '<p>Alle Details zu unseren Betreuungs- und Freizeitangeboten finden Sie auf den jeweiligen Einzelseiten:</p>'
                                .'<ul>'
                                .'<li><a href="/angebote/schulkindbetreuung">Schulkindbetreuung (Caritas, 7:30 – 17:00 Uhr)</a></li>'
                                .'<li><a href="/angebote/arbeitsgemeinschaften">Arbeitsgemeinschaften (AGs) &amp; Pausenangebote</a></li>'
                                .'<li><a href="/angebote/lesepatenschaften">Lesepatenschaften</a></li>'
                                .'<li><a href="/angebote/bibfit">Bibfit – Der Lesekompass</a></li>'
                                .'<li><a href="/angebote/kooperationen">Kooperationspartner</a></li>'
                                .'<li><a href="/angebote/schulwegeplan">Sicherer Schulweg (Schulwegeplan)</a></li>'
                                .'</ul>',
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
     * Erzeugt nummerierte Bildpfade (z. B. /images/school/crosslauf-full-01.jpg bis -44.jpg).
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
