<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
  /**
   * * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'dia', 'usuario_id', 'entrada', 'salida', 'total', 'is_real', 'comentario'
  ];

  /**
   * * The attributes excluded from the model's JSON form.
   *
   * @var string[]
   */
  protected $hidden = [];

  /**
   * * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'dia' => 'datetime:Y-m-d',
    'entrada' => 'datetime:H:i',
    'salida' => 'datetime:H:i',
    'total' => 'datetime:H:i',
  ];

  public function usuario()
  {
    return $this->belongsTo(Usuario::class);
  }

  public function extras()
  {
    return $this->hasMany(Extra::class)->latest();
  }


  public function pausas()
  {
    return $this->hasMany(Pausa::class)->latest();
  }
}
