<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="productos-panel">

    <h1>Productos</h1>

    <form method="GET" action="index.php" class="acciones-superior">
        <input type="hidden" name="page" value="productos">

        <input type="text" name="q" placeholder="Buscar por nombre"
               value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">

        <button type="submit" class="btn btn-buscar">Buscar</button>

        <button type="button" class="btn btn-limpiar"
                onclick="window.location.href='index.php?page=productos'">
            Limpiar
        </button>
    </form>

    <?php if (isset($error)): ?>
        <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <div class="acciones-extra">  
        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <a href="index.php?page=productos&action=crear" class="btn btn-crear">
                + Crear producto
            </a>
        <?php endif; ?>
    </div>

    <table class="tabla-productos">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio de venta</th>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                    <th>Precio de compra</th>
                <?php endif; ?>
                <th>Stock</th>
                <th>Stock mínimo</th>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($productos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre']); ?></td>
                <td><?= htmlspecialchars($p['descripcion']); ?></td>
                <td>$<?= number_format($p['precio_venta'], 0, ',', '.'); ?></td>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                    <td>$<?= number_format($p['precio_compra'], 0, ',', '.'); ?></td>
                <?php endif; ?>
                <td><?= htmlspecialchars($p['stock']); ?></td>
                <td><?= htmlspecialchars($p['stock_minimo']); ?></td>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                <td>
                    <a href="index.php?page=productos&action=editar&id=<?= $p['id_producto']; ?>" class="btn btn-editar">Editar</a>
                    <a href="index.php?page=productos&action=eliminar&id=<?= $p['id_producto']; ?>" class="btn btn-eliminar-table" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


</div>

<!-- Pagination -->
<?php if (!empty($totalPages) && $totalPages > 1): ?>
    <div class="pagination" style="text-align:center; margin:20px 0;">
        <?php
            $params = $_GET;
            for ($i = 1; $i <= $totalPages; $i++):
                $params['p'] = $i;
                $query = http_build_query($params);
        ?>
            <a href="index.php?<?= $query ?>" class="btn btn-pagina" style="margin:2px; <?= $i === $pageNum ? 'opacity:0.7; font-weight:700;' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>