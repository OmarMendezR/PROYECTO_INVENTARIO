<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="detalle-panel">

    <h1>Detalle del Mantenimiento</h1>

    <?php if (!empty($_GET['msg'])): ?>
        <p style="color: green; font-weight: bold;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </p>
    <?php endif; ?>

    <div class="acciones-extra">
        <a href="index.php?page=mantenimientos" class="btn btn-volver">Volver</a>
    </div>

    <p><strong>Cliente:</strong> <?= htmlspecialchars($m['nombre_cliente']) ?></p>
    <p><strong>Contacto:</strong> <?= htmlspecialchars($m['contacto_cliente'] ?? '') ?></p>
    <p><strong>Clase:</strong> <?= htmlspecialchars($m['clase'] ?? 'Sin clase') ?></p>
    <p><strong>Detalles:</strong><br><?= nl2br(htmlspecialchars($m['detalles'] ?? '')) ?></p>
    <p><strong>Precio:</strong> $<?= number_format($m['precio'] ?? 0, 0, ',', '.') ?></p>
    <p><strong>Estado:</strong> <?= htmlspecialchars($m['estado']) ?></p>
    <p><strong>Fecha Ingreso:</strong> <?= htmlspecialchars($m['creado_at']) ?></p>

    <hr>

    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <h3>Acciones administrador</h3>
        <a href="index.php?page=mantenimientos&action=editar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-editar">Editar</a>
        <a href="index.php?page=mantenimientos&action=eliminar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-eliminar-table">Eliminar</a>
    <?php endif; ?>

    <?php if ($_SESSION['rol'] === 'empleado'): ?>
       <h3>Actualizar estado</h3>
        <form method="POST" action="index.php?page=mantenimientos&action=cambiarEstado&id=<?= $m['id_mantenimiento'] ?>">
            <select name="estado">
                <option value="en_proceso" <?= $m['estado'] === 'en_proceso' ? 'selected' : '' ?>>En proceso</option>
                <option value="listo_para_entregar" <?= $m['estado'] === 'listo_para_entregar' ? 'selected' : '' ?>>Listo para entregar</option>
                <option value="entregado" <?= $m['estado'] === 'entregado' ? 'selected' : '' ?>>Entregado</option>
            </select>
            <button type="submit">Guardar</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($m['productos'])): ?>
        <hr>
        <h3>Productos asociados</h3>
        <div class="table-responsive">
        <table class="tabla-productos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($m['productos'] as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['cantidad']) ?></td>
                        <td>$<?= number_format($p['precio_unitario'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
