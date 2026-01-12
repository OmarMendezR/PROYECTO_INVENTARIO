<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="form-panel">
    <h1>Editar usuario</h1>

    <?php if (isset($error)): ?>
        <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php
        $nombre = htmlspecialchars($_POST['nombre'] ?? $usuario['nombre'] ?? '');
        $correo = htmlspecialchars($_POST['correo'] ?? $usuario['correo'] ?? '');
        $rol = htmlspecialchars($_POST['rol'] ?? $usuario['rol'] ?? 'empleado');
    ?>

    <form class="form-producto" method="POST" action="index.php?page=usuarios&action=editar&id=<?php echo $usuario['id']; ?>">
        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>

        <div class="campo">
            <label>Correo</label>
            <input type="email" name="correo" value="<?php echo $correo; ?>" required>
        </div>

        <div class="campo">
            <label>Nueva contraseña (opcional)</label>
            <input type="password" name="password" placeholder="Dejar vacío para no cambiar">
        </div>

        <div class="campo">
            <label>Rol</label>
            <select name="rol" required>
                <option value="empleado" <?php if($rol==='empleado') echo 'selected'; ?>>Empleado</option>
                <option value="admin" <?php if($rol==='admin') echo 'selected'; ?>>Administrador</option>
            </select>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn btn-guardar">Actualizar</button>
            <button type="button" onclick="window.location.href='index.php?page=usuarios'" class="btn btn-cancelar">Cancelar</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>