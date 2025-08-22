<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Bicicletería Rodríguez')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Estilos propios (paleta y fondos) --}}
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

{{-- bodyClass te deja poner clases/identificadores por sección para fondos --}}
<body class="bg-light @yield('bodyClass')">

  {{-- Navbar --}}
  <nav class="navbar navbar-expand-lg navbar-dark app-navbar">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('productos.index') }}">
        🚲 Bicicletería Rodríguez
      </a>

      {{-- Botón para colapsar en móvil --}}
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          {{-- Link Productos --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('productos.index') }}">Productos</a>
          </li>

          {{-- ✅ Nuevo Link a Pedidos --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pedidos.index') }}">Pedidos</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    @yield('content')
  </main>

  <footer class="text-center py-3 app-footer">
    <small>© 2025 Bicicletería Rodríguez</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
