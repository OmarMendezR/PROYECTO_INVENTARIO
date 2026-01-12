<?php

class ClaseMantenimiento {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos(): array {
    $sql = "SELECT id_clase, nombre, precio, tiempo_entrega, duracion_minutos, descripcion FROM clase_mantenimientos ORDER BY nombre ASC";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT * FROM clase_mantenimientos WHERE id_clase = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function crear(string $nombre, string $descripcion, float $precio, int $tiempo, int $duracion_minutos): bool {
        $sql = "INSERT INTO clase_mantenimientos (nombre, descripcion, precio, tiempo_entrega, duracion_minutos)
                VALUES (:nombre, :descripcion, :precio, :tiempo, :duracion_minutos)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":precio" => $precio,
            ":tiempo" => $tiempo,
            ":duracion_minutos" => $duracion_minutos,
        ]);
    }

    public function editar(int $id, string $nombre, string $descripcion, float $precio, int $tiempo, int $duracion_minutos): bool {
        $sql = "UPDATE clase_mantenimientos SET nombre = :nombre, descripcion = :descripcion,
                precio = :precio, tiempo_entrega = :tiempo, duracion_minutos = :duracion_minutos WHERE id_clase = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":precio" => $precio,
            ":tiempo" => $tiempo,
            ":duracion_minutos" => $duracion_minutos,
        ]);
    }

    public function eliminar(int $id): bool {
        $sql = "DELETE FROM clase_mantenimientos WHERE id_clase = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
?>
