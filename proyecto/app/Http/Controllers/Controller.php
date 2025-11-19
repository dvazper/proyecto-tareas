<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class TareaController extends Controller
{
    // Validación de tarea 
    function validarTarea(array $datosFormulario): array {
        $datosValidados = [
            'id' => $datosFormulario['id'] ?? '',
            'contacto' => $datosFormulario['contacto'] ?? '',
            'nif' => $datosFormulario['nif'] ?? '',
            'telefono' => $datosFormulario['telefono'] ?? '',
            'email' => $datosFormulario['email'] ?? '',
            'direccion' => $datosFormulario['direccion'] ?? '',
            'poblacion' => $datosFormulario['poblacion'] ?? '',
            'cp' => $datosFormulario['cp'] ?? '',
            'provincia' => $datosFormulario['provincia'] ?? '',
            'descripcion' => $datosFormulario['descripcion'] ?? '',
            'fecha' => $datosFormulario['fecha'] ?? '',
            'operario' => $datosFormulario['operario'] ?? '',
            'anot_prev' => $datosFormulario['anot_prev'] ?? '',
            'anot_post' => $datosFormulario['anot_post'] ?? '',
            'estado' => $datosFormulario['estado'] ?? '',
            'fecha_creacion' => $datosFormulario['fecha_creacion'] ?? date('d/m/Y'),
        ];

        $erroresValidacion = [];

        if ($datosValidados['contacto'] === '')    $erroresValidacion['contacto'] = 'La persona de contacto es obligatoria.';
        if ($datosValidados['descripcion'] === '') $erroresValidacion['descripcion'] = 'La descripción es obligatoria.';
        if ($datosValidados['email'] === '')       $erroresValidacion['email'] = 'El email es obligatorio.';
        if ($datosValidados['email'] !== '' && !filter_var($datosValidados['email'], FILTER_VALIDATE_EMAIL))
            $erroresValidacion['email'] = 'Email con formato no válido.';

        return [$datosValidados, $erroresValidacion];
    }

    // Lista de provincias
    function provincias() {
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

    // Mostrar todas las tareas
    public function index() {
        $tareas = DB::table('tareas')->get();
        return view('tareas.index', compact('tareas'));
    }

    // Formulario nueva tarea
    public function nueva() {
        $provincias = $this->provincias();
        return view('tareas.nueva', compact('provincias'));
    }

    // Guardar nueva tarea
    public function guardarNueva(Request $request) {
        [$datos, $errores] = $this->validarTarea($request->all());
        if ($errores) return back()->withErrors($errores)->withInput();

        // Convertir fechas a formato MySQL
        $datos['fecha'] = Carbon::createFromFormat('d/m/Y', $datos['fecha'])->format('Y-m-d');
        $datos['fecha_creacion'] = Carbon::createFromFormat('d/m/Y', $datos['fecha_creacion'])->format('Y-m-d');

        DB::table('tareas')->insert($datos);
        return redirect('/tareas');
    }

    // Formulario editar tarea
    public function editar($id) {
        $tarea = DB::table('tareas')->find($id);
        $provincias = $this->provincias();
        return view('tareas.editar', compact('tarea', 'provincias'));
    }

    // Guardar tarea editada
    public function guardarEditar(Request $request, $id) {
        [$datos, $errores] = $this->validarTarea($request->all());
        if ($errores) return back()->withErrors($errores)->withInput();

        // Convertir fechas
        $datos['fecha'] = Carbon::createFromFormat('d/m/Y', $datos['fecha'])->format('Y-m-d');
        $datos['fecha_creacion'] = Carbon::createFromFormat('d/m/Y', $datos['fecha_creacion'])->format('Y-m-d');

        DB::table('tareas')->where('id', $id)->update($datos);
        return redirect('/tareas');
    }

    // Borrar tarea
    public function borrar($id) {
        DB::table('tareas')->where('id', $id)->delete();
        return redirect('/tareas');
    }

    // Formulario parte operario
    public function operario($id) {
        $tarea = DB::table('tareas')->find($id);
        return view('tareas.operario', compact('tarea'));
    }

    // Guardar parte operario
    public function guardarOperario(Request $request, $id) {
        $datos = $request->only('estado','fecha','anot_post');

        // Convertir fecha
        $datos['fecha'] = Carbon::createFromFormat('d/m/Y', $datos['fecha'])->format('Y-m-d');

        DB::table('tareas')->where('id', $id)->update($datos);
        return redirect('/tareas');
    }
}
