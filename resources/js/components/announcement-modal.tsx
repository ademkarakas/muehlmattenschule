import type { AnnouncementItem } from '@/types/school';
import { Link } from '@inertiajs/react';
import { useCallback, useEffect, useMemo, useRef, useState, type ReactElement } from 'react';

export interface AnnouncementModalProps {
    announcements: AnnouncementItem[];
    schoolName: string;
}

const STORAGE_KEY = 'mms_dismissed_announcements_v1';

/**
 * Erzeugt eine eindeutige Signatur für eine Ankündigung, damit das Modal automatisch
 * wieder erscheint, sobald im Admin-Panel eine neue Ankündigung erstellt oder ein Text geändert wird.
 */
function getAnnouncementSignature(item: AnnouncementItem): string {
    return `${item.id}:${item.level}:${item.text}`;
}

/**
 * Liest bereits geschlossene Ankündigungs-Signaturen aus dem SessionStorage.
 */
function readDismissedSignatures(): Set<string> {
    if (typeof window === 'undefined') {
        return new Set<string>();
    }

    try {
        const raw = window.sessionStorage.getItem(STORAGE_KEY);
        if (!raw) {
            return new Set<string>();
        }
        const parsed: unknown = JSON.parse(raw);
        if (Array.isArray(parsed)) {
            return new Set<string>(parsed.filter((entry): entry is string => typeof entry === 'string'));
        }
    } catch {
        // SessionStorage nicht verfügbar oder ungültiges Format
    }

    return new Set<string>();
}

/**
 * Speichert geschlossene Ankündigungs-Signaturen im SessionStorage, damit Besucher
 * nach dem Schließen ungestört auf der Website weiterblättern können.
 */
function writeDismissedSignatures(signatures: Iterable<string>): void {
    if (typeof window === 'undefined') {
        return;
    }

    try {
        window.sessionStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(signatures)));
    } catch {
        // Ignorieren (z. B. im privaten Modus blockiert)
    }
}

/**
 * Modernes, großzügiges und barrierefreies Ankündigungs-Modal:
 * Öffnet sich beim Betreten der Website zentriert im Vordergrund, lässt sich per
 * Schließen-Kreuz (×), Escape-Taste (Esc) oder Klick außerhalb schließen
 * und bietet anschließend einen kontrastreichen Schnellzugriff-Button zum erneuten Öffnen.
 */
