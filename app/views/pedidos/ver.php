<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="pedido-panel">

    <h1>🧾 Pedido</h1>

    <p>
        <strong>Fecha:</strong> <?= $pedido['fecha_pedido'] ?><br>
        <strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?><br>
        <strong>Total:</strong> $<?= number_format($pedido['total'], 0, ',', '.') ?>
    </p>
    <?php if (isset($pedido['id_pedido']) && $_SESSION['rol'] === 'admin' && $pedido['estado'] === 'pendiente'): ?>
        <a href="index.php?page=pedidos&action=confirmar&id=<?= $pedido['id_pedido'] ?>"
            class="btn btn-confirmar"
            onclick="return confirm('¿Confirmar este pedido?')">
            Agregar al stock
        </a>
    <?php endif; ?>

    <?php if (isset($pedido['id_pedido'])): ?>
    <a href="index.php?page=pedidos&action=pdf&id=<?= $pedido['id_pedido'] ?>" class="btn btn-generar-pdf">
        Generar PDF
    </a>
    <?php endif; ?>
    <hr>

    <div class="table-responsive">
    <table class="tabla-pedido">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio de compra por Unidad</th>
                <th>Subtotal</th>

                <?php if ($_SESSION['rol'] === 'admin'): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
    <?php if (!empty($detalle)): ?>
    <?php foreach ($detalle as $d): ?>
    <tr>
    <?php if ($_SESSION['rol'] === 'admin' && isset($_GET['edit']) && $_GET['edit'] == $d['id_detalle_pedido']): ?>

        <!-- 🟡 MODO EDICIÓN -->
        <form method="POST" action="index.php?page=pedidos&action=editarDetalle">
            <td><?= htmlspecialchars($d['producto']) ?></td>

            <td>
                <input type="number" name="cantidad" value="<?= $d['cantidad'] ?>" min="1" required>
            </td>

            <td>
                <input type="number" name="costo" value="<?= $d['costo_unitario'] ?>" min="0" required>
            </td>

            <td>
                $<?= number_format($d['cantidad'] * $d['costo_unitario'], 0, ',', '.') ?>
            </td>

            <td>
                <input type="hidden" name="id_detalle" value="<?= $d['id_detalle_pedido'] ?>">
                <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">

                <button type="submit" class="btn btn-guardar">Guardar</button>
                <a href="index.php?page=pedidos&action=ver&id=<?= $pedido['id_pedido'] ?>"
                   class="btn btn-cancelar">Cancelar</a>
            </td>
        </form>

    <?php else: ?>

        <!-- 🟢 MODO NORMAL -->
        <td><?= htmlspecialchars($d['producto']) ?></td>
        <td><?= $d['cantidad'] ?></td>
        <td>$<?= number_format($d['costo_unitario'], 0, ',', '.') ?></td>
        <td>
            $<?= number_format($d['cantidad'] * $d['costo_unitario'], 0, ',', '.') ?>
        </td>

        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <td>
                <a href="index.php?page=pedidos&action=ver&id=<?= $pedido['id_pedido'] ?>&edit=<?= $d['id_detalle_pedido'] ?>"
                   class="btn btn-editar">
                   Editar
                </a>

                     <a href="index.php?page=pedidos&action=eliminarDetalle&id=<?= $d['id_detalle_pedido'] ?>&pedido=<?= $pedido['id_pedido'] ?>"
                         class="btn btn-eliminar-table"
                   onclick="return confirm('¿Eliminar producto del pedido?')">
                   Eliminar
                </a>
            </td>
        <?php endif; ?>

    <?php endif; ?>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
        <td colspan="5" style="text-align:center; padding:1rem;">No hay productos en este pedido.</td>
    </tr>
    <?php endif; ?>
    </tbody>
    </table>
    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
