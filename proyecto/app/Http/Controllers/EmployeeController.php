<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;
use App\Models\UserModel;

class EmployeeController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    private function requireLogin(): SessionManager
    {
        $session = SessionManager::getInstancia();
        if (!$session->estaLogueado()) {
            header('Location: /proyecto-tareas/proyecto/public/login');
            exit;
        }
        return $session;
    }

    private function requireAdmin(): SessionManager
    {
        $session = $this->requireLogin();
        if (!$session->esAdmin()) {
            header('Location: /proyecto-tareas/proyecto/public/tasks');
            exit;
        }
        return $session;
    }

    public function index()
    {
        $this->requireAdmin();

        $empleados = $this->userModel->obtenerTodos();
        $mensajeOk = $_SESSION['mensajeOk'] ?? null;
        unset($_SESSION['mensajeOk']);

        return view('employees.index', [
            'empleados' => $empleados,
            'mensajeOk' => $mensajeOk,
        ]);
    }

    public function create()
    {
        $this->requireAdmin();

        return view('employees.create', [
            'datosValidados'    => [],
            'erroresValidacion' => [],
        ]);
    }

    public function store()
    {
        $this->requireAdmin();

        $datosFormulario = [
            'usuario'    => trim($_POST['usuario'] ?? ''),
            'rol'        => trim($_POST['rol'] ?? ''),
            'correo'     => trim($_POST['correo'] ?? ''),
            'fecha_alta' => trim($_POST['fecha_alta'] ?? ''),
            'password'   => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
        ];

        [$datosValidados, $erroresValidacion] = $this->validarEmpleado($datosFormulario);

        if (empty($erroresValidacion)) {
            $datosValidados['password_hash'] = hash('sha256', $datosFormulario['password']);
            $datosValidados['fecha_alta'] = $this->aFechaSQL($datosValidados['fecha_alta']);
            $this->userModel->insertar($datosValidados);

            $_SESSION['mensajeOk'] = 'Empleado creado correctamente.';
            header('Location: /proyecto-tareas/proyecto/public/employees');
            exit;
        }

        return view('employees.create', [
            'datosValidados'    => $datosValidados,
            'erroresValidacion' => $erroresValidacion,
        ]);
    }

    public function edit(int $id)
    {
        $session = $this->requireLogin();
        $empleado = $this->userModel->buscarPorId($id);

        if (!$empleado) {
            $_SESSION['mensajeOk'] = 'Empleado no encontrado.';
            header('Location: /proyecto-tareas/proyecto/public/employees');
            exit;
        }

        if (!$session->esAdmin() && (int)$session->obtenerUsuario()['id'] !== $id) {
            header('Location: /proyecto-tareas/proyecto/public/tasks');
            exit;
        }

        return view('employees.edit', [
            'empleado'          => $empleado,
            'datosValidados'    => $empleado,
            'erroresValidacion' => [],
            'soloContacto'      => !$session->esAdmin(),
        ]);
    }

    public function update(int $id)
    {
        $session = $this->requireLogin();
        $empleado = $this->userModel->buscarPorId($id);

        if (!$empleado) {
            $_SESSION['mensajeOk'] = 'Empleado no encontrado.';
            header('Location: /proyecto-tareas/proyecto/public/employees');
            exit;
        }

        $usuarioActual = $session->obtenerUsuario();
        $puedeEditar = $session->esAdmin() || (int)$usuarioActual['id'] === $id;
        if (!$puedeEditar) {
            header('Location: /proyecto-tareas/proyecto/public/tasks');
            exit;
        }

        $datosFormulario = [
            'usuario'    => trim($_POST['usuario'] ?? $empleado['usuario']),
            'rol'        => trim($_POST['rol'] ?? $empleado['rol']),
            'correo'     => trim($_POST['correo'] ?? $empleado['correo']),
            'fecha_alta' => trim($_POST['fecha_alta'] ?? $empleado['fecha_alta']),
            'password'   => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
        ];

        if ($session->esAdmin()) {
            [$datosValidados, $erroresValidacion] = $this->validarEmpleado($datosFormulario, $id);
        } else {
            [$datosValidados, $erroresValidacion] = $this->validarContactoEmpleado($datosFormulario);
        }

        if (empty($erroresValidacion)) {
            $datosValidados['fecha_alta'] = $this->aFechaSQL($datosValidados['fecha_alta']);

            if ($session->esAdmin()) {
                if (!empty($datosFormulario['password'])) {
                    $datosValidados['password_hash'] = hash('sha256', $datosFormulario['password']);
                }
                $this->userModel->actualizar($id, $datosValidados);
            } else {
                $this->userModel->actualizarContacto($id, $datosValidados['correo'], $datosValidados['fecha_alta']);
            }

            $_SESSION['mensajeOk'] = 'Datos del empleado guardados correctamente.';
            header('Location: /proyecto-tareas/proyecto/public/employees');
            exit;
        }

        return view('employees.edit', [
            'empleado'          => $empleado,
            'datosValidados'    => $datosValidados,
            'erroresValidacion' => $erroresValidacion,
            'soloContacto'      => !$session->esAdmin(),
        ]);
    }

    public function destroy(int $id)
    {
        $this->requireAdmin();

        $this->userModel->eliminar($id);
        $_SESSION['mensajeOk'] = 'Empleado eliminado correctamente.';

        header('Location: /proyecto-tareas/proyecto/public/employees');
        exit;
    }

    private function validarEmpleado(array $datosFormulario, ?int $id = null): array
    {
        $datosValidados = [
            'usuario'    => htmlspecialchars($datosFormulario['usuario'] ?? ''),
            'rol'        => htmlspecialchars($datosFormulario['rol'] ?? ''),
            'correo'     => htmlspecialchars($datosFormulario['correo'] ?? ''),
            'fecha_alta' => htmlspecialchars($datosFormulario['fecha_alta'] ?? ''),
        ];

        $erroresValidacion = [];

        if ($datosValidados['usuario'] === '') {
            $erroresValidacion['usuario'] = 'El nombre de usuario es obligatorio.';
        } elseif ($this->userModel->existeUsuario($datosValidados['usuario'], $id)) {
            $erroresValidacion['usuario'] = 'Ya existe un empleado con ese usuario.';
        }

        if (!in_array($datosValidados['rol'], ['admin', 'operario'], true)) {
            $erroresValidacion['rol'] = 'El rol debe ser admin u operario.';
        }

        if ($datosValidados['correo'] === '') {
            $erroresValidacion['correo'] = 'El correo es obligatorio.';
        } elseif (!filter_var($datosValidados['correo'], FILTER_VALIDATE_EMAIL)) {
            $erroresValidacion['correo'] = 'El correo no es válido.';
        }

        if ($datosValidados['fecha_alta'] === '') {
            $erroresValidacion['fecha_alta'] = 'La fecha de alta es obligatoria.';
        } elseif (!$this->validarFecha($datosValidados['fecha_alta'])) {
            $erroresValidacion['fecha_alta'] = 'La fecha debe tener formato dd/mm/aaaa.';
        }

        if ($id === null) {
            if (empty($datosFormulario['password'])) {
                $erroresValidacion['password'] = 'La contraseña es obligatoria.';
            } elseif ($datosFormulario['password'] !== $datosFormulario['password_confirm']) {
                $erroresValidacion['password_confirm'] = 'Las contraseñas no coinciden.';
            }
        } elseif (!empty($datosFormulario['password']) && $datosFormulario['password'] !== $datosFormulario['password_confirm']) {
            $erroresValidacion['password_confirm'] = 'Las contraseñas no coinciden.';
        }

        return [$datosValidados, $erroresValidacion];
    }

    private function validarContactoEmpleado(array $datosFormulario): array
    {
        $datosValidados = [
            'correo'     => htmlspecialchars($datosFormulario['correo'] ?? ''),
            'fecha_alta' => htmlspecialchars($datosFormulario['fecha_alta'] ?? ''),
            'usuario'    => htmlspecialchars($datosFormulario['usuario'] ?? ''),
            'rol'        => htmlspecialchars($datosFormulario['rol'] ?? ''),
        ];

        $erroresValidacion = [];

        if ($datosValidados['correo'] === '') {
            $erroresValidacion['correo'] = 'El correo es obligatorio.';
        } elseif (!filter_var($datosValidados['correo'], FILTER_VALIDATE_EMAIL)) {
            $erroresValidacion['correo'] = 'El correo no es válido.';
        }

        if ($datosValidados['fecha_alta'] === '') {
            $erroresValidacion['fecha_alta'] = 'La fecha de alta es obligatoria.';
        } elseif (!$this->validarFecha($datosValidados['fecha_alta'])) {
            $erroresValidacion['fecha_alta'] = 'La fecha debe tener formato dd/mm/aaaa.';
        }

        return [$datosValidados, $erroresValidacion];
    }

    private function validarFecha(string $fecha): bool
    {
        $partes = explode('/', $fecha);
        if (count($partes) !== 3) {
            return false;
        }

        [$dia, $mes, $anio] = $partes;
        return ctype_digit($dia) && ctype_digit($mes) && ctype_digit($anio) && checkdate((int)$mes, (int)$dia, (int)$anio);
    }

    private function aFechaSQL(string $fecha): string
    {
        [$dia, $mes, $anio] = explode('/', $fecha);
        return sprintf('%04d-%02d-%02d', (int)$anio, (int)$mes, (int)$dia);
    }
}
