import { useCallback, useEffect, useRef, type ReactElement, type TouchEvent } from 'react';

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
 * Moderne Vollbild-Lightbox mit Tastatursteuerung (Pfeiltasten Links/Rechts, Escape),
 * mobiler Touch-Swipe-Geste (Links/Rechts wischen), Scroll-Lock sowie Bildzähler.
 */
export function PhotoLightbox({
    images,
    activeIndex,
    onClose,
    onChangeIndex,
}: PhotoLightboxProps): ReactElement | null {
    const total = images.length;
    const touchStartX = useRef<number | null>(null);
    const touchStartY = useRef<number | null>(null);

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

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

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
        return () => {
            document.body.style.overflow = previousOverflow;
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [activeIndex, showPrev, showNext, onClose]);

    if (activeIndex === null || !images[activeIndex]) {
        return null;
    }

    const current = images[activeIndex];

    const handleTouchStart = (event: TouchEvent<HTMLDivElement>): void => {
        const firstTouch = event.touches[0];
        if (!firstTouch) {
            return;
        }
        touchStartX.current = firstTouch.clientX;
        touchStartY.current = firstTouch.clientY;
    };

    const handleTouchEnd = (event: TouchEvent<HTMLDivElement>): void => {
        const endTouch = event.changedTouches[0];
        if (!endTouch || touchStartX.current === null || touchStartY.current === null) {
            return;
        }

        const deltaX = endTouch.clientX - touchStartX.current;
        const deltaY = endTouch.clientY - touchStartY.current;
        touchStartX.current = null;
        touchStartY.current = null;

        // Horizontales Wischen erkennen (mind. 40px und stärker als vertikales Scrollen)
        if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
            if (deltaX < 0) {
                showNext();
            } else {
                showPrev();
            }
        }
    };

    return (
        <div
            role="dialog"
            aria-modal="true"
            aria-label={current.alt}
            className="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-950/92 backdrop-blur-md p-3 sm:p-8 select-none"
            onClick={onClose}
            onTouchStart={handleTouchStart}
            onTouchEnd={handleTouchEnd}
        >
            {/* Bildzähler oben links */}
            {total > 1 && (
                <div
                    onClick={(e) => e.stopPropagation()}
                    className="fixed top-4 left-4 sm:top-5 sm:left-5 z-20 rounded-full border border-white/20 bg-slate-900/70 px-3.5 py-1.5 text-xs font-extrabold text-white backdrop-blur-md"
                >
                    {activeIndex + 1} / {total}
                </div>
            )}

            {/* Dezenter Schließen-Button oben rechts (mind. 44x44px Touch-Target) */}
            <button
                type="button"
                onClick={(e) => {
                    e.stopPropagation();
                    onClose();
                }}
                aria-label="Ansicht schließen"
                className="fixed top-4 right-4 sm:top-5 sm:right-5 z-20 flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-slate-900/70 text-white backdrop-blur-md transition hover:scale-105 hover:bg-brand-600 hover:border-brand-400 cursor-pointer"
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
                    className="fixed left-2 sm:left-6 top-1/2 -translate-y-1/2 z-20 flex h-11 w-11 sm:h-14 sm:w-14 items-center justify-center rounded-full border border-white/25 bg-slate-900/65 text-white shadow-xl backdrop-blur-md transition duration-200 hover:scale-110 hover:bg-brand-600 hover:border-brand-400 focus:outline-2 focus:outline-brand-400 cursor-pointer"
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

            {/* Hauptbild + Bildunterschrift */}
            <div
                className="relative flex flex-col max-h-[82dvh] sm:max-h-[88dvh] max-w-[94vw] sm:max-w-[85vw] items-center justify-center"
                onClick={(e) => e.stopPropagation()}
            >
                <img
                    src={current.url}
                    alt={current.alt}
                    className="max-h-[74dvh] sm:max-h-[82dvh] max-w-[94vw] sm:max-w-[82vw] rounded-2xl object-contain shadow-2xl ring-1 ring-white/15"
                />
                {current.alt && (
                    <p className="mt-2.5 max-w-xl text-center text-xs sm:text-sm font-medium text-slate-200 line-clamp-2 px-4">
                        {current.alt}
                    </p>
                )}
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
                    className="fixed right-2 sm:right-6 top-1/2 -translate-y-1/2 z-20 flex h-11 w-11 sm:h-14 sm:w-14 items-center justify-center rounded-full border border-white/25 bg-slate-900/65 text-white shadow-xl backdrop-blur-md transition duration-200 hover:scale-110 hover:bg-brand-600 hover:border-brand-400 focus:outline-2 focus:outline-brand-400 cursor-pointer"
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

