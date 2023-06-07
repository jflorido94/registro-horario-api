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
        if (Centro::all()->count()==0) {
            Centro::factory()->create([
                'nombre' => 'BM S.L.U.',
                'cif' => 'B28241800',
                'localidad' => 'Loeches',
            ]);
        }
    }
}
