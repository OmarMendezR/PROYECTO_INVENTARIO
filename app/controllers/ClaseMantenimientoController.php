<?php
require_once __DIR__ . '/../models/ClaseMantenimiento.php';

class ClaseMantenimientoController {
    private ClaseMantenimiento $model;

    public function __construct(PDO $pdo) {
        $this->model = new ClaseMantenimiento($pdo);
    }

    public function listar() {
        $clases = $this->model->obtenerTodos();
        require __DIR__ . '/../views/clases/listar.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $precio = (float)$_POST['precio'];
            $tiempo = (int)$_POST['tiempo_entrega'];
            $duracion_minutos = (int)$_POST['duracion_minutos'];
            $ok = $this->model->crear($nombre, $descripcion, $precio, $tiempo, $duracion_minutos);

            if ($ok) {
                header("Location: index.php?page=clases");
                exit;
            }

            $error = "Error al crear clase.";
        }

        require __DIR__ . '/../views/clases/crear.php';
    }

    public function editar($id) {
        $clase = $this->model->obtenerPorId((int)$id);
        if (!$clase) {
            header("Location: index.php?page=clases");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $precio = (float)$_POST['precio'];
            $tiempo = (int)$_POST['tiempo_entrega'];
            $duracion_minutos = (int)$_POST['duracion_minutos'];

            $ok = $this->model->editar((int)$id, $nombre, $descripcion, $precio, $tiempo, $duracion_minutos);

            if ($ok) {
                header("Location: index.php?page=clases");
                exit;
            }

            $error = "Error al editar clase.";
        }

        require __DIR__ . '/../views/clases/editar.php';
    }

    public function ver($id) {
        $clase = $this->model->obtenerPorId((int)$id);
        if (!$clase) {
            header("Location: index.php?page=clases");
            exit;
        }
        require __DIR__ . '/../views/clases/ver.php';
    }

    public function eliminar($id) {
        $this->model->eliminar((int)$id);
        header("Location: index.php?page=clases");
        exit;
    }
}
?>
