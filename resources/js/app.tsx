import { createInertiaApp } from '@inertiajs/react';
import { StrictMode, type ComponentType } from 'react';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Mühlmattenschule';
const pages = import.meta.glob<{ default: ComponentType }>('./pages/**/*.tsx');

/**
 * Initialisiert die Inertia-React-Anwendung im Browser.
 */
void createInertiaApp({
    title: (title) => (title ? `${title} – ${appName}` : appName),
    resolve: async (name) => {
        const loader = pages[`./pages/${name}.tsx`];
        if (!loader) {
            throw new Error(`Inertia-Seite nicht gefunden: ${name}`);
        }
        const module = await loader();
        return module.default;
    },
    setup({ el, App, props }) {
        if (!el) {
            return;
        }
        createRoot(el).render(
            <StrictMode>
                <App {...props} />
            </StrictMode>,
        );
    },
    progress: {
        color: '#00b4d8',
    },
});

