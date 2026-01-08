<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;

Route::get('/equipos', [EquipoController::class, 'index']);
Route::post('/validar-equipos', [EquipoController::class, 'validarEquipos']);

