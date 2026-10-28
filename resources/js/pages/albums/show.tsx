import { SiteLayout } from '@/layouts/site-layout';
import { Head, Link } from '@inertiajs/react';
import { useState, type ReactElement } from 'react';

export interface AlbumPhoto {
    id: number;
    thumbUrl: string;
    largeUrl: string;
    alt: string;
}

export interface AlbumShowProps {
    album: {
        id: number;
        title: string;
        slug: string;
        className: string | null;
        description: string | null;
        eventDate: string | null;
        photos: AlbumPhoto[];
    };
}

/**
 * Einzelansicht eines Fotoalbums mit barrierefreier Lightbox-Vergrößerung.
 */
export default function AlbumShowPage({ album }: AlbumShowProps): ReactElement {
    const [activeIndex, setActiveIndex] = useState<number | null>(null);
    const activePhoto = activeIndex !== null ? album.photos[activeIndex] : null;

    return (
        <SiteLayout>
            <Head title={album.title} />

            <div className="mx-auto max-w-6xl px-4 py-10">
                <Link href="/galerie" className="text-sm font-semibold text-brand-700 hover:text-brand-900">
                    ← Alle Fotoalben
                </Link>

                <header className="mt-4 mb-8">
                    <div className="flex flex-wrap items-center gap-2 text-xs font-semibold">
                        {album.eventDate && <span className="text-slate-500">{album.eventDate}</span>}
                        {album.className && (
                            <span className="rounded-full bg-amber-400 px-3 py-0.5 text-slate-950">
                                {album.className}
                            </span>
                        )}
                    </div>
                    <h1 className="mt-2 text-3xl font-extrabold text-slate-900">{album.title}</h1>
                    {album.description && <p className="mt-2 max-w-3xl text-slate-600">{album.description}</p>}
                </header>

                {album.photos.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-600">
                        Diesem Album wurden noch keine Fotos hinzugefügt.
                    </div>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {album.photos.map((photo, index) => (
                            <button
                                key={photo.id}
                                type="button"
                                onClick={() => setActiveIndex(index)}
                                className="group overflow-hidden rounded-2xl border border-slate-200 bg-white aspect-[4/3] focus:outline-2 focus:outline-brand-600"
                            >
                                <img
                                    src={photo.thumbUrl}
                                    alt={`${photo.alt} (${index + 1})`}
                                    loading="lazy"
                                    className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                />
                            </button>
                        ))}
                    </div>
                )}

                {activePhoto && activeIndex !== null && (
                    <div
                        role="dialog"
                        aria-modal="true"
                        aria-label={album.title}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 p-4"
                        onClick={() => setActiveIndex(null)}
                    >
                        <div
                            className="relative max-w-5xl w-full flex flex-col items-center"
                            onClick={(e) => e.stopPropagation()}
                        >
                            <img
                                src={activePhoto.largeUrl}
                                alt={activePhoto.alt}
                                className="max-h-[80vh] w-auto rounded-xl object-contain"
                            />
                            <div className="mt-4 flex items-center gap-4 text-white text-sm">
                                <button
                                    type="button"
                                    disabled={activeIndex === 0}
                                    onClick={() => setActiveIndex((i) => (i !== null && i > 0 ? i - 1 : i))}
                                    className="rounded-lg bg-white/15 px-3 py-1.5 disabled:opacity-40"
                                >
                                    ← Vorheriges Bild
                                </button>
                                <span>
                                    {activeIndex + 1} / {album.photos.length}
                                </span>
                                <button
                                    type="button"
                                    disabled={activeIndex === album.photos.length - 1}
                                    onClick={() =>
                                        setActiveIndex((i) =>
                                            i !== null && i < album.photos.length - 1 ? i + 1 : i,
                                        )
                                    }
                                    className="rounded-lg bg-white/15 px-3 py-1.5 disabled:opacity-40"
                                >
                                    Nächstes Bild →
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setActiveIndex(null)}
                                    className="rounded-lg bg-brand-600 px-3 py-1.5 font-semibold"
                                >
                                    Schließen ✕
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </SiteLayout>
    );
}

