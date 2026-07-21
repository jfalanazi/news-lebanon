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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            // هوية نشرة لبنان: أخضر أرزي أساسي + عنبري للتحذير + أحمر العلامة للخطر
            ->colors([
                'primary' => Color::hex('#0D5A33'),
                'success' => Color::hex('#146B3F'),
                'warning' => Color::hex('#B7791F'),
                'danger'  => Color::hex('#A8342B'),
                'gray'    => Color::Stone,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            // بطاقة الترحيب/الخروج أُزيلت — اللوحة للفعل لا للعرض (الخروج من قائمة المستخدم أعلى الشاشة)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            // تعتيم الأخبار المُطفأة + محاذاة الهيدر والتبويبات لليمين (RTL)
            ->renderHook(
                'panels::head.end',
                fn (): string => '<style>'
                    . '.nashra-dim{opacity:.4 !important;transition:opacity .35s ease}'
                    . '.fi-header{justify-content:flex-start !important;gap:1rem;flex-wrap:wrap}'
                    . '.fi-header-heading{flex:0 1 auto !important}'
                    . '.fi-ta-header-toolbar{justify-content:flex-start !important;gap:.75rem}'
                    . '.fi-ta-header-toolbar>.fi-ta-header-heading,.fi-ta-header-toolbar>.fi-ta-search-field{flex:0 1 auto !important}'
                    . '.fi-tabs{justify-content:flex-start !important;width:100%}'
                    . '</style>',
            )
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
