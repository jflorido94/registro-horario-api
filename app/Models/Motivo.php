<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Motivo extends Model
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'is_pausa',
    ];

    /**
     * * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [];


    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    public function pausas()
    {
        return $this->hasMany(Pausa::class);
    }
}
