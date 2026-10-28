import { Head } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface HomePageProps {
    schoolName: string;
    subtitle: string;
}

/**
 * Startseite der Mühlmattenschule (Grundgerüst Phase 1, wird in Phase 4 ausgebaut).
 */
export default function HomePage({ schoolName, subtitle }: HomePageProps): ReactElement {
    return (
        <>
            <Head title="Startseite" />
            <div className="min-h-screen flex flex-col">
                <header className="bg-brand-700 text-white">
                    <div className="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between gap-4">
                        <div className="flex items-center gap-3">
                            <img
                                src="/images/logo.svg"
                                alt="Logo der Mühlmattenschule"
                                className="h-11 w-11 rounded-lg bg-white/10 p-1"
                            />
                            <div>
                                <p className="text-lg font-bold leading-tight">{schoolName}</p>
                                <p className="text-xs text-brand-100">{subtitle}</p>
                            </div>
                        </div>
                        <a
                            href="/admin"
                            className="rounded-lg bg-brand-500 px-3.5 py-2 text-sm font-medium text-white hover:bg-brand-400"
                        >
                            Verwaltung
                        </a>
                    </div>
                </header>

                <main className="mx-auto max-w-6xl flex-1 px-4 py-12">
                    <section className="rounded-2xl bg-brand-50 border border-brand-200 p-8">
                        <span className="inline-block rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold text-brand-800">
                            Herzlich willkommen
                        </span>
                        <h1 className="mt-3 text-3xl font-bold text-brand-900">
                            {schoolName} – Freiburg-Hochdorf
                        </h1>
                        <p className="mt-2 max-w-2xl text-ink/80">
                            Grundschule für die Freiburger Stadtteile Hochdorf und Benzhausen. Hier entstehen in Kürze
                            aktuelle Beiträge, Termine und Einblicke in unser Schulleben.
                        </p>
                    </section>
                </main>

                <footer className="border-t border-slate-200 bg-white py-6 text-sm text-slate-600">
                    <div className="mx-auto max-w-6xl px-4 flex flex-wrap justify-between gap-4">
                        <span>{schoolName} · Zu den Mühlmatten 8 · 79108 Freiburg im Breisgau</span>
                        <span>Impressum · Datenschutz</span>
                    </div>
                </footer>
            </div>
        </>
    );
}

