<?php 

    require_once __DIR__ . '/../models/Usuario.php';

    class LoginController {
        private $usuario;

        public function __construct($pdo){
           $this->usuario = new Usuario($pdo);
        }

        public function procesar () {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $correo = $_POST["correo"];
                $password = $_POST["password"];

                $user = $this->usuario->buscarPorCorreo($correo);

                if ($user && password_verify($password, $user["password"])) {
                    // Normalizar rol
                    $rol = strtolower(trim($user["rol"]));
                    if ($rol === "administrador") $rol = "admin";

                    // Guardar sesión (una sola vez)
                    $_SESSION["usuario_id"] = $user["id"];
                    $_SESSION["usuario_nombre"] = $user["nombre"];
                    $_SESSION["rol"] = $rol;

                    // Redirección según rol normalizado
                    if ($rol === "admin") {
                        header("Location: index.php?page=admin");
                    } elseif ($rol === "empleado") {
                        header("Location: index.php?page=empleado");
                    } else {
                        header("Location: index.php?page=login");
                    }
                    exit;
                
                } else{
                    $error = "Correo o contraseña incorrectos.";
                    require __DIR__ . "/../views/login/index.php";
                }
            } else {
                require __DIR__ . "/../views/login/index.php";
            }
        }
    }

?>