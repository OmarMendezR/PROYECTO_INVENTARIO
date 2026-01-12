<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$rol = $_SESSION['rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Taller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Proyecto/public/estilos.css">
</head>
<body>

<header class="header">
    <div class="header-bar">
        <h2 class="logo">Sistema Taller</h2>

        <!-- BOTÓN -->
        <button class="menu-btn" id="menuBtn" onclick="menuFuncion()">☰</button>
    </div>

    <!-- MENÚ -->
    <nav class="menu" id="menu">
        <ul class="menu-panel">
            <?php if ($rol === 'admin'): ?>
                <li><a href="index.php?page=admin">Inicio</a></li>
                <li><a href="index.php?page=productos">Productos</a></li>
                <li><a href="index.php?page=usuarios">Usuarios</a></li>
                <li><a href="index.php?page=mantenimientos">Mantenimientos</a></li>
                <li><a href="index.php?page=pedidos&action=ver">Pedidos</a></li>
            <?php elseif ($rol === 'empleado'): ?>
                <li><a href="index.php?page=empleado">Inicio</a></li>
                <li><a href="index.php?page=productos">Inventario / Productos</a></li>
                <li><a href="index.php?page=mantenimientos">Mantenimientos</a></li>
                <li><a href="index.php?page=clases">Clases</a></li>
            <?php endif; ?>
                <?php if ($rol): ?>
                    <li style="margin-left: auto;"><a href="index.php?page=logout">Cerrar sesión</a></li>
                <?php endif; ?>
        </ul>
    </nav>
</header>

    <main class="main-content">
