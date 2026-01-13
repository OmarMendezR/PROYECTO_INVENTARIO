<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card-login">
    <h1>Iniciar Sesión</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login" autocomplete="off">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" placeholder="Correo" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" placeholder="Contraseña" required>

        <button type="submit" class="btn btn-ingresar">Ingresar</button>
    </form>

    <p>¿No tienes cuenta? <a href="index.php?page=registro">Regístrate aquí</a></p>
    </div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

