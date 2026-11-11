import type { BreadcrumbItem, SharedInertiaProps } from '@/types/school';
import { Link, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface BreadcrumbsProps {
    items?: BreadcrumbItem[];
}

/**
 * Semantische Brotkrumen-Navigation (passend zum Schema.org BreadcrumbList JSON-LD)
 * für bessere Orientierung und interne Verlinkung.
 */
export function Breadcrumbs({ items }: BreadcrumbsProps): ReactElement | null {
    const { seo } = usePage<SharedInertiaProps>().props;
    const crumbs = items ?? seo?.breadcrumbs ?? [];

    if (crumbs.length <= 1) {
        return null;
    }

    return (
        <nav aria-label="Brotkrumen-Navigation" className="mb-4 sm:mb-5 text-xs font-semibold text-slate-500">
            <ol className="flex flex-wrap items-center gap-x-1.5 gap-y-1">
                {crumbs.map((crumb, index) => {
                    const isLast = index === crumbs.length - 1;
                    return (
                        <li key={`${crumb.label}-${index}`} className="flex items-center gap-1.5 min-w-0">
                            {index > 0 && (
                                <span aria-hidden="true" className="text-slate-400 shrink-0">
                                    /
                                </span>
                            )}
                            {crumb.href && !isLast ? (
                                <Link
                                    href={crumb.href}
                                    className="inline-flex items-center py-0.5 text-brand-700 hover:text-brand-900 hover:underline"
                                >
                                    {crumb.label}
                                </Link>
                            ) : (
                                <span
                                    aria-current={isLast ? 'page' : undefined}
                                    className={`truncate max-w-[240px] sm:max-w-none ${
                                        isLast ? 'font-bold text-slate-700' : 'text-slate-600'
                                    }`}
                                >
                                    {crumb.label}
                                </span>
                            )}
                        </li>
                    );
                })}
            </ol>
        </nav>
    );
}
