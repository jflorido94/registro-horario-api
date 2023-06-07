<?php

namespace Database\Seeders;

use App\Models\Jornada;
use App\Models\TipoJornada;
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
        if (Jornada::all()->count() == 0) {
            Jornada::create([
                'nombre' => 'Predeterminada',
                'descripcion' => '',
                'entrada' => '08:00',
                'salida' => '17:02',
                'total' => '08:02',
                'tipo_jornada_id' => TipoJornada::where('nombre','Laboral')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Intensiva',
                'descripcion' => '',
                'entrada' => '07:00',
                'salida' => '15:02',
                'total' => '08:02',
                'tipo_jornada_id' => TipoJornada::where('nombre','Laboral')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Fin de Semana',
                'descripcion' => '',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Festivo')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Vacaciones Fijas',
                'descripcion' => 'Vacaciones por cierre de la empresa',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Vacaciones Fijas')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Vacaciones Personales',
                'descripcion' => '',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Vacaciones Personales')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Festivo',
                'descripcion' => '',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Festivo')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Puente',
                'descripcion' => '',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Festivo')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Permiso',
                'descripcion' => '',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Permiso y Baja')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Baja',
                'descripcion' => '',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Permiso y Baja')->first()->id,
            ]);
            Jornada::create([
                'nombre' => 'Excedencia',
                'descripcion' => '',
                'entrada' => '00:00',
                'salida' => '00:00',
                'total' => '00:00',
                'tipo_jornada_id' => TipoJornada::where('nombre','Excedencia')->first()->id,
            ]);
        }
    }
}
