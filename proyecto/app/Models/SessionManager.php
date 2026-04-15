<?php

namespace App\Models;

class SessionManager
{
    private static ?SessionManager $instancia = null;

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getInstancia(): SessionManager
    {
        if (self::$instancia === null) {
            self::$instancia = new SessionManager();
        }
        return self::$instancia;
    }

    public function guardarUsuario(string $nombreUsuario, string $rol, ?int $id = null): void
    {
        $_SESSION['usuario_logueado'] = [
            'id'     => $id,
            'nombre' => $nombreUsuario,
            'rol'    => $rol,
        ];
    }

    public function obtenerUsuario(): ?array
    {
        return $_SESSION['usuario_logueado'] ?? null;
    }

    public function estaLogueado(): bool
    {
        return isset($_SESSION['usuario_logueado']);
    }

    public function esAdmin(): bool
    {
        if (!$this->estaLogueado()) {
            return false;
        }
        return $_SESSION['usuario_logueado']['rol'] === 'admin';
    }

    public function esOperario(): bool
    {
        if (!$this->estaLogueado()) {
            return false;
        }
        return $_SESSION['usuario_logueado']['rol'] === 'operario';
    }

    public function cerrarSesion(): void
{
    // Eliminar los datos de sesión que creamos
    if (isset($_SESSION['usuario_logueado'])) {
        unset($_SESSION['usuario_logueado']);
    }

    // Vaciar la sesión completamente
    $_SESSION = [];

    // Destruir la sesión actual
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    // Eliminar la cookie de sesión (opcional pero profesional)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}

}
