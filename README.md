# Mühlmattenschule Freiburg-Hochdorf – Website

Dynamische Schulwebsite der **Mühlmattenschule Freiburg-Hochdorf** (Grundschule für Hochdorf und Benzhausen) mit EduPage-ähnlicher Startseite (Aktuelles, Termine, Fotoalben, Ankündigungen) und einem deutschsprachigen Admin-Panel für Schulleitung und Sekretariat.

- **Für Entwickler / KI-Assistenten (Übergabe):** Zuerst [`docs/HANDOFF.md`](docs/HANDOFF.md) lesen, danach [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) und [`docs/ROADMAP.md`](docs/ROADMAP.md).

## Tech-Stack

| Bereich | Technologie |
|---|---|
| Backend | PHP 8.3, Laravel 13 |
| Admin-Panel | Filament (`/admin`) |
| Frontend | React 19, TypeScript (strict), Inertia.js |
| Styling | Tailwind CSS 4 (Hauptfarbe Türkis `#14b8a6`) |
| Medien | Spatie Media Library |
| Datenbank | Lokal: SQLite · Produktion: MariaDB (IONOS Deploy Now) |

## Voraussetzungen

- PHP 8.3+ (mit `intl`, `gd`, `exif`, `zip`, `pdo_sqlite`, `pdo_mysql`)
- Composer 2.8+
- Node.js 20+ & npm

## Installation (lokal)

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

## Entwicklung starten

```powershell
composer run dev
```

- Öffentliche Website: http://127.0.0.1:8000
- Verwaltung (Admin-Panel): http://127.0.0.1:8000/admin

## Tests & Typprüfung

```powershell
npx tsc --noEmit
php artisan test
```

## Projektstruktur

Siehe [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) und Fortschrittsliste in [`docs/ROADMAP.md`](docs/ROADMAP.md).
