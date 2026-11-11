import { PostCard } from '@/components/post-card';
import { RubrikenWidget } from '@/components/rubriken-widget';
import { SiteLayout } from '@/layouts/site-layout';
import type { AlbumCardItem, PostCardItem, SharedInertiaProps, UpcomingEventItem } from '@/types/school';
import { Link, usePage } from '@inertiajs/react';
import { useEffect, useRef, useState, type ReactElement, type TouchEvent } from 'react';

export interface HomePageProps {
    posts: PostCardItem[];
    postsPagination?: {
        currentPage: number;
        lastPage: number;
        prevUrl: string | null;
        nextUrl: string | null;
        total: number;
    };
    upcomingEvents: UpcomingEventItem[];
    albums: AlbumCardItem[];
}

const HERO_AUTOPLAY_INTERVAL_MS = 5000;

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

const EVENT_CATEGORY_LABELS: Record<string, { label: string; className: string }> = {
    ferien: { label: 'Ferien / Schulfrei', className: 'bg-emerald-100 text-emerald-900' },
    eltern: { label: 'Eltern & Gremien', className: 'bg-sky-100 text-sky-900' },
    fest: { label: 'Schulfest & Projekttag', className: 'bg-amber-100 text-amber-950' },
    unterricht: { label: 'Unterricht & Klasse', className: 'bg-brand-100 text-brand-900' },
};

/**
 * EduPage-inspirierte Startseite (wie ghsfreiburg.edupage.org) im originalen Logo-Türkis-Design (#029786):
 * Hero-Bereich mit echtem Schulfoto, 1-spaltiger Nachrichten-Stream mit Paginierung links sowie
 * Pausen-, Kalender-, Rubriken- und Sekretariats-Widgets rechts.
 */
