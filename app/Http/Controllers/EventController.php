<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Zeigt die Terminübersicht (gruppiert nach Monaten).
     */
    public function index(): Response
    {
        $events = Event::query()
            ->upcoming()
            ->get()
            ->map(fn (Event $event): array => [
                'id' => $event->id,
                'title' => $event->title,
                'monthHeading' => $event->starts_at->locale('de')->isoFormat('MMMM YYYY'),
                'day' => $event->starts_at->format('d'),
                'weekday' => $event->starts_at->locale('de')->isoFormat('dd'),
                'dateFormatted' => $event->starts_at->format('d.m.Y'),
                'endDateFormatted' => $event->ends_at?->format('d.m.Y'),
                'timeFormatted' => $event->all_day
                    ? 'Ganztägig'
                    : $event->starts_at->format('H:i').($event->ends_at ? ' – '.$event->ends_at->format('H:i') : '').' Uhr',
                'location' => $event->location,
                'description' => $event->description,
            ])
            ->all();

        return Inertia::render('events/index', [
            'events' => $events,
        ]);
    }
}
