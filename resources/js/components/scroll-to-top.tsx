import { useEffect, useState, type ReactElement } from 'react';

/**
 * Moderner Scroll-to-Top-Button mit kreisförmiger Fortschrittsanzeige (SVG-Ring).
 * Füllt sich beim Herunterscrollen prozentual auf und bringt den Nutzer
 * mit einem Klick sanft an den Seitenanfang zurück.
 */
export function ScrollToTop(): ReactElement {
    const [progress, setProgress] = useState(0);
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        const handleScroll = (): void => {
            const scrollY = window.scrollY;
            const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;

            if (scrollY > 200) {
                setIsVisible(true);
            } else {
                setIsVisible(false);
            }

            if (scrollHeight > 0) {
                const currentProgress = Math.min(Math.max(scrollY / scrollHeight, 0), 1);
                setProgress(currentProgress);
            } else {
                setProgress(0);
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();

        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const scrollToTop = (): void => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    };

    // SVG Kreisberechnung: r = 18, Umfang = 2 * PI * 18 = 113.097
    const radius = 18;
    const circumference = 2 * Math.PI * radius;
    const strokeDashoffset = circumference - progress * circumference;

    return (
        <button
            type="button"
            onClick={scrollToTop}
            aria-label="Nach oben scrollen"
            title="Nach oben scrollen"
            className={`fixed bottom-5 right-5 sm:bottom-7 sm:right-7 z-40 flex h-12 w-12 sm:h-13 sm:w-13 items-center justify-center rounded-full bg-white/95 text-brand-700 shadow-xl backdrop-blur-md ring-1 ring-slate-900/10 transition-all duration-300 hover:scale-110 hover:text-brand-900 hover:shadow-2xl active:scale-95 cursor-pointer group ${
                isVisible
                    ? 'opacity-100 translate-y-0 pointer-events-auto'
                    : 'opacity-0 translate-y-4 pointer-events-none'
            }`}
        >
            {/* SVG Fortschritts-Ring */}
            <svg
                className="absolute inset-0 h-full w-full -rotate-90 p-1"
                viewBox="0 0 44 44"
                aria-hidden="true"
            >
                {/* Hintergrund-Kreis (inaktiv) */}
                <circle
                    cx="22"
                    cy="22"
                    r={radius}
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="3"
                    className="text-brand-100"
                />
                {/* Dynamischer Füll-Kreis (aktiv) */}
                <circle
                    cx="22"
                    cy="22"
                    r={radius}
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="3.2"
                    strokeDasharray={circumference}
                    strokeDashoffset={strokeDashoffset}
                    strokeLinecap="round"
                    className="text-brand-600 transition-[stroke-dashoffset] duration-75"
                />
            </svg>

            {/* Pfeil nach oben im Zentrum */}
            <svg
                className="relative h-5 w-5 sm:h-6 sm:w-6 transition-transform duration-200 group-hover:-translate-y-0.5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2.5"
                strokeLinecap="round"
                strokeLinejoin="round"
                aria-hidden="true"
            >
                <path d="M18 15l-6-6-6 6" />
            </svg>
        </button>
    );
}

