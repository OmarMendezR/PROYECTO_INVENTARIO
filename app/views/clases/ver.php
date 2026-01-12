<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="detalle-panel">

    <h1>Detalle de la Clase</h1>

    <div class="acciones-extra">
        <a href="index.php?page=clases" class="btn btn-volver">Volver</a>
    </div>

    <p><strong>Nombre:</strong> <?= htmlspecialchars($clase['nombre']) ?></p>
    <p><strong>Descripción:</strong><br><?= nl2br(htmlspecialchars($clase['descripcion'] ?? '')) ?></p>
    <p><strong>Precio:</strong> $<?= number_format($clase['precio'] ?? 0, 2, ',', '.') ?></p>
    <p><strong>Tiempo de entrega (días):</strong> <?= htmlspecialchars($clase['tiempo_entrega'] ?? '') ?></p>
    <p><strong>Duración (minutos):</strong> <?= htmlspecialchars($clase['duracion_minutos'] ?? '') ?></p>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
