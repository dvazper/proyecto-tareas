<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    {{-- Enlace al CSS --}}
    <link rel="stylesheet" href="/proyecto-tareas/proyecto/public/css/app.css">

    @php
        use App\Models\SessionManager;
        $session = SessionManager::getInstancia();
        $usuarioActual = $session->obtenerUsuario();
    @endphp
</head>

<body>

<header class="main-header">
    <div class="header-left">
        <h1>Bunglebuild S.L.</h1>
        @if($session->estaLogueado())
            <nav class="main-nav" style="margin-top: 0.75rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                <a href="/proyecto-tareas/proyecto/public/tasks" class="button-link">Tareas</a>

                @if($session->esAdmin())
                    <a href="/proyecto-tareas/proyecto/public/employees" class="button-link">Empleados</a>
                    <a href="/proyecto-tareas/proyecto/public/clients" class="button-link">Clientes</a>
                @elseif(!empty($usuarioActual['id']))
                    <a href="/proyecto-tareas/proyecto/public/employees/{{ $usuarioActual['id'] }}/edit" class="button-link">Mis datos</a>
                @endif
            </nav>
        @endif
    </div>

    <div class="header-right">
        @if($session->estaLogueado())
            <span class="usuario-info">
                {{ $usuarioActual['nombre'] }} ({{ $usuarioActual['rol'] }})
            </span>

            <form method="post"
                  action="/proyecto-tareas/proyecto/public/logout"
                  style="display:inline;">
                @csrf
                <button type="submit" class="button-link">
                    Cerrar sesión
                </button>
            </form>
        @endif
    </div>
</header>

<main class="contenido-principal">
    @yield('content')
</main>

</body>
</html>
