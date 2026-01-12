<?php

class Pedido {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function crearPedido(): int {
        $sql = "INSERT INTO pedidos (fecha_pedido, total, estado)
                VALUES (NOW(), 0, 'pendiente')";
        $this->pdo->exec($sql);
        return (int)$this->pdo->lastInsertId();
    }

    public function obtenerPedidoAbierto(): ?array {
        $sql = "SELECT * FROM pedidos WHERE estado = 'pendiente' LIMIT 1";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function actualizarTotal(int $idPedido): void {
        $sql = "
            UPDATE pedidos
            SET total = (
                SELECT SUM(cantidad * costo_unitario)
                FROM detalle_pedido
                WHERE id_pedido = :pedido
            )
            WHERE id_pedido = :pedido
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pedido' => $idPedido]);
    }

    public function listarPedidos(): array {
        return $this->pdo
            ->query("SELECT * FROM pedidos ORDER BY fecha_pedido DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarEstado(int $idPedido, string $estado): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE pedidos SET estado = :estado WHERE id_pedido = :pedido"
        );
        return $stmt->execute([
            ':estado' => $estado,
            ':pedido' => $idPedido
        ]);
    }

    public function obtenerPedidoPorId(int $idPedido): ?array {
    $sql = "SELECT * FROM pedidos WHERE id_pedido = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $idPedido]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } 

    public function obtenerDetalle(int $idPedido): array {
    $sql = "
        SELECT d.*, p.nombre AS producto
        FROM detalle_pedido d
        JOIN productos p ON p.id_producto = d.id_producto
        WHERE d.id_pedido = :pedido
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':pedido' => $idPedido]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
