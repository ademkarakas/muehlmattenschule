<div
    id="mms-unsaved-dialog-backdrop"
    class="mms-unsaved-backdrop"
    hidden
    aria-hidden="true"
>
    <div
        id="mms-unsaved-dialog"
        class="mms-unsaved-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="mms-unsaved-title"
        aria-describedby="mms-unsaved-desc"
    >
        <div class="mms-unsaved-body">
            <div class="mms-unsaved-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="mms-unsaved-content">
                <h2 id="mms-unsaved-title" class="mms-unsaved-title">
                    Ungespeicherte Änderungen
                </h2>
                <p id="mms-unsaved-desc" class="mms-unsaved-desc">
                    Sie haben Änderungen vorgenommen, die noch nicht gespeichert wurden. Möchten Sie diese Seite wirklich verlassen?
                </p>
            </div>
        </div>

        <div class="mms-unsaved-actions">
            <button
                type="button"
                id="mms-unsaved-stay"
                class="mms-unsaved-btn mms-unsaved-btn-stay"
            >
                Abbrechen
            </button>
            <button
                type="button"
                id="mms-unsaved-leave"
                class="mms-unsaved-btn mms-unsaved-btn-leave"
            >
                Seite verlassen
            </button>
        </div>
    </div>
</div>

<style>
    .mms-unsaved-backdrop[hidden] {
        display: none !important;
    }

    .mms-unsaved-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background-color: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(3px);
        animation: mms-backdrop-fade 0.14s ease-out;
    }

    .mms-unsaved-dialog {
        width: 100%;
        max-width: 420px;
        background-color: var(--color-card-bg, #ffffff);
        color: var(--color-text-primary, #0f172a);
        border: 1px solid var(--color-card-border, #e2e8f0);
        border-radius: var(--radius-xl, 12px);
        box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.24);
        padding: 1.25rem;
        animation: mms-dialog-pop 0.16s ease-out;
    }

    .mms-unsaved-body {
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
    }

    .mms-unsaved-icon {
        flex-shrink: 0;
        width: 2.375rem;
        height: 2.375rem;
        border-radius: var(--radius-lg, 10px);
        background-color: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dark .mms-unsaved-icon {
        background-color: rgba(217, 119, 6, 0.18);
        color: #fbbf24;
    }

    .mms-unsaved-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .mms-unsaved-content {
        flex: 1;
        min-width: 0;
    }

    .mms-unsaved-title {
        margin: 0;
        font-size: 0.975rem;
        font-weight: 600;
        line-height: 1.35;
        color: var(--color-text-primary, #0f172a);
    }

    .mms-unsaved-desc {
        margin: 0.375rem 0 0;
        font-size: 0.85rem;
        line-height: 1.48;
        color: var(--color-text-secondary, #64748b);
    }

    .mms-unsaved-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 1.25rem;
    }

    .mms-unsaved-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.475rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 500;
        line-height: 1.25;
        border-radius: var(--radius-sm, 6px);
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .mms-unsaved-btn:focus-visible {
        outline: 2px solid var(--color-brand, #029786);
        outline-offset: 2px;
    }

    .mms-unsaved-btn-stay {
        background-color: var(--color-button-secondary-bg, #ffffff);
        color: var(--color-button-secondary-text, #0f172a);
        border: 1px solid var(--color-button-border, #cbd5e1);
    }

    .mms-unsaved-btn-stay:hover {
        background-color: var(--color-table-header-bg, #f8fafc);
    }

    .mms-unsaved-btn-leave {
        background-color: #ef4444;
        color: #ffffff;
        border: 1px solid #ef4444;
    }

    .mms-unsaved-btn-leave:hover {
        background-color: #dc2626;
        border-color: #dc2626;
    }

    @keyframes mms-backdrop-fade {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes mms-dialog-pop {
        from {
            opacity: 0;
            transform: scale(0.96) translateY(4px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>

<script>
    (() => {
        const IGNORED_KEYS = new Set([
            'Tab', 'Escape', 'Shift', 'Control', 'Alt', 'Meta',
            'CapsLock', 'NumLock', 'ScrollLock',
            'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight',
            'Home', 'End', 'PageUp', 'PageDown',
            'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12',
        ]);

        const stableStringify = (value) => {
            if (value === null || typeof value !== 'object') {
                return JSON.stringify(value);
            }

            if (Array.isArray(value)) {
                return '[' + value.map((item) => (item === undefined ? 'null' : stableStringify(item))).join(',') + ']';
            }

            const keys = Object.keys(value).sort();
            const entries = [];
            for (const key of keys) {
                if (value[key] === undefined) {
                    continue;
                }
                entries.push(JSON.stringify(key) + ':' + stableStringify(value[key]));
            }

            return '{' + entries.join(',') + '}';
        };

        const safeSnapshot = ($wire) => {
            try {
                return stableStringify($wire.data ?? {});
            } catch {
                return '';
            }
        };

        const openMiniConfirmDialog = (onConfirm) => {
            const backdrop = document.getElementById('mms-unsaved-dialog-backdrop');
            const stayBtn = document.getElementById('mms-unsaved-stay');
            const leaveBtn = document.getElementById('mms-unsaved-leave');

            if (!backdrop || !stayBtn || !leaveBtn) {
                if (window.confirm('Sie haben ungespeicherte Änderungen. Möchten Sie diese Seite wirklich verlassen?')) {
                    onConfirm();
                }

                return;
            }

            const previousFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;

            const close = () => {
                backdrop.hidden = true;
                backdrop.setAttribute('aria-hidden', 'true');
                document.removeEventListener('keydown', onKeyDown, true);
                backdrop.removeEventListener('click', onBackdropClick);
                stayBtn.removeEventListener('click', onStay);
                leaveBtn.removeEventListener('click', onLeave);

                if (previousFocus && previousFocus.isConnected) {
                    previousFocus.focus();
                }
            };

            const onStay = () => close();

            const onLeave = () => {
                close();
                onConfirm();
            };

            const onBackdropClick = (event) => {
                if (event.target === backdrop) {
                    close();
                }
            };

            const onKeyDown = (event) => {
                if (event.key === 'Escape') {
                    event.preventDefault();
                    event.stopPropagation();
                    close();

                    return;
                }

                if (event.key === 'Tab') {
                    event.preventDefault();
                    if (document.activeElement === stayBtn) {
                        leaveBtn.focus();
                    } else {
                        stayBtn.focus();
                    }
                }
            };

            backdrop.hidden = false;
            backdrop.setAttribute('aria-hidden', 'false');
            stayBtn.focus();

            document.addEventListener('keydown', onKeyDown, true);
            backdrop.addEventListener('click', onBackdropClick);
            stayBtn.addEventListener('click', onStay);
            leaveBtn.addEventListener('click', onLeave);
        };

        const registerTracker = ({ $wire, resolveLivewireComponentUsing }) => {
            let baseline = safeSnapshot($wire);
            let lastSavedDataHash = $wire.savedDataHash ?? null;
            let hasUserInteracted = false;
            let allowNavigation = false;

            const isComponentActive = () => {
                if (typeof resolveLivewireComponentUsing === 'function') {
                    return typeof resolveLivewireComponentUsing() !== 'undefined';
                }

                return true;
            };

            const isDirty = () => {
                if (allowNavigation || !isComponentActive()) {
                    return false;
                }

                if ($wire?.__instance?.effects?.redirect) {
                    return false;
                }

                if (!hasUserInteracted) {
                    return false;
                }

                return safeSnapshot($wire) !== baseline;
            };

            // Nach Client-Hydration (z. B. TipTap/RichEditor, DateTimePicker, FilePond)
            // Baseline erneut abgleichen, solange der Nutzer noch nichts bearbeitet hat.
            const settleTimer = window.setTimeout(() => {
                if (!hasUserInteracted) {
                    baseline = safeSnapshot($wire);
                }
            }, 400);

            const isInteractiveFormControl = (target) => {
                if (!(target instanceof Element)) {
                    return false;
                }

                // Klicks in Toolbar-Buttons, Toggles, Select-Optionen, FilePond-Aktionen oder Checkboxes/Radios
                return Boolean(
                    target.closest(
                        '.fi-fo-rich-editor button, [role="switch"], [role="option"], [role="combobox"], [role="checkbox"], [role="radio"], [data-choice], .fi-select-input, .fi-fo-field-wrp button, .filepond--action-remove-item, .filepond--drop-label, .fi-fo-date-time-picker button, select, input[type="checkbox"], input[type="radio"]'
                    )
                );
            };

            const isInsideForm = (target) => {
                if (!(target instanceof Element)) {
                    return false;
                }

                return Boolean(target.closest('.fi-page form, .fi-sc-form, .fi-fo-component-ctn'));
            };

            const markBeforeEdit = (event) => {
                if (hasUserInteracted || !isInsideForm(event.target)) {
                    return;
                }

                baseline = safeSnapshot($wire);
                hasUserInteracted = true;
            };

            const handleKeyDown = (event) => {
                if (hasUserInteracted || !isInsideForm(event.target)) {
                    return;
                }

                if (event.ctrlKey || event.metaKey || event.altKey) {
                    // Strg+V / Strg+X / Strg+Z werden über paste/cut/input abgefangen
                    return;
                }

                if (!IGNORED_KEYS.has(event.key)) {
                    markBeforeEdit(event);
                }
            };

            const handlePointerDown = (event) => {
                if (hasUserInteracted || !isInsideForm(event.target)) {
                    return;
                }

                if (isInteractiveFormControl(event.target)) {
                    markBeforeEdit(event);
                }
            };

            const handlePasteOrDrop = (event) => {
                markBeforeEdit(event);
            };

            const handleInputOrChange = (event) => {
                if (!hasUserInteracted && isInsideForm(event.target)) {
                    hasUserInteracted = true;
                }
            };

            const handleLinkClick = (event) => {
                if (
                    event.defaultPrevented ||
                    event.button !== 0 ||
                    event.metaKey ||
                    event.ctrlKey ||
                    event.shiftKey ||
                    event.altKey
                ) {
                    return;
                }

                const target = event.target instanceof Element ? event.target.closest('a[href]') : null;
                if (!target) {
                    return;
                }

                const href = target.getAttribute('href') ?? '';
                if (
                    !href ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    target.hasAttribute('download') ||
                    target.getAttribute('target') === '_blank'
                ) {
                    return;
                }

                if (!isDirty()) {
                    return;
                }

                event.preventDefault();
                const destination = target.href;

                openMiniConfirmDialog(() => {
                    allowNavigation = true;
                    window.location.assign(destination);
                });
            };

            const handleLivewireNavigate = (event) => {
                if (!isDirty()) {
                    return;
                }

                event.preventDefault();
                const destination = event.detail?.url?.href ?? event.detail?.url ?? null;
                if (!destination) {
                    return;
                }

                openMiniConfirmDialog(() => {
                    allowNavigation = true;
                    window.location.assign(destination);
                });
            };

            const handleBeforeUnload = (event) => {
                if (!isDirty()) {
                    return;
                }

                event.preventDefault();
                event.returnValue = true;
            };

            let removeLivewireHook = null;
            if (window.Livewire && typeof window.Livewire.hook === 'function') {
                removeLivewireHook = window.Livewire.hook('commit', ({ component, succeed }) => {
                    if (component.id !== $wire.__instance?.id) {
                        return;
                    }

                    succeed(() => {
                        const currentHash = $wire.savedDataHash ?? null;
                        if (currentHash && currentHash !== lastSavedDataHash) {
                            lastSavedDataHash = currentHash;
                            baseline = safeSnapshot($wire);
                            hasUserInteracted = false;
                        }
                    });
                });
            }

            document.addEventListener('keydown', handleKeyDown, true);
            document.addEventListener('pointerdown', handlePointerDown, true);
            document.addEventListener('paste', handlePasteOrDrop, true);
            document.addEventListener('cut', handlePasteOrDrop, true);
            document.addEventListener('drop', handlePasteOrDrop, true);
            document.addEventListener('input', handleInputOrChange, true);
            document.addEventListener('change', handleInputOrChange, true);
            document.addEventListener('click', handleLinkClick);
            document.addEventListener('livewire:navigate', handleLivewireNavigate);
            window.addEventListener('beforeunload', handleBeforeUnload);

            $wire.__instance?.addCleanup?.(() => {
                window.clearTimeout(settleTimer);
                document.removeEventListener('keydown', handleKeyDown, true);
                document.removeEventListener('pointerdown', handlePointerDown, true);
                document.removeEventListener('paste', handlePasteOrDrop, true);
                document.removeEventListener('cut', handlePasteOrDrop, true);
                document.removeEventListener('drop', handlePasteOrDrop, true);
                document.removeEventListener('input', handleInputOrChange, true);
                document.removeEventListener('change', handleInputOrChange, true);
                document.removeEventListener('click', handleLinkClick);
                document.removeEventListener('livewire:navigate', handleLivewireNavigate);
                window.removeEventListener('beforeunload', handleBeforeUnload);
                if (typeof removeLivewireHook === 'function') {
                    removeLivewireHook();
                }
            });
        };

        const installOverrides = () => {
            window.setUpFilamentUnsavedDataChangesAlert = (options) => {
                registerTracker(options);
            };

            window.setUpFilamentSpaModeUnsavedDataChangesAlert = (options) => {
                registerTracker(options);
            };
        };

        installOverrides();
        document.addEventListener('DOMContentLoaded', installOverrides);
        document.addEventListener('livewire:init', installOverrides);
    })();
</script>
