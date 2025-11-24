@extends('layouts.app')
@section('title','Listado de tareas')

@section('content')
<h2>Listado</h2>

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
            {{ \Illuminate\Support\Carbon::parse($tarea['fecha_creacion'])->format('d/m/Y') }}
          @endif
        </td>
        <td>
          @if(!empty($tarea['fecha']))
            {{ \Illuminate\Support\Carbon::parse($tarea['fecha'])->format('d/m/Y') }}
          @endif
        </td>
        <td>
          <a href="{{ route('operario.edit', ['id' => $tarea['id']]) }}">Parte operario</a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
