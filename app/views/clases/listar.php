<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="productos-panel">

    <h1>Clases de Mantenimiento</h1>

    <div class="acciones-extra">
        <a href="index.php?page=clases&action=crear" class="btn btn-crear">
            + Crear nueva clase
        </a>
    </div>

    <?php if (empty($clases)): ?>
        <p class="ayuda-texto">No hay clases registradas.</p>
    <?php else: ?>

        <div class="table-responsive">
        <table class="tabla-productos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($clases as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['nombre']) ?></td>
                    <td>$<?= number_format($c['precio'], 2, ',', '.') ?></td>
                    <td>
                        <a href="index.php?page=clases&action=ver&id=<?= $c['id_clase'] ?>" class="btn btn-ver">Ver</a>
                        <a href="index.php?page=clases&action=editar&id=<?= $c['id_clase'] ?>" class="btn btn-editar">Editar</a>
                        <a href="index.php?page=clases&action=eliminar&id=<?= $c['id_clase'] ?>" class="btn btn-eliminar-table" onclick="return confirm('¿Eliminar esta clase?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

