import { SiteLayout } from '@/layouts/site-layout';
import type { AlbumCardItem } from '@/types/school';
import { Head, Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface AlbumsIndexProps {
    albums: AlbumCardItem[];
}

/**
 * Übersichtsseite aller freigegebenen Fotoalben der Schule und Klassen.
 */
export default function AlbumsIndexPage({ albums }: AlbumsIndexProps): ReactElement {
    return (
        <SiteLayout>
            <Head title="Fotoalben" />

            <div className="mx-auto max-w-6xl px-4 py-10">
                <header className="mb-8">
                    <h1 className="text-3xl font-extrabold text-slate-900">Fotoalben & Eindrücke</h1>
                    <p className="mt-2 text-slate-600">
                        Bilder von Ausflügen, Projekten, Festen und Aktivitäten unserer Klassen.
                    </p>
                </header>

                {albums.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-600">
                        Aktuell sind noch keine Fotoalben freigegeben.
                    </div>
                ) : (
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {albums.map((album) => (
                            <Link
                                key={album.id}
                                href={`/galerie/${album.slug}`}
                                className="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:-translate-y-0.5 hover:shadow-md"
                            >
                                <div className="aspect-[16/10] w-full overflow-hidden bg-brand-100">
                                    {album.coverUrl ? (
                                        <img
                                            src={album.coverUrl}
                                            alt={album.title}
                                            loading="lazy"
                                            className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                    ) : (
                                        <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-600 to-brand-800 p-6 text-center text-white font-semibold">
                                            {album.title}
                                        </div>
                                    )}
                                </div>
                                <div className="p-5 flex-1 flex flex-col">
                                    <div className="flex items-center justify-between text-xs text-slate-500">
                                        <span>{album.eventDate || 'Schuljahr'}</span>
                                        {album.className && (
                                            <span className="rounded-full bg-amber-400 px-2.5 py-0.5 font-bold text-slate-950">
                                                {album.className}
                                            </span>
                                        )}
                                    </div>
                                    <h2 className="mt-2 text-lg font-bold text-slate-900 group-hover:text-brand-700">
                                        {album.title}
                                    </h2>
                                    {album.description && (
                                        <p className="mt-1.5 text-sm text-slate-600 line-clamp-2">
                                            {album.description}
                                        </p>
                                    )}
                                </div>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </SiteLayout>
    );
}
