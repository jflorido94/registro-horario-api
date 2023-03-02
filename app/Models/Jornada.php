<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Jornada extends Model
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre', 'descripcion', 'entrada', 'salida',  'total', 'tipo_jornada_id'
    ];

    /**
     * * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [];

    /**
     * * Devuelve la jornada de ese dia
     */
    public function tipoJornada()
    {
        return $this->belongsTo(TipoJornada::class);
    }

    /**
     * * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entrada' => 'datetime:H:i',
        'salida' => 'datetime:H:i',
        'total' => 'datetime:H:i',
    ];
}
