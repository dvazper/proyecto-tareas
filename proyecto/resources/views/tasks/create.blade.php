@extends('layouts.app')

@section('title', 'Nueva tarea')

@section('content')

<a href="/proyecto-tareas/proyecto/public/tasks" class="button-link" style="margin-bottom: 1rem; display:inline-block;">
    ← Volver al listado
</a>

<h2>Nueva tarea</h2>

@if(!empty($erroresValidacion))
    <div class="msg error">
        Revisa los campos marcados.
    </div>
@endif

<form method="post" action="/proyecto-tareas/proyecto/public/tasks">
    @csrf

    {{-- CONTACTO --}}
    @php
        $contactoValor = $datosValidados['contacto'] ?? '';
        $contactoError = $erroresValidacion['contacto'] ?? null;
    @endphp
    <label>Persona de contacto</label>
    <input type="text"
           name="contacto"
           value="{{ $contactoValor }}"
           class="{{ $contactoError ? 'error-input' : '' }}">
    @if($contactoError)
        <p class="msg error">{{ $contactoError }}</p>
    @endif


    {{-- NIF/CIF --}}
    @php
        $nifValor = $datosValidados['nif'] ?? '';
        $nifError = $erroresValidacion['nif'] ?? null;
    @endphp
    <label>NIF/CIF</label>
    <input type="text"
           name="nif"
           value="{{ $nifValor }}"
           class="{{ $nifError ? 'error-input' : '' }}">
    @if($nifError)
        <p class="msg error">{{ $nifError }}</p>
    @endif


    {{-- TELÉFONO --}}
    @php
        $telValor = $datosValidados['telefono'] ?? '';
        $telError = $erroresValidacion['telefono'] ?? null;
    @endphp
    <label>Teléfono</label>
    <input type="text"
           name="telefono"
           value="{{ $telValor }}"
           class="{{ $telError ? 'error-input' : '' }}">
    @if($telError)
        <p class="msg error">{{ $telError }}</p>
    @endif


    {{-- EMAIL --}}
    @php
        $emailValor = $datosValidados['email'] ?? '';
        $emailError = $erroresValidacion['email'] ?? null;
    @endphp
    <label>Email</label>
    <input type="text"
           name="email"
           value="{{ $emailValor }}"
           class="{{ $emailError ? 'error-input' : '' }}">
    @if($emailError)
        <p class="msg error">{{ $emailError }}</p>
    @endif


    {{-- DIRECCIÓN --}}
    <label>Dirección</label>
    <input type="text"
           name="direccion"
           value="{{ $datosValidados['direccion'] ?? '' }}">


    {{-- POBLACIÓN --}}
    <label>Población</label>
    <input type="text"
           name="poblacion"
           value="{{ $datosValidados['poblacion'] ?? '' }}">


    {{-- CÓDIGO POSTAL --}}
    @php
        $cpValor = $datosValidados['cp'] ?? '';
        $cpError = $erroresValidacion['cp'] ?? null;
    @endphp
    <label>Código postal</label>
    <input type="text"
           name="cp"
           value="{{ $cpValor }}"
           class="{{ $cpError ? 'error-input' : '' }}">
    @if($cpError)
        <p class="msg error">{{ $cpError }}</p>
    @endif


    {{-- PROVINCIA --}}
    @php
        $provValor = $datosValidados['provincia'] ?? '';
        $provError = $erroresValidacion['provincia'] ?? null;
    @endphp
    <label>Provincia</label>
    <select name="provincia" class="{{ $provError ? 'error-input' : '' }}">
        <option value="">Seleccione provincia</option>
        @foreach($listaProvincias as $codigo => $nombre)
            <option value="{{ $codigo }}"
                @if($provValor === $codigo) selected @endif>
                {{ $nombre }}
            </option>
        @endforeach
    </select>
    @if($provError)
        <p class="msg error">{{ $provError }}</p>
    @endif


    {{-- FECHA DE REALIZACIÓN --}}
    @php
        $fechaValor = $datosValidados['fecha'] ?? '';
        $fechaError = $erroresValidacion['fecha'] ?? null;
    @endphp
    <label>Fecha de realización (dd/mm/aaaa)</label>
    <input type="text"
           name="fecha"
           value="{{ $fechaValor }}"
           placeholder="dd/mm/aaaa"
           class="{{ $fechaError ? 'error-input' : '' }}">
    @if($fechaError)
        <p class="msg error">{{ $fechaError }}</p>
    @endif


    {{-- DESCRIPCIÓN --}}
    @php
        $descValor = $datosValidados['descripcion'] ?? '';
        $descError = $erroresValidacion['descripcion'] ?? null;
    @endphp
    <label>Descripción</label>
    <textarea name="descripcion"
              class="{{ $descError ? 'error-input' : '' }}">{{ $descValor }}</textarea>
    @if($descError)
        <p class="msg error">{{ $descError }}</p>
    @endif


    {{-- OPERARIO --}}
    <label>Operario encargado</label>
    <input type="text"
           name="operario"
           value="{{ $datosValidados['operario'] ?? '' }}">


    {{-- ANOTACIONES PREVIAS --}}
    <label>Anotaciones previas</label>
    <textarea name="anot_prev">{{ $datosValidados['anot_prev'] ?? '' }}</textarea>


    <button type="submit" class="button-link" style="margin-top: 1rem;">
        Crear tarea
    </button>

</form>

@endsection
