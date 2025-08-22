<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'stock', 'stock_minimo', 'precio'];

    public function pedidosDetalles() {
        return $this->hasMany(PedidoDetalle::class);
    }

    public function salidas() {
        return $this->hasMany(Salida::class);
    }

    public function alertas() {
        return $this->hasMany(AlertaStock::class);
    }
}
