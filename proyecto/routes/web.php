<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    echo "<h1>Hola Mundo</h1>";
    //return view('welcome');
});

use App\Http\Controllers\TareaController;

Route::get('/', [TareaController::class, 'index']);