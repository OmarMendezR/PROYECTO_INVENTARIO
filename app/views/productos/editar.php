<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="form-panel">

    <h1>Editar producto</h1>

    <?php if (isset($error)): ?>
        <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php
        $nombre = htmlspecialchars($producto['nombre'] ?? '');
        $descripcion = htmlspecialchars($producto['descripcion'] ?? '');
        $precio_venta = htmlspecialchars($producto['precio_venta'] ?? '');
        $precio_compra = htmlspecialchars($producto['precio_compra'] ?? '');
        $stock = htmlspecialchars($producto['stock'] ?? '');
        $stock_minimo = htmlspecialchars($producto['stock_minimo'] ?? '');
    ?>

    <form method="POST"
          action="index.php?page=productos&action=editar&id=<?php echo $producto['id_producto']; ?>"
          class="form-producto">

        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>

        <div class="campo">
            <label>Descripción</label>
            <textarea name="descripcion"><?php echo $descripcion; ?></textarea>
        </div>

        <div class="campo">
            <label>Precio de venta</label>
            <input type="number" step="0.01" name="precio_venta" value="<?php echo $precio_venta; ?>" required>
        </div>

        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <div class="campo">
                <label>Precio de compra</label>
                <input type="number" step="0.01" name="precio_compra"
                    value="<?php
                            echo htmlspecialchars(
                                $_POST['precio_compra'] ?? $producto['precio_compra'] ?? ''
                            );
                    ?>"
                    required>
            </div>
        <?php endif; ?>


        <div class="campo">
            <label>Stock</label>
            <input type="number" name="stock" value="<?php echo $stock; ?>" required>
        </div>

        <div class="campo">
            <label>Stock mínimo</label>
            <input type="number" name="stock_minimo" value="<?php echo $stock_minimo; ?>">
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn btn-guardar">Actualizar</button>
            <button type="button" class="btn btn-cancelar"
                    onclick="window.location.href='index.php?page=productos'">
                Cancelar
            </button>
        </div>

    </form>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
