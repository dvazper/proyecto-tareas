<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;
use App\Models\UserModel;

class AuthController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function mostrarFormularioLogin()
    {
        $session = SessionManager::getInstancia();

        // Si ya hay sesión iniciada, lo mando directamente al listado de tareas
        if ($session->estaLogueado()) {
            header('Location: /proyecto-tareas/proyecto/public/tasks');
            exit;
        }

        // AUTO-LOGIN POR COOKIE (recordar sesión)
        if (isset($_COOKIE['usuarioRecordado'])) {
            $nombreUsuarioCookie = trim($_COOKIE['usuarioRecordado']);

            if ($nombreUsuarioCookie !== '') {
                $usuarioBd = $this->userModel->buscarPorNombreUsuario($nombreUsuarioCookie);

                if ($usuarioBd !== null) {
                    // Usuario válido => creamos sesión directamente
                    $session->guardarUsuario($usuarioBd['usuario'], $usuarioBd['rol']);

                    header('Location: /proyecto-tareas/proyecto/public/tasks');
                    exit;
                } else {
                    // La cookie apunta a un usuario que ya no existe -> la limpiamos
                    setcookie('usuarioRecordado', '', time() - 3600, "/");
                }
            }
        }

        // Si no hay sesión ni cookie válida, mostramos el formulario normal
        return view('auth.login', [
            'errorLogin' => null,
        ]);
    }

    public function procesarLogin()
    {
        $nombreUsuarioFormulario = trim($_POST['usuario'] ?? '');
        $claveFormulario         = $_POST['clave'] ?? '';

        if ($nombreUsuarioFormulario === '' || $claveFormulario === '') {
            return view('auth.login', [
                'errorLogin' => 'Introduce usuario y contraseña.',
            ]);
        }

        $usuarioBd = $this->userModel->buscarPorNombreUsuario($nombreUsuarioFormulario);
        if ($usuarioBd === null) {
            return view('auth.login', [
                'errorLogin' => 'Usuario o clave incorrectos.',
            ]);
        }

        $hashIntroducido = hash('sha256', $claveFormulario);
        if ($hashIntroducido !== $usuarioBd['password_hash']) {
            return view('auth.login', [
                'errorLogin' => 'Usuario o clave incorrectos.',
            ]);
        }

        // Login correcto -> guardamos sesión
        $session = SessionManager::getInstancia();
        $session->guardarUsuario($usuarioBd['usuario'], $usuarioBd['rol'], $usuarioBd['id'] ?? null);

        // ¿Ha marcado "recordar sesión"?
        if (!empty($_POST['recordar'])) {
            // Guardamos el usuario en una cookie para 30 días
            setcookie(
                'usuarioRecordado',
                $usuarioBd['usuario'],
                time() + (86400 * 30),   // 30 días
                "/"
            );
        } else {
            // Si no lo marca, limpiamos cookie si existía
            if (isset($_COOKIE['usuarioRecordado'])) {
                setcookie('usuarioRecordado', '', time() - 3600, "/");
            }
        }

        header('Location: /proyecto-tareas/proyecto/public/tasks');
        exit;
    }

    public function logout()
    {
        $session = SessionManager::getInstancia();
        $session->cerrarSesion();

        // Al cerrar sesión también borramos la cookie de recordar
        if (isset($_COOKIE['usuarioRecordado'])) {
            setcookie('usuarioRecordado', '', time() - 3600, "/");
        }

        header('Location: /proyecto-tareas/proyecto/public/login');
        exit;
    }
}
