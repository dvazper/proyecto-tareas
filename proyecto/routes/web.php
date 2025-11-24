<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\OperarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/tasks');
});

Route::get('/tasks',        [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks',       [TaskController::class, 'store'])->name('tasks.store');

Route::get('/tasks/{id}/operario',  [OperarioController::class, 'edit'])->name('operario.edit');
Route::post('/tasks/{id}/operario', [OperarioController::class, 'update'])->name('operario.update');
