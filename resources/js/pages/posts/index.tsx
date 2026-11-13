import { Breadcrumbs } from '@/components/breadcrumbs';
import { PostCard } from '@/components/post-card';
import { Reveal } from '@/components/reveal';
import { RubrikenWidget } from '@/components/rubriken-widget';
import { SiteLayout } from '@/layouts/site-layout';
import type { CategorySummary, PostCardItem, SharedInertiaProps } from '@/types/school';
import { Link, usePage } from '@inertiajs/react';
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
 * Übersichtsseite „Aktuelles & Klassen“ mit Filterung nach Kategorie und Schulklasse
 * sowie der „Beiträge nach Rubriken“-Seitenleiste.
 */
export default function PostsIndexPage({
    posts,
    pagination,
    categories,
    availableClasses,
    activeCategory,
    activeClass,
}: PostsIndexProps): ReactElement {
    const { rubriken } = usePage<SharedInertiaProps>().props;

    return (
        <SiteLayout>
            <div className="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-10 py-7 sm:py-10">
                <Breadcrumbs />

                <header className="mb-6 sm:mb-8">
                    <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 break-words hyphens-auto">
                        Aktuelles & Klassenaktivitäten
                    </h1>
                    <p className="mt-2 text-sm sm:text-base text-slate-600">
                        Berichte aus dem Schulalltag, Ausflüge, Projekte und Neuigkeiten aus unseren Klassen.
                    </p>
                </header>

                {/* Filterleiste für Kategorien und Klassen (horizontal wischbar auf Smartphones) */}
                <div
                    role="region"
                    aria-label="Beiträge filtern"
                    className="-mx-4 mb-6 sm:mb-8 flex items-center gap-2 overflow-x-auto px-4 pb-2 sm:mx-0 sm:flex-wrap sm:overflow-visible sm:px-0 sm:pb-0"
                >
                    <Link
                        href="/aktuelles"
                        aria-current={!activeCategory && !activeClass ? 'true' : undefined}
                        className={`inline-flex min-h-10 shrink-0 items-center rounded-full px-4 py-2 text-xs font-bold transition ${
                            !activeCategory && !activeClass
                                ? 'bg-brand-600 text-white shadow-2xs'
                                : 'bg-white border border-slate-200 text-slate-700 hover:bg-brand-50'
                        }`}
                    >
                        Alle Beiträge
                    </Link>

                    {categories.map((cat) => (
                        <Link
                            key={cat.slug}
                            href={`/aktuelles?kategorie=${encodeURIComponent(cat.slug)}`}
                            aria-current={activeCategory === cat.slug ? 'true' : undefined}
                            className={`inline-flex min-h-10 shrink-0 items-center rounded-full px-4 py-2 text-xs font-bold transition ${
                                activeCategory === cat.slug
                                    ? 'bg-brand-600 text-white shadow-2xs'
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
                            aria-current={activeClass === className ? 'true' : undefined}
                            className={`inline-flex min-h-10 shrink-0 items-center rounded-full px-4 py-2 text-xs font-bold transition ${
                                activeClass === className
                                    ? 'bg-amber-500 text-slate-950 shadow-2xs'
                                    : 'bg-amber-50 border border-amber-200 text-amber-900 hover:bg-amber-100'
                            }`}
                        >
                            {className}
                        </Link>
                    ))}
                </div>

                <div className="grid gap-8 lg:grid-cols-12">
                    <div className="lg:col-span-8">
                        {posts.length === 0 ? (
                            <div className="rounded-2xl border border-slate-200 bg-white p-8 sm:p-10 text-center text-slate-600">
                                Für diese Auswahl wurden keine Beiträge gefunden.
                            </div>
                        ) : (
                            <div className="flex flex-col gap-5 sm:gap-6">
                                {posts.map((post, index) => (
                                    <Reveal key={post.id} variant="fade-up" delay={Math.min(index, 4) * 65}>
                                        <PostCard post={post} layout="horizontal" />
                                    </Reveal>
                                ))}
                            </div>
                        )}

                        {pagination.lastPage > 1 && (
                            <nav
                                aria-label="Seitennavigation"
                                className="mt-8 sm:mt-10 flex items-center justify-center gap-3 sm:gap-4"
                            >
                                {pagination.prevUrl && (
                                    <Link
                                        href={pagination.prevUrl}
                                        className="inline-flex min-h-11 items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold hover:bg-brand-50"
                                    >
                                        ← Zurück
                                    </Link>
                                )}
                                <span className="text-xs sm:text-sm font-medium text-slate-600">
                                    Seite {pagination.currentPage} von {pagination.lastPage}
                                </span>
                                {pagination.nextUrl && (
                                    <Link
                                        href={pagination.nextUrl}
                                        className="inline-flex min-h-11 items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold hover:bg-brand-50"
                                    >
                                        Weiter →
                                    </Link>
                                )}
                            </nav>
                        )}
                    </div>

                    <aside className="lg:col-span-4 space-y-6">
                        <Reveal variant="fade-up" delay={80}>
                            <RubrikenWidget
                                rubriken={rubriken}
                                activeCategory={activeCategory}
                                activeClass={activeClass}
                            />
                        </Reveal>
                    </aside>
                </div>
            </div>
        </SiteLayout>
    );
}
