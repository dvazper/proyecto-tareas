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
