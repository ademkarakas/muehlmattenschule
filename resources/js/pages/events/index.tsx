import { Breadcrumbs } from '@/components/breadcrumbs';
import { SiteLayout } from '@/layouts/site-layout';
import type { UpcomingEventItem } from '@/types/school';
import type { ReactElement } from 'react';

export interface EventsIndexProps {
    events: UpcomingEventItem[];
}

const EVENT_CATEGORY_LABELS: Record<string, { label: string; className: string }> = {
    ferien: { label: 'Ferien / Schulfrei', className: 'bg-emerald-100 text-emerald-900' },
    eltern: { label: 'Eltern & Gremien', className: 'bg-sky-100 text-sky-900' },
    fest: { label: 'Schulfest & Projekttag', className: 'bg-amber-100 text-amber-950' },
    unterricht: { label: 'Unterricht & Klasse', className: 'bg-brand-100 text-brand-900' },
};

/**
 * Termin- und Kalenderübersicht der Mühlmattenschule, gruppiert nach Monaten,
 * inklusive iCal-Export (.ics) und Klassen-/Typ-Kennzeichnung.
 */
export default function EventsIndexPage({ events }: EventsIndexProps): ReactElement {
    const grouped = events.reduce<Record<string, UpcomingEventItem[]>>((acc, item) => {
        const key = item.monthHeading || 'Kommende Termine';
        if (!acc[key]) {
            acc[key] = [];
        }
        acc[key].push(item);
        return acc;
    }, {});

    return (
        <SiteLayout>
            <div className="mx-auto max-w-5xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <header className="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 break-words hyphens-auto">
                            Schultermine & Veranstaltungen
                        </h1>
                        <p className="mt-2 text-sm sm:text-base text-slate-600">
                            Alle wichtigen Termine, Feste, Elternabende und Ferientage im Überblick.
                        </p>
                    </div>
                    <a
                        href="/termine.ics"
                        className="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-xs sm:text-sm font-extrabold text-white shadow-xs transition hover:bg-brand-800"
                    >
                        <span aria-hidden="true">📅</span> Alle Termine als iCal (.ics)
                    </a>
                </header>

                {events.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-8 sm:p-10 text-center text-slate-600">
                        Derzeit sind keine anstehenden Termine eingetragen.
                    </div>
                ) : (
                    <div className="space-y-6 sm:space-y-8">
                        {Object.entries(grouped).map(([month, monthEvents]) => (
                            <section key={month}>
                                <h2 className="mb-3 text-base sm:text-lg font-bold text-brand-800 capitalize">
                                    {month}
                                </h2>
                                <div className="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white shadow-xs">
                                    {monthEvents.map((event) => {
                                        const catBadge = event.category
                                            ? EVENT_CATEGORY_LABELS[event.category]
                                            : undefined;
                                        return (
                                            <article
                                                key={event.id}
                                                className="flex flex-col sm:flex-row sm:items-start justify-between gap-3.5 sm:gap-4 p-4 sm:p-5"
                                            >
                                                <div className="flex items-start gap-3.5 sm:gap-4 min-w-0 flex-1">
                                                    <div className="flex h-13 w-13 sm:h-14 sm:w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-brand-100 text-brand-800">
                                                        <span className="text-base sm:text-lg font-extrabold leading-none">
                                                            {event.day}
                                                        </span>
                                                        <span className="text-[11px] sm:text-xs font-semibold uppercase">
                                                            {event.weekday}
                                                        </span>
                                                    </div>
                                                    <div className="min-w-0 flex-1">
                                                        <div className="flex flex-wrap items-center gap-1.5 mb-1">
                                                            {catBadge && (
                                                                <span
                                                                    className={`rounded-md px-2.5 py-0.5 text-xs font-bold ${catBadge.className}`}
                                                                >
                                                                    {catBadge.label}
                                                                </span>
                                                            )}
                                                            {event.className && (
                                                                <span className="rounded-md bg-amber-400 px-2.5 py-0.5 text-xs font-extrabold text-slate-950">
                                                                    {event.className}
                                                                </span>
                                                            )}
                                                        </div>
                                                        <h3 className="text-sm sm:text-base font-bold text-slate-900 break-words">
                                                            {event.title}
                                                        </h3>
                                                        <p className="mt-0.5 text-xs font-medium text-slate-500 break-words">
                                                            <time dateTime={event.startsAtIso ?? undefined}>
                                                                {event.dateFormatted} · {event.timeFormatted}
                                                            </time>
                                                            {event.location ? ` · Ort: ${event.location}` : ''}
                                                        </p>
                                                        {event.description && (
                                                            <p className="mt-2 text-xs sm:text-sm text-slate-700 break-words">
                                                                {event.description}
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>

                                                {event.icalUrl && (
                                                    <a
                                                        href={event.icalUrl}
                                                        className="inline-flex min-h-9 self-start sm:self-center shrink-0 items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700 hover:border-brand-300 hover:bg-brand-50 hover:text-brand-800"
                                                    >
                                                        <span aria-hidden="true">📅</span> Kalender (.ics)
                                                    </a>
                                                )}
                                            </article>
                                        );
                                    })}
                                </div>
                            </section>
                        ))}
                    </div>
                )}
            </div>
        </SiteLayout>
    );
}
