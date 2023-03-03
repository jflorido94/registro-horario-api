<?php

namespace Database\Seeders;

use App\Models\Centro;
use App\Models\Departamento;
use Illuminate\Container\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CentroDepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');

        $centros = Centro::all();
        $departamentos = Departamento::all('id');

        foreach ($centros as $centro) {
            $num_dep = $faker->numberBetween(0,count($departamentos));

            $used = array();

            for ($i = 0; $i < $num_dep; $i++) {
                $dep_id = $faker->randomElement($departamentos);
                while (in_array($dep_id, $used)) {
                    $dep_id = $faker->randomElement($departamentos);
                }
                $centro->departamentos()->attach($dep_id);
                array_push($used, $dep_id);
            }
        }
    }
}
