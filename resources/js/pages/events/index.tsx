import { SiteLayout } from '@/layouts/site-layout';
import type { UpcomingEventItem } from '@/types/school';
import { Head } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface EventsIndexProps {
    events: UpcomingEventItem[];
}

/**
 * Termin- und Kalenderübersicht der Mühlmattenschule, gruppiert nach Monaten.
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
            <Head title="Termine" />

            <div className="mx-auto max-w-5xl px-4 py-10">
                <header className="mb-8">
                    <h1 className="text-3xl font-extrabold text-slate-900">Schultermine & Veranstaltungen</h1>
                    <p className="mt-2 text-slate-600">
                        Alle wichtigen Termine, Feste, Elternabende und Ferientage im Überblick.
                    </p>
                </header>

                {events.length === 0 ? (
                    <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-600">
                        Derzeit sind keine anstehenden Termine eingetragen.
                    </div>
                ) : (
                    <div className="space-y-8">
                        {Object.entries(grouped).map(([month, monthEvents]) => (
                            <section key={month}>
                                <h2 className="mb-3 text-lg font-bold text-brand-800 capitalize">{month}</h2>
                                <div className="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white shadow-xs">
                                    {monthEvents.map((event) => (
                                        <div key={event.id} className="flex items-start gap-4 p-5">
                                            <div className="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-brand-100 text-brand-800">
                                                <span className="text-lg font-extrabold leading-none">{event.day}</span>
                                                <span className="text-xs font-semibold uppercase">{event.weekday}</span>
                                            </div>
                                            <div className="flex-1">
                                                <h3 className="text-base font-bold text-slate-900">{event.title}</h3>
                                                <p className="mt-0.5 text-xs font-medium text-slate-500">
                                                    {event.dateFormatted} · {event.timeFormatted}
                                                    {event.location ? ` · Ort: ${event.location}` : ''}
                                                </p>
                                                {event.description && (
                                                    <p className="mt-2 text-sm text-slate-700">{event.description}</p>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </section>
                        ))}
                    </div>
                )}
            </div>
        </SiteLayout>
    );
}
