<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        // Filtrar los productos con stock bajo el mínimo
        $pedidos = Producto::whereColumn('stock', '<', 'stock_minimo')->get();

        // Retornar la vista con la variable $pedidos
        return view('pedidos.index', compact('pedidos'));
    }
}
