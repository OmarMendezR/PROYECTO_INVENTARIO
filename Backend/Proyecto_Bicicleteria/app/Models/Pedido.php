<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['cliente','fecha','estado'];

    public function detalles() {
        return $this -> hasMany(PedidoDetalle::class);
    }
}
