import { Breadcrumbs } from '@/components/breadcrumbs';
import { Reveal } from '@/components/reveal';
import { SiteLayout } from '@/layouts/site-layout';
import type { BellScheduleItem, SharedInertiaProps } from '@/types/school';
import { Link, usePage } from '@inertiajs/react';
import { type ReactElement } from 'react';

interface SlotVisualStyle {
    badgeLabel: string;
    rowClassName: string;
    dotClassName: string;
    badgeClassName: string;
    timeClassName: string;
}

/**
 * Liefert die einheitliche visuelle Darstellung für Unterrichts-, Pausen- und Betreuungszeiten.
 */
function getSlotVisualStyle(slot: BellScheduleItem): SlotVisualStyle {
    const isBreak = slot.type === 'break' || Boolean(slot.is_break);
    if (isBreak) {
        return {
            badgeLabel: 'Pause',
            rowClassName: 'bg-amber-50/75 border-amber-200/80 text-amber-950',
            dotClassName: 'bg-amber-500',
            badgeClassName: 'bg-amber-100 text-amber-900',
            timeClassName: 'bg-amber-100/80 text-amber-950',
        };
    }

    if (slot.type === 'betreuung') {
        return {
            badgeLabel: 'Betreuung',
            rowClassName: 'bg-slate-50/80 border-slate-200/80 text-slate-800',
            dotClassName: 'bg-slate-400',
            badgeClassName: 'bg-slate-200/70 text-slate-700',
            timeClassName: 'bg-slate-100 text-slate-700',
        };
    }

    return {
        badgeLabel: 'Unterricht',
        rowClassName: 'bg-white border-slate-200/80 text-slate-900 hover:border-brand-200 hover:bg-brand-50/25',
        dotClassName: 'bg-brand-500',
        badgeClassName: 'bg-brand-50 text-brand-800',
        timeClassName: 'bg-brand-50/80 text-brand-900',
    };
}

/**
 * Detailseite für die Unterrichts- und Pausenzeiten (Läuteordnung) der Mühlmattenschule.
 */
export default function BellSchedulePage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;
    const schedule = school.bell_schedule ?? [];

    return (
        <SiteLayout>
            <div className="mx-auto max-w-6xl px-4 sm:px-6 py-7 sm:py-10">
                <Breadcrumbs />

                <header className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-[#06b09d] p-6 sm:p-9 text-white shadow-md">
                    <div className="relative z-10 max-w-3xl">
                        <span className="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white ring-1 ring-white/25">
                            Unsere Schule · Läuteordnung
                        </span>
                        <h1 className="mt-3 text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
                            Unterrichts- &amp; Pausenzeiten
                        </h1>
                        <p className="mt-2.5 text-sm sm:text-base text-white/90 leading-relaxed">
                            Alle regulären Unterrichtsstunden, Bewegungs- und Vesperpausen sowie die Betreuungszeiten
                            an der {school.name} im Überblick.
                        </p>
                    </div>
                    <div className="pointer-events-none absolute -right-16 -top-16 h-72 w-72 rounded-full bg-white/10 blur-2xl" />
                    <div className="pointer-events-none absolute -bottom-16 right-24 h-64 w-64 rounded-full bg-emerald-200/15 blur-2xl" />
                </header>

                <div className="mt-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <Reveal
                        as="section"
                        variant="fade-up"
                        aria-label="Tagesablauf und Läuteordnung"
                        className="lg:col-span-8 rounded-2xl bg-white p-5 sm:p-7 shadow-xs border border-slate-200/80"
                    >
                        <div className="flex items-center justify-between gap-2 pb-4 border-b border-slate-100">
                            <h2 className="text-base sm:text-lg font-extrabold text-brand-900 flex items-center gap-2.5">
                                <span
                                    aria-hidden="true"
                                    className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-500/15"
                                >
                                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                    </svg>
                                </span>
                                <span>Läuteordnung am Schulvormittag</span>
                            </h2>
                            <span className="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-bold text-brand-800">
                                {schedule.length} Zeiten
                            </span>
                        </div>

                        {schedule.length === 0 ? (
                            <p className="mt-4 text-sm text-slate-600">
                                Aktuell sind keine Unterrichtszeiten hinterlegt.
                            </p>
                        ) : (
                            <ul className="mt-4 space-y-2.5">
                                {schedule.map((slot, idx) => {
                                    const style = getSlotVisualStyle(slot);
                                    return (
                                        <li
                                            key={idx}
                                            className={`flex flex-wrap items-center justify-between gap-3 rounded-xl border px-4 py-3 transition ${style.rowClassName}`}
                                        >
                                            <div className="flex items-center gap-3 min-w-0">
                                                <span
                                                    aria-hidden="true"
                                                    className={`h-2.5 w-2.5 shrink-0 rounded-full ${style.dotClassName}`}
                                                />
                                                <div className="min-w-0">
                                                    <p className="text-sm sm:text-base font-bold leading-snug">
                                                        {slot.label}
                                                    </p>
                                                    <span
                                                        className={`mt-1 inline-block rounded-md px-2 py-0.5 text-[11px] font-bold ${style.badgeClassName}`}
                                                    >
                                                        {style.badgeLabel}
                                                    </span>
                                                </div>
                                            </div>
                                            <span
                                                className={`shrink-0 rounded-lg px-3 py-1.5 font-mono text-xs sm:text-sm font-bold tabular-nums ${style.timeClassName}`}
                                            >
                                                {slot.time} Uhr
                                            </span>
                                        </li>
                                    );
                                })}
                            </ul>
                        )}
                    </Reveal>

                    <aside className="lg:col-span-4 space-y-5">
                        <Reveal variant="fade-up" delay={100} className="rounded-2xl bg-white p-5 sm:p-6 shadow-xs border border-slate-200/80">
                            <h2 className="text-base font-extrabold text-brand-900">Hinweise für Eltern</h2>
                            <ul className="mt-3 space-y-2.5 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                <li className="flex items-start gap-2">
                                    <span aria-hidden="true" className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500" />
                                    <span>
                                        Ab <strong>07:30 Uhr</strong> ist der offene Anfang im Schulhaus bzw. in der
                                        Frühbetreuung möglich.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2">
                                    <span aria-hidden="true" className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500" />
                                    <span>
                                        Bitte melden Sie Ihr Kind im Krankheitsfall morgens rechtzeitig vor
                                        Unterrichtsbeginn im Sekretariat ab.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2">
                                    <span aria-hidden="true" className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500" />
                                    <span>
                                        Im Anschluss an den Vormittagsunterricht findet die Schulkindbetreuung mit
                                        Mittagessen (Caritas) bis <strong>17:00 Uhr</strong> statt.
                                    </span>
                                </li>
                            </ul>

                            <div className="mt-5 pt-4 border-t border-slate-100 flex flex-col gap-2">
                                <Link
                                    href="/kontakt"
                                    className="inline-flex min-h-10 items-center justify-center rounded-xl bg-brand-600 px-4 py-2 text-xs font-extrabold text-white transition hover:bg-brand-700"
                                >
                                    Kontakt &amp; Krankmeldung →
                                </Link>
                                <Link
                                    href="/"
                                    className="inline-flex min-h-10 items-center justify-center rounded-xl bg-brand-50 px-4 py-2 text-xs font-extrabold text-brand-800 transition hover:bg-brand-100"
                                >
                                    ← Zurück zur Startseite
                                </Link>
                            </div>
                        </Reveal>
                    </aside>
                </div>
            </div>
        </SiteLayout>
    );
}
