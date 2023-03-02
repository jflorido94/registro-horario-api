<?php

namespace Database\Factories;

use App\Models\Motivo;
use Illuminate\Database\Eloquent\Factories\Factory;

class MotivoFactory extends Factory
{
    protected $model = Motivo::class;

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
    	return [
    	    //
    	];
    }
}
