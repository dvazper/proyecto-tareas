<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tareas = Task::orderByDesc('id')->get();
        return view('tasks.index', compact('tareas'));
    }

    public function create()
    {
        $provincias = $this->provinciasINE();
        return view('tasks.create', compact('provincias'));
    }

    public function store(Request $request)
    {
        $datosFormulario = $request->all();

        // VALIDACIÓN BÁSICA 
        $datosValidados = $request->validate([
            'contacto' => 'required|string',
            'descripcion' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'cp' => 'nullable|digits:5',
            'provincia' => 'nullable|digits:2',
            'fecha' => 'required|string', // dd/mm/aaaa
            'estado' => 'required|in:B,P,R,C',
            'nif' => 'nullable|string|min:8|max:12',
            'direccion' => 'nullable|string',
            'poblacion' => 'nullable|string',
            'operario' => 'nullable|string',
            'anot_prev' => 'nullable|string',
            'anot_post' => 'nullable|string',
            'fecha_creacion' => 'nullable|string',
        ]);

        // NORMALIZAR FECHAS (dd/mm/aaaa → Y-m-d)
        $datosValidados['fecha'] = $this->aFechaSQL($datosValidados['fecha']);

        if (!empty($datosValidados['fecha_creacion'])) {
            $datosValidados['fecha_creacion'] = $this->aFechaSQL($datosValidados['fecha_creacion']);
        } else {
            $datosValidados['fecha_creacion'] = now()->format('Y-m-d');
        }

        // GUARDAR EN BD
        Task::create($datosValidados);

        return redirect()->route('tasks.index')->with('ok', 'Tarea creada correctamente.');
    }

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

    private function aFechaSQL(string $fecha): string
    {
        $partes = explode('/', $fecha);
        if (count($partes) !== 3) {
            abort(422, 'Fecha inválida');
        }
        [$dd, $mm, $aaaa] = $partes;
        return sprintf('%04d-%02d-%02d', (int)$aaaa, (int)$mm, (int)$dd);
    }
}