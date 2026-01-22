<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\OrderStats;
use App\Filament\Widgets\OrderPerLocationStats;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// 🔽 IMPORT RESOURCE (INI YANG PENTING)
use App\Filament\Resources\UserResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\LocationResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductOptionResource;
use App\Filament\Resources\ProductAddonResource;
use App\Filament\Resources\PromoResource;
use App\Filament\Resources\OrderResource;

// Filament Navigation
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('employee')

            ->favicon(asset('images/pizza-boxx-logo.png'))

            ->sidebarWidth('16rem')
            ->brandLogoHeight('4rem')

            ->brandLogo(fn () => new HtmlString(
                '<div class="flex items-center justify-center gap-4">
                    <img src="' . asset('images/pizza-boxx-logo.png') . '" 
                        alt="Pizza Boxx Logo" 
                        class="' . (request()->routeIs('filament.admin.auth.login') ? 'h-16 w-16' : 'h-10 w-10') . '" />
                    ' . (!request()->routeIs('filament.admin.auth.login') ? 
                        '<span class="font-bold text-lg">Admin Panel</span>' : '') . '
                </div>'
            ))

            ->brandName(fn () => new HtmlString(
                '<div class="flex flex-col items-center justify-center gap-16">
                    <img src="' . asset('images/pizza-boxx-logo.png') . '" class="h-10 w-10" />
                    <span class="font-bold text-lg">Pizza Boxx</span>
                </div>'
            ))

            ->colors([
                'primary' => Color::Red,
                'secondary' => Color::hex('#FFC107'),
            ])

            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                OrderStats::class,
                OrderPerLocationStats::class,
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

            // ->navigationGroups(array_filter([
            //     (auth('employee')->check() && auth('employee')->user()->isSuperAdmin())
            //         ? NavigationGroup::make('Manajemen Pusat')->items([
            //             NavigationItem::make('Dashboard')
            //                 ->url(fn () => route('filament.admin.pages.dashboard'))
            //                 ->icon('heroicon-o-home'),

            //             NavigationItem::make('Users')
            //                 ->url(fn () => UserResource::getUrl())
            //                 ->icon('heroicon-o-users'),

            //             NavigationItem::make('Categories')
            //                 ->url(fn () => CategoryResource::getUrl())
            //                 ->icon('heroicon-o-tag'),

            //             NavigationItem::make('Locations')
            //                 ->url(fn () => LocationResource::getUrl())
            //                 ->icon('heroicon-o-building-storefront'),

            //             NavigationItem::make('Products')
            //                 ->url(fn () => ProductResource::getUrl())
            //                 ->icon('heroicon-o-archive-box'),

            //             NavigationItem::make('Product Options')
            //                 ->url(fn () => ProductOptionResource::getUrl())
            //                 ->icon('heroicon-o-adjustments-vertical'),

            //             NavigationItem::make('Product Addons')
            //                 ->url(fn () => ProductAddonResource::getUrl())
            //                 ->icon('heroicon-o-plus-circle'),

            //             NavigationItem::make('Promos')
            //                 ->url(fn () => PromoResource::getUrl())
            //                 ->icon('heroicon-o-gift'),
            //         ])
            //         : null,

            //     (auth('employee')->check() && auth('employee')->user()->isSuperAdmin())
            //         ? NavigationGroup::make('Manajemen Pesanan')->items([
            //             NavigationItem::make('Orders')
            //                 ->url(fn () => OrderResource::getUrl())
            //                 ->icon('heroicon-o-shopping-bag'),
            //         ])
            //         : null,
            // ]));

            ->navigationGroups([
                'Manajemen Pusat',
                'Manajemen Pesanan',
                'Manajemen Cabang',
            ]);
    }
}