<?php

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuario::factory()->create([
            'nombre' => 'Javier',
            'apellidos' => 'Florido Pavon',
            'email' => 'jflorido@bmsl.es',
            'password' => app('hash')->make('147258'),
        ]);

        //***************
        Usuario::factory()->create([
            'nombre' => 'Francisco Javier',
            'apellidos' => 'Ruperez Sanchez',
            'email' => 'jruperez@bmsl.es',
            'password' => app('hash')->make('123456'),
        ]);

        //***************
        Usuario::factory()->create([
            'nombre' => 'Angel',
            'apellidos' => 'Sanchez Martin',
            'email' => 'asanchez@bmsl.es',
            'password' => app('hash')->make('123456'),
        ]);


        //***************
        Usuario::factory()->create([
            'nombre' => 'Fernando',
            'apellidos' => 'Alcalde-Moraño',
            'email' => 'falcalde@bmsl.es',
            'password' => app('hash')->make('123456'),
        ]);

        //***************

        Usuario::factory(10)->create();

    }
}
