<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Support\SeoData;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Zeigt die Terminübersicht (gruppiert nach Monaten).
     */
    public function index(): Response
    {
        $school = SeoData::schoolSettings();
        $eventModels = Event::query()
            ->upcoming()
            ->get();

        $events = $eventModels
            ->map(fn (Event $event): array => [
                'id' => $event->id,
                'title' => $event->title,
                'startsAtIso' => $event->starts_at->toIso8601String(),
                'monthHeading' => $event->starts_at->locale('de')->isoFormat('MMMM YYYY'),
                'day' => $event->starts_at->format('d'),
                'weekday' => $event->starts_at->locale('de')->isoFormat('dd'),
                'dateFormatted' => $event->starts_at->format('d.m.Y'),
                'endDateFormatted' => $event->ends_at?->format('d.m.Y'),
                'timeFormatted' => $event->all_day
                    ? 'Ganztägig'
                    : $event->starts_at->format('H:i').($event->ends_at ? ' – '.$event->ends_at->format('H:i') : '').' Uhr',
                'location' => $event->location,
                'className' => $event->class_name,
                'category' => $event->category ?? 'Termin',
                'description' => $event->description,
                'icalUrl' => '/termine/'.$event->id.'/ical',
            ])
            ->all();

        $eventSchemaNodes = $eventModels
            ->take(15)
            ->map(fn (Event $event): array => [
                '@type' => 'Event',
                'name' => $event->title,
                'startDate' => $event->all_day
                    ? $event->starts_at->toDateString()
                    : $event->starts_at->toIso8601String(),
                'endDate' => $event->ends_at
                    ? ($event->all_day ? $event->ends_at->toDateString() : $event->ends_at->toIso8601String())
                    : ($event->all_day ? $event->starts_at->toDateString() : $event->starts_at->toIso8601String()),
                'eventStatus' => 'https://schema.org/EventScheduled',
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'description' => $event->description ?: "Schultermin an der {$school['name']}",
                'location' => [
                    '@type' => 'Place',
                    'name' => $event->location ?: $school['name'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $school['street'],
                        'postalCode' => '79108',
                        'addressLocality' => 'Freiburg im Breisgau',
                        'addressCountry' => 'DE',
                    ],
                ],
                'organizer' => [
                    '@id' => url('/').'#school',
                ],
            ])
            ->values()
            ->all();

        return Inertia::render('events/index', [
            'events' => $events,
            'seo' => SeoData::make([
                'title' => 'Termine & Schulkalender',
                'description' => 'Alle anstehenden Schultermine, Elternabende, Feste, Projekte und Ferientage der Mühlmattenschule Freiburg-Hochdorf im Überblick.',
                'canonicalPath' => '/termine',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Termine & Kalender', 'href' => null],
                ],
                'schemaNodes' => $eventSchemaNodes,
            ]),
        ]);
    }

    /**
     * Exportiert einen einzelnen veröffentlichten Schultermin als iCalendar (.ics) Datei.
     */
    public function icalSingle(Event $event): HttpResponse
    {
        abort_unless($event->is_published, 404);

        $ics = $this->buildIcsCalendar([$event]);
        $filename = Str::slug($event->title ?: 'schultermin').'.ics';

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Exportiert alle kommenden veröffentlichten Schultermine als iCalendar (.ics) Abo-Datei.
     */
    public function icalAll(): HttpResponse
    {
        $events = Event::query()->upcoming()->get()->all();
        $ics = $this->buildIcsCalendar($events);

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="muehlmattenschule-termine.ics"',
        ]);
    }

    /**
     * Erstellt den RFC-5545-konformen iCalendar-Inhalt für eine Liste von Terminen.
     *
     * @param  array<int, Event>  $events
     */
    private function buildIcsCalendar(array $events): string
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Mühlmattenschule Freiburg-Hochdorf//Schulkalender//DE',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:Mühlmattenschule Termine',
            'X-WR-TIMEZONE:Europe/Berlin',
        ];

        $nowStamp = now()->utc()->format('Ymd\THis\Z');

        foreach ($events as $event) {
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:event-'.$event->id.'@muehlmattenschule.de';
            $lines[] = 'DTSTAMP:'.$nowStamp;

            if ($event->all_day) {
                $startDate = $event->starts_at->format('Ymd');
                $endDate = ($event->ends_at ?? $event->starts_at)->copy()->addDay()->format('Ymd');
                $lines[] = 'DTSTART;VALUE=DATE:'.$startDate;
                $lines[] = 'DTEND;VALUE=DATE:'.$endDate;
            } else {
                $startUtc = $event->starts_at->copy()->utc()->format('Ymd\THis\Z');
                $endUtc = ($event->ends_at ?? $event->starts_at->copy()->addHour())->copy()->utc()->format('Ymd\THis\Z');
                $lines[] = 'DTSTART:'.$startUtc;
                $lines[] = 'DTEND:'.$endUtc;
            }

            $summary = $event->title.($event->class_name ? ' ('.$event->class_name.')' : '');
            $lines[] = 'SUMMARY:'.$this->escapeIcsText($summary);

            if ($event->location) {
                $lines[] = 'LOCATION:'.$this->escapeIcsText($event->location);
            }

            if ($event->description) {
                $lines[] = 'DESCRIPTION:'.$this->escapeIcsText($event->description);
            }

            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", $lines)."\r\n";
    }

    /**
     * Maskiert Sonderzeichen gemäß RFC 5545 für iCalendar-Textfelder.
     */
    private function escapeIcsText(string $text): string
    {
        $clean = str_replace(["\r\n", "\r", "\n"], '\\n', strip_tags($text));

        return addcslashes($clean, ',;\\');
    }
}
