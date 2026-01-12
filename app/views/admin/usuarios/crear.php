<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="form-panel">
    <h1>Crear usuario</h1>

    <?php if (isset($error)): ?>
        <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form class="form-producto" method="POST" action="index.php?page=usuarios&action=crear">
        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
        </div>

        <div class="campo">
            <label>Correo</label>
            <input type="email" name="correo" value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>" required>
        </div>

        <div class="campo">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div class="campo">
            <label>Rol</label>
            <select name="rol" required>
                <option value="empleado" <?php if(($_POST['rol'] ?? '')==='empleado') echo 'selected'; ?>>Empleado</option>
                <option value="admin" <?php if(($_POST['rol'] ?? '')==='admin') echo 'selected'; ?>>Administrador</option>
            </select>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn btn-guardar">Crear</button>
            <button type="button" onclick="window.location.href='index.php?page=usuarios'" class="btn btn-cancelar">Cancelar</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>