<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card-usuario">

    <!-- Mensaje de éxito / alerta -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success" style="margin-bottom: 15px; padding: 10px; border-radius: 5px; background-color: #d4edda; color: #155724;">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <h1>Bienvenido, <?= htmlspecialchars($nombre) ?></h1>
    <h2 style="text-align: center;">⚠ Productos con stock bajo</h2>

    <?php if (empty($productosStockBajo)): ?>
        <p style="color: green; font-weight: bold;">
            ✅ No hay productos con stock crítico
        </p>
    <?php else: ?>

    <div class="table-responsive">
    <table class="tabla-productos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock actual</th>
                <th>Stock mínimo</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productosStockBajo as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td style="color:#7d0000;font-weight:bold;">
                        <?= $p['stock'] ?>
                    </td>
                    <td><?= $p['stock_minimo'] ?></td>
                    <td>
                       <a href="index.php?page=admin&action=quitarDeStockBajo&id=<?= $p['id_producto'] ?>"
                           class="btn btn-eliminar-table"
                            onclick="return confirm('¿Quitar este producto del pedido?')">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <br>
    <a href="index.php?page=admin&action=agregarTodoAlPedido"
       class="btn btn-crear"
       onclick="return confirm('¿Agregar todos los productos al pedido?')">
       Agregar todos al pedido
    </a>

    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
