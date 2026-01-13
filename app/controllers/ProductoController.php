<?php
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private $producto;

    public function __construct(PDO $pdo) {
        $this->producto = new Producto($pdo);
    }

    // Listar o buscar por nombre si existe ?q=
    public function listar() {
        $q = trim($_GET['q'] ?? '');
        $rol = $_SESSION['rol'] ?? null;

        // Paginación
        $pageNum = max(1, (int)($_GET['p'] ?? 1));
        $perPage = 10;
        $total = $this->producto->contarTotal($q);
        $totalPages = (int) max(1, ceil($total / $perPage));
        if ($pageNum > $totalPages) $pageNum = $totalPages;
        $offset = ($pageNum - 1) * $perPage;

        $productos = $this->producto->obtenerPagina($rol, $perPage, $offset, $q);

        require __DIR__ . '/../views/productos/listar.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio_venta = (float) ($_POST['precio_venta'] ?? 0);
            $precio_compra = (float) ($_POST['precio_compra'] ?? 0);
            $stock = (int) ($_POST['stock'] ?? 0);
            $stock_minimo = ($_POST['stock_minimo'] !== '' ? (int) $_POST['stock_minimo'] : null);

            if ($nombre === '' || $precio_venta <= 0 || $stock < 0) {
                $error = "Nombre, precio_venta y stock son obligatorios y deben ser válidos.";
                require __DIR__ . '/../views/productos/crear.php';
                return;
            }

            if ($this->producto->insertar($nombre, $descripcion, $precio_venta, $stock, $stock_minimo, $precio_compra)) {
                header("Location: index.php?page=productos");
                exit;
            } else {
                $error = "Error al guardar el producto.";
                require __DIR__ . '/../views/productos/crear.php';
            }
        } else {
            require __DIR__ . '/../views/productos/crear.php';
        }
    }

    public function editar($idOrName) {
        // Si llega un número tratamos como id, si no usamos nombre exacto
        if (is_numeric($idOrName)) {
            $id = (int) $idOrName;
            $producto = $this->producto->obtenerPorId($id);
            if (!$producto) { header("Location: index.php?page=productos"); exit; }
        } else {
            $producto = $this->producto->buscarPorNombre($idOrName);
            if (!$producto) { header("Location: index.php?page=productos"); exit; }
            $id = (int) $producto['id_producto'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio_venta = (float) ($_POST['precio_venta'] ?? 0);
            $precio_compra = (float) ($_POST['precio_compra'] ?? 0);
            $stock = (int) ($_POST['stock'] ?? 0);
            $stock_minimo = ($_POST['stock_minimo'] !== '' ? (int) $_POST['stock_minimo'] : null);

            if ($nombre === '' || $precio_venta <= 0 || $stock < 0) {
                $error = "Nombre, precio_venta y stock son obligatorios y deben ser válidos.";
                require __DIR__ . '/../views/productos/editar.php';
                return;
            }

            if ($this->producto->actualizar($id, $nombre, $descripcion, $precio_venta, $stock, $stock_minimo, $precio_compra)) {
                header("Location: index.php?page=productos");
                exit;
            } else {
                $error = "Error al actualizar el producto.";
            }
        }

        require __DIR__ . '/../views/productos/editar.php';
    }

    public function eliminar($id) {
        $id = (int) $id;
        if ($this->producto->eliminar($id)) {
            header("Location: index.php?page=productos");
            exit;
        } else {
            $error = "Error al eliminar el producto.";
            $rol = $_SESSION['rol'] ?? null;
            $productos = $this->producto->obtenerTodos($rol);
            require __DIR__ . '/../views/productos/listar.php';
        }
    }
}
?>