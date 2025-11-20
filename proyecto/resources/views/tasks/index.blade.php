@extends('layouts.app')
@section('title','Listado de tareas')

@section('content')
<h2>Listado</h2>

<table>
  <thead>
    <tr>
      <th>ID</th><th>Descripción</th><th>Contacto</th><th>Estado</th>
      <th>Fecha creación</th><th>Fecha realización</th><th>Acciones</th>
    </tr>
  </thead>

  <tbody>
    @foreach($tareas as $t)
      <tr>
        <td>{{ $t->id }}</td>
        <td>{{ $t->descripcion }}</td>
        <td>{{ $t->contacto }}</td>
        <td>{{ $t->estado }}</td>

        <td>
          {{ \Illuminate\Support\Carbon::parse($t->fecha_creacion)->format('d/m/Y') }}
        </td>

        <td>
          @if($t->fecha)
            {{ \Illuminate\Support\Carbon::parse($t->fecha)->format('d/m/Y') }}
          @endif
        </td>

        <td>
          <a href="{{ route('operario.edit', $t) }}">Parte operario</a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
