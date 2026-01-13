<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="productos-panel">
<h1>Gestionar Usuarios</h1>

    <?php if (isset($error)): ?><p style="color:red;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

    <div class="acciones-extra">
        <a href="index.php?page=usuarios&action=crear" class="btn btn-crear">Crear usuario</a>
    </div>

    <div class="table-responsive">
    <table class="tabla-productos">
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?php echo htmlspecialchars($u['nombre']); ?></td>
            <td><?php echo htmlspecialchars($u['correo']); ?></td>
            <td><?php echo htmlspecialchars($u['rol']); ?></td>
            <td>
                <a href="index.php?page=usuarios&action=editar&id=<?php echo $u['id']; ?>" class="btn btn-editar">Editar</a>
                <a href="index.php?page=usuarios&action=eliminar&id=<?php echo $u['id']; ?>" class="btn btn-eliminar-table" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
</div>
<?php require __DIR__ . '/../../layouts/footer.php'; ?>