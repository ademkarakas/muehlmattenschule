import { ScrollToTop } from '@/components/scroll-to-top';
import { SearchIcon } from '@/components/search-icon';
import { SeoHead } from '@/components/seo-head';
import type { NavigationPageLink, SharedInertiaProps } from '@/types/school';
import { Link, router, usePage } from '@inertiajs/react';
import { useEffect, useState, type FormEvent, type ReactElement, type ReactNode } from 'react';

export interface SiteLayoutProps {
    children: ReactNode;
}

type FontScale = 'normal' | 'lg' | 'xl';
type MobileSection = 'unsere-schule' | 'konzepte-projekte' | 'angebote' | null;

/**
 * Gruppiert Seitenlinks nach ihrer optionalen menuGroup für mehrspaltige oder strukturierte Dropdowns.
 */
function groupNavigationLinks(items: NavigationPageLink[]): Array<{ group: string | null; links: NavigationPageLink[] }> {
    const map = new Map<string, NavigationPageLink[]>();
    for (const item of items) {
        const key = item.menuGroup ?? '';
        const list = map.get(key) ?? [];
        list.push(item);
        map.set(key, list);
    }

    return Array.from(map.entries()).map(([key, links]) => ({
        group: key !== '' ? key : null,
        links,
    }));
}

/**
 * EduPage-inspiriertes Seitenlayout der Mühlmattenschule im originalen Logo-Türkis (#029786)
 * mit Barrierefreiheits-Schriftgrößenumschalter (A- / A / A+), Suche (/suche),
 * vollständigem Hauptmenü (Unsere Schule, Konzepte & Projekte, Angebote & Betreuung),
 * barrierefreier Mobilnavigation und Fußbereich.
 */
