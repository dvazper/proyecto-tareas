@extends('layouts.app')

@section('title', 'Listado de tareas')

@section('content')

{{-- Botón de alta solo para administradores --}}
@if(!empty($esAdmin) && $esAdmin)
    <a href="/proyecto-tareas/proyecto/public/tasks/create"
       class="button-link"
       style="margin-bottom: 1rem; display:inline-block;">
        + Nueva tarea
    </a>
@endif

<h2>Listado de tareas</h2>

{{-- Mensaje de éxito si existe --}}
@if(!empty($mensajeOk))
    <p class="msg ok">{{ $mensajeOk }}</p>
@endif

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

            {{-- Fecha creación --}}
            <td>
                @if(!empty($tarea['fecha_creacion']))
                    {{ date('d/m/Y', strtotime($tarea['fecha_creacion'])) }}
                @else
                    -
                @endif
            </td>

            {{-- Fecha realización --}}
            <td>
                @if(!empty($tarea['fecha']))
                    {{ date('d/m/Y', strtotime($tarea['fecha'])) }}
                @else
                    -
                @endif
            </td>

            <td style="white-space: nowrap;">

                {{-- Parte del operario --}}
                <a href="/proyecto-tareas/proyecto/public/tasks/{{ $tarea['id'] }}/operario"
                   class="button-link">
                    Parte operario
                </a>

                {{-- Solo admin puede eliminar --}}
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
