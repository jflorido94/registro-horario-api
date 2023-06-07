<?php

namespace Database\Seeders;

use App\Models\Centro;
use App\Models\Departamento;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (Usuario::all()->count() == 0) {


            $user = Usuario::factory()->create([
                'nombre' => 'Javier',
                'apellidos' => 'Florido Pavon',
                'email' => 'jflorido@bmsl.es',
                'password' => app('hash')->make('147258'),
                'centro_departamento_id' => (Centro::where('localidad', 'Loeches')->first())
                    ->departamentos()
                    ->wherePivot('departamento_id', (Departamento::where('nombre', 'Sistemas')->first())->id)
                    ->pluck('centro_departamento.id')
                    ->first()
            ]);
            $user->roles()->attach(Role::where('nombre', 'Admin'));


            //***************
            $user = Usuario::factory()->create([
                'nombre' => 'Francisco Javier',
                'apellidos' => 'Ruperez Sanchez',
                'email' => 'jruperez@bmsl.es',
                'password' => app('hash')->make('jruperez'),
                'centro_departamento_id' => (Centro::where('localidad', 'Loeches')->first())
                    ->departamentos()
                    ->wherePivot('departamento_id', (Departamento::where('nombre', 'Sistemas')->first())->id)
                    ->pluck('centro_departamento.id')
                    ->first()
            ]);
            $user->roles()->attach(Role::where('nombre', 'Sistemas'));


            //***************
            $user = Usuario::factory()->create([
                'nombre' => 'Angel',
                'apellidos' => 'Sanchez Martin',
                'email' => 'asanchez@bmsl.es',
                'password' => app('hash')->make('asanchez'),
                'centro_departamento_id' => (Centro::where('localidad', 'Loeches')->first())
                    ->departamentos()
                    ->wherePivot('departamento_id', (Departamento::where('nombre', 'Sistemas')->first())->id)
                    ->pluck('centro_departamento.id')
                    ->first()
            ]);
            (Centro::where('localidad', 'Loeches')->first())->departamentos()->updateExistingPivot(
                (Departamento::where('nombre', 'Sistemas')->first()),
                ['usuario_id' => $user->id]
            );
            $user->roles()->attach(Role::where('nombre', 'Sistema'));



            //***************
            $user = Usuario::factory()->create([
                'nombre' => 'Fernando',
                'apellidos' => 'Alcalde-Moraño',
                'email' => 'falcalde@bmsl.es',
                'password' => app('hash')->make('falcalde'),
                'centro_departamento_id' => (Centro::where('localidad', 'Loeches')->first())
                    ->departamentos()
                    ->wherePivot('departamento_id', (Departamento::where('nombre', 'Personal')->first())->id)
                    ->pluck('centro_departamento.id')
                    ->first()
            ]);
            (Centro::where('localidad', 'Loeches')->first())->departamentos()->updateExistingPivot(
                (Departamento::where('nombre', 'Personal')->first()),
                ['usuario_id' => $user->id]
            );
            $user->roles()->attach(Role::where('nombre','Administracion'));


            //***************
            $user = Usuario::factory()->create([
                'nombre' => 'Ismael',
                'apellidos' => 'Gonzalez',
                'email' => 'igonzalez@bmsl.es',
                'password' => app('hash')->make('igonzalez'),
                'centro_departamento_id' => (Centro::where('localidad', 'Loeches')->first())
                    ->departamentos()
                    ->wherePivot('departamento_id', (Departamento::where('nombre', 'Sistemas')->first())->id)
                    ->pluck('centro_departamento.id')
                    ->first()
            ]);
            $user->roles()->attach(Role::where('nombre','Empleado'));


            //***************
            $user = Usuario::factory()->create([
                'nombre' => 'Angel Luis',
                'apellidos' => 'Sanz',
                'email' => 'asanz@bmsl.es',
                'password' => app('hash')->make('asanz'),
                'centro_departamento_id' => (Centro::where('localidad', 'Loeches')->first())
                    ->departamentos()
                    ->wherePivot('departamento_id', (Departamento::where('nombre', 'Sistemas')->first())->id)
                    ->pluck('centro_departamento.id')
                    ->first()
            ]);
            $user->roles()->attach(Role::where('nombre','Empleado'));


            //***************
            $user = Usuario::factory()->create([
                'nombre' => 'Roberto',
                'apellidos' => 'Ramiro',
                'email' => 'rramiro@bmsl.es',
                'password' => app('hash')->make('rramiro'),
                'centro_departamento_id' => (Centro::where('localidad', 'Loeches')->first())
                    ->departamentos()
                    ->wherePivot('departamento_id', (Departamento::where('nombre', 'Ingenieria')->first())->id)
                    ->pluck('centro_departamento.id')
                    ->first()
            ]);
            (Centro::where('localidad', 'Loeches')->first())->departamentos()->updateExistingPivot(
                (Departamento::where('nombre', 'Ingenieria')->first()),
                ['usuario_id' => $user->id]
            );
            $user->roles()->attach(Role::where('nombre','Empleado'));


            //***************
        }
    }
}
