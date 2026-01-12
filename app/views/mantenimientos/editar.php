<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="form-panel mantenimiento-panel">

    <h1>Editar Mantenimiento</h1>

    <?php if (!empty($error)): ?>
        <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-producto" id="form-mantenimiento">

        <div class="campo">
            <label>Nombre del cliente</label>
            <input type="text" name="cliente"
                   value="<?= htmlspecialchars($m['nombre_cliente']) ?>" required>
        </div>

        <div class="campo">
            <label>Contacto</label>
            <input type="text" name="contacto"
                   value="<?= htmlspecialchars($m['contacto_cliente']) ?>" required>
        </div>

        <div class="campo">
            <label>Clase de mantenimiento</label>
            <select name="id_clase" id="id_clase" required>
                <?php foreach ($clases as $c): ?>
                    <option value="<?= $c['id_clase'] ?>"
                            data-precio="<?= $c['precio'] ?>"
                        <?= ($c['id_clase'] == $m['id_clase']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label>Detalles</label>
            <textarea name="detalles" rows="4"><?= htmlspecialchars($m['detalles']) ?></textarea>
        </div>

        <hr class="separador">

    <h3>Productos usados (opcional)</h3>

    <div id="productos">

    <?php if (!empty($m['productos'])): ?>

        <?php foreach ($m['productos'] as $p): ?>
            <div class="fila-producto">

                <select name="prod_id[]" class="prod-select">
                    <option value="">-- Producto --</option>
                    <?php foreach ($productosDisponibles as $pd): ?>
                        <option value="<?= $pd['id_producto'] ?>"
                                data-precio="<?= $pd['precio_venta'] ?>"
                            <?= ($pd['id_producto'] == $p['id_producto']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pd['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="number"
                       name="prod_cant[]"
                       value="<?= $p['cantidad'] ?>"
                       min="1">

                <input type="number"
                       step="0.01"
                       name="prod_pu[]"
                       readonly>

                <button type="button" class="btn-eliminar-row" aria-label="Eliminar producto">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        <?php endforeach; ?>

    <?php else: ?>

        <!-- Fila base vacía -->
        <div class="fila-producto">
            <select name="prod_id[]" class="prod-select">
                <option value="">-- Producto --</option>
                <?php foreach ($productosDisponibles as $pd): ?>
                    <option value="<?= $pd['id_producto'] ?>"
                            data-precio="<?= $pd['precio_venta'] ?>">
                        <?= htmlspecialchars($pd['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="prod_cant[]" min="1" value="1">
            <input type="number" step="0.01" name="prod_pu[]" readonly>
            <button type="button" class="btn-eliminar-row" aria-label="Eliminar producto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

    <?php endif; ?>

    </div>

    <button type="button"
            class="btn btn-secundario"
            id="btnAgregarProducto">
        + Agregar producto
    </button>


        <div class="campo">
            <label>Precio total</label>
            <input type="number"
                   step="0.01"
                   name="precio_total"
                   id="precio_total"
                   readonly>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn btn-guardar">Guardar Cambios</button>
        </div>

        <div class="acciones-form">
            <button type="button" class="btn btn-cancelar" onclick="window.location.href='index.php?page=mantenimientos'">Cancelar</button>
        </div>

    </form>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
