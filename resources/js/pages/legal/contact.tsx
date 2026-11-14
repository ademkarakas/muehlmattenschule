import { Breadcrumbs } from "@/components/breadcrumbs";
import { Reveal } from "@/components/reveal";
import { SiteLayout } from "@/layouts/site-layout";
import type { SharedInertiaProps } from "@/types/school";
import { usePage } from "@inertiajs/react";
import { useState, type ReactElement } from "react";

/**
 * Moderne, aufgeräumte Kontaktseite der Mühlmattenschule:
 * - Linke Spalte: Schulsekretariat (Frau Kremer), Öffnungs- & Sprechzeiten sowie Kontaktdaten
 * - Rechte Spalte: Schulgelände-Foto, Wegbeschreibung & Anreise sowie Routenplaner (OpenStreetMap & Google Maps)
 * - Keine überflüssigen Extra-Karten
 */
export default function ContactPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;
    const [copiedField, setCopiedField] = useState<string | null>(null);

    const copyToClipboard = async (
        text: string,
        fieldName: string,
    ): Promise<void> => {
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
            <div className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                {/* Hero Header */}
                <header className="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/60 to-brand-50/40 p-6 sm:p-9 shadow-xs">
                    <div className="relative z-10">
                        <div className="inline-flex items-center gap-2 rounded-full border border-brand-200/70 bg-brand-50/80 px-3 py-1 text-xs font-semibold text-brand-700">
                            <svg
                                className="h-3.5 w-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                strokeWidth="2"
                                aria-hidden="true"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"
                                />
                            </svg>
                            <span>Schulbüro &amp; Erreichbarkeit</span>
                        </div>

                        <h1 className="mt-3 text-2xl sm:text-4xl font-extrabold tracking-tight text-slate-900">
                            Kontakt &amp; Schulbüro
                        </h1>

                        <p className="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                            Am{" "}
                            <strong className="font-semibold text-slate-900">
                                Montag
                            </strong>
                            ,{" "}
                            <strong className="font-semibold text-slate-900">
                                Mittwoch
                            </strong>{" "}
                            und{" "}
                            <strong className="font-semibold text-slate-900">
                                Freitag
                            </strong>{" "}
                            erreichen Sie uns zwischen{" "}
                            <span className="inline-flex items-center rounded-lg bg-brand-50 px-2 py-0.5 font-bold text-brand-800 ring-1 ring-brand-500/20">
                                07:30 – 12:00 Uhr
                            </span>
                            .
                        </p>
                    </div>

                    {/* Dezente Hintergrund-Akzente */}
                    <div className="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-brand-100/45 blur-3xl" />
                    <div className="pointer-events-none absolute -bottom-24 right-24 h-56 w-56 rounded-full bg-emerald-100/40 blur-3xl" />
                </header>

                {/* 2-Spaltiger Hauptbereich: Links Schulsekretariat (Öffnungszeiten & Kontakt), Rechts Wegbeschreibung */}
                <div className="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    {/* Linke Spalte: Schulsekretariat – Öffnungszeiten & Kontaktdaten */}
                    <Reveal variant="fade-up" className="overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-xs">
                        {/* Sekretariat Header / Ansprechpartnerin */}
                        <div className="p-6 sm:p-7 border-b border-slate-100 bg-gradient-to-br from-slate-50/80 via-white to-brand-50/20">
                            <div className="flex items-center gap-4">
                                <div
                                    className="flex h-16 w-16 sm:h-20 sm:w-20 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-50 via-white to-brand-100/80 text-brand-700 ring-1 ring-brand-500/20 font-extrabold text-xl sm:text-2xl tracking-tight"
                                    aria-hidden="true"
                                >
                                    SK
                                </div>
                                <div>
                                    <span className="inline-block text-[11px] font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-md border border-brand-200/60">
                                        Schulsekretariat
                                    </span>
                                    <h2 className="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">
                                        Stefanie Kremer
                                    </h2>
                                    <p className="text-xs sm:text-sm text-slate-600">
                                        Schulsekretärin · Verwaltung &
                                        Organisation
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="p-6 sm:p-7">
                            {/* Kontaktdaten */}
                            <div>
                                <div className="flex items-center gap-2.5 mb-3.5">
                                    <span className="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                        <svg
                                            className="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="1.8"
                                            aria-hidden="true"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                            />
                                        </svg>
                                    </span>
                                    <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700">
                                        Kontaktdaten
                                    </h3>
                                </div>

                                <div className="space-y-3">
                                    {/* Telefon & Telefax */}
                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        {/* Telefon */}
                                        <div className="flex items-center justify-between gap-2 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-brand-200 hover:bg-white">
                                            <div className="flex items-center gap-3 min-w-0">
                                                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                                    <svg
                                                        className="h-5 w-5"
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
                                                </span>
                                                <div className="min-w-0">
                                                    <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                                        Telefon
                                                    </span>
                                                    <a
                                                        href={`tel:${school.phone.replace(/\s+/g, "")}`}
                                                        className="text-xs sm:text-sm font-bold text-slate-900 hover:text-brand-700 transition select-all"
                                                    >
                                                        {school.phone}
                                                    </a>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    copyToClipboard(
                                                        school.phone,
                                                        "phone",
                                                    )
                                                }
                                                aria-label="Telefonnummer kopieren"
                                                className="inline-flex min-h-9 min-w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-500 shadow-2xs transition hover:border-brand-200 hover:bg-brand-50/50 hover:text-brand-700"
                                                title="Nummer kopieren"
                                            >
                                                {copiedField === "phone" ? (
                                                    <svg
                                                        className="h-4 w-4 text-emerald-600"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth="2.2"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M4.5 12.75l6 6 9-13.5"
                                                        />
                                                    </svg>
                                                ) : (
                                                    <svg
                                                        className="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth="1.8"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"
                                                        />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>

                                        {/* Telefax */}
                                        <div className="flex items-center justify-between gap-2 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-brand-200 hover:bg-white">
                                            <div className="flex items-center gap-3 min-w-0">
                                                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                                    <svg
                                                        className="h-5 w-5"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth="1.8"
                                                        aria-hidden="true"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"
                                                        />
                                                    </svg>
                                                </span>
                                                <div className="min-w-0">
                                                    <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                                        Telefax
                                                    </span>
                                                    <span className="text-xs sm:text-sm font-bold text-slate-900 select-all">
                                                        07665 930648
                                                    </span>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    copyToClipboard(
                                                        "07665 930648",
                                                        "fax",
                                                    )
                                                }
                                                aria-label="Faxnummer kopieren"
                                                className="inline-flex min-h-9 min-w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-500 shadow-2xs transition hover:border-brand-200 hover:bg-brand-50/50 hover:text-brand-700"
                                                title="Fax kopieren"
                                            >
                                                {copiedField === "fax" ? (
                                                    <svg
                                                        className="h-4 w-4 text-emerald-600"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth="2.2"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M4.5 12.75l6 6 9-13.5"
                                                        />
                                                    </svg>
                                                ) : (
                                                    <svg
                                                        className="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth="1.8"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"
                                                        />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>
                                    </div>

                                    {/* E-Mail */}
                                    <div className="flex items-center justify-between gap-3 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-brand-200 hover:bg-white">
                                        <div className="flex items-center gap-3.5 min-w-0">
                                            <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                                <svg
                                                    className="h-5 w-5"
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
                                            </span>
                                            <div className="min-w-0">
                                                <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                                    E-Mail
                                                </span>
                                                <a
                                                    href={`mailto:${school.email}`}
                                                    className="block truncate text-xs sm:text-sm font-bold text-slate-900 hover:text-brand-700 transition select-all"
                                                    title={school.email}
                                                >
                                                    {school.email}
                                                </a>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={() =>
                                                copyToClipboard(
                                                    school.email,
                                                    "email",
                                                )
                                            }
                                            aria-label="E-Mail-Adresse kopieren"
                                            className="inline-flex min-h-9 min-w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-500 shadow-2xs transition hover:border-brand-200 hover:bg-brand-50/50 hover:text-brand-700"
                                            title="E-Mail kopieren"
                                        >
                                            {copiedField === "email" ? (
                                                <svg
                                                    className="h-4 w-4 text-emerald-600"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="2.2"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M4.5 12.75l6 6 9-13.5"
                                                    />
                                                </svg>
                                            ) : (
                                                <svg
                                                    className="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="1.8"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"
                                                    />
                                                </svg>
                                            )}
                                        </button>
                                    </div>

                                    {/* Postanschrift (einzeilig über die volle Breite) */}
                                    <div className="flex items-center justify-between gap-3 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-brand-200 hover:bg-white">
                                        <div className="flex items-center gap-3.5 min-w-0">
                                            <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                                <svg
                                                    className="h-5 w-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="1.8"
                                                    aria-hidden="true"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
                                                    />
                                                </svg>
                                            </span>
                                            <div className="min-w-0">
                                                <span className="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                                    Adresse
                                                </span>
                                                <span
                                                    className="block truncate text-xs sm:text-sm font-bold text-slate-900 select-all"
                                                    title={`${school.street}, ${school.zip_city}`}
                                                >
                                                    {school.street},{" "}
                                                    {school.zip_city}
                                                </span>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={() =>
                                                copyToClipboard(
                                                    `${school.name}, ${school.street}, ${school.zip_city}`,
                                                    "address",
                                                )
                                            }
                                            aria-label="Adresse kopieren"
                                            className="inline-flex min-h-9 min-w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-500 shadow-2xs transition hover:border-brand-200 hover:bg-brand-50/50 hover:text-brand-700"
                                            title="Adresse kopieren"
                                        >
                                            {copiedField === "address" ? (
                                                <svg
                                                    className="h-4 w-4 text-emerald-600"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="2.2"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M4.5 12.75l6 6 9-13.5"
                                                    />
                                                </svg>
                                            ) : (
                                                <svg
                                                    className="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="1.8"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"
                                                    />
                                                </svg>
                                            )}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Reveal>

                    {/* Rechte Spalte: Lage, Campusfoto, Wegbeschreibung & Karten */}
                    <Reveal variant="fade-up" delay={100} className="overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-xs">
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

                        <div className="p-6 sm:p-7">
                            <div className="flex items-center gap-2.5">
                                <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                    <svg
                                        className="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="1.8"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"
                                        />
                                    </svg>
                                </span>
                                <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700">
                                    Wegbeschreibung & Anreise
                                </h3>
                            </div>

                            <div className="mt-4 space-y-3 text-xs sm:text-sm text-slate-700">
                                {/* Fahrrad & Fußweg */}
                                <div className="flex items-start gap-3.5 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-brand-200 hover:bg-white">
                                    <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                        <svg
                                            className="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="1.8"
                                            aria-hidden="true"
                                        >
                                            <circle cx="5.5" cy="17.5" r="3" />
                                            <circle cx="18.5" cy="17.5" r="3" />
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M5.5 17.5L9.5 9h5l2 4.5M12 9L9.5 17.5M8 9h3.5M14.5 6.5h2.5l1.5 11"
                                            />
                                        </svg>
                                    </span>
                                    <div className="min-w-0">
                                        <span className="font-bold text-slate-900 block">
                                            Zu Fuß & mit dem Rad
                                        </span>
                                        <p className="text-slate-600 text-xs mt-0.5 leading-relaxed">
                                            Sichere Geh- und Radwege durch die
                                            verkehrsberuhigte 30er-Zone. Große
                                            Fahrradständer befinden sich direkt
                                            auf dem Schulhof.
                                        </p>
                                    </div>
                                </div>

                                {/* ÖPNV */}
                                <div className="flex items-start gap-3.5 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-brand-200 hover:bg-white">
                                    <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                        <svg
                                            className="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="1.8"
                                            aria-hidden="true"
                                        >
                                            <rect
                                                x="4"
                                                y="3"
                                                width="16"
                                                height="15"
                                                rx="2.5"
                                            />
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M4 11h16M8 15h.01M16 15h.01M6.5 18v2.5M17.5 18v2.5M8 7h8"
                                            />
                                        </svg>
                                    </span>
                                    <div className="min-w-0">
                                        <span className="font-bold text-slate-900 block">
                                            Öffentlicher Nahverkehr (VAG)
                                        </span>
                                        <p className="text-slate-600 text-xs mt-0.5 leading-relaxed">
                                            Buslinie 25 bis Haltestelle{" "}
                                            <strong>„Hochdorf Schule“</strong>{" "}
                                            oder{" "}
                                            <strong>„Hochdorf Rathaus“</strong>.
                                            Von dort sind es nur ca. 3–4
                                            Gehminuten.
                                        </p>
                                    </div>
                                </div>

                                {/* Auto & Sicherheit */}
                                <div className="flex items-start gap-3.5 rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-brand-200 hover:bg-white">
                                    <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15">
                                        <svg
                                            className="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            strokeWidth="1.8"
                                            aria-hidden="true"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                                            />
                                        </svg>
                                    </span>
                                    <div className="min-w-0">
                                        <span className="font-bold text-slate-900 block">
                                            Pkw & Schulwegsicherheit
                                        </span>
                                        <p className="text-slate-600 text-xs mt-0.5 leading-relaxed">
                                            Bitte nutzen Sie die
                                            Elternhaltestellen im Ort und halten
                                            Sie den Wendehammer vor dem Schultor
                                            frei, um die Sicherheit der Kinder
                                            zu gewährleisten.
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
                                    className="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200/90 bg-white px-3.5 py-2 text-xs font-bold text-slate-800 shadow-2xs transition hover:border-brand-200 hover:bg-brand-50/40 hover:text-brand-800"
                                >
                                    <svg
                                        className="h-4 w-4 text-brand-700 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="1.8"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
                                        />
                                    </svg>
                                    <span>OpenStreetMap</span>
                                    <svg
                                        className="h-3.5 w-3.5 text-slate-400 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="https://maps.google.com/?q=M%C3%BChlmattenschule+Zu+den+M%C3%BChlmatten+8+79108+Freiburg"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200/90 bg-white px-3.5 py-2 text-xs font-bold text-slate-800 shadow-2xs transition hover:border-brand-200 hover:bg-brand-50/40 hover:text-brand-800"
                                >
                                    <svg
                                        className="h-4 w-4 text-brand-700 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="1.8"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"
                                        />
                                    </svg>
                                    <span>Google Maps</span>
                                    <svg
                                        className="h-3.5 w-3.5 text-slate-400 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </Reveal>
                </div>
            </div>
        </SiteLayout>
    );
}
