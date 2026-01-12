<?php
// Plantilla HTML para PDF/impresión del pedido
// Variables disponibles: $pedido, $detalle
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Pedido - <?= date('d/m/Y H:i') ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header-left { text-align: left; }
        .header-right { text-align: right; }
        .header h1 { margin: 0; }
        .info { margin-bottom: 20px; }
        .info p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #0b2c4d; color: #fff; }
        .total { text-align: right; font-weight: bold; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>Pedido</h1>
            <p><?= htmlspecialchars($pedido['fecha_pedido']) ?></p>
        </div>
        <div class="header-right">
            <p><strong>Total:</strong> $<?= number_format($pedido['total'], 0, ',', '.') ?></p>
        </div>
    </div>

    <div class="info">
        <p><strong>Estado:</strong> <?= htmlspecialchars(ucfirst($pedido['estado'])) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Costo unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalle as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['producto']) ?></td>
                    <td><?= htmlspecialchars($d['cantidad']) ?></td>
                    <td>$<?= number_format($d['costo_unitario'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($d['cantidad'] * $d['costo_unitario'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>



</body>
</html>
