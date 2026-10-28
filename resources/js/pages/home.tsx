import { PostCard } from '@/components/post-card';
import { SiteLayout } from '@/layouts/site-layout';
import type { AlbumCardItem, PostCardItem, SharedInertiaProps, UpcomingEventItem } from '@/types/school';
import { Head, Link, usePage } from '@inertiajs/react';
import { useState, type ReactElement } from 'react';

export interface HomePageProps {
    posts: PostCardItem[];
    upcomingEvents: UpcomingEventItem[];
    albums: AlbumCardItem[];
}

const SCHOOL_HERO_PHOTOS = [
    {
        url: '/images/school/about-main.jpg',
        alt: 'Schulgebäude und Pausenhof der Mühlmattenschule Freiburg-Hochdorf',
    },
    {
        url: '/images/school/gebaeude-wide.jpg',
        alt: 'Ansicht des Schulhauses im Grünen',
    },
    {
        url: '/images/school/hero.jpg',
        alt: 'Eingangsbereich und Holzdach der Mühlmattenschule',
    },
];

/**
 * EduPage-inspirierte Startseite (wie ghsfreiburg.edupage.org) im dunklen Türkis-Design:
 * Hero-Bereich mit echtem Schulfoto, 1-spaltiger Nachrichten-Stream links und Termin-Widget rechts.
 */
