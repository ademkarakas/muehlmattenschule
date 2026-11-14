<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\AvatarProviders\LocalInitialsAvatarProvider;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\ActivityOverviewWidget;
use App\Filament\Widgets\SchoolOverviewStatsWidget;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\View\TablesIconAlias;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /**
     * Registriert moderne Outline-Icons für Tabellen-Filter und Spaltenauswahl.
     */
    public function boot(): void
    {
        FilamentIcon::register([
            TablesIconAlias::ACTIONS_FILTER => Heroicon::OutlinedAdjustmentsHorizontal,
            TablesIconAlias::ACTIONS_COLUMN_MANAGER => Heroicon::OutlinedViewColumns,
        ]);
    }

    /**
     * Konfiguriert das deutschsprachige Verwaltungs-Panel der Mühlmattenschule.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->defaultAvatarProvider(LocalInitialsAvatarProvider::class)
            ->brandName('Mühlmattenschule')
            ->brandLogo(fn (): View => view('filament.brand-logo'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/logo.png'))
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->colors([
                'primary' => Color::hex('#029786'),
                'warning' => Color::Amber,
            ])
            ->navigationGroups([
                NavigationGroup::make('Aktuelles & Schulleben'),
                NavigationGroup::make('Schulstruktur & Seiten'),
                NavigationGroup::make('System & Verwaltung'),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View => view('filament.admin-theme'),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): View => view('filament.unsaved-changes-dialog'),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn (): View => view('filament.topbar-actions'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                SchoolOverviewStatsWidget::class,
                ActivityOverviewWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SecurityHeadersMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
