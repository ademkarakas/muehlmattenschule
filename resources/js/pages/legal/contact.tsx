import { SiteLayout } from '@/layouts/site-layout';
import type { SharedInertiaProps } from '@/types/school';
import { Head, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

/**
 * Kontaktseite der Mühlmattenschule mit Sekretariats-Öffnungszeiten,
 * Ansprechpartnerinnen und DSGVO-konformer Anfahrtsinfo.
 */
export default function ContactPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;

    return (
        <SiteLayout>
            <Head title="Kontakt & Anfahrt" />

            <div className="mx-auto max-w-6xl px-4 py-10 space-y-8">
                {/* Prominenter Kopfbereich mit "Fragen zu unserer Schule?" */}
                <section className="rounded-3xl bg-gradient-to-br from-brand-800 via-brand-700 to-brand-900 p-8 md:p-10 text-white shadow-md">
                    <span className="inline-block rounded-full bg-white/15 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-brand-50">
                        Kontakt & Erreichbarkeit
                    </span>
                    <h1 className="mt-3 text-3xl md:text-4xl font-extrabold tracking-tight">
                        Fragen zu unserer Schule?
                    </h1>
                    <p className="mt-3 max-w-2xl text-base md:text-lg text-brand-50 leading-relaxed">
                        Unser Sekretariat ist <strong>Montag, Mittwoch und Freitag von 7:30 bis 12:00 Uhr</strong>{' '}
                        erreichbar.
                    </p>
                    <div className="mt-6 flex flex-wrap gap-3.5">
                        <a
                            href={`tel:${school.phone}`}
                            className="rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-800 shadow-xs hover:bg-brand-50"
                        >
                            ☎ {school.phone} anrufen
                        </a>
                        <a
                            href={`mailto:${school.email}`}
                            className="rounded-xl border border-white/35 bg-white/10 px-5 py-3 text-sm font-bold text-white hover:bg-white/20"
                        >
                            ✉ E-Mail schreiben
                        </a>
                    </div>
                </section>

                <div className="grid gap-6 md:grid-cols-3">
                    {/* Sekretariat & Schulleitung */}
                    <section className="rounded-2xl border border-slate-200 bg-white p-6 md:p-7 shadow-xs space-y-4">
                        <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                            Schulbüro
                        </span>
                        <h2 className="text-xl font-extrabold text-slate-900">Anschrift & Sekretariat</h2>
                        <p className="text-sm leading-relaxed text-slate-700">
                            <strong>{school.name}</strong>
                            <br />
                            {school.subtitle}
                            <br />
                            {school.street}
                            <br />
                            {school.zip_city}
                        </p>
                        <div className="space-y-1.5 text-sm text-slate-700">
                            <p>
                                <strong>Schulsekretärin:</strong> Mechthild Vennebusch
                            </p>
                            <p>
                                <strong>Telefon:</strong>{' '}
                                <a href={`tel:${school.phone}`} className="font-bold text-brand-700 hover:underline">
                                    {school.phone}
                                </a>
                            </p>
                            <p>
                                <strong>E-Mail:</strong>{' '}
                                <a
                                    href={`mailto:${school.email}`}
                                    className="font-semibold text-brand-700 hover:underline break-all"
                                >
                                    {school.email}
                                </a>
                            </p>
                        </div>
                        <div className="rounded-xl bg-brand-50 p-3.5 border border-brand-100 text-xs text-brand-900">
                            <strong>Öffnungszeiten Sekretariat:</strong>
                            <br />
                            Montag, Mittwoch und Freitag von 7:30 bis 12:00 Uhr
                        </div>
                    </section>

                    {/* Schulkindbetreuung */}
                    <section className="rounded-2xl border border-slate-200 bg-white p-6 md:p-7 shadow-xs space-y-4">
                        <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                            Caritas Freiburg-Stadt e.V.
                        </span>
                        <h2 className="text-xl font-extrabold text-slate-900">Kontakt Schulkindbetreuung</h2>
                        <p className="text-sm leading-relaxed text-slate-700">
                            <strong>Leitung:</strong> Lisa Jortzik
                            <br />
                            Zu den Mühlmatten 8, 79108 Freiburg im Breisgau
                        </p>
                        <div className="space-y-1.5 text-sm text-slate-700">
                            <p>
                                <strong>Festnetz:</strong>{' '}
                                <a href="tel:076659329221" className="font-bold text-brand-700 hover:underline">
                                    07665 932 92 21
                                </a>
                            </p>
                            <p>
                                <strong>Mobil:</strong>{' '}
                                <a href="tel:015202751785" className="font-bold text-brand-700 hover:underline">
                                    0152 02751785
                                </a>
                            </p>
                            <p>
                                <strong>E-Mail:</strong>{' '}
                                <a
                                    href="mailto:schulkindbetreuung-muehlmattenschule@caritas-freiburg.de"
                                    className="font-semibold text-brand-700 hover:underline break-all"
                                >
                                    schulkindbetreuung-muehlmattenschule@caritas-freiburg.de
                                </a>
                            </p>
                        </div>
                        <div className="rounded-xl bg-brand-50 p-3.5 border border-brand-100 text-xs text-brand-900">
                            <strong>Telefonische Sprechzeiten:</strong>
                            <br />
                            7:30 – 8:30 Uhr & 12:15 – 17:00 Uhr
                        </div>
                    </section>

                    {/* Lage & Anfahrt */}
                    <section className="rounded-2xl border border-brand-200 bg-brand-50/70 p-6 md:p-7 flex flex-col justify-between">
                        <div>
                            <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                                Wegbeschreibung
                            </span>
                            <h2 className="mt-1 text-xl font-extrabold text-brand-900">Lage & Anfahrt</h2>
                            <p className="mt-3 text-sm leading-relaxed text-slate-700">
                                Die Mühlmattenschule liegt am grünen Rand des Freiburger Stadtteils Hochdorf und ist
                                sowohl mit den Buslinien der VAG als auch mit dem Fahrrad über die Stadtteilwege sicher
                                erreichbar.
                            </p>
                            <p className="mt-3 text-xs text-slate-600">
                                Aus Datenschutzgründen (DSGVO) binden wir keine externen Kartendienste direkt ein.
                            </p>
                        </div>
                        <div className="mt-6">
                            <a
                                href="https://www.openstreetmap.org/search?query=Zu%20den%20M%C3%BChlmatten%208%2079108%20Freiburg"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-block w-full text-center rounded-xl bg-brand-700 px-4 py-3 text-sm font-bold text-white hover:bg-brand-800"
                            >
                                Standort in OpenStreetMap öffnen ↗
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </SiteLayout>
    );
}
