import { PostCard } from '@/components/post-card';
import { SiteLayout } from '@/layouts/site-layout';
import type { AlbumCardItem, PostCardItem, SharedInertiaProps, UpcomingEventItem } from '@/types/school';
import { Head, Link, usePage } from '@inertiajs/react';
import { useEffect, useState, type ReactElement } from 'react';

export interface HomePageProps {
    posts: PostCardItem[];
    upcomingEvents: UpcomingEventItem[];
    albums: AlbumCardItem[];
}

interface QuickTile {
    title: string;
    subtitle: string;
    href: string;
    badge: string;
}

const QUICK_TILES: QuickTile[] = [
    {
        title: 'Nachrichten & Klassen',
        subtitle: 'Ausflüge, Projekte & Berichte',
        href: '/aktuelles',
        badge: 'Aktuelles',
    },
    {
        title: 'Schulkalender',
        subtitle: 'Feste, Ferien & Elternabende',
        href: '/termine',
        badge: 'Termine',
    },
    {
        title: 'Fotogalerie',
        subtitle: 'Einblicke in unser Schulleben',
        href: '/galerie',
        badge: 'Fotos',
    },
    {
        title: 'Kollegium & Team',
        subtitle: 'Schulleitung, Lehrkräfte & Sozialarbeit',
        href: '/unsere-schule/kollegium',
        badge: 'Team',
    },
    {
        title: 'Betreuung & AGs',
        subtitle: 'Nachmittagsbetreuung & Angebote',
        href: '/schulleben/betreuung-und-ags',
        badge: 'Ganztag',
    },
    {
        title: 'Elternbriefe & Formulare',
        subtitle: 'Wichtige Dokumente als PDF',
        href: '/downloads',
        badge: 'Downloads',
    },
];

/**
 * EduPage-inspirierte Startseite (wie ghsfreiburg.edupage.org) mit interaktivem
 * Highlight-Slider, Schnellzugriffs-Kacheln, Nachrichten-Stream, Termin-Widget und Fotogalerie.
 */
