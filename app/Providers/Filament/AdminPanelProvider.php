<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\CaseStageChartWidget;
use App\Filament\Widgets\LegalPracticeStatsWidget;
use App\Filament\Widgets\TodayHearingsTableWidget;
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
            ->brandName('LexVanguard • 2027 AI Law OS')
            ->brandLogo(null)
            ->darkMode(true)
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
            ])
            ->navigationGroups([
                'Litigation & Cases',
                '2027 AI Legal Suite',
                'Clients & Contacts',
                'Finance & Trust (IOLTA)',
                'Practice Operations',
                'Statutes & Jurisdiction',
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => '<link rel="stylesheet" href="/css/filament-custom.css">'
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => view('filament.auth.login-roles-helper')->render()
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                LegalPracticeStatsWidget::class,
                TodayHearingsTableWidget::class,
                CaseStageChartWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
