<?php
class Mantenimiento {

    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    // Para empleado: obtener todos sus mantenimientos
    public function obtenerTodosPorEmpleado(int $idEmpleado){
        $sql = "SELECT m.*, c.nombre AS clase
                FROM mantenimientos m
                LEFT JOIN clase_mantenimientos c 
                    ON m.id_clase = c.id_clase
                WHERE m.id_empleado = ?
                ORDER BY m.creado_at DESC";

        $query = $this->pdo->prepare($sql);
        $query->execute([$idEmpleado]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Para empleado: buscar por cliente
    public function buscarPorCliente(int $idEmpleado, string $termino): array {
        $sql = "SELECT m.*, c.nombre AS clase
                FROM mantenimientos m
                LEFT JOIN clase_mantenimientos c ON m.id_clase = c.id_clase
                WHERE m.id_empleado = :idEmpleado
                  AND m.nombre_cliente LIKE :termino
                ORDER BY m.creado_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':idEmpleado' => $idEmpleado,
            ':termino' => '%' . $termino . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Para admin: obtener todos los mantenimientos
    public function obtenerTodos(): array {
        $sql = "SELECT m.*, c.nombre AS clase
                FROM mantenimientos m
                LEFT JOIN clase_mantenimientos c ON m.id_clase = c.id_clase
                ORDER BY m.creado_at DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Para admin: buscar por cliente
    public function buscarPorClienteAdmin(string $termino): array {
        $sql = "SELECT m.*, c.nombre AS clase
                FROM mantenimientos m
                LEFT JOIN clase_mantenimientos c ON m.id_clase = c.id_clase
                WHERE m.nombre_cliente LIKE :termino
                ORDER BY m.creado_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':termino' => '%' . $termino . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================== resto de tus métodos ==============================
    public function crear($idEmpleado, $cliente, $contacto, $idClase, $detalles, $precio, $productos){
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO mantenimientos 
                    (id_empleado, nombre_cliente, contacto_cliente, id_clase, detalles, precio, estado, creado_at)
                    VALUES (?, ?, ?, ?, ?, ?, 'en_proceso', NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idEmpleado, $cliente, $contacto, $idClase, $detalles, $precio]);

            $idMantenimiento = $this->pdo->lastInsertId();

            foreach ($productos as $p) {
                $stmt = $this->pdo->prepare("SELECT stock FROM productos WHERE id_producto = ?");
                $stmt->execute([$p['id_producto']]);
                $stock = $stmt->fetchColumn();

                if ($stock < $p['cantidad']) {
                    $this->pdo->rollBack();
                    return false; 
                }

                $sql2 = "INSERT INTO mantenimiento_productos (id_mantenimiento, id_producto, cantidad, precio_unitario)
                         VALUES (?, ?, ?, ?)";
                $stmt2 = $this->pdo->prepare($sql2);
                $stmt2->execute([$idMantenimiento, $p['id_producto'], $p['cantidad'], $p['precio_unitario']]);

                $stmt3 = $this->pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
                $stmt3->execute([$p['cantidad'], $p['id_producto']]);
            }

            $this->pdo->commit();
            return $idMantenimiento;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function obtenerPorId(int $id){
        $sql = "SELECT m.*, c.nombre AS clase
                FROM mantenimientos m
                LEFT JOIN clase_mantenimientos c 
                    ON m.id_clase = c.id_clase
                WHERE m.id_mantenimiento = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $m = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$m) return false;

        $sql2 = "SELECT mp.*, p.nombre 
                 FROM mantenimiento_productos mp
                 INNER JOIN productos p ON mp.id_producto = p.id_producto
                 WHERE mp.id_mantenimiento = ?";

        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$id]);
        $m['productos'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return $m;
    }

    public function actualizar($id, $cliente, $contacto, $idClase, $detalles, $precio, $productos){
        try {
            $this->pdo->beginTransaction();

            $sql = "UPDATE mantenimientos 
                    SET nombre_cliente = ?, contacto_cliente = ?, id_clase = ?, detalles = ?, precio = ?
                    WHERE id_mantenimiento = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cliente, $contacto, $idClase, $detalles, $precio, $id]);

            $sqlDel = "DELETE FROM mantenimiento_productos WHERE id_mantenimiento = ?";
            $this->pdo->prepare($sqlDel)->execute([$id]);

            foreach ($productos as $p) {
                $sql2 = "INSERT INTO mantenimiento_productos 
                         (id_mantenimiento, id_producto, cantidad, precio_unitario)
                         VALUES (?, ?, ?, ?)";
                $this->pdo->prepare($sql2)->execute([
                    $id, 
                    $p['id_producto'], 
                    $p['cantidad'], 
                    $p['precio_unitario']
                ]);
            }

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function cambiarEstado(int $id, string $estado): bool {
        $estadosValidos = ['en_proceso', 'listo_para_entregar', 'entregado'];
        if (!in_array($estado, $estadosValidos)) {
            return false;
        }

        $sql = "UPDATE mantenimientos SET estado = ? WHERE id_mantenimiento = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }

    public function eliminar(int $id) {
        $sql1 = "DELETE FROM mantenimiento_productos WHERE id_mantenimiento = ?";
        $this->pdo->prepare($sql1)->execute([$id]);

        $sql2 = "DELETE FROM mantenimientos WHERE id_mantenimiento = ?";
        $this->pdo->prepare($sql2)->execute([$id]);

        return true;
    }

    // Opcionales que tenías
    public function obtenerListosEntregar(int $idEmpleado): array {
        $sql = "SELECT * 
                FROM mantenimientos
                WHERE id_empleado = :idEmpleado
                  AND estado = 'listo_para_entregar'
                ORDER BY creado_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idEmpleado' => $idEmpleado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPendientesPorEmpleado(int $idEmpleado): array {
        $sql = "SELECT * 
                FROM mantenimientos
                WHERE id_empleado = :idEmpleado
                  AND estado = 'en_proceso'
                ORDER BY creado_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idEmpleado' => $idEmpleado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
