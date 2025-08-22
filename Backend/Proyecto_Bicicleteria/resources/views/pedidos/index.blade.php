@extends('layouts.app')

@section('title', 'Pedidos Pendientes')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-box-seam"></i> Pedidos Pendientes</h2>
    <p>Listado de productos con stock bajo el mínimo</p>

    @if($pedidos->isEmpty())
        <div class="alert alert-info">No hay pedidos pendientes 🎉</div>
    @else
        <table class="tabla pedidos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Cantidad a Pedir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ $p->stock_minimo }}</td>
                        <td>{{ $p->stock_minimo - $p->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('productos.index') }}" class="btn">← Volver a productos</a>
</div>
@endsection
