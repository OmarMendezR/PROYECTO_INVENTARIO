<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/DetallePedido.php';
require_once __DIR__ . '/../models/Producto.php';


class AdminController {
    private $pdo;
    private $producto;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->producto = new Producto($pdo);
    }

    public function dashboard() {

        // Seguridad básica
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $nombre = $_SESSION["usuario_nombre"];

        // 🔴 productos con stock bajo
        $productosStockBajo = $this->producto->obtenerConStockBajo();

        require __DIR__ . "/../views/admin/dashboard.php";
    }

    public function logout() {
        header('Location: index.php?page=logout');
        exit;
    }

    /* Metodo para agregar productos al pedido */
    
    public function agregarTodoAlPedido() {
    if ($_SESSION['rol'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    $productoModel = new Producto($this->pdo);
    $pedidoModel = new Pedido($this->pdo);
    $detalleModel = new DetallePedido($this->pdo);

    $productos = $productoModel->obtenerConStockBajo();

    if (!$productos) {
        $_SESSION['mensaje'] = "No hay productos con stock bajo para agregar.";
        header('Location: index.php?page=admin');
        exit;
    }

    $pedido = $pedidoModel->obtenerPedidoAbierto();
    $idPedido = $pedido
        ? $pedido['id_pedido']
        : $pedidoModel->crearPedido();

    $contador = 0; // Contador de productos agregados

    foreach ($productos as $producto) {
        $cantidad = $producto['stock_minimo'] - $producto['stock'];
        if ($cantidad > 0) {
            $detalleModel->agregarProducto(
                $idPedido,
                $producto['id_producto'],
                $cantidad,
                (float) $producto['precio_venta']
            );
            $contador++;
        }
    }

    $pedidoModel->actualizarTotal($idPedido);

    // Mensaje con número de productos agregados
    $_SESSION['mensaje'] = "Se agregaron $contador producto(s) al pedido con éxito.";

    header('Location: index.php?page=admin');
    exit;
    }

    // Metodo para quitar productos de la lista de stock bajo
    public function obtenerConStockBajo(): array {
    $sql = "
        SELECT id_producto, nombre, precio_venta, stock, stock_minimo
        FROM productos
        WHERE stock_minimo IS NOT NULL
          AND stock < stock_minimo
        ORDER BY stock ASC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function quitarDeStockBajo() {
    if ($_SESSION['rol'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    if (!isset($_GET['id'])) {
        $_SESSION['mensaje'] = "No se especificó ningún producto.";
        header('Location: index.php?page=admin');
        exit;
    }

    $idProducto = (int)$_GET['id'];
    $detalleModel = new DetallePedido($this->pdo);

    // Aquí decides cómo “quitar del pedido”
    // Por ejemplo, eliminar el producto del pedido abierto
    $pedidoModel = new Pedido($this->pdo);
    $pedido = $pedidoModel->obtenerPedidoAbierto();

    if ($pedido) {
        $detalleModel->eliminarProducto($pedido['id_pedido'], $idProducto);
        $_SESSION['mensaje'] = "Producto eliminado del pedido correctamente.";
    } else {
        $_SESSION['mensaje'] = "No hay pedido abierto.";
    }

    header('Location: index.php?page=admin');
    exit;
    }

}
