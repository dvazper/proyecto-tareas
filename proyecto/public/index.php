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
