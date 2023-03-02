<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CentroSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(CentroDepartamentoSeeder::class);

        $this->call(UsuarioSeeder::class);
        $this->call(JefeDepartamentoSeeder::class);

        $this->call(RoleSeeder::class);
        $this->call(RoleUsuarioSeeder::class);

        $this->call(MotivoSeeder::class);

        $this->call(TipoJornadaSeeder::class);
        $this->call(JornadaSeeder::class);

    }
}
