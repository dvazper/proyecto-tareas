@extends('layouts.app')
@section('title','Parte de operario')

@section('content')
<h2>Parte de operario — Tarea #{{ $task->id }}</h2>

@if ($errors->any())
  <p class="msg error">Revisa los campos marcados.</p>
@endif

<section class="card">
  <h3>Datos (solo lectura)</h3>
  <ul>
    <li><strong>Contacto:</strong> {{ $task->contacto }}</li>
    <li><strong>Descripción:</strong> {{ $task->descripcion }}</li>
    <li><strong>Teléfono:</strong> {{ $task->telefono }}</li>
    <li><strong>Email:</strong> {{ $task->email }}</li>
    <li><strong>Provincia/CP:</strong> {{ $task->provincia }} / {{ $task->cp }}</li>
    <li><strong>Creación:</strong> {{ \Illuminate\Support\Carbon::parse($task->fecha_creacion)->format('d/m/Y') }}</li>
  </ul>
</section>

<form method="post" action="{{ route('operario.update', $task) }}" novalidate>
  @csrf
  @method('PUT')

  <label>Fecha de realización (dd/mm/aaaa)
    <input type="text" name="fecha" value="{{ old('fecha', \Illuminate\Support\Carbon::parse($task->fecha)->format('d/m/Y')) }}">
    @error('fecha') <small class="error">{{ $message }}</small> @enderror
  </label>

  <fieldset>
    <legend>Estado</legend>
    @php $estado = old('estado', $task->estado ?? 'R'); @endphp
    @foreach(['R'=>'Realizada','C'=>'Cancelada','P'=>'Pendiente','B'=>'Esperando aprobación'] as $val=>$txt)
      <label style="margin-right:15px;">
        <input type="radio" name="estado" value="{{ $val }}" {{ $estado===$val ? 'checked':'' }}>
        {{ $val }} ({{ $txt }})
      </label>
    @endforeach
    @error('estado') <small class="error">{{ $message }}</small> @enderror
  </fieldset>

  <label>Anotaciones posteriores
    <textarea name="anot_post">{{ old('anot_post', $task->anot_post) }}</textarea>
  </label>

  <button type="submit">Guardar parte</button>
</form>
@endsection
