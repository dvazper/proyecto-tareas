@extends('layouts.app')

@section('title', 'Listado de tareas')

@section('content')

<h2>Listado de tareas</h2>

{{-- Mensaje flash (éxito) --}}
@if(isset($mensajeOk) && $mensajeOk)
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
                    {{ \Carbon\Carbon::parse($tarea['fecha_creacion'])->format('d/m/Y') }}
                @endif
            </td>

            {{-- Fecha realización --}}
            <td>
                @if(!empty($tarea['fecha']))
                    {{ \Carbon\Carbon::parse($tarea['fecha'])->format('d/m/Y') }}
                @endif
            </td>

            <td style="white-space: nowrap;">

                {{-- Parte operario --}}
                <a href="{{ route('operario.edit', ['id' => $tarea['id']]) }}"
                   class="button-link">
                    Parte operario
                </a>

                {{-- Botón eliminar --}}
                <form action="{{ route('tasks.destroy', ['id' => $tarea['id']]) }}"
                      method="post"
                      style="display:inline"
                      onsubmit="return confirm('¿Seguro que quieres eliminar esta tarea?');">

                    @csrf

                    <button type="submit" class="button-link boton-peligro">
                        Eliminar
                    </button>

                </form>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
