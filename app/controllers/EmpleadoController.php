<?php

class EmpleadoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function dashboard() {
        // Seguridad: solo usuarios logueados
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $nombre = $_SESSION["usuario_nombre"];
        $idEmpleado = (int)$_SESSION['usuario_id'];

        // Cargar modelo de mantenimientos
        require_once __DIR__ . '/../models/Mantenimiento.php';
        $mantenimientoModel = new Mantenimiento($this->pdo);

        // Obtener solo los mantenimientos pendientes
        $mantenimientosPendientes = $mantenimientoModel->obtenerPendientesPorEmpleado($idEmpleado);

        // obtener los manetenimientos para entregar
        $mantenimientosParaEntregar = $mantenimientoModel->obtenerListosEntregar($idEmpleado);

        // Cargar vista del dashboard del empleado
        require __DIR__ . "/../views/empleado/dashboard.php";
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
?>
