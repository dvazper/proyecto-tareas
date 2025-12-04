@extends('layouts.app')

@section('title', 'Acceso')

@section('content')

<div class="card login-card">
    <h2>Acceso al gestor de tareas</h2>

    @if(!empty($errorLogin))
        <p class="msg error">{{ $errorLogin }}</p>
    @endif

    <form method="post" action="/proyecto-tareas/proyecto/public/login">
        @csrf

        <label>
            Usuario
            <input type="text"
                   name="usuario"
                   value=""
                   autocomplete="user_name">
        </label>

        <label>
            Contraseña
            <input type="password"
                   name="clave"
                   autocomplete="current-password">
        </label>

        {{-- Recordar sesión --}}
        <label style="display:flex; align-items:center; gap:0.5rem; margin-top:0.75rem;">
            <input type="checkbox" name="recordar" value="1">
            Mantener sesión iniciada
        </label>

        <button type="submit" class="button-link" style="margin-top:1rem;">
            Entrar
        </button>
    </form>

    <p style="margin-top:0.8rem;font-size:0.85rem;color:#6b7280;">
        Ejemplos: admin / admin1234, operario1 / ope1234
    </p>
</div>

@endsection
