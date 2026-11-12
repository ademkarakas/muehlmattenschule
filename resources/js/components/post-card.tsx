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
                className={`relative overflow-hidden bg-brand-950/90 shrink-0 ${
                    isHorizontal
                        ? 'aspect-[4/3] sm:aspect-auto sm:w-72 md:w-80 sm:min-h-60'
                        : 'aspect-[4/3] w-full'
                }`}
            >
                {post.coverUrl ? (
                    <>
                        <img
                            src={post.coverUrl}
                            alt=""
                            aria-hidden="true"
                            loading="lazy"
                            className="absolute inset-0 h-full w-full scale-110 object-cover blur-md opacity-45"
                        />
                        <img
                            src={post.coverUrl}
                            alt=""
                            loading="lazy"
                            className="relative h-full w-full object-contain transition duration-300 group-hover:scale-[1.03]"
                        />
                    </>
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

                {Boolean(post.photosCount && post.photosCount > 0) && (
                    <div className="absolute bottom-3 right-3">
                        <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-950/75 px-2.5 py-1 text-[11px] font-bold text-white backdrop-blur-xs shadow-xs">
                            <svg className="h-3.5 w-3.5 text-brand-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                            </svg>
                            <span>{post.photosCount} Fotos</span>
                        </span>
                    </div>
                )}
            </Link>

            <div className="flex flex-1 flex-col justify-between p-4 sm:p-6 min-w-0">
                <div>
                    {post.publishedAt && (
                        <time
                            dateTime={post.publishedAtIso ?? undefined}
                            className="text-xs font-bold text-brand-700"
                        >
                            {post.publishedAt}
                        </time>
                    )}
                    <h3
                        className={`mt-1.5 font-extrabold leading-snug text-slate-900 group-hover:text-brand-700 break-words hyphens-auto ${
                            isHorizontal ? 'text-lg sm:text-2xl' : 'text-base sm:text-lg'
                        }`}
                    >
                        <Link href={`/aktuelles/${post.slug}`}>{post.title}</Link>
                    </h3>
                    {post.excerpt && (
                        <p
                            className={`mt-2 text-sm sm:text-base leading-relaxed text-slate-600 break-words ${
                                isHorizontal ? 'line-clamp-5' : 'line-clamp-4'
                            }`}
                        >
                            {post.excerpt}
                        </p>
                    )}
                </div>

                <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <Link
                        href={`/aktuelles/${post.slug}`}
                        className="inline-flex min-h-9 items-center gap-1.5 py-1 text-xs font-extrabold uppercase tracking-wide text-brand-700 hover:text-brand-900"
                    >
                        Beitrag lesen <span aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>
        </article>
    );
}