export function SiteLayout({ children }: SiteLayoutProps): ReactElement {
    const page = usePage<SharedInertiaProps>();
    const { school, navigationPages, maintenancePreview } = page.props;
    const currentUrl = page.url;
    const [mobileOpen, setMobileOpen] = useState(false);
    const [fontScale, setFontScale] = useState<FontScale>('normal');
    const [headerSearchQuery, setHeaderSearchQuery] = useState('');
    const [openMobileSection, setOpenMobileSection] = useState<MobileSection>(() => {
        if (currentUrl.startsWith('/unsere-schule')) {
            return 'unsere-schule';
        }
        if (currentUrl.startsWith('/konzepte-projekte')) {
            return 'konzepte-projekte';
        }
        if (currentUrl.startsWith('/angebote') || currentUrl.startsWith('/schulleben')) {
            return 'angebote';
        }
        return null;
    });

    const konzepteLinks =
        navigationPages.konzepteProjekte && navigationPages.konzepteProjekte.length > 0
            ? navigationPages.konzepteProjekte
            : navigationPages.schulleben.filter((p) => p.section === 'konzepte-projekte');

    const angeboteLinks =
        navigationPages.angebote && navigationPages.angebote.length > 0
            ? navigationPages.angebote
            : navigationPages.schulleben.filter((p) => p.section !== 'konzepte-projekte');

    const unsereSchuleGroups = groupNavigationLinks(navigationPages.unsereSchule);
    const konzepteGroups = groupNavigationLinks(konzepteLinks);
    const angeboteGroups = groupNavigationLinks(angeboteLinks);

    const isActive = (prefix: string): boolean => {
        if (prefix === '/') {
            return currentUrl === '/';
        }
        return currentUrl.startsWith(prefix);
    };

    const phoneHref = `tel:${school.phone.replace(/\s+/g, '')}`;

    useEffect(() => {
        try {
            const saved = window.localStorage.getItem('mms_font_scale') as FontScale | null;
            if (saved === 'lg' || saved === 'xl' || saved === 'normal') {
                setFontScale(saved);
                if (saved === 'normal') {
                    delete document.documentElement.dataset.fontScale;
                } else {
                    document.documentElement.dataset.fontScale = saved;
                }
            }
        } catch {
            // LocalStorage nicht verfügbar
        }
    }, []);

    const updateFontScale = (scale: FontScale): void => {
        setFontScale(scale);
        if (scale === 'normal') {
            delete document.documentElement.dataset.fontScale;
        } else {
            document.documentElement.dataset.fontScale = scale;
        }
        try {
            window.localStorage.setItem('mms_font_scale', scale);
        } catch {
            // Ignorieren
        }
    };

    useEffect(() => {
        setMobileOpen(false);
    }, [currentUrl]);

    useEffect(() => {
        if (!mobileOpen) {
            return;
        }

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        const handleKeyDown = (event: KeyboardEvent): void => {
            if (event.key === 'Escape') {
                event.preventDefault();
                setMobileOpen(false);
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => {
            document.body.style.overflow = previousOverflow;
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [mobileOpen]);

    const toggleMobileSection = (section: MobileSection): void => {
        setOpenMobileSection((prev) => (prev === section ? null : section));
    };

    const resolvePageHref = (item: NavigationPageLink, fallbackSection: string): string => {
        const sec = item.section ?? fallbackSection;
        return `/${sec}/${item.slug}`;
    };

    const handleHeaderSearch = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();
        const trimmed = headerSearchQuery.trim();
        router.get('/suche', trimmed !== '' ? { q: trimmed } : {});
    };

    return (
        <div className="min-h-screen min-h-dvh flex flex-col bg-surface text-ink">
            <SeoHead />

            <a
                href="#hauptinhalt"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2.5 focus:text-sm focus:font-bold focus:text-brand-900 focus:shadow-lg"
            >
                Zum Hauptinhalt springen
            </a>

            {maintenancePreview && (
                <div
                    role="status"
                    className="bg-amber-400 text-slate-950 border-b border-amber-500 px-4 py-2.5 text-xs sm:text-sm"
                >
                    <div className="mx-auto max-w-[1440px] flex flex-wrap items-center justify-between gap-2">
                        <div className="flex items-center gap-2 font-semibold">
                            <span
                                aria-hidden="true"
                                className="inline-block h-2.5 w-2.5 shrink-0 rounded-full bg-red-600 animate-pulse"
                            />
                            <span>
                                <strong>Wartungsmodus ist AKTIV:</strong> Besucher sehen derzeit die Wartungsseite
                                (HTTP 503). Sie sehen als angemeldete Lehrkraft / Verwaltung die Vorschau.
                            </span>
                        </div>
                        <div className="flex flex-wrap items-center gap-2">
                            <a
                                href="/?wartungsmodus-vorschau=1"
                                className="inline-flex min-h-8 items-center rounded-lg bg-white/95 px-3 py-1 text-xs font-bold text-slate-900 shadow-2xs hover:bg-white"
                            >
                                Wartungsseite prüfen
                            </a>
                            <a
                                href="/admin/manage-school-settings"
                                className="inline-flex min-h-8 items-center rounded-lg bg-slate-900 px-3 py-1 text-xs font-bold text-white shadow-2xs hover:bg-slate-800"
                            >
                                Im Admin-Panel verwalten
                            </a>
                        </div>
                    </div>
                </div>
            )}

            {/* Oberer Kopfbereich im EduPage-Portalstil */}
            <header className="sticky top-0 z-40 shadow-sm">
                <div className="bg-white border-b border-slate-200/80">
                    <div className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 py-2.5 sm:py-3.5 flex items-center justify-between gap-3 sm:gap-6">
                        <Link href="/" className="flex items-center gap-3 sm:gap-4 group min-w-0">
                            <img
                                src="/images/logo.png"
                                alt={`Logo ${school.name}`}
                                width={52}
                                height={52}
                                className="h-11 w-11 sm:h-13 sm:w-13 shrink-0 rounded-2xl shadow-xs transition group-hover:scale-105 object-contain"
                            />
                            <div className="min-w-0">
                                <span className="block truncate text-lg sm:text-2xl md:text-[26px] font-extrabold tracking-tight text-brand-900 leading-tight">
                                    {school.name}
                                </span>
                                <span className="block truncate text-xs sm:text-sm font-medium text-brand-700">
                                    {school.subtitle}
                                </span>
                            </div>
                        </Link>

                        <div className="flex items-center gap-2 lg:gap-3 text-xs sm:text-sm shrink-0">
                            {/* Barrierefreie Schriftgrößen-Auswahl (wie auf ghsfreiburg.edupage.org) */}
                            <div
                                role="group"
                                aria-label="Schriftgröße anpassen"
                                className="hidden lg:flex items-center rounded-xl border border-brand-200 bg-brand-50/70 p-1"
                            >
                                <button
                                    type="button"
                                    onClick={() => updateFontScale('normal')}
                                    aria-pressed={fontScale === 'normal'}
                                    title="Normale Schriftgröße"
                                    className={`rounded-lg px-2.5 py-1.5 text-xs font-extrabold cursor-pointer transition ${
                                        fontScale === 'normal'
                                            ? 'bg-brand-600 text-white shadow-2xs'
                                            : 'text-brand-800 hover:bg-brand-100'
                                    }`}
                                >
                                    A
                                </button>
                                <button
                                    type="button"
                                    onClick={() => updateFontScale('lg')}
                                    aria-pressed={fontScale === 'lg'}
                                    title="Große Schriftgröße"
                                    className={`rounded-lg px-2.5 py-1.5 text-xs font-extrabold cursor-pointer transition ${
                                        fontScale === 'lg'
                                            ? 'bg-brand-600 text-white shadow-2xs'
                                            : 'text-brand-800 hover:bg-brand-100'
                                    }`}
                                >
                                    A+
                                </button>
                                <button
                                    type="button"
                                    onClick={() => updateFontScale('xl')}
                                    aria-pressed={fontScale === 'xl'}
                                    title="Sehr große Schriftgröße"
                                    className={`rounded-lg px-2.5 py-1.5 text-xs font-extrabold cursor-pointer transition ${
                                        fontScale === 'xl'
                                            ? 'bg-brand-600 text-white shadow-2xs'
                                            : 'text-brand-800 hover:bg-brand-100'
                                    }`}
                                >
                                    A++
                                </button>
                            </div>

                            <form
                                onSubmit={handleHeaderSearch}
                                role="search"
                                aria-label="Schulportal durchsuchen"
                                className="relative hidden md:flex w-56 lg:w-64 xl:w-80 2xl:w-[26rem] items-center"
                            >
                                <label htmlFor="header-search-input" className="sr-only">
                                    Schulportal durchsuchen
                                </label>
                                <input
                                    id="header-search-input"
                                    type="search"
                                    value={headerSearchQuery}
                                    onChange={(e) => setHeaderSearchQuery(e.target.value)}
                                    placeholder="Schulportal durchsuchen…"
                                    className="h-11 w-full rounded-xl border border-brand-200 bg-brand-50/70 pl-4 pr-11 text-sm font-medium text-brand-950 placeholder:text-brand-700/60 transition focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20"
                                />
                                <button
                                    type="submit"
                                    aria-label="Suche starten"
                                    title="Suchen"
                                    className="absolute right-1.5 flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-brand-800/60 hover:text-brand-900 cursor-pointer transition"
                                >
                                    <SearchIcon className="h-4.5 w-4.5" />
                                </button>
                            </form>

                            <Link
                                href="/suche"
                                aria-label="Suche öffnen"
                                className="flex md:hidden h-11 w-11 items-center justify-center rounded-xl bg-transparent text-brand-800/65 hover:text-brand-900 active:bg-brand-50/50 transition"
                            >
                                <SearchIcon className="h-5 w-5" />
                            </Link>

                            <a
                                href="/admin"
                                rel="nofollow"
                                className="hidden lg:flex min-h-11 shrink-0 items-center rounded-xl bg-gradient-to-r from-brand-600 to-brand-800 px-4 py-2.5 text-sm font-bold text-white shadow-xs hover:from-brand-700 hover:to-brand-900"
                            >
                                Anmelden / Verwaltung
                            </a>

                            <button
                                type="button"
                                onClick={() => setMobileOpen((prev) => !prev)}
                                aria-expanded={mobileOpen}
                                aria-controls="mobile-navigation-panel"
                                aria-label={mobileOpen ? 'Hauptmenü schließen' : 'Hauptmenü öffnen'}
                                className="inline-flex lg:hidden min-h-11 items-center gap-2 rounded-xl border border-brand-200 bg-brand-50 px-3.5 py-2 text-sm font-bold text-brand-800 active:bg-brand-100 cursor-pointer"
                            >
                                <span>{mobileOpen ? 'Schließen' : 'Menü'}</span>
                                <span aria-hidden="true" className="text-base leading-none">
                                    {mobileOpen ? '✕' : '☰'}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Türkise, breite EduPage-Menüleiste im Original-Logo-Farbton (#029786) */}
                <nav
                    aria-label="Hauptnavigation"
                    className="bg-gradient-to-r from-brand-700 via-brand-600 to-brand-800 text-white"
                >
                    <div className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10">
                        <ul className="hidden lg:flex items-center justify-between text-[14.5px] xl:text-[15px] font-bold tracking-wide">
                            <li>
                                <Link
                                    href="/"
                                    aria-current={isActive('/') ? 'page' : undefined}
                                    className={`block px-4 py-3.5 transition ${
                                        isActive('/') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Startseite
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/aktuelles"
                                    aria-current={isActive('/aktuelles') ? 'page' : undefined}
                                    className={`block px-4 py-3.5 transition ${
                                        isActive('/aktuelles') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Aktuelles
                                </Link>
                            </li>

                            {navigationPages.unsereSchule.length > 0 && (
                                <li className="relative group">
                                    <button
                                        type="button"
                                        aria-haspopup="true"
                                        className={`flex items-center gap-1.5 px-4 py-3.5 cursor-pointer transition ${
                                            isActive('/unsere-schule')
                                                ? 'bg-white/20 shadow-inner'
                                                : 'group-hover:bg-white/15 group-focus-within:bg-white/15'
                                        }`}
                                    >
                                        Unsere Schule <span aria-hidden="true" className="text-xs">▾</span>
                                    </button>
                                    <div
                                        className={`invisible opacity-0 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 transition-all duration-150 absolute left-0 top-full z-40 rounded-b-2xl bg-white p-3 text-slate-800 shadow-xl border border-slate-200 ${
                                            unsereSchuleGroups.length > 1 ? 'w-[34rem] grid grid-cols-2 gap-3' : 'w-72'
                                        }`}
                                    >
                                        {unsereSchuleGroups.map((groupEntry, idx) => (
                                            <div key={groupEntry.group ?? idx}>
                                                {groupEntry.group && (
                                                    <p className="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wider text-brand-700 border-b border-brand-100 mb-1">
                                                        {groupEntry.group}
                                                    </p>
                                                )}
                                                <ul className="space-y-0.5">
                                                    {groupEntry.links.map((item) => {
                                                        const itemHref = resolvePageHref(item, 'unsere-schule');
                                                        const itemActive = currentUrl === itemHref;
                                                        return (
                                                            <li key={item.slug}>
                                                                <Link
                                                                    href={itemHref}
                                                                    aria-current={itemActive ? 'page' : undefined}
                                                                    className={`block rounded-xl px-3 py-2 text-sm font-semibold transition ${
                                                                        itemActive
                                                                            ? 'bg-brand-50 text-brand-800 font-bold'
                                                                            : 'hover:bg-brand-50 hover:text-brand-700'
                                                                    }`}
                                                                >
                                                                    {item.title}
                                                                </Link>
                                                            </li>
                                                        );
                                                    })}
                                                </ul>
                                            </div>
                                        ))}
                                    </div>
                                </li>
                            )}

                            {konzepteLinks.length > 0 && (
                                <li className="relative group">
                                    <button
                                        type="button"
                                        aria-haspopup="true"
                                        className={`flex items-center gap-1.5 px-4 py-3.5 cursor-pointer transition ${
                                            isActive('/konzepte-projekte')
                                                ? 'bg-white/20 shadow-inner'
                                                : 'group-hover:bg-white/15 group-focus-within:bg-white/15'
                                        }`}
                                    >
                                        Konzepte & Projekte <span aria-hidden="true" className="text-xs">▾</span>
                                    </button>
                                    <div className="invisible opacity-0 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 transition-all duration-150 absolute left-0 top-full z-40 w-76 rounded-b-2xl bg-white p-3 text-slate-800 shadow-xl border border-slate-200 space-y-2">
                                        {konzepteGroups.map((groupEntry, idx) => (
                                            <div key={groupEntry.group ?? idx}>
                                                {groupEntry.group && (
                                                    <p className="px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-brand-700 border-b border-brand-100 mb-1">
                                                        {groupEntry.group}
                                                    </p>
                                                )}
                                                <ul className="space-y-0.5">
                                                    {groupEntry.links.map((item) => {
                                                        const itemHref = resolvePageHref(item, 'konzepte-projekte');
                                                        const itemActive = currentUrl === itemHref;
                                                        return (
                                                            <li key={item.slug}>
                                                                <Link
                                                                    href={itemHref}
                                                                    aria-current={itemActive ? 'page' : undefined}
                                                                    className={`block rounded-xl px-3 py-2 text-sm font-semibold transition ${
                                                                        itemActive
                                                                            ? 'bg-brand-50 text-brand-800 font-bold'
                                                                            : 'hover:bg-brand-50 hover:text-brand-700'
                                                                    }`}
                                                                >
                                                                    {item.title}
                                                                </Link>
                                                            </li>
                                                        );
                                                    })}
                                                </ul>
                                            </div>
                                        ))}
                                    </div>
                                </li>
                            )}

                            {angeboteLinks.length > 0 && (
                                <li className="relative group">
                                    <button
                                        type="button"
                                        aria-haspopup="true"
                                        className={`flex items-center gap-1.5 px-4 py-3.5 cursor-pointer transition ${
                                            isActive('/angebote') || isActive('/schulleben')
                                                ? 'bg-white/20 shadow-inner'
                                                : 'group-hover:bg-white/15 group-focus-within:bg-white/15'
                                        }`}
                                    >
                                        Angebote & Betreuung <span aria-hidden="true" className="text-xs">▾</span>
                                    </button>
                                    <div className="invisible opacity-0 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 transition-all duration-150 absolute left-0 top-full z-40 w-80 rounded-b-2xl bg-white p-3 text-slate-800 shadow-xl border border-slate-200 space-y-2">
                                        {angeboteGroups.map((groupEntry, idx) => (
                                            <div key={groupEntry.group ?? idx}>
                                                {groupEntry.group && (
                                                    <p className="px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-brand-700 border-b border-brand-100 mb-1">
                                                        {groupEntry.group}
                                                    </p>
                                                )}
                                                <ul className="space-y-0.5">
                                                    {groupEntry.links.map((item) => {
                                                        const itemHref = resolvePageHref(item, 'angebote');
                                                        const itemActive = currentUrl === itemHref;
                                                        return (
                                                            <li key={item.slug}>
                                                                <Link
                                                                    href={itemHref}
                                                                    aria-current={itemActive ? 'page' : undefined}
                                                                    className={`block rounded-xl px-3 py-2 text-sm font-semibold transition ${
                                                                        itemActive
                                                                            ? 'bg-brand-50 text-brand-800 font-bold'
                                                                            : 'hover:bg-brand-50 hover:text-brand-700'
                                                                    }`}
                                                                >
                                                                    {item.title}
                                                                </Link>
                                                            </li>
                                                        );
                                                    })}
                                                </ul>
                                            </div>
                                        ))}
                                    </div>
                                </li>
                            )}

                            <li>
                                <Link
                                    href="/termine"
                                    aria-current={isActive('/termine') ? 'page' : undefined}
                                    className={`block px-4 py-3.5 transition ${
                                        isActive('/termine') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Termine
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/galerie"
                                    aria-current={isActive('/galerie') ? 'page' : undefined}
                                    className={`block px-4 py-3.5 transition ${
                                        isActive('/galerie') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Fotogalerie
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/downloads"
                                    aria-current={isActive('/downloads') ? 'page' : undefined}
                                    className={`block px-4 py-3.5 transition ${
                                        isActive('/downloads') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Downloads
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/kontakt"
                                    aria-current={isActive('/kontakt') ? 'page' : undefined}
                                    className={`block px-4 py-3.5 transition ${
                                        isActive('/kontakt') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Kontakt
                                </Link>
                            </li>
                        </ul>

                        {/* Mobiles Menü mit Scroll-Container, Akkordeon-Gruppen & Schnellkontakt */}
                        {mobileOpen && (
                            <div
                                id="mobile-navigation-panel"
                                className="lg:hidden max-h-[calc(100dvh-4.25rem)] overflow-y-auto overscroll-contain py-3 space-y-1.5 text-sm font-semibold"
                            >
                                <div className="flex items-center justify-between rounded-xl bg-white/10 px-3 py-2 mb-2">
                                    <span className="text-xs font-bold text-brand-100">Schriftgröße:</span>
                                    <div className="flex items-center gap-1">
                                        {(['normal', 'lg', 'xl'] as FontScale[]).map((scale) => (
                                            <button
                                                key={scale}
                                                type="button"
                                                onClick={() => updateFontScale(scale)}
                                                className={`rounded-lg px-2.5 py-1 text-xs font-extrabold ${
                                                    fontScale === scale ? 'bg-white text-brand-900' : 'text-white'
                                                }`}
                                            >
                                                {scale === 'normal' ? 'A' : scale === 'lg' ? 'A+' : 'A++'}
                                            </button>
                                        ))}
                                    </div>
                                </div>

                                <Link
                                    href="/"
                                    aria-current={isActive('/') ? 'page' : undefined}
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive('/') ? 'bg-white/20 font-extrabold' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Startseite
                                </Link>
                                <Link
                                    href="/aktuelles"
                                    aria-current={isActive('/aktuelles') ? 'page' : undefined}
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive('/aktuelles') ? 'bg-white/20 font-extrabold' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Aktuelles & Berichte
                                </Link>

                                {navigationPages.unsereSchule.length > 0 && (
                                    <div className="rounded-xl bg-white/8 p-1">
                                        <button
                                            type="button"
                                            onClick={() => toggleMobileSection('unsere-schule')}
                                            aria-expanded={openMobileSection === 'unsere-schule'}
                                            className="flex min-h-11 w-full items-center justify-between rounded-lg px-3 py-2 text-left font-bold text-white hover:bg-white/10 cursor-pointer"
                                        >
                                            <span>Unsere Schule</span>
                                            <span
                                                aria-hidden="true"
                                                className={`text-xs transition-transform ${
                                                    openMobileSection === 'unsere-schule' ? 'rotate-180' : ''
                                                }`}
                                            >
                                                ▾
                                            </span>
                                        </button>
                                        {openMobileSection === 'unsere-schule' && (
                                            <div className="mt-1 space-y-1 pb-1">
                                                {navigationPages.unsereSchule.map((item) => {
                                                    const itemHref = resolvePageHref(item, 'unsere-schule');
                                                    const itemActive = currentUrl === itemHref;
                                                    return (
                                                        <Link
                                                            key={item.slug}
                                                            href={itemHref}
                                                            aria-current={itemActive ? 'page' : undefined}
                                                            className={`flex min-h-10 items-center rounded-lg px-3 py-2 pl-5 text-brand-50 ${
                                                                itemActive
                                                                    ? 'bg-white/20 font-extrabold text-white'
                                                                    : 'hover:bg-white/15'
                                                            }`}
                                                        >
                                                            • {item.title}
                                                        </Link>
                                                    );
                                                })}
                                            </div>
                                        )}
                                    </div>
                                )}

                                {konzepteLinks.length > 0 && (
                                    <div className="rounded-xl bg-white/8 p-1">
                                        <button
                                            type="button"
                                            onClick={() => toggleMobileSection('konzepte-projekte')}
                                            aria-expanded={openMobileSection === 'konzepte-projekte'}
                                            className="flex min-h-11 w-full items-center justify-between rounded-lg px-3 py-2 text-left font-bold text-white hover:bg-white/10 cursor-pointer"
                                        >
                                            <span>Konzepte & Projekte</span>
                                            <span
                                                aria-hidden="true"
                                                className={`text-xs transition-transform ${
                                                    openMobileSection === 'konzepte-projekte' ? 'rotate-180' : ''
                                                }`}
                                            >
                                                ▾
                                            </span>
                                        </button>
                                        {openMobileSection === 'konzepte-projekte' && (
                                            <div className="mt-1 space-y-1 pb-1">
                                                {konzepteLinks.map((item) => {
                                                    const itemHref = resolvePageHref(item, 'konzepte-projekte');
                                                    const itemActive = currentUrl === itemHref;
                                                    return (
                                                        <Link
                                                            key={item.slug}
                                                            href={itemHref}
                                                            aria-current={itemActive ? 'page' : undefined}
                                                            className={`flex min-h-10 items-center rounded-lg px-3 py-2 pl-5 text-brand-50 ${
                                                                itemActive
                                                                    ? 'bg-white/20 font-extrabold text-white'
                                                                    : 'hover:bg-white/15'
                                                            }`}
                                                        >
                                                            • {item.title}
                                                        </Link>
                                                    );
                                                })}
                                            </div>
                                        )}
                                    </div>
                                )}

                                {angeboteLinks.length > 0 && (
                                    <div className="rounded-xl bg-white/8 p-1">
                                        <button
                                            type="button"
                                            onClick={() => toggleMobileSection('angebote')}
                                            aria-expanded={openMobileSection === 'angebote'}
                                            className="flex min-h-11 w-full items-center justify-between rounded-lg px-3 py-2 text-left font-bold text-white hover:bg-white/10 cursor-pointer"
                                        >
                                            <span>Angebote & Betreuung</span>
                                            <span
                                                aria-hidden="true"
                                                className={`text-xs transition-transform ${
                                                    openMobileSection === 'angebote' ? 'rotate-180' : ''
                                                }`}
                                            >
                                                ▾
                                            </span>
                                        </button>
                                        {openMobileSection === 'angebote' && (
                                            <div className="mt-1 space-y-1 pb-1">
                                                {angeboteLinks.map((item) => {
                                                    const itemHref = resolvePageHref(item, 'angebote');
                                                    const itemActive = currentUrl === itemHref;
                                                    return (
                                                        <Link
                                                            key={item.slug}
                                                            href={itemHref}
                                                            aria-current={itemActive ? 'page' : undefined}
                                                            className={`flex min-h-10 items-center rounded-lg px-3 py-2 pl-5 text-brand-50 ${
                                                                itemActive
                                                                    ? 'bg-white/20 font-extrabold text-white'
                                                                    : 'hover:bg-white/15'
                                                            }`}
                                                        >
                                                            • {item.title}
                                                        </Link>
                                                    );
                                                })}
                                            </div>
                                        )}
                                    </div>
                                )}

                                <Link
                                    href="/termine"
                                    aria-current={isActive('/termine') ? 'page' : undefined}
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive('/termine') ? 'bg-white/20 font-extrabold' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Termine & Kalender
                                </Link>
                                <Link
                                    href="/galerie"
                                    aria-current={isActive('/galerie') ? 'page' : undefined}
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive('/galerie') ? 'bg-white/20 font-extrabold' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Fotogalerie
                                </Link>
                                <Link
                                    href="/downloads"
                                    aria-current={isActive('/downloads') ? 'page' : undefined}
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive('/downloads') ? 'bg-white/20 font-extrabold' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Elternbriefe & Formulare
                                </Link>
                                <Link
                                    href="/kontakt"
                                    aria-current={isActive('/kontakt') ? 'page' : undefined}
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive('/kontakt') ? 'bg-white/20 font-extrabold' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Kontakt & Krankmeldung
                                </Link>

                                <div className="mt-3 pt-3 border-t border-white/15 grid grid-cols-2 gap-2">
                                    <a
                                        href={phoneHref}
                                        className="flex min-h-11 items-center justify-center gap-1.5 rounded-xl bg-white/15 px-3 py-2.5 text-xs font-bold text-white active:bg-white/25"
                                    >
                                        <span aria-hidden="true">☎</span>
                                        <span>Anrufen</span>
                                    </a>
                                    <a
                                        href={`mailto:${school.email}`}
                                        className="flex min-h-11 items-center justify-center gap-1.5 rounded-xl bg-white/15 px-3 py-2.5 text-xs font-bold text-white active:bg-white/25"
                                    >
                                        <span aria-hidden="true">✉</span>
                                        <span>E-Mail</span>
                                    </a>
                                </div>

                                <a
                                    href="/admin"
                                    rel="nofollow"
                                    className="mt-2 flex min-h-11 items-center justify-center rounded-xl bg-white px-3 py-2.5 text-center font-bold text-brand-800 shadow-xs"
                                >
                                    Anmelden / Verwaltung
                                </a>
                            </div>
                        )}
                    </div>
                </nav>
            </header>

            {/* Abdunkelung des Hintergrunds bei geöffnetem Mobilmenü */}
            {mobileOpen && (
                <div
                    aria-hidden="true"
                    onClick={() => setMobileOpen(false)}
                    className="fixed inset-0 z-30 bg-slate-950/45 backdrop-blur-[1px] lg:hidden"
                />
            )}

            {/* Seiteninhalt */}
            <main id="hauptinhalt" className="flex-1">
                {children}
            </main>

            {/* Fußbereich */}
            <footer className="mt-12 sm:mt-16 bg-brand-900 text-brand-100">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 py-10 sm:py-12 grid gap-8 md:grid-cols-4 text-sm">
                    <div>
                        <div className="flex items-center gap-3">
                            <img
                                src="/images/logo.png"
                                alt={`Logo ${school.name}`}
                                width={42}
                                height={42}
                                className="h-10 w-10 rounded-xl bg-white p-1 object-contain"
                            />
                            <p className="text-lg font-extrabold text-white">{school.name}</p>
                        </div>
                        <p className="mt-2 text-brand-200">{school.subtitle}</p>
                        <address className="mt-3 not-italic leading-relaxed">
                            {school.street}
                            <br />
                            {school.zip_city}
                        </address>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">Sekretariat & Erreichbarkeit</p>
                        <p className="mt-2">
                            Telefon:{' '}
                            <a href={phoneHref} className="font-semibold text-white underline-offset-2 hover:underline">
                                {school.phone}
                            </a>
                        </p>
                        <p className="mt-1 break-all">
                            E-Mail:{' '}
                            <a
                                href={`mailto:${school.email}`}
                                className="font-semibold text-white underline-offset-2 hover:underline"
                            >
                                {school.email}
                            </a>
                        </p>
                        <p className="mt-2 text-brand-200">Öffnungszeiten: {school.office_hours}</p>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">Unsere Schule & Konzepte</p>
                        <ul className="mt-2 space-y-1.5">
                            <li>
                                <Link href="/unsere-schule/schulportrait" className="inline-block py-0.5 hover:text-white">
                                    Schulportrait & Kollegium
                                </Link>
                            </li>
                            <li>
                                <Link href="/unsere-schule/schulsozialarbeit" className="inline-block py-0.5 hover:text-white">
                                    Schulsozialarbeit
                                </Link>
                            </li>
                            <li>
                                <Link href="/konzepte-projekte/leitbild" className="inline-block py-0.5 hover:text-white">
                                    Leitbild & Klassenrat
                                </Link>
                            </li>
                            <li>
                                <Link href="/angebote/schulkindbetreuung" className="inline-block py-0.5 hover:text-white">
                                    Schulkindbetreuung & AGs
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">Service & Rechtliches</p>
                        <ul className="mt-2 space-y-1.5">
                            <li>
                                <Link href="/aktuelles" className="inline-block py-0.5 hover:text-white">
                                    Aktuelles & Berichte
                                </Link>
                            </li>
                            <li>
                                <Link href="/termine" className="inline-block py-0.5 hover:text-white">
                                    Schulkalender (.ics Export)
                                </Link>
                            </li>
                            <li>
                                <Link href="/downloads" className="inline-block py-0.5 hover:text-white">
                                    Elternbriefe & Formulare
                                </Link>
                            </li>
                            <li>
                                <Link href="/suche" className="inline-block py-0.5 hover:text-white">
                                    Suche auf dem Portal
                                </Link>
                            </li>
                            <li className="pt-1">
                                <Link href="/impressum" className="inline-block py-0.5 hover:text-white">
                                    Impressum
                                </Link>{' '}
                                ·{' '}
                                <Link href="/datenschutz" className="inline-block py-0.5 hover:text-white">
                                    Datenschutzerklärung
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className="border-t border-white/10 py-4 px-4 text-center text-xs text-brand-200">
                    © {new Date().getFullYear()} {school.name} Freiburg-Hochdorf
                </div>
            </footer>

            {/* Füllt sich beim Herabscrollen & bringt den Nutzer zum Seitenanfang */}
            <ScrollToTop />
        </div>
    );
}
