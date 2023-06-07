<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Departamento::all()->count() == 0) {
            Departamento::factory()->create([
                'nombre' => 'Sistemas',
            ]);

            Departamento::factory()->create([
                'nombre' => 'Personal',
            ]);

            Departamento::factory()->create([
                'nombre' => 'Ingenieria',
            ]);
        }
    }
}
