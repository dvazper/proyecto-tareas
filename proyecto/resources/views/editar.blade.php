<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Editar tarea</title>
  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>
<header>
  <h1>Gestor de tareas</h1>
  <nav><a href="{{ url('/tareas') }}">Inicio</a> · <strong>Editar</strong></nav>
</header>

<main>
  <h2>Editar tarea #{{ $tarea->id }}</h2>

  @if($errors->any())
    <p class="msg error">Revisa los campos marcados en rojo.</p>
  @endif

  <form action="{{ url('/tareas/editar/'.$tarea->id) }}" method="post" novalidate enctype="multipart/form-data">
    @csrf

    <label>Persona de contacto
      <input type="text" name="contacto" value="{{ old('contacto', $tarea->contacto) }}">
      @error('contacto')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>NIF/CIF
      <input type="text" name="nif" value="{{ old('nif', $tarea->nif) }}">
      @error('nif')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Teléfono
      <input type="text" name="telefono" value="{{ old('telefono', $tarea->telefono) }}">
      @error('telefono')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Correo electrónico
      <input type="text" name="email" value="{{ old('email', $tarea->email) }}">
      @error('email')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Dirección
      <input type="text" name="direccion" value="{{ old('direccion', $tarea->direccion) }}">
    </label>

    <label>Población
      <input type="text" name="poblacion" value="{{ old('poblacion', $tarea->poblacion) }}">
    </label>

    <label>Código postal
      <input type="text" name="cp" value="{{ old('cp', $tarea->cp) }}">
      @error('cp')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Provincia
      <select name="provincia">
        <option value="">— Selecciona —</option>
        @foreach($provincias as $id => $nom)
          <option value="{{ $id }}" {{ old('provincia', $tarea->provincia) == $id ? 'selected' : '' }}>{{ $nom }}</option>
        @endforeach
      </select>
      @error('provincia')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Descripción
      <input type="text" name="descripcion" value="{{ old('descripcion', $tarea->descripcion) }}">
      @error('descripcion')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Fecha de realización (dd/mm/aaaa)
      <input type="text" name="fecha" value="{{ old('fecha', $tarea->fecha) }}">
      @error('fecha')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Estado
      <select name="estado">
        @php $est = old('estado', $tarea->estado) ?: 'B'; @endphp
        <option value="B" {{ $est==='B'?'selected':'' }}>B (Esperando aprobación)</option>
        <option value="P" {{ $est==='P'?'selected':'' }}>P (Pendiente)</option>
        <option value="R" {{ $est==='R'?'selected':'' }}>R (Realizada)</option>
        <option value="C" {{ $est==='C'?'selected':'' }}>C (Cancelada)</option>
      </select>
      @error('estado')<small class="error">{{ $message }}</small>@enderror
    </label>

    <label>Operario
      <select name="operario">
        <option value="">— Selecciona —</option>
        <option value="ope1" {{ old('operario', $tarea->operario)==='ope1'?'selected':'' }}>Operario 1</option>
        <option value="ope2" {{ old('operario', $tarea->operario)==='ope2'?'selected':'' }}>Operario 2</option>
      </select>
    </label>

    <label>Anotaciones previas
      <textarea name="anot_prev">{{ old('anot_prev', $tarea->anot_prev) }}</textarea>
    </label>

    <label>Anotaciones posteriores
      <textarea name="anot_post">{{ old('anot_post', $tarea->anot_post) }}</textarea>
    </label>

    <label>Fecha de creación
      <input type="text" name="fecha_creacion" value="{{ old('fecha_creacion', $tarea->fecha_creacion) }}" readonly>
    </label>

    <button type="submit">Actualizar</button>
  </form>
</main>
<footer><small></small></footer>
</body>
</html>
