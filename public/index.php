<?php
require __DIR__ . '/../data/seed.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav><strong>Inicio</strong> · <a href="nueva.php">Nueva</a></nav>
</header>
<main>
  <h2>Listado (simulado)</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Descripción</th><th>Contacto</th><th>Estado</th><th>F. creación</th><th>F. realización</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tareas as $tarea): ?>
      <tr>
        <td><?= htmlspecialchars($tarea['id']) ?></td>
        <td><?= htmlspecialchars($tarea['descripcion']) ?></td>
        <td><?= htmlspecialchars($tarea['contacto']) ?></td>
        <td><?= htmlspecialchars($tarea['estado']) ?></td>
        <td><?= htmlspecialchars($tarea['fecha_creacion']) ?></td>
        <td><?= htmlspecialchars($tarea['fecha']) ?></td>
        <td>
          <a href="editar.php?id=<?= urlencode($tarea['id']) ?>">Editar</a>
          <a class="danger" href="borrar.php?id=<?= urlencode($tarea['id']) ?>">Borrar</a>
          <a href="operario.php?id=<?= urlencode($tarea['id']) ?>">Parte operario</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="nueva.php">+ Añadir tarea</a></p>
</main>
</body>
</html>
