<?php

namespace Database\Factories;

use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartamentoFactory extends Factory
{
    protected $model = Departamento::class;

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
    	return [
    	    'nombre' => $this->faker->unique()->domainWord(),
    	];
    }
}
