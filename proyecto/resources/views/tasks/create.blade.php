@extends('layouts.app')
@section('title','Nueva tarea')

@section('content')
<h2>Nueva tarea</h2>

@if ($errors->any())
  <p class="msg error">Revisa los campos marcados.</p>
@endif

<form method="post" action="{{ route('tasks.store') }}" novalidate>
  @csrf

  <label>Contacto
    <input name="contacto" value="{{ old('contacto') }}">
    @error('contacto') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>NIF/CIF
    <input name="nif" value="{{ old('nif') }}">
    @error('nif') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Teléfono
    <input name="telefono" value="{{ old('telefono') }}">
    @error('telefono') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Email
    <input name="email" value="{{ old('email') }}">
    @error('email') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Dirección
    <input name="direccion" value="{{ old('direccion') }}">
  </label>

  <label>Población
    <input name="poblacion" value="{{ old('poblacion') }}">
  </label>

  <label>Código Postal
    <input name="cp" value="{{ old('cp') }}">
    @error('cp') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Provincia
    <select name="provincia">
      <option value="">— Selecciona —</option>
      @foreach($provincias as $codigo=>$nombre)
        <option value="{{ $codigo }}" @selected(old('provincia')==$codigo)>
          {{ $nombre }}
        </option>
      @endforeach
    </select>
    @error('provincia') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Descripción
    <input name="descripcion" value="{{ old('descripcion') }}">
    @error('descripcion') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Fecha de realización (dd/mm/aaaa)
    <input name="fecha" value="{{ old('fecha') }}">
    @error('fecha') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Estado
    @php $est = old('estado','B'); @endphp
    <select name="estado">
      <option value="B" @selected($est==='B')>B (Esperando aprobación)</option>
      <option value="P" @selected($est==='P')>P (Pendiente)</option>
      <option value="R" @selected($est==='R')>R (Realizada)</option>
      <option value="C" @selected($est==='C')>C (Cancelada)</option>
    </select>
    @error('estado') <small class="error">{{ $message }}</small> @enderror
  </label>

  <label>Operario
    <input name="operario" value="{{ old('operario') }}">
  </label>

  <label>Anotaciones previas
    <textarea name="anot_prev">{{ old('anot_prev') }}</textarea>
  </label>

  <label>Anotaciones posteriores
    <textarea name="anot_post">{{ old('anot_post') }}</textarea>
  </label>

  <label>Fecha creación (dd/mm/aaaa)
    <input name="fecha_creacion" value="{{ old('fecha_creacion', now()->format('d/m/Y')) }}">
  </label>

  <button type="submit">Crear</button>
</form>
@endsection