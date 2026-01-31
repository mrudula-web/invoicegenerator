<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\Heroicon;


class InvoicePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('invoice')
            ->path('invoice')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
    'panels::head.start',
    fn () => <<<HTML
        <style>
            /* Sidebar background */
            .fi-sidebar {
                background-color: #001f3f !important; /* Navy Blue */
            }

            /* Sidebar item text */
            .fi-sidebar-item-label {
                color: #ffffff !important;
            }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
    background-color: rgb(102, 102, 132);
         }
         .fi-sidebar-item > .fi-sidebar-item-btn:hover {
    background-color: rgb(102, 102, 132) !important;
           }
         .fi-ac-btn-action{
    background-color: #001f3f !important;
    color: #ffffff !important;
         }
      .fi-header {
    text-transform: uppercase !important;
         }

        </style>
    HTML
)

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
           ->pages([
    \App\Filament\Pages\CustomDashboard::class,
])


            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
