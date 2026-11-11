import type { RubrikenData } from '@/types/school';
import { Link } from '@inertiajs/react';
import { type ReactElement } from 'react';

export interface RubrikenWidgetProps {
    rubriken?: RubrikenData;
    activeCategory?: string | null;
    activeClass?: string | null;
}

/**
 * Zeigt den Kasten „Beiträge nach Rubriken" (wie auf muehlmattenschule.de & ghsfreiburg.edupage.org)
 * mit Beitragszählern für alle Kategorien und Schulklassen.
 */
export function RubrikenWidget({
    rubriken,
    activeCategory = null,
    activeClass = null,
}: RubrikenWidgetProps): ReactElement | null {
    if (!rubriken) {
        return null;
    }

    const isAllActive = !activeCategory && !activeClass;

    return (
        <section
            aria-label="Beiträge nach Rubriken"
            className="rounded-2xl bg-white p-5 sm:p-6 shadow-xs border border-slate-200/80"
        >
            <div className="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                <h2 className="text-base sm:text-lg font-extrabold text-brand-900 flex items-center gap-2">
                    <span aria-hidden="true" className="text-brand-600">
                        🏷️
                    </span>
                    <span>Beiträge nach Rubriken</span>
                </h2>
                <span className="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-bold text-brand-800">
                    {rubriken.totalPosts}
                </span>
            </div>

            <div className="mt-3 space-y-1.5 text-sm">
                <Link
                    href="/aktuelles"
                    className={`flex items-center justify-between rounded-xl px-3 py-2 font-semibold transition ${
                        isAllActive
                            ? 'bg-brand-600 text-white shadow-2xs'
                            : 'text-slate-700 hover:bg-brand-50 hover:text-brand-800'
                    }`}
                >
                    <span>Alle Beiträge</span>
                    <span
                        className={`rounded-full px-2 py-0.5 text-xs font-bold ${
                            isAllActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'
                        }`}
                    >
                        {rubriken.totalPosts}
                    </span>
                </Link>

                {rubriken.categories.map((cat) => {
                    const isActive = activeCategory === cat.slug;
                    return (
                        <Link
                            key={cat.slug}
                            href={`/aktuelles?kategorie=${encodeURIComponent(cat.slug)}`}
                            className={`flex items-center justify-between rounded-xl px-3 py-2 font-semibold transition ${
                                isActive
                                    ? 'bg-brand-600 text-white shadow-2xs'
                                    : 'text-slate-700 hover:bg-brand-50 hover:text-brand-800'
                            }`}
                        >
                            <span className="flex items-center gap-2 min-w-0 truncate">
                                <span
                                    aria-hidden="true"
                                    className="h-2.5 w-2.5 shrink-0 rounded-full"
                                    style={{ backgroundColor: isActive ? '#ffffff' : cat.color }}
                                />
                                <span className="truncate">{cat.name}</span>
                            </span>
                            <span
                                className={`ml-2 shrink-0 rounded-full px-2 py-0.5 text-xs font-bold ${
                                    isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'
                                }`}
                            >
                                {cat.count}
                            </span>
                        </Link>
                    );
                })}
            </div>

            {rubriken.classes.length > 0 && (
                <div className="mt-4 pt-3 border-t border-slate-100">
                    <p className="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-2">
                        Aus den Schulklassen
                    </p>
                    <div className="flex flex-wrap gap-1.5">
                        {rubriken.classes.map((cls) => {
                            const isActive = activeClass === cls.name;
                            return (
                                <Link
                                    key={cls.name}
                                    href={`/aktuelles?klasse=${encodeURIComponent(cls.name)}`}
                                    className={`inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-bold transition ${
                                        isActive
                                            ? 'bg-brand-600 text-white'
                                            : 'bg-brand-50 text-brand-800 hover:bg-brand-100'
                                    }`}
                                >
                                    <span>{cls.name}</span>
                                    <span className="opacity-75">({cls.count})</span>
                                </Link>
                            );
                        })}
                    </div>
                </div>
            )}
        </section>
    );
}

