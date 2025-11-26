@extends('layouts.app')
@section('title','Parte de operario')

@section('content')
<h2>Parte de operario — Tarea #{{ $tarea['id'] }}</h2>

@if ($erroresValidacion)
  <p class="msg error">Revisa los campos marcados.</p>
@endif

<section class="card">
  <h3>Datos (solo lectura)</h3>
  <ul>
    <li><strong>Contacto:</strong> {{ $tarea['contacto'] }}</li>
    <li><strong>Descripción:</strong> {{ $tarea['descripcion'] }}</li>
    <li><strong>Teléfono:</strong> {{ $tarea['telefono'] }}</li>
    <li><strong>Email:</strong> {{ $tarea['email'] }}</li>
    <li><strong>Provincia / CP:</strong> {{ $tarea['provincia'] }} / {{ $tarea['cp'] }}</li>
    <li><strong>Fecha creación:</strong>
      @if(!empty($tarea['fecha_creacion']))
        {{ \Illuminate\Support\Carbon::parse($tarea['fecha_creacion'])->format('d/m/Y') }}
      @endif
    </li>
  </ul>
</section>

<form method="post" action="{{ route('operario.update', ['id' => $tarea['id']]) }}" novalidate>
  @csrf

  <input type="hidden" name="fecha_creacion" value="{{ $datosValidados['fecha_creacion'] ?? '' }}">

  <label>Fecha de realización (dd/mm/aaaa)
    <input type="text" name="fecha" value="{{ $datosValidados['fecha'] ?? '' }}">
    @if(isset($erroresValidacion['fecha']))
      <small class="error">{{ $erroresValidacion['fecha'] }}</small>
    @endif
  </label>

 <fieldset>
  <legend>Estado</legend>
  @php $estado = $datosValidados['estado'] ?? $tarea['estado'] ?? 'R'; @endphp

  <div class="grupo-estado-radios">
    @foreach(['R'=>'Realizada','C'=>'Cancelada','P'=>'Pendiente','B'=>'Esperando aprobación'] as $val => $txt)
      <label>
        <input type="radio" name="estado" value="{{ $val }}" @if($estado === $val) checked @endif>
        <span>{{ $val }} ({{ $txt }})</span>
      </label>
    @endforeach
  </div>

  @if(isset($erroresValidacion['estado']))
    <small class="error">{{ $erroresValidacion['estado'] }}</small>
  @endif
</fieldset>


  <label>Anotaciones posteriores
    <textarea name="anot_post">{{ $datosValidados['anot_post'] ?? $tarea['anot_post'] }}</textarea>
  </label>

  <button type="submit">Guardar parte</button>
</form>
@endsection
