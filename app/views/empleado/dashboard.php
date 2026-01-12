<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1 style="text-align:center;">Bienvenido, <?= htmlspecialchars($nombre) ?></h1>

<div class="accordion">

    <!-- Pendientes -->
    <div class="accordion-item">
        <button class="accordion-header">
            🛠 Mantenimientos Pendientes (<?= count($mantenimientosPendientes) ?>)
        </button>
        <div class="accordion-content">
            <?php if (empty($mantenimientosPendientes)): ?>
                <p style="text-align:center; color: green;">✅ No hay mantenimientos Pendientes.</p>
            <?php else: ?>
                <div class="table-responsive">
                <table class="tabla-productos">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Detalles</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mantenimientosPendientes as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['nombre_cliente']) ?></td>
                                <td><?= htmlspecialchars($m['detalles']) ?></td>
                                <td><?= htmlspecialchars($m['estado']) ?></td>
                                <td>$<?= number_format($m['precio'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Listos para entregar -->
    <div class="accordion-item">
        <button class="accordion-header">
            🛠 Mantenimientos Listos para Entrega (<?= count($mantenimientosParaEntregar) ?>)
        </button>
        <div class="accordion-content">
            <?php if (empty($mantenimientosParaEntregar)): ?>
                <p style="text-align:center; color: green;">✅ No hay mantenimientos para Entregar.</p>
            <?php else: ?>
                <div class="table-responsive">
                <table class="tabla-productos">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Detalles</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mantenimientosParaEntregar as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['nombre_cliente']) ?></td>
                                <td><?= htmlspecialchars($m['detalles']) ?></td>
                                <td><?= htmlspecialchars($m['estado']) ?></td>
                                <td>$<?= number_format($m['precio'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