export default function HomePage({ posts, upcomingEvents, albums }: HomePageProps): ReactElement {
    const { school, announcements } = usePage<SharedInertiaProps>().props;
    const [heroPhotoIndex, setHeroPhotoIndex] = useState(0);
    const currentHeroPhoto = SCHOOL_HERO_PHOTOS[heroPhotoIndex] ?? SCHOOL_HERO_PHOTOS[0];

    return (
        <SiteLayout>
            <Head title="Startseite" />

            {/* Dunkel-türkiser Hero-Bereich mit Willkommenstext und echtem Schulfoto aus der alten Website */}
            <section className="relative overflow-hidden bg-gradient-to-br from-brand-800 via-brand-700 to-brand-900 text-white">
                <div className="relative mx-auto max-w-[1440px] px-6 lg:px-10 py-10 lg:py-14 grid gap-8 lg:grid-cols-12 items-center">
                    {/* Linke Seite: Willkommenstext ohne Zahlen */}
                    <div className="lg:col-span-6 flex flex-col justify-center">
                        <div className="inline-flex items-center gap-2 self-start rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold tracking-wide text-brand-50">
                            <span>Grundschule Freiburg-Hochdorf & Benzhausen</span>
                        </div>

                        <h1 className="mt-4 text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                            {school.welcome_title || `Willkommen an der ${school.name}`}
                        </h1>

                        <p className="mt-4 max-w-2xl text-base sm:text-lg text-brand-50/95 leading-relaxed">
                            {school.welcome_text ||
                                'Die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen – ein Ort zum Lernen, Wachsen und Entdecken.'}
                        </p>

                        <div className="mt-8 flex flex-wrap gap-3.5">
                            <Link
                                href="/aktuelles"
                                className="rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-800 shadow-md hover:bg-brand-50"
                            >
                                Aktuelle Beiträge →
                            </Link>
                            <Link
                                href="/unsere-schule/schulportrait"
                                className="rounded-xl border border-white/35 bg-white/10 px-5 py-3 text-sm font-bold text-white hover:bg-white/20"
                            >
                                Unsere Schule kennenlernen
                            </Link>
                        </div>
                    </div>

                    {/* Rechte Seite: Echtes Foto des Schulgebäudes der Mühlmattenschule */}
                    <div className="lg:col-span-6">
                        <div className="relative overflow-hidden rounded-3xl border-2 border-white/25 bg-brand-950/40 shadow-2xl aspect-[16/10]">
                            <img
                                src={currentHeroPhoto.url}
                                alt={currentHeroPhoto.alt}
                                className="h-full w-full object-cover"
                            />
                            <button
                                type="button"
                                onClick={() =>
                                    setHeroPhotoIndex(
                                        (prev) =>
                                            (prev - 1 + SCHOOL_HERO_PHOTOS.length) % SCHOOL_HERO_PHOTOS.length,
                                    )
                                }
                                aria-label="Vorheriges Schulfoto"
                                className="absolute left-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full border border-white/30 bg-slate-900/55 text-white backdrop-blur-md transition hover:scale-110 hover:bg-brand-600 cursor-pointer"
                            >
                                <svg
                                    className="h-5 w-5 -ml-0.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeWidth="2.4"
                                >
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                onClick={() =>
                                    setHeroPhotoIndex((prev) => (prev + 1) % SCHOOL_HERO_PHOTOS.length)
                                }
                                aria-label="Nächstes Schulfoto"
                                className="absolute right-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full border border-white/30 bg-slate-900/55 text-white backdrop-blur-md transition hover:scale-110 hover:bg-brand-600 cursor-pointer"
                            >
                                <svg
                                    className="h-5 w-5 ml-0.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeWidth="2.4"
                                >
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {/* Aktive Ankündigungen aus dem Admin-Panel (/admin/announcements) */}
            {announcements.length > 0 && (
                <section
                    aria-label="Aktuelle Ankündigungen"
                    className="mx-auto max-w-[1440px] px-6 lg:px-10 pt-8 space-y-3"
                >
                    {announcements.map((item) => {
                        const isImportant = item.level === 'important';
                        return (
                            <div
                                key={item.id}
                                className={`flex flex-wrap items-center justify-between gap-4 rounded-2xl border px-5 py-4 shadow-xs ${
                                    isImportant
                                        ? 'border-amber-300 bg-gradient-to-r from-amber-50 to-orange-50 text-amber-950'
                                        : 'border-brand-200 bg-gradient-to-r from-brand-50 to-white text-slate-900'
                                }`}
                            >
                                <div className="flex items-start sm:items-center gap-3.5">
                                    <span
                                        className={`inline-flex shrink-0 items-center rounded-xl px-3 py-1 text-xs font-extrabold uppercase tracking-wider ${
                                            isImportant
                                                ? 'bg-amber-400 text-slate-950'
                                                : 'bg-brand-700 text-white'
                                        }`}
                                    >
                                        {isImportant ? 'Wichtig' : 'Ankündigung'}
                                    </span>
                                    <p className="text-sm sm:text-base font-bold leading-snug">{item.text}</p>
                                </div>

                                {item.linkUrl && (
                                    <Link
                                        href={item.linkUrl}
                                        className={`shrink-0 rounded-xl px-4 py-2 text-xs font-extrabold transition ${
                                            isImportant
                                                ? 'bg-amber-900 text-white hover:bg-amber-950'
                                                : 'bg-brand-700 text-white hover:bg-brand-800'
                                        }`}
                                    >
                                        {item.linkLabel || 'Mehr erfahren'} →
                                    </Link>
                                )}
                            </div>
                        );
                    })}
                </section>
            )}

            {/* Hauptbereich im EduPage-Portal-Layout (1 Beitrag pro Zeile links, Termine & Kontakt rechts) */}
            <div className="mx-auto max-w-[1440px] px-6 lg:px-10 py-12 grid gap-10 lg:grid-cols-12">
                {/* Linke Spalte (8/12): Aktuelle Nachrichten & Klassenaktivitäten untereinander (1 pro Zeile) */}
                <section className="lg:col-span-8">
                    <div className="flex flex-wrap items-end justify-between gap-4 mb-6">
                        <div>
                            <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                                Neuigkeiten & Schulleben
                            </span>
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900">
                                Aktuelles aus der Mühlmattenschule
                            </h2>
                        </div>
                        <Link
                            href="/aktuelles"
                            className="rounded-xl bg-brand-100 px-4 py-2.5 text-xs font-extrabold text-brand-800 hover:bg-brand-200"
                        >
                            Alle Beiträge anzeigen →
                        </Link>
                    </div>

                    {posts.length === 0 ? (
                        <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-600">
                            Aktuell sind noch keine Beiträge veröffentlicht.
                        </div>
                    ) : (
                        <div className="flex flex-col gap-6">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={post} layout="horizontal" />
                            ))}
                        </div>
                    )}
                </section>

                {/* Rechte Spalte (4/12): Terminkalender-Widget & Infokarte */}
                <aside className="lg:col-span-4 space-y-6">
                    <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
                        <div className="bg-gradient-to-r from-brand-700 to-brand-800 px-5 py-4 text-white flex items-center justify-between">
                            <div>
                                <h2 className="text-base font-extrabold">Nächste Termine</h2>
                                <p className="text-xs text-brand-100">Veranstaltungen & wichtige Tage</p>
                            </div>
                            <Link
                                href="/termine"
                                className="rounded-lg bg-white/15 px-3 py-1 text-xs font-bold text-white hover:bg-white/25"
                            >
                                Alle →
                            </Link>
                        </div>

                        {upcomingEvents.length === 0 ? (
                            <p className="p-5 text-sm text-slate-500">Derzeit stehen keine neuen Termine an.</p>
                        ) : (
                            <ul className="divide-y divide-slate-100 p-4">
                                {upcomingEvents.map((event) => (
                                    <li key={event.id} className="py-3.5 first:pt-1 last:pb-1 flex items-start gap-3.5">
                                        <div className="flex h-13 w-13 shrink-0 flex-col items-center justify-center rounded-xl bg-brand-50 border border-brand-200 text-brand-800">
                                            <span className="text-base font-extrabold leading-none">{event.day}</span>
                                            <span className="mt-0.5 text-[10px] font-bold uppercase text-brand-700">
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

                    {/* Kontakt- & Sekretariats-Widget */}
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

            {/* Fotogalerie-Vorschau */}
            {albums.length > 0 && (
                <section className="mx-auto max-w-[1440px] px-6 lg:px-10 pb-8">
                    <div className="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
                        <div className="flex flex-wrap items-end justify-between gap-4 mb-6">
                            <div>
                                <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                                    Fotogalerie
                                </span>
                                <h2 className="text-2xl font-extrabold text-slate-900">
                                    Einblicke in unser Schulleben
                                </h2>
                            </div>
                            <Link
                                href="/galerie"
                                className="rounded-xl bg-brand-100 px-4 py-2 text-xs font-extrabold text-brand-800 hover:bg-brand-200"
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
