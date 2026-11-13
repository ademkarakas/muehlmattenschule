import { useEffect, useState, type ReactElement } from 'react';

/**
 * Dezenter, GPU-beschleunigter Lesefortschrittsbalken am oberen Fensterrand.
 * Blendet sich bei Seiten ohne Scrollbereich oder bei „prefers-reduced-motion“ aus.
 */
export function ScrollProgress(): ReactElement | null {
    const [progress, setProgress] = useState(0);
    const [enabled, setEnabled] = useState(false);

    useEffect(() => {
        if (typeof window === 'undefined') {
            return;
        }

        const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (motionQuery.matches) {
            setEnabled(false);
            return;
        }

        setEnabled(true);
        let frameId: number | null = null;

        const updateProgress = (): void => {
            frameId = null;
            const doc = document.documentElement;
            const scrollable = doc.scrollHeight - window.innerHeight;
            if (scrollable <= 80) {
                setProgress(0);
                return;
            }
            const next = Math.min(1, Math.max(0, window.scrollY / scrollable));
            setProgress(next);
        };

        const handleScroll = (): void => {
            if (frameId === null) {
                frameId = window.requestAnimationFrame(updateProgress);
            }
        };

        updateProgress();
        window.addEventListener('scroll', handleScroll, { passive: true });
        window.addEventListener('resize', handleScroll, { passive: true });

        return () => {
            window.removeEventListener('scroll', handleScroll);
            window.removeEventListener('resize', handleScroll);
            if (frameId !== null) {
                window.cancelAnimationFrame(frameId);
            }
        };
    }, []);

    if (!enabled || progress <= 0.005) {
        return null;
    }

    return (
        <div
            aria-hidden="true"
            className="pointer-events-none fixed top-0 left-0 right-0 z-50 h-[3px] bg-brand-900/10"
        >
            <div
                className="h-full w-full origin-left bg-gradient-to-r from-brand-400 via-brand-500 to-amber-400 transition-transform duration-100 ease-out"
                style={{ transform: `scaleX(${progress})` }}
            />
        </div>
    );
}
