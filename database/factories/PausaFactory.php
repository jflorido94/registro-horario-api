<?php

namespace Database\Factories;

use App\Models\Pausa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PausaFactory extends Factory
{
    protected $model = Pausa::class;

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
    	return [
    	    //
    	];
    }
}
