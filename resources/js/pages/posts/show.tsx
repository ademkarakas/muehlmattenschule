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
        adminMeta?: {
            authorName: string;
            publishedAtFull: string | null;
            updatedAtFull: string | null;
            editUrl: string;
        } | null;
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
    const hasInlineImages = /<img\b/i.test(post.body);

    const lightboxImages: LightboxImageItem[] = galleryPhotos.map((photo) => ({
        url: photo.largeUrl,
        alt: photo.alt,
    }));

    return (
        <SiteLayout>
            <article className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
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
                                Veröffentlicht am {post.publishedAt}
                            </time>
                        )}
                    </div>

                    <h1 className="mt-3 text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight break-words hyphens-auto">
                        {post.title}
                    </h1>

                    {post.adminMeta && (
                        <div className="mt-3 flex flex-wrap items-center justify-between gap-2 rounded-xl border border-brand-200 bg-brand-50/80 px-3.5 py-2 text-xs text-brand-900">
                            <span>
                                <strong>Admin-Info:</strong> Verfasst von{' '}
                                <span className="font-bold">{post.adminMeta.authorName}</span>
                                {post.adminMeta.publishedAtFull &&
                                    ` · Veröffentlicht: ${post.adminMeta.publishedAtFull} Uhr`}
                                {post.adminMeta.updatedAtFull &&
                                    ` · Aktualisiert: ${post.adminMeta.updatedAtFull} Uhr`}
                            </span>
                            <a
                                href={post.adminMeta.editUrl}
                                className="font-bold text-brand-700 underline hover:text-brand-900"
                            >
                                Im Adminbereich bearbeiten →
                            </a>
                        </div>
                    )}
                </header>

                {post.coverUrl && !hasInlineImages && (
                    <div className="mt-5 sm:mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <img
                            src={post.coverUrl}
                            alt=""
                            decoding="async"
                            className="mx-auto max-h-[560px] w-auto max-w-full object-contain"
                        />
                    </div>
                )}

                {post.body.trim() !== '' && (
                    <div
                        className="prose-school mt-6 sm:mt-8 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 shadow-2xs space-y-4 text-sm sm:text-base leading-relaxed text-slate-800"
                        dangerouslySetInnerHTML={{ __html: post.body }}
                    />
                )}

                {galleryPhotos.length > 0 && (
                    <section className="mt-6 sm:mt-8 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 shadow-2xs">
                        <div className="mb-4 flex items-center justify-between gap-2">
                            <div className="flex items-center gap-3">
                                <span
                                    className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15"
                                    aria-hidden="true"
                                >
                                    <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                                        Fotostrecke zum Beitrag
                                    </span>
                                    <h2 className="text-lg sm:text-xl font-extrabold text-slate-900">
                                        Bilder & Impressionen ({galleryPhotos.length} Fotos)
                                    </h2>
                                </div>
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
                        <div className="flex items-center gap-3.5">
                            <span
                                className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-700 ring-1 ring-brand-500/15"
                                aria-hidden="true"
                            >
                                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </span>
                            <div>
                                <p className="text-xs font-bold uppercase tracking-wider text-brand-700">
                                    Zugehöriges Fotoalbum ({post.linkedAlbum.photosCount} Fotos)
                                </p>
                                <p className="mt-0.5 text-base sm:text-lg font-bold text-slate-900 break-words">
                                    {post.linkedAlbum.title}
                                </p>
                            </div>
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
