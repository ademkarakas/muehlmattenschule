import { Breadcrumbs } from "@/components/breadcrumbs";
import {
    PhotoLightbox,
    type LightboxImageItem,
} from "@/components/photo-lightbox";
import { Reveal } from "@/components/reveal";
import { SiteLayout } from "@/layouts/site-layout";
import type { PersonItem } from "@/types/school";
import { useState, type ReactElement } from "react";

export interface PageBlock {
    type:
        | "rich_text"
        | "info_box"
        | "people_list"
        | "image_banner"
        | "image_gallery"
        | "accordion"
        | "downloads_box";
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

/**
 * Stellt eine über den Admin-Baukasten gepflegte Schulseite dar (inkl. Fotos, Galerien, Akkordeon, Downloads und Kollegiums-Karten).
 */
export default function PageShow({
    page,
    people,
}: PageShowProps): ReactElement {
    const [activeLightboxIndex, setActiveLightboxIndex] = useState<
        number | null
    >(null);

    const allPageImages: LightboxImageItem[] = [];
    for (const block of page.content) {
        if (block.type === "image_banner" && block.data.image_url) {
            allPageImages.push({
                url: block.data.image_url,
                alt: block.data.caption || page.title,
            });
        } else if (block.type === "image_gallery") {
            const galleryItems = [
                {
                    url: block.data.image_1_url,
                    caption: block.data.image_1_caption,
                },
                {
                    url: block.data.image_2_url,
                    caption: block.data.image_2_caption,
                },
                {
                    url: block.data.image_3_url,
                    caption: block.data.image_3_caption,
                },
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

    return (
        <SiteLayout>
            <div className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <header className="mb-6 sm:mb-8 border-b border-slate-200 pb-5 sm:pb-6">
                    <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-900 break-words hyphens-auto">
                        {page.title}
                    </h1>
                    {page.summary && (
                        <p className="mt-2 text-base sm:text-lg text-slate-600 break-words">
                            {page.summary}
                        </p>
                    )}
                </header>

                <div className="space-y-6 sm:space-y-8">
                    {page.content.map((block, index) => {
                        const content = (() => {
                            if (
                                block.type === "image_banner" &&
                                block.data.image_url
                            ) {
                            const bannerUrl = block.data.image_url;
                            return (
                                <figure
                                    key={index}
                                    className="overflow-hidden rounded-2xl sm:rounded-3xl border border-slate-200 bg-white shadow-sm"
                                >
                                    <button
                                        type="button"
                                        onClick={() =>
                                            openImageInLightbox(bannerUrl)
                                        }
                                        className="group block max-h-[460px] w-full overflow-hidden bg-slate-100 cursor-pointer"
                                    >
                                        <img
                                            src={bannerUrl}
                                            alt={
                                                block.data.caption || page.title
                                            }
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

                        if (block.type === "image_gallery") {
                            const items = [
                                {
                                    url: block.data.image_1_url,
                                    caption: block.data.image_1_caption,
                                },
                                {
                                    url: block.data.image_2_url,
                                    caption: block.data.image_2_caption,
                                },
                                {
                                    url: block.data.image_3_url,
                                    caption: block.data.image_3_caption,
                                },
                            ].filter(
                                (
                                    item,
                                ): item is {
                                    url: string;
                                    caption: string | undefined;
                                } => Boolean(item.url),
                            );

                            return (
                                <section key={index} className="space-y-4">
                                    {block.data.heading && (
                                        <h2 className="text-lg sm:text-xl font-extrabold text-slate-900 break-words hyphens-auto">
                                            {block.data.heading}
                                        </h2>
                                    )}
                                    <div
                                        className={`grid gap-4 sm:gap-5 ${
                                            items.length === 2
                                                ? "sm:grid-cols-2"
                                                : "sm:grid-cols-2 lg:grid-cols-3"
                                        }`}
                                    >
                                        {items.map((item, idx) => (
                                            <figure
                                                key={idx}
                                                className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xs transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md"
                                            >
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        openImageInLightbox(
                                                            item.url,
                                                        )
                                                    }
                                                    className="group block aspect-[4/3] w-full overflow-hidden bg-slate-100 cursor-pointer"
                                                >
                                                    <img
                                                        src={item.url}
                                                        alt={
                                                            item.caption ||
                                                            `${page.title} Bild ${idx + 1}`
                                                        }
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

                        if (block.type === "rich_text") {
                            return (
                                <section
                                    key={index}
                                    className="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 shadow-2xs"
                                >
                                    {block.data.heading && (
                                        <h2 className="mb-3 border-l-4 border-brand-500 pl-3 text-lg sm:text-xl font-extrabold text-brand-700 break-words hyphens-auto">
                                            {block.data.heading}
                                        </h2>
                                    )}
                                    {block.data.body && (
                                        <div
                                            className="prose-school space-y-3 text-sm sm:text-base leading-relaxed text-slate-800"
                                            dangerouslySetInnerHTML={{
                                                __html: block.data.body,
                                            }}
                                        />
                                    )}
                                </section>
                            );
                        }

                        if (block.type === "info_box") {
                            return (
                                <aside
                                    key={index}
                                    className="rounded-2xl border border-brand-200 bg-brand-50 p-5 sm:p-6 text-brand-950"
                                >
                                    <div className="flex items-start gap-3">
                                        <span
                                            className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-brand-700 ring-1 ring-brand-500/15"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                className="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                strokeWidth="1.8"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"
                                                />
                                            </svg>
                                        </span>
                                        <div className="min-w-0 flex-1">
                                            <h3 className="font-extrabold text-base sm:text-lg text-brand-700 break-words hyphens-auto">
                                                {block.data.title}
                                            </h3>
                                            <p className="mt-1.5 text-sm leading-relaxed text-slate-700 break-words">
                                                {block.data.text}
                                            </p>
                                        </div>
                                    </div>
                                </aside>
                            );
                        }

                        if (block.type === "accordion") {
                            const items = block.data.items ?? [];
                            return (
                                <section
                                    key={index}
                                    className="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 md:p-8 shadow-2xs space-y-4"
                                >
                                    {block.data.heading && (
                                        <div className="flex items-center gap-2.5">
                                            <span
                                                className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15"
                                                aria-hidden="true"
                                            >
                                                <svg
                                                    className="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="1.8"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"
                                                    />
                                                </svg>
                                            </span>
                                            <h2 className="text-lg sm:text-xl font-extrabold text-brand-700">
                                                {block.data.heading}
                                            </h2>
                                        </div>
                                    )}
                                    <div className="divide-y divide-slate-200 rounded-xl border border-slate-200">
                                        {items.map((item, idx) => (
                                            <details
                                                key={idx}
                                                className="group p-4"
                                            >
                                                <summary className="flex cursor-pointer list-none items-center justify-between gap-3 font-bold text-slate-900">
                                                    <span>{item.question}</span>
                                                    <span
                                                        className="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700 ring-1 ring-brand-500/15 transition group-open:rotate-180"
                                                        aria-hidden="true"
                                                    >
                                                        <svg
                                                            className="h-4 w-4"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            strokeWidth="2"
                                                        >
                                                            <path
                                                                strokeLinecap="round"
                                                                strokeLinejoin="round"
                                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                            />
                                                        </svg>
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

                        if (block.type === "downloads_box") {
                            const items = block.data.items ?? [];
                            return (
                                <section
                                    key={index}
                                    className="rounded-2xl border border-brand-200 bg-brand-50/70 p-5 sm:p-6 space-y-3.5"
                                >
                                    {block.data.heading && (
                                        <div className="flex items-center gap-2.5">
                                            <span
                                                className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white text-brand-700 ring-1 ring-brand-500/15"
                                                aria-hidden="true"
                                            >
                                                <svg
                                                    className="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="1.8"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                                    />
                                                </svg>
                                            </span>
                                            <h2 className="text-base sm:text-lg font-extrabold text-brand-700">
                                                {block.data.heading}
                                            </h2>
                                        </div>
                                    )}
                                    <div className="grid gap-2.5 sm:grid-cols-2">
                                        {items.map((item, idx) =>
                                            item.url ? (
                                                <a
                                                    key={idx}
                                                    href={item.url}
                                                    className="flex items-center justify-between gap-3 rounded-2xl border border-brand-200/90 bg-white p-3.5 text-sm font-bold text-brand-900 shadow-2xs transition hover:border-brand-400 hover:bg-brand-50/40"
                                                >
                                                    <span className="flex items-center gap-3 min-w-0">
                                                        <span
                                                            className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15"
                                                            aria-hidden="true"
                                                        >
                                                            <svg
                                                                className="h-4.5 w-4.5"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                                strokeWidth="1.8"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                                                />
                                                            </svg>
                                                        </span>
                                                        <span className="truncate">
                                                            {item.label ||
                                                                item.url}
                                                        </span>
                                                    </span>
                                                    <svg
                                                        className="h-4 w-4 shrink-0 text-brand-700"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth="2"
                                                        aria-hidden="true"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"
                                                        />
                                                    </svg>
                                                </a>
                                            ) : null,
                                        )}
                                    </div>
                                </section>
                            );
                        }

                        if (block.type === "people_list") {
                            const filtered =
                                !block.data.group || block.data.group === "all"
                                    ? people
                                    : people.filter(
                                          (person) =>
                                              person.group === block.data.group,
                                      );

                            const withPortraits = filtered.filter((person) =>
                                Boolean(person.avatarUrl),
                            );
                            const withoutPortraits = filtered.filter(
                                (person) => !person.avatarUrl,
                            );

                            return (
                                <section key={index} className="space-y-6">
                                    {block.data.heading && (
                                        <h2 className="border-l-4 border-brand-500 pl-3 text-xl sm:text-2xl font-extrabold text-brand-700 break-words hyphens-auto">
                                            {block.data.heading}
                                        </h2>
                                    )}

                                    {withPortraits.length > 0 && (
                                        <div
                                            className={`grid gap-3.5 sm:gap-6 ${
                                                withPortraits.length === 1
                                                    ? "grid-cols-1 max-w-sm"
                                                    : withPortraits.length === 2
                                                      ? "grid-cols-1 sm:grid-cols-2 max-w-3xl"
                                                      : "grid-cols-2 lg:grid-cols-3"
                                            }`}
                                        >
                                            {withPortraits.map((person) => (
                                                <div
                                                    key={person.id}
                                                    className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:border-brand-300 hover:shadow-md flex flex-col"
                                                >
                                                    <div className="aspect-[4/5] w-full overflow-hidden bg-brand-50">
                                                        <img
                                                            src={
                                                                person.avatarUrl ??
                                                                ""
                                                            }
                                                            alt={person.name}
                                                            loading="lazy"
                                                            className="h-full w-full object-cover object-top"
                                                        />
                                                    </div>
                                                    <div className="p-3.5 sm:p-5 flex-1 flex flex-col justify-between min-w-0">
                                                        <div>
                                                            <div className="flex flex-wrap items-center gap-1">
                                                                <span className="rounded-md bg-brand-50 px-2 py-0.5 text-[10px] sm:text-[11px] font-bold text-brand-700">
                                                                    {
                                                                        person.roleTitle
                                                                    }
                                                                </span>
                                                                {person.className && (
                                                                    <span className="rounded-md bg-amber-400/90 px-2 py-0.5 text-[10px] sm:text-[11px] font-extrabold text-slate-950">
                                                                        {
                                                                            person.className
                                                                        }
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
                                                                className="mt-2.5 inline-flex items-center gap-1.5 py-1 text-[11px] sm:text-xs font-semibold text-brand-700 hover:underline break-all"
                                                            >
                                                                <svg
                                                                    className="h-3.5 w-3.5 shrink-0"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke="currentColor"
                                                                    strokeWidth="1.8"
                                                                    aria-hidden="true"
                                                                >
                                                                    <path
                                                                        strokeLinecap="round"
                                                                        strokeLinejoin="round"
                                                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                                                                    />
                                                                </svg>
                                                                <span>
                                                                    {
                                                                        person.email
                                                                    }
                                                                </span>
                                                            </a>
                                                        )}
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}

                                    {withoutPortraits.length > 0 && (
                                        <div
                                            className={`grid gap-3.5 sm:gap-4 ${
                                                withoutPortraits.length === 1
                                                    ? "grid-cols-1 max-w-md"
                                                    : withoutPortraits.length ===
                                                        2
                                                      ? "sm:grid-cols-2 max-w-4xl"
                                                      : "sm:grid-cols-2 lg:grid-cols-3"
                                            }`}
                                        >
                                            {withoutPortraits.map((person) => {
                                                const nameParts = person.name
                                                    .replace(
                                                        /^(Frau|Herr)\s+/i,
                                                        "",
                                                    )
                                                    .trim()
                                                    .split(/\s+/);
                                                const initials =
                                                    nameParts.length >= 2
                                                        ? `${nameParts[0][0] ?? ""}${nameParts[nameParts.length - 1][0] ?? ""}`.toUpperCase()
                                                        : (nameParts[0] ?? "")
                                                              .slice(0, 2)
                                                              .toUpperCase();
                                                return (
                                                    <div
                                                        key={person.id}
                                                        className="group rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md flex items-start gap-3.5 sm:gap-4"
                                                    >
                                                        <div
                                                            className="flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-50 via-white to-brand-100/80 text-brand-700 ring-1 ring-brand-500/20 font-extrabold text-sm sm:text-base tracking-tight transition-colors group-hover:from-brand-100/80 group-hover:to-brand-100 group-hover:ring-brand-500/35"
                                                            aria-hidden="true"
                                                        >
                                                            {initials || (
                                                                <svg
                                                                    className="h-5 w-5"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke="currentColor"
                                                                    strokeWidth="1.8"
                                                                >
                                                                    <path
                                                                        strokeLinecap="round"
                                                                        strokeLinejoin="round"
                                                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                                                                    />
                                                                </svg>
                                                            )}
                                                        </div>
                                                        <div className="min-w-0 flex-1">
                                                            <div className="flex flex-wrap items-center gap-1.5">
                                                                <p className="text-sm sm:text-base font-extrabold text-slate-900 leading-snug break-words">
                                                                    {
                                                                        person.name
                                                                    }
                                                                </p>
                                                                {person.className && (
                                                                    <span className="rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-bold text-amber-800 ring-1 ring-amber-500/25">
                                                                        {
                                                                            person.className
                                                                        }
                                                                    </span>
                                                                )}
                                                            </div>
                                                            <p className="mt-1 text-xs font-semibold text-brand-700/90">
                                                                {
                                                                    person.roleTitle
                                                                }
                                                            </p>
                                                            {person.email && (
                                                                <a
                                                                    href={`mailto:${person.email}`}
                                                                    className="mt-2.5 inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200/80 transition hover:bg-brand-50 hover:text-brand-700 hover:ring-brand-500/20 break-all"
                                                                >
                                                                    <svg
                                                                        className="h-3.5 w-3.5 shrink-0"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                        strokeWidth="1.8"
                                                                        aria-hidden="true"
                                                                    >
                                                                        <path
                                                                            strokeLinecap="round"
                                                                            strokeLinejoin="round"
                                                                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                                                                        />
                                                                    </svg>
                                                                    <span>
                                                                        {
                                                                            person.email
                                                                        }
                                                                    </span>
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
                        })();

                        if (!content) {
                            return null;
                        }

                        return (
                            <Reveal
                                key={index}
                                variant={block.type === "image_banner" ? "zoom-in" : "fade-up"}
                                delay={Math.min(index, 2) * 50}
                            >
                                {content}
                            </Reveal>
                        );
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
