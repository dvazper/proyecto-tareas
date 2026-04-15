@extends('layouts.app')

@section('title', 'Editar empleado')

@section('content')

<h2>Editar empleado</h2>

<form method="post" action="/proyecto-tareas/proyecto/public/employees/{{ $empleado['id'] }}" style="max-width: 600px;">
    @csrf

    @if(!$soloContacto)
        <label>Usuario</label>
        <input type="text" name="usuario" value="{{ $datosValidados['usuario'] ?? '' }}">
        @if(!empty($erroresValidacion['usuario']))
            <p class="msg error">{{ $erroresValidacion['usuario'] }}</p>
        @endif

        <label>Rol</label>
        <select name="rol">
            <option value="">Selecciona rol</option>
            <option value="admin" @if(($datosValidados['rol'] ?? '') === 'admin') selected @endif>Admin</option>
            <option value="operario" @if(($datosValidados['rol'] ?? '') === 'operario') selected @endif>Operario</option>
        </select>
        @if(!empty($erroresValidacion['rol']))
            <p class="msg error">{{ $erroresValidacion['rol'] }}</p>
        @endif
    @endif

    <label>Correo</label>
    <input type="email" name="correo" value="{{ $datosValidados['correo'] ?? '' }}">
    @if(!empty($erroresValidacion['correo']))
        <p class="msg error">{{ $erroresValidacion['correo'] }}</p>
    @endif

    <label>Fecha de alta</label>
    <input type="text" name="fecha_alta" placeholder="dd/mm/aaaa" value="{{ $datosValidados['fecha_alta'] ?? '' }}">
    @if(!empty($erroresValidacion['fecha_alta']))
        <p class="msg error">{{ $erroresValidacion['fecha_alta'] }}</p>
    @endif

    <label>Contraseña (dejar en blanco para no cambiar)</label>
    <input type="password" name="password">
    @if(!empty($erroresValidacion['password']))
        <p class="msg error">{{ $erroresValidacion['password'] }}</p>
    @endif

    <label>Repetir contraseña</label>
    <input type="password" name="password_confirm">
    @if(!empty($erroresValidacion['password_confirm']))
        <p class="msg error">{{ $erroresValidacion['password_confirm'] }}</p>
    @endif

    <button type="submit" class="button-link" style="margin-top: 1rem;">Guardar</button>
</form>

@endsection