export default function HomePage({ posts, upcomingEvents, albums }: HomePageProps): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;
    const slides = posts.slice(0, 3);
    const [activeSlide, setActiveSlide] = useState(0);
    const currentSlide = slides[activeSlide] ?? null;

    useEffect(() => {
        if (slides.length <= 1) {
            return;
        }
        const handleKeyDown = (event: KeyboardEvent): void => {
            if (event.key === 'ArrowLeft') {
                setActiveSlide((prev) => (prev - 1 + slides.length) % slides.length);
            } else if (event.key === 'ArrowRight') {
                setActiveSlide((prev) => (prev + 1) % slides.length);
            }
        };
        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [slides.length]);

    return (
        <SiteLayout>
            <Head title="Startseite" />

            {/* EduPage-Banner & Highlight-Slider in bläulichem Türkis mit echtem Schulfoto */}
            <section className="relative overflow-hidden bg-brand-900 text-white">
                <img
                    src="/images/school/hero.jpg"
                    alt="Mühlmattenschule Freiburg-Hochdorf"
                    className="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-luminosity"
                />
                <div
                    aria-hidden="true"
                    className="pointer-events-none absolute inset-0 bg-gradient-to-br from-brand-900/90 via-brand-700/85 to-brand-800/90"
                />

                <div className="relative mx-auto max-w-7xl px-4 py-10 md:py-14 grid gap-8 lg:grid-cols-12 items-stretch">
                    {/* Linke Seite: Willkommensgruß & Schulprofil */}
                    <div className="lg:col-span-7 flex flex-col justify-center">
                        <div className="inline-flex items-center gap-2 self-start rounded-full bg-white/15 px-3.5 py-1 text-xs font-bold tracking-wide text-brand-50 backdrop-blur-xs">
                            <span>Grundschule Freiburg-Hochdorf & Benzhausen</span>
                        </div>

                        <h1 className="mt-4 text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                            {school.welcome_title || `Herzlich willkommen an der ${school.name}`}
                        </h1>

                        <p className="mt-4 max-w-2xl text-base sm:text-lg text-brand-50/95 leading-relaxed">
                            {school.welcome_text ||
                                'Ein Ort zum Lernen, Wachsen und Entdecken – hier finden Eltern und Interessierte alle aktuellen Nachrichten, Klassenaktivitäten, Termine und Einblicke in unser Schulleben.'}
                        </p>

                        {/* Kennzahlen aus der bestehenden Website */}
                        <div className="mt-6 grid grid-cols-3 gap-3 max-w-md">
                            <div className="rounded-xl bg-white/12 border border-white/20 px-3.5 py-2.5 backdrop-blur-xs">
                                <span className="block text-xl sm:text-2xl font-extrabold text-white">187</span>
                                <span className="text-[11px] font-semibold text-brand-100">Kinder</span>
                            </div>
                            <div className="rounded-xl bg-white/12 border border-white/20 px-3.5 py-2.5 backdrop-blur-xs">
                                <span className="block text-xl sm:text-2xl font-extrabold text-white">9 + 1</span>
                                <span className="text-[11px] font-semibold text-brand-100">Klassen & Außenkl.</span>
                            </div>
                            <div className="rounded-xl bg-white/12 border border-white/20 px-3.5 py-2.5 backdrop-blur-xs">
                                <span className="block text-xl sm:text-2xl font-extrabold text-white">20</span>
                                <span className="text-[11px] font-semibold text-brand-100">Lehrkräfte</span>
                            </div>
                        </div>

                        <div className="mt-7 flex flex-wrap gap-3">
                            <Link
                                href="/aktuelles"
                                className="rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-800 shadow-md hover:bg-brand-50"
                            >
                                Aktuelle Beiträge ansehen →
                            </Link>
                            <Link
                                href="/unsere-schule/schulportrait"
                                className="rounded-xl border border-white/35 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur-xs hover:bg-white/20"
                            >
                                Unsere Schule
                            </Link>
                            <Link
                                href="/unsere-schule/kollegium"
                                className="rounded-xl border border-white/35 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur-xs hover:bg-white/20"
                            >
                                Unser Kollegium
                            </Link>
                        </div>
                    </div>

                    {/* Rechte Seite: EduPage-typischer Nachrichten-Slider / Highlight-Karte mit Bild */}
                    <div className="lg:col-span-5 flex">
                        {currentSlide ? (
                            <div className="flex w-full flex-col justify-between overflow-hidden rounded-2xl bg-white/12 backdrop-blur-md border border-white/25 shadow-xl">
                                {currentSlide.coverUrl && (
                                    <div className="relative aspect-[16/9] w-full overflow-hidden bg-brand-950/40">
                                        <img
                                            src={currentSlide.coverUrl}
                                            alt={currentSlide.title}
                                            className="h-full w-full object-cover"
                                        />
                                        {slides.length > 1 && (
                                            <>
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        setActiveSlide(
                                                            (prev) => (prev - 1 + slides.length) % slides.length,
                                                        )
                                                    }
                                                    aria-label="Vorheriger Beitrag"
                                                    className="absolute left-3 top-1/2 -translate-y-1/2 flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-slate-900/55 text-white backdrop-blur-md transition hover:scale-110 hover:bg-brand-600 cursor-pointer"
                                                >
                                                    <svg
                                                        className="h-5 w-5 -ml-0.5"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        strokeWidth="2.4"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M15 19l-7-7 7-7"
                                                        />
                                                    </svg>
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        setActiveSlide((prev) => (prev + 1) % slides.length)
                                                    }
                                                    aria-label="Nächster Beitrag"
                                                    className="absolute right-3 top-1/2 -translate-y-1/2 flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-slate-900/55 text-white backdrop-blur-md transition hover:scale-110 hover:bg-brand-600 cursor-pointer"
                                                >
                                                    <svg
                                                        className="h-5 w-5 ml-0.5"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        strokeWidth="2.4"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M9 5l7 7-7 7"
                                                        />
                                                    </svg>
                                                </button>
                                            </>
                                        )}
                                    </div>
                                )}
                                <div className="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div className="flex items-center justify-between gap-2 text-xs">
                                            <span className="rounded-full bg-amber-400 px-3 py-0.5 font-extrabold text-slate-950">
                                                {currentSlide.className ||
                                                    currentSlide.category?.name ||
                                                    'Aktuelles Highlight'}
                                            </span>
                                            {currentSlide.publishedAt && (
                                                <span className="text-brand-100 font-semibold">
                                                    {currentSlide.publishedAt}
                                                </span>
                                            )}
                                        </div>

                                        <h2 className="mt-3 text-lg sm:text-xl font-extrabold leading-snug text-white">
                                            {currentSlide.title}
                                        </h2>

                                        {currentSlide.excerpt && (
                                            <p className="mt-2 line-clamp-2 text-xs sm:text-sm leading-relaxed text-brand-50/90">
                                                {currentSlide.excerpt}
                                            </p>
                                        )}
                                    </div>

                                    <div className="mt-5 pt-4 border-t border-white/15 flex items-center justify-between gap-4">
                                        <Link
                                            href={`/aktuelles/${currentSlide.slug}`}
                                            className="rounded-xl bg-white px-4 py-2 text-xs font-extrabold text-brand-800 hover:bg-brand-50"
                                        >
                                            Zum Bericht →
                                        </Link>

                                        {slides.length > 1 && (
                                            <div
                                                className="flex items-center gap-1.5"
                                                role="tablist"
                                                aria-label="Highlights"
                                            >
                                                {slides.map((slide, idx) => (
                                                    <button
                                                        key={slide.id}
                                                        type="button"
                                                        onClick={() => setActiveSlide(idx)}
                                                        aria-label={`Highlight ${idx + 1} anzeigen`}
                                                        className={`h-2.5 rounded-full transition-all ${
                                                            idx === activeSlide
                                                                ? 'w-7 bg-white'
                                                                : 'w-2.5 bg-white/40 hover:bg-white/70'
                                                        }`}
                                                    />
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="flex w-full flex-col justify-center rounded-2xl bg-white/12 backdrop-blur-md border border-white/25 p-6">
                                <p className="text-lg font-bold">Sekretariat & Kontakt</p>
                                <p className="mt-2 text-sm text-brand-100">
                                    {school.street}, {school.zip_city}
                                </p>
                                <p className="mt-2 text-sm">Telefon: {school.phone}</p>
                                <p className="text-sm">Öffnungszeiten: {school.office_hours}</p>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* EduPage-Schnellzugriffskacheln (Modul-Karten wie bei ghsfreiburg.edupage.org) */}
            <section aria-label="Schnellzugriff" className="mx-auto max-w-7xl px-4 -mt-6 relative z-10">
                <div className="grid gap-3.5 sm:grid-cols-2 lg:grid-cols-6">
                    {QUICK_TILES.map((tile) => (
                        <Link
                            key={tile.href}
                            href={tile.href}
                            className="group rounded-2xl border border-slate-200/90 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-400 hover:shadow-md"
                        >
                            <span className="inline-block rounded-lg bg-brand-50 px-2.5 py-0.5 text-[11px] font-bold text-brand-700 group-hover:bg-brand-500 group-hover:text-white transition">
                                {tile.badge}
                            </span>
                            <p className="mt-2 font-extrabold text-sm text-slate-900 group-hover:text-brand-700">
                                {tile.title}
                            </p>
                            <p className="mt-0.5 text-xs text-slate-500 line-clamp-1">{tile.subtitle}</p>
                        </Link>
                    ))}
                </div>
            </section>

            {/* Hauptbereich im EduPage-Portal-Layout (Nachrichten links, Termine & Schulinfo rechts) */}
            <div className="mx-auto max-w-7xl px-4 py-12 grid gap-8 lg:grid-cols-12">
                {/* Linke Spalte (8/12): Aktuelle Nachrichten & Klassenaktivitäten */}
                <section className="lg:col-span-8">
                    <div className="flex flex-wrap items-end justify-between gap-4 mb-6">
                        <div>
                            <span className="text-xs font-extrabold uppercase tracking-wider text-brand-600">
                                Neuigkeiten & Schulleben
                            </span>
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900">
                                Aktuelles aus unseren Klassen
                            </h2>
                        </div>
                        <Link
                            href="/aktuelles"
                            className="rounded-xl bg-brand-50 px-4 py-2 text-xs font-extrabold text-brand-800 hover:bg-brand-100"
                        >
                            Alle Beiträge anzeigen →
                        </Link>
                    </div>

                    {posts.length === 0 ? (
                        <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-600">
                            Aktuell sind noch keine Beiträge veröffentlicht.
                        </div>
                    ) : (
                        <div className="grid gap-6 sm:grid-cols-2">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={post} />
                            ))}
                        </div>
                    )}
                </section>

                {/* Rechte Spalte (4/12): Terminkalender-Widget & Infokarte */}
                <aside className="lg:col-span-4 space-y-6">
                    <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
                        <div className="bg-gradient-to-r from-brand-600 to-brand-700 px-5 py-4 text-white flex items-center justify-between">
                            <div>
                                <h2 className="text-base font-extrabold">Nächste Termine</h2>
                                <p className="text-xs text-brand-100">Veranstaltungen & wichtige Tage</p>
                            </div>
                            <Link
                                href="/termine"
                                className="rounded-lg bg-white/15 px-2.5 py-1 text-xs font-bold text-white hover:bg-white/25"
                            >
                                Alle →
                            </Link>
                        </div>

                        {upcomingEvents.length === 0 ? (
                            <p className="p-5 text-sm text-slate-500">Derzeit stehen keine neuen Termine an.</p>
                        ) : (
                            <ul className="divide-y divide-slate-100 p-4">
                                {upcomingEvents.map((event) => (
                                    <li key={event.id} className="py-3 first:pt-1 last:pb-1 flex items-start gap-3.5">
                                        <div className="flex h-13 w-13 shrink-0 flex-col items-center justify-center rounded-xl bg-brand-50 border border-brand-200 text-brand-800">
                                            <span className="text-base font-extrabold leading-none">{event.day}</span>
                                            <span className="mt-0.5 text-[10px] font-bold uppercase text-brand-600">
                                                {event.monthShort}
                                            </span>
                                        </div>
                                        <div className="min-w-0 flex-1">
                                            <p className="font-bold text-sm text-slate-900 leading-snug">
                                                {event.title}
                                            </p>
                                            <p className="mt-0.5 text-xs text-slate-500">
                                                {event.dateFormatted} · {event.timeFormatted}
                                            </p>
                                            {event.location && (
                                                <p className="mt-0.5 text-xs font-semibold text-brand-700">
                                                    📍 {event.location}
                                                </p>
                                            )}
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>

                    {/* Kontakt- & Sekretariats-Widget im EduPage-Stil */}
                    <div className="rounded-2xl border border-brand-200 bg-gradient-to-b from-brand-50 to-white p-6 shadow-xs">
                        <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                            Schulbüro & Krankmeldung
                        </span>
                        <h3 className="mt-1 text-lg font-extrabold text-slate-900">Sekretariat</h3>
                        <p className="mt-2 text-xs leading-relaxed text-slate-600">
                            Bitte melden Sie Ihr Kind im Krankheitsfall morgens vor Unterrichtsbeginn telefonisch oder
                            per E-Mail im Sekretariat ab.
                        </p>
                        <div className="mt-4 space-y-2 text-sm">
                            <div className="rounded-xl bg-white p-3 border border-brand-100">
                                <span className="block text-xs text-slate-500">Telefon</span>
                                <a href={`tel:${school.phone}`} className="font-extrabold text-brand-800">
                                    {school.phone}
                                </a>
                            </div>
                            <div className="rounded-xl bg-white p-3 border border-brand-100">
                                <span className="block text-xs text-slate-500">E-Mail</span>
                                <a href={`mailto:${school.email}`} className="font-bold text-brand-800 break-all">
                                    {school.email}
                                </a>
                            </div>
                            <div className="rounded-xl bg-white p-3 border border-brand-100">
                                <span className="block text-xs text-slate-500">Öffnungszeiten</span>
                                <span className="font-semibold text-slate-800">{school.office_hours}</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

            {/* Einblick in unsere Schule mit den Originalfotos der Mühlmattenschule */}
            <section className="mx-auto max-w-7xl px-4 pb-10">
                <div className="grid gap-6 md:grid-cols-3">
                    <Link
                        href="/unsere-schule/schulportrait"
                        className="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md"
                    >
                        <div className="aspect-[16/10] w-full overflow-hidden bg-slate-100">
                            <img
                                src="/images/school/about-main.jpg"
                                alt="Schulgebäude und Pausenhof"
                                loading="lazy"
                                className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                            />
                        </div>
                        <div className="p-5">
                            <span className="text-[11px] font-extrabold uppercase tracking-wider text-brand-600">
                                Schulportrait
                            </span>
                            <h3 className="mt-1 text-lg font-extrabold text-slate-900 group-hover:text-brand-700">
                                Schulgebäude & Architektur
                            </h3>
                            <p className="mt-1 text-xs leading-relaxed text-slate-600">
                                Helle Räume, viel Holz und ein weitläufiger Pausenhof im Grünen am Ortsrand von Hochdorf.
                            </p>
                        </div>
                    </Link>

                    <Link
                        href="/unsere-schule/kollegium"
                        className="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md"
                    >
                        <div className="aspect-[16/10] w-full overflow-hidden bg-slate-100">
                            <img
                                src="/images/school/kollegium-wide.jpg"
                                alt="Das Kollegium der Mühlmattenschule"
                                loading="lazy"
                                className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                            />
                        </div>
                        <div className="p-5">
                            <span className="text-[11px] font-extrabold uppercase tracking-wider text-brand-600">
                                Unser Team
                            </span>
                            <h3 className="mt-1 text-lg font-extrabold text-slate-900 group-hover:text-brand-700">
                                Kollegium & Schulleitung
                            </h3>
                            <p className="mt-1 text-xs leading-relaxed text-slate-600">
                                20 Lehrkräfte, Schulleitung, Sekretariat und Schulsozialarbeit begleiten unsere 187 Kinder.
                            </p>
                        </div>
                    </Link>

                    <Link
                        href="/schulleben/betreuung-und-ags"
                        className="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md"
                    >
                        <div className="aspect-[16/10] w-full overflow-hidden bg-slate-100">
                            <img
                                src="/images/school/about-kids.jpg"
                                alt="Schulkindbetreuung und Unterricht"
                                loading="lazy"
                                className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                            />
                        </div>
                        <div className="p-5">
                            <span className="text-[11px] font-extrabold uppercase tracking-wider text-brand-600">
                                Betreuung & AGs
                            </span>
                            <h3 className="mt-1 text-lg font-extrabold text-slate-900 group-hover:text-brand-700">
                                Schulkindbetreuung bis 17 Uhr
                            </h3>
                            <p className="mt-1 text-xs leading-relaxed text-slate-600">
                                Verlässliche Betreuung durch die Caritas von 7:30 bis 17:00 Uhr inklusive Mittagessen und AGs.
                            </p>
                        </div>
                    </Link>
                </div>
            </section>

            {/* Fotogalerie-Vorschau */}
            {albums.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 pb-8">
                    <div className="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
                        <div className="flex flex-wrap items-end justify-between gap-4 mb-6">
                            <div>
                                <span className="text-xs font-extrabold uppercase tracking-wider text-brand-600">
                                    Fotogalerie
                                </span>
                                <h2 className="text-2xl font-extrabold text-slate-900">
                                    Einblicke in unser Schulleben
                                </h2>
                            </div>
                            <Link
                                href="/galerie"
                                className="rounded-xl bg-brand-50 px-4 py-2 text-xs font-extrabold text-brand-800 hover:bg-brand-100"
                            >
                                Alle Fotoalben →
                            </Link>
                        </div>

                        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                            {albums.map((album) => (
                                <Link
                                    key={album.id}
                                    href={`/galerie/${album.slug}`}
                                    className="group overflow-hidden rounded-2xl border border-slate-200 bg-surface transition hover:-translate-y-1 hover:border-brand-300 hover:shadow-md"
                                >
                                    <div className="aspect-[4/3] w-full overflow-hidden bg-brand-100">
                                        {album.coverUrl ? (
                                            <img
                                                src={album.coverUrl}
                                                alt={album.title}
                                                loading="lazy"
                                                className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            />
                                        ) : (
                                            <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 to-brand-700 p-4 text-center text-white text-sm font-bold">
                                                {album.title}
                                            </div>
                                        )}
                                    </div>
                                    <div className="p-4">
                                        <div className="flex items-center justify-between text-xs text-slate-500">
                                            <span>{album.eventDate || 'Schuljahr'}</span>
                                            {album.className && (
                                                <span className="rounded-full bg-amber-400 px-2 py-0.5 font-bold text-slate-950">
                                                    {album.className}
                                                </span>
                                            )}
                                        </div>
                                        <p className="mt-1.5 font-extrabold text-slate-900 group-hover:text-brand-700">
                                            {album.title}
                                        </p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}
