<?php

namespace Database\Factories;

use App\Models\Jornada;
use Illuminate\Database\Eloquent\Factories\Factory;

class JornadaFactory extends Factory
{
    protected $model = Jornada::class;

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
    	return [
    	    //
    	];
    }
}
