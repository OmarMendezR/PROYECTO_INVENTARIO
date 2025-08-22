<?php

namespace App\Observers;

use App\Models\AlertaStock;
use App\Models\Salida;

class SalidaObserver
{
    /** create event */
    public function created(Salida $salida): void
    {
        $producto = $salida->producto;

        // Disminuir stock minimo 0 

        $producto->stock = max(0, $producto->stock - $salida->cantidad);
        $producto->save();

        // verificar stock minimo

        if ($producto->stock < $producto->stock_minimo) {
            AlertaStock::create([
                'producto_id' => $producto->id,
                'mensaje' => "Stock bajo despues de venta. Stock actual: {$producto->stock}"
            ]);
        }
    }

    /**
     * Handle the Salida "updated" event.
     */
    public function updated(Salida $salida): void
    {
        //
    }

    /**
     * Handle the Salida "deleted" event.
     */
    public function deleted(Salida $salida): void
    {
        //
    }

    /**
     * Handle the Salida "restored" event.
     */
    public function restored(Salida $salida): void
    {
        //
    }

    /**
     * Handle the Salida "force deleted" event.
     */
    public function forceDeleted(Salida $salida): void
    {
        //
    }
}
