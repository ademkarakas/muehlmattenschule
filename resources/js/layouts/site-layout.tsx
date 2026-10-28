import { Link, usePage } from '@inertiajs/react';
import type { SharedInertiaProps } from '@/types/school';
import { useState, type ReactElement, type ReactNode } from 'react';

export interface SiteLayoutProps {
    children: ReactNode;
}

/**
 * Gemeinsames EduPage-inspiriertes Seitenlayout der Mühlmattenschule mit
 * türkiser Kopfleiste, Hauptnavigation, Ankündigungsband und Fußbereich.
 */
export function SiteLayout({ children }: SiteLayoutProps): ReactElement {
    const { school, announcements, navigationPages } = usePage<SharedInertiaProps>().props;
    const [mobileOpen, setMobileOpen] = useState(false);

    return (
        <div className="min-h-screen flex flex-col bg-surface text-ink">
            <a
                href="#hauptinhalt"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-brand-900 focus:shadow-lg"
            >
                Zum Hauptinhalt springen
            </a>

            {/* Oberer Kopfbereich (dunkles Türkis) */}
            <header className="bg-brand-800 text-white">
                <div className="mx-auto max-w-6xl px-4 py-3.5 flex flex-wrap items-center justify-between gap-4">
                    <Link href="/" className="flex items-center gap-3.5 group">
                        <img
                            src="/images/logo.svg"
                            alt={`Logo ${school.name}`}
                            className="h-12 w-12 rounded-xl bg-white/10 p-1.5 transition group-hover:scale-105"
                        />
                        <div>
                            <span className="block text-xl font-bold tracking-tight leading-tight">
                                {school.name}
                            </span>
                            <span className="block text-xs text-brand-100">{school.subtitle}</span>
                        </div>
                    </Link>

                    <div className="hidden md:flex items-center gap-6 text-xs text-brand-100">
                        <div>
                            <span className="block font-semibold text-white">Telefon Sekretariat</span>
                            <a href={`tel:${school.phone}`} className="hover:underline">
                                {school.phone}
                            </a>
                        </div>
                        <div>
                            <span className="block font-semibold text-white">E-Mail</span>
                            <a href={`mailto:${school.email}`} className="hover:underline">
                                {school.email}
                            </a>
                        </div>
                        <a
                            href="/admin"
                            className="rounded-lg bg-brand-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-brand-500"
                        >
                            Verwaltung / Login
                        </a>
                    </div>

                    <button
                        type="button"
                        onClick={() => setMobileOpen((prev) => !prev)}
                        aria-expanded={mobileOpen}
                        aria-label="Hauptmenü öffnen oder schließen"
                        className="md:hidden rounded-lg border border-white/25 px-3 py-2 text-sm font-semibold"
                    >
                        {mobileOpen ? 'Schließen ✕' : 'Menü ☰'}
                    </button>
                </div>

                {/* Türkise Navigationsleiste (EduPage-Stil) */}
                <nav aria-label="Hauptnavigation" className="bg-brand-600 border-t border-brand-500/40">
                    <div className="mx-auto max-w-6xl px-4">
                        <ul className="hidden md:flex items-center gap-1 text-sm font-semibold">
                            <li>
                                <Link href="/" className="block px-3.5 py-3 hover:bg-brand-700 rounded-t-md">
                                    Startseite
                                </Link>
                            </li>
                            <li>
                                <Link href="/aktuelles" className="block px-3.5 py-3 hover:bg-brand-700">
                                    Aktuelles & Klassen
                                </Link>
                            </li>
                            <li>
                                <Link href="/termine" className="block px-3.5 py-3 hover:bg-brand-700">
                                    Termine
                                </Link>
                            </li>
                            <li>
                                <Link href="/galerie" className="block px-3.5 py-3 hover:bg-brand-700">
                                    Fotoalben
                                </Link>
                            </li>

                            {navigationPages.unsereSchule.length > 0 && (
                                <li className="relative group">
                                    <span className="flex items-center gap-1 px-3.5 py-3 cursor-pointer group-hover:bg-brand-700">
                                        Unsere Schule <span aria-hidden="true">▾</span>
                                    </span>
                                    <ul className="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all absolute left-0 top-full z-30 w-64 rounded-b-xl bg-white py-2 text-slate-800 shadow-lg border border-slate-200">
                                        {navigationPages.unsereSchule.map((item) => (
                                            <li key={item.slug}>
                                                <Link
                                                    href={`/unsere-schule/${item.slug}`}
                                                    className="block px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-800"
                                                >
                                                    {item.title}
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                </li>
                            )}

                            {navigationPages.schulleben.length > 0 && (
                                <li className="relative group">
                                    <span className="flex items-center gap-1 px-3.5 py-3 cursor-pointer group-hover:bg-brand-700">
                                        Schulleben <span aria-hidden="true">▾</span>
                                    </span>
                                    <ul className="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all absolute left-0 top-full z-30 w-64 rounded-b-xl bg-white py-2 text-slate-800 shadow-lg border border-slate-200">
                                        {navigationPages.schulleben.map((item) => (
                                            <li key={item.slug}>
                                                <Link
                                                    href={`/schulleben/${item.slug}`}
                                                    className="block px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-800"
                                                >
                                                    {item.title}
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                </li>
                            )}

                            <li>
                                <Link href="/downloads" className="block px-3.5 py-3 hover:bg-brand-700">
                                    Downloads
                                </Link>
                            </li>
                            <li>
                                <Link href="/kontakt" className="block px-3.5 py-3 hover:bg-brand-700">
                                    Kontakt
                                </Link>
                            </li>
                        </ul>

                        {/* Mobiles Menü */}
                        {mobileOpen && (
                            <div className="md:hidden py-3 space-y-1 text-sm font-medium">
                                <Link href="/" className="block rounded-lg px-3 py-2 hover:bg-brand-700">
                                    Startseite
                                </Link>
                                <Link href="/aktuelles" className="block rounded-lg px-3 py-2 hover:bg-brand-700">
                                    Aktuelles & Klassen
                                </Link>
                                <Link href="/termine" className="block rounded-lg px-3 py-2 hover:bg-brand-700">
                                    Termine
                                </Link>
                                <Link href="/galerie" className="block rounded-lg px-3 py-2 hover:bg-brand-700">
                                    Fotoalben
                                </Link>
                                {navigationPages.unsereSchule.map((item) => (
                                    <Link
                                        key={item.slug}
                                        href={`/unsere-schule/${item.slug}`}
                                        className="block rounded-lg px-3 py-2 pl-6 text-brand-100 hover:bg-brand-700"
                                    >
                                        • {item.title}
                                    </Link>
                                ))}
                                {navigationPages.schulleben.map((item) => (
                                    <Link
                                        key={item.slug}
                                        href={`/schulleben/${item.slug}`}
                                        className="block rounded-lg px-3 py-2 pl-6 text-brand-100 hover:bg-brand-700"
                                    >
                                        • {item.title}
                                    </Link>
                                ))}
                                <Link href="/downloads" className="block rounded-lg px-3 py-2 hover:bg-brand-700">
                                    Downloads
                                </Link>
                                <Link href="/kontakt" className="block rounded-lg px-3 py-2 hover:bg-brand-700">
                                    Kontakt
                                </Link>
                                <a
                                    href="/admin"
                                    className="mt-2 block rounded-lg bg-brand-800 px-3 py-2 text-center font-semibold"
                                >
                                    Verwaltung / Login
                                </a>
                            </div>
                        )}
                    </div>
                </nav>
            </header>

            {/* Ankündigungsband */}
            {announcements.length > 0 && (
                <section aria-label="Wichtige Mitteilungen" className="border-b border-brand-200 bg-brand-50">
                    <div className="mx-auto max-w-6xl px-4 py-2.5 space-y-1.5">
                        {announcements.map((item) => (
                            <div
                                key={item.id}
                                className="flex flex-wrap items-center justify-between gap-2 text-sm"
                            >
                                <div className="flex items-center gap-2.5">
                                    <span
                                        className={
                                            item.level === 'important'
                                                ? 'rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-bold text-slate-950'
                                                : 'rounded-full bg-brand-600 px-2.5 py-0.5 text-xs font-bold text-white'
                                        }
                                    >
                                        {item.level === 'important' ? 'Wichtig' : 'Hinweis'}
                                    </span>
                                    <span className="font-medium text-slate-800">{item.text}</span>
                                </div>
                                {item.linkUrl && (
                                    <Link
                                        href={item.linkUrl}
                                        className="text-xs font-semibold text-brand-800 underline hover:text-brand-950"
                                    >
                                        {item.linkLabel || 'Mehr erfahren'} →
                                    </Link>
                                )}
                            </div>
                        ))}
                    </div>
                </section>
            )}

            {/* Seiteninhalt */}
            <main id="hauptinhalt" className="flex-1">
                {children}
            </main>

            {/* Fußbereich */}
            <footer className="mt-16 bg-brand-900 text-brand-100">
                <div className="mx-auto max-w-6xl px-4 py-10 grid gap-8 md:grid-cols-3 text-sm">
                    <div>
                        <p className="text-base font-bold text-white">{school.name}</p>
                        <p className="mt-1 text-brand-200">{school.subtitle}</p>
                        <p className="mt-3">
                            {school.street}
                            <br />
                            {school.zip_city}
                        </p>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">Kontakt & Sekretariat</p>
                        <p className="mt-2">Telefon: {school.phone}</p>
                        <p>E-Mail: {school.email}</p>
                        <p className="mt-2 text-brand-200">Öffnungszeiten: {school.office_hours}</p>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">Schnellzugriff</p>
                        <ul className="mt-2 space-y-1.5">
                            <li>
                                <Link href="/aktuelles" className="hover:text-white">
                                    Aktuelles & Klassenaktivitäten
                                </Link>
                            </li>
                            <li>
                                <Link href="/termine" className="hover:text-white">
                                    Schultermine
                                </Link>
                            </li>
                            <li>
                                <Link href="/downloads" className="hover:text-white">
                                    Formulare & Elternbriefe
                                </Link>
                            </li>
                            <li>
                                <Link href="/impressum" className="hover:text-white">
                                    Impressum
                                </Link>{' '}
                                ·{' '}
                                <Link href="/datenschutz" className="hover:text-white">
                                    Datenschutz
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className="border-t border-brand-800 py-4 text-center text-xs text-brand-300">
                    © {new Date().getFullYear()} {school.name} Freiburg-Hochdorf
                </div>
            </footer>
        </div>
    );
}

