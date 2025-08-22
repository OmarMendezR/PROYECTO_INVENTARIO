<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;

// el home se redirige a productos
Route::redirect('/', '/productos');

// Rutas CRUD de productos
Route::resource('productos', ProductoController::class);

// Pedidos

Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
