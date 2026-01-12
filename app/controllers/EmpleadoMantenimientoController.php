<?php
require_once __DIR__ . '/../models/Mantenimiento.php';
require_once __DIR__ . '/../models/ClaseMantenimiento.php';
require_once __DIR__ . '/../models/Producto.php';

class EmpleadoMantenimientoController {

    private Mantenimiento $mantenimiento;
    private ClaseMantenimiento $claseModel;
    private Producto $productoModel;

    public function __construct(PDO $pdo) {
        $this->mantenimiento = new Mantenimiento($pdo);
        $this->claseModel = new ClaseMantenimiento($pdo);
        $this->productoModel = new Producto($pdo);
    }

    public function listar() {
        if (!isset($_SESSION['usuario_id'])) { header('Location: index.php?page=login'); exit; }

        $rol = $_SESSION['rol'] ?? 'empleado';
        $idEmpleado = (int)$_SESSION['usuario_id'];
        $busqueda = trim($_GET['buscar'] ?? '');

        if ($rol === 'admin') {
            // Admin ve todos los mantenimientos
            if ($busqueda !== '') {
                $mantenimientos = $this->mantenimiento->buscarPorClienteAdmin($busqueda);
            } else {
                $mantenimientos = $this->mantenimiento->obtenerTodos();
            }
        } else {
            // Empleado ve solo los suyos
            if ($busqueda !== '') {
                $mantenimientos = $this->mantenimiento->buscarPorCliente($idEmpleado, $busqueda);
            } else {
                $mantenimientos = $this->mantenimiento->obtenerTodosPorEmpleado($idEmpleado);
            }
        }

        require __DIR__ . '/../views/mantenimientos/listar.php';
    }

    public function crear() {
        if (!isset($_SESSION['usuario_id'])) { header('Location: index.php?page=login'); exit; }

        $rol = $_SESSION['rol'] ?? null;
        $clases = $this->claseModel->obtenerTodos();
        $productosDisponibles = $this->productoModel->obtenerTodos($rol);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idEmpleado = (int)$_SESSION['usuario_id'];
            $cliente = trim($_POST['cliente']);
            $contacto = trim($_POST['contacto']);
            $idClase = (int)$_POST['id_clase'];
            $detalles = trim($_POST['detalles']);
            $precio = (float)($_POST['precio_total'] ?? 0);

            // Productos
            $productos = [];
            $ids = $_POST['prod_id'] ?? [];
            $cants = $_POST['prod_cant'] ?? [];
            $pus = $_POST['prod_pu'] ?? [];

            for ($i=0; $i < count($ids); $i++) {
                if ($ids[$i] === "") continue;
                $productos[] = [
                    'id_producto' => (int)$ids[$i],
                    'cantidad' => (int)$cants[$i],
                    'precio_unitario' => (float)$pus[$i]
                ];
            }

            $id = $this->mantenimiento->crear($idEmpleado, $cliente, $contacto, $idClase, $detalles, $precio, $productos);

            if ($id) {
                header('Location: index.php?page=mantenimientos');
                exit;
            }

            $error = "Error al registrar mantenimiento.";
        }

        require __DIR__ . '/../views/mantenimientos/crear.php';
    }

    public function ver($id) {
        if (!isset($_SESSION['usuario_id'])) { header('Location: index.php?page=login'); exit; }

        $m = $this->mantenimiento->obtenerPorId((int)$id);
        if (!$m) {
            header('Location: index.php?page=mantenimientos');
            exit;
        }

        require __DIR__ . '/../views/mantenimientos/ver.php';
    }

    public function editar($id) {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $m = $this->mantenimiento->obtenerPorId((int)$id);
        if (!$m) {
            header('Location: index.php?page=mantenimientos');
            exit;
        }

        $rol = $_SESSION['rol'] ?? null;
        $clases = $this->claseModel->obtenerTodos();
        $productosDisponibles = $this->productoModel->obtenerTodos($rol);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $cliente  = trim($_POST['cliente']);
            $contacto = trim($_POST['contacto']);
            $idClase  = (int)$_POST['id_clase'];
            $detalles = trim($_POST['detalles']);

            /* =========================
               RECALCULAR PRECIO TOTAL
               ========================= */
            $precioTotal = 0;

            $clase = $this->claseModel->obtenerPorId($idClase);
            if ($clase) {
                $precioTotal += (float)$clase['precio'];
            }

            $productos = [];
            $ids   = $_POST['prod_id'] ?? [];
            $cants = $_POST['prod_cant'] ?? [];

            for ($i = 0; $i < count($ids); $i++) {
                if (empty($ids[$i])) continue;
                $cantidad = (int)$cants[$i];
                if ($cantidad <= 0) continue;

                $producto = $this->productoModel->obtenerPorId((int)$ids[$i]);
                if (!$producto) continue;

                $precioUnit = (float)$producto['precio_venta'];
                $subtotal = $precioUnit * $cantidad;

                $precioTotal += $subtotal;

                $productos[] = [
                    'id_producto'    => (int)$ids[$i],
                    'cantidad'       => $cantidad,
                    'precio_unitario'=> $precioUnit
                ];
            }

            if ($this->mantenimiento->actualizar(
                (int)$id,
                $cliente,
                $contacto,
                $idClase,
                $detalles,
                $precioTotal,
                $productos
            )) {
                header('Location: index.php?page=mantenimientos');
                exit;
            }

            $error = "Error al actualizar mantenimiento.";
        }

        require __DIR__ . '/../views/mantenimientos/editar.php';
    }

    public function cambiarEstado($id) {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $estado = $_POST['estado'] ?? null;

        if ($estado && in_array($estado, ['en_proceso', 'listo_para_entregar', 'entregado'])) {
            $this->mantenimiento->cambiarEstado((int)$id, $estado);
        }

        header("Location: index.php?page=mantenimientos");
        exit;
    }

    public function eliminar($id) {
        if (!isset($_SESSION['usuario_id'])) { header('Location: index.php?page=login'); exit; }

        $this->mantenimiento->eliminar((int)$id);

        header("Location: index.php?page=mantenimientos");
        exit;
    }
}
?>
