<?php

namespace Database\Seeders;

use App\Models\Centro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Centro::factory()->create([
            'nombre' => 'BM S.L.U.',
            'cif' => 'B28241800',
            'localidad' => 'Loeches',
        ]);
        Centro::factory(2)->create();
    }
}
