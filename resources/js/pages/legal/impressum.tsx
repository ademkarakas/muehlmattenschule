import { Breadcrumbs } from '@/components/breadcrumbs';
import { SiteLayout } from '@/layouts/site-layout';
import type { SharedInertiaProps } from '@/types/school';
import { usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

/**
 * Offizielles Impressum der Mühlmattenschule Freiburg-Hochdorf gemäß § 5 DDG.
 */
export default function ImpressumPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;
    const phoneHref = `tel:${school.phone.replace(/\s+/g, '')}`;

    return (
        <SiteLayout>
            <div className="mx-auto max-w-4xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 break-words hyphens-auto">
                    Impressum
                </h1>

                <div className="mt-5 sm:mt-6 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 space-y-5 text-sm leading-relaxed text-slate-700 break-words">
                    <p>
                        Dies ist der offizielle Internetauftritt der Grundschule Mühlmattenschule. Er stellt
                        Informationen aus allen Bereichen des Schullebens dar. Alle Seiten und Inhalte dieser
                        Internetpräsenz, insbesondere Texte, Fotografien und Grafiken, sind urheberrechtlich geschützt.
                    </p>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">Verantwortlich für diese Internetseite</h2>
                        <p className="mt-1">
                            <strong>{school.name}</strong> ({school.subtitle})
                            <br />
                            {school.street}
                            <br />
                            {school.zip_city}
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">Schulleitung & Vertretung</h2>
                        <p className="mt-1">
                            Rektorin: Diana Blum
                            <br />
                            Konrektorin: Sabine Caputo
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">Kontakt</h2>
                        <p className="mt-1">
                            Telefon:{' '}
                            <a href={phoneHref} className="font-semibold text-brand-700 hover:underline">
                                {school.phone}
                            </a>
                            <br />
                            Telefax: +49 7665 932081
                            <br />
                            E-Mail:{' '}
                            <a
                                href={`mailto:${school.email}`}
                                className="font-semibold text-brand-700 hover:underline break-all"
                            >
                                {school.email}
                            </a>
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">Schulträger</h2>
                        <p className="mt-1">
                            Stadt Freiburg im Breisgau – Amt für Schule und Bildung
                            <br />
                            Rathausplatz 2–4, 79098 Freiburg im Breisgau
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">Zuständige Aufsichtsbehörde</h2>
                        <p className="mt-1">
                            Staatliches Schulamt Freiburg
                            <br />
                            Ottmannstraße 22, 79100 Freiburg im Breisgau
                        </p>
                    </section>
                </div>
            </div>
        </SiteLayout>
    );
}
