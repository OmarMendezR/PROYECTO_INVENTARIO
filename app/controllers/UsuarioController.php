<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private Usuario $usuario;

    public function __construct(PDO $pdo) {
        $this->usuario = new Usuario($pdo);
    }

    public function listar() {
        $usuarios = $this->usuario->obtenerTodos();
        require __DIR__ . '/../views/admin/usuarios/listar.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'empleado';

            if ($nombre === '' || $correo === '' || $password === '') {
                $error = 'Nombre, correo y contraseña son obligatorios.';
                require __DIR__ . '/../views/admin/usuarios/crear.php';
                return;
            }

            if ($this->usuario->buscarPorCorreo($correo)) {
                $error = 'El correo ya está registrado.';
                require __DIR__ . '/../views/admin/usuarios/crear.php';
                return;
            }

            if ($this->usuario->crear($nombre, $correo, $password, $rol)) {
                header('Location: index.php?page=usuarios');
                exit;
            } else {
                $error = 'Error al crear usuario.';
                require __DIR__ . '/../views/admin/usuarios/crear.php';
            }
        } else {
            require __DIR__ . '/../views/admin/usuarios/crear.php';
        }
    }

    public function editar($id) {
        $id = (int)$id;
        $usuario = $this->usuario->obtenerPorId($id);
        if (!$usuario) { header('Location: index.php?page=usuarios'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? null; // opcional
            $rol = $_POST['rol'] ?? 'empleado';

            if ($nombre === '' || $correo === '') {
                $error = 'Nombre y correo son obligatorios.';
                require __DIR__ . '/../views/admin/usuarios/editar.php';
                return;
            }

            $ex = $this->usuario->buscarPorCorreo($correo);
            if ($ex && (int)$ex['id'] !== $id) {
                $error = 'El correo ya pertenece a otro usuario.';
                require __DIR__ . '/../views/admin/usuarios/editar.php';
                return;
            }

            if ($this->usuario->actualizar($id, $nombre, $correo, $password, $rol)) {
                header('Location: index.php?page=usuarios');
                exit;
            } else {
                $error = 'Error al actualizar usuario.';
            }
        }

        require __DIR__ . '/../views/admin/usuarios/editar.php';
    }

    public function eliminar($id) {
        $id = (int)$id;
        if ($this->usuario->eliminar($id)) {
            header('Location: index.php?page=usuarios');
            exit;
        } else {
            $error = 'Error al eliminar usuario.';
            $usuarios = $this->usuario->obtenerTodos();
            require __DIR__ . '/../views/admin/usuarios/listar.php';
        }
    }
}
?>