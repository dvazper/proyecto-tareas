<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

## Tenemos dos usuarios, administrador u operario. Con el patrón singleton crearemos una clase session, podremos guardar datos en la sesión o recuperar los usuarios. No tenemos por qué guardar
## el usuario en la base de datos puesto que aún no vamos a seguir con eso, simplemente tenemos que tener clave y usuario y poder validarlos de manera "local" por así decirlo.
## Para validar la clave utilizaremos un hash con la función hash() sha 256. 

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
  <p><a href="views/nueva.php">+ Añadir tarea</a></p>
</main>
</body>
</html>