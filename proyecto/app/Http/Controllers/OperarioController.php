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

        // Copiamos todo lo que había
        $paqueteOperario = $tareaOriginal;

        // Campos modificables por operario
        $paqueteOperario['estado']    = $_POST['estado']    ?? $tareaOriginal['estado'];
        $paqueteOperario['anot_post'] = $_POST['anot_post'] ?? $tareaOriginal['anot_post'];
        $paqueteOperario['fecha']     = $_POST['fecha']     ?? $tareaOriginal['fecha'];
        $paqueteOperario['fecha_creacion'] =
            $_POST['fecha_creacion'] ?? $tareaOriginal['fecha_creacion'];

        // ⬇ VALIDACIÓN DENTRO DEL CONTROLADOR
        [$datosValidados, $erroresValidacion] = $this->validarParteOperario($paqueteOperario);

        if (empty($erroresValidacion)) {

            $datosValidados['fecha'] = $this->aFechaSQL($datosValidados['fecha']);

            $this->taskModel->actualizarDesdeOperario($id, $datosValidados);

            return view('tasks.index', [
                'tareasRegistradas' => $this->taskModel->obtenerTodas(),
                'mensajeOk'         => 'Parte de operario guardado.',
            ]);
        }

        return view('tasks.operario', [
            'tarea'            => $tareaOriginal,
            'datosValidados'   => $datosValidados,
            'erroresValidacion'=> $erroresValidacion,
        ]);
    }


    /* ============================================================
       VALIDACIONES PARA EL PARTE DE OPERARIO
       ============================================================ */

    private function validarParteOperario(array $d): array
    {
        $errores = [];

        // Solo validar lo que el operario puede tocar
        $errores += $this->validarFecha($d);
        $errores += $this->validarEstado($d);

        // anot_post no requiere formato
        $d['anot_post'] = htmlspecialchars(trim($d['anot_post']));

        return [$d, $errores];
    }


    /* ============================================================
       FUNCIONES PRIVADAS DE VALIDACIÓN (RECORTADAS)
       ============================================================ */

    private function validarFecha($d)
    {
        $e=[];
        if ($d['fecha']!=='') {
            $p = explode('/',$d['fecha']);
            if (count($p)!==3) return ['fecha'=>'Formato dd/mm/aaaa.'];

            [$dd,$mm,$aa] = $p;

            if(!ctype_digit($dd) || !ctype_digit($mm) || !ctype_digit($aa))
                return ['fecha'=>'Formato dd/mm/aaaa.'];

            if(!checkdate((int)$mm,(int)$dd,(int)$aa))
                return ['fecha'=>'Fecha no válida.'];

            if (strtotime("$aa-$mm-$dd") <= strtotime('today'))
                return ['fecha'=>'Debe ser posterior a hoy.'];
        }
        return $e;
    }

    private function validarEstado($d)
    {
        $e=[];
        if ($d['estado']==='' || !in_array($d['estado'], ['B','P','R','C'])) {
            $e['estado']='Estado no válido.';
        }
        return $e;
    }

    /* ============================================================
       AUXILIARES
       ============================================================ */

    private function aFechaSQL(string $f): string
    {
        $p = explode('/',$f);
        return sprintf('%04d-%02d-%02d',(int)$p[2],(int)$p[1],(int)$p[0]);
    }
}
