import { AnnouncementModal } from "@/components/announcement-modal";
import { Reveal } from "@/components/reveal";
import { ScrollProgress } from "@/components/scroll-progress";
import { ScrollToTop } from "@/components/scroll-to-top";
import { SearchIcon } from "@/components/search-icon";
import { SeoHead } from "@/components/seo-head";
import type { NavigationPageLink, SharedInertiaProps } from "@/types/school";
import { Link, router, usePage } from "@inertiajs/react";
import {
    useEffect,
    useRef,
    useState,
    type FormEvent,
    type ReactElement,
    type ReactNode,
} from "react";

export interface SiteLayoutProps {
    children: ReactNode;
}

type FontScale = "normal" | "lg" | "xl";
type MobileSection = "unsere-schule" | "konzepte-projekte" | "angebote" | null;

/**
 * Gruppiert Seitenlinks nach ihrer optionalen menuGroup für mehrspaltige oder strukturierte Dropdowns.
 */
function groupNavigationLinks(
    items: NavigationPageLink[],
): Array<{ group: string | null; links: NavigationPageLink[] }> {
    const map = new Map<string, NavigationPageLink[]>();
    for (const item of items) {
        const key = item.menuGroup ?? "";
        const list = map.get(key) ?? [];
        list.push(item);
        map.set(key, list);
    }

    return Array.from(map.entries()).map(([key, links]) => ({
        group: key !== "" ? key : null,
        links,
    }));
}

interface DesktopNavDropdownProps {
    label: string;
    active: boolean;
    groups: Array<{ group: string | null; links: NavigationPageLink[] }>;
    fallbackSection: string;
    currentPath: string;
    wideOnMultiGroup?: boolean;
}

/**
 * Erzeugt die klaren Klassen für Hauptnavigations-Tabs ohne Hintergrundkasten –
 * aktiver und gehoverter Zustand werden ausschließlich durch die goldgelbe (amber-400) Unterstreichung markiert.
 */
function getDesktopNavTabClass(active: boolean, isOpen = false): string {
    const base =
        "relative inline-flex items-center gap-1.5 px-3 py-2 cursor-pointer transition-colors duration-200 after:content-[''] after:absolute after:bottom-0 after:left-2.5 after:right-2.5 after:h-[3px] after:rounded-full after:transition-all after:duration-200";

    if (active) {
        return `${base} text-white font-extrabold after:bg-amber-400 after:scale-x-100 after:opacity-100`;
    }

    if (isOpen) {
        return `${base} text-white font-semibold after:bg-amber-400 after:scale-x-100 after:opacity-100`;
    }

    return `${base} text-white/90 font-semibold hover:text-white after:bg-amber-400 after:scale-x-0 after:opacity-0 hover:after:scale-x-100 hover:after:opacity-100`;
}

/**
 * Barrierefreies Desktop-Dropdown-Menü für die Hauptnavigation mit Hover-, Klick- und
 * Tastatursteuerung (Escape / Outside-Click) sowie farblich hervorgehobenen Gruppenüberschriften.
 */
