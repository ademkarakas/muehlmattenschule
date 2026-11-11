@php
    $defaults = [
        'name' => 'Mühlmattenschule',
        'subtitle' => 'Grundschule Freiburg-Hochdorf mit Benzhausen',
        'street' => 'Zu den Mühlmatten 8',
        'zip_city' => '79108 Freiburg im Breisgau',
        'phone' => '07665 2232',
        'fax' => '07665 932081',
        'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
        'office_hours' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
        'betreuung_phone' => '07665 932 92 21',
        'betreuung_mobile' => '0152 02751785',
        'betreuung_email' => 'schulkindbetreuung-muehlmattenschule@caritas-freiburg.de',
        'maintenance_title' => 'Unsere Website wird gerade für Sie aktualisiert',
        'maintenance_message' => 'Wir führen aktuell planmäßige Wartungsarbeiten durch und aktualisieren Inhalte unserer Schulwebsite. In Kürze sind wir wieder wie gewohnt für Sie erreichbar.',
        'maintenance_until' => '',
        'maintenance_show_contact' => true,
    ];

    try {
        $loaded = isset($school) && is_array($school)
            ? $school
            : \App\Support\SeoData::schoolSettings();
        $info = array_merge($defaults, $loaded);
    } catch (\Throwable) {
        $info = $defaults;
    }

    $phoneHref = 'tel:' . preg_replace('/\s+/', '', (string) $info['phone']);
    $betreuungPhoneHref = 'tel:' . preg_replace('/\s+/', '', (string) $info['betreuung_phone']);
    $isPreviewMode = ! empty($isPreview);
