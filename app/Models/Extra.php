<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Extra extends Model
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'inicio', 'fin', 'motivo_id', 'registro_id'
    ];

    /**
     * * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [];

    public function motivo()
    {
        return $this->belongsTo(Motivo::class);
    }

    public function registro()
    {
        return $this->belongsTo(Registro::class);
    }
}
