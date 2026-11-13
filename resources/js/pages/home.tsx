import { PostCard } from '@/components/post-card';
import { Reveal } from '@/components/reveal';
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
                    <Reveal variant="fade-up" duration={600} className="lg:col-span-6 flex flex-col justify-center">
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
                    </Reveal>

                    {/* Rechte Seite: Echtes Foto des Schulgebäudes der Mühlmattenschule mit weißem Passepartout-Rahmen */}
                    <Reveal variant="zoom-in" delay={100} duration={650} className="lg:col-span-6">
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
                    </Reveal>
                </div>
            </section>

            {/* Aktive Ankündigungen aus dem Admin-Panel (/admin/announcements) */}
            {announcements.length > 0 && (
                <Reveal
                    as="section"
                    variant="fade-up"
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
                                        className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-xl ring-1 ${
                                            isImportant
                                                ? 'bg-amber-400/25 text-amber-900 ring-amber-500/30'
                                                : 'bg-brand-50 text-brand-700 ring-brand-500/15'
                                        }`}
                                        aria-hidden="true"
                                    >
                                        <svg className="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                                        </svg>
                                    </span>
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
                </Reveal>
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
                            {posts.map((post, index) => (
                                <Reveal key={post.id} variant="fade-up" delay={Math.min(index, 3) * 65}>
                                    <PostCard post={post} layout="horizontal" />
                                </Reveal>
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
                    <Reveal
                        as="section"
                        variant="fade-up"
                        delay={60}
                        aria-label="Nächste Termine"
                        className="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs"
                    >
                        <div className="flex items-center justify-between gap-2 border-b border-slate-100 px-5 pt-5 pb-3 sm:px-6 sm:pt-6">
                            <h2 className="flex min-w-0 items-center gap-2.5 text-base font-extrabold text-brand-900 sm:text-lg">
                                <span
                                    aria-hidden="true"
                                    className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15"
                                >
                                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </span>
                                <span className="truncate">Nächste Termine</span>
                            </h2>
                            <Link
                                href="/termine"
                                className="shrink-0 rounded-full bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-800 transition hover:bg-brand-100"
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
                                                <div className="mt-1.5 flex flex-wrap items-center justify-between gap-2">
                                                    {event.location ? (
                                                        <span className="inline-flex items-center gap-1 text-xs font-semibold text-brand-700">
                                                            <svg className="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                            </svg>
                                                            <span>{event.location}</span>
                                                        </span>
                                                    ) : (
                                                        <span />
                                                    )}
                                                    {event.icalUrl && (
                                                        <a
                                                            href={event.icalUrl}
                                                            title="Termin im Kalender speichern (.ics)"
                                                            className="inline-flex items-center gap-1 rounded-lg border border-slate-200/80 bg-slate-50 px-2 py-0.5 text-[11px] font-bold text-slate-700 transition hover:border-brand-200 hover:bg-brand-50 hover:text-brand-800"
                                                        >
                                                            <svg className="h-3.5 w-3.5 text-brand-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                                                <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                            </svg>
                                                            <span>.ics</span>
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
                                <svg className="h-4 w-4 text-brand-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span>Kalender exportieren (.ics)</span>
                            </a>
                            <Link href="/termine" className="font-semibold text-slate-600 hover:text-brand-800">
                                Monatsübersicht →
                            </Link>
                        </div>
                    </Reveal>

                    {/* 2. EduPage-Widget „Unterrichts- & Pausenzeiten“ im Stil von „Beiträge nach Rubriken“ */}
                    {school.bell_schedule && school.bell_schedule.length > 0 && (
                        <Reveal
                            as="section"
                            variant="fade-up"
                            delay={120}
                            aria-label="Unterrichts- & Pausenzeiten"
                            className="rounded-2xl bg-white p-5 sm:p-6 shadow-xs border border-slate-200/80"
                        >
                            <div className="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                <h2 className="text-base sm:text-lg font-extrabold text-brand-900 flex items-center gap-2.5 min-w-0">
                                    <span
                                        aria-hidden="true"
                                        className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15"
                                    >
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                        </svg>
                                    </span>
                                    <span className="truncate">Unterrichts- &amp; Pausenzeiten</span>
                                </h2>
                                <Link
                                    href="/unsere-schule/unterrichtszeiten"
                                    className="shrink-0 rounded-full bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-800 transition hover:bg-brand-100"
                                >
                                    Details →
                                </Link>
                            </div>

                            <ul className="mt-3 space-y-1.5 text-xs sm:text-sm">
                                {school.bell_schedule.map((slot, idx) => {
                                    const isBreak = slot.type === 'break' || Boolean(slot.is_break);
                                    const isBetreuung = slot.type === 'betreuung';

                                    const rowClasses = isBreak
                                        ? 'bg-amber-50/75 text-amber-950 ring-1 ring-amber-200/70'
                                        : isBetreuung
                                          ? 'bg-slate-50/80 text-slate-700'
                                          : 'text-slate-700 hover:bg-brand-50/60 hover:text-brand-900';

                                    const dotClasses = isBreak
                                        ? 'bg-amber-500'
                                        : isBetreuung
                                          ? 'bg-slate-400'
                                          : 'bg-brand-500';

                                    const timeClasses = isBreak
                                        ? 'bg-amber-100/80 text-amber-950'
                                        : isBetreuung
                                          ? 'bg-slate-200/70 text-slate-700'
                                          : 'bg-slate-100 text-slate-700';

                                    return (
                                        <li
                                            key={idx}
                                            className={`flex items-center justify-between gap-2.5 rounded-xl px-3 py-2 transition ${rowClasses}`}
                                        >
                                            <span className="flex items-center gap-2.5 min-w-0">
                                                <span
                                                    className={`h-2 w-2 shrink-0 rounded-full ${dotClasses}`}
                                                    aria-hidden="true"
                                                />
                                                <span className="font-semibold truncate">{slot.label}</span>
                                            </span>
                                            <span
                                                className={`shrink-0 rounded-lg px-2 py-0.5 font-mono text-[11px] sm:text-xs font-bold tabular-nums ${timeClasses}`}
                                            >
                                                {slot.time}
                                            </span>
                                        </li>
                                    );
                                })}
                            </ul>
                        </Reveal>
                    )}

                    {/* 3. Original-Widget „Beiträge nach Rubriken“ */}
                    <Reveal variant="fade-up" delay={150}>
                        <RubrikenWidget rubriken={rubriken} />
                    </Reveal>

                    {/* 4. Kontakt- & Sekretariats-Widget im modernen Kontakt-Kartendesign */}
                    <Reveal variant="fade-up" delay={180} className="rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-xs">
                        <div className="flex items-center gap-2.5">
                            <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <div>
                                <span className="block text-[11px] font-extrabold uppercase tracking-wider text-brand-700">
                                    Schulbüro & Krankmeldung
                                </span>
                                <h3 className="text-base sm:text-lg font-extrabold text-slate-900 leading-tight">
                                    Sekretariat
                                </h3>
                            </div>
                        </div>

                        <p className="mt-2.5 text-xs leading-relaxed text-slate-600">
                            Bitte melden Sie Ihr Kind im Krankheitsfall morgens vor Unterrichtsbeginn telefonisch oder
                            per E-Mail im Sekretariat ab.
                        </p>

                        <div className="mt-4 space-y-2.5 text-sm">
                            <a
                                href={phoneHref}
                                className="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-3 transition hover:border-brand-200 hover:bg-brand-50/30"
                            >
                                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                    <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                </span>
                                <div className="min-w-0">
                                    <span className="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                        Telefon (Direktanruf)
                                    </span>
                                    <span className="font-bold text-slate-900">{school.phone}</span>
                                </div>
                            </a>

                            <a
                                href={`mailto:${school.email}`}
                                className="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-3 transition hover:border-brand-200 hover:bg-brand-50/30"
                            >
                                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                    <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </span>
                                <div className="min-w-0">
                                    <span className="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                        E-Mail
                                    </span>
                                    <span className="block truncate font-bold text-slate-900">{school.email}</span>
                                </div>
                            </a>

                            <div className="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-3">
                                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                    <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                    </svg>
                                </span>
                                <div className="min-w-0">
                                    <span className="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                        Öffnungszeiten
                                    </span>
                                    <span className="font-semibold text-slate-800">{school.office_hours}</span>
                                </div>
                            </div>
                        </div>
                    </Reveal>
                </aside>
            </div>

            {/* Fotogalerie-Vorschau */}
            {albums.length > 0 && (
                <section className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 pb-8">
                    <Reveal variant="fade-up" className="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-8 shadow-xs">
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
                            {albums.map((album, index) => (
                                <Reveal key={album.id} variant="zoom-in" delay={Math.min(index, 3) * 70}>
                                    <Link
                                        href={`/galerie/${album.slug}`}
                                        className="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-surface transition hover:-translate-y-1 hover:border-brand-300 hover:shadow-md"
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
                                            <span className="absolute bottom-2 right-2 inline-flex items-center gap-1.5 rounded-full bg-slate-950/75 px-2.5 py-0.5 text-[11px] font-bold text-white backdrop-blur-xs">
                                                <svg className="h-3.5 w-3.5 text-brand-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                                </svg>
                                                <span>{album.photosCount} Fotos</span>
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
                                </Reveal>
                            ))}
                        </div>
                    </Reveal>
                </section>
            )}
        </SiteLayout>
    );
}
