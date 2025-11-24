<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title','Gestor de tareas')</title>

  {{-- Enlazamos el CSS (public/css/app.css) --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="cabecera">
  <div class="cabecera-contenido">
    <div>
      <h1 class="empresa">Bunglebuild S.L.</h1>
      <p class="subtitulo">Gestor de incidencias y tareas</p>
    </div>

    <nav class="menu">
      <a href="{{ route('tasks.index') }}">Listado de tareas</a>
      <a href="{{ route('tasks.create') }}">Nueva tarea</a>
    </nav>
  </div>

  @if(isset($mensajeOk) && $mensajeOk)
    <p class="msg ok">{{ $mensajeOk }}</p>
  @endif
</header>

<main class="contenedor">
  @yield('content')
</main>

<footer class="pie">
  <small>© {{ date('Y') }} Bunglebuild S.L. · Gestor interno</small>
</footer>

</body>
</html>
