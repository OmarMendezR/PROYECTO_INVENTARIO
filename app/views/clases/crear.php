<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="form-panel">

    <h1>Crear Clase de Mantenimiento</h1>

    <?php if (!empty($error)): ?>
        <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-producto">

        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="campo">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3"></textarea>
        </div>

        <div class="campo">
            <label>Precio</label>
            <input type="number" name="precio" step="0.01" required>
        </div>

        <div class="campo">
            <label>Tiempo estimado (días)</label>
            <input type="number" name="tiempo_entrega" required>
        </div>

        <div class="campo">
            <label>Tiempo estimado (Minutos)</label>
            <input type="number" name="duracion_minutos" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn btn-guardar">Guardar</button>

            <button type="button" class="btn btn-cancelar"
                    onclick="window.location.href='index.php?page=clases'">
                Cancelar
            </button>
        </div>

    </form>
        <br>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

