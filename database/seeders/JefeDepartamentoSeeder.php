<?php

namespace Database\Seeders;

use App\Models\CentroDepartamento;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JefeDepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');

        $centro_departamentos = CentroDepartamento::all();
        $usuarios = Usuario::all('id');

        $used = array();
        foreach ($centro_departamentos as $cd) {


                $usu_id = $faker->randomElement($usuarios);
                while (in_array($usu_id, $used)) {
                    $usu_id = $faker->randomElement($usuarios);
                }
                $cd->usuario_id = $usu_id['id'];

                $cd->save();
                array_push($used, $usu_id);
        }
    }
}