function DesktopNavDropdown({
    label,
    active,
    groups,
    fallbackSection,
    currentPath,
    wideOnMultiGroup = false,
}: DesktopNavDropdownProps): ReactElement {
    const [isOpen, setIsOpen] = useState(false);
    const containerRef = useRef<HTMLLIElement | null>(null);
    const triggerRef = useRef<HTMLButtonElement | null>(null);
    const closeTimerRef = useRef<number | null>(null);

    const clearCloseTimer = (): void => {
        if (closeTimerRef.current !== null) {
            window.clearTimeout(closeTimerRef.current);
            closeTimerRef.current = null;
        }
    };

    const openMenu = (): void => {
        clearCloseTimer();
        setIsOpen(true);
    };

    const scheduleCloseMenu = (): void => {
        clearCloseTimer();
        closeTimerRef.current = window.setTimeout(() => {
            setIsOpen(false);
        }, 120);
    };

    useEffect(() => {
        setIsOpen(false);
    }, [currentPath]);

    useEffect(() => {
        if (!isOpen) {
            return;
        }

        const handlePointerDown = (event: PointerEvent): void => {
            if (
                containerRef.current &&
                !containerRef.current.contains(event.target as Node)
            ) {
                setIsOpen(false);
            }
        };

        const handleKeyDown = (event: KeyboardEvent): void => {
            if (event.key === "Escape") {
                event.preventDefault();
                setIsOpen(false);
                triggerRef.current?.focus();
            }
        };

        document.addEventListener("pointerdown", handlePointerDown);
        document.addEventListener("keydown", handleKeyDown);
        return () => {
            document.removeEventListener("pointerdown", handlePointerDown);
            document.removeEventListener("keydown", handleKeyDown);
        };
    }, [isOpen]);

    useEffect(() => () => clearCloseTimer(), []);

    const panelWidthClass =
        wideOnMultiGroup && groups.length > 1
            ? "w-[36rem] grid grid-cols-2 gap-4"
            : groups.length > 1
              ? "w-80 space-y-3"
              : "w-72";

    return (
        <li
            ref={containerRef}
            className="relative"
            onMouseEnter={openMenu}
            onMouseLeave={scheduleCloseMenu}
        >
            <button
                ref={triggerRef}
                type="button"
                aria-haspopup="true"
                aria-expanded={isOpen}
                onClick={() => {
                    clearCloseTimer();
                    setIsOpen((prev) => !prev);
                }}
                className={getDesktopNavTabClass(active, isOpen)}
            >
                <span>{label}</span>
                <svg
                    aria-hidden="true"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    className={`h-4 w-4 shrink-0 opacity-85 transition-transform duration-200 ${isOpen ? "rotate-180 opacity-100" : ""}`}
                >
                    <path
                        fillRule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clipRule="evenodd"
                    />
                </svg>
            </button>
            <div
                className={`transition-all duration-150 origin-top-left absolute left-0 top-full pt-2 z-40 before:content-[''] before:absolute before:-top-2 before:inset-x-0 before:h-2 ${
                    isOpen
                        ? "visible opacity-100 translate-y-0"
                        : "invisible opacity-0 -translate-y-1 pointer-events-none"
                }`}
            >
                <div
                    className={`rounded-2xl border-t-3 border-amber-400 bg-white p-3.5 text-slate-800 shadow-2xl ring-1 ring-slate-900/10 ${panelWidthClass}`}
                >
                    {groups.map((groupEntry, idx) => (
                        <div key={groupEntry.group ?? idx}>
                            {groupEntry.group && (
                                <p className="mb-2 rounded-r-lg border-l-3 border-brand-500 bg-brand-50/90 px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wider text-brand-700">
                                    {groupEntry.group}
                                </p>
                            )}
                            <ul className="space-y-0.5">
                                {groupEntry.links.map((item) => {
                                    const sec = item.section ?? fallbackSection;
                                    const itemHref = `/${sec}/${item.slug}`;
                                    const itemActive = currentPath === itemHref;
                                    return (
                                        <li key={item.slug}>
                                            <Link
                                                href={itemHref}
                                                aria-current={
                                                    itemActive
                                                        ? "page"
                                                        : undefined
                                                }
                                                onClick={() => setIsOpen(false)}
                                                className={`flex items-center justify-between gap-2 rounded-xl px-3 py-2 text-sm transition-all duration-150 ${
                                                    itemActive
                                                        ? "bg-brand-50 text-brand-800 font-bold ring-1 ring-brand-500/20"
                                                        : "font-medium text-slate-700 hover:bg-slate-100 hover:text-brand-800 hover:translate-x-0.5"
                                                }`}
                                            >
                                                <span>{item.title}</span>
                                                {itemActive && (
                                                    <span
                                                        aria-hidden="true"
                                                        className="h-2 w-2 shrink-0 rounded-full bg-amber-500"
                                                    />
                                                )}
                                            </Link>
                                        </li>
                                    );
                                })}
                            </ul>
                        </div>
                    ))}
                </div>
            </div>
        </li>
    );
}

/**
 * EduPage-inspiriertes Seitenlayout der Mühlmattenschule im originalen Logo-Türkis (#029786)
 * mit Barrierefreiheits-Schriftgrößenumschalter (A- / A / A+), Suche (/suche),
 * vollständigem Hauptmenü (Unsere Schule, Konzepte & Projekte, Angebote & Betreuung),
 * barrierefreier Mobilnavigation und Fußbereich.
 */
