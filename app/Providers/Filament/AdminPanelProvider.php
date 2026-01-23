<?php

namespace App\Providers\Filament;

use App\Livewire\ComprasMesChart;
use App\Livewire\PagarHojeStatsOverview;
use App\Livewire\ReceberHojeStatsOverview;
use App\Livewire\TotalCompraStatsOverview;
use App\Livewire\VendasMesChart;
use App\Livewire\VendasPDVMesChart;
use App\Livewire\TotalVendasPorCliente;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Hugomyb\FilamentErrorMailer\FilamentErrorMailerPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
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
            ->favicon(asset('img/logo.png'))
            ->brandLogo(asset('img/logo.png'))
            ->brandLogoHeight('3rem')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //  Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                PagarHojeStatsOverview::class,
                ReceberHojeStatsOverview::class,
              //  VendasPDVMesChart::class,
              //  TotalCompraStatsOverview::class,
                //  Widgets\FilamentInfoWidget::class,
            //     TotalCompraStatsOverview::class,
            //     PagarHojeStatsOverview::class,
            //     ReceberHojeStatsOverview::class,
            //     //  VendasMesChart::class,
            //     VendasPDVMesChart::class,
            //   //  ComprasMesChart::class,
            //     TotalVendasPorCliente::class,

                // RanckingProdutos::class,

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
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                function (): string {
                    return Blade::render('@laravelPWA');
                }
            )
            ->resources([
                config('filament-logger.activity_resource'),

            ])
            ->renderHook(
                PanelsRenderHook::FOOTER,
                function () {
                    $parametro = \App\Models\Parametro::first();
                    $version = $parametro->versao_sistema ?? '1.0.0';


                    return \Illuminate\Support\Facades\Blade::render('
            <footer class="border-t bg-gray-50/50">
                <div class="flex justify-end w-full px-4 py-3">
                    <div class="flex flex-col items-end gap-2 text-xs text-gray-600 sm:flex-row sm:gap-6 sm:items-center">
                        <span>© {{ date("Y") }} Wsys - Sistemas - Todos os direitos reservados</span>
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                </svg>
                                Versão Sistema {{ $version }}
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        ', [
                        'version' => $version,

                    ]);
                }
            )
            ->plugins([
                FilamentErrorMailerPlugin::make(),
            ]);
    }
}
