import { Link } from '@inertiajs/react';
import type { PostCardItem } from '@/types/school';
import type { ReactElement } from 'react';

export interface PostCardProps {
    post: PostCardItem;
    layout?: 'vertical' | 'horizontal';
}

/**
 * Moderne Beitrags- und Klassenaktivitätskarte im dunkel-türkisen EduPage-Stil
 * (entweder 1-spaltig breit auf der Startseite oder als Kachel in der Übersicht).
 */
export function PostCard({ post, layout = 'vertical' }: PostCardProps): ReactElement {
    const isHorizontal = layout === 'horizontal';

    return (
        <article
            className={`group overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-brand-400 hover:shadow-md ${
                isHorizontal ? 'flex flex-col sm:flex-row' : 'flex flex-col'
            }`}
        >
            <Link
                href={`/aktuelles/${post.slug}`}
                className={`relative overflow-hidden bg-brand-100 shrink-0 ${
                    isHorizontal ? 'aspect-[16/10] sm:aspect-auto sm:w-72 md:w-80' : 'aspect-[16/10] w-full'
                }`}
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

            <div className="flex flex-1 flex-col justify-between p-5 sm:p-6">
                <div>
                    {post.publishedAt && (
                        <time className="text-xs font-bold text-brand-700">{post.publishedAt}</time>
                    )}
                    <h3
                        className={`mt-1.5 font-extrabold leading-snug text-slate-900 group-hover:text-brand-700 ${
                            isHorizontal ? 'text-xl sm:text-2xl' : 'text-lg'
                        }`}
                    >
                        <Link href={`/aktuelles/${post.slug}`}>{post.title}</Link>
                    </h3>
                    {post.excerpt && (
                        <p className="mt-2.5 line-clamp-3 text-sm sm:text-base leading-relaxed text-slate-600">
                            {post.excerpt}
                        </p>
                    )}
                </div>

                <div className="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                    <Link
                        href={`/aktuelles/${post.slug}`}
                        className="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wide text-brand-700 hover:text-brand-900"
                    >
                        Beitrag lesen <span aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>
        </article>
    );
}