export function SiteLayout({ children }: SiteLayoutProps): ReactElement {
    const page = usePage<SharedInertiaProps>();
    const { school, announcements, navigationPages, maintenancePreview } =
        page.props;
    const currentUrl = page.url;
    const currentPath = currentUrl.split("?")[0];
    const [mobileOpen, setMobileOpen] = useState(false);
    const [fontScale, setFontScale] = useState<FontScale>("normal");
    const [headerSearchQuery, setHeaderSearchQuery] = useState("");
    const [openMobileSection, setOpenMobileSection] = useState<MobileSection>(
        () => {
            if (currentUrl.startsWith("/unsere-schule")) {
                return "unsere-schule";
            }
            if (currentUrl.startsWith("/konzepte-projekte")) {
                return "konzepte-projekte";
            }
            if (
                currentUrl.startsWith("/angebote") ||
                currentUrl.startsWith("/schulleben")
            ) {
                return "angebote";
            }
            return null;
        },
    );

    const konzepteLinks =
        navigationPages.konzepteProjekte &&
        navigationPages.konzepteProjekte.length > 0
            ? navigationPages.konzepteProjekte
            : navigationPages.schulleben.filter(
                  (p) => p.section === "konzepte-projekte",
              );

    const angeboteLinks =
        navigationPages.angebote && navigationPages.angebote.length > 0
            ? navigationPages.angebote
            : navigationPages.schulleben.filter(
                  (p) => p.section !== "konzepte-projekte",
              );

    const unsereSchuleGroups = groupNavigationLinks(
        navigationPages.unsereSchule,
    );
    const konzepteGroups = groupNavigationLinks(konzepteLinks);
    const angeboteGroups = groupNavigationLinks(angeboteLinks);

    const isActive = (prefix: string): boolean => {
        if (prefix === "/") {
            return currentUrl === "/";
        }
        return currentUrl.startsWith(prefix);
    };

    useEffect(() => {
        try {
            const saved = window.localStorage.getItem(
                "mms_font_scale",
            ) as FontScale | null;
            if (saved === "lg" || saved === "xl" || saved === "normal") {
                setFontScale(saved);
                if (saved === "normal") {
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
        if (scale === "normal") {
            delete document.documentElement.dataset.fontScale;
        } else {
            document.documentElement.dataset.fontScale = scale;
        }
        try {
            window.localStorage.setItem("mms_font_scale", scale);
        } catch {
            // Ignorieren
        }
    };

    useEffect(() => {
        setMobileOpen(false);
    }, [currentUrl]);

    useEffect(() => {
        if (currentPath === "/suche") {
            const queryString = currentUrl.includes("?")
                ? currentUrl.split("?")[1]
                : "";
            const qParam = new URLSearchParams(queryString).get("q") ?? "";
            setHeaderSearchQuery(qParam);
        } else {
            setHeaderSearchQuery("");
        }
    }, [currentPath, currentUrl]);

    useEffect(() => {
        if (!mobileOpen) {
            return;
        }

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = "hidden";

        const handleKeyDown = (event: KeyboardEvent): void => {
            if (event.key === "Escape") {
                event.preventDefault();
                setMobileOpen(false);
            }
        };

        window.addEventListener("keydown", handleKeyDown);
        return () => {
            document.body.style.overflow = previousOverflow;
            window.removeEventListener("keydown", handleKeyDown);
        };
    }, [mobileOpen]);

    const toggleMobileSection = (section: MobileSection): void => {
        setOpenMobileSection((prev) => (prev === section ? null : section));
    };

    const resolvePageHref = (
        item: NavigationPageLink,
        fallbackSection: string,
    ): string => {
        const sec = item.section ?? fallbackSection;
        return `/${sec}/${item.slug}`;
    };

    const handleHeaderSearch = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();
        const trimmed = headerSearchQuery.trim();
        router.get("/suche", trimmed !== "" ? { q: trimmed } : {});
    };

    return (
        <div className="min-h-screen min-h-dvh flex flex-col bg-surface text-ink">
            <SeoHead />
            <ScrollProgress />

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
                                <strong>Wartungsmodus ist AKTIV:</strong>{" "}
                                Besucher sehen derzeit die Wartungsseite (HTTP
                                503). Sie sehen als angemeldete Lehrkraft /
                                Verwaltung die Vorschau.
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

            {/* Einheitlicher Kopfbereich (Markenzeile + Hauptnavigation) im originalen Logo-Türkis (#029786) ohne Farbbruch */}
            <header className="sticky top-0 z-40 bg-gradient-to-r from-brand-700 via-brand-500 to-brand-700 text-white shadow-[0_4px_20px_-4px_rgba(2,151,134,0.28)]">
                <div>
                    <div className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 py-2.5 sm:py-3.5 flex items-center justify-between gap-3 sm:gap-6">
                        <Link
                            href="/"
                            title={`Zur Startseite – ${school.name}`}
                            className="flex items-center gap-3 sm:gap-3.5 group min-w-0 rounded-2xl focus-visible:outline-amber-400"
                        >
                            <span className="relative shrink-0 before:content-[''] before:absolute before:-inset-1 before:rounded-2xl before:bg-amber-400/45 before:blur-sm before:opacity-0 before:scale-90 before:transition-all before:duration-300 group-hover:before:opacity-100 group-hover:before:scale-105">
                                <img
                                    src="/images/logo.png"
                                    alt={`Logo ${school.name}`}
                                    width={52}
                                    height={52}
                                    className="relative h-11 w-11 sm:h-12 sm:w-12 shrink-0 rounded-2xl bg-white p-1 ring-2 ring-white/40 shadow-md transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-0.5 group-hover:scale-110 group-hover:-rotate-3 group-hover:ring-amber-400 group-hover:shadow-[0_10px_24px_-4px_rgba(15,23,42,0.38)] object-contain"
                                />
                            </span>
                            <div className="min-w-0 transition-transform duration-300 ease-out group-hover:translate-x-1">
                                <span className="block truncate text-lg sm:text-2xl font-extrabold tracking-tight text-white leading-tight transition-colors duration-200 group-hover:text-amber-400">
                                    {school.name}
                                </span>
                                <span className="block truncate text-xs sm:text-[13px] font-medium text-brand-100 transition-colors duration-200 group-hover:text-white">
                                    {school.subtitle}
                                </span>
                            </div>
                        </Link>

                        <div className="flex items-center gap-2 lg:gap-2.5 text-xs sm:text-sm shrink-0">
                            {/* Barrierefreie Schriftgrößen-Auswahl – dunkler Slate-Kontrast mit warmem Goldgelb-Aktivstatus (amber-400) */}
                            <div
                                role="group"
                                aria-label="Schriftgröße anpassen"
                                className="hidden lg:flex items-center gap-0.5 rounded-full bg-slate-900/55 p-1 ring-1 ring-amber-400/55 shadow-inner backdrop-blur-xs"
                            >
                                <button
                                    type="button"
                                    onClick={() => updateFontScale("normal")}
                                    aria-pressed={fontScale === "normal"}
                                    title="Normale Schriftgröße"
                                    className={`rounded-full px-2.5 py-1 text-xs font-extrabold cursor-pointer transition-all duration-200 ${
                                        fontScale === "normal"
                                            ? "bg-amber-400 text-slate-950 shadow-xs"
                                            : "text-slate-100 hover:text-white hover:bg-white/15"
                                    }`}
                                >
                                    A
                                </button>
                                <button
                                    type="button"
                                    onClick={() => updateFontScale("lg")}
                                    aria-pressed={fontScale === "lg"}
                                    title="Große Schriftgröße"
                                    className={`rounded-full px-2.5 py-1 text-xs font-extrabold cursor-pointer transition-all duration-200 ${
                                        fontScale === "lg"
                                            ? "bg-amber-400 text-slate-950 shadow-xs"
                                            : "text-slate-100 hover:text-white hover:bg-white/15"
                                    }`}
                                >
                                    A+
                                </button>
                                <button
                                    type="button"
                                    onClick={() => updateFontScale("xl")}
                                    aria-pressed={fontScale === "xl"}
                                    title="Sehr große Schriftgröße"
                                    className={`rounded-full px-2.5 py-1 text-xs font-extrabold cursor-pointer transition-all duration-200 ${
                                        fontScale === "xl"
                                            ? "bg-amber-400 text-slate-950 shadow-xs"
                                            : "text-slate-100 hover:text-white hover:bg-white/15"
                                    }`}
                                >
                                    A++
                                </button>
                            </div>

                            {/* Kontraststarkes Suchfeld (weißes Eingabefeld mit goldgelbem amber-400 Rahmen & Such-Button) */}
                            <form
                                onSubmit={handleHeaderSearch}
                                role="search"
                                aria-label="Schulportal durchsuchen"
                                className="group/search relative hidden md:flex w-56 lg:w-68 xl:w-80 2xl:w-96 items-center"
                            >
                                <label
                                    htmlFor="header-search-input"
                                    className="sr-only"
                                >
                                    Schulportal durchsuchen
                                </label>
                                <input
                                    id="header-search-input"
                                    type="search"
                                    value={headerSearchQuery}
                                    onChange={(e) =>
                                        setHeaderSearchQuery(e.target.value)
                                    }
                                    placeholder="Schulportal durchsuchen…"
                                    className="h-10 w-full rounded-full border-2 border-amber-400 bg-white pl-4 pr-18 text-sm font-medium text-slate-800 placeholder:text-slate-400 shadow-[0_3px_12px_rgba(15,23,42,0.16)] transition-all duration-200 hover:border-amber-400 focus:border-amber-400 focus:bg-white focus:text-slate-950 focus:outline-none focus:ring-4 focus:ring-amber-400/35"
                                />
                                {headerSearchQuery !== "" && (
                                    <button
                                        type="button"
                                        onClick={() => setHeaderSearchQuery("")}
                                        aria-label="Suchbegriff löschen"
                                        title="Eingabe löschen"
                                        className="absolute right-10.5 flex h-6 w-6 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-700 cursor-pointer transition"
                                    >
                                        <svg
                                            className="h-3.5 w-3.5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="2.2"
                                            aria-hidden="true"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                )}
                                <button
                                    type="submit"
                                    aria-label="Suche starten"
                                    title="Suchen"
                                    className="absolute right-1.5 flex h-7.5 w-8 items-center justify-center rounded-full bg-amber-400 text-slate-950 shadow-2xs hover:bg-amber-500 active:scale-95 cursor-pointer transition-all duration-200"
                                >
                                    <SearchIcon className="h-4 w-4" />
                                </button>
                            </form>

                            <Link
                                href="/suche"
                                aria-label="Suche öffnen"
                                className="flex md:hidden h-11 w-11 items-center justify-center rounded-full bg-slate-900/55 text-amber-400 ring-1 ring-amber-400/60 hover:bg-amber-400 hover:text-slate-950 transition-all"
                            >
                                <SearchIcon className="h-5 w-5" />
                            </Link>

                            <a
                                href="/admin"
                                rel="nofollow"
                                className="group/login hidden lg:inline-flex h-10 shrink-0 items-center gap-2 rounded-full bg-slate-900/55 px-4 py-2 text-xs xl:text-sm font-bold text-white ring-1 ring-amber-400/65 shadow-sm backdrop-blur-xs transition-all duration-200 hover:-translate-y-0.5 hover:bg-amber-400 hover:text-slate-950 hover:ring-amber-400 hover:shadow-md"
                            >
                                <svg
                                    className="h-4 w-4 shrink-0 text-amber-400 transition-colors duration-200 group-hover/login:text-slate-950"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                    aria-hidden="true"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                    />
                                </svg>
                                <span>Anmelden / Verwaltung</span>
                            </a>

                            <button
                                type="button"
                                onClick={() => setMobileOpen((prev) => !prev)}
                                aria-expanded={mobileOpen}
                                aria-controls="mobile-navigation-panel"
                                aria-label={
                                    mobileOpen
                                        ? "Hauptmenü schließen"
                                        : "Hauptmenü öffnen"
                                }
                                className="inline-flex lg:hidden min-h-11 items-center gap-2 rounded-full border border-amber-400/65 bg-slate-900/55 px-3.5 py-2 text-sm font-bold text-white active:bg-slate-900/75 cursor-pointer transition-all"
                            >
                                <span>{mobileOpen ? "Schließen" : "Menü"}</span>
                                {mobileOpen ? (
                                    <svg
                                        className="h-4.5 w-4.5 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                ) : (
                                    <svg
                                        className="h-4.5 w-4.5 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                                        />
                                    </svg>
                                )}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Nahtlos in denselben Logo-Türkis-Kopfbereich integrierte Hauptnavigation (gleiche Farbe, feine Trennlinie) */}
                <nav
                    aria-label="Hauptnavigation"
                    className="border-t border-white/15"
                >
                    <div className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10">
                        <ul className="hidden lg:flex items-center justify-between gap-1 py-1.5 text-[14px] xl:text-[14.5px] tracking-wide">
                            <li>
                                <Link
                                    href="/"
                                    aria-current={
                                        isActive("/") ? "page" : undefined
                                    }
                                    className={getDesktopNavTabClass(
                                        isActive("/"),
                                    )}
                                >
                                    Startseite
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/aktuelles"
                                    aria-current={
                                        isActive("/aktuelles")
                                            ? "page"
                                            : undefined
                                    }
                                    className={getDesktopNavTabClass(
                                        isActive("/aktuelles"),
                                    )}
                                >
                                    Aktuelles
                                </Link>
                            </li>

                            {navigationPages.unsereSchule.length > 0 && (
                                <DesktopNavDropdown
                                    label="Unsere Schule"
                                    active={isActive("/unsere-schule")}
                                    groups={unsereSchuleGroups}
                                    fallbackSection="unsere-schule"
                                    currentPath={currentPath}
                                    wideOnMultiGroup
                                />
                            )}

                            {konzepteLinks.length > 0 && (
                                <DesktopNavDropdown
                                    label="Konzepte & Projekte"
                                    active={isActive("/konzepte-projekte")}
                                    groups={konzepteGroups}
                                    fallbackSection="konzepte-projekte"
                                    currentPath={currentPath}
                                />
                            )}

                            {angeboteLinks.length > 0 && (
                                <DesktopNavDropdown
                                    label="Angebote & Betreuung"
                                    active={
                                        isActive("/angebote") ||
                                        isActive("/schulleben")
                                    }
                                    groups={angeboteGroups}
                                    fallbackSection="angebote"
                                    currentPath={currentPath}
                                />
                            )}

                            <li>
                                <Link
                                    href="/termine"
                                    aria-current={
                                        isActive("/termine")
                                            ? "page"
                                            : undefined
                                    }
                                    className={getDesktopNavTabClass(
                                        isActive("/termine"),
                                    )}
                                >
                                    Termine
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/galerie"
                                    aria-current={
                                        isActive("/galerie")
                                            ? "page"
                                            : undefined
                                    }
                                    className={getDesktopNavTabClass(
                                        isActive("/galerie"),
                                    )}
                                >
                                    Fotogalerie
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/downloads"
                                    aria-current={
                                        isActive("/downloads")
                                            ? "page"
                                            : undefined
                                    }
                                    className={getDesktopNavTabClass(
                                        isActive("/downloads"),
                                    )}
                                >
                                    Downloads
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/kontakt"
                                    aria-current={
                                        isActive("/kontakt")
                                            ? "page"
                                            : undefined
                                    }
                                    className={getDesktopNavTabClass(
                                        isActive("/kontakt"),
                                    )}
                                >
                                    Kontakt
                                </Link>
                            </li>
                        </ul>

                        {/* Mobiles Menü mit Scroll-Container, Akkordeon-Gruppen & Schnellkontakt */}
                        {mobileOpen && (
                            <div
                                id="mobile-navigation-panel"
                                className="lg:hidden max-h-[calc(100dvh-4.25rem)] overflow-y-auto overscroll-contain py-3 space-y-1.5 text-sm font-semibold text-white"
                            >
                                <div className="flex items-center justify-between rounded-xl bg-white/10 px-3 py-2 mb-2">
                                    <span className="text-xs font-bold text-brand-100">
                                        Schriftgröße:
                                    </span>
                                    <div className="flex items-center gap-1">
                                        {(
                                            [
                                                "normal",
                                                "lg",
                                                "xl",
                                            ] as FontScale[]
                                        ).map((scale) => (
                                            <button
                                                key={scale}
                                                type="button"
                                                onClick={() =>
                                                    updateFontScale(scale)
                                                }
                                                className={`rounded-lg px-2.5 py-1 text-xs font-extrabold ${
                                                    fontScale === scale
                                                        ? "bg-white text-brand-900"
                                                        : "text-white"
                                                }`}
                                            >
                                                {scale === "normal"
                                                    ? "A"
                                                    : scale === "lg"
                                                      ? "A+"
                                                      : "A++"}
                                            </button>
                                        ))}
                                    </div>
                                </div>

                                <Link
                                    href="/"
                                    aria-current={
                                        isActive("/") ? "page" : undefined
                                    }
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive("/")
                                            ? "bg-white text-brand-800 font-extrabold"
                                            : "hover:bg-white/15"
                                    }`}
                                >
                                    Startseite
                                </Link>
                                <Link
                                    href="/aktuelles"
                                    aria-current={
                                        isActive("/aktuelles")
                                            ? "page"
                                            : undefined
                                    }
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive("/aktuelles")
                                            ? "bg-white text-brand-800 font-extrabold"
                                            : "hover:bg-white/15"
                                    }`}
                                >
                                    Aktuelles & Berichte
                                </Link>

                                {navigationPages.unsereSchule.length > 0 && (
                                    <div className="rounded-xl bg-white/10 p-1">
                                        <button
                                            type="button"
                                            onClick={() =>
                                                toggleMobileSection(
                                                    "unsere-schule",
                                                )
                                            }
                                            aria-expanded={
                                                openMobileSection ===
                                                "unsere-schule"
                                            }
                                            className="flex min-h-11 w-full items-center justify-between rounded-lg px-3 py-2 text-left font-bold text-white hover:bg-white/10 cursor-pointer"
                                        >
                                            <span>Unsere Schule</span>
                                            <span
                                                aria-hidden="true"
                                                className={`text-xs transition-transform ${
                                                    openMobileSection ===
                                                    "unsere-schule"
                                                        ? "rotate-180"
                                                        : ""
                                                }`}
                                            >
                                                ▾
                                            </span>
                                        </button>
                                        {openMobileSection ===
                                            "unsere-schule" && (
                                            <div className="mt-1 space-y-1 pb-1">
                                                {navigationPages.unsereSchule.map(
                                                    (item) => {
                                                        const itemHref =
                                                            resolvePageHref(
                                                                item,
                                                                "unsere-schule",
                                                            );
                                                        const itemActive =
                                                            currentUrl ===
                                                            itemHref;
                                                        return (
                                                            <Link
                                                                key={item.slug}
                                                                href={itemHref}
                                                                aria-current={
                                                                    itemActive
                                                                        ? "page"
                                                                        : undefined
                                                                }
                                                                className={`flex min-h-10 items-center rounded-lg px-3 py-2 pl-5 text-brand-50 ${
                                                                    itemActive
                                                                        ? "bg-white/20 font-extrabold text-white"
                                                                        : "hover:bg-white/15"
                                                                }`}
                                                            >
                                                                • {item.title}
                                                            </Link>
                                                        );
                                                    },
                                                )}
                                            </div>
                                        )}
                                    </div>
                                )}

                                {konzepteLinks.length > 0 && (
                                    <div className="rounded-xl bg-white/10 p-1">
                                        <button
                                            type="button"
                                            onClick={() =>
                                                toggleMobileSection(
                                                    "konzepte-projekte",
                                                )
                                            }
                                            aria-expanded={
                                                openMobileSection ===
                                                "konzepte-projekte"
                                            }
                                            className="flex min-h-11 w-full items-center justify-between rounded-lg px-3 py-2 text-left font-bold text-white hover:bg-white/10 cursor-pointer"
                                        >
                                            <span>Konzepte & Projekte</span>
                                            <span
                                                aria-hidden="true"
                                                className={`text-xs transition-transform ${
                                                    openMobileSection ===
                                                    "konzepte-projekte"
                                                        ? "rotate-180"
                                                        : ""
                                                }`}
                                            >
                                                ▾
                                            </span>
                                        </button>
                                        {openMobileSection ===
                                            "konzepte-projekte" && (
                                            <div className="mt-1 space-y-1 pb-1">
                                                {konzepteLinks.map((item) => {
                                                    const itemHref =
                                                        resolvePageHref(
                                                            item,
                                                            "konzepte-projekte",
                                                        );
                                                    const itemActive =
                                                        currentUrl === itemHref;
                                                    return (
                                                        <Link
                                                            key={item.slug}
                                                            href={itemHref}
                                                            aria-current={
                                                                itemActive
                                                                    ? "page"
                                                                    : undefined
                                                            }
                                                            className={`flex min-h-10 items-center rounded-lg px-3 py-2 pl-5 text-brand-50 ${
                                                                itemActive
                                                                    ? "bg-white/20 font-extrabold text-white"
                                                                    : "hover:bg-white/15"
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
                                    <div className="rounded-xl bg-white/10 p-1">
                                        <button
                                            type="button"
                                            onClick={() =>
                                                toggleMobileSection("angebote")
                                            }
                                            aria-expanded={
                                                openMobileSection === "angebote"
                                            }
                                            className="flex min-h-11 w-full items-center justify-between rounded-lg px-3 py-2 text-left font-bold text-white hover:bg-white/10 cursor-pointer"
                                        >
                                            <span>Angebote & Betreuung</span>
                                            <span
                                                aria-hidden="true"
                                                className={`text-xs transition-transform ${
                                                    openMobileSection ===
                                                    "angebote"
                                                        ? "rotate-180"
                                                        : ""
                                                }`}
                                            >
                                                ▾
                                            </span>
                                        </button>
                                        {openMobileSection === "angebote" && (
                                            <div className="mt-1 space-y-1 pb-1">
                                                {angeboteLinks.map((item) => {
                                                    const itemHref =
                                                        resolvePageHref(
                                                            item,
                                                            "angebote",
                                                        );
                                                    const itemActive =
                                                        currentUrl === itemHref;
                                                    return (
                                                        <Link
                                                            key={item.slug}
                                                            href={itemHref}
                                                            aria-current={
                                                                itemActive
                                                                    ? "page"
                                                                    : undefined
                                                            }
                                                            className={`flex min-h-10 items-center rounded-lg px-3 py-2 pl-5 text-brand-50 ${
                                                                itemActive
                                                                    ? "bg-white/20 font-extrabold text-white"
                                                                    : "hover:bg-white/15"
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
                                    aria-current={
                                        isActive("/termine")
                                            ? "page"
                                            : undefined
                                    }
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive("/termine")
                                            ? "bg-white text-brand-800 font-extrabold"
                                            : "hover:bg-white/15"
                                    }`}
                                >
                                    Termine & Kalender
                                </Link>
                                <Link
                                    href="/galerie"
                                    aria-current={
                                        isActive("/galerie")
                                            ? "page"
                                            : undefined
                                    }
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive("/galerie")
                                            ? "bg-white text-brand-800 font-extrabold"
                                            : "hover:bg-white/15"
                                    }`}
                                >
                                    Fotogalerie
                                </Link>
                                <Link
                                    href="/downloads"
                                    aria-current={
                                        isActive("/downloads")
                                            ? "page"
                                            : undefined
                                    }
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive("/downloads")
                                            ? "bg-white text-brand-800 font-extrabold"
                                            : "hover:bg-white/15"
                                    }`}
                                >
                                    Elternbriefe & Formulare
                                </Link>
                                <Link
                                    href="/kontakt"
                                    aria-current={
                                        isActive("/kontakt")
                                            ? "page"
                                            : undefined
                                    }
                                    className={`flex min-h-11 items-center rounded-xl px-3.5 py-2.5 ${
                                        isActive("/kontakt")
                                            ? "bg-white text-brand-800 font-extrabold"
                                            : "hover:bg-white/15"
                                    }`}
                                >
                                    Kontakt & Krankmeldung
                                </Link>

                                <div className="mt-3 pt-3 border-t border-white/15 grid grid-cols-2 gap-2">
                                    <div className="flex min-h-11 items-center justify-center gap-1.5 rounded-xl bg-white/10 px-2.5 py-2.5 text-xs font-semibold text-white select-all">
                                        <svg
                                            className="h-4 w-4 shrink-0"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="1.8"
                                            aria-hidden="true"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                                            />
                                        </svg>
                                        <span className="truncate">{school.phone}</span>
                                    </div>
                                    <a
                                        href={`mailto:${school.email}`}
                                        className="flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white/15 px-3 py-2.5 text-xs font-bold text-white active:bg-white/25"
                                    >
                                        <svg
                                            className="h-4 w-4 shrink-0"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="1.8"
                                            aria-hidden="true"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                                            />
                                        </svg>
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
                <Reveal
                    variant="fade-up"
                    duration={550}
                    className="mx-auto max-w-7xl px-4 sm:px-6 py-10 sm:py-12 grid gap-8 md:grid-cols-4 text-sm"
                >
                    <div>
                        <div className="flex items-center gap-3">
                            <img
                                src="/images/logo.png"
                                alt={`Logo ${school.name}`}
                                width={42}
                                height={42}
                                className="h-10 w-10 rounded-xl bg-white p-1 object-contain"
                            />
                            <p className="text-lg font-extrabold text-white">
                                {school.name}
                            </p>
                        </div>
                        <p className="mt-2 text-brand-200">{school.subtitle}</p>
                        <address className="mt-3 not-italic leading-relaxed">
                            {school.street}
                            <br />
                            {school.zip_city}
                        </address>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">
                            Sekretariat & Erreichbarkeit
                        </p>
                        <div className="mt-2.5 space-y-2">
                            <p className="flex items-center gap-2">
                                <svg
                                    className="h-4 w-4 shrink-0 text-brand-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                                    />
                                </svg>
                                <span>
                                    Telefon:{" "}
                                    <span className="font-semibold text-white">
                                        {school.phone}
                                    </span>
                                </span>
                            </p>
                            <p className="flex items-start gap-2 break-all">
                                <svg
                                    className="h-4 w-4 shrink-0 text-brand-300 mt-0.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                                    />
                                </svg>
                                <span>
                                    E-Mail:{" "}
                                    <a
                                        href={`mailto:${school.email}`}
                                        className="font-semibold text-white underline-offset-2 hover:underline"
                                    >
                                        {school.email}
                                    </a>
                                </span>
                            </p>
                            <p className="flex items-start gap-2 text-brand-200">
                                <svg
                                    className="h-4 w-4 shrink-0 text-brand-300 mt-0.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"
                                    />
                                </svg>
                                <span>
                                    Öffnungszeiten: {school.office_hours}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">
                            Unsere Schule & Konzepte
                        </p>
                        <ul className="mt-2 space-y-1.5">
                            <li>
                                <Link
                                    href="/unsere-schule/schulportrait"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Schulportrait & Kollegium
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/unsere-schule/schulsozialarbeit"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Schulsozialarbeit
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/konzepte-projekte/leitbild"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Leitbild & Klassenrat
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/angebote/schulkindbetreuung"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Schulkindbetreuung & AGs
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">
                            Service & Rechtliches
                        </p>
                        <ul className="mt-2 space-y-1.5">
                            <li>
                                <Link
                                    href="/aktuelles"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Aktuelles & Berichte
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/termine"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Schulkalender (.ics Export)
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/downloads"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Elternbriefe & Formulare
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/suche"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Suche auf dem Portal
                                </Link>
                            </li>
                            <li className="pt-1">
                                <Link
                                    href="/impressum"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Impressum
                                </Link>{" "}
                                ·{" "}
                                <Link
                                    href="/datenschutz"
                                    className="inline-block py-0.5 hover:text-white"
                                >
                                    Datenschutzerklärung
                                </Link>
                            </li>
                        </ul>
                    </div>
                </Reveal>
                <div className="border-t border-white/10 py-4 px-4 text-center text-xs text-brand-200">
                    © {new Date().getFullYear()} {school.name} Freiburg-Hochdorf
                </div>
            </footer>

            {/* Zentriertes Ankündigungs-Modal beim Betreten der Website (schließbar per × oder Esc) */}
            <AnnouncementModal
                announcements={announcements ?? []}
                schoolName={school.name}
            />

            {/* Füllt sich beim Herabscrollen & bringt den Nutzer zum Seitenanfang */}
            <ScrollToTop />
        </div>
    );
}
