<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Models\SessionManager;

class OperarioController extends Controller
{
    private TaskModel $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    /**
     * Muestra el formulario del parte de operario para una tarea concreta.
     */
    public function edit(int $id)
    {
        $session = SessionManager::getInstancia();

        // Solo usuarios logueados
        if (!$session->estaLogueado()) {
            header('Location: /proyecto-tareas/proyecto/public/login');
            exit;
        }

        // Solo operario o admin
        if (!$session->esOperario() && !$session->esAdmin()) {
            header('Location: /proyecto-tareas/proyecto/public/login');
            exit;
        }

        $tarea = $this->taskModel->buscarPorId($id);

        if (!$tarea) {
            $_SESSION['mensajeOk'] = 'La tarea no existe.';
            header('Location: /proyecto-tareas/proyecto/public/tasks');
            exit;
        }

        return view('tasks.operario', [
            'tarea'             => $tarea,
            'datosValidados'    => $tarea,
            'erroresValidacion' => [],
        ]);
    }

    /**
     * Procesa el envío del parte de operario.
     */
    public function update(int $id)
    {
        $session = SessionManager::getInstancia();

        // Solo usuarios logueados
        if (!$session->estaLogueado()) {
            header('Location: /proyecto-tareas/proyecto/public/login');
            exit;
        }

        // Solo operario o admin
        if (!$session->esOperario() && !$session->esAdmin()) {
            header('Location: /proyecto-tareas/proyecto/public/login');
            exit;
        }

        // --- 1. Obtener tarea original ---
        $tareaOriginal = $this->taskModel->buscarPorId($id);

        if (!$tareaOriginal) {
            $_SESSION['mensajeOk'] = 'La tarea no existe.';
            header('Location: /proyecto-tareas/proyecto/public/tasks');
            exit;
        }

        // --- 2. Recoger datos del formulario ---
        $datosFormulario = [
            'estado'    => $_POST['estado']    ?? '',
            'fecha'     => $_POST['fecha']     ?? '',
            'anot_post' => $_POST['anot_post'] ?? '',
        ];

        // --- 3. Mezclar tarea original + cambios del operario ---
        $datosCombinados = $tareaOriginal;

        $datosCombinados['estado']    = htmlspecialchars(trim($datosFormulario['estado']));
        $datosCombinados['fecha']     = htmlspecialchars(trim($datosFormulario['fecha']));
        $datosCombinados['anot_post'] = htmlspecialchars(trim($datosFormulario['anot_post']));

        // --- 4. Validación ---
        $erroresValidacion = [];

        // Validar fecha
        $erroresFecha = $this->validarFecha($datosCombinados);
        $erroresValidacion = array_merge($erroresValidacion, $erroresFecha);

        // Validar estado
        $erroresEstado = $this->validarEstado($datosCombinados);
        $erroresValidacion = array_merge($erroresValidacion, $erroresEstado);

        // Si hay errores, volvemos al formulario de operario
        if (!empty($erroresValidacion)) {
            return view('tasks.operario', [
                'tarea'             => $tareaOriginal,
                'datosValidados'    => $datosCombinados,
                'erroresValidacion' => $erroresValidacion,
            ]);
        }

        // --- 5. Convertir fecha a formato SQL ---
        $datosCombinados['fecha'] = $this->aFechaSQL($datosCombinados['fecha']);

        // --- 6. Guardar cambios en la base de datos ---
        $this->taskModel->actualizarDesdeOperario($id, $datosCombinados);

        // --- 7. Mensaje y redirección al listado ---
        $_SESSION['mensajeOk'] = 'Parte de operario guardado.';
        header('Location: /proyecto-tareas/proyecto/public/tasks');
        exit;
    }

    /* =========================================================
       VALIDACIONES SOLO PARA OPERARIO (fecha + estado)
       ========================================================= */

    private function validarFecha(array $datosTarea): array
    {
        $erroresValidacion = [];
        $fechaTexto = $datosTarea['fecha'] ?? '';

        if ($fechaTexto !== '') {
            $partesFecha = explode('/', $fechaTexto);

            if (count($partesFecha) !== 3) {
                $erroresValidacion['fecha'] = 'Formato dd/mm/aaaa.';
                return $erroresValidacion;
            }

            [$diaTexto, $mesTexto, $anioTexto] = $partesFecha;

            if (!ctype_digit($diaTexto) ||
                !ctype_digit($mesTexto) ||
                !ctype_digit($anioTexto)) {

                $erroresValidacion['fecha'] = 'Formato dd/mm/aaaa.';
                return $erroresValidacion;
            }

            $dia  = (int)$diaTexto;
            $mes  = (int)$mesTexto;
            $anio = (int)$anioTexto;

            if (!checkdate($mes, $dia, $anio)) {
                $erroresValidacion['fecha'] = 'Fecha no válida.';
                return $erroresValidacion;
            }

            $timestampFecha = strtotime("$anio-$mes-$dia");
            $timestampHoy   = strtotime('today');

            if ($timestampFecha <= $timestampHoy) {
                $erroresValidacion['fecha'] = 'Debe ser posterior a hoy.';
                return $erroresValidacion;
            }
        }

        return $erroresValidacion;
    }

    private function validarEstado(array $datosTarea): array
    {
        $erroresValidacion = [];
        $estadoActual = $datosTarea['estado'] ?? '';
        $estadosPermitidos = ['B', 'P', 'R', 'C'];

        if ($estadoActual === '' ||
            !in_array($estadoActual, $estadosPermitidos, true)) {

            $erroresValidacion['estado'] = 'Estado no válido.';
        }

        return $erroresValidacion;
    }

    /* =========================================================
       AUXILIAR: convertir fecha dd/mm/aaaa → Y-m-d para SQL
       ========================================================= */

    private function aFechaSQL(string $fechaFormulario): string
    {
        $partesFecha = explode('/', $fechaFormulario);

        if (count($partesFecha) !== 3) {
            return date('Y-m-d');
        }

        [$diaTexto, $mesTexto, $anioTexto] = $partesFecha;

        $dia  = (int)$diaTexto;
        $mes  = (int)$mesTexto;
        $anio = (int)$anioTexto;

        return sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
    }
}
