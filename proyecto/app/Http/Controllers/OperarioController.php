<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class OperarioController extends Controller
{
    public function edit(Task $task)
    {
        return view('tasks.operario', ['task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $datosValidados = $request->validate([
            'fecha'   => 'required|string',
            'estado'  => 'required|in:R,C,P,B',
            'anot_post' => 'nullable|string',
        ]);

        $datosValidados['fecha'] = $this->aFechaSQL($datosValidados['fecha']);

        $task->update($datosValidados);

        return redirect()->route('tasks.index')->with('ok', 'Parte de operario guardado.');
    }

    private function aFechaSQL(string $fecha): string
    {
        $p = explode('/', $fecha);
        if (count($p)!==3) {
            abort(422, 'Fecha inválida');
        }
        return sprintf('%04d-%02d-%02d', (int)$p[2], (int)$p[1], (int)$p[0]);
    }
}
