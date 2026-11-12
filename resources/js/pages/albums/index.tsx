import { Breadcrumbs } from '@/components/breadcrumbs';
import { SiteLayout } from '@/layouts/site-layout';
import type { AlbumCardItem } from '@/types/school';
import { Link } from '@inertiajs/react';
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
            <div className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <header className="mb-6 sm:mb-8">
                    <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 break-words hyphens-auto">
                        Fotoalben & Eindrücke
                    </h1>
                    <p className="mt-2 text-sm sm:text-base text-slate-600">
                        Bilder von Ausflügen, Projekten, Festen und Aktivitäten unserer Klassen.
                    </p>
                </header>

                {albums.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-8 sm:p-10 text-center text-slate-600">
                        Aktuell sind noch keine Fotoalben freigegeben.
                    </div>
                ) : (
                    <div className="grid gap-5 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {albums.map((album) => (
                            <Link
                                key={album.id}
                                href={`/galerie/${album.slug}`}
                                className="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:-translate-y-0.5 hover:shadow-md"
                            >
                                <div className="relative aspect-[16/10] w-full overflow-hidden bg-brand-100">
                                    {album.coverUrl ? (
                                        <img
                                            src={album.coverUrl}
                                            alt={album.title}
                                            loading="lazy"
                                            decoding="async"
                                            className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                    ) : (
                                        <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-600 to-brand-800 p-6 text-center text-white font-semibold">
                                            {album.title}
                                        </div>
                                    )}
                                    {album.photosCount > 0 && (
                                        <span className="absolute bottom-2.5 right-2.5 inline-flex items-center gap-1.5 rounded-full bg-slate-950/75 px-2.5 py-1 text-[11px] font-bold text-white backdrop-blur-xs">
                                            <svg className="h-3.5 w-3.5 text-brand-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                            </svg>
                                            <span>{album.photosCount} Fotos</span>
                                        </span>
                                    )}
                                </div>
                                <div className="p-5 flex-1 flex flex-col">
                                    <div className="flex items-center justify-between text-xs text-slate-500">
                                        <time dateTime={album.eventDateIso ?? undefined}>
                                            {album.eventDate || 'Schuljahr'}
                                        </time>
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
