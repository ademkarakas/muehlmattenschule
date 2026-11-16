<style>
    /* ==========================================================================
       Mühlmattenschule – 1:1 SupportCallSystem.Frontend Design-System
       Exakte Umsetzung von Body (#f2f9f8), .page-header, .top-bar,
       .content-container, .form-card, .input-wrap & .dashboard-landing
       ========================================================================== */

    /* 1. Design-Tokens exakt aus SupportCallSystem.Frontend/src/styles.scss */
    .fi-body {
        --color-primary: #1e3a5f;
        --color-primary-hover: #152d4d;
        --color-brand: #029786;
        --color-brand-hover: #00695c;
        --color-brand-light: #e6f6f4;
        --color-main-bg: #f2f9f8;
        --color-header-bg: #ffffff;
        --color-card-bg: #ffffff;
        --color-table-header-bg: #f8fafc;
        --color-table-row-alt: #fafbfc;
        --color-table-row-hover: #f0f4f8;
        --color-pagination-bg: #ffffff;
        --color-header-border: #e2e8f0;
        --color-table-border: #e2e8f0;
        --color-input-border: #cbd5e1;
        --color-button-border: #cbd5e1;
        --color-card-border: #e2e8f0;
        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-text-tertiary: #94a3b8;
        --color-button-secondary-bg: #ffffff;
        --color-button-secondary-text: #0f172a;
        --color-button-danger-border: #ef4444;
        --color-button-danger-text: #ef4444;
        --color-button-danger-hover-bg: #fee2e2;
        --color-success: #059669;
        --color-danger: #ef4444;
        --color-warning: #d97706;
        --color-info: #3b82f6;
        --color-status-neutral-bg: #f1f5f9;
        --color-status-neutral-text: #64748b;
        --radius-xs: 4px;
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 10px;
        --radius-xl: 12px;
        --radius-badge: 12px;
        --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow-sm: 0 1px 4px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
        --page-section-gap: 1.5rem;

        background-color: var(--color-main-bg) !important;
        color: var(--color-text-primary) !important;
    }

    .dark .fi-body {
        --color-primary: #60a5fa;
        --color-primary-hover: #3b82f6;
        --color-brand: #36c4b2;
        --color-brand-hover: #029786;
        --color-brand-light: rgba(54, 196, 178, 0.14);
        --color-main-bg: #0f172a;
        --color-header-bg: #1e293b;
        --color-card-bg: #1e293b;
        --color-table-header-bg: #334155;
        --color-table-row-alt: #1a2744;
        --color-table-row-hover: #334155;
        --color-pagination-bg: #1e293b;
        --color-header-border: #334155;
        --color-table-border: #334155;
        --color-input-border: #475569;
        --color-button-border: #475569;
        --color-card-border: #334155;
        --color-text-primary: #f1f5f9;
        --color-text-secondary: #94a3b8;
        --color-text-tertiary: #64748b;
        --color-button-secondary-bg: #1e293b;
        --color-button-secondary-text: #e2e8f0;
        --color-button-danger-border: #f87171;
        --color-button-danger-text: #f87171;
        --color-button-danger-hover-bg: #3f1a1a;
        --color-status-neutral-bg: #334155;
        --color-status-neutral-text: #94a3b8;
    }

    /* Sanfte Seiten-Einblendung (.page-enter) */
    @keyframes scs-page-enter {
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
        animation: scs-page-enter 0.18s ease-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .fi-main .fi-page {
            animation: none !important;
        }
    }

    /* Breadcrumbs komplett ausblenden (SupportCallSystem.Frontend hat keine Breadcrumbs) */
    .fi-breadcrumbs {
        display: none !important;
    }

    /* 2. Topbar & Sidebar (Minimal Neutral Best-Practice) */
    .fi-topbar {
        background-color: var(--color-header-bg) !important;
        border-bottom: 1px solid var(--color-header-border) !important;
        box-shadow: none !important;
    }

    .fi-topbar .fi-global-search-field .fi-input-wrp {
        background-color: var(--color-table-header-bg) !important;
        border: 1px solid var(--color-header-border) !important;
        border-radius: var(--radius-md) !important;
        box-shadow: none !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease !important;
    }

    .fi-topbar .fi-global-search-field .fi-input-wrp:focus-within {
        background-color: var(--color-header-bg) !important;
        border-color: var(--color-brand) !important;
        box-shadow: 0 0 0 3px rgba(2, 151, 134, 0.12) !important;
    }

    @media (min-width: 768px) {
        .fi-topbar .fi-global-search-field .fi-input-wrp::after {
            content: 'Ctrl K';
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
            margin-inline-end: 0.5rem;
            padding: 0.12rem 0.4rem;
            border-radius: var(--radius-xs);
            border: 1px solid var(--color-header-border);
            background-color: var(--color-header-bg);
            color: var(--color-text-tertiary);
            font-size: 0.68rem;
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: 0.02em;
            pointer-events: none;
        }
    }

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
        box-shadow: 0 2px 6px rgba(0, 105, 120, 0.16);
        flex-shrink: 0;
    }

    .mms-admin-brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
    }

    .mms-admin-brand-title {
        font-size: 0.96rem;
        font-weight: 700;
        color: var(--color-text-primary);
        letter-spacing: -0.01em;
    }

    .mms-admin-brand-subtitle {
        font-size: 0.66rem;
        font-weight: 600;
        color: var(--color-brand);
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .fi-sidebar {
        background: #ffffff !important;
        border-right: 1px solid #e2e8f0 !important;
        box-shadow: 1px 0 3px rgba(0, 0, 0, 0.02) !important;
    }

    .dark .fi-sidebar {
        background: #111827 !important;
        border-right-color: #1f2937 !important;
    }

    .fi-body-has-topbar .fi-sidebar-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .dark .fi-body-has-topbar .fi-sidebar-header {
        background-color: #111827 !important;
        border-bottom-color: #1f2937 !important;
    }

    .fi-sidebar-header .mms-admin-brand-title {
        color: #0f172a !important;
        font-weight: 700 !important;
    }

    .fi-sidebar-header .mms-admin-brand-subtitle {
        color: #029786 !important;
    }

    .dark .fi-sidebar-header .mms-admin-brand-title {
        color: #f8fafc !important;
    }

    .dark .fi-sidebar-header .mms-admin-brand-subtitle {
        color: #36c4b2 !important;
    }

    .fi-sidebar-nav {
        padding: 1rem 0.75rem 1.25rem 0.75rem !important;
        gap: 1rem !important;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .fi-sidebar-nav-groups {
        gap: 1rem !important;
        margin-inline: 0 !important;
    }

    .fi-sidebar-group-btn {
        padding: 0.3rem 0.65rem !important;
    }

    .fi-sidebar-group-label {
        color: #64748b !important;
        font-size: 0.68rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
    }

    .dark .fi-sidebar-group-label {
        color: #94a3b8 !important;
    }

    .fi-sidebar-group-btn .fi-icon,
    .fi-sidebar-group-collapse-btn {
        color: #94a3b8 !important;
        transition: color 0.15s ease, transform 0.15s ease !important;
    }

    .fi-sidebar-group-btn:hover .fi-sidebar-group-label,
    .fi-sidebar-group-btn:hover .fi-icon {
        color: #029786 !important;
    }

    .fi-sidebar-item {
        position: relative;
    }

    .fi-sidebar-item-btn {
        position: relative !important;
        border-radius: var(--radius-md) !important;
        padding: 0.5rem 0.75rem !important;
        transition: background-color 0.18s cubic-bezier(0.4, 0, 0.2, 1),
                    color 0.18s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.18s cubic-bezier(0.4, 0, 0.2, 1),
                    transform 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .fi-sidebar-item-label {
        color: #334155 !important;
        font-size: 0.855rem !important;
        font-weight: 500 !important;
        transition: color 0.15s ease !important;
    }

    .dark .fi-sidebar-item-label {
        color: #cbd5e1 !important;
    }

    .fi-sidebar-item-btn > .fi-icon,
    .fi-sidebar-item-icon {
        color: #64748b;
        width: 1.25rem !important;
        height: 1.25rem !important;
        flex-shrink: 0;
        transition: color 0.18s ease, transform 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    /* Semantik Özel Renkli İkonlar (URL bazlı) */
    /* Dashboard - Canlı Turkuaz */
    .fi-sidebar-item-btn[href$="/admin"] > .fi-icon,
    .fi-sidebar-item-btn[href$="/admin/"] > .fi-icon {
        color: #029786 !important;
    }

    /* Beiträge (Yazılar/Haberler) - Mavi/Indigo */
    .fi-sidebar-item-btn[href*="/admin/posts"] > .fi-icon {
        color: #2563eb !important;
    }

    /* Termine (Etkinlikler/Takvim) - Turuncu/Amber */
    .fi-sidebar-item-btn[href*="/admin/events"] > .fi-icon {
        color: #ea580c !important;
    }

    /* Fotoalben (Albümler/Galeri) - Mor/Fuşya */
    .fi-sidebar-item-btn[href*="/admin/albums"] > .fi-icon {
        color: #9333ea !important;
    }

    /* Ankündigungen (Duyurular) - Sıcak Kırmızı/Mercan */
    .fi-sidebar-item-btn[href*="/admin/announcements"] > .fi-icon {
        color: #e11d48 !important;
    }

    /* Seiten (Sayfalar) - Camgöbeği/Cyan */
    .fi-sidebar-item-btn[href*="/admin/pages"] > .fi-icon {
        color: #0891b2 !important;
    }

    /* Personen (Kişiler/Öğretmenler) - Zümrüt Yeşili */
    .fi-sidebar-item-btn[href*="/admin/people"] > .fi-icon {
        color: #059669 !important;
    }

    /* Downloads (İndirmeler/Belgeler) - Açık Mavi/Sky */
    .fi-sidebar-item-btn[href*="/admin/downloads"] > .fi-icon {
        color: #0284c7 !important;
    }

    /* Kategorien (Kategoriler) - Sarı/Kehribar */
    .fi-sidebar-item-btn[href*="/admin/categories"] > .fi-icon {
        color: #d97706 !important;
    }

    /* Benutzer (Kullanıcılar) - Çivit/Violet */
    .fi-sidebar-item-btn[href*="/admin/users"] > .fi-icon {
        color: #4f46e5 !important;
    }

    /* Schuleinstellungen (Okul Ayarları) - Slate/Petrol Mavisi */
    .fi-sidebar-item-btn[href*="/admin/manage-school-settings"] > .fi-icon {
        color: #0d9488 !important;
    }

    /* Karanlık Mod Renk Doygunluğu */
    .dark .fi-sidebar-item-btn[href$="/admin"] > .fi-icon,
    .dark .fi-sidebar-item-btn[href$="/admin/"] > .fi-icon { color: #2dd4bf !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/posts"] > .fi-icon { color: #60a5fa !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/events"] > .fi-icon { color: #fb923c !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/albums"] > .fi-icon { color: #c084fc !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/announcements"] > .fi-icon { color: #fb7185 !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/pages"] > .fi-icon { color: #22d3ee !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/people"] > .fi-icon { color: #34d399 !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/downloads"] > .fi-icon { color: #38bdf8 !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/categories"] > .fi-icon { color: #fbbf24 !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/users"] > .fi-icon { color: #818cf8 !important; }
    .dark .fi-sidebar-item-btn[href*="/admin/manage-school-settings"] > .fi-icon { color: #2dd4bf !important; }

    /* Hover State: Yumuşak ferah açık turkuaz/nane zemin, hafif büyüyen ikon, mikro sağa kayma (translate-x) */
    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover {
        background-color: #f1f9f8 !important;
        transform: translateX(2px) !important;
    }

    .dark .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover {
        background-color: rgba(54, 196, 178, 0.12) !important;
        transform: translateX(2px) !important;
    }

    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover .fi-sidebar-item-label {
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .dark .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover .fi-sidebar-item-label {
        color: #ffffff !important;
    }

    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover > .fi-icon,
    .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
        transform: scale(1.12) !important;
    }

    /* Active State: Hafif turkuaz dolgu zemin + sol kenarda 3.5px dikey vurgu çizgisi */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background: #e6f6f4 !important;
        box-shadow: inset 3.5px 0 0 0 #029786 !important;
        transform: none !important;
    }

    .dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background: rgba(2, 151, 134, 0.22) !important;
        box-shadow: inset 3.5px 0 0 0 #36c4b2 !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: #004d43 !important;
        font-weight: 700 !important;
    }

    .dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: #5eead4 !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-icon,
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
        transform: none !important;
    }

    .fi-sidebar-item-badge-ctn {
        display: none !important;
    }

    /* Topbar Rechts */
    .mms-topbar-site-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.38rem 0.75rem;
        border-radius: var(--radius-md);
        font-size: 0.815rem;
        font-weight: 600;
        color: #029786;
        background-color: #e6f6f4;
        border: 1px solid rgba(2, 151, 134, 0.22);
        text-decoration: none;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(2, 151, 134, 0.06);
    }

    .mms-topbar-site-link:hover {
        background-color: #029786;
        border-color: #029786;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 151, 134, 0.28);
        transform: translateY(-1px);
    }

    .mms-topbar-site-link svg {
        transition: transform 0.18s ease;
    }

    .mms-topbar-site-link:hover svg {
        transform: translate(1px, -1px);
    }

    .dark .mms-topbar-site-link {
        color: #5eead4;
        background-color: rgba(2, 151, 134, 0.16);
        border-color: rgba(94, 234, 212, 0.25);
    }

    .dark .mms-topbar-site-link:hover {
        background-color: #029786;
        color: #ffffff;
        border-color: #029786;
    }

    .mms-topbar-user-pill {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.55rem !important;
        padding: 0.25rem 0.5rem 0.25rem 0.25rem !important;
        border-radius: var(--radius-md) !important;
        background-color: transparent !important;
        border: 1px solid transparent !important;
        cursor: pointer !important;
        transition: background-color 0.15s ease !important;
    }

    .mms-topbar-user-pill:hover {
        background-color: #f1f5f9 !important;
    }

    .dark .mms-topbar-user-pill:hover {
        background-color: rgba(255, 255, 255, 0.06) !important;
    }

    .mms-topbar-user-pill .fi-user-avatar {
        width: 1.9rem !important;
        height: 1.9rem !important;
        border-radius: var(--radius-md) !important;
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
        color: var(--color-text-primary);
        white-space: nowrap;
    }

    .mms-topbar-user-role {
        font-size: 0.68rem;
        font-weight: 500;
        color: var(--color-text-secondary);
        white-space: nowrap;
    }

    .mms-topbar-user-chevron {
        width: 1rem;
        height: 1rem;
        color: var(--color-text-secondary);
        flex-shrink: 0;
    }

    /* ==========================================================================
       3. PAGE HEADER (.page-header & .top-bar aus SupportCallSystem.Frontend)
          KEINE Kasten-Box, KEIN Verlauf, direkt auf dem #f2f9f8 Body!
       ========================================================================== */
    .fi-main .fi-header {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 1rem !important;
    }

    .fi-main .fi-header::after {
        display: none !important;
    }

    /* Listen-Seiten (.page-header in users-list / customers-list) */
    .scs-title-row {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .scs-title-icon {
        width: 1.75rem !important;
        height: 1.75rem !important;
        color: var(--color-primary) !important;
        flex-shrink: 0;
    }

    .scs-count-badge {
        background-color: var(--color-status-neutral-bg);
        color: var(--color-text-secondary);
        padding: 2px 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.4;
    }

    .fi-main .fi-header-heading {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: var(--color-text-primary) !important;
        margin: 0 !important;
        line-height: 1.25 !important;
        letter-spacing: 0 !important;
    }

    .fi-main .fi-header-subheading {
        color: var(--color-text-secondary) !important;
        font-size: 0.875rem !important;
        font-weight: 400 !important;
        margin: 0.25rem 0 0 0 !important;
        line-height: 1.4 !important;
    }

    /* Auf Listen-Seiten ist der Untertitel um 2.5rem eingerückt (unter dem Text nach dem 1.75rem Icon) */
    .fi-resource-list-records-page .fi-header-subheading {
        margin-left: 2.5rem !important;
    }

    /* Hinzufügen-, Bearbeiten- & Einstellungs-Seiten: Sticky .top-bar
       Links: [← Zurück] | Mitte: [Titel + Untertitel] */
    .fi-resource-create-record-page .fi-header,
    .fi-resource-edit-record-page .fi-header,
    .mms-settings-page .fi-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: var(--color-main-bg) !important;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    /* Untertitel wird auf Create/Edit/Settings direkt in .scs-top-bar-titles gerendert */
    .fi-resource-create-record-page .fi-header-subheading,
    .fi-resource-edit-record-page .fi-header-subheading,
    .mms-settings-page .fi-header-subheading {
        display: none !important;
    }

    .scs-top-bar-lead {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        min-width: 0;
    }

    .scs-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        background-color: var(--color-button-secondary-bg);
        color: var(--color-button-secondary-text);
        border: 1px solid var(--color-button-border);
        font-size: 13.5px;
        font-weight: 500;
        line-height: 1.5;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .scs-back-btn:hover {
        background-color: var(--color-table-header-bg);
        border-color: var(--color-brand);
        color: var(--color-brand);
    }

    .scs-back-icon {
        width: 1.1rem;
        height: 1.1rem;
        flex-shrink: 0;
    }

    .scs-top-bar-titles {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .scs-top-bar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-text-primary);
        line-height: 1.2;
        margin: 0;
    }

    .scs-top-bar-subtitle {
        font-size: 0.8rem;
        font-weight: 400;
        color: var(--color-text-secondary);
        line-height: 1.35;
        margin-top: 2px;
    }

    /* ==========================================================================
       4. BUTTON SYSTEM (.btn, .btn-primary, .btn-secondary, .btn-danger, .btn-sm)
          6px Radius (--radius-sm), kompakte Abmessungen
       ========================================================================== */
    .fi-main .fi-header .fi-btn,
    .fi-main .fi-sc-actions .fi-btn {
        border-radius: var(--radius-sm) !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
        box-shadow: none !important;
        --tw-ring-shadow: 0 0 #0000 !important;
        transition: all 0.15s ease !important;
    }

    /* Primary Button (.btn-primary: Speichern, + Neu) */
    .fi-main .fi-header .fi-btn.fi-color-primary,
    .fi-main .fi-sc-actions .fi-btn.fi-color-primary {
        background-color: var(--color-brand) !important;
        color: #ffffff !important;
        border: 1px solid transparent !important;
    }

    .fi-main .fi-header .fi-btn.fi-color-primary:hover,
    .fi-main .fi-sc-actions .fi-btn.fi-color-primary:hover {
        background-color: var(--color-brand-hover) !important;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md) !important;
    }

    /* Secondary Button (.btn-secondary: Abbrechen, Aktualisieren, Vorschau) */
    .fi-main .fi-header .fi-btn.fi-color-gray,
    .fi-main .fi-sc-actions .fi-btn.fi-color-gray {
        background-color: var(--color-button-secondary-bg) !important;
        color: var(--color-button-secondary-text) !important;
        border: 1px solid var(--color-button-border) !important;
    }

    .fi-main .fi-header .fi-btn.fi-color-gray:hover,
    .fi-main .fi-sc-actions .fi-btn.fi-color-gray:hover {
        background-color: var(--color-table-header-bg) !important;
        border-color: var(--color-brand) !important;
        color: var(--color-brand) !important;
    }

    /* Outlined Danger Button (.btn-danger: Löschen in .top-bar) */
    .fi-main .fi-header .fi-btn.fi-color-danger {
        background-color: var(--color-main-bg) !important;
        color: var(--color-button-danger-text) !important;
        border: 1px solid var(--color-button-danger-border) !important;
    }

    .fi-main .fi-header .fi-btn.fi-color-danger:hover {
        background-color: var(--color-button-danger-hover-bg) !important;
    }

    /* ==========================================================================
       5. LISTEN- & TABELLEN-KARTEN (.content-container, .filters-bar, .data-table)
       ========================================================================== */
    .fi-ta-ctn {
        background-color: var(--color-card-bg) !important;
        border-radius: var(--radius-xl) !important;
        border: 1px solid var(--color-card-border) !important;
        box-shadow: none !important;
        overflow: hidden !important;
        --tw-ring-shadow: 0 0 #0000 !important;
    }

    /* Kompakte einzeilige Toolbar (.filters-bar): kein leerer Aktions-Umbruch über dem Suchfeld */
    .fi-ta-header-toolbar {
        padding: 0.625rem 1rem !important;
        background-color: var(--color-card-bg) !important;
        border-bottom: 1px solid var(--color-table-border) !important;
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 0.75rem !important;
        min-height: 0 !important;
    }

    .fi-ta-header-toolbar > * {
        min-height: 0 !important;
    }

    .fi-ta-header-toolbar > .fi-ta-actions:empty {
        display: none !important;
    }

    .fi-ta-header-toolbar > div:last-child {
        margin-inline-start: auto !important;
        flex: 1 1 auto !important;
        width: auto !important;
        min-width: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 0.5rem !important;
    }

    .fi-ta-search-field {
        flex: 0 1 420px !important;
        max-width: 420px !important;
        width: 100% !important;
    }

    .fi-ta-search-field .fi-input-wrp {
        height: 36px !important;
        min-height: 36px !important;
        border: 1px solid var(--color-input-border) !important;
        border-radius: var(--radius-sm) !important;
        background-color: transparent !important;
        box-shadow: none !important;
        --tw-ring-shadow: 0 0 #0000 !important;
        transition: border-color 0.15s ease !important;
    }

    .fi-ta-search-field .fi-input-wrp:focus-within {
        border-color: var(--color-brand) !important;
        box-shadow: none !important;
    }

    /* Moderne Toolbar-Icon-Buttons (Filter & Spalten) passend zum Suchfeld & Marken-Türkis */
    .fi-ta-header-toolbar .fi-icon-btn {
        width: 36px !important;
        height: 36px !important;
        margin: 0 !important;
        border-radius: var(--radius-sm) !important;
        border: 1px solid var(--color-input-border) !important;
        background-color: var(--color-card-bg) !important;
        color: var(--color-text-secondary) !important;
        box-shadow: none !important;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease !important;
    }

    .fi-ta-header-toolbar .fi-icon-btn:hover,
    .fi-ta-header-toolbar .fi-icon-btn:focus-visible,
    .fi-ta-header-toolbar [aria-expanded="true"] > .fi-icon-btn,
    .fi-ta-header-toolbar .fi-icon-btn[aria-expanded="true"] {
        background-color: var(--color-brand-light) !important;
        border-color: var(--color-brand) !important;
        color: var(--color-brand) !important;
    }

    .fi-ta-header-toolbar .fi-icon-btn .fi-icon-btn-icon {
        width: 1.125rem !important;
        height: 1.125rem !important;
    }

    .fi-ta-header-toolbar .fi-icon-btn-badge-ctn {
        top: -6px !important;
        inset-inline-end: -6px !important;
    }

    .fi-ta-header-toolbar .fi-icon-btn-badge-ctn .fi-badge {
        min-width: 18px !important;
        height: 18px !important;
        padding: 0 4px !important;
        border-radius: 9999px !important;
        background-color: var(--color-brand) !important;
        color: #ffffff !important;
        border: 2px solid var(--color-card-bg) !important;
        font-size: 0.65rem !important;
        font-weight: 700 !important;
        line-height: 1 !important;
        box-shadow: none !important;
        --tw-ring-shadow: 0 0 #0000 !important;
    }

    /* .data-table Spaltenköpfe (th) */
    .fi-ta-table thead tr,
    .fi-ta-header-cell {
        background-color: var(--color-table-header-bg) !important;
    }

    .fi-ta-header-cell {
        padding: 0.55rem 0.75rem !important;
        border-bottom: 1px solid var(--color-table-border) !important;
    }

    .fi-ta-header-cell:first-of-type {
        padding-inline-start: 1rem !important;
    }

    .fi-ta-header-cell:last-of-type {
        padding-inline-end: 1rem !important;
    }

    .fi-ta-header-cell-label {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: var(--color-text-secondary) !important;
    }

    /* .data-table Zellen: kein doppeltes Padding auf .fi-ta-cell + innerem Spalten-Wrapper */
    .fi-ta-cell {
        padding: 0 !important;
        border-bottom: 1px solid var(--color-table-border) !important;
        vertical-align: middle !important;
    }

    /* Auswahl-, Gruppenauswahl- und Sortiergriff-Zellen haben keinen inneren Spalten-Wrapper */
    .fi-ta-table thead th.fi-ta-selection-cell {
        padding: 0.55rem 0.75rem !important;
    }

    .fi-ta-table tbody td.fi-ta-selection-cell,
    .fi-ta-cell.fi-ta-group-selection-cell,
    .fi-ta-cell:has(> .fi-ta-record-checkbox),
    .fi-ta-cell:has(> .fi-ta-reorder-handle) {
        padding: 0.5rem 0.75rem !important;
    }

    .fi-ta-cell.fi-ta-selection-cell:first-child,
    .fi-ta-cell.fi-ta-group-selection-cell:first-child,
    .fi-ta-cell:has(> .fi-ta-record-checkbox):first-child,
    .fi-ta-cell:has(> .fi-ta-reorder-handle):first-child {
        padding-inline-start: 1rem !important;
    }

    .fi-ta-cell.fi-ta-selection-cell:last-child,
    .fi-ta-cell.fi-ta-group-selection-cell:last-child,
    .fi-ta-cell:has(> .fi-ta-record-checkbox):last-child,
    .fi-ta-cell:has(> .fi-ta-reorder-handle):last-child {
        padding-inline-end: 1rem !important;
    }

    .fi-ta-row:last-child .fi-ta-cell {
        border-bottom: none !important;
    }

    .fi-ta-cell > .fi-ta-text:not(.fi-inline),
    .fi-ta-cell > .fi-ta-image:not(.fi-inline),
    .fi-ta-cell > .fi-ta-icon:not(.fi-inline),
    .fi-ta-cell > .fi-ta-toggle:not(.fi-inline),
    .fi-ta-cell > .fi-ta-checkbox:not(.fi-inline),
    .fi-ta-cell > .fi-ta-color:not(.fi-inline),
    .fi-ta-cell > .fi-ta-select:not(.fi-inline),
    .fi-ta-cell > .fi-ta-text-input:not(.fi-inline),
    .fi-ta-cell > a > .fi-ta-text:not(.fi-inline),
    .fi-ta-cell > a > .fi-ta-image:not(.fi-inline),
    .fi-ta-cell > a > .fi-ta-icon:not(.fi-inline),
    .fi-ta-cell > a > .fi-ta-toggle:not(.fi-inline),
    .fi-ta-cell > a > .fi-ta-color:not(.fi-inline),
    .fi-ta-cell:has(.fi-ta-actions) {
        padding: 0.5rem 0.75rem !important;
    }

    @media (min-width: 640px) {
        .fi-ta-cell:first-child > .fi-ta-text:not(.fi-inline),
        .fi-ta-cell:first-child > .fi-ta-image:not(.fi-inline),
        .fi-ta-cell:first-child > .fi-ta-icon:not(.fi-inline),
        .fi-ta-cell:first-child > .fi-ta-toggle:not(.fi-inline),
        .fi-ta-cell:first-child > .fi-ta-checkbox:not(.fi-inline),
        .fi-ta-cell:first-child > a > .fi-ta-text:not(.fi-inline),
        .fi-ta-cell:first-child > a > .fi-ta-image:not(.fi-inline),
        .fi-ta-cell:first-child:has(.fi-ta-actions) {
            padding-inline-start: 1rem !important;
        }

        .fi-ta-cell:last-child > .fi-ta-text:not(.fi-inline),
        .fi-ta-cell:last-child > .fi-ta-image:not(.fi-inline),
        .fi-ta-cell:last-child > .fi-ta-icon:not(.fi-inline),
        .fi-ta-cell:last-child > .fi-ta-toggle:not(.fi-inline),
        .fi-ta-cell:last-child > a > .fi-ta-text:not(.fi-inline),
        .fi-ta-cell:last-child > a > .fi-ta-image:not(.fi-inline),
        .fi-ta-cell:last-child:has(.fi-ta-actions) {
            padding-inline-end: 1rem !important;
        }
    }

    .fi-ta-text.fi-ta-text-has-descriptions {
        gap: 0.125rem !important;
    }

    .fi-ta-text .fi-ta-text-description {
        line-height: 1.35 !important;
    }

    .fi-ta-row {
        transition: background-color 0.15s ease !important;
    }

    .fi-ta-row:hover {
        background-color: var(--color-table-row-hover) !important;
    }

    /* Avatare & Vorschaubilder (36x36px, 10px Radius wie .user-avatar-small / .name-avatar) */
    .fi-ta-image img {
        border-radius: var(--radius-lg) !important;
        object-fit: cover;
    }

    /* .role-badge / .badge (12px Radius, dezente Farben) */
    .fi-badge {
        border-radius: var(--radius-badge) !important;
        padding: 0.25rem 0.625rem !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        --tw-ring-shadow: 0 0 #0000 !important;
    }

    /* .custom-paginator (#ffffff Hintergrund, 44px Höhe) */
    .fi-pagination {
        background-color: var(--color-pagination-bg) !important;
        border-top: 1px solid var(--color-table-border) !important;
        min-height: 44px !important;
        padding: 0.375rem 1rem !important;
        font-size: 12px !important;
        color: var(--color-text-secondary) !important;
    }

    /* ==========================================================================
       6. FORMULAR-KARTE (.form-card, .form-card-header, .form-grid, .input-wrap)
          Exakt wie in user-edit.component.scss & customer-edit.component.scss
       ========================================================================== */
    .fi-section {
        background-color: var(--color-card-bg) !important;
        border: 1px solid var(--color-card-border) !important;
        border-radius: var(--radius-xl) !important;
        box-shadow: var(--shadow-sm) !important;
        overflow: hidden;
        --tw-ring-shadow: 0 0 #0000 !important;
    }

    /* .form-card-header: #f8fafc Hintergrund, schlichtes 1.1rem Icon ohne Kasten */
    .fi-section-header {
        display: flex !important;
        align-items: center !important;
        gap: 0.6rem !important;
        padding: 1rem 1.25rem !important;
        border-bottom: 1px solid var(--color-card-border) !important;
        background-color: var(--color-table-header-bg) !important;
    }

    .fi-section-header > .fi-icon,
    .fi-section-header-icon {
        width: 1.1rem !important;
        height: 1.1rem !important;
        padding: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        color: var(--color-brand) !important;
        flex-shrink: 0;
    }

    .fi-section-header-heading {
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        color: var(--color-text-secondary) !important;
        letter-spacing: 0 !important;
    }

    .fi-section-header-description {
        color: var(--color-text-tertiary) !important;
        font-size: 0.78rem !important;
    }

    /* .form-grid Abstände */
    .fi-section-content {
        padding: 2rem 1.25rem 1.5rem !important;
    }

    /* .form-field label (0.75rem, font-weight: 500, #64748b) */
    .fi-fo-field-label-content {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: var(--color-text-secondary) !important;
    }

    .fi-fo-field-label-required-mark {
        color: var(--color-danger) !important;
        margin-left: 2px;
    }

    /* .input-wrap: Gestrichelte untere Linie (1px dashed #cbd5e1), bei Fokus durchgehend Brand-Farbe */
    .fi-section .fi-input-wrp {
        background-color: transparent !important;
        border: none !important;
        border-bottom: 1px dashed var(--color-input-border) !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        --tw-ring-shadow: 0 0 #0000 !important;
        padding: 0.2rem 0 !important;
        min-height: 2.15rem !important;
        transition: border-color 0.15s ease !important;
    }

    .fi-section .fi-input-wrp:focus-within {
        border-bottom-style: solid !important;
        border-bottom-color: var(--color-brand) !important;
        box-shadow: none !important;
    }

    /* Prefix-Icons in Formular-Inputs ausblenden (SupportCallSystem.Frontend hat reine Text-Inputs) */
    .fi-section .fi-input-wrp-prefix {
        display: none !important;
    }

    .fi-section .fi-input-wrp .fi-input,
    .fi-section .fi-input-wrp select,
    .fi-section .fi-input-wrp textarea {
        padding-inline-start: 0 !important;
        font-size: 0.9rem !important;
        color: var(--color-text-primary) !important;
        background: transparent !important;
    }

    /* ==========================================================================
       7. DASHBOARD (.dashboard-landing Stil: .stat-card mit 4px Top-Border)
       ========================================================================== */
    .fi-wi-stats-overview-stat {
        background-color: var(--color-card-bg) !important;
        border: 1px solid var(--color-card-border) !important;
        border-top: 4px solid var(--color-brand) !important;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg) !important;
        box-shadow: var(--shadow-sm) !important;
        padding: 20px !important;
        --tw-ring-shadow: 0 0 #0000 !important;
        transition: box-shadow 0.15s ease !important;
    }

    .fi-wi-stats-overview-stat.scs-stat-card-1,
    .fi-grid-col:nth-child(1) .fi-wi-stats-overview-stat {
        border-top-color: var(--color-brand) !important;
    }

    .fi-wi-stats-overview-stat.scs-stat-card-2,
    .fi-grid-col:nth-child(2) .fi-wi-stats-overview-stat {
        border-top-color: var(--color-warning) !important;
    }

    .fi-wi-stats-overview-stat.scs-stat-card-3,
    .fi-grid-col:nth-child(3) .fi-wi-stats-overview-stat {
        border-top-color: var(--color-success) !important;
    }

    .fi-wi-stats-overview-stat.scs-stat-card-4,
    .fi-grid-col:nth-child(4) .fi-wi-stats-overview-stat {
        border-top-color: var(--color-info) !important;
    }

    .fi-wi-stats-overview-stat:hover {
        box-shadow: var(--shadow-md) !important;
        transform: none !important;
    }

    /* .data-table-card.activity-panel (Dashboard „Neueste Aktivitäten“ Tabelle 1:1 aus dashboard-landing.component.scss & styles.scss) */
    .scs-activity-card {
        background-color: var(--color-card-bg);
        border: 1px solid var(--color-card-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 0;
        overflow: hidden;
        min-width: 0;
        width: 100%;
        transition: box-shadow 0.15s ease;
    }

    .scs-activity-card:hover {
        box-shadow: var(--shadow-md);
    }

    .scs-activity-header {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--color-table-border);
        background-color: var(--color-card-bg);
    }

    .scs-activity-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .scs-btn-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 500;
        color: var(--color-text-secondary);
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .scs-btn-link svg {
        width: 16px;
        height: 16px;
    }

    .scs-btn-link:hover {
        color: var(--color-primary);
        background-color: var(--color-table-row-hover);
    }

    .scs-table-container {
        overflow-x: auto;
        width: 100%;
        -webkit-overflow-scrolling: touch;
    }

    .scs-data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .scs-data-table thead {
        background-color: var(--color-table-header-bg);
    }

    .scs-data-table thead th {
        padding: 0.55rem 1rem;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--color-table-border);
        white-space: nowrap;
    }

    .scs-data-table tbody tr {
        border-bottom: 1px solid var(--color-table-border);
        transition: background-color 0.15s ease;
    }

    .scs-data-table tbody tr:nth-child(even) {
        background-color: var(--color-table-row-alt);
    }

    .scs-data-table tbody tr:hover {
        background-color: var(--color-table-row-hover);
    }

    .scs-data-table tbody tr:last-child {
        border-bottom: none;
    }

    .scs-data-table tbody tr.is-clickable {
        cursor: pointer;
    }

    .scs-data-table tbody td {
        padding: 0.6rem 1rem;
        font-size: 14px;
        color: var(--color-text-primary);
        vertical-align: middle;
    }

    .scs-cell-date {
        color: var(--color-text-secondary) !important;
        white-space: nowrap;
    }

    .scs-cell-main {
        font-weight: 500;
        color: var(--color-text-primary);
    }

    .scs-row-link {
        color: inherit;
        text-decoration: none;
    }

    .scs-row-link:hover {
        color: var(--color-brand);
    }

    .scs-customer-cell {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .scs-name-avatar {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        background: var(--color-table-header-bg);
        color: var(--color-text-secondary);
        flex-shrink: 0;
    }

    .scs-name-avatar.avatar-tone-1 {
        background: #c53030;
        color: #ffffff;
    }

    .scs-name-avatar.avatar-tone-2 {
        background: #6b46c1;
        color: #ffffff;
    }

    .scs-name-avatar.avatar-tone-3 {
        background: #2b6cb0;
        color: #ffffff;
    }

    .scs-name-avatar.avatar-tone-4 {
        background: #276749;
        color: #ffffff;
    }

    .scs-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: var(--radius-badge);
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .scs-badge-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .dark .scs-badge-success {
        background-color: #064e3b;
        color: #6ee7b7;
    }

    .scs-badge-muted {
        background-color: var(--color-status-neutral-bg);
        color: var(--color-status-neutral-text);
    }

    .scs-empty-state {
        padding: 48px;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    /* Dialoge exakt mittig */
    .fi-modal:not(.fi-modal-slide-over) .fi-modal-window-ctn {
        grid-template-rows: 1fr auto 1fr !important;
    }

    /* ==========================================================================
       11. REGISTER-TABS / EINSTELLUNGS-NAVIGATION (.fi-sc-tabs, .fi-tabs, .fi-tabs-item)
           Segmented Bar / Pill Navigation Best-Practice für Schuleinstellungen
       ========================================================================== */
    .fi-sc-tabs {
        gap: 1.25rem !important;
    }

    /* Tabs-Leiste als zusammenhängende, erhabene Navigationskarte */
    .fi-sc-tabs > .fi-tabs,
    .fi-tabs:not(.fi-contained),
    .fi-tabs.fi-contained {
        background-color: var(--color-card-bg) !important;
        border: 1px solid var(--color-card-border) !important;
        border-radius: var(--radius-xl) !important;
        box-shadow: var(--shadow-sm) !important;
        padding: 0.375rem !important;
        display: flex !important;
        max-width: 100% !important;
        width: 100% !important;
        gap: 0.375rem !important;
        overflow-x: auto !important;
        align-items: center !important;
        scrollbar-width: thin;
        --tw-ring-shadow: 0 0 #0000 !important;
    }

    .fi-sc-tabs-tablist {
        display: flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        width: 100% !important;
    }

    /* Jeder Tab als eigenständiger, abgerundeter Navigations-Button */
    .fi-tabs-item {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        padding: 0.625rem 1.125rem !important;
        border-radius: var(--radius-md) !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
        white-space: nowrap !important;
        color: var(--color-text-secondary) !important;
        background-color: transparent !important;
        border: 1px solid transparent !important;
        transition: all 0.15s ease-in-out !important;
        cursor: pointer !important;
        flex: 1 1 0px !important;
        min-width: fit-content !important;
        text-decoration: none !important;
        box-shadow: none !important;
    }

    .fi-tabs-item > .fi-icon {
        width: 1.15rem !important;
        height: 1.15rem !important;
        color: var(--color-text-tertiary) !important;
        transition: color 0.15s ease-in-out, transform 0.15s ease !important;
        flex-shrink: 0 !important;
    }

    .fi-tabs-item .fi-tabs-item-label {
        color: inherit !important;
        font-weight: inherit !important;
        font-size: 0.875rem !important;
    }

    /* Hover-Zustand für inaktive Tabs */
    .fi-tabs-item:not(.fi-active):hover {
        background-color: var(--color-table-header-bg) !important;
        color: var(--color-text-primary) !important;
        border-color: var(--color-card-border) !important;
    }

    .fi-tabs-item:not(.fi-active):hover > .fi-icon {
        color: var(--color-brand) !important;
        transform: translateY(-1px);
    }

    /* Aktiver Tab: Klar und leuchtend in Logo-Türkis (#029786) hervorgehoben */
    .fi-tabs-item.fi-active {
        background-color: var(--color-brand) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border-color: var(--color-brand) !important;
        box-shadow: 0 2px 8px rgba(2, 151, 134, 0.28) !important;
    }

    .fi-tabs-item.fi-active > .fi-icon {
        color: #ffffff !important;
    }

    .fi-tabs-item.fi-active .fi-tabs-item-label {
        color: #ffffff !important;
    }

    .dark .fi-tabs-item.fi-active {
        background-color: var(--color-brand) !important;
        color: #0f172a !important;
        border-color: var(--color-brand) !important;
    }

    .dark .fi-tabs-item.fi-active > .fi-icon,
    .dark .fi-tabs-item.fi-active .fi-tabs-item-label {
        color: #0f172a !important;
    }

    /* Badges innerhalb der Tabs (z. B. Wartungsmodus-Status, Element-Zähler) */
    .fi-tabs-item .fi-badge {
        margin-left: 0.35rem !important;
        font-size: 0.72rem !important;
        padding: 2px 8px !important;
        border-radius: var(--radius-badge) !important;
        font-weight: 600 !important;
        transition: all 0.15s ease !important;
    }

    .fi-tabs-item:not(.fi-active) .fi-badge {
        background-color: var(--color-status-neutral-bg) !important;
        color: var(--color-status-neutral-text) !important;
        border: 1px solid var(--color-card-border) !important;
    }

    .fi-tabs-item.fi-active .fi-badge {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
    }

    .dark .fi-tabs-item.fi-active .fi-badge {
        background-color: rgba(15, 23, 42, 0.25) !important;
        color: #0f172a !important;
        border: 1px solid rgba(15, 23, 42, 0.35) !important;
    }

    /* Aktiver Tab-Inhalt (.fi-sc-tabs-tab) ohne doppelte Card-Umrahmung */
    .fi-sc-tabs-tab.fi-active {
        margin-top: 1rem !important;
    }

    /* Mobile Anpassungen (< 768px) wie in SupportCallSystem.Frontend */
    @media (max-width: 768px) {
        .fi-main .fi-header {
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        .fi-resource-list-records-page .fi-header-subheading {
            margin-left: 0 !important;
        }

        .fi-main .fi-header-actions-ctn,
        .fi-main .fi-header .fi-ac {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .fi-main .fi-header .fi-btn {
            flex: 1 1 auto;
            justify-content: center;
        }

        .fi-ta-search-field {
            flex: 1 1 0% !important;
            min-width: 0 !important;
            max-width: 100% !important;
        }

        .fi-sc-tabs > .fi-tabs,
        .fi-tabs:not(.fi-contained),
        .fi-tabs.fi-contained {
            overflow-x: auto !important;
            justify-content: flex-start !important;
            -webkit-overflow-scrolling: touch;
        }

        .fi-tabs-item {
            flex: 0 0 auto !important;
            min-width: max-content !important;
            padding: 0.5rem 0.875rem !important;
        }
    }
</style>
