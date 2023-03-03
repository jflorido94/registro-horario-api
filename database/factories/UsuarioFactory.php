<?php

namespace Database\Factories;

use App\Models\CentroDepartamento;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');


    	return [
    	    'nombre' => $this->faker->firstname(),
    	    'apellidos' => $this->faker->lastName(),
    	    'email' => $this->faker->unique()->companyEmail(),
    	    'password' => app('hash')->make('123456'),
    	    'first_login' => 0,

            'centro_departamento_id' => $this->faker->randomElement(CentroDepartamento::all('id')),

            'remember_token' => Str::random(10),
    	];
    }
}
