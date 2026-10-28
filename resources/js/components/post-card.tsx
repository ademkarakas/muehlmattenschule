import { Link } from '@inertiajs/react';
import type { PostCardItem } from '@/types/school';
import type { ReactElement } from 'react';

export interface PostCardProps {
    post: PostCardItem;
}

/**
 * Moderne Beitrags- und Klassenaktivitätskarte im bläulich-türkisen EduPage-Stil.
 */
export function PostCard({ post }: PostCardProps): ReactElement {
    return (
        <article className="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-xs transition duration-200 hover:-translate-y-1 hover:border-brand-300 hover:shadow-lg">
            <Link
                href={`/aktuelles/${post.slug}`}
                className="relative aspect-[16/10] w-full overflow-hidden bg-brand-100"
            >
                {post.coverUrl ? (
                    <img
                        src={post.coverUrl}
                        alt={post.title}
                        loading="lazy"
                        className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-6 text-center text-white">
                        <span className="rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-wider">
                            Mühlmattenschule
                        </span>
                        <span className="mt-2 line-clamp-2 text-base font-bold leading-snug">{post.title}</span>
                    </div>
                )}

                <div className="absolute top-3 left-3 flex flex-wrap gap-1.5">
                    {post.category && (
                        <span className="rounded-full bg-white/95 px-3 py-1 text-xs font-bold text-brand-800 shadow-xs">
                            {post.category.name}
                        </span>
                    )}
                    {post.className && (
                        <span className="rounded-full bg-amber-400 px-3 py-1 text-xs font-extrabold text-slate-950 shadow-xs">
                            {post.className}
                        </span>
                    )}
                </div>
            </Link>

            <div className="flex flex-1 flex-col p-5">
                {post.publishedAt && (
                    <time className="text-xs font-semibold text-brand-700">{post.publishedAt}</time>
                )}
                <h3 className="mt-1.5 text-lg font-extrabold leading-snug text-slate-900 group-hover:text-brand-700">
                    <Link href={`/aktuelles/${post.slug}`}>{post.title}</Link>
                </h3>
                {post.excerpt && (
                    <p className="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600">{post.excerpt}</p>
                )}
                <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <Link
                        href={`/aktuelles/${post.slug}`}
                        className="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wide text-brand-700 hover:text-brand-900"
                    >
                        Beitrag lesen <span aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>
        </article>
    );
}
