<?php
// habilitar para depuración de errores
 
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

session_start();
require __DIR__ . '/../config/conexion.php';

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        require __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController($pdo);
        $controller->procesar();
        break;

    case 'registro':
        require __DIR__ . '/../app/controllers/RegistroController.php';
        $controller = new RegistroController($pdo);
        $controller->procesar();
        break;

    case 'admin':
    if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    require __DIR__ . '/../app/controllers/AdminController.php';
    $controller = new AdminController($pdo);

    $action = $_GET['action'] ?? 'dashboard';

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Acción no válida: " . htmlspecialchars($action);
    }
    break;


    case 'empleado':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'empleado') {
            header('Location: index.php?page=login');
            exit;
        }
        require __DIR__ . '/../app/controllers/EmpleadoController.php';
        $controller = new EmpleadoController($pdo);
        $controller->dashboard();
        break;
    
    case 'usuarios':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        require __DIR__ . '/../app/controllers/UsuarioController.php';
        $uController = new UsuarioController($pdo);
        $action = $_GET['action'] ?? 'listar';
        $id = $_GET['id'] ?? null;
        switch ($action) {
            case 'crear':
                $uController->crear();
                break;
            case 'editar':
                if ($id) $uController->editar($id); else $uController->listar();
                break;
            case 'eliminar':
                if ($id) $uController->eliminar($id); else $uController->listar();
                break;
            case 'listar':
            default:
                $uController->listar();
        }
        break;
    
    case 'productos':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        require __DIR__ . '/../app/controllers/ProductoController.php';
        $controller = new ProductoController($pdo);

        $action = $_GET['action'] ?? 'listar';
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'crear':
                $controller->crear();
                break;
            case 'editar':
                if ($id !== null) {
                    $controller->editar($id);
                } else {
                    $controller->listar();
                }
                break;
            case 'eliminar':
                if ($id !== null) {
                    $controller->eliminar($id);
                } else {
                    $controller->listar();
                }
                break;
            case 'listar':
            default:
                $controller->listar();
        }
        break;

    case 'mantenimientos':

    if (!isset($_SESSION['usuario_id']) ||
        !in_array($_SESSION['rol'], ['empleado', 'admin'])) {
        header('Location: index.php?page=login');
        exit;
    }

    require_once __DIR__ . '/../app/controllers/EmpleadoMantenimientoController.php';
    $controller = new EmpleadoMantenimientoController($pdo);

    $action = $_GET['action'] ?? 'listar';
    $id = $_GET['id'] ?? null;

    switch ($action) {
        case 'crear':
            $controller->crear();
            break;
        case 'ver':
            if ($id) $controller->ver($id);
            else $controller->listar();
            break;
        case 'editar':
            if ($id) $controller->editar($id);
            else $controller->listar();
            break;
        case 'cambiarEstado':
            if ($id) $controller->cambiarEstado($id);
            else $controller->listar();
            break;
        case 'eliminar':
            if ($id) $controller->eliminar($id);
            else $controller->listar();
            break;
        default:
            $controller->listar();
            break;
    }
    break;

    case 'clases':

    // Control de sesión: solo empleados y admins
    if (!isset($_SESSION['usuario_id']) ||
        !in_array($_SESSION['rol'], ['empleado', 'admin'])) {
        header('Location: index.php?page=login');
        exit;
    }

    require_once __DIR__ . '/../app/controllers/ClaseMantenimientoController.php';
    $controller = new ClaseMantenimientoController($pdo);

    $action = $_GET['action'] ?? 'listar';
    $id = $_GET['id'] ?? null;

    switch ($action) {
        case 'crear':
            $controller->crear();
            break;
        case 'ver':
            if ($id) $controller->ver($id);
            else $controller->listar();
            break;
        case 'editar':
            if ($id) $controller->editar($id);
            else $controller->listar();
            break;
        case 'eliminar':
            if ($id) $controller->eliminar($id);
            else $controller->listar();
            break;
        case 'listar':
        default:
            $controller->listar();
            break;
    }
    break;


    case 'pedidos':

    if (!isset($_SESSION['usuario_id'])) {
        echo "NO HAY SESIÓN";
        exit;
    }

    require __DIR__ . '/../app/controllers/PedidoController.php';
    $controller = new PedidoController($pdo);

    $action = $_GET['action'] ?? 'ver';

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "NO EXISTE EL MÉTODO";
    }
    break;


    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        exit;
        break;

    default:
        header('Location: index.php?page=login');
        exit;
    }

?>