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
            'nombre' => 'Comida',
            'descripcion' => 'Pausa para comer',
            'is_pausa' => true
        ]);
        Motivo::create([
            'nombre' => 'Ingles',
            'descripcion' => 'Clases de inglés',
            'is_pausa' => true
        ]);
        Motivo::create([
            'nombre' => 'Permiso',
            'descripcion' => 'Permiso especiales',
            'is_pausa' => true
        ]);
        Motivo::create([
            'nombre' => 'Reparacion',
            'descripcion' => 'Extras por reparacion',
            'is_pausa' => false
        ]);
        Motivo::create([
            'nombre' => 'Produccion',
            'descripcion' => 'Extras por motivos de produccion',
            'is_pausa' => false
        ]);
    }
}
