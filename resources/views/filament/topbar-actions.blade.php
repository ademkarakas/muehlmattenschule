@php
    $schoolSettings = \App\Support\SeoData::schoolSettings();
    $isMaintenanceActive = (bool) ($schoolSettings['maintenance_active'] ?? false);
@endphp

@if ($isMaintenanceActive)
    <a
        href="{{ url('/admin/manage-school-settings') }}"
        style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.38rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; color: #78350f; background-color: #fef3c7; border: 1px solid #f59e0b; text-decoration: none; white-space: nowrap;"
        title="Der Wartungsmodus ist aktiv. Klicken Sie hier, um ihn in den Schuleinstellungen zu beenden."
    >
        <span style="display: inline-block; width: 0.5rem; height: 0.5rem; border-radius: 9999px; background-color: #d97706;"></span>
        <span>Wartungsmodus AKTIV</span>
    </a>
@endif

<a
    href="{{ url('/') }}"
    target="_blank"
    rel="noopener noreferrer"
    class="mms-topbar-site-link"
    title="{{ $isMaintenanceActive ? 'Öffentliche Schulwebsite in der Mitarbeiter-Vorschau öffnen (Wartungsmodus aktiv)' : 'Öffentliche Schulwebsite in neuem Tab öffnen' }}"
>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem; flex-shrink: 0;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
    </svg>
    <span>{{ $isMaintenanceActive ? 'Zur Website (Vorschau)' : 'Zur Website' }}</span>
</a>
