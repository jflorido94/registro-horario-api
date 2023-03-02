<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre', 'apellidos', 'email', 'password', 'first_login'
    ];

    /**
     * * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'deleted_at'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();;
    }

    /**
     * * Devuelve el departamento al que pertenece
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class); // a traves de la tabla pivote
    }


    /**
     * * Devuelve el centro al que pertenece
     */
    public function cendep()
    {
        return $this->belongsTo(CentroDepartamento::class); // a traves de la tabla pivote
    }

    /**
     * * Devuelve el Departamento del que es responsable (si lo fuese)
     */
    public function responsabilidad()
    {
        return $this->hasOne(Departamento::class, 'responsable_id');
    }

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function calendario_usuario()
    {
        return $this->hasMany(CalendarioUsuario::class);
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
