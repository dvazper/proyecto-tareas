<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\OperarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/tasks');
});

Route::get('/tasks',        [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks',       [TaskController::class, 'store'])->name('tasks.store');
Route::post('/tasks/{id}/delete', [TaskController::class, 'destroy'])->name('tasks.destroy');


Route::get('/tasks/{id}/operario',  [OperarioController::class, 'edit'])->name('operario.edit');
Route::post('/tasks/{id}/operario', [OperarioController::class, 'update'])->name('operario.update');
Route::get('/login',  [AuthController::class, 'mostrarFormularioLogin']);
Route::post('/login', [AuthController::class, 'procesarLogin']);
Route::post('/logout', [AuthController::class, 'logout']);