export function AnnouncementModal({
    announcements,
    schoolName,
}: AnnouncementModalProps): ReactElement | null {
    const closeButtonRef = useRef<HTMLButtonElement | null>(null);
    const reopenButtonRef = useRef<HTMLButtonElement | null>(null);

    const currentSignatures = useMemo(
        () => announcements.map((item) => getAnnouncementSignature(item)),
        [announcements],
    );

    const [isOpen, setIsOpen] = useState<boolean>(() => {
        if (announcements.length === 0 || typeof window === 'undefined') {
            return false;
        }
        const dismissed = readDismissedSignatures();
        return currentSignatures.some((sig) => !dismissed.has(sig));
    });

    // Öffnet das Modal automatisch, falls während der Sitzung eine neue Ankündigung hinzukommt
    useEffect(() => {
        if (announcements.length === 0) {
            setIsOpen(false);
            return;
        }

        const dismissed = readDismissedSignatures();
        const hasUnseen = currentSignatures.some((sig) => !dismissed.has(sig));
        if (hasUnseen) {
            setIsOpen(true);
        }
    }, [announcements.length, currentSignatures]);

    const handleClose = useCallback((): void => {
        const dismissed = readDismissedSignatures();
        for (const sig of currentSignatures) {
            dismissed.add(sig);
        }
        writeDismissedSignatures(dismissed);
        setIsOpen(false);
    }, [currentSignatures]);

    const handleReopen = useCallback((): void => {
        setIsOpen(true);
    }, []);

    // Tastatursteuerung (Escape) & Hintergrund-Scroll-Sperre bei geöffnetem Dialog
    useEffect(() => {
        if (!isOpen) {
            return;
        }

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        const focusTimer = window.setTimeout(() => {
            closeButtonRef.current?.focus();
        }, 20);

        const handleKeyDown = (event: KeyboardEvent): void => {
            if (event.key === 'Escape') {
                event.preventDefault();
                handleClose();
                window.setTimeout(() => {
                    reopenButtonRef.current?.focus();
                }, 20);
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => {
            window.clearTimeout(focusTimer);
            document.body.style.overflow = previousOverflow;
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [isOpen, handleClose]);

    if (announcements.length === 0) {
        return null;
    }

    const hasImportant = announcements.some((item) => item.level === 'important');

    return (
        <>
            {/* Kontrastreicher Schnellzugriff-Button unten links, sobald das Modal geschlossen wurde */}
            {!isOpen && (
                <button
                    ref={reopenButtonRef}
                    type="button"
                    onClick={handleReopen}
                    aria-label="Aktuelle Schulankündigung erneut öffnen"
                    title="Aktuelle Ankündigung anzeigen"
                    className="fixed bottom-5 left-5 sm:bottom-7 sm:left-7 z-40 inline-flex min-h-11 items-center gap-2.5 rounded-full bg-slate-900 px-4.5 py-2.5 text-xs sm:text-sm font-extrabold text-white shadow-xl ring-2 ring-amber-400 transition-all duration-300 hover:-translate-y-0.5 hover:scale-105 hover:bg-brand-800 hover:shadow-2xl active:scale-95 cursor-pointer group"
                >
                    <span className="relative flex h-2.5 w-2.5 shrink-0" aria-hidden="true">
                        <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75" />
                        <span className="relative inline-flex h-2.5 w-2.5 rounded-full bg-amber-400" />
                    </span>

                    <svg
                        className="h-4.5 w-4.5 shrink-0 text-amber-400 transition-transform duration-200 group-hover:-rotate-12"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        strokeWidth="2"
                        aria-hidden="true"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"
                        />
                    </svg>

                    <span className="text-white">
                        {hasImportant ? 'Wichtige Mitteilung' : 'Aktuelle Ankündigung'}
                        {announcements.length > 1 ? ` (${announcements.length})` : ''}
                    </span>
                </button>
            )}

            {/* Großzügiges, zentriertes Modal-Fenster beim Betreten der Website */}
            {isOpen && (
                <div
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="announcement-modal-title"
                    aria-describedby="announcement-modal-body"
                    onClick={handleClose}
                    className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/65 backdrop-blur-xs p-4 sm:p-6 lg:p-10 overflow-y-auto"
                >
                    <div
                        onClick={(event) => event.stopPropagation()}
                        className="relative w-full max-w-2xl lg:max-w-3xl overflow-hidden rounded-3xl border-t-4 border-amber-400 bg-white shadow-[0_32px_90px_-12px_rgba(15,23,42,0.62)] ring-1 ring-slate-900/10 my-auto"
                    >
                        {/* Kopfbereich im originalen Logo-Türkis (#029786) mit offiziellem Schullogo & gut sichtbarem Schließen-Kreuz (×) */}
                        <div className="relative bg-gradient-to-r from-brand-700 via-brand-500 to-brand-700 px-6 py-5 sm:px-9 sm:py-6 text-white">
                            <div className="flex items-center justify-between gap-4">
                                <div className="flex items-center gap-4 sm:gap-5 min-w-0">
                                    <div className="relative shrink-0">
                                        <img
                                            src="/images/logo.png"
                                            alt={`Logo ${schoolName}`}
                                            width={64}
                                            height={64}
                                            className="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl bg-white p-1.5 object-contain shadow-lg ring-2 ring-white/60"
                                        />
                                        <span
                                            aria-hidden="true"
                                            className="absolute -bottom-1.5 -right-1.5 flex h-7 w-7 items-center justify-center rounded-full bg-amber-400 text-slate-950 shadow-md ring-2 ring-brand-700"
                                        >
                                            <svg
                                                className="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                strokeWidth="2.2"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"
                                                />
                                            </svg>
                                        </span>
                                    </div>

                                    <div className="min-w-0">
                                        <p className="truncate text-xl sm:text-2xl font-extrabold tracking-tight text-white leading-tight">
                                            {schoolName}
                                        </p>
                                        <h2
                                            id="announcement-modal-title"
                                            className="mt-0.5 text-sm sm:text-base font-bold tracking-wide text-amber-300 leading-snug"
                                        >
                                            {hasImportant
                                                ? 'Wichtige Ankündigung'
                                                : 'Aktuelle Ankündigung'}
                                        </h2>
                                    </div>
                                </div>

                                {/* Schließen-Kreuz (×) oben rechts – hoher Kontrast & 48×48px Klickfläche */}
                                <button
                                    ref={closeButtonRef}
                                    type="button"
                                    onClick={handleClose}
                                    aria-label="Ankündigungsfenster schließen (Escape)"
                                    title="Schließen (Esc)"
                                    className="flex h-11 w-11 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-full bg-slate-900/55 text-white ring-2 ring-amber-400 shadow-md transition-all duration-200 hover:scale-105 hover:rotate-90 hover:bg-amber-400 hover:text-slate-950 focus-visible:outline-2 focus-visible:outline-amber-400 cursor-pointer"
                                >
                                    <svg
                                        className="h-5 w-5 sm:h-6 sm:w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="2.5"
                                        aria-hidden="true"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {/* Großzügiger Mitteilungsinhalt */}
                        <div
                            id="announcement-modal-body"
                            className="max-h-[72dvh] overflow-y-auto p-6 sm:p-9 space-y-5 bg-gradient-to-b from-white via-white to-brand-50/25"
                        >
                            {announcements.map((item) => {
                                const isImportant = item.level === 'important';
                                return (
                                    <div
                                        key={item.id}
                                        className={`rounded-2xl border p-6 sm:p-7 transition ${
                                            isImportant
                                                ? 'border-amber-300 bg-gradient-to-br from-amber-50/90 via-white to-orange-50/50 shadow-xs'
                                                : 'border-brand-200/80 bg-gradient-to-br from-brand-50/55 via-white to-brand-50/20 shadow-xs'
                                        }`}
                                    >
                                        {announcements.length > 1 && (
                                            <div className="mb-3 flex items-center gap-2">
                                                <span
                                                    className={`inline-flex items-center rounded-lg px-3 py-1 text-xs font-extrabold uppercase tracking-wider ${
                                                        isImportant
                                                            ? 'bg-amber-400 text-slate-950'
                                                            : 'bg-brand-700 text-white'
                                                    }`}
                                                >
                                                    {isImportant ? 'Wichtig' : 'Ankündigung'}
                                                </span>
                                            </div>
                                        )}

                                        <p className="text-lg sm:text-xl font-bold text-slate-900 leading-relaxed break-words">
                                            {item.text}
                                        </p>

                                        {item.linkUrl && (
                                            <div className="mt-6 pt-5 border-t border-slate-200/80 flex items-center justify-end">
                                                <Link
                                                    href={item.linkUrl}
                                                    onClick={handleClose}
                                                    className={`inline-flex min-h-12 items-center justify-center gap-2.5 rounded-xl px-6 py-3 text-sm sm:text-base font-extrabold tracking-wide no-underline shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg ${
                                                        isImportant
                                                            ? 'bg-amber-400 !text-slate-950 ring-1 ring-amber-500/40 hover:bg-amber-300'
                                                            : 'bg-brand-700 !text-white ring-1 ring-brand-800/30 hover:bg-brand-800'
                                                    }`}
                                                >
                                                    <span className={isImportant ? 'text-slate-950' : 'text-white'}>
                                                        {item.linkLabel || 'Mehr erfahren'}
                                                    </span>
                                                    <svg
                                                        className={`h-5 w-5 shrink-0 ${
                                                            isImportant ? 'text-slate-950' : 'text-white'
                                                        }`}
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth="2.4"
                                                        aria-hidden="true"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                                        />
                                                    </svg>
                                                </Link>
                                            </div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}
