import { Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface BackButtonProps {
    href: string;
    label: string;
    className?: string;
}

/**
 * Gut sichtbarer Zurück-Button im Stil des Admin-Panel-Zurück-Buttons.
 */
export function BackButton({ href, label, className = '' }: BackButtonProps): ReactElement {
    return (
        <Link
            href={href}
            className={`inline-flex min-h-10 items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2 text-[13.5px] font-medium text-slate-900 shadow-2xs transition duration-150 hover:border-brand-600 hover:bg-slate-50 hover:text-brand-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 ${className}`.trim()}
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                className="h-[1.1rem] w-[1.1rem] shrink-0"
                aria-hidden="true"
            >
                <path
                    fillRule="evenodd"
                    d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                    clipRule="evenodd"
                />
            </svg>
            <span>{label}</span>
        </Link>
    );
}
