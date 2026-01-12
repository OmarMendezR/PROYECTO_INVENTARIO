<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card-register">
    <h1>Regístrarse</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=registro">
        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" placeholder="Correo" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" placeholder="Contraseña" required>

        <label for="rol">Rol:</label>
        <select name="rol" id="rol">
            <option value="empleado">Empleado</option>
            <option value="admin">Administrador</option>
        </select>
        
        <button type="submit">Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="index.php?page=login">Inicia sesión aquí</a></p>
    </div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>