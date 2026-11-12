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
                        <div className="flex items-center gap-2.5">
                            <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                </svg>
                            </span>
                            <h2 className="text-base font-bold text-slate-900">Verantwortlich für diese Internetseite</h2>
                        </div>
                        <p className="mt-2 pl-10.5">
                            <strong>{school.name}</strong> ({school.subtitle})
                            <br />
                            {school.street}
                            <br />
                            {school.zip_city}
                        </p>
                    </section>

                    <section>
                        <div className="flex items-center gap-2.5">
                            <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <h2 className="text-base font-bold text-slate-900">Schulleitung & Vertretung</h2>
                        </div>
                        <p className="mt-2 pl-10.5">
                            Rektorin: Diana Blum
                            <br />
                            Konrektorin: Sabine Caputo
                        </p>
                    </section>

                    <section>
                        <div className="flex items-center gap-2.5">
                            <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <h2 className="text-base font-bold text-slate-900">Kontakt</h2>
                        </div>
                        <p className="mt-2 pl-10.5">
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
                        <div className="flex items-center gap-2.5">
                            <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                </svg>
                            </span>
                            <h2 className="text-base font-bold text-slate-900">Schulträger</h2>
                        </div>
                        <p className="mt-2 pl-10.5">
                            Stadt Freiburg im Breisgau – Amt für Schule und Bildung
                            <br />
                            Rathausplatz 2–4, 79098 Freiburg im Breisgau
                        </p>
                    </section>

                    <section>
                        <div className="flex items-center gap-2.5">
                            <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            </span>
                            <h2 className="text-base font-bold text-slate-900">Zuständige Aufsichtsbehörde</h2>
                        </div>
                        <p className="mt-2 pl-10.5">
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
