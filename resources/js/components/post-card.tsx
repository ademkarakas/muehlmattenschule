import { Link } from '@inertiajs/react';
import type { PostCardItem } from '@/types/school';
import type { ReactElement } from 'react';

export interface PostCardProps {
    post: PostCardItem;
    layout?: 'vertical' | 'horizontal';
}

interface PlaceholderTheme {
    gradientClass: string;
    accentGlowClass: string;
    badgeTextClass: string;
    subtitle: string;
    icon: ReactElement;
}

/**
 * Liefert ein semantisch passendes, kontrastreiches Farb- und Ikon-Thema
 * für Beiträge ohne eigenes Vorschaubild (orientiert an Rubrik bzw. Klasse).
 */
function resolvePlaceholderTheme(post: PostCardItem): PlaceholderTheme {
    const slug = post.category?.slug ?? '';
    const hasClass = Boolean(post.className);

    if (slug === 'ferienkalender') {
        return {
            gradientClass: 'from-[#00544a] via-[#007f70] to-[#d97706]',
            accentGlowClass: 'bg-amber-300/25',
            badgeTextClass: 'text-amber-100',
            subtitle: 'Ferien & Schulfrei',
            icon: (
                <svg className="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            ),
        };
    }

    if (slug === 'schulfeste-feiern') {
        return {
            gradientClass: 'from-[#9a3412] via-[#b45309] to-[#d97706]',
            accentGlowClass: 'bg-amber-200/25',
            badgeTextClass: 'text-amber-100',
            subtitle: 'Schulfeste & Feiern',
            icon: (
                <svg className="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                </svg>
            ),
        };
    }

    if (slug === 'sport') {
        return {
            gradientClass: 'from-[#0c4a6e] via-[#0369a1] to-[#029786]',
            accentGlowClass: 'bg-sky-300/25',
            badgeTextClass: 'text-sky-100',
            subtitle: 'Sport & Bewegung',
            icon: (
                <svg className="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                </svg>
            ),
        };
    }

    if (hasClass || slug.startsWith('klasse-')) {
        return {
            gradientClass: 'from-[#064e3b] via-[#047857] to-[#029786]',
            accentGlowClass: 'bg-emerald-300/25',
            badgeTextClass: 'text-emerald-100',
            subtitle: post.className || post.category?.name || 'Aus den Klassen',
            icon: (
                <svg className="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                </svg>
            ),
        };
    }

    if (slug === 'elterninfos') {
        return {
            gradientClass: 'from-[#0f172a] via-[#134e4a] to-[#00695c]',
            accentGlowClass: 'bg-brand-300/20',
            badgeTextClass: 'text-brand-100',
            subtitle: 'Elterninformation',
            icon: (
                <svg className="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                </svg>
            ),
        };
    }

    if (slug === 'konzept-didaktik') {
        return {
            gradientClass: 'from-[#1e293b] via-[#0f5257] to-[#029786]',
            accentGlowClass: 'bg-teal-300/20',
            badgeTextClass: 'text-teal-100',
            subtitle: 'Konzept & Didaktik',
            icon: (
                <svg className="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            ),
        };
    }

    return {
        gradientClass: 'from-brand-900 via-brand-700 to-brand-500',
        accentGlowClass: 'bg-brand-300/25',
        badgeTextClass: 'text-brand-100',
        subtitle: post.category?.name || 'Aus dem Schulleben',
        icon: (
            <svg className="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
            </svg>
        ),
    };
}

/**
 * Moderne Beitrags- und Klassenaktivitätskarte im dunkel-türkisen EduPage-Stil
 * (entweder 1-spaltig breit auf der Startseite oder als Kachel in der Übersicht).
 */
export function PostCard({ post, layout = 'vertical' }: PostCardProps): ReactElement {
    const isHorizontal = layout === 'horizontal';
    const placeholderTheme = resolvePlaceholderTheme(post);

    return (
        <article
            className={`group overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-brand-400 hover:shadow-md ${
                isHorizontal ? 'flex flex-col sm:flex-row sm:h-72' : 'flex flex-col'
            }`}
        >
            <Link
                href={`/aktuelles/${post.slug}`}
                aria-label={post.title}
                className={`relative overflow-hidden shrink-0 ${
                    post.coverUrl
                        ? 'bg-brand-950/90'
                        : 'border-b border-brand-900/10 sm:border-b-0'
                } ${
                    isHorizontal
                        ? `aspect-[4/3] sm:aspect-auto sm:h-full sm:w-72 md:w-80${
                              !post.coverUrl ? ' sm:border-r sm:border-brand-900/10' : ''
                          }`
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
                    <div
                        aria-hidden="true"
                        className={`relative flex h-full w-full flex-col items-center justify-center overflow-hidden bg-gradient-to-br ${placeholderTheme.gradientClass} p-6 text-center text-white select-none`}
                    >
                        {/* Dezente architektonische Licht- und Kreis-Akzente im Hintergrund */}
                        <div
                            className={`pointer-events-none absolute -right-10 -bottom-10 h-40 w-40 rounded-full ${placeholderTheme.accentGlowClass} blur-2xl`}
                        />
                        <div className="pointer-events-none absolute -left-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-xl" />
                        <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_75%_25%,rgba(255,255,255,0.14),transparent_55%)]" />

                        {/* Zentrales thematisches Glas-Emblem statt doppelter Titel-Wiederholung */}
                        <div className="relative z-10 flex flex-col items-center pt-4">
                            <span className="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/30 backdrop-blur-xs shadow-sm transition-transform duration-300 group-hover:scale-105">
                                {placeholderTheme.icon}
                            </span>
                            <span className="mt-3 text-[11px] font-extrabold uppercase tracking-widest text-white/80">
                                Mühlmattenschule
                            </span>
                            <span className={`mt-1 text-sm font-bold ${placeholderTheme.badgeTextClass}`}>
                                {placeholderTheme.subtitle}
                            </span>
                        </div>
                    </div>
                )}

                <div className="absolute top-3 left-3 z-10 flex flex-wrap gap-1.5">
                    {post.category && (
                        <span className="rounded-full bg-white/95 px-3 py-1 text-xs font-bold text-brand-900 ring-1 ring-slate-900/10 shadow-xs">
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
                    <div className="absolute bottom-3 right-3 z-10">
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
                <div className="min-w-0">
                    {post.publishedAt && (
                        <time
                            dateTime={post.publishedAtIso ?? undefined}
                            className="block text-xs font-bold text-brand-700"
                        >
                            {post.publishedAt}
                        </time>
                    )}
                    <h3
                        className={`mt-1.5 font-extrabold leading-snug text-slate-900 group-hover:text-brand-700 break-words hyphens-auto line-clamp-2 ${
                            isHorizontal ? 'text-lg sm:text-2xl' : 'text-base sm:text-lg'
                        }`}
                    >
                        <Link href={`/aktuelles/${post.slug}`}>{post.title}</Link>
                    </h3>
                    {post.excerpt && (
                        <p
                            className={`mt-2 text-sm sm:text-base leading-relaxed text-slate-600 break-words ${
                                isHorizontal ? 'line-clamp-3' : 'line-clamp-4'
                            }`}
                        >
                            {post.excerpt}
                        </p>
                    )}
                </div>

                <div className="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
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

