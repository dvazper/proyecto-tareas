@extends('layouts.app')

@section('title', 'Parte de operario')

@section('content')

<a href="/proyecto-tareas/proyecto/public/tasks" class="button-link" style="margin-bottom: 1rem; display:inline-block;">
    ← Volver al listado
</a>

<h2>Parte de operario — Tarea #{{ $tarea['id'] }}</h2>

{{-- Mensaje general de error --}}
@if(!empty($erroresValidacion))
    <div class="msg error">
        Revisa los campos marcados.
    </div>
@endif

{{-- INFORMACIÓN DE LA TAREA (solo lectura) --}}
<h3>Datos (solo lectura)</h3>

<ul style="margin-bottom: 1.5rem;">
    <li><strong>Operario asignado:</strong> {{ $tarea['contacto'] }}</li>
    <li><strong>Descripción:</strong> {{ $tarea['descripcion'] }}</li>
    <li><strong>Teléfono:</strong> {{ $tarea['telefono'] }}</li>
    <li><strong>Email:</strong> {{ $tarea['email'] }}</li>
    <li><strong>Provincia / CP:</strong> {{ $tarea['provincia'] }} / {{ $tarea['cp'] }}</li>
    <li><strong>Fecha creación:</strong> 
        {{ date('d/m/Y', strtotime($tarea['fecha_creacion'])) }}
    </li>
</ul>


{{-- PREPARAR FECHA PARA MOSTRARLA EN dd/mm/aaaa --}}
@php
    $fechaFormateada = '';
    if (!empty($datosValidados['fecha'])) {
        // Si viene en SQL (yyyy-mm-dd), la convertimos
        if (str_contains($datosValidados['fecha'], '-')) {
            $p = explode('-', $datosValidados['fecha']); // [yyyy, mm, dd]
            if (count($p) === 3) {
                $fechaFormateada = $p[2] . '/' . $p[1] . '/' . $p[0];
            }
        }
        // Si ya viene en dd/mm/aaaa, la dejamos igual
        elseif (str_contains($datosValidados['fecha'], '/')) {
            $fechaFormateada = $datosValidados['fecha'];
        }
    }
@endphp


<form method="post" action="/proyecto-tareas/proyecto/public/tasks/{{ $tarea['id'] }}/operario">

    @csrf

    {{-- FECHA --}}
    <label>Fecha de realización (dd/mm/aaaa)</label>
    <input type="text" name="fecha" value="{{ $fechaFormateada }}">
    @if(isset($erroresValidacion['fecha']))
        <p class="msg error">{{ $erroresValidacion['fecha'] }}</p>
    @endif


    {{-- ESTADO --}}
    <label style="margin-top: 1rem;">Estado</label>

    <div class="radio-group">

        <label class="radio-item">
            <input type="radio" name="estado" value="R"
                @if(($datosValidados['estado'] ?? '') === 'R') checked @endif
            >
            R (Realizada)
        </label>

        <label class="radio-item">
            <input type="radio" name="estado" value="C"
                @if(($datosValidados['estado'] ?? '') === 'C') checked @endif
            >
            C (Cancelada)
        </label>

        <label class="radio-item">
            <input type="radio" name="estado" value="P"
                @if(($datosValidados['estado'] ?? '') === 'P') checked @endif
            >
            P (Pendiente)
        </label>

        <label class="radio-item">
            <input type="radio" name="estado" value="B"
                @if(($datosValidados['estado'] ?? 'B') === 'B') checked @endif
            >
            B (Esperando aprobación)
        </label>

    </div>

    @if(isset($erroresValidacion['estado']))
        <p class="msg error">{{ $erroresValidacion['estado'] }}</p>
    @endif


    {{-- ANOTACIONES --}}
    <label style="margin-top: 1rem;">Anotaciones posteriores</label>
    <textarea name="anot_post">{{ $datosValidados['anot_post'] ?? '' }}</textarea>


    {{-- BOTÓN GUARDAR --}}
    <button type="submit" class="button-link" style="margin-top: 1rem;">
        Guardar parte
    </button>

</form>

@endsection
