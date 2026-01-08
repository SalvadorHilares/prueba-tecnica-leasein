<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';

    protected $fillable = [
        'codigo',
        'tipo',
        'cliente',
        'estado',
        'fecha_entrega',
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
    ];
}

