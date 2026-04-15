<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
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

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::post('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
Route::post('/employees/{id}/delete', [EmployeeController::class, 'destroy'])->name('employees.destroy');

Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
Route::post('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
Route::post('/clients/{id}/delete', [ClientController::class, 'destroy'])->name('clients.destroy');

Route::get('/login',  [AuthController::class, 'mostrarFormularioLogin']);
Route::post('/login', [AuthController::class, 'procesarLogin']);
Route::post('/logout', [AuthController::class, 'logout']);
