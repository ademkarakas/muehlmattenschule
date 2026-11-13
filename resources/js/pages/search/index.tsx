import { SearchIcon } from '@/components/search-icon';
import { SiteLayout } from '@/layouts/site-layout';
import { Link, router } from '@inertiajs/react';
import { useState, type FormEvent, type ReactElement } from 'react';

interface SearchPostResult {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    className: string | null;
    publishedAt: string | null;
    categoryName: string | null;
    href: string;
}

interface SearchPageResult {
    id: number;
    title: string;
    summary: string | null;
    section: string;
    href: string;
}

interface SearchEventResult {
    id: number;
    title: string;
    dateFormatted: string;
    location: string | null;
    className: string | null;
    href: string;
}

interface SearchDownloadResult {
    id: number;
    title: string;
    category: string | null;
    description: string | null;
    fileUrl: string;
}

interface SearchPersonResult {
    id: number;
    name: string;
    roleTitle: string;
    className: string | null;
    email: string | null;
}

export interface SearchPageProps {
    query: string;
    totalCount: number;
    results: {
        posts: SearchPostResult[];
        pages: SearchPageResult[];
        events: SearchEventResult[];
        downloads: SearchDownloadResult[];
        people: SearchPersonResult[];
    };
}

/**
 * Übergreifende Suchseite (/suche) für Beiträge, Schulseiten, Termine, Downloads und Kollegium.
 */
