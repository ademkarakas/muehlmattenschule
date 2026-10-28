import { useCallback, useEffect, type ReactElement } from 'react';

export interface LightboxImageItem {
    url: string;
    alt: string;
}

export interface PhotoLightboxProps {
    images: LightboxImageItem[];
    activeIndex: number | null;
    onClose: () => void;
    onChangeIndex: (newIndex: number) => void;
}

/**
 * Moderne Vollbild-Lightbox mit Tastatursteuerung (Pfeiltasten Links/Rechts, Escape)
 * sowie schwebenden Links-/Rechts-Pfeilbuttons direkt neben dem Foto.
 */
export function PhotoLightbox({
    images,
    activeIndex,
    onClose,
    onChangeIndex,
}: PhotoLightboxProps): ReactElement | null {
    const total = images.length;

    const showPrev = useCallback((): void => {
        if (activeIndex === null || total <= 1) {
            return;
        }
        onChangeIndex((activeIndex - 1 + total) % total);
    }, [activeIndex, total, onChangeIndex]);

    const showNext = useCallback((): void => {
        if (activeIndex === null || total <= 1) {
            return;
        }
        onChangeIndex((activeIndex + 1) % total);
    }, [activeIndex, total, onChangeIndex]);

    useEffect(() => {
        if (activeIndex === null) {
            return;
        }

        const handleKeyDown = (event: KeyboardEvent): void => {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                showPrev();
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                showNext();
            } else if (event.key === 'Escape') {
                event.preventDefault();
                onClose();
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [activeIndex, showPrev, showNext, onClose]);

    if (activeIndex === null || !images[activeIndex]) {
        return null;
    }

    const current = images[activeIndex];

    return (
        <div
            role="dialog"
            aria-modal="true"
            aria-label={current.alt}
            className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/92 backdrop-blur-md p-4 sm:p-8 select-none"
            onClick={onClose}
        >
            {/* Dezenter Schließen-Button oben rechts */}
            <button
                type="button"
                onClick={(e) => {
                    e.stopPropagation();
                    onClose();
                }}
                aria-label="Ansicht schließen"
                className="fixed top-5 right-5 z-20 flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white backdrop-blur-md transition hover:scale-105 hover:bg-brand-600 hover:border-brand-400 cursor-pointer"
            >
                <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {/* Linker Pfeil-Button */}
            {total > 1 && (
                <button
                    type="button"
                    onClick={(e) => {
                        e.stopPropagation();
                        showPrev();
                    }}
                    aria-label="Vorheriges Foto"
                    className="fixed left-3 sm:left-6 top-1/2 -translate-y-1/2 z-20 flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full border border-white/25 bg-slate-900/60 text-white shadow-xl backdrop-blur-md transition duration-200 hover:scale-110 hover:bg-brand-600 hover:border-brand-400 focus:outline-2 focus:outline-brand-400 cursor-pointer"
                >
                    <svg
                        className="h-6 w-6 sm:h-7 sm:w-7 -ml-0.5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="2.4"
                    >
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            )}

            {/* Hauptbild */}
            <div
                className="relative flex max-h-[88vh] max-w-[85vw] items-center justify-center"
                onClick={(e) => e.stopPropagation()}
            >
                <img
                    src={current.url}
                    alt={current.alt}
                    className="max-h-[86vh] max-w-[82vw] rounded-2xl object-contain shadow-2xl ring-1 ring-white/15"
                />
            </div>

            {/* Rechter Pfeil-Button */}
            {total > 1 && (
                <button
                    type="button"
                    onClick={(e) => {
                        e.stopPropagation();
                        showNext();
                    }}
                    aria-label="Nächstes Foto"
                    className="fixed right-3 sm:right-6 top-1/2 -translate-y-1/2 z-20 flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full border border-white/25 bg-slate-900/60 text-white shadow-xl backdrop-blur-md transition duration-200 hover:scale-110 hover:bg-brand-600 hover:border-brand-400 focus:outline-2 focus:outline-brand-400 cursor-pointer"
                >
                    <svg
                        className="h-6 w-6 sm:h-7 sm:w-7 ml-0.5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="2.4"
                    >
                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            )}
        </div>
    );
}
