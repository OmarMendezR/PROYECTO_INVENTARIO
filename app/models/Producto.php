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

    /**
     * Obtener página de productos con límite/offset y búsqueda opcional.
     * @param string|null $rol
     * @param int $limit
     * @param int $offset
     * @param string $q
     * @return array
     */
    public function obtenerPagina(?string $rol, int $limit, int $offset, string $q = ''): array {
        $q = trim($q);
        if ($rol === 'admin') {
            $select = "*";
        } else {
            $select = "id_producto, nombre, descripcion, precio_venta, stock, stock_minimo";
        }

        if ($q !== '') {
            $sql = "SELECT $select FROM productos WHERE nombre LIKE :term ORDER BY id_producto DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':term', '%'.$q.'%', PDO::PARAM_STR);
        } else {
            $sql = "SELECT $select FROM productos ORDER BY id_producto DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar productos totales (opcionalmente filtrando por búsqueda).
     */
    public function contarTotal(string $q = ''): int {
        $q = trim($q);
        if ($q !== '') {
            $sql = "SELECT COUNT(*) FROM productos WHERE nombre LIKE :term";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':term', '%'.$q.'%', PDO::PARAM_STR);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        }

        $sql = "SELECT COUNT(*) FROM productos";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
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
        try {
            $sql = "DELETE FROM productos WHERE id_producto = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // Error por clave foránea (producto en uso)
            return false;
        }
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