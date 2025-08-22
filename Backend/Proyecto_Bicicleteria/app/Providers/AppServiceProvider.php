<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PedidoDetalle;
use App\Models\Salida;
use App\Observers\PedidoDetalleObserver;
use App\Observers\SalidaObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PedidoDetalle::observe(PedidoDetalleObserver::class);
        Salida::observe(SalidaObserver::class);
    }
}
