import { PostCard } from '@/components/post-card';
import { SiteLayout } from '@/layouts/site-layout';
import type { AlbumCardItem, PostCardItem, SharedInertiaProps, UpcomingEventItem } from '@/types/school';
import { Head, Link, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface HomePageProps {
    posts: PostCardItem[];
    upcomingEvents: UpcomingEventItem[];
    albums: AlbumCardItem[];
}

/**
 * EduPage-inspirierte Startseite der Mühlmattenschule mit türkisem Hero-Bereich,
 * Beitragsstream („Aktuelles & Klassen“), Terminspalte und neuesten Fotoalben.
 */
export default function HomePage({ posts, upcomingEvents, albums }: HomePageProps): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;

    return (
        <SiteLayout>
            <Head title="Startseite" />

            {/* Hero-Bereich */}
            <section className="bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 text-white">
                <div className="mx-auto max-w-6xl px-4 py-12 md:py-16 grid gap-8 md:grid-cols-12 items-center">
                    <div className="md:col-span-8">
                        <span className="inline-block rounded-full bg-white/15 px-3.5 py-1 text-xs font-semibold tracking-wide text-brand-50">
                            {school.subtitle}
                        </span>
                        <h1 className="mt-3 text-3xl md:text-4xl font-extrabold leading-tight">
                            {school.welcome_title || `Willkommen an der ${school.name}`}
                        </h1>
                        <p className="mt-3 max-w-2xl text-base md:text-lg text-brand-50/95 leading-relaxed">
                            {school.welcome_text ||
                                'Grundschule für die Freiburger Stadtteile Hochdorf und Benzhausen – lebendiges Lernen, Gemeinschaft und Natur.'}
                        </p>
                        <div className="mt-6 flex flex-wrap gap-3">
                            <Link
                                href="/aktuelles"
                                className="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-brand-800 shadow-sm hover:bg-brand-50"
                            >
                                Aktuelles & Klassen
                            </Link>
                            <Link
                                href="/unsere-schule/schulportrait"
                                className="rounded-xl border border-white/40 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/20"
                            >
                                Unsere Schule kennenlernen
                            </Link>
                        </div>
                    </div>

                    <div className="md:col-span-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/20 p-5 text-sm">
                        <p className="font-bold text-base text-white">Kontakt & Sekretariat</p>
                        <p className="mt-2 text-brand-100">
                            {school.street} · {school.zip_city}
                        </p>
                        <p className="mt-2">
                            <span className="text-brand-200">Telefon:</span>{' '}
                            <a href={`tel:${school.phone}`} className="font-semibold underline">
                                {school.phone}
                            </a>
                        </p>
                        <p className="mt-1">
                            <span className="text-brand-200">Öffnungszeiten:</span> {school.office_hours}
                        </p>
                    </div>
                </div>
            </section>

            {/* Hauptbereich im EduPage-Zweispalten-Layout */}
            <div className="mx-auto max-w-6xl px-4 py-10 grid gap-8 lg:grid-cols-12">
                {/* Linke Hauptspalte: Aktuelle Beiträge & Klassenaktivitäten */}
                <section className="lg:col-span-8">
                    <div className="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 className="text-2xl font-bold text-slate-900">Aktuelles aus dem Schulleben</h2>
                            <p className="text-sm text-slate-600">
                                Berichte, Projekte und Aktivitäten unserer Klassen
                            </p>
                        </div>
                        <Link
                            href="/aktuelles"
                            className="text-sm font-semibold text-brand-700 hover:text-brand-900"
                        >
                            Alle Beiträge →
                        </Link>
                    </div>

                    {posts.length === 0 ? (
                        <div className="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-600">
                            Aktuell sind noch keine Beiträge veröffentlicht.
                        </div>
                    ) : (
                        <div className="grid gap-6 sm:grid-cols-2">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={post} />
                            ))}
                        </div>
                    )}
                </section>

                {/* Rechte Seitenleiste: Nächste Termine & Schnellzugriff */}
                <aside className="lg:col-span-4 space-y-6">
                    <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-bold text-slate-900">Nächste Termine</h2>
                            <Link
                                href="/termine"
                                className="text-xs font-semibold text-brand-700 hover:text-brand-900"
                            >
                                Kalender →
                            </Link>
                        </div>

                        {upcomingEvents.length === 0 ? (
                            <p className="mt-4 text-sm text-slate-500">Derzeit stehen keine neuen Termine an.</p>
                        ) : (
                            <ul className="mt-4 divide-y divide-slate-100">
                                {upcomingEvents.map((event) => (
                                    <li key={event.id} className="py-3 flex items-start gap-3.5">
                                        <div className="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl bg-brand-100 text-brand-800">
                                            <span className="text-base font-extrabold leading-none">{event.day}</span>
                                            <span className="text-[11px] font-semibold uppercase">
                                                {event.monthShort}
                                            </span>
                                        </div>
                                        <div className="min-w-0 flex-1">
                                            <p className="font-semibold text-sm text-slate-900 leading-snug">
                                                {event.title}
                                            </p>
                                            <p className="mt-0.5 text-xs text-slate-500">
                                                {event.dateFormatted} · {event.timeFormatted}
                                            </p>
                                            {event.location && (
                                                <p className="text-xs text-brand-700">{event.location}</p>
                                            )}
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>

                    <div className="rounded-2xl border border-brand-200 bg-brand-50 p-6">
                        <h3 className="text-base font-bold text-brand-900">Wichtige Bereiche</h3>
                        <ul className="mt-3 space-y-2 text-sm font-medium text-brand-800">
                            <li>
                                <Link
                                    href="/unsere-schule/kollegium"
                                    className="flex items-center justify-between rounded-xl bg-white px-4 py-2.5 shadow-2xs hover:bg-brand-100/60"
                                >
                                    <span>Kollegium & Ansprechpartner</span>
                                    <span aria-hidden="true">→</span>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/schulleben/betreuung-und-ags"
                                    className="flex items-center justify-between rounded-xl bg-white px-4 py-2.5 shadow-2xs hover:bg-brand-100/60"
                                >
                                    <span>Schulkindbetreuung & AGs</span>
                                    <span aria-hidden="true">→</span>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/downloads"
                                    className="flex items-center justify-between rounded-xl bg-white px-4 py-2.5 shadow-2xs hover:bg-brand-100/60"
                                >
                                    <span>Formulare & Elternbriefe</span>
                                    <span aria-hidden="true">→</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>

            {/* Neueste Fotoalben */}
            {albums.length > 0 && (
                <section className="mx-auto max-w-6xl px-4 py-6">
                    <div className="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h2 className="text-2xl font-bold text-slate-900">Einblicke & Fotoalben</h2>
                            <p className="text-sm text-slate-600">Impressionen aus unseren Klassen und Projekten</p>
                        </div>
                        <Link href="/galerie" className="text-sm font-semibold text-brand-700 hover:text-brand-900">
                            Alle Fotoalben →
                        </Link>
                    </div>

                    <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        {albums.map((album) => (
                            <Link
                                key={album.id}
                                href={`/galerie/${album.slug}`}
                                className="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition hover:-translate-y-0.5 hover:shadow-md"
                            >
                                <div className="aspect-[4/3] w-full overflow-hidden bg-brand-100">
                                    {album.coverUrl ? (
                                        <img
                                            src={album.coverUrl}
                                            alt={album.title}
                                            loading="lazy"
                                            className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                    ) : (
                                        <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 to-brand-700 p-4 text-center text-white text-sm font-semibold">
                                            {album.title}
                                        </div>
                                    )}
                                </div>
                                <div className="p-4">
                                    <div className="flex items-center justify-between text-xs text-slate-500">
                                        <span>{album.eventDate || 'Schuljahr'}</span>
                                        {album.className && (
                                            <span className="rounded-full bg-brand-100 px-2 py-0.5 font-semibold text-brand-800">
                                                {album.className}
                                            </span>
                                        )}
                                    </div>
                                    <p className="mt-1 font-bold text-slate-900 group-hover:text-brand-700">
                                        {album.title}
                                    </p>
                                </div>
                            </Link>
                        ))}
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}