export default function SearchIndex({ query, totalCount, results }: SearchPageProps): ReactElement {
    const [searchInput, setSearchInput] = useState(query);

    const handleSubmit = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();
        router.get('/suche', { q: searchInput.trim() }, { preserveState: true });
    };

    return (
        <SiteLayout>
            <div className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
                <div className="rounded-3xl bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 p-6 sm:p-10 text-white shadow-md">
                    <span className="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-bold uppercase tracking-wider">
                        Suchfunktion
                    </span>
                    <h1 className="mt-2 text-2xl sm:text-4xl font-extrabold tracking-tight">
                        Schulportal durchsuchen
                    </h1>
                    <p className="mt-2 text-sm sm:text-base text-brand-50 max-w-2xl">
                        Finden Sie schnell Berichte aus den Klassen, Schulseiten, Termine, Formulare oder
                        Ansprechpartnerinnen und Ansprechpartner der Mühlmattenschule.
                    </p>

                    <form onSubmit={handleSubmit} className="mt-6 flex flex-col sm:flex-row gap-3">
                        <label htmlFor="portal-search-input" className="sr-only">
                            Suchbegriff eingeben
                        </label>
                        <div className="relative flex flex-1 items-center">
                            <SearchIcon className="pointer-events-none absolute left-4 h-5 w-5 text-slate-400/75" />
                            <input
                                id="portal-search-input"
                                type="search"
                                value={searchInput}
                                onChange={(e) => setSearchInput(e.target.value)}
                                placeholder="Suchbegriff eingeben (z. B. Crosslauf, Betreuung, Ferienplan, Klassenrat)…"
                                className="w-full rounded-2xl bg-white pl-11 pr-4 py-3.5 text-base font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-3 focus:ring-amber-300"
                            />
                        </div>
                        <button
                            type="submit"
                            className="inline-flex min-h-12 items-center justify-center gap-2 rounded-2xl bg-amber-400 px-6 py-3.5 text-base font-extrabold text-slate-950 shadow-sm hover:bg-amber-300 cursor-pointer transition"
                        >
                            <SearchIcon className="h-5 w-5 opacity-75" />
                            <span>Suchen</span>
                        </button>
                    </form>
                </div>

                {query !== '' && (
                    <div className="mt-8 space-y-8">
                        <p className="text-sm sm:text-base font-semibold text-slate-600">
                            {totalCount === 0
                                ? `Keine Treffer für „${query}“ gefunden.`
                                : `${totalCount} Treffer für „${query}“ gefunden:`}
                        </p>

                        {results.pages.length > 0 && (
                            <section className="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
                                <div className="flex items-center gap-2.5 mb-4">
                                    <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                    </span>
                                    <h2 className="text-lg font-extrabold text-brand-900">
                                        Schulseiten ({results.pages.length})
                                    </h2>
                                </div>
                                <div className="grid gap-3 sm:grid-cols-2">
                                    {results.pages.map((page) => (
                                        <Link
                                            key={page.id}
                                            href={page.href}
                                            className="block rounded-xl border border-slate-200/80 p-4 hover:border-brand-400 hover:bg-brand-50/40 transition"
                                        >
                                            <p className="font-bold text-brand-900">{page.title}</p>
                                            {page.summary && (
                                                <p className="mt-1 text-xs text-slate-600 line-clamp-2">
                                                    {page.summary}
                                                </p>
                                            )}
                                        </Link>
                                    ))}
                                </div>
                            </section>
                        )}

                        {results.posts.length > 0 && (
                            <section className="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
                                <div className="flex items-center gap-2.5 mb-4">
                                    <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                        </svg>
                                    </span>
                                    <h2 className="text-lg font-extrabold text-brand-900">
                                        Nachrichten & Klassenberichte ({results.posts.length})
                                    </h2>
                                </div>
                                <div className="space-y-3">
                                    {results.posts.map((post) => (
                                        <Link
                                            key={post.id}
                                            href={post.href}
                                            className="block rounded-xl border border-slate-200/80 p-4 hover:border-brand-400 hover:bg-brand-50/40 transition"
                                        >
                                            <div className="flex flex-wrap items-center gap-2 text-xs text-slate-500 mb-1">
                                                {post.publishedAt && <span>{post.publishedAt}</span>}
                                                {post.categoryName && (
                                                    <span className="rounded-md bg-brand-50 px-2 py-0.5 font-bold text-brand-800">
                                                        {post.categoryName}
                                                    </span>
                                                )}
                                                {post.className && (
                                                    <span className="rounded-md bg-amber-50 px-2 py-0.5 font-bold text-amber-900">
                                                        {post.className}
                                                    </span>
                                                )}
                                            </div>
                                            <p className="font-bold text-brand-900">{post.title}</p>
                                            <p className="mt-1 text-xs sm:text-sm text-slate-600 line-clamp-2">
                                                {post.excerpt}
                                            </p>
                                        </Link>
                                    ))}
                                </div>
                            </section>
                        )}

                        {results.downloads.length > 0 && (
                            <section className="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
                                <div className="flex items-center gap-2.5 mb-4">
                                    <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </span>
                                    <h2 className="text-lg font-extrabold text-brand-900">
                                        Elternbriefe & Formulare ({results.downloads.length})
                                    </h2>
                                </div>
                                <div className="grid gap-3 sm:grid-cols-2">
                                    {results.downloads.map((dl) => (
                                        <a
                                            key={dl.id}
                                            href={dl.fileUrl}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex items-start gap-3 rounded-xl border border-slate-200/80 p-4 hover:border-brand-400 hover:bg-brand-50/40 transition"
                                        >
                                            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                                <svg className="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </span>
                                            <div className="min-w-0">
                                                <p className="font-bold text-brand-900">{dl.title}</p>
                                                {dl.description && (
                                                    <p className="mt-1 text-xs text-slate-600 line-clamp-2">
                                                        {dl.description}
                                                    </p>
                                                )}
                                            </div>
                                        </a>
                                    ))}
                                </div>
                            </section>
                        )}

                        {results.events.length > 0 && (
                            <section className="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
                                <div className="flex items-center gap-2.5 mb-4">
                                    <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </span>
                                    <h2 className="text-lg font-extrabold text-brand-900">
                                        Termine ({results.events.length})
                                    </h2>
                                </div>
                                <div className="grid gap-3 sm:grid-cols-2">
                                    {results.events.map((ev) => (
                                        <Link
                                            key={ev.id}
                                            href={ev.href}
                                            className="block rounded-xl border border-slate-200/80 p-4 hover:border-brand-400 hover:bg-brand-50/40 transition"
                                        >
                                            <p className="text-xs font-bold text-brand-700">{ev.dateFormatted}</p>
                                            <p className="font-bold text-brand-900">{ev.title}</p>
                                            {ev.location && (
                                                <p className="mt-1 inline-flex items-center gap-1 text-xs text-slate-500">
                                                    <svg className="h-3.5 w-3.5 text-brand-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                    </svg>
                                                    <span>{ev.location}</span>
                                                </p>
                                            )}
                                        </Link>
                                    ))}
                                </div>
                            </section>
                        )}

                        {results.people.length > 0 && (
                            <section className="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
                                <div className="flex items-center gap-2.5 mb-4">
                                    <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15" aria-hidden="true">
                                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </span>
                                    <h2 className="text-lg font-extrabold text-brand-900">
                                        Kollegium & Ansprechpartner ({results.people.length})
                                    </h2>
                                </div>
                                <div className="grid gap-3 sm:grid-cols-2">
                                    {results.people.map((person) => (
                                        <div
                                            key={person.id}
                                            className="rounded-xl border border-slate-200/80 p-4 bg-slate-50/60"
                                        >
                                            <p className="font-bold text-brand-900">{person.name}</p>
                                            <p className="text-xs text-slate-600">{person.roleTitle}</p>
                                            {person.className && (
                                                <p className="mt-1 text-xs font-semibold text-brand-700">
                                                    {person.className}
                                                </p>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </section>
                        )}
                    </div>
                )}
            </div>
        </SiteLayout>
    );
}

