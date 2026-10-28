import { SiteLayout } from '@/layouts/site-layout';
import type { SharedInertiaProps } from '@/types/school';
import { Head, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

/**
 * Datenschutzerklärung der Mühlmattenschule gemäß DSGVO.
 */
export default function DatenschutzPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;

    return (
        <SiteLayout>
            <Head title="Datenschutzerklärung" />

            <div className="mx-auto max-w-4xl px-4 py-10">
                <h1 className="text-3xl font-extrabold text-slate-900">Datenschutzerklärung</h1>

                <div className="mt-6 rounded-2xl border border-slate-200 bg-white p-6 md:p-8 space-y-5 text-sm leading-relaxed text-slate-700">
                    <section>
                        <h2 className="text-base font-bold text-slate-900">1. Verantwortliche Stelle</h2>
                        <p className="mt-1">
                            {school.name}, {school.street}, {school.zip_city}
                            <br />
                            Telefon: {school.phone} · E-Mail: {school.email}
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">
                            2. Verzicht auf Tracking, Werbe-Cookies und externe Schriftarten
                        </h2>
                        <p className="mt-1">
                            Diese Website verwendet keine Analyse- oder Tracking-Dienste, keine externen Schriftarten
                            (wie Google Fonts) und keine eingebetteten Inhalte von Drittanbietern. Es werden lediglich
                            technisch notwendige Sitzungs-Cookies verwendet.
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">3. Veröffentlichung von Fotos (DSGVO)</h2>
                        <p className="mt-1">
                            Aufnahmen aus dem Schulleben werden ausschließlich veröffentlicht, wenn für abgebildete
                            Personen die schriftliche Einwilligung der Erziehungsberechtigten gemäß Art. 6 Abs. 1 lit. a
                            DSGVO vorliegt. Eine erteilte Einwilligung kann jederzeit mit Wirkung für die Zukunft
                            gegenüber der Schulleitung widerrufen werden.
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">4. Hosting in Deutschland</h2>
                        <p className="mt-1">
                            Diese Website wird bei der IONOS SE in Rechenzentren innerhalb Deutschlands betrieben.
                        </p>
                    </section>
                </div>
            </div>
        </SiteLayout>
    );
}
