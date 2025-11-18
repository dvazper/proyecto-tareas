<?php
require __DIR__ . '/../data/seed.php';

$encontrada = null;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    foreach ($tareas as $t) {
        if ((int)$t['id'] === $id) { $encontrada = $t; break; }
    }
}

$estado = '';
if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] === 'si') { $estado = 'ok'; }
    if ($_POST['confirm'] === 'no') { $estado = 'cancel'; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Borrar tarea</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav><a href="index.php">Inicio</a> · <strong>Borrar</strong></nav>
</header>
<main>
  <h2>Confirmación de borrado</h2>

  <?php if ($estado === 'ok'): ?>
    <p class="msg ok">Borrado confirmado (simulación sin BD).</p>
    <p><a href="index.php">Volver al inicio</a></p>
    <?php exit; ?>
  <?php elseif ($estado === 'cancel'): ?>
    <p class="msg">Operación cancelada.</p>
    <p><a href="index.php">Volver al inicio</a></p>
    <?php exit; ?>
  <?php endif; ?>

  <?php if (!$encontrada): ?>
    <p class="msg error">No existe la tarea indicada.</p>
    <p><a href="index.php">Volver</a></p>
  <?php else: ?>
    <p>Vas a borrar la tarea <strong>#<?= htmlspecialchars($encontrada['id']) ?></strong>:
      <em><?= htmlspecialchars($encontrada['descripcion']) ?></em></p>
    <form action="" method="post">
      <input type="hidden" name="id" value="<?= htmlspecialchars($encontrada['id']) ?>">
      <button name="confirm" value="si" type="submit" class="danger">Sí, borrar</button>
      <button name="confirm" value="no" type="submit">No, volver</button>
    </form>
  <?php endif; ?>
</main>
</body>
</html>
