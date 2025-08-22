<?php

namespace App\Observers;

use App\Models\AlertaStock;
use App\Models\PedidoDetalle;

class PedidoDetalleObserver
{
    /** evento  create */
    public function created(PedidoDetalle $pedidoDetalle): void
    {
        $producto = $pedidoDetalle->producto;

        // sumar cantidad en stock
        $producto->stock += $pedidoDetalle->cantidad;
        $producto->saved();

        //verificar stock minimo

        if ($producto->stock < $producto->stock_minimo) {
            AlertaStock::create([
                'producto_id' => $producto->id,
                'mensaje' => "Stock disponible bajo. Stock actual: {$producto->stock}"
            ]);
        }
    }

    /** evento update */
    public function updated(PedidoDetalle $pedidoDetalle): void
    {
        $producto = $pedidoDetalle->producto;
        $diferencia = $pedidoDetalle->cantidad - $pedidoDetalle->getOriginal('cantidad');

        $producto->stock += $diferencia;
        $producto->saved();

        if ($producto->stock < $producto->stock_minimo) {
            AlertaStock::create([
                'producto_id'=>$producto->id,
                'mensaje'=> "Stock bajo. Stock actual: {$producto->stock}"
            ]);
        }
    }

    /**
     * Handle the PedidoDetalle "deleted" event.
     */
    public function deleted(PedidoDetalle $pedidoDetalle): void
    {
        //
    }

    /**
     * Handle the PedidoDetalle "restored" event.
     */
    public function restored(PedidoDetalle $pedidoDetalle): void
    {
        //
    }

    /**
     * Handle the PedidoDetalle "force deleted" event.
     */
    public function forceDeleted(PedidoDetalle $pedidoDetalle): void
    {
        //
    }
}
