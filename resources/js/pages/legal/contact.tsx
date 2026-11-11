import { Breadcrumbs } from '@/components/breadcrumbs';
import { SiteLayout } from '@/layouts/site-layout';
import type { SharedInertiaProps } from '@/types/school';
import { usePage } from '@inertiajs/react';
import { useState, type ReactElement } from 'react';

/**
 * Moderne, aufgeräumte Kontaktseite der Mühlmattenschule:
 * - Linke Spalte: Schulsekretariat (Frau Vennebusch), Öffnungs- & Sprechzeiten sowie Kontaktdaten
 * - Rechte Spalte: Schulgelände-Foto, Wegbeschreibung & Anreise sowie Routenplaner (OpenStreetMap & Google Maps)
 * - Keine überflüssigen Extra-Karten
 */
export default function ContactPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;
    const [copiedField, setCopiedField] = useState<string | null>(null);

    const copyToClipboard = async (text: string, fieldName: string): Promise<void> => {
        try {
            await navigator.clipboard.writeText(text);
            setCopiedField(fieldName);
            setTimeout(() => setCopiedField(null), 2200);
        } catch {
            // Fallback für ältere Browser
            setCopiedField(null);
        }
    };

    return (
        <SiteLayout>
            <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-7 sm:py-10">
                <Breadcrumbs />

                {/* Hero Header */}
                <header className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700 p-6 sm:p-10 text-white shadow-lg">
                    <div className="relative z-10 max-w-3xl">
                        <div className="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-brand-100 backdrop-blur-xs">
                            <span className="h-2 w-2 rounded-full bg-emerald-400 animate-pulse" />
                            <span>Schulsekretariat · Mühlmattenschule Freiburg-Hochdorf</span>
                        </div>

                        <h1 className="mt-3 text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
                            Kontakt & Schulbüro
                        </h1>

                        <p className="mt-3 text-sm sm:text-base text-brand-100 leading-relaxed">
                            Haben Sie Fragen zur Schulanmeldung, zum Schulalltag oder möchten Sie das Sekretariat erreichen?
                            Wir stehen Ihnen gerne zur Seite.
                        </p>
                    </div>

                    {/* Dekorative Hintergrund-Formen */}
                    <div className="pointer-events-none absolute -right-16 -top-16 h-72 w-72 rounded-full bg-white/5 blur-2xl" />
                    <div className="pointer-events-none absolute -bottom-16 right-24 h-64 w-64 rounded-full bg-emerald-400/10 blur-2xl" />
                </header>

                {/* 2-Spaltiger Hauptbereich: Links Schulsekretariat (Öffnungszeiten & Kontakt), Rechts Wegbeschreibung */}
                <div className="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    {/* Linke Spalte: Schulsekretariat – Öffnungszeiten & Kontaktdaten */}
                    <div className="overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-xs">
                        {/* Sekretariat Header / Ansprechpartnerin */}
                        <div className="p-6 sm:p-7 border-b border-slate-100 bg-gradient-to-br from-slate-50/80 via-white to-brand-50/20">
                            <div className="flex items-center gap-4">
                                <img
                                    src="/images/school/teacher-vennebusch.jpg"
                                    alt="Frau Mechthild Vennebusch – Schulsekretariat"
                                    className="h-16 w-16 sm:h-20 sm:w-20 rounded-2xl object-cover object-top border-2 border-brand-200 shadow-xs shrink-0"
                                    loading="lazy"
                                />
                                <div>
                                    <span className="inline-block text-[11px] font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-md border border-brand-200/60">
                                        Schulsekretariat
                                    </span>
                                    <h2 className="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">
                                        Mechthild Vennebusch
                                    </h2>
                                    <p className="text-xs sm:text-sm text-slate-600">
                                        Schulsekretärin · Verwaltung & Organisation
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="p-6 sm:p-7 space-y-6">
                            {/* Öffnungs- & Sprechzeiten */}
                            <div>
                                <div className="flex items-center gap-2">
                                    <span className="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700">
                                        Öffnungs- & Sprechzeiten
                                    </h3>
                                </div>

                                <div className="mt-3 overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-50/60 divide-y divide-slate-200/70 text-xs sm:text-sm">
                                    <div className="flex items-center justify-between px-4 py-2.5 bg-emerald-50/70">
                                        <div className="flex items-center gap-2">
                                            <span className="h-2 w-2 rounded-full bg-emerald-600" />
                                            <span className="font-semibold text-slate-900">Montag</span>
                                        </div>
                                        <span className="font-bold text-emerald-950">07:30 – 12:00 Uhr</span>
                                    </div>
                                    <div className="flex items-center justify-between px-4 py-2 text-slate-500">
                                        <span>Dienstag</span>
                                        <span className="text-[11px] font-medium text-slate-500">Bearbeitungszeit / Termine n. V.</span>
                                    </div>
                                    <div className="flex items-center justify-between px-4 py-2.5 bg-emerald-50/70">
                                        <div className="flex items-center gap-2">
                                            <span className="h-2 w-2 rounded-full bg-emerald-600" />
                                            <span className="font-semibold text-slate-900">Mittwoch</span>
                                        </div>
                                        <span className="font-bold text-emerald-950">07:30 – 12:00 Uhr</span>
                                    </div>
                                    <div className="flex items-center justify-between px-4 py-2 text-slate-500">
                                        <span>Donnerstag</span>
                                        <span className="text-[11px] font-medium text-slate-500">Bearbeitungszeit / Termine n. V.</span>
                                    </div>
                                    <div className="flex items-center justify-between px-4 py-2.5 bg-emerald-50/70">
                                        <div className="flex items-center gap-2">
                                            <span className="h-2 w-2 rounded-full bg-emerald-600" />
                                            <span className="font-semibold text-slate-900">Freitag</span>
                                        </div>
                                        <span className="font-bold text-emerald-950">07:30 – 12:00 Uhr</span>
                                    </div>
                                </div>

                                <p className="mt-2 text-[11px] text-slate-500 italic leading-relaxed">
                                    Außerhalb der Sprechzeiten erreichen Sie uns jederzeit per E-Mail oder über den schulischen Anrufbeantworter.
                                    In den Schulferien von Baden-Württemberg ist das Sekretariat geschlossen.
                                </p>
                            </div>

                            {/* Kontaktdaten */}
                            <div className="pt-5 border-t border-slate-100">
                                <div className="flex items-center gap-2 mb-3">
                                    <span className="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700">
                                        Kontaktdaten
                                    </h3>
                                </div>

                                <div className="space-y-3">
                                    {/* Telefon */}
                                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2 rounded-xl border border-slate-200/80 bg-slate-50/50 p-3.5">
                                        <div className="flex items-center gap-3">
                                            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-100/70 text-brand-800">
                                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2" aria-hidden="true">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </span>
                                            <div>
                                                <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Telefon</span>
                                                <span className="text-sm font-bold text-slate-900 select-all">{school.phone}</span>
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-2 self-end sm:self-auto">
                                            <a
                                                href={`tel:${school.phone.replace(/\s+/g, '')}`}
                                                className="inline-flex min-h-9 items-center justify-center rounded-lg bg-brand-700 px-3 py-1.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-800"
                                            >
                                                Anrufen
                                            </a>
                                            <button
                                                type="button"
                                                onClick={() => copyToClipboard(school.phone, 'phone')}
                                                aria-label="Telefonnummer kopieren"
                                                className="inline-flex min-h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-100"
                                                title="Nummer kopieren"
                                            >
                                                {copiedField === 'phone' ? (
                                                    <span className="text-xs font-bold text-emerald-600">✓</span>
                                                ) : (
                                                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>
                                    </div>

                                    {/* E-Mail */}
                                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2 rounded-xl border border-slate-200/80 bg-slate-50/50 p-3.5">
                                        <div className="flex items-center gap-3 min-w-0">
                                            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-100/70 text-brand-800">
                                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2" aria-hidden="true">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <div className="min-w-0">
                                                <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">E-Mail</span>
                                                <span className="block truncate text-xs sm:text-sm font-bold text-slate-900 select-all" title={school.email}>
                                                    {school.email}
                                                </span>
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-2 self-end sm:self-auto shrink-0">
                                            <a
                                                href={`mailto:${school.email}`}
                                                className="inline-flex min-h-9 items-center justify-center rounded-lg bg-brand-700 px-3 py-1.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-800"
                                            >
                                                Schreiben
                                            </a>
                                            <button
                                                type="button"
                                                onClick={() => copyToClipboard(school.email, 'email')}
                                                aria-label="E-Mail-Adresse kopieren"
                                                className="inline-flex min-h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-100"
                                                title="E-Mail kopieren"
                                            >
                                                {copiedField === 'email' ? (
                                                    <span className="text-xs font-bold text-emerald-600">✓</span>
                                                ) : (
                                                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>
                                    </div>

                                    {/* Telefax & Anschrift */}
                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        {/* Telefax */}
                                        <div className="flex items-center justify-between rounded-xl border border-slate-200/80 bg-slate-50/50 p-3.5">
                                            <div className="flex items-center gap-2.5">
                                                <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-xs">
                                                    📠
                                                </span>
                                                <div>
                                                    <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Telefax</span>
                                                    <span className="text-xs sm:text-sm font-bold text-slate-900 select-all">07665 930648</span>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onClick={() => copyToClipboard('07665 930648', 'fax')}
                                                aria-label="Faxnummer kopieren"
                                                className="inline-flex min-h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-100"
                                                title="Fax kopieren"
                                            >
                                                {copiedField === 'fax' ? (
                                                    <span className="text-xs font-bold text-emerald-600">✓</span>
                                                ) : (
                                                    <svg className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>

                                        {/* Postanschrift */}
                                        <div className="flex items-center justify-between rounded-xl border border-slate-200/80 bg-slate-50/50 p-3.5">
                                            <div className="flex items-center gap-2.5 min-w-0">
                                                <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-xs">
                                                    📍
                                                </span>
                                                <div className="min-w-0">
                                                    <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Adresse</span>
                                                    <span className="block truncate text-xs sm:text-sm font-bold text-slate-900 select-all" title={`${school.street}, ${school.zip_city}`}>
                                                        {school.street}
                                                    </span>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onClick={() => copyToClipboard(`${school.name}, ${school.street}, ${school.zip_city}`, 'address')}
                                                aria-label="Adresse kopieren"
                                                className="inline-flex min-h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-100 shrink-0"
                                                title="Adresse kopieren"
                                            >
                                                {copiedField === 'address' ? (
                                                    <span className="text-xs font-bold text-emerald-600">✓</span>
                                                ) : (
                                                    <svg className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Rechte Spalte: Lage, Campusfoto, Wegbeschreibung & Karten */}
                    <div className="overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-xs">
                        <div className="relative h-48 sm:h-56 w-full overflow-hidden bg-slate-900">
                            <img
                                src="/images/school/gebaeude-wide.jpg"
                                alt="Schulgebäude und Außenbereich der Mühlmattenschule Freiburg-Hochdorf"
                                className="h-full w-full object-cover opacity-90 transition duration-500 hover:scale-105"
                                loading="lazy"
                            />
                            <div className="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20" />
                            <div className="absolute bottom-4 left-4 right-4 text-white">
                                <span className="inline-block rounded-md bg-brand-700/90 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white backdrop-blur-xs">
                                    Schulgelände
                                </span>
                                <p className="mt-1 text-base sm:text-lg font-bold leading-tight">
                                    {school.name}
                                </p>
                                <p className="text-xs text-slate-200">
                                    {school.street} · {school.zip_city}
                                </p>
                            </div>
                        </div>

                        <div className="p-6">
                            <h3 className="text-sm font-bold uppercase tracking-wider text-slate-500">
                                Wegbeschreibung & Anreise
                            </h3>

                            <div className="mt-4 space-y-3.5 text-xs sm:text-sm text-slate-700">
                                {/* Fahrrad & Fußweg */}
                                <div className="flex items-start gap-3">
                                    <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 text-xs">
                                        🚲
                                    </span>
                                    <div>
                                        <span className="font-bold text-slate-900 block">Zu Fuß & mit dem Rad</span>
                                        <p className="text-slate-600 text-xs mt-0.5">
                                            Sichere Geh- und Radwege durch die verkehrsberuhigte 30er-Zone. Große Fahrradständer befinden sich direkt auf dem Schulhof.
                                        </p>
                                    </div>
                                </div>

                                {/* ÖPNV */}
                                <div className="flex items-start gap-3">
                                    <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700 text-xs">
                                        🚌
                                    </span>
                                    <div>
                                        <span className="font-bold text-slate-900 block">Öffentlicher Nahverkehr (VAG)</span>
                                        <p className="text-slate-600 text-xs mt-0.5">
                                            Buslinie 25 bis Haltestelle <strong>„Hochdorf Schule“</strong> oder <strong>„Hochdorf Rathaus“</strong>. Von dort sind es nur ca. 3–4 Gehminuten.
                                        </p>
                                    </div>
                                </div>

                                {/* Auto & Sicherheit */}
                                <div className="flex items-start gap-3">
                                    <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-700 text-xs">
                                        🚗
                                    </span>
                                    <div>
                                        <span className="font-bold text-slate-900 block">Pkw & Schulwegsicherheit</span>
                                        <p className="text-slate-600 text-xs mt-0.5">
                                            Bitte nutzen Sie die Elternhaltestellen im Ort und halten Sie den Wendehammer vor dem Schultor frei, um die Sicherheit der Kinder zu gewährleisten.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Routenplaner Buttons */}
                            <div className="mt-6 pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <a
                                    href="https://www.openstreetmap.org/search?query=Zu%20den%20M%C3%BChlmatten%208%2079108%20Freiburg"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex min-h-11 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-800 shadow-2xs hover:bg-slate-50 transition"
                                >
                                    <span>OpenStreetMap</span>
                                    <svg className="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                <a
                                    href="https://maps.google.com/?q=M%C3%BChlmattenschule+Zu+den+M%C3%BChlmatten+8+79108+Freiburg"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex min-h-11 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-800 shadow-2xs hover:bg-slate-50 transition"
                                >
                                    <span>Google Maps</span>
                                    <svg className="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SiteLayout>
    );
}