@endphp
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="robots" content="noindex, nofollow">
        <title>Wartungsarbeiten – {{ $info['name'] }}</title>
        <link rel="icon" type="image/png" href="/images/logo.png">
        @vite(['resources/css/app.css'])
    </head>
    <body class="min-h-screen min-h-dvh overflow-x-clip bg-surface text-ink flex flex-col justify-between">
        @if ($isPreviewMode)
            <div class="bg-amber-400 text-slate-950 border-b border-amber-500 px-4 py-2.5 text-xs sm:text-sm font-semibold">
                <div class="mx-auto max-w-5xl flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-slate-950" aria-hidden="true"></span>
                        <span>
                            <strong>Vorschau der Wartungsseite:</strong>
                            So sehen nicht angemeldete Besucherinnen und Besucher Ihre Website (HTTP 503).
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a
                            href="/"
                            class="inline-flex items-center rounded-lg bg-white/90 px-3 py-1 text-xs font-bold text-slate-900 shadow-2xs hover:bg-white"
                        >
                            Website-Vorschau öffnen
                        </a>
                        <a
                            href="/admin/manage-school-settings"
                            class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-1 text-xs font-bold text-white shadow-2xs hover:bg-slate-800"
                        >
                            Zurück zu den Einstellungen
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Oberer Markenstreifen im originalen Logo-Türkis (#029786) -->
        <div class="h-2 w-full bg-gradient-to-r from-brand-500 via-brand-600 to-brand-800"></div>

        <main class="flex-1 flex items-center justify-center px-4 py-8 sm:p-10">
            <div class="max-w-3xl w-full overflow-hidden rounded-3xl border border-brand-200/80 bg-white shadow-xl">
                <!-- Kopfbereich mit Logo und Status-Badge -->
                <div class="bg-gradient-to-br from-brand-50 via-[#e4faf5] to-brand-100/70 border-b border-brand-200/70 px-6 py-7 sm:px-10 sm:py-9 text-center">
                    <img
                        src="/images/logo.png"
                        alt="Logo der {{ $info['name'] }}"
                        width="88"
                        height="88"
                        class="mx-auto h-20 w-20 sm:h-24 sm:w-24 rounded-2xl bg-white p-2 shadow-md object-contain"
                    />

                    <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-brand-300 bg-white px-4 py-1.5 text-xs font-extrabold uppercase tracking-wider text-brand-800 shadow-2xs">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                        <span>Wartungsmodus · Kurzzeitige Aktualisierung</span>
                    </div>

                    <h1 class="mt-4 text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-brand-900 leading-tight">
                        {{ $info['maintenance_title'] }}
                    </h1>

                    <p class="mt-2 text-sm sm:text-base font-semibold text-brand-700">
                        {{ $info['name'] }} · {{ $info['subtitle'] }}
                    </p>
                </div>

                <!-- Haupttext & Erläuterung -->
                <div class="px-6 py-6 sm:px-10 sm:py-8 space-y-6">
                    <div class="rounded-2xl border border-slate-200/90 bg-slate-50/70 p-5 sm:p-6 text-center">
                        <p class="text-sm sm:text-base leading-relaxed text-slate-800 font-medium">
                            {{ $info['maintenance_message'] }}
                        </p>

                        @if (! empty($info['maintenance_until']))
                            <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-amber-50 border border-amber-200 px-4 py-2 text-xs sm:text-sm font-bold text-amber-950">
                                <span aria-hidden="true">🕒</span>
                                <span>Voraussichtlich wieder erreichbar: {{ $info['maintenance_until'] }}</span>
                            </div>
                        @endif
                    </div>

                    @if (! empty($info['maintenance_show_contact']))
                        <!-- Wichtige Kontaktdaten für Eltern (Krankmeldung, Sekretariat, Betreuung) -->
                        <div>
                            <h2 class="text-xs font-extrabold uppercase tracking-wider text-brand-700 text-center sm:text-left">
                                Wichtige Erreichbarkeit während der Wartung (Krankmeldung & Kontakt)
                            </h2>

                            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                <!-- Sekretariat & Krankmeldung -->
                                <div class="rounded-2xl border border-brand-200 bg-brand-50/60 p-4 sm:p-5 flex flex-col justify-between">
                                    <div>
                                        <span class="inline-block rounded-lg bg-brand-600 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider text-white">
                                            Schulsekretariat
                                        </span>
                                        <h3 class="mt-2 text-base font-extrabold text-slate-900">
                                            Sekretariat & Krankmeldung
                                        </h3>
                                        <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                                            Frau Vennebusch · Öffnungszeiten: <strong>{{ $info['office_hours'] }}</strong>
                                        </p>
                                        <p class="mt-1 text-xs text-slate-600">
                                            📍 {{ $info['street'] }}, {{ $info['zip_city'] }}
                                        </p>
                                    </div>

                                    <div class="mt-4 space-y-2 text-xs sm:text-sm">
                                        <a
                                            href="{{ $phoneHref }}"
                                            class="flex min-h-10 items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2 font-bold text-white shadow-2xs hover:bg-brand-700"
                                        >
                                            <span aria-hidden="true">☎</span>
                                            <span>Anrufen: {{ $info['phone'] }}</span>
                                        </a>
                                        <a
                                            href="mailto:{{ $info['email'] }}"
                                            class="flex min-h-10 items-center justify-center gap-1.5 rounded-xl border border-brand-300 bg-white px-3 py-2 font-semibold text-brand-800 hover:bg-brand-50 break-all text-center"
                                        >
                                            <span aria-hidden="true">✉</span>
                                            <span>{{ $info['email'] }}</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Schulkindbetreuung (Caritas) -->
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 flex flex-col justify-between">
                                    <div>
                                        <span class="inline-block rounded-lg bg-amber-400 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-950">
                                            Betreuung (7:30 – 17:00 Uhr)
                                        </span>
                                        <h3 class="mt-2 text-base font-extrabold text-slate-900">
                                            Caritas Schulkindbetreuung
                                        </h3>
                                        <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                                            Leitung: Lisa Jortzik · Sprechzeiten: 7:30–8:30 Uhr & 12:15–17:00 Uhr
                                        </p>
                                        <p class="mt-1 text-xs text-slate-600">
                                            Mobil: <strong>{{ $info['betreuung_mobile'] }}</strong>
                                        </p>
                                    </div>

                                    <div class="mt-4 space-y-2 text-xs sm:text-sm">
                                        <a
                                            href="{{ $betreuungPhoneHref }}"
                                            class="flex min-h-10 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-slate-100 px-4 py-2 font-bold text-slate-900 hover:bg-slate-200"
                                        >
                                            <span aria-hidden="true">☎</span>
                                            <span>Festnetz: {{ $info['betreuung_phone'] }}</span>
                                        </a>
                                        <a
                                            href="mailto:{{ $info['betreuung_email'] }}"
                                            class="flex min-h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 font-semibold text-slate-700 hover:bg-slate-50 break-all text-center"
                                        >
                                            <span aria-hidden="true">✉</span>
                                            <span>E-Mail Schulkindbetreuung</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Fußleiste mit Verwaltungs-Zugang -->
                <div class="border-t border-slate-200/80 bg-slate-50 px-6 py-4 sm:px-10 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                    <span>© {{ date('Y') }} {{ $info['name'] }} Freiburg-Hochdorf</span>
                    <a
                        href="/admin"
                        rel="nofollow"
                        class="font-semibold text-brand-700 hover:text-brand-900 hover:underline"
                    >
                        Anmeldung für Schulleitung & Verwaltung →
                    </a>
                </div>
            </div>
        </main>
    </body>
</html>
