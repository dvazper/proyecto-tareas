<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Parte de Operario</title>
  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav><a href="{{ url('/tareas') }}">Inicio</a> · <strong>Parte operario</strong></nav>
</header>

<main>
  <h2>Tarea #{{ $tarea->id }} — Parte de operario</h2>

  @if($errors->any())
    <p class="msg error">Revisa los campos marcados en rojo.</p>
  @elseif(session('ok'))
    <p class="msg ok">{{ session('ok') }}</p>
  @endif

  <section class="card">
    <h3>Datos de la tarea (solo lectura)</h3>
    <div class="grid">
      <p><strong>Contacto:</strong> {{ $tarea->contacto }}</p>
      <p><strong>NIF/CIF:</strong> {{ $tarea->nif }}</p>
      <p><strong>Teléfono:</strong> {{ $tarea->telefono }}</p>
      <p><strong>Email:</strong> {{ $tarea->email }}</p>
      <p><strong>Dirección:</strong> {{ $tarea->direccion }}</p>
      <p><strong>Población:</strong> {{ $tarea->poblacion }}</p>
      <p><strong>CP:</strong> {{ $tarea->cp }}</p>
      <p><strong>Provincia:</strong> {{ $tarea->provincia }}</p>
      <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
      <p><strong>Fecha creación:</strong> {{ $tarea->fecha_creacion }}</p>
      <p><strong>Operario:</strong> {{ $tarea->operario }}</p>
    </div>
  </section>

  <h3>Actualizar estado y anotaciones</h3>
  <form action="{{ url('/tareas/operario/'.$tarea->id) }}" method="post">
    @csrf
    <label>Fecha de realización (dd/mm/aaaa)
      <input type="text" name="fecha" value="{{ old('fecha', $tarea->fecha) }}">
      @error('fecha')<small class="error">{{ $message }}</small>@enderror
    </label>

    <fieldset>
      <legend>Estado de la tarea</legend>
      @php
        $estadoActual = old('estado', $tarea->estado ?? 'R');
        $opciones = ['R'=>'Realizada','C'=>'Cancelada','P'=>'Pendiente','B'=>'Esperando aprobación'];
      @endphp
      @foreach($opciones as $valor => $texto)
        <label style="display:inline-block;margin-right:14px;">
          <input type="radio" name="estado" value="{{ $valor }}" {{ $estadoActual==$valor?'checked':'' }}>
          {{ $valor }} ({{ $texto }})
        </label>
      @endforeach
      @error('estado')<small class="error">{{ $message }}</small>@enderror
    </fieldset>

    <label>Anotaciones posteriores
      <textarea name="anot_post">{{ old('anot_post', $tarea->anot_post) }}</textarea>
      @error('anot_post')<small class="error">{{ $message }}</small>@enderror
    </label>

    <button type="submit">Guardar parte</button>
  </form>
</main>
</body>
</html>
