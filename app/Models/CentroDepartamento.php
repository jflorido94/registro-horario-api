<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CentroDepartamento extends Model
{
    protected $table = 'centro_departamento';

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
    public function centro()
    {
        return $this->belongsTo(Centro::class); // a traves de la tabla pivote
    }
}
