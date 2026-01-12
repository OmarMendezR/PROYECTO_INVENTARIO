<?php

class Producto {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos(?string $rol = null): array {
        if ($rol === 'admin') {
            $sql = "SELECT * FROM productos ORDER BY id_producto DESC";
        } else {
            $sql = "SELECT id_producto, nombre, descripcion, precio_venta, stock, stock_minimo
                    FROM productos ORDER BY id_producto DESC";
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT * FROM productos WHERE id_producto = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function buscarPorNombre(string $termino): array {
        $sql = "SELECT * FROM productos WHERE nombre LIKE :term ORDER BY id_producto DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':term' => '%' . $termino . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar(string $nombre, string $descripcion, float $precio_venta, int $stock, ?int $stock_minimo, float $precio_compra): bool {
        $sql = "INSERT INTO productos (nombre, descripcion, precio_venta, stock, stock_minimo, precio_compra)
                VALUES (:nombre, :descripcion, :precio_venta, :stock, :stock_minimo, :precio_compra)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio_venta' => $precio_venta,
            ':stock' => $stock,
            ':stock_minimo' => $stock_minimo,
            ':precio_compra' => $precio_compra
        ]);
    }

    public function actualizar(int $id, string $nombre, string $descripcion, float $precio_venta, int $stock, ?int $stock_minimo, float $precio_compra): bool {
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio_venta = :precio_venta, stock = :stock, stock_minimo = :stock_minimo, precio_compra = :precio_compra
                WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio_venta' => $precio_venta,
            ':stock' => $stock,
            ':stock_minimo' => $stock_minimo,
            ':precio_compra' => $precio_compra
        ]);
    }

    public function eliminar(int $id): bool {
        $sql = "DELETE FROM productos WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function actualizarStock(int $id, int $nuevoStock): bool {
        $sql = "UPDATE productos SET stock = :stock WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':stock'=>$nuevoStock, ':id'=>$id]);
    }
    public function obtenerConStockBajo(): array {
    $sql = "
        SELECT id_producto, nombre, precio_venta, stock, stock_minimo, precio_compra
        FROM productos
        WHERE stock_minimo IS NOT NULL
          AND stock < stock_minimo
        ORDER BY stock ASC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function sumarStock(int $idProducto, int $cantidad): bool {
    $sql = "UPDATE productos SET stock = stock + :cantidad WHERE id_producto = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':cantidad' => $cantidad,
        ':id' => $idProducto
    ]);
    }

}
?>