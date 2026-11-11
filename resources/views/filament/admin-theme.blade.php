<style>
    /* ==========================================================================
       Mühlmattenschule – Modernes Filament 5 Admin-Design (Deep Turquoise Theme)
       ========================================================================== */

    /* 1. Body & Arbeitsbereich: Sanftes Schiefergrau für klaren Karten-Kontrast */
    .fi-body {
        background-color: #f1f5f9 !important;
    }

    .dark .fi-body {
        background-color: #091318 !important;
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

    /* 6. Dashboard KPI-Karten & Tabellen-Veredelung */
    .fi-wi-stats-overview-stat {
        border-top: 3px solid #029786 !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -8px rgba(15, 23, 42, 0.1) !important;
    }

    /* 7. Dialoge (z. B. „Löschen bestätigen“) exakt mittig auf dem Bildschirm */
    .fi-modal:not(.fi-modal-slide-over) .fi-modal-window-ctn {
        grid-template-rows: 1fr auto 1fr !important;
    }

    /* 8. Aktionsleiste (Speichern · Abbrechen · Löschen) */
    .fi-sc-actions.fi-sticky {
        border-radius: 0.75rem;
    }

    /* 9. Dezent-solide Zwischenüberschriften unter dem Seitentitel (Anzahl & Kurzbeschreibung) */
    .fi-header-subheading {
        color: #64748b !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        margin-top: 0.25rem !important;
        line-height: 1.4 !important;
    }

    .dark .fi-header-subheading {
        color: #94a3b8 !important;
    }
</style>

