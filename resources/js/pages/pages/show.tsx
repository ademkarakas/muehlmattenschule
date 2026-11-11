import { Breadcrumbs } from '@/components/breadcrumbs';
import { PhotoLightbox, type LightboxImageItem } from '@/components/photo-lightbox';
import { SiteLayout } from '@/layouts/site-layout';
import type { PersonItem } from '@/types/school';
import { useState, type ReactElement } from 'react';

export interface PageBlock {
    type:
        | 'rich_text'
        | 'info_box'
        | 'people_list'
        | 'image_banner'
        | 'image_gallery'
        | 'accordion'
        | 'downloads_box';
    data: {
        heading?: string;
        body?: string;
        title?: string;
        text?: string;
        group?: string;
        image_url?: string;
        caption?: string;
        image_1_url?: string;
        image_1_caption?: string;
        image_2_url?: string;
        image_2_caption?: string;
        image_3_url?: string;
        image_3_caption?: string;
        items?: Array<{
            question?: string;
            answer?: string;
            label?: string;
            url?: string;
        }>;
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

const SECTION_LABELS: Record<string, string> = {
    'unsere-schule': 'Unsere Schule',
    'konzepte-projekte': 'Konzepte & Projekte',
    angebote: 'Angebote & Betreuung',
    schulleben: 'Schulleben & Angebote',
};

/**
 * Stellt eine über den Admin-Baukasten gepflegte Schulseite dar (inkl. Fotos, Galerien, Akkordeon, Downloads und Kollegiums-Karten).
 */
export default function PageShow({ page, people }: PageShowProps): ReactElement {
    const [activeLightboxIndex, setActiveLightboxIndex] = useState<number | null>(null);

    const allPageImages: LightboxImageItem[] = [];
    for (const block of page.content) {
        if (block.type === 'image_banner' && block.data.image_url) {
            allPageImages.push({
                url: block.data.image_url,
                alt: block.data.caption || page.title,
            });
        } else if (block.type === 'image_gallery') {
            const galleryItems = [
                { url: block.data.image_1_url, caption: block.data.image_1_caption },
                { url: block.data.image_2_url, caption: block.data.image_2_caption },
                { url: block.data.image_3_url, caption: block.data.image_3_caption },
            ];
            for (const item of galleryItems) {
                if (item.url) {
                    allPageImages.push({
                        url: item.url,
                        alt: item.caption || page.title,
                    });
                }
            }
        }
    }

    const openImageInLightbox = (url: string): void => {
        const idx = allPageImages.findIndex((img) => img.url === url);
        if (idx !== -1) {
            setActiveLightboxIndex(idx);
        }
    };

    const sectionBadge = SECTION_LABELS[page.section] ?? 'Schulleben & Angebote';

    return (
        <SiteLayout>
            <div className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <header className="mb-6 sm:mb-8 border-b border-slate-200 pb-5 sm:pb-6">
                    <span className="inline-block rounded-full bg-brand-100 px-3 py-1 text-xs font-extrabold uppercase tracking-wider text-brand-800">
                        {sectionBadge}
                    </span>
                    <h1 className="mt-2.5 text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 break-words hyphens-auto">
                        {page.title}
                    </h1>
                    {page.summary && (
                        <p className="mt-2 max-w-3xl text-base sm:text-lg text-slate-600 break-words">{page.summary}</p>
                    )}
                </header>

                <div className="space-y-6 sm:space-y-8">
                    {page.content.map((block, index) => {
                        if (block.type === 'image_banner' && block.data.image_url) {
                            const bannerUrl = block.data.image_url;
                            return (
                                <figure
                                    key={index}
                                    className="overflow-hidden rounded-2xl sm:rounded-3xl border border-slate-200 bg-white shadow-sm"
                                >
                                    <button
                                        type="button"
                                        onClick={() => openImageInLightbox(bannerUrl)}
                                        className="group block max-h-[460px] w-full overflow-hidden bg-slate-100 cursor-pointer"
                                    >
                                        <img
                                            src={bannerUrl}
                                            alt={block.data.caption || page.title}
                                            className="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                                        />
                                    </button>
                                    {block.data.caption && (
                                        <figcaption className="border-t border-slate-100 bg-slate-50/80 px-4 sm:px-5 py-3 text-xs font-medium text-slate-600">
                                            {block.data.caption}
                                        </figcaption>
                                    )}
                                </figure>
                            );
                        }

                        if (block.type === 'image_gallery') {
                            const items = [
                                { url: block.data.image_1_url, caption: block.data.image_1_caption },
                                { url: block.data.image_2_url, caption: block.data.image_2_caption },
                                { url: block.data.image_3_url, caption: block.data.image_3_caption },
                            ].filter((item): item is { url: string; caption: string | undefined } => Boolean(item.url));

                            return (
                                <section key={index} className="space-y-4">
                                    {block.data.heading && (
                                        <h2 className="text-lg sm:text-xl font-extrabold text-slate-900 break-words hyphens-auto">
                                            {block.data.heading}
                                        </h2>
                                    )}
                                    <div
                                        className={`grid gap-4 sm:gap-5 ${
                                            items.length === 2 ? 'sm:grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3'
                                        }`}
                                    >
                                        {items.map((item, idx) => (
                                            <figure
                                                key={idx}
                                                className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xs transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md"
                                            >
                                                <button
                                                    type="button"
                                                    onClick={() => openImageInLightbox(item.url)}
                                                    className="group block aspect-[4/3] w-full overflow-hidden bg-slate-100 cursor-pointer"
                                                >
                                                    <img
                                                        src={item.url}
                                                        alt={item.caption || `${page.title} Bild ${idx + 1}`}
                                                        loading="lazy"
                                                        className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                    />
                                                </button>
                                                {item.caption && (
                                                    <figcaption className="p-3.5 text-xs font-medium text-slate-600">
                                                        {item.caption}
                                                    </figcaption>
                                                )}
                                            </figure>
                                        ))}
                                    </div>
                                </section>
                            );
                        }

                        if (block.type === 'rich_text') {
                            return (
                                <section
                                    key={index}
                                    className="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 shadow-2xs"
                                >
                                    {block.data.heading && (
                                        <h2 className="mb-3 text-lg sm:text-xl font-extrabold text-brand-900 break-words hyphens-auto">
                                            {block.data.heading}
                                        </h2>
                                    )}
                                    {block.data.body && (
                                        <div
                                            className="prose-school space-y-3 text-sm sm:text-base leading-relaxed text-slate-800"
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
                                    className="rounded-2xl border border-brand-200 bg-brand-50 p-5 sm:p-6 text-brand-950"
                                >
                                    <h3 className="font-extrabold text-base sm:text-lg text-brand-900 break-words hyphens-auto">
                                        {block.data.title}
                                    </h3>
                                    <p className="mt-1.5 text-sm leading-relaxed text-slate-700 break-words">
                                        {block.data.text}
                                    </p>
                                </aside>
                            );
                        }

                        if (block.type === 'accordion') {
                            const items = block.data.items ?? [];
                            return (
                                <section
                                    key={index}
                                    className="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 shadow-2xs space-y-4"
                                >
                                    {block.data.heading && (
                                        <h2 className="text-lg sm:text-xl font-extrabold text-brand-900">
                                            {block.data.heading}
                                        </h2>
                                    )}
                                    <div className="divide-y divide-slate-200 rounded-xl border border-slate-200">
                                        {items.map((item, idx) => (
                                            <details key={idx} className="group p-4">
                                                <summary className="flex cursor-pointer list-none items-center justify-between font-bold text-slate-900">
                                                    <span>{item.question}</span>
                                                    <span className="ml-2 text-brand-700 transition group-open:rotate-180">
                                                        ▾
                                                    </span>
                                                </summary>
                                                {item.answer && (
                                                    <p className="mt-2.5 text-sm leading-relaxed text-slate-700">
                                                        {item.answer}
                                                    </p>
                                                )}
                                            </details>
                                        ))}
                                    </div>
                                </section>
                            );
                        }

                        if (block.type === 'downloads_box') {
                            const items = block.data.items ?? [];
                            return (
                                <section
                                    key={index}
                                    className="rounded-2xl border border-brand-200 bg-brand-50/70 p-5 sm:p-6 space-y-3"
                                >
                                    {block.data.heading && (
                                        <h2 className="text-base sm:text-lg font-extrabold text-brand-900">
                                            {block.data.heading}
                                        </h2>
                                    )}
                                    <div className="grid gap-2.5 sm:grid-cols-2">
                                        {items.map((item, idx) =>
                                            item.url ? (
                                                <a
                                                    key={idx}
                                                    href={item.url}
                                                    className="flex items-center justify-between gap-3 rounded-xl border border-brand-200 bg-white px-4 py-3 text-sm font-bold text-brand-900 shadow-2xs transition hover:border-brand-400 hover:bg-brand-50"
                                                >
                                                    <span>📄 {item.label || item.url}</span>
                                                    <span aria-hidden="true">→</span>
                                                </a>
                                            ) : null,
                                        )}
                                    </div>
                                </section>
                            );
                        }

                        if (block.type === 'people_list') {
                            const filtered =
                                !block.data.group || block.data.group === 'all'
                                    ? people
                                    : people.filter((person) => person.group === block.data.group);

                            const withPortraits = filtered.filter((person) => Boolean(person.avatarUrl));
                            const withoutPortraits = filtered.filter((person) => !person.avatarUrl);

                            return (
                                <section key={index} className="space-y-6">
                                    {block.data.heading && (
                                        <h2 className="text-xl sm:text-2xl font-extrabold text-slate-900 break-words hyphens-auto">
                                            {block.data.heading}
                                        </h2>
                                    )}

                                    {withPortraits.length > 0 && (
                                        <div className="grid grid-cols-2 gap-3.5 sm:gap-6 lg:grid-cols-3">
                                            {withPortraits.map((person) => (
                                                <div
                                                    key={person.id}
                                                    className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:border-brand-300 hover:shadow-md flex flex-col"
                                                >
                                                    <div className="aspect-[4/5] w-full overflow-hidden bg-brand-50">
                                                        <img
                                                            src={person.avatarUrl ?? ''}
                                                            alt={person.name}
                                                            loading="lazy"
                                                            className="h-full w-full object-cover object-top"
                                                        />
                                                    </div>
                                                    <div className="p-3.5 sm:p-5 flex-1 flex flex-col justify-between min-w-0">
                                                        <div>
                                                            <div className="flex flex-wrap items-center gap-1">
                                                                <span className="rounded-md bg-brand-50 px-2 py-0.5 text-[10px] sm:text-[11px] font-bold text-brand-700">
                                                                    {person.roleTitle}
                                                                </span>
                                                                {person.className && (
                                                                    <span className="rounded-md bg-amber-400/90 px-2 py-0.5 text-[10px] sm:text-[11px] font-extrabold text-slate-950">
                                                                        {person.className}
                                                                    </span>
                                                                )}
                                                            </div>
                                                            <p className="mt-1.5 sm:mt-2 text-sm sm:text-lg font-extrabold text-slate-900 leading-snug break-words">
                                                                {person.name}
                                                            </p>
                                                        </div>
                                                        {person.email && (
                                                            <a
                                                                href={`mailto:${person.email}`}
                                                                className="mt-2.5 inline-block py-1 text-[11px] sm:text-xs font-semibold text-brand-700 hover:underline break-all"
                                                            >
                                                                ✉ {person.email}
                                                            </a>
                                                        )}
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}

                                    {withoutPortraits.length > 0 && (
                                        <div className="grid gap-3.5 sm:gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                            {withoutPortraits.map((person) => {
                                                const initials = person.name
                                                    .replace(/^(Frau|Herr)\s+/i, '')
                                                    .slice(0, 2)
                                                    .toUpperCase();
                                                return (
                                                    <div
                                                        key={person.id}
                                                        className="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-2xs transition hover:border-brand-300 flex items-start gap-3.5 sm:gap-4"
                                                    >
                                                        <div className="flex h-12 w-12 sm:h-13 sm:w-13 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white font-extrabold text-sm sm:text-base shadow-2xs">
                                                            {initials}
                                                        </div>
                                                        <div className="min-w-0 flex-1">
                                                            <div className="flex flex-wrap items-center gap-1.5">
                                                                <p className="font-extrabold text-slate-900 break-words">
                                                                    {person.name}
                                                                </p>
                                                                {person.className && (
                                                                    <span className="rounded-full bg-amber-400 px-2.5 py-0.5 text-[11px] font-extrabold text-slate-950">
                                                                        {person.className}
                                                                    </span>
                                                                )}
                                                            </div>
                                                            <p className="mt-0.5 text-xs font-semibold text-brand-700">
                                                                {person.roleTitle}
                                                            </p>
                                                            {person.email && (
                                                                <a
                                                                    href={`mailto:${person.email}`}
                                                                    className="mt-1 inline-block py-1 text-xs font-medium text-slate-600 underline hover:text-brand-700 break-all"
                                                                >
                                                                    {person.email}
                                                                </a>
                                                            )}
                                                        </div>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    )}
                                </section>
                            );
                        }

                        return null;
                    })}
                </div>

                <PhotoLightbox
                    images={allPageImages}
                    activeIndex={activeLightboxIndex}
                    onClose={() => setActiveLightboxIndex(null)}
                    onChangeIndex={setActiveLightboxIndex}
                />
            </div>
        </SiteLayout>
    );
}