export default function HomePage({ posts, postsPagination, upcomingEvents, albums }: HomePageProps): ReactElement {
    const { school, announcements, rubriken } = usePage<SharedInertiaProps>().props;
    const heroPhotos =
        school.hero_slides && school.hero_slides.length > 0 ? school.hero_slides : SCHOOL_HERO_PHOTOS;
    const [heroPhotoIndex, setHeroPhotoIndex] = useState(0);
    const [isHeroPaused, setIsHeroPaused] = useState(false);
    const [prefersReducedMotion, setPrefersReducedMotion] = useState(false);
    const touchStartX = useRef<number | null>(null);
    const phoneHref = `tel:${school.phone.replace(/\s+/g, '')}`;

    useEffect(() => {
        const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        setPrefersReducedMotion(mediaQuery.matches);
        const handleChange = (event: MediaQueryListEvent): void => {
            setPrefersReducedMotion(event.matches);
        };
        mediaQuery.addEventListener('change', handleChange);
        return () => mediaQuery.removeEventListener('change', handleChange);
    }, []);

    useEffect(() => {
        if (heroPhotos.length <= 1 || isHeroPaused || prefersReducedMotion) {
            return;
        }

        const timer = window.setInterval(() => {
            if (!document.hidden) {
                setHeroPhotoIndex((prev) => (prev + 1) % heroPhotos.length);
            }
        }, HERO_AUTOPLAY_INTERVAL_MS);

        return () => window.clearInterval(timer);
    }, [heroPhotoIndex, heroPhotos.length, isHeroPaused, prefersReducedMotion]);

    const showPrevHero = (): void => {
        setHeroPhotoIndex((prev) => (prev - 1 + heroPhotos.length) % heroPhotos.length);
    };

    const showNextHero = (): void => {
        setHeroPhotoIndex((prev) => (prev + 1) % heroPhotos.length);
    };

    const handleHeroTouchStart = (event: TouchEvent<HTMLDivElement>): void => {
        const firstTouch = event.touches[0];
        if (firstTouch) {
            touchStartX.current = firstTouch.clientX;
        }
    };

    const handleHeroTouchEnd = (event: TouchEvent<HTMLDivElement>): void => {
        const endTouch = event.changedTouches[0];
        if (!endTouch || touchStartX.current === null) {
            return;
        }
        const deltaX = endTouch.clientX - touchStartX.current;
        touchStartX.current = null;
        if (Math.abs(deltaX) > 40) {
            if (deltaX < 0) {
                showNextHero();
            } else {
                showPrevHero();
            }
        }
    };

    return (
        <SiteLayout>
            {/* Charakteristischer, frischer Logo-Türkis-Hero-Bereich (#029786) */}
            <section className="relative overflow-hidden bg-gradient-to-br from-[#dff7f4] via-[#edfcf9] to-[#d0f2ee] border-b border-brand-300/70 shadow-xs text-ink">
                <div className="relative mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 py-8 sm:py-12 lg:py-16 grid gap-6 sm:gap-8 lg:grid-cols-12 items-center">
                    {/* Linke Seite: Willkommenstext im Logo-Türkis-Farbton */}
                    <div className="lg:col-span-6 flex flex-col justify-center">
                        <div className="inline-flex items-center gap-2 self-start rounded-full border border-brand-300 bg-white/90 px-3.5 py-1.5 text-xs font-bold tracking-wide text-brand-900 shadow-2xs">
                            <span className="inline-block h-2 w-2 rounded-full bg-brand-500" aria-hidden="true" />
                            <span>Grundschule Freiburg-Hochdorf & Benzhausen</span>
                        </div>

                        <h1 className="mt-3.5 sm:mt-4 text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-brand-900 break-words hyphens-auto">
                            {school.welcome_title || `Willkommen an der ${school.name}`}
                        </h1>

                        <p className="mt-3 sm:mt-4 max-w-2xl text-sm sm:text-lg text-brand-950/85 leading-relaxed font-normal">
                            {school.welcome_text ||
                                'Die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen – ein Ort zum Lernen, Wachsen und Entdecken.'}
                        </p>

                        <div className="mt-6 sm:mt-8 flex flex-col sm:flex-row flex-wrap gap-3">
                            <Link
                                href="/aktuelles"
                                className="inline-flex min-h-11 items-center justify-center rounded-xl bg-gradient-to-r from-brand-700 to-brand-800 px-5 py-3 text-sm font-extrabold text-white shadow-md transition hover:from-brand-600 hover:to-brand-700 hover:shadow-lg"
                            >
                                Aktuelle Beiträge →
                            </Link>
                            <Link
                                href="/unsere-schule/schulportrait"
                                className="inline-flex min-h-11 items-center justify-center rounded-xl border-2 border-brand-800/30 bg-white/90 px-5 py-3 text-sm font-bold text-brand-900 shadow-xs transition hover:border-brand-800 hover:bg-white"
                            >
                                Unsere Schule kennenlernen
                            </Link>
                        </div>
                    </div>

                    {/* Rechte Seite: Echtes Foto des Schulgebäudes der Mühlmattenschule mit weißem Passepartout-Rahmen */}
                    <div className="lg:col-span-6">
                        <div
                            onTouchStart={handleHeroTouchStart}
                            onTouchEnd={handleHeroTouchEnd}
                            onMouseEnter={() => setIsHeroPaused(true)}
                            onMouseLeave={() => setIsHeroPaused(false)}
                            onFocus={() => setIsHeroPaused(true)}
                            onBlur={(event) => {
                                if (!event.currentTarget.contains(event.relatedTarget)) {
                                    setIsHeroPaused(false);
                                }
                            }}
                            className="relative overflow-hidden rounded-2xl sm:rounded-3xl border-4 border-white bg-white shadow-xl ring-1 ring-brand-900/10 aspect-[16/10]"
                        >
                            {heroPhotos.map((photo, index) => (
                                <img
                                    key={`${photo.url}-${index}`}
                                    src={photo.url}
                                    alt={photo.alt}
                                    fetchPriority={index === 0 ? 'high' : 'auto'}
                                    decoding="async"
                                    aria-hidden={heroPhotoIndex !== index}
                                    className={`absolute inset-0 h-full w-full object-cover select-none transition-opacity duration-700 ease-in-out ${
                                        heroPhotoIndex === index ? 'opacity-100' : 'opacity-0 pointer-events-none'
                                    }`}
                                />
                            ))}
                            <button
                                type="button"
                                onClick={showPrevHero}
                                aria-label="Vorheriges Schulfoto"
                                className="absolute left-2.5 sm:left-3 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full border border-white/30 bg-slate-900/55 text-white backdrop-blur-md transition hover:scale-110 hover:bg-brand-600 cursor-pointer"
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
                                onClick={showNextHero}
                                aria-label="Nächstes Schulfoto"
                                className="absolute right-2.5 sm:right-3 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full border border-white/30 bg-slate-900/55 text-white backdrop-blur-md transition hover:scale-110 hover:bg-brand-600 cursor-pointer"
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

                            {/* Bild-Indikatoren für Mobilgeräte & Desktop */}
                            <div className="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2 rounded-full bg-slate-950/50 px-3 py-1.5 backdrop-blur-xs">
                                {heroPhotos.map((photo, index) => (
                                    <button
                                        key={photo.url}
                                        type="button"
                                        onClick={() => setHeroPhotoIndex(index)}
                                        aria-label={`Schulfoto ${index + 1} anzeigen`}
                                        aria-current={heroPhotoIndex === index ? 'true' : undefined}
                                        className={`h-2.5 rounded-full transition-all cursor-pointer ${
                                            heroPhotoIndex === index
                                                ? 'w-6 bg-white'
                                                : 'w-2.5 bg-white/50 hover:bg-white/80'
                                        }`}
                                    />
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Aktive Ankündigungen aus dem Admin-Panel (/admin/announcements) */}
            {announcements.length > 0 && (
                <section
                    aria-label="Aktuelle Ankündigungen"
                    className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 pt-6 sm:pt-8 space-y-3"
                >
                    {announcements.map((item) => {
                        const isImportant = item.level === 'important';
                        return (
                            <div
                                key={item.id}
                                className={`flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 rounded-2xl border p-4 sm:px-5 sm:py-4 shadow-xs ${
                                    isImportant
                                        ? 'border-amber-300 bg-gradient-to-r from-amber-50 to-orange-50 text-amber-950'
                                        : 'border-brand-200 bg-gradient-to-r from-brand-50 to-white text-slate-900'
                                }`}
                            >
                                <div className="flex items-start sm:items-center gap-3">
                                    <span
                                        className={`inline-flex shrink-0 items-center rounded-xl px-3 py-1 text-xs font-extrabold uppercase tracking-wider ${
                                            isImportant
                                                ? 'bg-amber-400 text-slate-950'
                                                : 'bg-brand-700 text-white'
                                        }`}
                                    >
                                        {isImportant ? 'Wichtig' : 'Ankündigung'}
                                    </span>
                                    <p className="text-sm sm:text-base font-bold leading-snug break-words">
                                        {item.text}
                                    </p>
                                </div>

                                {item.linkUrl && (
                                    <Link
                                        href={item.linkUrl}
                                        className={`inline-flex min-h-10 shrink-0 items-center justify-center rounded-xl px-4 py-2 text-xs font-extrabold transition ${
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

            {/* Hauptbereich im EduPage-Portal-Layout (1 Beitrag pro Zeile links, Pausen/Kalender/Rubriken/Kontakt rechts) */}
            <div className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 py-8 sm:py-12 grid gap-8 sm:gap-10 lg:grid-cols-12">
                {/* Linke Spalte (8/12): Aktuelle Nachrichten & Klassenaktivitäten untereinander (1 pro Zeile) + Paginierung */}
                <section className="lg:col-span-8">
                    <div className="flex flex-wrap items-end justify-between gap-3 sm:gap-4 mb-5 sm:mb-6">
                        <div>
                            <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                                Neuigkeiten & Schulleben
                            </span>
                            <h2 className="text-xl sm:text-3xl font-extrabold text-slate-900">
                                Aktuelles aus der Mühlmattenschule
                            </h2>
                        </div>
                        <Link
                            href="/aktuelles"
                            className="inline-flex min-h-10 items-center rounded-xl bg-brand-100 px-4 py-2 text-xs font-extrabold text-brand-800 hover:bg-brand-200"
                        >
                            Alle Beiträge & Filter →
                        </Link>
                    </div>

                    {posts.length === 0 ? (
                        <div className="rounded-2xl border border-slate-200 bg-white p-8 sm:p-10 text-center text-slate-600">
                            Aktuell sind noch keine Beiträge veröffentlicht.
                        </div>
                    ) : (
                        <div className="flex flex-col gap-5 sm:gap-6">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={post} layout="horizontal" />
                            ))}
                        </div>
                    )}

                    {/* EduPage-inspirierte Paginierung direkt auf der Startseite */}
                    {postsPagination && postsPagination.lastPage > 1 && (
                        <nav
                            aria-label="Nachrichten-Seitennavigation"
                            className="mt-7 sm:mt-8 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs"
                        >
                            <div>
                                {postsPagination.prevUrl ? (
                                    <Link
                                        href={postsPagination.prevUrl}
                                        className="inline-flex min-h-10 items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs sm:text-sm font-extrabold text-slate-800 hover:border-brand-400 hover:bg-brand-50"
                                    >
                                        ← Neuere Beiträge
                                    </Link>
                                ) : (
                                    <span className="inline-flex min-h-10 items-center rounded-xl border border-slate-100 bg-slate-50 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-400">
                                        ← Neuere Beiträge
                                    </span>
                                )}
                            </div>

                            <span className="text-xs sm:text-sm font-bold text-slate-600">
                                Seite {postsPagination.currentPage} von {postsPagination.lastPage} ({postsPagination.total}{' '}
                                Beiträge)
                            </span>

                            <div>
                                {postsPagination.nextUrl ? (
                                    <Link
                                        href={postsPagination.nextUrl}
                                        className="inline-flex min-h-10 items-center rounded-xl bg-brand-700 px-4 py-2 text-xs sm:text-sm font-extrabold text-white hover:bg-brand-800"
                                    >
                                        Ältere Beiträge →
                                    </Link>
                                ) : (
                                    <span className="inline-flex min-h-10 items-center rounded-xl border border-slate-100 bg-slate-50 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-400">
                                        Ältere Beiträge →
                                    </span>
                                )}
                            </div>
                        </nav>
                    )}
                </section>

                {/* Rechte Spalte (4/12): EduPage-Sidebar-Widgets (Kalender, Pausen, Beiträge nach Rubriken, Sekretariat) */}
                <aside className="lg:col-span-4 space-y-6">
                    {/* 1. Terminkalender-Widget mit Klassen-/Typ-Badges & iCal-Download */}
                    <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
                        <div className="bg-gradient-to-r from-brand-700 to-brand-800 px-5 py-4 text-white flex items-center justify-between gap-2">
                            <div>
                                <h2 className="text-base font-extrabold">Nächste Termine</h2>
                                <p className="text-xs text-brand-100">Veranstaltungen & wichtige Tage</p>
                            </div>
                            <Link
                                href="/termine"
                                className="inline-flex min-h-9 items-center rounded-lg bg-white/15 px-3 py-1.5 text-xs font-bold text-white hover:bg-white/25"
                            >
                                Alle →
                            </Link>
                        </div>

                        {upcomingEvents.length === 0 ? (
                            <p className="p-5 text-sm text-slate-500">Derzeit stehen keine neuen Termine an.</p>
                        ) : (
                            <ul className="divide-y divide-slate-100 p-4">
                                {upcomingEvents.map((event) => {
                                    const catBadge = event.category
                                        ? EVENT_CATEGORY_LABELS[event.category]
                                        : undefined;
                                    return (
                                        <li
                                            key={event.id}
                                            className="py-3.5 first:pt-1 last:pb-1 flex items-start gap-3.5"
                                        >
                                            <div className="flex h-13 w-13 shrink-0 flex-col items-center justify-center rounded-xl bg-brand-50 border border-brand-200 text-brand-800">
                                                <span className="text-base font-extrabold leading-none">
                                                    {event.day}
                                                </span>
                                                <span className="mt-0.5 text-[10px] font-bold uppercase text-brand-700">
                                                    {event.monthShort}
                                                </span>
                                            </div>
                                            <div className="min-w-0 flex-1">
                                                <div className="flex flex-wrap items-center gap-1.5 mb-1">
                                                    {catBadge && (
                                                        <span
                                                            className={`rounded-md px-2 py-0.5 text-[10px] font-bold ${catBadge.className}`}
                                                        >
                                                            {catBadge.label}
                                                        </span>
                                                    )}
                                                    {event.className && (
                                                        <span className="rounded-md bg-amber-400 px-2 py-0.5 text-[10px] font-extrabold text-slate-950">
                                                            {event.className}
                                                        </span>
                                                    )}
                                                </div>
                                                <p className="font-bold text-sm text-slate-900 leading-snug break-words">
                                                    {event.title}
                                                </p>
                                                <p className="mt-0.5 text-xs text-slate-500">
                                                    {event.dateFormatted} · {event.timeFormatted}
                                                </p>
                                                <div className="mt-1 flex flex-wrap items-center justify-between gap-2">
                                                    {event.location ? (
                                                        <span className="text-xs font-semibold text-brand-700">
                                                            📍 {event.location}
                                                        </span>
                                                    ) : (
                                                        <span />
                                                    )}
                                                    {event.icalUrl && (
                                                        <a
                                                            href={event.icalUrl}
                                                            title="Termin im Kalender speichern (.ics)"
                                                            className="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-700 hover:bg-brand-100 hover:text-brand-800"
                                                        >
                                                            📅 .ics
                                                        </a>
                                                    )}
                                                </div>
                                            </div>
                                        </li>
                                    );
                                })}
                            </ul>
                        )}

                        <div className="border-t border-slate-100 bg-slate-50/70 px-4 py-2.5 flex items-center justify-between text-xs">
                            <a
                                href="/termine.ics"
                                className="font-bold text-brand-800 hover:text-brand-950 inline-flex items-center gap-1.5"
                            >
                                <span>📅</span> Kalender exportieren (.ics)
                            </a>
                            <Link href="/termine" className="font-semibold text-slate-600 hover:text-brand-800">
                                Monatsübersicht →
                            </Link>
                        </div>
                    </div>

                    {/* 2. EduPage-Widget „Unterrichts- & Pausenzeiten“ (Pausen / Läuteordnung) */}
                    {school.bell_schedule && school.bell_schedule.length > 0 && (
                        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
                            <div className="bg-gradient-to-r from-brand-800 to-brand-900 px-5 py-3.5 text-white flex items-center justify-between gap-2">
                                <div>
                                    <h2 className="text-base font-extrabold">Unterrichts- & Pausenzeiten</h2>
                                    <p className="text-xs text-brand-100">Läuteordnung am Schulvormittag</p>
                                </div>
                                <Link
                                    href="/unsere-schule/unterrichtszeiten"
                                    className="inline-flex min-h-8 items-center rounded-lg bg-white/15 px-2.5 py-1 text-xs font-bold text-white hover:bg-white/25"
                                >
                                    Details →
                                </Link>
                            </div>
                            <ul className="divide-y divide-slate-100 p-3.5 text-xs">
                                {school.bell_schedule.map((slot, idx) => {
                                    const isBreak = slot.type === 'break' || Boolean(slot.is_break);
                                    return (
                                        <li
                                            key={idx}
                                            className={`flex items-center justify-between gap-2 rounded-lg px-2.5 py-2 ${
                                                isBreak
                                                    ? 'bg-amber-50/90 font-bold text-amber-950'
                                                    : 'text-slate-700'
                                            }`}
                                        >
                                            <span className="flex items-center gap-2">
                                                <span
                                                    className={`inline-block h-2 w-2 rounded-full ${
                                                        isBreak ? 'bg-amber-500' : 'bg-brand-500'
                                                    }`}
                                                    aria-hidden="true"
                                                />
                                                <span className="font-bold">{slot.label}</span>
                                            </span>
                                            <span className="font-mono font-semibold text-slate-800">{slot.time}</span>
                                        </li>
                                    );
                                })}
                            </ul>
                        </div>
                    )}

                    {/* 3. Original-Widget „Beiträge nach Rubriken“ */}
                    <RubrikenWidget rubriken={rubriken} />

                    {/* 4. Kontakt- & Sekretariats-Widget */}
                    <div className="rounded-2xl border border-brand-200 bg-gradient-to-b from-brand-50 to-white p-5 sm:p-6 shadow-xs">
                        <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                            Schulbüro & Krankmeldung
                        </span>
                        <h3 className="mt-1 text-lg font-extrabold text-slate-900">Sekretariat</h3>
                        <p className="mt-2 text-xs leading-relaxed text-slate-600">
                            Bitte melden Sie Ihr Kind im Krankheitsfall morgens vor Unterrichtsbeginn telefonisch oder
                            per E-Mail im Sekretariat ab.
                        </p>
                        <div className="mt-4 space-y-2 text-sm">
                            <a
                                href={phoneHref}
                                className="block rounded-xl bg-white p-3 border border-brand-100 hover:border-brand-300 transition"
                            >
                                <span className="block text-xs text-slate-500">Telefon (Direktanruf)</span>
                                <span className="font-extrabold text-brand-800">☎ {school.phone}</span>
                            </a>
                            <a
                                href={`mailto:${school.email}`}
                                className="block rounded-xl bg-white p-3 border border-brand-100 hover:border-brand-300 transition"
                            >
                                <span className="block text-xs text-slate-500">E-Mail</span>
                                <span className="font-bold text-brand-800 break-all">✉ {school.email}</span>
                            </a>
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
                <section className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 pb-8">
                    <div className="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-8 shadow-xs">
                        <div className="flex flex-wrap items-end justify-between gap-3 sm:gap-4 mb-5 sm:mb-6">
                            <div>
                                <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                                    Fotogalerie
                                </span>
                                <h2 className="text-xl sm:text-2xl font-extrabold text-slate-900">
                                    Einblicke in unser Schulleben
                                </h2>
                            </div>
                            <Link
                                href="/galerie"
                                className="inline-flex min-h-10 items-center rounded-xl bg-brand-100 px-4 py-2 text-xs font-extrabold text-brand-800 hover:bg-brand-200"
                            >
                                Alle Fotoalben →
                            </Link>
                        </div>

                        <div className="grid grid-cols-2 gap-3.5 sm:gap-5 lg:grid-cols-4">
                            {albums.map((album) => (
                                <Link
                                    key={album.id}
                                    href={`/galerie/${album.slug}`}
                                    className="group overflow-hidden rounded-2xl border border-slate-200 bg-surface transition hover:-translate-y-1 hover:border-brand-300 hover:shadow-md"
                                >
                                    <div className="relative aspect-[4/3] w-full overflow-hidden bg-brand-100">
                                        {album.coverUrl ? (
                                            <img
                                                src={album.coverUrl}
                                                alt={album.title}
                                                loading="lazy"
                                                className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            />
                                        ) : (
                                            <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 to-brand-700 p-3 sm:p-4 text-center text-white text-xs sm:text-sm font-bold">
                                                {album.title}
                                            </div>
                                        )}
                                        {album.photosCount > 0 && (
                                            <span className="absolute bottom-2 right-2 rounded-full bg-slate-950/75 px-2.5 py-0.5 text-[11px] font-bold text-white backdrop-blur-xs">
                                                📷 {album.photosCount} Fotos
                                            </span>
                                        )}
                                    </div>
                                    <div className="p-3 sm:p-4">
                                        <div className="flex flex-wrap items-center justify-between gap-1 text-[11px] sm:text-xs text-slate-500">
                                            <span>{album.eventDate || 'Schuljahr'}</span>
                                            {album.className && (
                                                <span className="rounded-full bg-amber-400 px-2 py-0.5 font-bold text-slate-950">
                                                    {album.className}
                                                </span>
                                            )}
                                        </div>
                                        <p className="mt-1 sm:mt-1.5 text-sm sm:text-base font-extrabold text-slate-900 group-hover:text-brand-700 line-clamp-2 break-words">
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
