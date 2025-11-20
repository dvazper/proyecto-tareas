<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tareas'; // o el nombre que tenga tu tabla real

    protected $fillable = [
        'contacto',
        'descripcion',
        'email',
        'telefono',
        'cp',
        'provincia',
        'fecha',
        'estado',
        'nif',
        'direccion',
        'poblacion',
        'operario',
        'anot_prev',
        'anot_post',
        'fecha_creacion'
    ];
}