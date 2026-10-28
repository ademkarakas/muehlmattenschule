import { Link, usePage } from '@inertiajs/react';
import type { SharedInertiaProps } from '@/types/school';
import { useState, type ReactElement, type ReactNode } from 'react';

export interface SiteLayoutProps {
    children: ReactNode;
}

/**
 * EduPage-inspiriertes Seitenlayout der Mühlmattenschule im bläulichen Türkis-Design
 * mit klarem Kopfbereich, moderner Kachel-/Tab-Navigation und Fußbereich.
 */
export function SiteLayout({ children }: SiteLayoutProps): ReactElement {
    const page = usePage<SharedInertiaProps>();
    const { school, navigationPages } = page.props;
    const currentUrl = page.url;
    const [mobileOpen, setMobileOpen] = useState(false);

    const isActive = (prefix: string): boolean => {
        if (prefix === '/') {
            return currentUrl === '/';
        }
        return currentUrl.startsWith(prefix);
    };

    return (
        <div className="min-h-screen flex flex-col bg-surface text-ink">
            <a
                href="#hauptinhalt"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-brand-900 focus:shadow-lg"
            >
                Zum Hauptinhalt springen
            </a>

            {/* Oberer Kopfbereich im EduPage-Portalstil */}
            <header className="sticky top-0 z-40 shadow-sm">
                <div className="bg-white border-b border-slate-200/80">
                    <div className="mx-auto max-w-[1440px] px-6 lg:px-10 py-4 flex items-center justify-between gap-6">
                        <Link href="/" className="flex items-center gap-4 group">
                            <img
                                src="/images/logo.svg"
                                alt={`Logo ${school.name}`}
                                className="h-13 w-13 rounded-2xl shadow-xs transition group-hover:scale-105"
                            />
                            <div>
                                <span className="block text-2xl md:text-[26px] font-extrabold tracking-tight text-brand-900 leading-tight">
                                    {school.name}
                                </span>
                                <span className="block text-xs sm:text-sm font-medium text-brand-700">
                                    {school.subtitle} · {school.street}, {school.zip_city}
                                </span>
                            </div>
                        </Link>

                        <div className="hidden lg:flex items-center gap-4 text-xs sm:text-sm">
                            <a
                                href={`tel:${school.phone}`}
                                className="flex items-center gap-2 rounded-xl bg-brand-50 px-4 py-2.5 font-semibold text-brand-800 hover:bg-brand-100"
                            >
                                <span aria-hidden="true">☎</span>
                                <span>{school.phone}</span>
                            </a>
                            <a
                                href={`mailto:${school.email}`}
                                className="flex items-center gap-2 rounded-xl bg-brand-50 px-4 py-2.5 font-semibold text-brand-800 hover:bg-brand-100"
                            >
                                <span aria-hidden="true">✉</span>
                                <span>{school.email}</span>
                            </a>
                            <a
                                href="/admin"
                                className="rounded-xl bg-gradient-to-r from-brand-600 to-brand-800 px-5 py-2.5 text-sm font-bold text-white shadow-xs hover:from-brand-700 hover:to-brand-900"
                            >
                                Anmelden / Verwaltung
                            </a>
                        </div>

                        <button
                            type="button"
                            onClick={() => setMobileOpen((prev) => !prev)}
                            aria-expanded={mobileOpen}
                            aria-label="Hauptmenü öffnen oder schließen"
                            className="lg:hidden rounded-xl border border-brand-200 bg-brand-50 px-4 py-2.5 text-sm font-bold text-brand-800"
                        >
                            {mobileOpen ? 'Schließen ✕' : 'Menü ☰'}
                        </button>
                    </div>
                </div>

                {/* Dunkel-türkise, breite EduPage-Menüleiste */}
                <nav
                    aria-label="Hauptnavigation"
                    className="bg-gradient-to-r from-brand-700 via-brand-600 to-brand-800 text-white"
                >
                    <div className="mx-auto max-w-[1440px] px-6 lg:px-10">
                        <ul className="hidden lg:flex items-center justify-between text-[15px] font-bold tracking-wide">
                            <li>
                                <Link
                                    href="/"
                                    className={`block px-5 py-4 transition ${
                                        isActive('/') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Startseite
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/aktuelles"
                                    className={`block px-5 py-4 transition ${
                                        isActive('/aktuelles') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Nachrichten & Klassen
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/termine"
                                    className={`block px-5 py-4 transition ${
                                        isActive('/termine') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Termine & Kalender
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/galerie"
                                    className={`block px-5 py-4 transition ${
                                        isActive('/galerie') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Fotogalerie
                                </Link>
                            </li>

                            {navigationPages.unsereSchule.length > 0 && (
                                <li className="relative group">
                                    <span
                                        className={`flex items-center gap-1.5 px-5 py-4 cursor-pointer transition ${
                                            isActive('/unsere-schule')
                                                ? 'bg-white/20 shadow-inner'
                                                : 'group-hover:bg-white/15'
                                        }`}
                                    >
                                        Unsere Schule <span aria-hidden="true" className="text-xs">▾</span>
                                    </span>
                                    <ul className="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 absolute left-0 top-full z-40 w-68 rounded-b-2xl bg-white py-2 text-slate-800 shadow-xl border border-slate-200">
                                        {navigationPages.unsereSchule.map((item) => (
                                            <li key={item.slug}>
                                                <Link
                                                    href={`/unsere-schule/${item.slug}`}
                                                    className="block px-5 py-2.5 text-sm font-semibold hover:bg-brand-50 hover:text-brand-700"
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
                                    <span
                                        className={`flex items-center gap-1.5 px-5 py-4 cursor-pointer transition ${
                                            isActive('/schulleben')
                                                ? 'bg-white/20 shadow-inner'
                                                : 'group-hover:bg-white/15'
                                        }`}
                                    >
                                        Schulleben & Betreuung <span aria-hidden="true" className="text-xs">▾</span>
                                    </span>
                                    <ul className="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 absolute left-0 top-full z-40 w-68 rounded-b-2xl bg-white py-2 text-slate-800 shadow-xl border border-slate-200">
                                        {navigationPages.schulleben.map((item) => (
                                            <li key={item.slug}>
                                                <Link
                                                    href={`/schulleben/${item.slug}`}
                                                    className="block px-5 py-2.5 text-sm font-semibold hover:bg-brand-50 hover:text-brand-700"
                                                >
                                                    {item.title}
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                </li>
                            )}

                            <li>
                                <Link
                                    href="/downloads"
                                    className={`block px-5 py-4 transition ${
                                        isActive('/downloads') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Elternbriefe & Formulare
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/kontakt"
                                    className={`block px-5 py-4 transition ${
                                        isActive('/kontakt') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/15'
                                    }`}
                                >
                                    Kontakt
                                </Link>
                            </li>
                        </ul>

                        {/* Mobiles Menü */}
                        {mobileOpen && (
                            <div className="lg:hidden py-3 space-y-1 text-sm font-semibold">
                                <Link href="/" className="block rounded-xl px-3 py-2 hover:bg-white/15">
                                    Startseite
                                </Link>
                                <Link href="/aktuelles" className="block rounded-xl px-3 py-2 hover:bg-white/15">
                                    Nachrichten & Klassen
                                </Link>
                                <Link href="/termine" className="block rounded-xl px-3 py-2 hover:bg-white/15">
                                    Termine & Kalender
                                </Link>
                                <Link href="/galerie" className="block rounded-xl px-3 py-2 hover:bg-white/15">
                                    Fotogalerie
                                </Link>
                                {navigationPages.unsereSchule.map((item) => (
                                    <Link
                                        key={item.slug}
                                        href={`/unsere-schule/${item.slug}`}
                                        className="block rounded-xl px-3 py-2 pl-6 text-brand-100 hover:bg-white/15"
                                    >
                                        • {item.title}
                                    </Link>
                                ))}
                                {navigationPages.schulleben.map((item) => (
                                    <Link
                                        key={item.slug}
                                        href={`/schulleben/${item.slug}`}
                                        className="block rounded-xl px-3 py-2 pl-6 text-brand-100 hover:bg-white/15"
                                    >
                                        • {item.title}
                                    </Link>
                                ))}
                                <Link href="/downloads" className="block rounded-xl px-3 py-2 hover:bg-white/15">
                                    Elternbriefe & Formulare
                                </Link>
                                <Link href="/kontakt" className="block rounded-xl px-3 py-2 hover:bg-white/15">
                                    Kontakt
                                </Link>
                                <a
                                    href="/admin"
                                    className="mt-2 block rounded-xl bg-white px-3 py-2.5 text-center font-bold text-brand-800"
                                >
                                    Anmelden / Verwaltung
                                </a>
                            </div>
                        )}
                    </div>
                </nav>
            </header>

            {/* Seiteninhalt */}
            <main id="hauptinhalt" className="flex-1">
                {children}
            </main>

            {/* Fußbereich */}
            <footer className="mt-16 bg-brand-900 text-brand-100">
                <div className="mx-auto max-w-7xl px-4 py-12 grid gap-8 md:grid-cols-3 text-sm">
                    <div>
                        <div className="flex items-center gap-3">
                            <img src="/images/logo.svg" alt="" className="h-9 w-9 rounded-xl" />
                            <p className="text-lg font-extrabold text-white">{school.name}</p>
                        </div>
                        <p className="mt-2 text-brand-200">{school.subtitle}</p>
                        <p className="mt-3">
                            {school.street}
                            <br />
                            {school.zip_city}
                        </p>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">Sekretariat & Erreichbarkeit</p>
                        <p className="mt-2">Telefon: {school.phone}</p>
                        <p>E-Mail: {school.email}</p>
                        <p className="mt-2 text-brand-200">Öffnungszeiten: {school.office_hours}</p>
                    </div>
                    <div>
                        <p className="text-base font-bold text-white">Service & Rechtliches</p>
                        <ul className="mt-2 space-y-1.5">
                            <li>
                                <Link href="/aktuelles" className="hover:text-white">
                                    Nachrichten & Klassenaktivitäten
                                </Link>
                            </li>
                            <li>
                                <Link href="/termine" className="hover:text-white">
                                    Schulkalender
                                </Link>
                            </li>
                            <li>
                                <Link href="/downloads" className="hover:text-white">
                                    Elternbriefe & Formulare
                                </Link>
                            </li>
                            <li>
                                <Link href="/impressum" className="hover:text-white">
                                    Impressum
                                </Link>{' '}
                                ·{' '}
                                <Link href="/datenschutz" className="hover:text-white">
                                    Datenschutzerklärung
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className="border-t border-white/10 py-4 text-center text-xs text-brand-200">
                    © {new Date().getFullYear()} {school.name} Freiburg-Hochdorf
                </div>
            </footer>
        </div>
    );
}
