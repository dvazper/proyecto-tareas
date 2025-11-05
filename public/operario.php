<?php
require __DIR__ . '/../app/validate.php';
require __DIR__ . '/../data/seed.php';

$tareaEncontrada = null;
if (isset($_GET['id'])) {
    $idTarea = (int) $_GET['id'];
    foreach ($tareas as $fila) {
        if ((int)$fila['id'] === $idTarea) { $tareaEncontrada = $fila; break; }
    }
}
if (!$tareaEncontrada) {
    http_response_code(404);
    echo 'No existe la tarea indicada. <a href="index.php">Volver</a>';
    exit;
}

$datosValidados = $tareaEncontrada;
$erroresValidacion = [];
$estadoPorDefecto = 'R';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paqueteOperario = $tareaEncontrada;
    if (isset($_POST['estado']))      $paqueteOperario['estado']      = $_POST['estado'];
    if (isset($_POST['anot_post']))   $paqueteOperario['anot_post']   = $_POST['anot_post'];
    if (isset($_POST['fecha']))       $paqueteOperario['fecha']       = $_POST['fecha'];
    if (isset($_POST['fecha_creacion'])) $paqueteOperario['fecha_creacion'] = $_POST['fecha_creacion'];

    [$datosValidados, $erroresValidacion] = validarTarea($paqueteOperario);
}

function valorCampo($clave, $array) { return isset($array[$clave]) ? $array[$clave] : ''; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Parte de Operario</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav><a href="index.php">Inicio</a> · <strong>Parte operario</strong></nav>
</header>

<main>
  <h2>Tarea #<?= htmlspecialchars($tareaEncontrada['id']) ?> — Parte de operario</h2>

  <?php if ($erroresValidacion): ?>
    <p class="msg error">Revisa los campos marcados en rojo.</p>
  <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p class="msg ok">Validación OK (simulación sin BD).</p>
  <?php endif; ?>

  <section class="card">
    <h3>Datos de la tarea (solo lectura)</h3>
    <div class="grid">
      <p><strong>Contacto:</strong> <?= htmlspecialchars($tareaEncontrada['contacto']) ?></p>
      <p><strong>NIF/CIF:</strong> <?= htmlspecialchars($tareaEncontrada['nif']) ?></p>
      <p><strong>Teléfono:</strong> <?= htmlspecialchars($tareaEncontrada['telefono']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($tareaEncontrada['email']) ?></p>
      <p><strong>Dirección:</strong> <?= htmlspecialchars($tareaEncontrada['direccion']) ?></p>
      <p><strong>Población:</strong> <?= htmlspecialchars($tareaEncontrada['poblacion']) ?></p>
      <p><strong>CP:</strong> <?= htmlspecialchars($tareaEncontrada['cp']) ?></p>
      <p><strong>Provincia:</strong> <?= htmlspecialchars($tareaEncontrada['provincia']) ?></p>
      <p><strong>Descripción:</strong> <?= htmlspecialchars($tareaEncontrada['descripcion']) ?></p>
      <p><strong>Fecha creación:</strong> <?= htmlspecialchars($tareaEncontrada['fecha_creacion']) ?></p>
      <p><strong>Operario:</strong> <?= htmlspecialchars($tareaEncontrada['operario']) ?></p>
    </div>
  </section>

  <h3>Actualizar estado y anotaciones</h3>
  <form action="?id=<?= urlencode($tareaEncontrada['id']) ?>" method="post" novalidate>
    <input type="hidden" name="id" value="<?= htmlspecialchars($tareaEncontrada['id']) ?>">
    <input type="hidden" name="fecha_creacion" value="<?= htmlspecialchars($tareaEncontrada['fecha_creacion']) ?>">

    <label>Fecha de realización (dd/mm/aaaa)
      <input type="text" name="fecha" value="<?= valorCampo('fecha', $datosValidados) ?>">
      <?= isset($erroresValidacion['fecha']) ? '<small class="error">'.$erroresValidacion['fecha'].'</small>' : '' ?>
    </label>

    <fieldset>
      <legend>Estado de la tarea</legend>
      <?php
        $estadoActual = valorCampo('estado', $datosValidados) ?: $estadoPorDefecto; // por defecto 'R' (Realizada)
        $opcionesEstado = ['R' => 'Realizada', 'C' => 'Cancelada', 'P' => 'Pendiente', 'B' => 'Esperando aprobación'];
      ?>
      <?php foreach ($opcionesEstado as $valor => $texto): ?>
        <label style="display:inline-block;margin-right:14px;">
          <input type="radio" name="estado" value="<?= $valor ?>" <?= $estadoActual === $valor ? 'checked' : '' ?>>
          <?= $valor ?> (<?= $texto ?>)
        </label>
      <?php endforeach; ?>
      <?= isset($erroresValidacion['estado']) ? '<small class="error">'.$erroresValidacion['estado'].'</small>' : '' ?>
    </fieldset>

    <label>Anotaciones posteriores
      <textarea name="anot_post"><?= valorCampo('anot_post', $datosValidados) ?></textarea>
    </label>

    <button type="submit">Guardar parte</button>
  </form>
</main>
</body>
</html>
