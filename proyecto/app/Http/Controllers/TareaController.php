<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

    $tareas = [
    [
      
    ]
];

class TareaController extends Controller
{
    public function index()
    {
        // Aquí obtienes las tareas desde tu tabla
        $tareas = DB::table('tareas')->get();

        // Pasamos $tareas a la vista
        return view('index', compact('tareas'));
    }
}
