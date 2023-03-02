<?php

namespace Database\Factories;

use App\Models\Centro;
use Illuminate\Database\Eloquent\Factories\Factory;

class CentroFactory extends Factory
{
    protected $model = Centro::class;


    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
    	return [
    	    'nombre' => $this->faker->unique()->company(),
    	    'cif' => $this->faker->unique()->vat(),
    	    'localidad' => $this->faker->unique()->city(),
    	];
    }
}
