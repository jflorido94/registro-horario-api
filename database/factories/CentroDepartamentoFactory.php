<?php

namespace Database\Factories;

use App\Models\CentroDepartamento;
use App\Models\Centro;
use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class CentroDepartamentoFactory extends Factory
{
    protected $model = CentroDepartamento::class;

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
    	return [
    	    //
    	];
    }
}
