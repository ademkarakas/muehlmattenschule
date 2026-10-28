import { PhotoLightbox } from '@/components/photo-lightbox';
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
 * Einzelansicht eines Fotoalbums mit moderner Vollbild-Lightbox
 * (Tastatur-Pfeiltasten sowie Links-/Rechts-Pfeile am Bildrand).
 */
export default function AlbumShowPage({ album }: AlbumShowProps): ReactElement {
    const [activeIndex, setActiveIndex] = useState<number | null>(null);

    const lightboxImages = album.photos.map((photo, index) => ({
        url: photo.largeUrl,
        alt: `${photo.alt} (${index + 1})`,
    }));

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
                                className="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white aspect-[4/3] shadow-2xs transition hover:-translate-y-0.5 hover:border-brand-400 hover:shadow-md focus:outline-2 focus:outline-brand-600 cursor-pointer"
                            >
                                <img
                                    src={photo.thumbUrl}
                                    alt={`${photo.alt} (${index + 1})`}
                                    loading="lazy"
                                    className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                />
                                <div className="pointer-events-none absolute inset-0 bg-slate-950/0 transition group-hover:bg-slate-950/15" />
                            </button>
                        ))}
                    </div>
                )}

                <PhotoLightbox
                    images={lightboxImages}
                    activeIndex={activeIndex}
                    onClose={() => setActiveIndex(null)}
                    onChangeIndex={setActiveIndex}
                />
            </div>
        </SiteLayout>
    );
}
