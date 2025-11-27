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
    // Versión simple: siempre muestra el formulario.
    // Si estás logueado y entras aquí, podrías mostrar un mensaje,
    // pero no hace falta redirigir.

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

        $session = SessionManager::getInstancia();
        $session->guardarUsuario($usuarioBd['usuario'], $usuarioBd['rol']);

        header('Location: /proyecto-tareas/proyecto/public/tasks');
        exit;
    }

    public function logout()
{
    $session = SessionManager::getInstancia();
    $session->cerrarSesion();

    header('Location: /proyecto-tareas/proyecto/public/login');
    exit;
}

}
