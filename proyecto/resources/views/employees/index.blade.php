@extends('layouts.app')

@section('title', 'Empleados')

@section('content')

<a href="/proyecto-tareas/proyecto/public/employees/create" class="button-link" style="margin-bottom: 1rem; display:inline-block;">+ Nuevo empleado</a>

<h2>Lista de empleados</h2>

@if(!empty($mensajeOk))
    <p class="msg ok">{{ $mensajeOk }}</p>
@endif

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Correo</th>
            <th>Fecha de alta</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($empleados as $empleado)
            <tr>
                <td>{{ $empleado['id'] }}</td>
                <td>{{ $empleado['usuario'] }}</td>
                <td>{{ $empleado['rol'] }}</td>
                <td>{{ $empleado['correo'] ?? '-' }}</td>
                <td>{{ !empty($empleado['fecha_alta']) ? date('d/m/Y', strtotime($empleado['fecha_alta'])) : '-' }}</td>
                <td style="white-space: nowrap;">
                    <a href="/proyecto-tareas/proyecto/public/employees/{{ $empleado['id'] }}/edit" class="button-link">Editar</a>
                    <form method="post" action="/proyecto-tareas/proyecto/public/employees/{{ $empleado['id'] }}/delete" style="display:inline" onsubmit="return confirm('¿Seguro que quieres eliminar este empleado?');">
                        @csrf
                        <button type="submit" class="button-link boton-peligro">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
