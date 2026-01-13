<?php require_once 'app/views/layouts/header.php'; ?>
  
<h1>Mantenimientos</h1>
  <p><a href="index.php?page=mantenimientos&action=crear" class="btn btn-crear">Registrar mantenimiento</a></p>
  <table border="1">
  <tr><th>ID</th><th>Cliente</th><th>Clase</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr>
  <?php foreach($mantenimientos as $m): ?>
  <tr>
    <td><?php echo $m['id_mantenimiento']; ?></td>
    <td><?php echo htmlspecialchars($m['nombre_cliente']); ?></td>
    <td><?php echo htmlspecialchars($m['id_clase']); ?></td>
    <td><?php echo $m['precio']; ?></td>
    <td><?php echo $m['estado']; ?></td>
    <td><a href="index.php?page=mantenimientos&action=ver&id=<?php echo $m['id_mantenimiento']; ?>" class="btn btn-ver">Ver</a></td>
  </tr>
    <?php endforeach; ?>
  </table>

<?php require_once 'app/views/layouts/footer.php'; ?>