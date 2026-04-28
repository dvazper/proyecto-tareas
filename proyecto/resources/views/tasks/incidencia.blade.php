@extends('layouts.app')

@section('title', 'Registrar incidencia')

@section('content')

<a href="/proyecto-tareas/proyecto/public/tasks" class="button-link" style="margin-bottom: 1rem; display:inline-block;">
    ← Volver al listado
</a>

<h2>Registrar incidencia</h2>

@if(!empty($erroresValidacion))
    <div class="msg error">
        Revisa los campos marcados.
    </div>
@endif

<form method="post" action="/proyecto-tareas/proyecto/public/incidencias">
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
    <label>CIF</label>
    <input type="text"
           name="nif"
           value="{{ $nifValor }}"
           class="{{ $nifError ? 'error-input' : '' }}">
    @if($nifError)
        <p class="msg error">{{ $nifError }}</p>
    @endif


    {{-- TELEFONO --}}
    @php
        $telefonoValor = $datosValidados['telefono'] ?? '';
        $telefonoError = $erroresValidacion['telefono'] ?? null;
    @endphp
    <label>Teléfono</label>
    <input type="text"
           name="telefono"
           value="{{ $telefonoValor }}"
           class="{{ $telefonoError ? 'error-input' : '' }}">
    @if($telefonoError)
        <p class="msg error">{{ $telefonoError }}</p>
    @endif


    {{-- EMAIL --}}
    @php
        $emailValor = $datosValidados['email'] ?? '';
        $emailError = $erroresValidacion['email'] ?? null;
    @endphp
    <label>Email</label>
    <input type="email"
           name="email"
           value="{{ $emailValor }}"
           class="{{ $emailError ? 'error-input' : '' }}">
    @if($emailError)
        <p class="msg error">{{ $emailError }}</p>
    @endif


    {{-- DIRECCION --}}
    <label>Dirección</label>
    <input type="text"
           name="direccion"
           value="{{ $datosValidados['direccion'] ?? '' }}">


    {{-- POBLACION --}}
    <label>Población</label>
    <input type="text"
           name="poblacion"
           value="{{ $datosValidados['poblacion'] ?? '' }}">


    {{-- CODIGO POSTAL --}}
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


    {{-- ANOTACIONES PREVIAS --}}
    <label>Anotaciones previas</label>
    <textarea name="anot_prev">{{ $datosValidados['anot_prev'] ?? '' }}</textarea>


    @if(isset($erroresValidacion['cliente']))
        <p class="msg error">{{ $erroresValidacion['cliente'] }}</p>
    @endif

    <button type="submit" class="button-link" style="margin-top: 1rem;">
        Registrar incidencia
    </button>

</form>

@endsection