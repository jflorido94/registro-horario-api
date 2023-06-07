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


    	return [
    	    'password' => app('hash')->make('123456'),
    	    'first_login' => 1,
            'remember_token' => Str::random(10),
    	];
    }
}
