import { SiteLayout } from '@/layouts/site-layout';
import type { SharedInertiaProps } from '@/types/school';
import { Head, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

/**
 * Impressum der Mühlmattenschule Freiburg-Hochdorf gemäß § 5 DDG.
 */
export default function ImpressumPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;

    return (
        <SiteLayout>
            <Head title="Impressum" />

            <div className="mx-auto max-w-4xl px-4 py-10">
                <h1 className="text-3xl font-extrabold text-slate-900">Impressum</h1>

                <div className="mt-6 rounded-2xl border border-slate-200 bg-white p-6 md:p-8 space-y-5 text-sm leading-relaxed text-slate-700">
                    <section>
                        <h2 className="text-base font-bold text-slate-900">Angaben gemäß § 5 DDG</h2>
                        <p className="mt-1">
                            {school.name} ({school.subtitle})
                            <br />
                            {school.street}
                            <br />
                            {school.zip_city}
                        </p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">Vertretungsberechtigt</h2>
                        <p className="mt-1">Schulleitung: Rektorin Diana Blum</p>
                    </section>

                    <section>
                        <h2 className="text-base font-bold text-slate-900">Kontakt</h2>
                        <p className="mt-1">
                            Telefon: {school.phone}
                            <br />
                            E-Mail: {school.email}
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
                        <p className="mt-1">Staatliches Schulamt Freiburg</p>
                    </section>
                </div>
            </div>
        </SiteLayout>
    );
}
