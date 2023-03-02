<?php

namespace Database\Seeders;

use App\Models\Jornada;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JornadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jornada::create([
            'nombre' => 'Predeterminada',
            'descripcion' => '',
            'entrada' => '08:00',
            'salida' => '17:02',
            'total' => '08:02',
            'tipo_jornada_id' => 1,
        ]);
        Jornada::create([
            'nombre' => 'Intensiva',
            'descripcion' => '',
            'entrada' => '07:00',
            'salida' => '15:02',
            'total' => '08:02',
            'tipo_jornada_id' => 1,
        ]);
        Jornada::create([
            'nombre' => 'Fin de Semana',
            'descripcion' => '',
            'entrada' => '00:00',
            'salida' => '00:00',
            'total' => '00:00',
            'tipo_jornada_id' => '4',
        ]);
    }
}
