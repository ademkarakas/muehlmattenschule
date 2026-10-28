import { SiteLayout } from '@/layouts/site-layout';
import type { SharedInertiaProps } from '@/types/school';
import { Head, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

/**
 * Kontaktseite der Mühlmattenschule ohne externe Karten-Tracker (DSGVO-konform).
 */
export default function ContactPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;

    return (
        <SiteLayout>
            <Head title="Kontakt & Anfahrt" />

            <div className="mx-auto max-w-5xl px-4 py-10">
                <header className="mb-8">
                    <h1 className="text-3xl font-extrabold text-slate-900">Kontakt & Anfahrt</h1>
                    <p className="mt-2 text-slate-600">
                        So erreichen Sie unser Sekretariat und die Schulleitung in Freiburg-Hochdorf.
                    </p>
                </header>

                <div className="grid gap-6 md:grid-cols-2">
                    <section className="rounded-2xl border border-slate-200 bg-white p-6 md:p-8 shadow-xs space-y-4">
                        <h2 className="text-xl font-bold text-brand-900">Anschrift & Sekretariat</h2>
                        <p className="text-slate-700">
                            <strong>{school.name}</strong>
                            <br />
                            {school.subtitle}
                            <br />
                            {school.street}
                            <br />
                            {school.zip_city}
                        </p>
                        <p className="text-slate-700">
                            <strong>Telefon:</strong>{' '}
                            <a href={`tel:${school.phone}`} className="text-brand-700 underline">
                                {school.phone}
                            </a>
                            <br />
                            <strong>E-Mail:</strong>{' '}
                            <a href={`mailto:${school.email}`} className="text-brand-700 underline">
                                {school.email}
                            </a>
                        </p>
                        <p className="text-sm text-slate-600">
                            <strong>Öffnungszeiten des Sekretariats:</strong>
                            <br />
                            {school.office_hours}
                        </p>
                    </section>

                    <section className="rounded-2xl border border-brand-200 bg-brand-50 p-6 md:p-8 flex flex-col justify-between">
                        <div>
                            <h2 className="text-xl font-bold text-brand-900">Lage & Anfahrt</h2>
                            <p className="mt-2 text-sm leading-relaxed text-slate-700">
                                Die Mühlmattenschule liegt im Freiburger Stadtteil Hochdorf und ist sowohl mit den
                                Buslinien der VAG als auch mit dem Fahrrad über die Stadtteilwege gut erreichbar.
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
                                className="inline-block rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700"
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
