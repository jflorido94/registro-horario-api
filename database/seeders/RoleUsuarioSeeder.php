<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');

        $usuarios = Usuario::all();
        $roles = Role::all('id');

        foreach ($usuarios as $usuario) {


                $rol_id = $faker->randomElement($roles);
                $usuario->roles()->attach($rol_id);

        }
    }
}
