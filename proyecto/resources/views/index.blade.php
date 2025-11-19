<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio</title>
  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav><strong>Inicio</strong> · <a href="{{ url('/tareas/nueva') }}">Nueva</a></nav>
</header>
<main>
  <h2>Listado de tareas</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Descripción</th><th>Contacto</th><th>Estado</th><th>F. creación</th><th>F. realización</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($tareas as $tarea)
      <tr>
        <td>{{ $tarea->id }}</td>
        <td>{{ $tarea->descripcion }}</td>
        <td>{{ $tarea->contacto }}</td>
        <td>{{ $tarea->estado }}</td>
        <td>{{ $tarea->fecha_creacion }}</td>
        <td>{{ $tarea->fecha }}</td>
        <td>
          <a href="{{ url('/tareas/editar/'.$tarea->id) }}">Editar</a>
          <a class="danger" href="{{ url('/tareas/borrar/'.$tarea->id) }}">Borrar</a>
          <a href="{{ url('/tareas/operario/'.$tarea->id) }}">Parte operario</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</main>
</body>
</html>
