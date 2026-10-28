import { PostCard } from '@/components/post-card';
import { SiteLayout } from '@/layouts/site-layout';
import type { CategorySummary, PostCardItem } from '@/types/school';
import { Head, Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface PostsIndexProps {
    posts: PostCardItem[];
    pagination: {
        currentPage: number;
        lastPage: number;
        prevUrl: string | null;
        nextUrl: string | null;
    };
    categories: CategorySummary[];
    availableClasses: string[];
    activeCategory: string | null;
    activeClass: string | null;
}

/**
 * Übersichtsseite „Aktuelles & Klassen“ mit Filterung nach Kategorie und Schulklasse.
 */
export default function PostsIndexPage({
    posts,
    pagination,
    categories,
    availableClasses,
    activeCategory,
    activeClass,
}: PostsIndexProps): ReactElement {
    return (
        <SiteLayout>
            <Head title="Aktuelles & Klassen" />

            <div className="mx-auto max-w-6xl px-4 py-10">
                <header className="mb-8">
                    <h1 className="text-3xl font-extrabold text-slate-900">Aktuelles & Klassenaktivitäten</h1>
                    <p className="mt-2 text-slate-600">
                        Berichte aus dem Schulalltag, Ausflüge, Projekte und Neuigkeiten aus unseren Klassen.
                    </p>
                </header>

                {/* Filterleiste für Kategorien und Klassen */}
                <div className="mb-8 flex flex-wrap items-center gap-2">
                    <Link
                        href="/aktuelles"
                        className={`rounded-full px-3.5 py-1.5 text-xs font-semibold transition ${
                            !activeCategory && !activeClass
                                ? 'bg-brand-600 text-white'
                                : 'bg-white border border-slate-200 text-slate-700 hover:bg-brand-50'
                        }`}
                    >
                        Alle Beiträge
                    </Link>

                    {categories.map((cat) => (
                        <Link
                            key={cat.slug}
                            href={`/aktuelles?kategorie=${encodeURIComponent(cat.slug)}`}
                            className={`rounded-full px-3.5 py-1.5 text-xs font-semibold transition ${
                                activeCategory === cat.slug
                                    ? 'bg-brand-600 text-white'
                                    : 'bg-white border border-slate-200 text-slate-700 hover:bg-brand-50'
                            }`}
                        >
                            {cat.name}
                        </Link>
                    ))}

                    {availableClasses.map((className) => (
                        <Link
                            key={className}
                            href={`/aktuelles?klasse=${encodeURIComponent(className)}`}
                            className={`rounded-full px-3.5 py-1.5 text-xs font-semibold transition ${
                                activeClass === className
                                    ? 'bg-amber-500 text-slate-950'
                                    : 'bg-amber-50 border border-amber-200 text-amber-900 hover:bg-amber-100'
                            }`}
                        >
                            {className}
                        </Link>
                    ))}
                </div>

                {posts.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-600">
                        Für diese Auswahl wurden keine Beiträge gefunden.
                    </div>
                ) : (
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {posts.map((post) => (
                            <PostCard key={post.id} post={post} />
                        ))}
                    </div>
                )}

                {pagination.lastPage > 1 && (
                    <nav aria-label="Seitennavigation" className="mt-10 flex items-center justify-center gap-4">
                        {pagination.prevUrl && (
                            <Link
                                href={pagination.prevUrl}
                                className="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-brand-50"
                            >
                                ← Zurück
                            </Link>
                        )}
                        <span className="text-sm text-slate-600">
                            Seite {pagination.currentPage} von {pagination.lastPage}
                        </span>
                        {pagination.nextUrl && (
                            <Link
                                href={pagination.nextUrl}
                                className="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-brand-50"
                            >
                                Weiter →
                            </Link>
                        )}
                    </nav>
                )}
            </div>
        </SiteLayout>
    );
}
