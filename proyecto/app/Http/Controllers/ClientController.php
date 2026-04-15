<?php

namespace App\Http\Controllers;

use App\Models\ClientModel;
use App\Models\SessionManager;

class ClientController extends Controller
{
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    private function requireAdmin(): void
    {
        $session = SessionManager::getInstancia();
        if (!$session->estaLogueado() || !$session->esAdmin()) {
            header('Location: /proyecto-tareas/proyecto/public/tasks');
            exit;
        }
    }

    public function index()
    {
        $this->requireAdmin();

        $clientes = $this->clientModel->obtenerTodos();
        $mensajeOk = $_SESSION['mensajeOk'] ?? null;
        unset($_SESSION['mensajeOk']);

        return view('clients.index', [
            'clientes' => $clientes,
            'mensajeOk' => $mensajeOk,
        ]);
    }

    public function create()
    {
        $this->requireAdmin();

        return view('clients.create', [
            'datosValidados'    => [],
            'erroresValidacion' => [],
            'listaPaises'       => config('paises.lista'),
            'codigosPaises'     => config('telefonos.lista'),
            'monedas'           => ['EUR', 'USD', 'GBP'],
        ]);
    }

    public function store()
    {
        $this->requireAdmin();

        $datosFormulario = [
            'cif'                   => trim($_POST['cif'] ?? ''),
            'nombre'                => trim($_POST['nombre'] ?? ''),
            'codigo_pais'           => trim($_POST['codigo_pais'] ?? ''),
            'telefono'              => trim($_POST['telefono'] ?? ''),
            'correo'                => trim($_POST['correo'] ?? ''),
            'cuenta_corriente'      => trim($_POST['cuenta_corriente'] ?? ''),
            'pais'                  => trim($_POST['pais'] ?? ''),
            'moneda'                => trim($_POST['moneda'] ?? ''),
            'importe_cuota_mensual' => trim($_POST['importe_cuota_mensual'] ?? ''),
        ];

        [$datosValidados, $erroresValidacion] = $this->validarCliente($datosFormulario);

        if (empty($erroresValidacion)) {
            $this->clientModel->insertar($datosValidados);
            $_SESSION['mensajeOk'] = 'Cliente registrado correctamente.';
            header('Location: /proyecto-tareas/proyecto/public/clients');
            exit;
        }

        return view('clients.create', [
            'datosValidados'    => $datosValidados,
            'erroresValidacion' => $erroresValidacion,
            'listaPaises'       => config('paises.lista'),
            'codigosPaises'     => config('telefonos.lista'),
            'monedas'           => ['EUR', 'USD', 'GBP'],
        ]);
    }

    public function edit(int $id)
    {
        $this->requireAdmin();

        $cliente = $this->clientModel->buscarPorId($id);
        if (!$cliente) {
            $_SESSION['mensajeOk'] = 'Cliente no encontrado.';
            header('Location: /proyecto-tareas/proyecto/public/clients');
            exit;
        }

        return view('clients.edit', [
            'cliente'           => $cliente,
            'datosValidados'    => $cliente,
            'erroresValidacion' => [],
            'listaPaises'       => config('paises.lista'),
            'codigosPaises'     => config('telefonos.lista'),
            'monedas'           => ['EUR', 'USD', 'GBP'],
        ]);
    }

    public function update(int $id)
    {
        $this->requireAdmin();

        $cliente = $this->clientModel->buscarPorId($id);
        if (!$cliente) {
            $_SESSION['mensajeOk'] = 'Cliente no encontrado.';
            header('Location: /proyecto-tareas/proyecto/public/clients');
            exit;
        }

        $datosFormulario = [
            'cif'                   => trim($_POST['cif'] ?? ''),
            'nombre'                => trim($_POST['nombre'] ?? ''),
            'codigo_pais'           => trim($_POST['codigo_pais'] ?? ''),
            'telefono'              => trim($_POST['telefono'] ?? ''),
            'correo'                => trim($_POST['correo'] ?? ''),
            'cuenta_corriente'      => trim($_POST['cuenta_corriente'] ?? ''),
            'pais'                  => trim($_POST['pais'] ?? ''),
            'moneda'                => trim($_POST['moneda'] ?? ''),
            'importe_cuota_mensual' => trim($_POST['importe_cuota_mensual'] ?? ''),
        ];

        [$datosValidados, $erroresValidacion] = $this->validarCliente($datosFormulario);

        if (empty($erroresValidacion)) {
            $this->clientModel->actualizar($id, $datosValidados);
            $_SESSION['mensajeOk'] = 'Datos del cliente actualizados.';
            header('Location: /proyecto-tareas/proyecto/public/clients');
            exit;
        }

        return view('clients.edit', [
            'cliente'           => $cliente,
            'datosValidados'    => $datosValidados,
            'erroresValidacion' => $erroresValidacion,
            'listaPaises'       => config('paises.lista'),
            'codigosPaises'     => config('telefonos.lista'),
            'monedas'           => ['EUR', 'USD', 'GBP'],
        ]);
    }

    public function destroy(int $id)
    {
        $this->requireAdmin();

        $this->clientModel->eliminar($id);
        $_SESSION['mensajeOk'] = 'Cliente eliminado correctamente.';
        header('Location: /proyecto-tareas/proyecto/public/clients');
        exit;
    }

