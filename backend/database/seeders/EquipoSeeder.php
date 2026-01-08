<?php

namespace Database\Seeders;

use App\Models\Equipo;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipos = [
            [
                'codigo' => 'EQ001',
                'tipo' => 'Laptop',
                'cliente' => 'Cliente A',
                'estado' => 'Activo',
                'fecha_entrega' => '2024-01-15',
            ],
            [
                'codigo' => 'EQ002',
                'tipo' => 'Monitor',
                'cliente' => 'Cliente B',
                'estado' => 'En reparación',
                'fecha_entrega' => '2024-01-20',
            ],
            [
                'codigo' => 'EQ003',
                'tipo' => 'Teclado',
                'cliente' => 'Cliente C',
                'estado' => 'Activo',
                'fecha_entrega' => '2024-02-01',
            ],
            [
                'codigo' => 'EQ004',
                'tipo' => 'Mouse',
                'cliente' => 'Cliente A',
                'estado' => 'Activo',
                'fecha_entrega' => '2024-02-10',
            ],
            [
                'codigo' => 'EQ005',
                'tipo' => 'Impresora',
                'cliente' => 'Cliente D',
                'estado' => 'Mantenimiento',
                'fecha_entrega' => '2024-02-15',
            ],
        ];

        foreach ($equipos as $equipo) {
            Equipo::create($equipo);
        }
    }
}

