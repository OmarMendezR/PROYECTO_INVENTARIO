@extends('layouts.app')

@section('title', 'Editar producto')
@section('bodyClass', 'body-productos')

@section('content')
  <h1 class="h4 mb-3">Editar producto</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>Revisa los campos:</strong>
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('productos.update', $producto) }}" method="POST" class="card p-3 shadow-sm">
    @csrf
    @method('PUT')

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Precio</label>
        <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" class="form-control" required>
      </div>

      <div class="col-12">
        <label class="form-label">Descripción</label>
        <input type="text" name="descripcion" value="{{ old('descripcion', $producto->descripcion) }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $producto->stock) }}" class="form-control" min="0" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Stock mínimo</label>
        <input type="number" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" class="form-control" min="0" required>
      </div>
    </div>

    <div class="mt-3 d-flex gap-2">
      <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      <button class="btn btn-primary">Actualizar</button>
    </div>
  </form>
@endsection
