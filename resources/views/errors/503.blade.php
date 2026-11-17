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

    $isPreviewMode = ! empty($isPreview);
@endphp
<!DOCTYPE html>
<html lang="de" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="robots" content="noindex, nofollow">
        <title>Wartung: {{ $info['name'] }}</title>
        <link rel="icon" type="image/png" href="/images/logo.png">
        @vite(['resources/css/app.css'])
    </head>
    <body class="min-h-screen min-h-dvh bg-white text-slate-800 antialiased flex flex-col justify-between selection:bg-brand-100 selection:text-brand-900">
        @if ($isPreviewMode)
            <div class="bg-amber-400 text-slate-950 border-b border-amber-500 px-4 py-2 text-xs sm:text-sm font-semibold w-full shrink-0">
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

        <main class="flex-1 flex flex-col items-center justify-center px-4 py-6 sm:py-8 sm:px-6">
            <div class="w-full max-w-2xl mx-auto text-center">
                <!-- Logo & Schulname nebeneinander (horizontal lockup wie im Referenz-Serviceportal) -->
                <div class="inline-flex items-center justify-center gap-3.5 sm:gap-4.5 text-left">
                    <img
                        src="/images/logo.png"
                        alt="Logo der {{ $info['name'] }}"
                        width="72"
                        height="72"
                        class="h-13 w-13 sm:h-16 sm:w-16 object-contain shrink-0"
                    />
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-tight">
                            {{ $info['name'] }}
                        </h1>
                        <p class="mt-0.5 text-xs sm:text-sm font-medium text-slate-500">
                            {{ $info['subtitle'] }}
                        </p>
                    </div>
                </div>

                <!-- Wartungshinweis (Titel & Nachricht) -->
                <div class="mt-6 sm:mt-7 space-y-2 sm:space-y-2.5">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800">
                        {{ $info['maintenance_title'] }}
                    </h2>
                    <p class="text-base sm:text-lg leading-relaxed text-slate-600 max-w-xl mx-auto">
                        {{ $info['maintenance_message'] }}
                    </p>

                    @if (! empty($info['maintenance_until']))
                        <p class="text-sm sm:text-base font-semibold text-brand-700">
                            Voraussichtlich wieder erreichbar: {{ $info['maintenance_until'] }}
                        </p>
                    @endif

                    <p class="text-sm sm:text-base text-slate-500 font-medium pt-0.5">
                        Wir bitten um Ihr Verständnis.
                    </p>
                </div>

                <!-- Kontaktdaten als reiner Text (kein Button, kein Link, sauber untereinander) -->
                @if (! empty($info['maintenance_show_contact']))
                    <div class="mt-6 sm:mt-7 pt-5 sm:pt-6 border-t border-slate-200/90 text-sm sm:text-base text-slate-600 space-y-4">
                        <!-- Schulsekretariat -->
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                                Schulsekretariat
                            </p>
                            <div class="space-y-0.5 text-slate-800">
                                <p>
                                    <span class="text-slate-500">Telefon:</span>
                                    <span class="font-semibold text-slate-900 select-all">{{ $info['phone'] }}</span>
                                </p>
                                <p>
                                    <span class="text-slate-500">E-Mail:</span>
                                    <span class="font-semibold text-slate-900 select-all">{{ $info['email'] }}</span>
                                </p>
                                @if (! empty($info['office_hours']))
                                    <p class="text-xs text-slate-500 pt-0.5">
                                        Öffnungszeiten: {{ $info['office_hours'] }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Caritas Schulkindbetreuung -->
                        @if (! empty($info['betreuung_phone']) || ! empty($info['betreuung_email']) || ! empty($info['betreuung_mobile']))
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                                    Caritas Schulkindbetreuung
                                </p>
                                <div class="space-y-0.5 text-slate-800">
                                    @if (! empty($info['betreuung_phone']))
                                        <p>
                                            <span class="text-slate-500">Telefon:</span>
                                            <span class="font-semibold text-slate-900 select-all">{{ $info['betreuung_phone'] }}</span>
                                        </p>
                                    @endif
                                    @if (! empty($info['betreuung_email']))
                                        <p>
                                            <span class="text-slate-500">E-Mail:</span>
                                            <span class="font-semibold text-slate-900 select-all">{{ $info['betreuung_email'] }}</span>
                                        </p>
                                    @endif
                                    @if (! empty($info['betreuung_mobile']))
                                        <p class="text-xs text-slate-500 pt-0.5">
                                            Mobil: <span class="font-medium text-slate-700 select-all">{{ $info['betreuung_mobile'] }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </main>

        <footer class="py-4 text-center text-xs text-slate-400 shrink-0">
            © {{ date('Y') }} {{ $info['name'] }} Freiburg-Hochdorf
        </footer>
    </body>
</html>
