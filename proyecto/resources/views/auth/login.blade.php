@extends('layouts.app')

@section('title', 'Acceso')

@section('content')

<div class="card">
    <h2>Acceso al gestor de tareas</h2>

    @if($errorLogin)
        <p class="msg error">{{ $errorLogin }}</p>
    @endif

    <form method="post" action="/proyecto-tareas/proyecto/public/login">
        @csrf

        <label>
            Usuario
            <input type="text" name="usuario" value="">
        </label>

        <label>
            Contraseña
            <input type="password" name="clave">
        </label>

        <button type="submit">Entrar</button>
    </form>

    <p style="margin-top:0.8rem;font-size:0.85rem;color:#6b7280;">
        Ejemplos: admin / admin1234, operario1 / ope1234
    </p>
</div>

@endsection
