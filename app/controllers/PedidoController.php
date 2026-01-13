<?php

require_once __DIR__ . '/../models/Pedido.php';

class PedidoController {
    private $pedido;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->pedido = new Pedido($pdo);
    }

    public function ver() {

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (!isset($_GET['id'])) {
            $pedido = $this->pedido->obtenerPedidoAbierto();
            $idPedido = $pedido['id_pedido'] ?? null;
        } else {
            $idPedido = (int) $_GET['id'];
            $pedido = $this->pedido->obtenerPedidoPorId($idPedido);
        }

        if (!$idPedido || !$pedido) {
            // Si es admin, mostrar la vista de pedidos vacía (sin redirigir
            // a admin) para evitar repetir la vista de inicio.
            if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
                $pedido = [
                    'id_pedido' => null,
                    'fecha_pedido' => date('Y-m-d'),
                    'estado' => 'pendiente',
                    'total' => 0
                ];
                $detalle = [];
            } else {
                $_SESSION['mensaje'] = "No hay pedidos activos.";
                header('Location: index.php?page=admin');
                exit;
            }
        } else {
            $detalle = $this->pedido->obtenerDetalle($idPedido);
        }

        require __DIR__ . '/../views/pedidos/ver.php';
    }

    public function editarDetalle() {

        if ($_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=pedidos');
            exit;
        }

        $idDetalle = $_POST['id_detalle'];
        $idPedido  = $_POST['id_pedido'];
        $cantidad  = $_POST['cantidad'];

        require_once __DIR__ . '/../models/DetallePedido.php';
        $detalle = new DetallePedido($this->pdo);
        $detalle->actualizar($idDetalle, $cantidad);

        $this->pedido->actualizarTotal($idPedido);

        header("Location: index.php?page=pedidos&action=ver&id=$idPedido");
        exit;
    }

    public function eliminarDetalle() {

        if ($_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=pedidos');
            exit;
        }

        $idDetalle = $_GET['id'] ?? null;
        $idPedido  = $_GET['pedido'] ?? null;

        if (!$idDetalle || !$idPedido) {
            header('Location: index.php?page=pedidos');
            exit;
        }

        require_once __DIR__ . '/../models/DetallePedido.php';
        $detalle = new DetallePedido($this->pdo);
        $detalle->eliminar($idDetalle);

        $this->pedido->actualizarTotal($idPedido);

        header("Location: index.php?page=pedidos&action=ver&id=$idPedido");
        exit;
    }

    public function confirmar() {

        if ($_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=pedidos');
            exit;
        }

        $idPedido = $_GET['id'];

        $detalle = $this->pedido->obtenerDetalle($idPedido);

        require_once __DIR__ . '/../models/Producto.php';
        $producto = new Producto($this->pdo);

        foreach ($detalle as $d) {
            $producto->sumarStock($d['id_producto'], $d['cantidad']);
        }

        $this->pedido->cambiarEstado($idPedido, 'confirmado');

        // Mensaje para feedback
        $_SESSION['mensaje'] = "Pedido confirmado y stock actualizado.";

        // Si el usuario es admin, redirigir al dashboard para que la
        // lista de productos con stock bajo se actualice. Si no, volver
        // a la vista del pedido.
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
            header('Location: index.php?page=admin');
            exit;
        } else {
            header("Location: index.php?page=pedidos&action=ver&id=$idPedido");
            exit;
        }
    }

    // Generar PDF del pedido
    public function pdf() {

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=pedidos');
            exit;
        }

        $idPedido = (int) $_GET['id'];
        $pedido = $this->pedido->obtenerPedidoPorId($idPedido);

        if (!$pedido) {
            header('Location: index.php?page=pedidos');
            exit;
        }

        $detalle = $this->pedido->obtenerDetalle($idPedido);

        // Intentar usar Dompdf si está disponible
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';

            // Si la clase Dompdf no está disponible, mostrar la vista HTML
            if (!class_exists('Dompdf\\Dompdf')) {
                require __DIR__ . '/../views/pedidos/pdf.php';
                exit;
            }

            try {
                $html = '';
                // Cargar plantilla HTML para el PDF
                ob_start();
                require __DIR__ . '/../views/pedidos/pdf.php';
                $html = ob_get_clean();

                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                $filename = 'pedido_' . $idPedido . '.pdf';
                $dompdf->stream($filename, ['Attachment' => 1]);
                exit;

            } catch (\Exception $e) {
                // si falla Dompdf, redirigir a la vista normal
                header("Location: index.php?page=pedidos&action=ver&id={$idPedido}");
                exit;
            }
        } else {
            // Dompdf no está instalado: mostrar la vista HTML preparada para imprimir
            require __DIR__ . '/../views/pedidos/pdf.php';
            exit;
        }
    }
}
