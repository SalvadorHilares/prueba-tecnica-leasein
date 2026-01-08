<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EquipoController extends Controller
{
    /**
     * Obtener todos los equipos
     */
    public function index(): JsonResponse
    {
        $equipos = Equipo::all();
        
        return response()->json($equipos);
    }

    /**
     * Validar códigos de equipos
     */
    public function validarEquipos(Request $request): JsonResponse
    {
        $request->validate([
            'codigos' => 'required|array',
            'codigos.*' => 'required|string',
        ]);

        $codigos = $request->input('codigos');
        
        // Buscar equipos que existen
        $equiposEncontrados = Equipo::whereIn('codigo', $codigos)
            ->pluck('codigo')
            ->toArray();
        
        // Separar encontrados y no encontrados
        $encontrados = array_intersect($codigos, $equiposEncontrados);
        $noEncontrados = array_diff($codigos, $equiposEncontrados);
        
        return response()->json([
            'encontrados' => array_values($encontrados),
            'no_encontrados' => array_values($noEncontrados),
        ]);
    }
}

