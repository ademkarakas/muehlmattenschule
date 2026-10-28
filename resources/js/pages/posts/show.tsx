import { SiteLayout } from '@/layouts/site-layout';
import type { CategorySummary } from '@/types/school';
import { Head, Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface PostShowProps {
    post: {
        id: number;
        title: string;
        slug: string;
        excerpt: string | null;
        body: string;
        className: string | null;
        publishedAt: string | null;
        authorName: string | null;
        coverUrl: string | null;
        category: CategorySummary | null;
        linkedAlbum: {
            title: string;
            slug: string;
            photosCount: number;
        } | null;
    };
}

/**
 * Detailansicht eines einzelnen Beitrags mit optionaler Verknüpfung zum zugehörigen Fotoalbum.
 */
export default function PostShowPage({ post }: PostShowProps): ReactElement {
    return (
        <SiteLayout>
            <Head title={post.title} />

            <article className="mx-auto max-w-4xl px-4 py-10">
                <Link href="/aktuelles" className="text-sm font-semibold text-brand-700 hover:text-brand-900">
                    ← Zurück zur Übersicht
                </Link>

                <header className="mt-4">
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
                        {post.publishedAt && <span className="text-slate-500">{post.publishedAt}</span>}
                    </div>

                    <h1 className="mt-3 text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
                        {post.title}
                    </h1>
                </header>

                {post.coverUrl && (
                    <div className="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                        <img src={post.coverUrl} alt={post.title} className="max-h-[460px] w-full object-cover" />
                    </div>
                )}

                <div
                    className="mt-8 rounded-2xl border border-slate-200 bg-white p-6 md:p-8 space-y-4 leading-relaxed text-slate-800"
                    dangerouslySetInnerHTML={{ __html: post.body }}
                />

                {post.linkedAlbum && (
                    <div className="mt-8 rounded-2xl border border-brand-200 bg-brand-50 p-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p className="text-xs font-bold uppercase tracking-wider text-brand-700">
                                Zugehöriges Fotoalbum
                            </p>
                            <p className="mt-1 text-lg font-bold text-slate-900">{post.linkedAlbum.title}</p>
                        </div>
                        <Link
                            href={`/galerie/${post.linkedAlbum.slug}`}
                            className="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700"
                        >
                            Fotoalbum ansehen →
                        </Link>
                    </div>
                )}
            </article>
        </SiteLayout>
    );
}

