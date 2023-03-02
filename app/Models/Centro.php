<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

class Centro extends Model
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre', 'cif', 'localidad'
    ];

    /**
     * * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [

    ];

    /**
     * * Devuelve los departamentos del centro
     */
    public function departamentos(){
        return $this->belongsToMany(Departamento::class);
    }

    /**
     * * Devuelve los usuarios del centro TODO: checkear
     */
    public function usuarios(){
        return $this->hasManyThrough(Usuarios::class,Departamento::class);
    }
}
