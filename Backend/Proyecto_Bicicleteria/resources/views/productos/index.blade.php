@extends('layouts.app')

@section('title', 'Listado de Productos')
@section('bodyClass', 'body-productos') {{-- para aplicar fondo de la sección --}}

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Productos</h1>
    <a href="{{ route('productos.create') }}" class="btn btn-custom">+ Nuevo Producto</a>
  </div>

  @if($productos->isEmpty())
    <div class="alert alert-info">No hay productos aún. ¡Crea el primero!</div>
  @else
    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Precio</th>
                <th style="width:160px">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($productos as $producto)
                <tr>
                  <td>{{ $producto->id }}</td>
                  <td>{{ $producto->nombre }}</td>
                  <td>
                    <span class="badge {{ $producto->stock < $producto->stock_minimo ? 'text-bg-danger' : 'text-bg-success' }}">
                      {{ $producto->stock }}
                    </span>
                  </td>
                  <td>${{ number_format($producto->precio, 2) }}</td>
                  <td>
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@endsection
