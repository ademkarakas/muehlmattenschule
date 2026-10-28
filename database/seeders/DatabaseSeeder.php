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
use Spatie\MediaLibrary\HasMedia;

class DatabaseSeeder extends Seeder
{
    /**
     * Befüllt die Datenbank mit sämtlichen Original-Inhalten, Aktivitäten,
     * Fotoalben, PDF-Downloads und dem Kollegium von muehlmattenschule.de.
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

        $lehrkraft2b = User::query()->updateOrCreate(
            ['email' => 'klasse2a@muehlmattenschule.de'],
            [
                'name' => 'Frau Jakoby (Klasse 2b)',
                'role' => UserRole::Lehrkraft,
                'class_name' => 'Klasse 2b',
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
            ['name' => 'Schulleben', 'color' => '#00838f'],
        );
        $catElterninfo = Category::query()->updateOrCreate(
            ['slug' => 'elterninfos'],
            ['name' => 'Elterninfos & Ferien', 'color' => '#006978'],
        );
        $catFest = Category::query()->updateOrCreate(
            ['slug' => 'feste-feiern'],
            ['name' => 'Schulfeste & Feiern', 'color' => '#f59e0b'],
        );
        $catSport = Category::query()->updateOrCreate(
            ['slug' => 'sport-bewegung'],
            ['name' => 'Sport & Gesundheit', 'color' => '#0097a7'],
        );
        $catProjekte = Category::query()->updateOrCreate(
            ['slug' => 'projekte'],
            ['name' => 'Konzepte & Projekte', 'color' => '#005662'],
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

        // --- 6 Reale Fotoalben mit über 50 Originalfotos von muehlmattenschule.de ---
        $albumCrosslauf = Album::query()->updateOrCreate(
            ['slug' => 'crosslauf-der-muehlmattenschule'],
            [
                'title' => 'Bilder vom Crosslauf & Sponsorenlauf',
                'event_date' => '2024-06-24',
                'description' => 'Unser großes Schulsportfest für die ganze Familie, veranstaltet vom Förderverein in Kooperation mit der Mühlmattenschule.',
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(5),
            ],
        );
        $this->attachSingleMedia($albumCrosslauf, 'cover', 'crosslauf-1.jpg');
        $this->attachGalleryMedia($albumCrosslauf, 'photos', [
            'crosslauf-1.jpg',
            'crosslauf-2.jpg',
            'crosslauf-3.jpg',
            'crosslauf-4.jpg',
            'crosslauf-5.jpg',
            'crosslauf-6.jpg',
            'crosslauf-7.jpg',
        ]);

        $albumBewegung = Album::query()->updateOrCreate(
            ['slug' => 'bewegungs-und-gesundheitstage'],
            [
                'title' => 'Bewegungs- & Gesundheitstage: Spielmobil, Slow-Mobil & Tanzen',
                'event_date' => '2024-10-24',
                'description' => 'Kochen im Slow-Mobil, Spielstationen mit dem Spielmobil auf dem Pausenhof und Tanzaktion mit der Tanzschule Gutmann.',
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(10),
            ],
        );
        $this->attachSingleMedia($albumBewegung, 'cover', 'slowmobil-1.jpg');
        $this->attachGalleryMedia($albumBewegung, 'photos', [
            'slowmobil-1.jpg',
            'slowmobil-2.jpg',
            'slowmobil-3.jpg',
            'slowmobil-4.jpg',
            'slowmobil-5.jpg',
            'spielmobil-1.jpg',
            'spielmobil-2.jpg',
            'spielmobil-3.jpg',
            'spielmobil-4.jpg',
            'spielmobil-5.jpg',
            'news-tanzen.jpg',
            'tanzen-2.jpg',
            'tanzen-3.jpg',
        ]);

        $albumErnaehrung = Album::query()->updateOrCreate(
            ['slug' => 'gesundes-fruehstueck-obstsalat-und-brotgesichter'],
            [
                'title' => 'Gesunde Ernährung in den 2. Klassen: Frühstück, Obstsalat & Brotgesichter',
                'class_name' => 'Klasse 2a & 2b',
                'event_date' => '2024-10-20',
                'description' => 'Die 2. Klassen bereiten ein gesundes Frühstück, frischen Obstsalat für Kater Cook und lustige Brotgesichter zu.',
                'author_id' => $lehrkraft2b->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(15),
            ],
        );
        $this->attachSingleMedia($albumErnaehrung, 'cover', 'fruehstueck-2kl-1.jpg');
        $this->attachGalleryMedia($albumErnaehrung, 'photos', [
            'fruehstueck-2kl-1.jpg',
            'fruehstueck-2kl-2.jpg',
            'fruehstueck-2kl-3.jpg',
            'fruehstueck-2kl-4.jpg',
            'fruehstueck-2kl-5.jpg',
            'obstsalat-2b-1.jpg',
            'obstsalat-2b-2.jpg',
            'obstsalat-2b-3.jpg',
            'brotgesichter-2b-1.jpg',
            'brotgesichter-2b-2.jpg',
            'brotgesichter-2b-3.jpg',
        ]);

        $albumAdvent = Album::query()->updateOrCreate(
            ['slug' => 'adventszeit-und-basteln'],
            [
                'title' => 'Adventssingen, Adventsbasteln & Plätzchenverkauf',
                'event_date' => '2024-12-19',
                'description' => 'Weihnachtliche Stimmung, Adventsbasteln in allen Klassen und der Plätzchenverkauf auf dem Hochdorfer Markt.',
                'author_id' => $schulleitung->id,
                'consent_confirmed' => true,
                'status' => PublishStatus::Published,
                'published_at' => now()->subDays(20),
            ],
        );
        $this->attachSingleMedia($albumAdvent, 'cover', 'news-advent-1.jpg');
        $this->attachGalleryMedia($albumAdvent, 'photos', [
            'news-advent-1.jpg',
            'advent-2.jpg',
            'advent-3.jpg',
            'advent-4.jpg',
            'advent-5.jpg',
            'news-basteln-1.jpg',
            'basteln-2.jpg',
            'basteln-3.jpg',
            'basteln-4.jpg',
            'basteln-5.jpg',
            'basteln-6.jpg',
            'news-plaetzchen.jpg',
            'plaetzchen-2.jpg',
            'plaetzchen-3.jpg',
        ]);

        $albumCampus = Album::query()->updateOrCreate(
            ['slug' => 'unser-schulgebaeude-und-campus'],
            [
                'title' => 'Unser Schulgebäude & Pausenhof im Grünen',
                'event_date' => '2024-09-15',
                'description' => 'Helle Flure mit Holzelementen, freundliche Klassenzimmer und unser weitläufiges Schulgelände in Freiburg-Hochdorf.',
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
            'gebaeude-full.jpg',
            'slider-schulgebaeude1.jpg',
            'unsere-schule-innen.jpg',
            'unsere-schule-umgegend.jpg',
            'schule-bei-abend.jpg',
            'slider-schuleinnen.jpg',
        ]);

        $albumRosedale = Album::query()->updateOrCreate(
            ['slug' => 'patenschaft-kinderheim-rosedale-simbabwe'],
            [
                'title' => 'Patenschaft Midlands Children Home Rosedale (Simbabwe)',
                'event_date' => '2024-05-10',
                'description' => 'Seit 1995 pflegt die Mühlmattenschule eine lebendige Patenschaft mit dem Kinderheim Rosedale in Gweru, Simbabwe.',
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

        // --- Sämtliche 18 Original-Beiträge & Klassenaktivitäten von muehlmattenschule.de ---
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
                'excerpt' => 'Die Klassen 4a und 4b waren im Januar auf dem Mundenhof bei der Grünholzwerkstatt des Spielmobils. Die Kinder durften drechseln, schnitzen und eigene Berichte schreiben.',
                'body' => '<p>Die Klassen 4a und 4b waren im Januar auf dem Mundenhof bei der Grünholzwerkstatt des Spielmobils. Die Kinder haben dazu eigene Berichte geschrieben:</p><h3>Die Grünholzwerkstatt (Schülerbericht)</h3><p>„Am 24. Januar war ich mit meiner Klasse in der Grünholzwerkstatt. Diese Werkstatt wurde vom Spielmobil veranstaltet. Wir durften drechseln, schnitzen und auf einem Schnitzesel mit einem Ziehmesser frisches Holz bearbeiten. Am Ende durfte jedes Kind sein eigenes Werkstück mit nach Hause nehmen – es war ein toller Ausflug!"</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => null,
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
                'category_id' => $catElterninfo->id,
                'album_id' => null,
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
                'body' => '<p>Liebe Eltern, mit diesem Elternbrief informieren wir Sie über den Ablauf der letzten Schultage vor den Sommerferien, die Ausgabe der Zeugnisse und Schulberichte in den Klassen 1 bis 4 sowie den Unterrichtsbeginn im neuen Schuljahr und die Einschulungsfeier unserer neuen Erstklässlerinnen und Erstklässler.</p>',
                'category_id' => $catElterninfo->id,
                'album_id' => null,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
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
                'album_id' => null,
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
                'body' => '<p>Nach langjähriger aktiver ehrenamtlicher Unterstützung des Fördervereins wurden unsere bisherigen Vorstände Nils Henkel und Fabian Baumann im Rahmen der Jahreshauptversammlung feierlich verabschiedet. Gleichzeitig wurden ein neuer Vorstand sowie ein neuer Beirat gewählt, die sich nun gemeinsam für die Kinder der Mühlmattenschule engagieren.</p>',
                'category_id' => $catElterninfo->id,
                'album_id' => null,
                'author_id' => $admin->id,
                'is_featured' => false,
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
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 10,
                'cover' => 'fsj-sport.jpg',
            ],
            [
                'slug' => 'weihnachtliche-muehlmattenschule-und-adventssingen',
                'title' => 'Weihnachtliche Mühlmattenschule & gemeinsames Adventssingen',
                'class_name' => null,
                'excerpt' => 'Weihnachtliche Stimmung und gemeinsames Adventssingen in der Mühlmattenschule – Bilder und Eindrücke aus der Vorweihnachtszeit.',
                'body' => '<p>In der Vorweihnachtszeit verwandelte sich unser Schulhaus in eine festlich geschmückte Weihnachtswelt. Beim gemeinsamen Adventssingen unter dem großen Weihnachtsbaum musizierten und sangen alle Klassen gemeinsam.</p>',
                'category_id' => $catFest->id,
                'album_id' => $albumAdvent->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 12,
                'cover' => 'news-advent-1.jpg',
            ],
            [
                'slug' => 'adventsbasteln-in-den-klassen-die-bilder',
                'title' => 'Adventsbasteln am 12. Dezember – die Bilder',
                'class_name' => null,
                'excerpt' => 'Ein Vormittag voller Kerzen, Papier, Tannenzweige und Klebstoff: Eindrücke vom Adventsbasteln in allen Klassen.',
                'body' => '<p>Beim traditionellen Adventsbasteln am 12. Dezember wurde in allen Klassenzimmern geschnitten, gefaltet und geklebt. Mit tatkräftiger Unterstützung vieler Eltern entstanden wunderschöne Dekorationen für die Adventszeit.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumAdvent->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
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
                'author_id' => $sekretariat->id,
                'is_featured' => false,
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
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 18,
                'cover' => 'news-tanzen.jpg',
            ],
            [
                'slug' => 'bewegungs-und-gesundheitstage-kochen-mit-dem-slow-mobil',
                'title' => 'Bewegungs- und Gesundheitstage (2): Kochen und Essen mit dem Slow-Mobil',
                'class_name' => null,
                'excerpt' => 'Im bunten Küchenwagen des Slow-Mobils schnippelten, kochten und probierten die Kinder frische regionale Gerichte.',
                'body' => '<p>Ein besonderes Erlebnis unserer Bewegungs- und Gesundheitstage war der Besuch des Freiburger Slow-Mobils auf unserem Schulhof. In der rollenden Kinderküche bereiteten die Schülerinnen und Schüler aus frischem Gemüse köstliche Mahlzeiten zu und aßen anschließend gemeinsam an einer langen Tafel.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumBewegung->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 20,
                'cover' => 'slowmobil-1.jpg',
            ],
            [
                'slug' => 'bewegungs-und-gesundheitstage-spielmobil',
                'title' => 'Bewegungs- und Gesundheitstage (1): Aktionstag mit dem Spielmobil',
                'class_name' => null,
                'excerpt' => 'Nicht nur die Kinder, sondern auch die Lehrerinnen hatten großen Spaß mit den Bewegungs- und Geschicklichkeitsspielen des Spielmobils.',
                'body' => '<p>Alle zwei Jahre finden an der Mühlmattenschule die Bewegungs- und Gesundheitstage statt. Zum Auftakt verwandelte das Freiburger Spielmobil unseren Pausenhof in einen großen Bewegungsparcours mit Pedalos, Stelzen, Rollenrutsche und Teamspielen.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumBewegung->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 22,
                'cover' => 'spielmobil-2.jpg',
            ],
            [
                'slug' => 'gesundes-fruehstueck-bei-den-2-klassen',
                'title' => 'Gesundes Frühstück bei den 2. Klassen',
                'class_name' => 'Klasse 2a & 2b',
                'excerpt' => 'Die 2. Klassen bereiteten gemeinsam im Unterricht ein buntes, gesundes Schulfrühstück mit frischem Obst, Gemüse und Vollkornbrot zu.',
                'body' => '<p>Im Sachunterricht beschäftigten sich die 2. Klassen intensiv mit gesunder Ernährung. Gemeinsam schnitten die Kinder Rohkost, richteten bunte Platten an und genossen das selbst zubereitete gesunde Frühstück im Klassenzimmer.</p>',
                'category_id' => $catSchulleben->id,
                'album_id' => $albumErnaehrung->id,
                'author_id' => $lehrkraft2b->id,
                'is_featured' => false,
                'days_ago' => 25,
                'cover' => 'fruehstueck-2kl-1.jpg',
            ],
            [
                'slug' => 'transfer-von-sprachbildung-lese-und-schreibfoerderung-biss-transfer',
                'title' => 'Transfer von Sprachbildung, Lese- und Schreibförderung (BiSS-Transfer)',
                'class_name' => null,
                'excerpt' => 'Die Mühlmattenschule nimmt an der bundesweiten Initiative BiSS-Transfer zur systematischen Stärkung der Lese- und Schreibkompetenzen teil.',
                'body' => '<p>BiSS-Transfer ist eine gemeinsame Initiative des Bundesministeriums für Bildung und Forschung (BMBF) und der Kultusministerkonferenz (KMK) zum Transfer von Sprachbildung, Lese- und Schreibförderung. Ziel an der Mühlmattenschule ist die gezielte und durchgängige Stärkung der Basiskompetenzen unserer Schülerinnen und Schüler im Lesen und Schreiben.</p>',
                'category_id' => $catProjekte->id,
                'album_id' => null,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
                'days_ago' => 28,
                'cover' => 'konzepte.jpg',
            ],
            [
                'slug' => 'bilder-vom-crosslauf-der-muehlmattenschule',
                'title' => 'Bilder vom Crosslauf & Sponsorenlauf der Mühlmattenschule',
                'class_name' => null,
                'excerpt' => 'Olympische Stimmung in Hochdorf: Beim Schülerlauf und anschließenden Sponsorenlauf gaben alle Kinder ihr Bestes.',
                'body' => '<p>Beim traditionellen Crosslauf der Mühlmattenschule liefen alle Jahrgangsstufen um die Wette, angefeuert von zahlreichen Eltern, Großeltern und Lehrkräften. Nach der Siegerehrung folgte der Sponsorenlauf für den guten Zweck sowie ein gemütliches Beisammensein mit Tombola.</p>',
                'category_id' => $catSport->id,
                'album_id' => $albumCrosslauf->id,
                'author_id' => $schulleitung->id,
                'is_featured' => false,
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
                'author_id' => $lehrkraft2b->id,
                'is_featured' => false,
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
                'author_id' => $lehrkraft2b->id,
                'is_featured' => false,
                'days_ago' => 40,
                'cover' => 'brotgesichter-2b-1.jpg',
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
                'name' => 'Philipp Markert',
                'role_title' => 'Schulsozialarbeiter (Caritas)',
                'class_name' => 'Schulsozialarbeit',
                'email' => null,
                'group' => PersonGroup::Sozialarbeit,
                'sort_order' => 30,
                'portrait' => null,
            ],
            [
                'name' => 'Alexandra Mühleisen',
                'role_title' => 'Schulsozialarbeiterin (Caritas)',
                'class_name' => 'Schulsozialarbeit',
                'email' => null,
                'group' => PersonGroup::Sozialarbeit,
                'sort_order' => 31,
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

        // --- Schulseiten mit sämtlichen Originaltexten und Fotos von muehlmattenschule.de ---
        $pages = [
            [
                'title' => 'Schulportrait',
                'slug' => 'schulportrait',
                'section' => 'unsere-schule',
                'menu_order' => 1,
                'summary' => 'Geschichte, Architektur, Hochschulpartnerschaft und Schulleben der Mühlmattenschule in Freiburg-Hochdorf.',
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
                            'heading' => 'Lage und Geschichte der Mühlmattenschule',
                            'body' => '<p>Alle Kinder aus dem Freiburger Stadtteil Hochdorf (mit der Ortschaft Benzhausen und dem Gewerbegebiet Hochdorf) besuchen die Mühlmattenschule. Durch den Zuzug vieler junger Familien wurde die frühere Dorfschule, die „Michael-Denzlinger-Schule", zu klein. So wurde eine neue Schule gebaut und <strong>1991</strong> bezogen – benannt nach dem Gewann, in dem sie liegt: <strong>„Mühlmattenschule"</strong>.</p><p>Nach Erweiterungen in den Jahren 1995 und 2000 verfügt die Schule über die Räumlichkeiten für eine bis zu dreizügige Grundschule.</p>',
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
                            'heading' => 'Hochschulpartnerschule (FACE Praxiskolleg)',
                            'body' => '<p>Die Mühlmattenschule ist offizielle <strong>Hochschulpartnerschule</strong> und arbeitet im Rahmen des „Praxiskollegs" unter dem Dach des <em>Freiburg Advanced Center of Education (FACE)</em> eng mit der Pädagogischen Hochschule Freiburg und der Albert-Ludwigs-Universität Freiburg in der Aus- und Fortbildung von Lehrkräften zusammen.</p>',
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
                'summary' => 'Philipp Markert und Alexandra Mühleisen (Caritasverband Freiburg-Stadt e.V.).',
                'content' => [
                    [
                        'type' => 'people_list',
                        'data' => [
                            'heading' => 'Team der Schulsozialarbeit',
                            'group' => PersonGroup::Sozialarbeit->value,
                        ],
                    ],
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'heading' => 'Aufgaben und Angebote der Schulsozialarbeit',
                            'body' => '<p>Träger der Schulsozialarbeit an der Mühlmattenschule ist der <strong>Caritasverband Freiburg-Stadt e.V.</strong> Zu unseren Aufgaben zählen:</p><ul><li>Sozialpädagogische Begleitung von Schülerinnen und Schülern bei persönlichen, schulischen oder sozialen Anliegen</li><li>Förderung sozialer Kompetenzen durch Projekt- und Gruppenangebote sowie Begleitung des Klassenrats</li><li>Vertrauliche Beratung von Kindern, Eltern und Lehrkräften</li><li>Leitung von Arbeitsgemeinschaften wie der Sport-AG und Computer-AG</li></ul>',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Elternbeirat & Förderverein',
                'slug' => 'elternbeirat',
                'section' => 'unsere-schule',
                'menu_order' => 6,
                'summary' => 'Elternbeirat und der Verein der Freunde und Förderer der Mühlmattenschule Freiburg-Hochdorf e.V.',
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
                            'body' => '<p>Der Förderverein der Mühlmattenschule wurde im Jahr 1996 gegründet und ist im Vereinsregister Freiburg (Nr. 2943) als gemeinnütziger Verein eingetragen. Er unterstützt schulische Projekte, das gesunde Frühstück, die Spielgeräteausleihe, Autorenlesungen und Ausflüge.</p><h3>Der Crosslauf – unser großes Schulsportfest seit 1998</h3><p>In jedem Schuljahr im Mai oder Juni veranstaltet der Förderverein in Kooperation mit der Mühlmattenschule den großen <strong>Crosslauf</strong> mit Schülerlauf, Sponsorenlauf und Tombola für die ganze Familie.</p><h3>Elternbeirat</h3><p>Der Elternbeirat vertritt die Belange der Elternschaft, gestaltet Einschulungsfeiern, den Weihnachtsplätzchenverkauf auf dem Hochdorfer Markt sowie Schulfeste aktiv mit.</p>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Aktionen von Förderverein & Elternbeirat',
                            'image_1_url' => '/images/school/crosslauf-1.jpg',
                            'image_1_caption' => 'Start beim jährlichen Crosslauf der Mühlmattenschule.',
                            'image_2_url' => '/images/school/crosslauf-3.jpg',
                            'image_2_caption' => 'Voller Einsatz beim Schüler- und Sponsorenlauf.',
                            'image_3_url' => '/images/school/news-plaetzchen.jpg',
                            'image_3_caption' => 'Weihnachtsplätzchenverkauf des Elternbeirats auf dem Hochdorfer Markt.',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Konzepte & Projekte',
                'slug' => 'konzepte-und-projekte',
                'section' => 'schulleben',
                'menu_order' => 1,
                'summary' => 'Leitbild, Vereinbarung „So ist es bei uns", wöchentlicher Klassenrat, BiSS-Transfer und unsere Patenschaft nach Simbabwe.',
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
                            'heading' => 'Unser Leitbild & Leitfaden „So ist es bei uns"',
                            'body' => '<p>Eltern und Lehrkräfte haben gemeinsam das Leitbild der Mühlmattenschule sowie unseren Leitfaden <strong>„So ist es bei uns"</strong> entwickelt – ein Heft, das von zwei Seiten lesbar ist: einmal für Eltern und einmal kindgerecht für unsere Schülerinnen und Schüler.</p><h3>Wöchentlicher Klassenrat</h3><p>Seit Herbst 2009 findet in jeder Klasse wöchentlich der Klassenrat statt. Soziales Lernen, Gewaltprävention und demokratische Mitbestimmung stehen dabei im Mittelpunkt.</p><h3>Patenschaft mit dem Kinderheim Rosedale in Simbabwe</h3><p>Seit 1995 pflegen wir eine enge Patenschaft mit dem Kinderheim <em>Midlands Children Home Rosedale</em> in Gweru, Simbabwe, in dem rund 60 Kinder ein Zuhause finden.</p>',
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
                            'image_3_url' => '/images/school/slowmobil-1.jpg',
                            'image_3_caption' => 'Kochen und gesunde Ernährung mit dem Slow-Mobil.',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Schulkindbetreuung & AGs',
                'slug' => 'betreuung-und-ags',
                'section' => 'schulleben',
                'menu_order' => 2,
                'summary' => 'Schulkindbetreuung durch die Caritas von 7:30 bis 17:00 Uhr sowie vielfältige Arbeitsgemeinschaften, Lesepatenschaften und Bibfit.',
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
                            'body' => '<p>Offen für alle Schülerinnen und Schüler der Mühlmattenschule ist die Schulkindbetreuung. Zwischen <strong>07:30 Uhr und 17:00 Uhr</strong> – auch während eines Großteils der Ferien – wird die Betreuung in verschiedenen Modulen von der <strong>Caritas Schulkindbetreuung e.V.</strong> in den Räumen der Mühlmattenschule angeboten, inklusive warmem Mittagessen.</p>',
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
                            'heading' => 'Arbeitsgemeinschaften (AGs), Lesepatenschaften, Bibfit & Kooperationen',
                            'body' => '<ul><li><strong>Sport-AG:</strong> Jeden Montag in der Mooswaldhalle mit Herrn Markert (Schulsozialarbeit)</li><li><strong>Computer-AG:</strong> Dienstags in zwei Gruppen in Zimmer 12 mit Herrn Markert</li><li><strong>Musik &amp; Bewegung:</strong> Flöten-AG, Chor-AG, Melodika, Fußball-AG und Englisch-AG</li><li><strong>Lesepatenschaften:</strong> Ehrenamtliche Leselernpatinnen begleiten Kleingruppen beim Lesenlernen</li><li><strong>Bibfit-Lesekompass:</strong> Gemeinsam mit der Bücherei St. Martin für die 1. und 2. Klassen</li><li><strong>Kooperationspartner:</strong> Musikschule Freiburg, SV Hochdorf, Chorvereinigung Hochdorf, Akkordeon-Club Hochdorf und Bücherei St. Martin</li></ul>',
                        ],
                    ],
                    [
                        'type' => 'image_gallery',
                        'data' => [
                            'heading' => 'Bewegung, Ernährung & Aktionen im Schulleben',
                            'image_1_url' => '/images/school/spielmobil-2.jpg',
                            'image_1_caption' => 'Aktionstag mit dem Spielmobil auf dem Schulhof.',
                            'image_2_url' => '/images/school/fruehstueck-2kl-1.jpg',
                            'image_2_caption' => 'Gesundes Frühstück und Kochen in den Klassen.',
                            'image_3_url' => '/images/school/news-tanzen.jpg',
                            'image_3_caption' => 'Tanzaktion in unserer Sporthalle.',
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
