<?php

namespace Database\Seeders;

use App\Models\Motivo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Motivo::create([
            'nombre' => 'Comida'
        ]);
        Motivo::create([
            'nombre' => 'Ingles'
        ]);
        Motivo::create([
            'nombre' => 'Permiso'
        ]);
        Motivo::create([
            'nombre' => 'Reparacion'
        ]);
        Motivo::create([
            'nombre' => 'Produccion'
        ]);
    }
}
