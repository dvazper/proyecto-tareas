@extends('layouts.app')
@section('title','Nueva tarea')

@section('content')
<h2>Nueva tarea</h2>

@if ($erroresValidacion)
  <p class="msg error">Revisa los campos marcados en rojo.</p>
@endif

<form method="post" action="{{ route('tasks.store') }}" novalidate>
  @csrf

  <label>Persona de contacto
    <input type="text" name="contacto" value="{{ $datosValidados['contacto'] ?? '' }}">
    @if(isset($erroresValidacion['contacto']))
      <small class="error">{{ $erroresValidacion['contacto'] }}</small>
    @endif
  </label>

  <label>NIF/CIF
    <input type="text" name="nif" value="{{ $datosValidados['nif'] ?? '' }}">
    @if(isset($erroresValidacion['nif']))
      <small class="error">{{ $erroresValidacion['nif'] }}</small>
    @endif
  </label>

  <label>Teléfono
    <input type="text" name="telefono" value="{{ $datosValidados['telefono'] ?? '' }}">
    @if(isset($erroresValidacion['telefono']))
      <small class="error">{{ $erroresValidacion['telefono'] }}</small>
    @endif
  </label>

  <label>Correo electrónico
    <input type="text" name="email" value="{{ $datosValidados['email'] ?? '' }}">
    @if(isset($erroresValidacion['email']))
      <small class="error">{{ $erroresValidacion['email'] }}</small>
    @endif
  </label>

  <label>Dirección
    <input type="text" name="direccion" value="{{ $datosValidados['direccion'] ?? '' }}">
  </label>

  <label>Población
    <input type="text" name="poblacion" value="{{ $datosValidados['poblacion'] ?? '' }}">
  </label>

  <label>Código postal
    <input type="text" name="cp" value="{{ $datosValidados['cp'] ?? '' }}">
    @if(isset($erroresValidacion['cp']))
      <small class="error">{{ $erroresValidacion['cp'] }}</small>
    @endif
  </label>

  <label>Provincia
    <select name="provincia">
      <option value="">— Selecciona —</option>
      @foreach($listaProvincias as $codigo => $nombre)
        <option value="{{ $codigo }}" @if(($datosValidados['provincia'] ?? '') === $codigo) selected @endif>
          {{ $nombre }}
        </option>
      @endforeach
    </select>
    @if(isset($erroresValidacion['provincia']))
      <small class="error">{{ $erroresValidacion['provincia'] }}</small>
    @endif
  </label>

  <label>Descripción
    <input type="text" name="descripcion" value="{{ $datosValidados['descripcion'] ?? '' }}">
    @if(isset($erroresValidacion['descripcion']))
      <small class="error">{{ $erroresValidacion['descripcion'] }}</small>
    @endif
  </label>

  <label>Fecha de realización (dd/mm/aaaa)
    <input type="text" name="fecha" value="{{ $datosValidados['fecha'] ?? '' }}">
    @if(isset($erroresValidacion['fecha']))
      <small class="error">{{ $erroresValidacion['fecha'] }}</small>
    @endif
  </label>

  <label>Estado
    @php $estadoActual = $datosValidados['estado'] ?? 'B'; @endphp
    <select name="estado">
      <option value="B" @if($estadoActual==='B') selected @endif>B (Esperando aprobación)</option>
      <option value="P" @if($estadoActual==='P') selected @endif>P (Pendiente)</option>
      <option value="R" @if($estadoActual==='R') selected @endif>R (Realizada)</option>
      <option value="C" @if($estadoActual==='C') selected @endif>C (Cancelada)</option>
    </select>
    @if(isset($erroresValidacion['estado']))
      <small class="error">{{ $erroresValidacion['estado'] }}</small>
    @endif
  </label>

  <label>Operario
    <input type="text" name="operario" value="{{ $datosValidados['operario'] ?? '' }}">
  </label>

  <label>Anotaciones previas
    <textarea name="anot_prev">{{ $datosValidados['anot_prev'] ?? '' }}</textarea>
  </label>

  <label>Anotaciones posteriores
    <textarea name="anot_post">{{ $datosValidados['anot_post'] ?? '' }}</textarea>
  </label>

  <label>Fecha de creación (dd/mm/aaaa)
    <input type="text" name="fecha_creacion" value="{{ $datosValidados['fecha_creacion'] ?? date('d/m/Y') }}">
  </label>

  <button type="submit">Crear</button>
</form>
@endsection
