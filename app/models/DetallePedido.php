<?php

class DetallePedido {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function agregarProducto(int $idPedido, int $idProducto, int $cantidad): bool {

    // Obtener precio_compra del producto
    $sql = "SELECT precio_compra FROM productos WHERE id_producto = :producto";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':producto' => $idProducto]);
    $precioCompra = $stmt->fetchColumn();

    if (!$precioCompra) {
        $precioCompra = 0; // por seguridad
    }

    // ¿Ya existe el producto en el pedido?
    $check = $this->pdo->prepare(
        "SELECT id_detalle_pedido, cantidad
        FROM detalle_pedido
        WHERE id_pedido = :pedido AND id_producto = :producto"
    );
    $check->execute([
        ':pedido' => $idPedido,
        ':producto' => $idProducto
    ]);

    if ($row = $check->fetch(PDO::FETCH_ASSOC)) {
        // sumar cantidad
        $stmt = $this->pdo->prepare(
            "UPDATE detalle_pedido
            SET cantidad = cantidad + :cantidad
            WHERE id_detalle_pedido = :id"
        );
        return $stmt->execute([
            ':cantidad' => $cantidad,
            ':id' => $row['id_detalle_pedido']
        ]);
    }

    // insertar nuevo
    $stmt = $this->pdo->prepare(
        "INSERT INTO detalle_pedido
        (id_pedido, id_producto, cantidad, costo_unitario)
        VALUES (:pedido, :producto, :cantidad, :costo)"
    );

    return $stmt->execute([
        ':pedido'   => $idPedido,
        ':producto' => $idProducto,
        ':cantidad' => $cantidad,
        ':costo'    => $precioCompra
    ]);
    }


    public function eliminar(int $idDetalle): bool {
    $sql = "DELETE FROM detalle_pedido WHERE id_detalle_pedido = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $idDetalle]);
    }

    public function actualizar(int $idDetalle, int $cantidad): bool {

    // Obtener precio_compra del producto
    $sql = "
        SELECT p.precio_compra
        FROM detalle_pedido d
        JOIN productos p ON p.id_producto = d.id_producto
        WHERE d.id_detalle_pedido = :id
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $idDetalle]);
    $precioCompra = $stmt->fetchColumn();

    if (!$precioCompra) {
        $precioCompra = 0; // seguridad
    }

    // Actualizar cantidad y costo_unitario
    $sql = "
        UPDATE detalle_pedido
        SET cantidad = :cantidad, costo_unitario = :costo
        WHERE id_detalle_pedido = :id
    ";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':cantidad' => $cantidad,
        ':costo' => $precioCompra,
        ':id' => $idDetalle
    ]);
    }


    // NUEVO: Método para eliminar un producto de un pedido
    public function eliminarProducto(int $idPedido, int $idProducto): bool {
        $sql = "DELETE FROM detalle_pedido WHERE id_pedido = :idPedido AND id_producto = :idProducto";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idPedido' => $idPedido,
            ':idProducto' => $idProducto
        ]);
    }
}
