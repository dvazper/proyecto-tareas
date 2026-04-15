@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<a href="/proyecto-tareas/proyecto/public/clients/create" class="button-link" style="margin-bottom: 1rem; display:inline-block;">+ Nuevo cliente</a>

<h2>Lista de clientes</h2>

@if(!empty($mensajeOk))
    <p class="msg ok">{{ $mensajeOk }}</p>
@endif

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>CIF</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>País</th>
            <th>Moneda</th>
            <th>Cuota</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente['id'] }}</td>
                <td>{{ $cliente['cif'] }}</td>
                <td>{{ $cliente['nombre'] }}</td>
                <td>{{ $cliente['correo'] }}</td>
                <td>{{ $cliente['pais'] }}</td>
                <td>{{ $cliente['moneda'] }}</td>
                <td>{{ number_format($cliente['importe_cuota_mensual'], 2, ',', '.') }}</td>
                <td style="white-space: nowrap;">
                    <a href="/proyecto-tareas/proyecto/public/clients/{{ $cliente['id'] }}/edit" class="button-link">Editar</a>
                    <form method="post" action="/proyecto-tareas/proyecto/public/clients/{{ $cliente['id'] }}/delete" style="display:inline" onsubmit="return confirm('¿Seguro que quieres eliminar este cliente?');">
                        @csrf
                        <button type="submit" class="button-link boton-peligro">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
