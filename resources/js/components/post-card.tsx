import { Link } from '@inertiajs/react';
import type { PostCardItem } from '@/types/school';
import type { ReactElement } from 'react';

export interface PostCardProps {
    post: PostCardItem;
}

/**
 * Stellt eine einzelne Beitrags-/Aktivitätskarte im EduPage-Stil dar.
 */
export function PostCard({ post }: PostCardProps): ReactElement {
    return (
        <article className="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:-translate-y-0.5 hover:shadow-md">
            <Link href={`/aktuelles/${post.slug}`} className="relative aspect-[16/10] w-full overflow-hidden bg-brand-100">
                {post.coverUrl ? (
                    <img
                        src={post.coverUrl}
                        alt={post.title}
                        loading="lazy"
                        className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-600 to-brand-800 p-6 text-center text-white">
                        <span className="text-sm font-semibold tracking-wide opacity-90">{post.title}</span>
                    </div>
                )}
                <div className="absolute top-3 left-3 flex flex-wrap gap-1.5">
                    {post.category && (
                        <span className="rounded-full bg-white/95 px-2.5 py-0.5 text-xs font-semibold text-brand-800 shadow-xs">
                            {post.category.name}
                        </span>
                    )}
                    {post.className && (
                        <span className="rounded-full bg-amber-400 px-2.5 py-0.5 text-xs font-bold text-slate-900 shadow-xs">
                            {post.className}
                        </span>
                    )}
                </div>
            </Link>

            <div className="flex flex-1 flex-col p-5">
                {post.publishedAt && (
                    <time className="text-xs font-medium text-slate-500">{post.publishedAt}</time>
                )}
                <h3 className="mt-1.5 text-lg font-bold leading-snug text-slate-900 group-hover:text-brand-700">
                    <Link href={`/aktuelles/${post.slug}`}>{post.title}</Link>
                </h3>
                {post.excerpt && (
                    <p className="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600">{post.excerpt}</p>
                )}
                <div className="mt-4 pt-2">
                    <Link
                        href={`/aktuelles/${post.slug}`}
                        className="inline-flex items-center gap-1 text-sm font-semibold text-brand-700 hover:text-brand-900"
                    >
                        Weiterlesen <span aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>
        </article>
    );
}
