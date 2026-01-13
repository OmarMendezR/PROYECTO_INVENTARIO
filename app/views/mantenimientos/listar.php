<?php 
require __DIR__ . '/../layouts/header.php';
?>

<div class="productos-panel">

    <h1>Mantenimientos</h1>

    <!-- Barra superior -->
    <div class="acciones-extra">
        <a href="index.php?page=mantenimientos&action=crear" class="btn btn-crear">
            + Nuevo Mantenimiento
        </a>
    </div>

    <!-- Buscador -->
    <form method="GET" action="index.php" class="acciones-superior">
        <input type="hidden" name="page" value="mantenimientos">

        <input
            type="text"
            name="buscar"
            placeholder="Buscar por cliente..."
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
        >

        <button type="submit" class="btn btn-buscar">Buscar</button>

        <button type="button" class="btn btn-limpiar"
                onclick="window.location.href='index.php?page=mantenimientos'">
            Limpiar
        </button>
    </form>

    <!-- Tabla -->
    <div class="table-responsive">
    <table class="tabla-productos">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Fecha Ingreso</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($mantenimientos as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['nombre_cliente']) ?></td>
                <td>
                    <form method="POST" action="index.php?page=mantenimientos&action=cambiarEstado&id=<?= $m['id_mantenimiento'] ?>">
                        <select name="estado" onchange="this.form.submit()">
                            <option value="en_proceso" <?= $m['estado'] === 'en_proceso' ? 'selected' : '' ?>>En proceso</option>
                            <option value="listo_para_entregar" <?= $m['estado'] === 'listo_para_entregar' ? 'selected' : '' ?>>Listo para entregar</option>
                            <option value="entregado" <?= $m['estado'] === 'entregado' ? 'selected' : '' ?>>Entregado</option>
                        </select>
                    </form>
                </td>
                <td><?= htmlspecialchars($m['creado_at']) ?></td>
                <td>
                    <a href="index.php?page=mantenimientos&action=ver&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-ver">Ver</a>
                    <a href="index.php?page=mantenimientos&action=editar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-editar">Editar</a>
                    <a href="index.php?page=mantenimientos&action=eliminar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-eliminar-table" onclick="return confirm('¿Seguro que deseas eliminar este mantenimiento?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>

</div>
<?php 
require __DIR__ . '/../layouts/footer.php';
?>