    private function validarCliente(array $datosFormulario): array
    {
        $datosValidados = [
            'cif'                   => htmlspecialchars($datosFormulario['cif'] ?? ''),
            'nombre'                => htmlspecialchars($datosFormulario['nombre'] ?? ''),
            'codigo_pais'           => htmlspecialchars($datosFormulario['codigo_pais'] ?? ''),
            'telefono'              => htmlspecialchars($datosFormulario['telefono'] ?? ''),
            'correo'                => htmlspecialchars($datosFormulario['correo'] ?? ''),
            'cuenta_corriente'      => htmlspecialchars($datosFormulario['cuenta_corriente'] ?? ''),
            'pais'                  => htmlspecialchars($datosFormulario['pais'] ?? ''),
            'moneda'                => htmlspecialchars($datosFormulario['moneda'] ?? ''),
            'importe_cuota_mensual' => htmlspecialchars($datosFormulario['importe_cuota_mensual'] ?? ''),
        ];

        $erroresValidacion = [];

        // Validar CIF
        if ($datosValidados['cif'] === '') {
            $erroresValidacion['cif'] = 'El CIF es obligatorio.';
        } elseif (!$this->validarCIF($datosValidados['cif'])) {
            $erroresValidacion['cif'] = 'El CIF no es válido.';
        }

        // Validar nombre
        if ($datosValidados['nombre'] === '') {
            $erroresValidacion['nombre'] = 'El nombre del cliente es obligatorio.';
        } elseif (strlen($datosValidados['nombre']) < 3) {
            $erroresValidacion['nombre'] = 'El nombre debe tener al menos 3 caracteres.';
        } elseif (strlen($datosValidados['nombre']) > 255) {
            $erroresValidacion['nombre'] = 'El nombre no puede exceder 255 caracteres.';
        }

        // Validar código de país y teléfono
        if ($datosValidados['codigo_pais'] === '') {
            $erroresValidacion['codigo_pais'] = 'Selecciona un código de país.';
        } elseif (!in_array($datosValidados['codigo_pais'], config('telefonos.lista'), true)) {
            $erroresValidacion['codigo_pais'] = 'Código de país no válido.';
        }

        if ($datosValidados['telefono'] === '') {
            $erroresValidacion['telefono'] = 'El teléfono es obligatorio.';
        } elseif (!$this->validarTelefono($datosValidados['telefono'], $datosValidados['codigo_pais'])) {
            $erroresValidacion['telefono'] = 'El número de teléfono no es válido. Debe contener entre 7 y 15 dígitos.';
        }

        // Validar correo
        if ($datosValidados['correo'] === '') {
            $erroresValidacion['correo'] = 'El correo es obligatorio.';
        } elseif (!filter_var($datosValidados['correo'], FILTER_VALIDATE_EMAIL)) {
            $erroresValidacion['correo'] = 'El correo no es válido.';
        }

        // Validar cuenta corriente (IBAN básico)
        if ($datosValidados['cuenta_corriente'] === '') {
            $erroresValidacion['cuenta_corriente'] = 'La cuenta corriente es obligatoria.';
        } elseif (!$this->validarIBAN($datosValidados['cuenta_corriente'])) {
            $erroresValidacion['cuenta_corriente'] = 'El IBAN no es válido (debe tener entre 15 y 34 caracteres alfanuméricos).';
        }

        // Validar país
        if ($datosValidados['pais'] === '' || !in_array($datosValidados['pais'], array_keys(config('paises.lista')), true)) {
            $erroresValidacion['pais'] = 'Selecciona un país válido.';
        }

        // Validar moneda
        if ($datosValidados['moneda'] === '') {
            $erroresValidacion['moneda'] = 'La moneda es obligatoria.';
        } elseif (!in_array($datosValidados['moneda'], ['EUR', 'USD', 'GBP'], true)) {
            $erroresValidacion['moneda'] = 'La moneda no es válida.';
        }

        // Validar importe cuota mensual
        if ($datosValidados['importe_cuota_mensual'] === '') {
            $erroresValidacion['importe_cuota_mensual'] = 'El importe de la cuota es obligatorio.';
        } elseif (!is_numeric($datosValidados['importe_cuota_mensual'])) {
            $erroresValidacion['importe_cuota_mensual'] = 'El importe debe ser un número.';
        } elseif ((float)$datosValidados['importe_cuota_mensual'] < 0) {
            $erroresValidacion['importe_cuota_mensual'] = 'El importe debe ser un número positivo.';
        } elseif ((float)$datosValidados['importe_cuota_mensual'] > 999999.99) {
            $erroresValidacion['importe_cuota_mensual'] = 'El importe es demasiado grande.';
        }

        return [$datosValidados, $erroresValidacion];
    }

    private function validarCIF(string $cif): bool
    {
        $cif = strtoupper(trim($cif));
        
        // Formato: 8 caracteres alpanuméricos
        if (!preg_match('/^[A-Z0-9]{8,9}$/', $cif)) {
            return false;
        }

        // CIF español: letra-7dígitos-dígito/letra o 8 dígitos-letra
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}\d{7}[0-9A-J]$/', $cif)) {
            return true;
        }

        // Validar NIE
        if (preg_match('/^[XYZ]{1}\d{7}[0-9A-J]$/', $cif)) {
            return true;
        }

        return true; // Permítir otros formatos también
    }

    private function validarTelefono(string $telefono, string $codigoPais): bool
    {
        // Eliminar espacios, guiones y paréntesis
        $telefonoLimpio = preg_replace('/[\s\-().]/', '', $telefono);

        // Validar que solo contenga dígitos (7 a 15 dígitos)
        if (!preg_match('/^\d{7,15}$/', $telefonoLimpio)) {
            return false;
        }

        return true;
    }

    private function validarIBAN(string $iban): bool
    {
        $iban = strtoupper(str_replace(' ', '', $iban));

        // Validar longitud básica (IBAN válido: 15-34 caracteres)
        if (strlen($iban) < 15 || strlen($iban) > 34) {
            return false;
        }

        // Validar que contenga solo letras y números
        if (!preg_match('/^[A-Z0-9]+$/', $iban)) {
            return false;
        }

        return true;
    }
}
