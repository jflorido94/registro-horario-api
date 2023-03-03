<?php

namespace Database\Factories;

use App\Models\Extra;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtraFactory extends Factory
{
    protected $model = Extra::class;

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
    	return [
    	    //
    	];
    }
}
