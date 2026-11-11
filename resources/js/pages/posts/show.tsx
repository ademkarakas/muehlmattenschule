import { Breadcrumbs } from '@/components/breadcrumbs';
import { PhotoLightbox, type LightboxImageItem } from '@/components/photo-lightbox';
import { SiteLayout } from '@/layouts/site-layout';
import type { CategorySummary, GalleryPhotoItem } from '@/types/school';
import { Link } from '@inertiajs/react';
import { useState, type ReactElement } from 'react';

export interface PostShowProps {
    post: {
        id: number;
        title: string;
        slug: string;
        excerpt: string | null;
        body: string;
        className: string | null;
        publishedAt: string | null;
        publishedAtIso?: string | null;
        authorName: string | null;
        coverUrl: string | null;
        category: CategorySummary | null;
        galleryPhotos?: GalleryPhotoItem[];
        linkedAlbum: {
            title: string;
            slug: string;
            photosCount: number;
        } | null;
    };
}

/**
 * Detailansicht eines einzelnen Beitrags mit integrierter Fotogalerie (Lightbox) und optionaler Verknüpfung zum Fotoalbum.
 */
export default function PostShowPage({ post }: PostShowProps): ReactElement {
    const [activeLightboxIndex, setActiveLightboxIndex] = useState<number | null>(null);
    const galleryPhotos = post.galleryPhotos ?? [];

    const lightboxImages: LightboxImageItem[] = galleryPhotos.map((photo) => ({
        url: photo.largeUrl,
        alt: photo.alt,
    }));

    return (
        <SiteLayout>
            <article className="mx-auto max-w-4xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <Link
                    href="/aktuelles"
                    className="inline-flex min-h-10 items-center py-1 text-sm font-semibold text-brand-700 hover:text-brand-900"
                >
                    ← Zurück zur Übersicht
                </Link>

                <header className="mt-3 sm:mt-4">
                    <div className="flex flex-wrap items-center gap-2 text-xs font-semibold">
                        {post.category && (
                            <span className="rounded-full bg-brand-100 px-3 py-1 text-brand-800">
                                {post.category.name}
                            </span>
                        )}
                        {post.className && (
                            <span className="rounded-full bg-amber-400 px-3 py-1 text-slate-950">
                                {post.className}
                            </span>
                        )}
                        {post.publishedAt && (
                            <time
                                dateTime={post.publishedAtIso ?? undefined}
                                className="text-slate-500"
                            >
                                {post.publishedAt}
                            </time>
                        )}
                    </div>

                    <h1 className="mt-3 text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight break-words hyphens-auto">
                        {post.title}
                    </h1>
                </header>

                {post.coverUrl && (
                    <div className="mt-5 sm:mt-6 overflow-hidden rounded-2xl border border-slate-200">
                        <img
                            src={post.coverUrl}
                            alt={post.title}
                            decoding="async"
                            className="max-h-[460px] w-full object-cover"
                        />
                    </div>
                )}

                <div
                    className="prose-school mt-6 sm:mt-8 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 space-y-4 text-sm sm:text-base leading-relaxed text-slate-800"
                    dangerouslySetInnerHTML={{ __html: post.body }}
                />

                {galleryPhotos.length > 0 && (
                    <section className="mt-6 sm:mt-8 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 shadow-2xs">
                        <div className="mb-4 flex items-center justify-between gap-2">
                            <div>
                                <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                                    Fotostrecke zum Beitrag
                                </span>
                                <h2 className="text-lg sm:text-xl font-extrabold text-slate-900">
                                    Bilder & Impressionen ({galleryPhotos.length} Fotos)
                                </h2>
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 sm:gap-4">
                            {galleryPhotos.map((photo, index) => (
                                <button
                                    key={photo.id}
                                    type="button"
                                    onClick={() => setActiveLightboxIndex(index)}
                                    className="group relative aspect-[4/3] overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-2xs transition hover:-translate-y-0.5 hover:border-brand-400 hover:shadow-md cursor-pointer"
                                >
                                    <img
                                        src={photo.thumbUrl}
                                        alt={photo.alt}
                                        loading="lazy"
                                        className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    />
                                </button>
                            ))}
                        </div>
                    </section>
                )}

                {post.linkedAlbum && (
                    <div className="mt-6 sm:mt-8 rounded-2xl border border-brand-200 bg-brand-50 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p className="text-xs font-bold uppercase tracking-wider text-brand-700">
                                Zugehöriges Fotoalbum ({post.linkedAlbum.photosCount} Fotos)
                            </p>
                            <p className="mt-1 text-base sm:text-lg font-bold text-slate-900 break-words">
                                {post.linkedAlbum.title}
                            </p>
                        </div>
                        <Link
                            href={`/galerie/${post.linkedAlbum.slug}`}
                            className="inline-flex min-h-11 w-full sm:w-auto items-center justify-center rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700"
                        >
                            Fotoalbum ansehen →
                        </Link>
                    </div>
                )}

                <PhotoLightbox
                    images={lightboxImages}
                    activeIndex={activeLightboxIndex}
                    onClose={() => setActiveLightboxIndex(null)}
                    onChangeIndex={setActiveLightboxIndex}
                />
            </article>
        </SiteLayout>
    );
}
