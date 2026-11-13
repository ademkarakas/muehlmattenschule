import { useEffect, useRef, useState, type CSSProperties, type ReactElement, type ReactNode } from 'react';

export type RevealVariant = 'fade-up' | 'fade-down' | 'fade-left' | 'fade-right' | 'zoom-in' | 'fade';

export type RevealElementTag = 'div' | 'section' | 'article' | 'header' | 'aside' | 'footer' | 'li';

export interface RevealProps {
    children: ReactNode;
    variant?: RevealVariant;
    delay?: number;
    duration?: number;
    once?: boolean;
    threshold?: number;
    as?: RevealElementTag;
    className?: string;
    'aria-label'?: string;
}

/**
 * Blendet Inhalte beim Scrollen sanft und GPU-beschleunigt in den sichtbaren Bereich ein.
 * Respektiert „prefers-reduced-motion“ und bleibt ohne IntersectionObserver sofort sichtbar.
 *
 * @param props - Konfiguration für Animationsvariante, Verzögerung, Dauer und HTML-Tag.
 */
export function Reveal({
    children,
    variant = 'fade-up',
    delay = 0,
    duration = 600,
    once = true,
    threshold = 0.08,
    as = 'div',
    className = '',
    'aria-label': ariaLabel,
}: RevealProps): ReactElement {
    const elementRef = useRef<HTMLElement | null>(null);
    const [isVisible, setIsVisible] = useState<boolean>(() => {
        if (typeof window === 'undefined') {
            return true;
        }
        if (typeof window.IntersectionObserver === 'undefined') {
            return true;
        }
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    });

    useEffect(() => {
        if (isVisible && once) {
            return;
        }

        const node = elementRef.current;
        if (!node || typeof window === 'undefined' || typeof window.IntersectionObserver === 'undefined') {
            setIsVisible(true);
            return;
        }

        const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (motionQuery.matches) {
            setIsVisible(true);
            return;
        }

        const observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) {
                        setIsVisible(true);
                        if (once) {
                            observer.unobserve(entry.target);
                        }
                    } else if (!once) {
                        setIsVisible(false);
                    }
                }
            },
            {
                threshold,
                rootMargin: '0px 0px -36px 0px',
            },
        );

        observer.observe(node);

        return () => {
            observer.disconnect();
        };
    }, [isVisible, once, threshold]);

    const style = {
        '--reveal-delay': `${delay}ms`,
        '--reveal-duration': `${duration}ms`,
    } as CSSProperties;

    const Tag = as;

    return (
        <Tag
            ref={(el: HTMLElement | null) => {
                elementRef.current = el;
            }}
            aria-label={ariaLabel}
            data-variant={variant}
            data-visible={isVisible ? 'true' : 'false'}
            style={style}
            className={`reveal-item ${className}`.trim()}
        >
            {children}
        </Tag>
    );
}
