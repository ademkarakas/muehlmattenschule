import { SiteLayout } from '@/layouts/site-layout';
import { Head } from '@inertiajs/react';
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
            <Head title="Downloads & Formulare" />

            <div className="mx-auto max-w-5xl px-4 py-10">
                <header className="mb-8">
                    <h1 className="text-3xl font-extrabold text-slate-900">Downloads, Formulare & Elternbriefe</h1>
                    <p className="mt-2 text-slate-600">
                        Hier finden Sie aktuelle Elternbriefe, Anträge und wichtige Dokumente als PDF.
                    </p>
                </header>

                {downloads.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-600">
                        Aktuell sind noch keine Dokumente hinterlegt.
                    </div>
                ) : (
                    <div className="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white shadow-xs">
                        {downloads.map((item) => (
                            <div key={item.id} className="flex flex-wrap items-center justify-between gap-4 p-5">
                                <div>
                                    <span className="rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-semibold text-brand-800">
                                        {item.category}
                                    </span>
                                    <h2 className="mt-1 text-base font-bold text-slate-900">{item.title}</h2>
                                    {item.description && (
                                        <p className="mt-0.5 text-sm text-slate-600">{item.description}</p>
                                    )}
                                </div>
                                {item.fileUrl && (
                                    <a
                                        href={item.fileUrl}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700"
                                    >
                                        Herunterladen ↓
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

