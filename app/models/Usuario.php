<?php
class Usuario {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos(): array {
        $sql = "SELECT id, nombre, correo, rol FROM usuarios ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function buscarPorCorreo(string $correo): ?array {
        $sql = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function crear(string $nombre, string $correo, string $password, string $rol): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, correo, password, rol) VALUES (:nombre, :correo, :password, :rol)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':password' => $hash,
            ':rol' => $rol
        ]);
    }

    public function actualizar(int $id, string $nombre, string $correo, ?string $password, string $rol): bool {
        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET nombre = :nombre, correo = :correo, password = :password, rol = :rol WHERE id = :id";
            $params = [':nombre'=>$nombre, ':correo'=>$correo, ':password'=>$hash, ':rol'=>$rol, ':id'=>$id];
        } else {
            $sql = "UPDATE usuarios SET nombre = :nombre, correo = :correo, rol = :rol WHERE id = :id";
            $params = [':nombre'=>$nombre, ':correo'=>$correo, ':rol'=>$rol, ':id'=>$id];
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function eliminar(int $id): bool {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>