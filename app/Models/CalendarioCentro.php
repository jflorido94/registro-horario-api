<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class CalendarioCentro extends Model
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'dia', 'jornada_id', 'centro_id'
    ];

    /**
     * * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [];

    public $timestamps = false;

    /**
     * * Devuelve el centro de trabajo con este horario
     */
    public function centro()
    {
        return $this->hasOne(Centro::class);
    }

    /**
     * * Devuelve la jornada de ese dia
     */
    public function jornada()
    {
        return $this->belongsTo(Jornada::class);
    }

    /**
     * * Devuelve la jornada de ese dia
     */
    public function tipoJornada()
    {
        return $this->hasOneThrough(TipoJornada::class, Jornada::class);
    }

    /**
     * * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'dia' => 'datetime:d-m-Y',
    ];

}
