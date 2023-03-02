<?php

namespace Database\Seeders;

use App\Models\TipoJornada;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoJornadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoJornada::create([
            'nombre' => 'Laboral',
            'vacaciones' => 0,
            'personal' => 1,
            'remunerado' => 1,
            'libre' => 0,
        ]);
        TipoJornada::create([
            'nombre' => 'Vacaciones Fijas',
            'vacaciones' => 1,
            'personal' => 0,
            'remunerado' => 1,
            'libre' => 1,
        ]);
        TipoJornada::create([
            'nombre' => 'Vacaciones Personales',
            'vacaciones' => 1,
            'personal' => 1,
            'remunerado' => 1,
            'libre' => 1,
        ]);
        TipoJornada::create([
            'nombre' => 'Festivo',
            'vacaciones' => 0,
            'personal' => 0,
            'remunerado' => 1,
            'libre' => 1,
        ]);
        TipoJornada::create([
            'nombre' => 'Permiso y Baja',
            'vacaciones' => 0,
            'personal' => 1,
            'remunerado' => 1,
            'libre' => 1,
        ]);
        TipoJornada::create([
            'nombre' => 'Excedencia',
            'vacaciones' => 0,
            'personal' => 1,
            'remunerado' => 0,
            'libre' => 1,
        ]);
    }
}
