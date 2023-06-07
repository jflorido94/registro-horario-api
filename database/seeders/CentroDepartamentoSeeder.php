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
            foreach ($departamentos as $departamento) {
                if (!$departamento->centros()->where('centro_id',$centro->id)->exists()) {
                    $centro->departamentos()->attach($departamento->id);
                }
            }
        }
    }
}
