<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;

class OperarioController extends Controller
{
    private TaskModel $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    public function edit(int $id)
    {
        $tarea = $this->taskModel->buscarPorId($id);

        if (!$tarea) {
            abort(404, 'Tarea no encontrada');
        }

        $datosValidados = $tarea;

        if (!empty($tarea['fecha'])) {
            $datosValidados['fecha'] = date('d/m/Y', strtotime($tarea['fecha']));
        }
        if (!empty($tarea['fecha_creacion'])) {
            $datosValidados['fecha_creacion'] = date('d/m/Y', strtotime($tarea['fecha_creacion']));
        }

        return view('tasks.operario', [
            'tarea'            => $tarea,
            'datosValidados'   => $datosValidados,
            'erroresValidacion'=> [],
        ]);
    }

    public function update(int $id)
    {
        $tareaOriginal = $this->taskModel->buscarPorId($id);
        if (!$tareaOriginal) {
            abort(404, 'Tarea no encontrada');
        }

        $paqueteOperario = $tareaOriginal;

        // Campos modificables por operario
        $paqueteOperario['estado']    = $_POST['estado']    ?? $tareaOriginal['estado'];
        $paqueteOperario['anot_post'] = $_POST['anot_post'] ?? $tareaOriginal['anot_post'];
        $paqueteOperario['fecha']     = $_POST['fecha']     ?? $tareaOriginal['fecha'];
        $paqueteOperario['fecha_creacion'] =
            $_POST['fecha_creacion'] ?? $tareaOriginal['fecha_creacion'];

       // VALIDACIÓN
[$datosValidados, $erroresValidacion] = $this->validarParteOperario($paqueteOperario);

if (empty($erroresValidacion)) {

    $datosValidados['fecha'] = $this->convertirFechaFormularioASQL($datosValidados['fecha']);

    $this->taskModel->actualizarDesdeOperario($id, $datosValidados);

    return redirect()
        ->route('tasks.index')
        ->with('mensajeOk', 'Parte de operario guardado.');
}


return view('tasks.operario', [
    'tarea'            => $tareaOriginal,
    'datosValidados'   => $datosValidados,
    'erroresValidacion'=> $erroresValidacion,
]);
}


 private function validarParteOperario(array $datosOperario): array
{
    $erroresValidacion = [];

    $erroresValidacion += $this->validarFechaOperario($datosOperario);
    $erroresValidacion += $this->validarEstadoOperario($datosOperario);

    $textoAnotaciones = $datosOperario['anot_post'] ?? '';
    $datosOperario['anot_post'] = htmlspecialchars(trim($textoAnotaciones));

    return [$datosOperario, $erroresValidacion];
}

private function validarFechaOperario(array $datosOperario): array
{
    $erroresFecha = [];

    if ($datosOperario['fecha'] !== '') {
        $partesFecha = explode('/', $datosOperario['fecha']);
        if (count($partesFecha) !== 3) {
            return ['fecha' => 'Formato dd/mm/aaaa.'];
        }

        [$diaStr, $mesStr, $anioStr] = $partesFecha;

        if (!ctype_digit($diaStr) || !ctype_digit($mesStr) || !ctype_digit($anioStr)) {
            return ['fecha' => 'Formato dd/mm/aaaa.'];
        }

        $dia  = (int)$diaStr;
        $mes  = (int)$mesStr;
        $anio = (int)$anioStr;

        if (!checkdate($mes, $dia, $anio)) {
            return ['fecha' => 'Fecha no válida.'];
        }

        $timestampFecha = strtotime("$anio-$mes-$dia");
        $timestampHoy   = strtotime('today');

        if ($timestampFecha <= $timestampHoy) {
            return ['fecha' => 'Debe ser posterior a hoy.'];
        }
    }

    return $erroresFecha;
}

private function validarEstadoOperario(array $datosOperario): array
{
    $erroresEstado = [];
    $estadosPermitidos = ['B','P','R','C'];

    if ($datosOperario['estado'] === '' ||
        !in_array($datosOperario['estado'], $estadosPermitidos, true)) {

        $erroresEstado['estado'] = 'Estado no válido.';
    }

    return $erroresEstado;
}

private function convertirFechaFormularioASQL(string $fechaFormulario): string
{
    $partesFecha = explode('/', $fechaFormulario);

    if (count($partesFecha) !== 3) {
        return date('Y-m-d');
    }

    [$diaStr, $mesStr, $anioStr] = $partesFecha;
    $dia  = (int)$diaStr;
    $mes  = (int)$mesStr;
    $anio = (int)$anioStr;

    return sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
}

}
