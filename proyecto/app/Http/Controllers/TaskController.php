<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;

class TaskController extends Controller
{
    private TaskModel $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    public function index()
{
    $tareasRegistradas = $this->taskModel->obtenerTodas();
    $mensajeOk = session('mensajeOk'); // leer mensaje flash

    return view('tasks.index', [
        'tareasRegistradas' => $tareasRegistradas,
        'mensajeOk'         => $mensajeOk,
    ]);
}


    public function create()
    {
        return view('tasks.create', [
            'listaProvincias'   => $this->provinciasINE(),
            'datosValidados'    => [],
            'erroresValidacion' => [],
        ]);
    }

    public function store()
    {
        $datosFormulario = $_POST;

        // VALIDACIÓN INCLUIDA EN EL MISMO ARCHIVO
        [$datosValidados, $erroresValidacion] = $this->validarTarea($datosFormulario);

      if (empty($erroresValidacion)) {

    $datosValidados['fecha'] = $this->aFechaSQL($datosValidados['fecha']);
    $datosValidados['fecha_creacion'] = $this->aFechaSQL($datosValidados['fecha_creacion']);
    $datosValidados['fichero'] = '';

    $this->taskModel->insertar($datosValidados);

    return redirect()
        ->route('tasks.index')
        ->with('mensajeOk', 'Tarea creada correctamente.');
}


        return view('tasks.create', [
            'listaProvincias'   => $this->provinciasINE(),
            'datosValidados'    => $datosValidados,
            'erroresValidacion' => $erroresValidacion,
        ]);
    }


    /* ============================================================
       VALIDACIÓN INCLUIDA EN EL MISMO CONTROLADOR
       ============================================================ */

    private function validarTarea(array $datosFormulario): array
    {
        $datosValidados = $this->limpiarCampos($datosFormulario);
        $errores = [];

        $errores += $this->validarObligatorios($datosValidados);
        $errores += $this->validarNif($datosValidados);
        $errores += $this->validarTelefono($datosValidados);
        $errores += $this->validarEmail($datosValidados);
        $errores += $this->validarCodigoPostal($datosValidados);
        $errores += $this->validarProvincia($datosValidados);
        $errores += $this->validarFecha($datosValidados);
        $errores += $this->validarEstado($datosValidados);

        $datosValidados = $this->normalizarFechaCreacion($datosValidados);

        return [$datosValidados, $errores];
    }


    private function limpiarCampos(array $f): array
    {
        $limpio = [];
        foreach ([
            'id','contacto','nif','telefono','email','direccion','poblacion',
            'cp','provincia','descripcion','fecha','operario','anot_prev',
            'anot_post','estado','fecha_creacion','fichero'
        ] as $campo) {
            $limpio[$campo] = isset($f[$campo]) ? htmlspecialchars(trim($f[$campo])) : '';
        }
        return $limpio;
    }

    private function validarObligatorios($d)
    {
        $e = [];
        if ($d['contacto']==='')    $e['contacto']='La persona de contacto es obligatoria.';
        if ($d['descripcion']==='') $e['descripcion']='La descripción es obligatoria.';
        if ($d['email']==='')       $e['email']='El email es obligatorio.';
        if ($d['telefono']==='')    $e['telefono']='El teléfono es obligatorio.';
        if ($d['fecha']==='')       $e['fecha']='La fecha de realización es obligatoria.';
        return $e;
    }

    private function validarNif($d)
    {
        $e = [];
        if ($d['nif']!=='') {
            $tmp = str_replace('-', '', $d['nif']);
            if (!ctype_alnum($tmp) || strlen($tmp) < 8 || strlen($tmp) > 12) {
                $e['nif'] = 'Formato de NIF/CIF no válido.';
            }
        }
        return $e;
    }

    private function validarTelefono($d)
    {
        $e = [];
        if ($d['telefono']!=='') {
            $solo = str_replace([' ', '-', '+', '.', '(', ')'], '', $d['telefono']);
            if (!ctype_digit($solo) || strlen($solo) < 7 || strlen($solo) > 16) {
                $e['telefono'] = 'Teléfono no válido.';
            }
        }
        return $e;
    }

    private function validarEmail($d)
    {
        $e = [];
        if ($d['email']!=='' && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $e['email'] = 'Email con formato no válido.';
        }
        return $e;
    }

    private function validarCodigoPostal($d)
    {
        $e = [];
        if ($d['cp']!=='' && (!ctype_digit($d['cp']) || strlen($d['cp']) !== 5)) {
            $e['cp'] = 'Código postal debe ser 5 números.';
        }
        return $e;
    }

    private function validarProvincia($d)
    {
        $e = [];
        if ($d['provincia']==='') {
            $e['provincia']='Selecciona una provincia.';
            return $e;
        }
        if (!ctype_digit($d['provincia']) || strlen($d['provincia'])!==2) {
            $e['provincia']='Código de provincia no válido.';
            return $e;
        }
        if ($d['cp']!=='' && substr($d['cp'],0,2)!==$d['provincia']) {
            $e['provincia']='La provincia debe coincidir con el CP.';
        }
        return $e;
    }

    private function validarFecha($d)
    {
        $e=[];
        if ($d['fecha']!=='') {
            $p = explode('/',$d['fecha']);
            if (count($p)!==3) {
                $e['fecha'] = 'Formato dd/mm/aaaa.';
                return $e;
            }
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

    private function normalizarFechaCreacion($d)
    {
        if ($d['fecha_creacion']==='') {
            $d['fecha_creacion'] = date('d/m/Y');
        }
        return $d;
    }


    /* ===============================================================
       AUXILIARES
       ============================================================== */

   private function provinciasINE(): array
{
    return [
        '01'=>'Álava','02'=>'Albacete','03'=>'Alicante','04'=>'Almería','05'=>'Ávila',
        '06'=>'Badajoz','07'=>'Illes Balears','08'=>'Barcelona','09'=>'Burgos','10'=>'Cáceres',
        '11'=>'Cádiz','12'=>'Castellón','13'=>'Ciudad Real','14'=>'Córdoba','15'=>'A Coruña',
        '16'=>'Cuenca','17'=>'Girona','18'=>'Granada','19'=>'Guadalajara','20'=>'Gipuzkoa',
        '21'=>'Huelva','22'=>'Huesca','23'=>'Jaén','24'=>'León','25'=>'Lleida',
        '26'=>'La Rioja','27'=>'Lugo','28'=>'Madrid','29'=>'Málaga','30'=>'Murcia',
        '31'=>'Navarra','32'=>'Ourense','33'=>'Asturias','34'=>'Palencia','35'=>'Las Palmas',
        '36'=>'Pontevedra','37'=>'Salamanca','38'=>'Santa Cruz de Tenerife','39'=>'Cantabria','40'=>'Segovia',
        '41'=>'Sevilla','42'=>'Soria','43'=>'Tarragona','44'=>'Teruel','45'=>'Toledo',
        '46'=>'València','47'=>'Valladolid','48'=>'Bizkaia','49'=>'Zamora','50'=>'Zaragoza',
        '51'=>'Ceuta','52'=>'Melilla'
    ];
}


    private function aFechaSQL(string $f): string
    {
        $p = explode('/',$f);
        return sprintf('%04d-%02d-%02d',(int)$p[2],(int)$p[1],(int)$p[0]);
    }

    /* ELIMINAR PARTES */
    public function destroy(int $id)
{
    $this->taskModel->eliminar($id);

    return redirect()
        ->route('tasks.index')
        ->with('mensajeOk', 'Tarea eliminada correctamente.');
}

}
