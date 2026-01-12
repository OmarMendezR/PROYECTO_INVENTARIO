<?php  

    require_once __DIR__ . '/../models/Usuario.php';

    class RegistroController {
        private $usuario;

        public function __construct($pdo) {
            $this->usuario = new Usuario($pdo);
        }

        public function procesar() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = $_POST["nombre"] ?? '';
                $correo = $_POST["correo"] ?? '';
                $password = $_POST["password"] ?? '';
                $rol = $_POST["rol"] ?? '';

                if (empty($nombre) || empty($correo) || empty($password) || empty($rol)) {
                    $error = "Todos los campos son obligatorios.";
                    require __DIR__ . "/../views/registro/index.php";
                    return;
                }

                if ($this->usuario->buscarPorCorreo($correo)) {
                    $error = "El correo ya está registrado.";
                    require __DIR__ . "/../views/registro/index.php";
                    return;
                }

                if ($this->usuario->crear($nombre, $correo, $password, $rol)) {
                    header("Location: index.php?page=login");
                    exit();
                } else {
                    $error = "Error al registrar el usuario. Intenta de nuevo.";
                    require __DIR__ . "/../views/registro/index.php";
                }
            } else {
                require __DIR__ . "/../views/registro/index.php";
            }
        }
    }
?>