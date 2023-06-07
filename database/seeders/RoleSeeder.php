<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Role::all()->count() == 0) {

            Role::create(
                ['nombre' => 'Admin']
            );
            Role::create(
                ['nombre' => 'Empleado']
            );
            Role::create(
                ['nombre' => 'Sistemas']
            );
            Role::create(
                ['nombre' => 'Administracion']
            );
        }
    }
}
