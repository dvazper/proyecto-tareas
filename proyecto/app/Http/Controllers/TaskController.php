<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Models\SessionManager;

class TaskController extends Controller
{
    private TaskModel $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

public function index()
{
    $session = SessionManager::getInstancia();

    // SOLO redirige si NO hay sesión
    if (!$session->estaLogueado()) {
        header('Location: /proyecto-tareas/proyecto/public/login');
        exit;
    }

    $esAdmin = $session->esAdmin();

    $tareasRegistradas = $this->taskModel->obtenerTodas();
    $mensajeOk = $_SESSION['mensajeOk'] ?? null;
    unset($_SESSION['mensajeOk']);

    return view('tasks.index', [
        'tareasRegistradas' => $tareasRegistradas,
        'mensajeOk'         => $mensajeOk,
        'esAdmin'           => $esAdmin,
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

    // Estado por defecto para nuevas tareas
    if (empty($datosFormulario['estado'])) {
        $datosFormulario['estado'] = 'B';   // Esperando aprobación
    }

    // VALIDACIÓN
    [$datosValidados, $erroresValidacion] = $this->validarTarea($datosFormulario);

    if (empty($erroresValidacion)) {

        $datosValidados['fecha']          = $this->aFechaSQL($datosValidados['fecha']);
        $datosValidados['fecha_creacion'] = $this->aFechaSQL($datosValidados['fecha_creacion']);
        $datosValidados['fichero']        = '';

        $this->taskModel->insertar($datosValidados);

        $_SESSION['mensajeOk'] = 'Tarea creada correctamente.';
        header('Location: /proyecto-tareas/proyecto/public/tasks');
        exit;
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


  private function limpiarCampos(array $datosFormulario): array
{
    $datosLimpios = [];

    $listaCampos = [
        'id', 'contacto', 'nif', 'telefono', 'email',
        'direccion', 'poblacion', 'cp', 'provincia',
        'descripcion', 'fecha', 'operario',
        'anot_prev', 'anot_post', 'estado',
        'fecha_creacion', 'fichero'
    ];

    foreach ($listaCampos as $nombreCampo) {
        if (isset($datosFormulario[$nombreCampo])) {
            $valorLimpio = htmlspecialchars(trim($datosFormulario[$nombreCampo]));
            $datosLimpios[$nombreCampo] = $valorLimpio;
        } else {
            $datosLimpios[$nombreCampo] = '';
        }
    }

    return $datosLimpios;
}


   private function validarObligatorios(array $datosTarea): array
{
    $erroresValidacion = [];

    if ($datosTarea['contacto'] === '') {
        $erroresValidacion['contacto'] = 'La persona de contacto es obligatoria.';
    }

    if ($datosTarea['descripcion'] === '') {
        $erroresValidacion['descripcion'] = 'La descripción es obligatoria.';
    }

    if ($datosTarea['email'] === '') {
        $erroresValidacion['email'] = 'El email es obligatorio.';
    }

    if ($datosTarea['telefono'] === '') {
        $erroresValidacion['telefono'] = 'El teléfono es obligatorio.';
    }

    if ($datosTarea['fecha'] === '') {
        $erroresValidacion['fecha'] = 'La fecha de realización es obligatoria.';
    }

    return $erroresValidacion;
}

    private function validarNif(array $datosTarea): array
{
    $erroresValidacion = [];
    $valorNif = $datosTarea['nif'] ?? '';

    if ($valorNif !== '') {
        $nifSinGuiones = str_replace('-', '', $valorNif);
        if (!ctype_alnum($nifSinGuiones) || strlen($nifSinGuiones) < 8 || strlen($nifSinGuiones) > 12) {
            $erroresValidacion['nif'] = 'Formato del NIF/CIF no válido.';
        }
    }

    return $erroresValidacion;
}

   private function validarTelefono(array $datosTarea): array
{
    $erroresValidacion = [];
    $telefonoOriginal = $datosTarea['telefono'] ?? '';

    if ($telefonoOriginal !== '') {
        $telefonoSoloDigitos = str_replace(
            [' ', '-', '+', '.', '(', ')'],
            '',
            $telefonoOriginal
        );

        if (!ctype_digit($telefonoSoloDigitos) ||
            strlen($telefonoSoloDigitos) < 7 ||
            strlen($telefonoSoloDigitos) > 16) {

            $erroresValidacion['telefono'] = 'Teléfono no válido.';
        }
    }

    return $erroresValidacion;
}

    private function validarEmail(array $datosTarea): array
{
    $erroresValidacion = [];
    $email = $datosTarea['email'] ?? '';

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroresValidacion['email'] = 'Email con formato no válido.';
    }

    return $erroresValidacion;
}

  private function validarCodigoPostal(array $datosTarea): array
{
    $erroresValidacion = [];
    $codigoPostal = $datosTarea['cp'] ?? '';

    if ($codigoPostal !== '' &&
        (!ctype_digit($codigoPostal) || strlen($codigoPostal) !== 5)) {

        $erroresValidacion['cp'] = 'El código postal debe contener 5 números.';
    }

    return $erroresValidacion;
}

   private function validarProvincia(array $datosTarea): array
{
    $erroresValidacion = [];
    $codigoProvincia = $datosTarea['provincia'] ?? '';
    $codigoPostal    = $datosTarea['cp'] ?? '';

    if ($codigoProvincia === '') {
        $erroresValidacion['provincia'] = 'Seleccione una provincia.';
        return $erroresValidacion;
    }

    if (!ctype_digit($codigoProvincia) || strlen($codigoProvincia) !== 2) {
        $erroresValidacion['provincia'] = 'Código de provincia no válido.';
        return $erroresValidacion;
    }

    if ($codigoPostal !== '' && strlen($codigoPostal) === 5) {
        $prefijoPostal = substr($codigoPostal, 0, 2);
        if ($prefijoPostal !== $codigoProvincia) {
            $erroresValidacion['provincia'] =
                'La provincia debe coincidir con los dos primeros dígitos del CP.';
        }
    }

    return $erroresValidacion;
}

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

private function normalizarFechaCreacion(array $datosTarea): array
{
    $fechaCreacion = $datosTarea['fecha_creacion'] ?? '';

    if ($fechaCreacion === '') {
        $datosTarea['fecha_creacion'] = date('d/m/Y');
        return $datosTarea;
    }

    $partesFecha = explode('/', $fechaCreacion);
    if (count($partesFecha) !== 3) {
        $datosTarea['fecha_creacion'] = date('d/m/Y');
        return $datosTarea;
    }

    [$diaTexto, $mesTexto, $anioTexto] = $partesFecha;

    if (!ctype_digit($diaTexto) ||
        !ctype_digit($mesTexto) ||
        !ctype_digit($anioTexto) ||
        !checkdate((int)$mesTexto, (int)$diaTexto, (int)$anioTexto)) {

        $datosTarea['fecha_creacion'] = date('d/m/Y');
        return $datosTarea;
    }

    return $datosTarea;
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

    /* ELIMINAR PARTES */
    public function destroy(int $id)
{
    $this->taskModel->eliminar($id);

    $_SESSION['mensajeOk'] = 'Tarea eliminada correctamente.';
    header('Location: /proyecto-tareas/proyecto/public/tasks');
    exit;
}

}
