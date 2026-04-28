@extends('layouts.app')

@section('title', 'Listado de tareas')

@section('content')

{{-- BOTÓN DE CREAR TAREA (SOLO ADMIN) --}}
@if(!empty($esAdmin) && $esAdmin)
    <a href="/proyecto-tareas/proyecto/public/tasks/create"
       class="button-link"
       style="margin-bottom: 1rem; display:inline-block;">
        + Nueva tarea
    </a>
@endif

{{-- BOTÓN DE REGISTRAR INCIDENCIA --}}
<a href="/proyecto-tareas/proyecto/public/incidencias/create"
   class="button-link"
   style="margin-bottom: 1rem; display:inline-block; background-color:#10b981;">
    + Registrar incidencia
</a>

<h2>Listado de tareas</h2>

{{-- MENSAJE OK --}}
@if(!empty($mensajeOk))
    <p class="msg ok">{{ $mensajeOk }}</p>
@endif

{{-- FORMULARIO DE FILTRO --}}
<form method="get"
      action="/proyecto-tareas/proyecto/public/tasks"
      style="margin-bottom: 1rem; display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">

    @php
        $estadoActual = $estadoFiltro ?? '';
    @endphp

    <label for="estado" style="font-weight:600;">Filtrar por estado:</label>

    <select name="estado" id="estado">
        <option value="">Todos</option>
        <option value="B" @if($estadoActual === 'B') selected @endif>B - Esperando aprobación</option>
        <option value="P" @if($estadoActual === 'P') selected @endif>P - Pendiente</option>
        <option value="R" @if($estadoActual === 'R') selected @endif>R - Realizada</option>
        <option value="C" @if($estadoActual === 'C') selected @endif>C - Cancelada</option>
    </select>

    <button type="submit" class="button-link">
        Aplicar filtro
    </button>

    <a href="/proyecto-tareas/proyecto/public/tasks"
       class="button-link"
       style="background-color:#6b7280;">
        Limpiar filtros
    </a>
</form>

{{-- TABLA DE TAREAS --}}
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Contacto</th>
            <th>Estado</th>
            <th>Fecha creación</th>
            <th>Fecha realización</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach($tareasRegistradas as $tarea)
        <tr>
            <td>{{ $tarea['id'] }}</td>
            <td>{{ $tarea['descripcion'] }}</td>
            <td>{{ $tarea['contacto'] }}</td>
            <td>{{ $tarea['estado'] }}</td>

            <td>
                @if(!empty($tarea['fecha_creacion']))
                    {{ date('d/m/Y', strtotime($tarea['fecha_creacion'])) }}
                @else
                    -
                @endif
            </td>

            <td>
                @if(!empty($tarea['fecha']))
                    {{ date('d/m/Y', strtotime($tarea['fecha'])) }}
                @else
                    -
                @endif
            </td>

            <td style="white-space: nowrap;">

                {{-- PARTE DE OPERARIO --}}
                <a href="/proyecto-tareas/proyecto/public/tasks/{{ $tarea['id'] }}/operario"
                   class="button-link">
                    Parte operario
                </a>

                {{-- ELIMINAR SOLO ADMIN --}}
                @if(!empty($esAdmin) && $esAdmin)
                    <form method="post"
                          action="/proyecto-tareas/proyecto/public/tasks/{{ $tarea['id'] }}/delete"
                          style="display:inline"
                          onsubmit="return confirm('¿Seguro que quieres eliminar esta tarea?');">

                        @csrf
                        <button type="submit" class="button-link boton-peligro">
                            Eliminar
                        </button>
                    </form>
                @endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
