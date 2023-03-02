<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

class Departamento extends Model
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre'
        ];

    /**
     * * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [

    ];

    // /**
    //  * * Devuelve todo los usuarios del departamento
    //  */
    // public function usuarios(){
    //     return $this->hasMany(Usuario::class);
    // }

    // /**
    //  * * Devuelve el usuario responsable del departamento checkear
    //  */
    // public function responsables(){
    //     return $this->belongsToMany(Usuario::class);
    // }

    /**
     * * Devuelve los centros con ese departamento
     */
    public function centros(){
        return $this->belongsToMany(Centro::class, 'centro_departamento'); //? como obtener los responsables
    }
}
