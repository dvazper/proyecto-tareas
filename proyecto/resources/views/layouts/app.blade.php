<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title','Gestor')</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav>
    <a href="{{ route('tasks.index') }}">Inicio</a> ·
    <a href="{{ route('tasks.create') }}">Nueva</a>
  </nav>
  @if(session('ok'))
    <p class="msg ok">{{ session('ok') }}</p>
  @endif
</header>

<main>
  @yield('content')
</main>

</body>
</html>
