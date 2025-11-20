<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\OperarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class)->only(['index','create','store']);

Route::get('tasks/{task}/operario', [OperarioController::class, 'edit'])->name('operario.edit');
Route::put('tasks/{task}/operario', [OperarioController::class, 'update'])->name('operario.update');
