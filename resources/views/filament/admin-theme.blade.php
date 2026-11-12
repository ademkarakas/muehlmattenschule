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

    /* 10. Einheitliches, modernes Karten-Design für alle Formular- & Einstellungsseiten
           (Heller Türkis-Kopfbereich passend zur Schulfarbe #029786, 16px-Kartenradien,
           Fokus-Ringe, dezente Abschnitts-Köpfe und schwebende Glas-Aktionsleiste) */
    .fi-resource-create-record-page,
    .fi-resource-edit-record-page,
    .mms-settings-page {
        --mms-primary: #029786;
        --mms-primary-dark: #00695c;
        --mms-primary-50: #e6f6f4;
        --mms-primary-100: #c5ebe6;
        --mms-ink: #0f1f1d;
        --mms-muted: #526663;
        --mms-line: #dbe7e5;
        --mms-radius: 16px;
        --mms-radius-sm: 10px;
        --mms-shadow: 0 4px 12px -2px rgba(2, 105, 92, 0.07), 0 2px 6px -2px rgba(15, 31, 29, 0.04);
        --mms-shadow-md: 0 14px 28px -10px rgba(2, 105, 92, 0.18), 0 4px 10px -4px rgba(15, 31, 29, 0.06);
        --mms-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .dark .fi-resource-create-record-page,
    .dark .fi-resource-edit-record-page,
    .dark .mms-settings-page {
        --mms-primary: #36c4b2;
        --mms-primary-dark: #70ddcd;
        --mms-primary-50: rgba(54, 196, 178, 0.12);
        --mms-primary-100: rgba(54, 196, 178, 0.24);
        --mms-ink: #f1f5f9;
        --mms-muted: #94a3b8;
        --mms-line: rgba(255, 255, 255, 0.09);
        --mms-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.5);
        --mms-shadow-md: 0 14px 28px -10px rgba(0, 0, 0, 0.65);
    }

    /* Kopfbereich in frischem, hellem Türkis passend zur Markenfarbe (#029786) */
    .fi-resource-create-record-page .fi-header,
    .fi-resource-edit-record-page .fi-header,
    .mms-settings-page .fi-header {
        position: relative;
        overflow: hidden;
        padding: 1.5rem 1.85rem;
        border-radius: var(--mms-radius);
        border: 1px solid #a8e2d9;
        background:
            radial-gradient(120% 160% at 100% 0%, rgba(255, 255, 255, 0.78) 0%, rgba(255, 255, 255, 0) 55%),
            linear-gradient(135deg, #e6f7f5 0%, #d2f1ec 55%, #bfeae3 100%);
        box-shadow: var(--mms-shadow);
    }

    .fi-resource-create-record-page .fi-header::after,
    .fi-resource-edit-record-page .fi-header::after,
    .mms-settings-page .fi-header::after {
        content: '';
        position: absolute;
        inset: auto 0 0 0;
        height: 3px;
        background: linear-gradient(90deg, #029786 0%, #14b8a6 55%, #36c4b2 100%);
    }

    .dark .fi-resource-create-record-page .fi-header,
    .dark .fi-resource-edit-record-page .fi-header,
    .dark .mms-settings-page .fi-header {
        border-color: rgba(54, 196, 178, 0.26);
        background:
            radial-gradient(120% 140% at 100% 0%, rgba(54, 196, 178, 0.2) 0%, rgba(54, 196, 178, 0) 55%),
            linear-gradient(135deg, #0d2a2a 0%, #0f3532 100%);
    }

    .fi-resource-create-record-page .fi-header-heading,
    .fi-resource-edit-record-page .fi-header-heading,
    .mms-settings-page .fi-header-heading {
        font-weight: 700 !important;
        letter-spacing: -0.02em;
        color: #004d43 !important;
    }

    .dark .fi-resource-create-record-page .fi-header-heading,
    .dark .fi-resource-edit-record-page .fi-header-heading,
    .dark .mms-settings-page .fi-header-heading {
        color: #e6f7f5 !important;
    }

    .fi-resource-create-record-page .fi-header-subheading,
    .fi-resource-edit-record-page .fi-header-subheading,
    .mms-settings-page .fi-header-subheading {
        color: #1f5f56 !important;
        font-weight: 500 !important;
    }

    .dark .fi-resource-create-record-page .fi-header-subheading,
    .dark .fi-resource-edit-record-page .fi-header-subheading,
    .dark .mms-settings-page .fi-header-subheading {
        color: #9bd9d0 !important;
    }

    .fi-resource-create-record-page .fi-breadcrumbs-item-label,
    .fi-resource-edit-record-page .fi-breadcrumbs-item-label {
        color: #00695c !important;
        font-weight: 600 !important;
    }

    .dark .fi-resource-create-record-page .fi-breadcrumbs-item-label,
    .dark .fi-resource-edit-record-page .fi-breadcrumbs-item-label {
        color: #70ddcd !important;
    }

    /* Formular-Karten (alle Sections auf Create-, Edit- und Einstellungsseiten) */
    .fi-resource-create-record-page .fi-section,
    .fi-resource-edit-record-page .fi-section,
    .mms-settings-page .fi-section {
        border-radius: var(--mms-radius) !important;
        border: 1px solid var(--mms-line) !important;
        box-shadow: var(--mms-shadow) !important;
        --tw-ring-shadow: 0 0 #0000 !important;
        transition: box-shadow 0.28s var(--mms-ease), border-color 0.28s var(--mms-ease);
    }

    .fi-resource-create-record-page .fi-section:focus-within,
    .fi-resource-edit-record-page .fi-section:focus-within,
    .mms-settings-page .fi-section:focus-within {
        box-shadow: var(--mms-shadow-md) !important;
        border-color: var(--mms-primary-100) !important;
    }

    .fi-resource-create-record-page .fi-section-header,
    .fi-resource-edit-record-page .fi-section-header,
    .mms-settings-page .fi-section-header {
        padding: 1.15rem 1.5rem !important;
        gap: 0.85rem !important;
        align-items: center !important;
        border-bottom: 1px solid var(--mms-line);
    }

    .fi-resource-create-record-page .fi-section-header > .fi-icon,
    .fi-resource-edit-record-page .fi-section-header > .fi-icon,
    .mms-settings-page .fi-section-header > .fi-icon {
        width: 2.35rem !important;
        height: 2.35rem !important;
        padding: 0.5rem;
        box-sizing: border-box;
        border-radius: 10px;
        color: var(--mms-primary) !important;
        background: var(--mms-primary-50);
        box-shadow: inset 0 0 0 1px var(--mms-primary-100);
        flex-shrink: 0;
    }

    .fi-resource-create-record-page .fi-section-header-heading,
    .fi-resource-edit-record-page .fi-section-header-heading,
    .mms-settings-page .fi-section-header-heading {
        font-size: 0.98rem !important;
        font-weight: 700 !important;
        letter-spacing: -0.01em;
        color: var(--mms-ink) !important;
    }

    .fi-resource-create-record-page .fi-section-header-description,
    .fi-resource-edit-record-page .fi-section-header-description,
    .mms-settings-page .fi-section-header-description {
        color: var(--mms-muted) !important;
        font-size: 0.82rem !important;
        line-height: 1.45 !important;
    }

    .fi-resource-create-record-page .fi-section-content,
    .fi-resource-edit-record-page .fi-section-content,
    .mms-settings-page .fi-section-content {
        padding: 1.5rem !important;
    }

    /* Feldbeschriftungen */
    .fi-resource-create-record-page .fi-fo-field-label-content,
    .fi-resource-edit-record-page .fi-fo-field-label-content,
    .mms-settings-page .fi-fo-field-label-content {
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        color: var(--mms-ink) !important;
    }

    /* Eingabefelder: weiche Radien & Türkis-Fokus-Ring */
    .fi-resource-create-record-page .fi-input-wrp,
    .fi-resource-edit-record-page .fi-input-wrp,
    .mms-settings-page .fi-input-wrp {
        border-radius: var(--mms-radius-sm) !important;
        min-height: 2.75rem;
        transition: box-shadow 0.18s var(--mms-ease), background-color 0.18s var(--mms-ease) !important;
    }

    .fi-resource-create-record-page .fi-input-wrp:focus-within,
    .fi-resource-edit-record-page .fi-input-wrp:focus-within,
    .mms-settings-page .fi-input-wrp:focus-within {
        --tw-ring-color: var(--mms-primary) !important;
        box-shadow: 0 0 0 1px var(--mms-primary), 0 0 0 4px var(--mms-primary-50) !important;
    }

    .fi-resource-create-record-page .fi-input-wrp-prefix .fi-icon,
    .fi-resource-edit-record-page .fi-input-wrp-prefix .fi-icon,
    .mms-settings-page .fi-input-wrp-prefix .fi-icon {
        color: var(--mms-muted) !important;
        transition: color 0.18s ease;
    }

    .fi-resource-create-record-page .fi-input-wrp:focus-within .fi-input-wrp-prefix .fi-icon,
    .fi-resource-edit-record-page .fi-input-wrp:focus-within .fi-input-wrp-prefix .fi-icon,
    .mms-settings-page .fi-input-wrp:focus-within .fi-input-wrp-prefix .fi-icon {
        color: var(--mms-primary) !important;
    }

    /* Schwebende Aktionsleiste mit Glas-Effekt */
    .fi-resource-create-record-page .fi-sc-actions.fi-sticky,
    .fi-resource-edit-record-page .fi-sc-actions.fi-sticky {
        border-radius: var(--mms-radius) !important;
        border: 1px solid var(--mms-line) !important;
        background-color: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(14px);
        box-shadow: 0 -8px 30px -12px rgba(15, 31, 29, 0.18) !important;
    }

    .dark .fi-resource-create-record-page .fi-sc-actions.fi-sticky,
    .dark .fi-resource-edit-record-page .fi-sc-actions.fi-sticky {
        background-color: rgba(13, 27, 34, 0.9) !important;
    }

    .fi-resource-create-record-page .fi-sc-actions .fi-btn,
    .fi-resource-edit-record-page .fi-sc-actions .fi-btn,
    .mms-settings-page .fi-sc-actions .fi-btn {
        border-radius: 9999px !important;
        padding-inline: 1.25rem !important;
        transition: transform 0.18s var(--mms-ease), box-shadow 0.18s var(--mms-ease) !important;
    }

    .fi-resource-create-record-page .fi-sc-actions .fi-btn.fi-color-primary:hover,
    .fi-resource-edit-record-page .fi-sc-actions .fi-btn.fi-color-primary:hover,
    .mms-settings-page .fi-sc-actions .fi-btn.fi-color-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px -10px rgba(2, 151, 134, 0.55) !important;
    }
</style>

