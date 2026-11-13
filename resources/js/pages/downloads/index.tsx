import { Breadcrumbs } from '@/components/breadcrumbs';
import { SiteLayout } from '@/layouts/site-layout';
import type { ReactElement } from 'react';

export interface DownloadItem {
    id: number;
    title: string;
    category: string;
    description: string | null;
    fileUrl: string | null;
}

export interface DownloadsIndexProps {
    downloads: DownloadItem[];
}

/**
 * Übersichtsseite für Elternbriefe, Formulare und wichtige Dokumente zum Herunterladen.
 */
export default function DownloadsIndexPage({ downloads }: DownloadsIndexProps): ReactElement {
    return (
        <SiteLayout>
            <div className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <header className="mb-6 sm:mb-8">
                    <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 break-words hyphens-auto">
                        Downloads, Formulare & Elternbriefe
                    </h1>
                    <p className="mt-2 text-sm sm:text-base text-slate-600">
                        Hier finden Sie aktuelle Elternbriefe, Anträge und wichtige Dokumente als PDF.
                    </p>
                </header>

                {downloads.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-8 sm:p-10 text-center text-slate-600">
                        Aktuell sind noch keine Dokumente hinterlegt.
                    </div>
                ) : (
                    <div className="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white shadow-xs">
                        {downloads.map((item) => (
                            <div
                                key={item.id}
                                className="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 sm:gap-4 p-4 sm:p-5 transition hover:bg-slate-50/60"
                            >
                                <div className="flex items-start gap-3.5 min-w-0">
                                    <span
                                        className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15"
                                        aria-hidden="true"
                                    >
                                        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </span>
                                    <div className="min-w-0">
                                        <span className="inline-block rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-semibold text-brand-800">
                                            {item.category}
                                        </span>
                                        <h2 className="mt-1.5 text-base font-bold text-slate-900 break-words">
                                            {item.title}
                                        </h2>
                                        {item.description && (
                                            <p className="mt-0.5 text-sm text-slate-600 break-words">{item.description}</p>
                                        )}
                                    </div>
                                </div>
                                {item.fileUrl && (
                                    <a
                                        href={item.fileUrl}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="inline-flex min-h-11 w-full sm:w-auto shrink-0 items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700"
                                    >
                                        <svg className="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        <span>Herunterladen</span>
                                    </a>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </SiteLayout>
    );
}

