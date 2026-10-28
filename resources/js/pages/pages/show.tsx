import { SiteLayout } from '@/layouts/site-layout';
import type { PersonItem } from '@/types/school';
import { Head } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface PageBlock {
    type: 'rich_text' | 'info_box' | 'people_list';
    data: {
        heading?: string;
        body?: string;
        title?: string;
        text?: string;
        group?: string;
    };
}

export interface PageShowProps {
    page: {
        title: string;
        slug: string;
        section: string;
        summary: string | null;
        content: PageBlock[];
    };
    people: PersonItem[];
}

/**
 * Stellt eine über den Admin-Baukasten gepflegte Schulseite dar (inkl. Kollegiums-Karten).
 */
export default function PageShow({ page, people }: PageShowProps): ReactElement {
    return (
        <SiteLayout>
            <Head title={page.title} />

            <div className="mx-auto max-w-5xl px-4 py-10">
                <header className="mb-8 border-b border-slate-200 pb-6">
                    <span className="text-xs font-bold uppercase tracking-wider text-brand-700">
                        {page.section === 'unsere-schule' ? 'Unsere Schule' : 'Schulleben'}
                    </span>
                    <h1 className="mt-1 text-3xl md:text-4xl font-extrabold text-slate-900">{page.title}</h1>
                    {page.summary && <p className="mt-2 text-lg text-slate-600">{page.summary}</p>}
                </header>

                <div className="space-y-8">
                    {page.content.map((block, index) => {
                        if (block.type === 'rich_text') {
                            return (
                                <section
                                    key={index}
                                    className="rounded-2xl border border-slate-200 bg-white p-6 md:p-8 shadow-2xs"
                                >
                                    {block.data.heading && (
                                        <h2 className="mb-3 text-xl font-bold text-brand-900">
                                            {block.data.heading}
                                        </h2>
                                    )}
                                    {block.data.body && (
                                        <div
                                            className="space-y-3 leading-relaxed text-slate-800"
                                            dangerouslySetInnerHTML={{ __html: block.data.body }}
                                        />
                                    )}
                                </section>
                            );
                        }

                        if (block.type === 'info_box') {
                            return (
                                <aside
                                    key={index}
                                    className="rounded-2xl border border-brand-200 bg-brand-50 p-6 text-brand-950"
                                >
                                    <h3 className="font-bold text-lg">{block.data.title}</h3>
                                    <p className="mt-1.5 text-sm leading-relaxed">{block.data.text}</p>
                                </aside>
                            );
                        }

                        if (block.type === 'people_list') {
                            const filtered =
                                !block.data.group || block.data.group === 'all'
                                    ? people
                                    : people.filter((person) => person.group === block.data.group);

                            return (
                                <section key={index} className="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
                                    {filtered.map((person) => (
                                        <div
                                            key={person.id}
                                            className="rounded-2xl border border-slate-200 bg-white p-5 shadow-2xs flex items-center gap-4"
                                        >
                                            <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-brand-100 text-brand-800 font-bold text-lg overflow-hidden">
                                                {person.avatarUrl ? (
                                                    <img
                                                        src={person.avatarUrl}
                                                        alt={person.name}
                                                        className="h-full w-full object-cover"
                                                    />
                                                ) : (
                                                    person.name.slice(0, 1)
                                                )}
                                            </div>
                                            <div>
                                                <p className="font-bold text-slate-900">{person.name}</p>
                                                <p className="text-xs font-medium text-brand-700">{person.roleTitle}</p>
                                                {person.className && (
                                                    <p className="text-xs text-slate-500">{person.className}</p>
                                                )}
                                                {person.email && (
                                                    <a
                                                        href={`mailto:${person.email}`}
                                                        className="mt-1 inline-block text-xs text-slate-600 underline"
                                                    >
                                                        {person.email}
                                                    </a>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </section>
                            );
                        }

                        return null;
                    })}
                </div>
            </div>
        </SiteLayout>
    );
}

