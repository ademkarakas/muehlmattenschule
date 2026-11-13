<style>
    /* ==========================================================================
       Mühlmattenschule – Einheitliches Filament 5 Design-System (#029786)
       Inspiriert von SupportCallSystem.Frontend (Listen-, Erstellen- & Bearbeiten-Seiten)
       ========================================================================== */

    /* 1. Globale Design-Tokens (Light & Dark Mode) & Arbeitsbereich */
    .fi-body {
        --mms-primary: #029786;
        --mms-primary-dark: #00695c;
        --mms-primary-50: #e6f6f4;
        --mms-primary-100: #c5ebe6;
        --mms-ink: #0f172a;
        --mms-muted: #526663;
        --mms-subtle: #64748b;
        --mms-surface: #ffffff;
        --mms-surface-header: #f8fafc;
        --mms-row-hover: #f0fdfa;
        --mms-line: #e2e8f0;
        --mms-card-border: #dbe7e5;
        --mms-radius: 16px;
        --mms-radius-md: 12px;
        --mms-radius-sm: 10px;
        --mms-shadow: 0 4px 12px -2px rgba(2, 105, 92, 0.07), 0 2px 6px -2px rgba(15, 23, 42, 0.04);
        --mms-shadow-md: 0 14px 28px -10px rgba(2, 105, 92, 0.18), 0 4px 10px -4px rgba(15, 23, 42, 0.06);
        --mms-ease: cubic-bezier(0.16, 1, 0.3, 1);

        background-color: #f1f5f9 !important;
    }

    .dark .fi-body {
        --mms-primary: #36c4b2;
        --mms-primary-dark: #70ddcd;
        --mms-primary-50: rgba(54, 196, 178, 0.12);
        --mms-primary-100: rgba(54, 196, 178, 0.24);
        --mms-ink: #f1f5f9;
        --mms-muted: #94a3b8;
        --mms-subtle: #94a3b8;
        --mms-surface: #0f2027;
        --mms-surface-header: #132830;
        --mms-row-hover: rgba(54, 196, 178, 0.08);
        --mms-line: rgba(255, 255, 255, 0.09);
        --mms-card-border: rgba(255, 255, 255, 0.1);
        --mms-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.5);
        --mms-shadow-md: 0 14px 28px -10px rgba(0, 0, 0, 0.65);

        background-color: #091318 !important;
    }

    /* Sanfte Seiten-Einblendung analog zu .page-enter */
    @keyframes mms-page-enter {
        from {
            opacity: 0;
            transform: translateY(4px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fi-main .fi-page {
        animation: mms-page-enter 0.2s var(--mms-ease);
    }

    @media (prefers-reduced-motion: reduce) {
        .fi-main .fi-page,
        .fi-sidebar-item-btn,
        .fi-btn,
        .fi-section,
        .fi-wi-stats-overview-stat {
            animation: none !important;
            transition: none !important;
        }
    }

    /* 2. Topbar (Header): Klares, modernes Glas-Finish mit feiner Trennlinie */
    .fi-topbar {
        background-color: rgba(255, 255, 255, 0.96) !important;
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04) !important;
    }

    .dark .fi-topbar {
        background-color: rgba(13, 27, 34, 0.96) !important;
        border-bottom-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* 3. Marken-Logo in Topbar & Sidebar */
    .mms-admin-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        text-decoration: none;
    }

    .mms-admin-brand-icon {
        width: 2.15rem;
        height: 2.15rem;
        border-radius: 0.55rem;
        box-shadow: 0 2px 6px rgba(0, 105, 120, 0.22);
        flex-shrink: 0;
    }

    .mms-admin-brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
    }

    .mms-admin-brand-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .dark .mms-admin-brand-title {
        color: #f8fafc;
    }

    .mms-admin-brand-subtitle {
        font-size: 0.68rem;
        font-weight: 600;
        color: #029786;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .dark .mms-admin-brand-subtitle {
        color: #36c4b2;
    }

    /* 4. Sidebar: Original Mühlmattenschule Logo-Türkis (#029786 -> #007f70 -> #00695c) */
    .fi-sidebar {
        background: linear-gradient(180deg, #029786 0%, #007f70 52%, #00695c 100%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.16) !important;
        box-shadow: 4px 0 24px -6px rgba(0, 105, 92, 0.22) !important;
    }

    .dark .fi-sidebar {
        background: linear-gradient(180deg, #00695c 0%, #004d43 60%, #00352e 100%) !important;
        border-right-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Mobiler Sidebar-Header */
    .fi-body-has-topbar .fi-sidebar-header {
        background-color: #007f70 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
    }

    .fi-sidebar-header .mms-admin-brand-title {
        color: #ffffff !important;
    }

    .fi-sidebar-header .mms-admin-brand-subtitle {
        color: #80deea !important;
    }

    /* Sidebar-Navigation Abstände */
    .fi-sidebar-nav {
        padding: 1.25rem 1rem 1.5rem 1rem !important;
        gap: 1.35rem !important;
    }

    .fi-sidebar-nav-groups {
        gap: 1.35rem !important;
        margin-inline: 0 !important;
    }

    /* Gruppen-Überschriften in der Sidebar */
    .fi-sidebar-group-btn {
        padding: 0.35rem 0.6rem !important;
    }

    .fi-sidebar-group-label {
        color: rgba(224, 247, 250, 0.85) !important;
        font-size: 0.70rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
    }

    .fi-sidebar-group-btn .fi-icon,
    .fi-sidebar-group-collapse-btn {
        color: rgba(224, 247, 250, 0.82) !important;
    }

    .fi-sidebar-group-collapse-btn:hover {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* Menüpunkte in der Sidebar */
    .fi-sidebar-item-btn {
        border-radius: 0.65rem !important;
        padding: 0.55rem 0.75rem !important;
        transition: all 0.16s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .fi-sidebar-item-label {
        color: #ffffff !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
    }

    .fi-sidebar-item-btn > .fi-icon,
    .fi-sidebar-item-icon {
        color: rgba(224, 247, 250, 0.92) !important;
        transition: transform 0.16s ease, color 0.16s ease !important;
    }

    /* Hover-Zustand in der Sidebar */
    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover,
    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:focus-visible {
        background-color: rgba(255, 255, 255, 0.16) !important;
    }

    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover .fi-sidebar-item-label,
    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:focus-visible .fi-sidebar-item-label {
        color: #ffffff !important;
    }

    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover > .fi-icon,
    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
        color: #ffffff !important;
        transform: translateX(2px);
    }

    /* Aktiver Menüpunkt in der Sidebar */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.14) 100%) !important;
        box-shadow: inset 3px 0 0 #ffffff, 0 2px 10px rgba(0, 0, 0, 0.12) !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-icon,
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
        color: #ffffff !important;
    }

    /* Zähler-Badges in der Sidebar: komplett ausgeblendet laut Design-Vorgabe */
    .fi-sidebar-item-badge-ctn {
        display: none !important;
    }

    /* 5. Topbar Rechts: „Zur Website“-Button & Benutzer-Kapsel (Avatar + Name + Rolle) */
    .mms-topbar-site-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.42rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #00695c;
        background-color: #effcf9;
        border: 1px solid #a8ede1;
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .mms-topbar-site-link:hover {
        background-color: #d3f7f0;
        border-color: #70ddcd;
        color: #003d36;
        transform: translateY(-1px);
    }

    .dark .mms-topbar-site-link {
        color: #70ddcd;
        background-color: rgba(2, 151, 134, 0.18);
        border-color: rgba(112, 221, 205, 0.3);
    }

    .mms-topbar-user-pill {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.6rem !important;
        padding: 0.28rem 0.7rem 0.28rem 0.32rem !important;
        border-radius: 9999px !important;
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.15s ease !important;
        cursor: pointer !important;
    }

    .mms-topbar-user-pill:hover,
    .mms-topbar-user-pill:focus-visible {
        background-color: #f0fdfa !important;
        border-color: #99f6e4 !important;
        box-shadow: 0 2px 6px rgba(0, 131, 143, 0.08) !important;
    }

    .dark .mms-topbar-user-pill {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    .mms-topbar-user-pill .fi-user-avatar {
        width: 2rem !important;
        height: 2rem !important;
        border-radius: 9999px !important;
        box-shadow: 0 0 0 2px #ffffff, 0 1px 3px rgba(0, 86, 98, 0.25) !important;
    }

    .mms-topbar-user-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        line-height: 1.15;
    }

    @media (max-width: 640px) {
        .mms-topbar-user-meta,
        .mms-topbar-site-link span {
            display: none;
        }
    }

    .mms-topbar-user-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
    }

    .dark .mms-topbar-user-name {
        color: #f8fafc;
    }

    .mms-topbar-user-role {
        font-size: 0.68rem;
        font-weight: 600;
        color: #029786;
        white-space: nowrap;
    }

    .dark .mms-topbar-user-role {
        color: #70ddcd;
    }

    .mms-topbar-user-chevron {
        width: 1rem;
        height: 1rem;
        color: #64748b;
        flex-shrink: 0;
    }

    /* 6. Dashboard KPI-Karten */
    .fi-wi-stats-overview-stat {
        border-radius: var(--mms-radius) !important;
        border: 1px solid var(--mms-card-border) !important;
        border-top: 3px solid #029786 !important;
        box-shadow: var(--mms-shadow) !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-2px);
        box-shadow: var(--mms-shadow-md) !important;
    }

    /* 7. Dialoge (z. B. „Löschen bestätigen“) exakt mittig auf dem Bildschirm */
    .fi-modal:not(.fi-modal-slide-over) .fi-modal-window-ctn {
        grid-template-rows: 1fr auto 1fr !important;
    }

    /* 8. Einheitlicher Kopfbereich (.fi-header) für ALLE Admin-Seiten
          (Listen, Hinzufügen, Bearbeiten, Dashboard, Schuleinstellungen, Profil) */
    .fi-main .fi-header {
        position: relative;
        overflow: hidden;
        padding: 1.35rem 1.75rem;
        border-radius: var(--mms-radius);
        border: 1px solid #a8e2d9;
        background:
            radial-gradient(120% 160% at 100% 0%, rgba(255, 255, 255, 0.78) 0%, rgba(255, 255, 255, 0) 55%),
            linear-gradient(135deg, #e6f7f5 0%, #d2f1ec 55%, #bfeae3 100%);
        box-shadow: var(--mms-shadow);
    }

    .fi-main .fi-header::after {
        content: '';
        position: absolute;
        inset: auto 0 0 0;
        height: 3px;
        background: linear-gradient(90deg, #029786 0%, #14b8a6 55%, #36c4b2 100%);
    }

    .dark .fi-main .fi-header {
        border-color: rgba(54, 196, 178, 0.26);
        background:
            radial-gradient(120% 140% at 100% 0%, rgba(54, 196, 178, 0.2) 0%, rgba(54, 196, 178, 0) 55%),
            linear-gradient(135deg, #0d2a2a 0%, #0f3532 100%);
    }

    .fi-main .fi-header-heading {
        font-weight: 700 !important;
        letter-spacing: -0.02em;
        color: #004d43 !important;
    }

    .dark .fi-main .fi-header-heading {
        color: #e6f7f5 !important;
    }

    .fi-main .fi-header-subheading {
        color: #1f5f56 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        margin-top: 0.25rem !important;
        line-height: 1.4 !important;
    }

    .dark .fi-main .fi-header-subheading {
        color: #9bd9d0 !important;
    }

    .fi-main .fi-breadcrumbs-item-label {
        color: #00695c !important;
        font-weight: 600 !important;
    }

    .dark .fi-main .fi-breadcrumbs-item-label {
        color: #70ddcd !important;
    }

    /* Kopfzeilen-Buttons (+ Neu, ← Zurück, Auf Website ansehen) im Pill-Design */
    .fi-main .fi-header .fi-ac-btn-action,
    .fi-main .fi-header .fi-btn {
        border-radius: 9999px !important;
        padding-inline: 1.15rem !important;
        font-weight: 600 !important;
        transition: transform 0.16s var(--mms-ease), box-shadow 0.16s var(--mms-ease) !important;
    }

    .fi-main .fi-header .fi-btn.fi-color-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px -8px rgba(2, 151, 134, 0.55) !important;
    }

    /* 9. SupportCallSystem.Frontend Listen- & Tabellen-Design (.content-container Stil)
          Such-/Filterleiste + Tabelle + Paginierung in einer einheitlichen Karte */
    .fi-ta-ctn {
        border-radius: var(--mms-radius) !important;
        border: 1px solid var(--mms-card-border) !important;
        background-color: var(--mms-surface) !important;
        box-shadow: var(--mms-shadow) !important;
        overflow: hidden !important;
        --tw-ring-shadow: 0 0 #0000 !important;
    }

    .fi-ta-header-toolbar {
        padding: 0.9rem 1.25rem !important;
        background-color: var(--mms-surface) !important;
        border-bottom: 1px solid var(--mms-line) !important;
    }

    /* Tabellen-Spaltenköpfe (th) in Uppercase + #f8fafc wie in SupportCallSystem.Frontend */
    .fi-ta-table thead tr,
    .fi-ta-header-cell {
        background-color: var(--mms-surface-header) !important;
    }

    .fi-ta-header-cell {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
        border-bottom: 1px solid var(--mms-line) !important;
    }

    .fi-ta-header-cell-label {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: var(--mms-subtle) !important;
    }

    /* Tabellen-Zeilen: sanfter Hover & klickbare Zeilen */
    .fi-ta-row {
        transition: background-color 0.14s ease !important;
    }

    .fi-ta-row:hover {
        background-color: var(--mms-row-hover) !important;
    }

    /* Abgerundete Vorschaubilder & Avatare in Listen (10px Radius wie .name-avatar) */
    .fi-ta-image img {
        border-radius: 10px !important;
        object-fit: cover;
    }

    /* Badges in Tabellen & Formularen als harmonische Pills */
    .fi-badge {
        border-radius: 9999px !important;
        padding-inline: 0.65rem !important;
        font-weight: 600 !important;
    }

    /* Paginierungs-Fußzeile */
    .fi-pagination {
        background-color: var(--mms-surface-header) !important;
        border-top: 1px solid var(--mms-line) !important;
        padding: 0.75rem 1.25rem !important;
    }

    /* 10. Einheitliches Formular-Karten-Design (.form-card & .form-card-header Stil)
           für alle Hinzufügen-, Bearbeiten-, Einstellungs- und Profilseiten */
    .fi-section {
        border-radius: var(--mms-radius) !important;
        border: 1px solid var(--mms-card-border) !important;
        background-color: var(--mms-surface) !important;
        box-shadow: var(--mms-shadow) !important;
        overflow: hidden;
        --tw-ring-shadow: 0 0 #0000 !important;
        transition: box-shadow 0.25s var(--mms-ease), border-color 0.25s var(--mms-ease);
    }

    .fi-section:focus-within {
        box-shadow: var(--mms-shadow-md) !important;
        border-color: var(--mms-primary-100) !important;
    }

    /* Karten-Kopfzeile analog zu .form-card-header in SupportCallSystem.Frontend */
    .fi-section-header {
        padding: 1rem 1.4rem !important;
        gap: 0.75rem !important;
        align-items: center !important;
        background-color: var(--mms-surface-header) !important;
        border-bottom: 1px solid var(--mms-line) !important;
    }

    .fi-section-header > .fi-icon,
    .fi-section-header-icon {
        width: 2.25rem !important;
        height: 2.25rem !important;
        padding: 0.45rem;
        box-sizing: border-box;
        border-radius: 10px;
        color: var(--mms-primary) !important;
        background: var(--mms-primary-50);
        box-shadow: inset 0 0 0 1px var(--mms-primary-100);
        flex-shrink: 0;
    }

    .fi-section-header-heading {
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        letter-spacing: -0.01em;
        color: var(--mms-ink) !important;
    }

    .fi-section-header-description {
        color: var(--mms-muted) !important;
        font-size: 0.8rem !important;
        line-height: 1.45 !important;
    }

    .fi-section-content {
        padding: 1.5rem !important;
    }

    /* Tabs (z. B. in Schuleinstellungen) im selben Karten-Design */
    .fi-fo-tabs {
        border-radius: var(--mms-radius) !important;
        border: 1px solid var(--mms-card-border) !important;
        box-shadow: var(--mms-shadow) !important;
        overflow: hidden;
    }

    /* Feldbeschriftungen & Pflichtfeld-Sternchen */
    .fi-fo-field-label-content {
        font-weight: 600 !important;
        font-size: 0.84rem !important;
        color: var(--mms-ink) !important;
    }

    .fi-fo-field-label-required-mark {
        color: #ef4444 !important;
        font-weight: 700 !important;
    }

    /* Eingabefelder & Suchfelder: weiche Radien (10px), 44px Touch-Höhe & Türkis-Fokus-Ring */
    .fi-input-wrp {
        border-radius: var(--mms-radius-sm) !important;
        min-height: 2.75rem;
        transition: box-shadow 0.18s var(--mms-ease), border-color 0.18s var(--mms-ease), background-color 0.18s var(--mms-ease) !important;
    }

    .fi-input-wrp:focus-within {
        --tw-ring-color: var(--mms-primary) !important;
        box-shadow: 0 0 0 1px var(--mms-primary), 0 0 0 4px var(--mms-primary-50) !important;
    }

    .fi-input-wrp-prefix .fi-icon {
        color: var(--mms-muted) !important;
        transition: color 0.18s ease;
    }

    .fi-input-wrp:focus-within .fi-input-wrp-prefix .fi-icon {
        color: var(--mms-primary) !important;
    }

    /* 11. Schwebende Aktionsleiste (Speichern · Abbrechen · Löschen) mit Glas-Effekt */
    .fi-sc-actions.fi-sticky {
        border-radius: var(--mms-radius) !important;
        border: 1px solid var(--mms-card-border) !important;
        background-color: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(14px);
        padding: 0.75rem 1.25rem !important;
        box-shadow: 0 -8px 30px -12px rgba(15, 31, 29, 0.18) !important;
    }

    .dark .fi-sc-actions.fi-sticky {
        background-color: rgba(13, 27, 34, 0.92) !important;
    }

    .fi-sc-actions .fi-btn {
        border-radius: 9999px !important;
        padding-inline: 1.25rem !important;
        min-height: 2.6rem;
        font-weight: 600 !important;
        transition: transform 0.18s var(--mms-ease), box-shadow 0.18s var(--mms-ease) !important;
    }

    .fi-sc-actions .fi-btn.fi-color-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px -10px rgba(2, 151, 134, 0.55) !important;
    }

    /* 12. Mobile Optimierung (< 768px) analog zu SupportCallSystem.Frontend */
    @media (max-width: 768px) {
        .fi-main .fi-header {
            padding: 1.15rem 1.2rem;
        }

        .fi-main .fi-header .fi-ac {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .fi-main .fi-header .fi-btn {
            flex: 1 1 auto;
            justify-content: center;
            min-height: 2.75rem;
        }

        .fi-section-content {
            padding: 1.15rem !important;
        }
    }
</style>
