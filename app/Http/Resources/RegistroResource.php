<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistroResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'dia'                   => Carbon::parse($this->dia)->toDateString(),
            'entrada'               => $this->entrada?Carbon::parse($this->entrada)->toTimeString('minute'):null,
            'comentarioEntrada'     => $this->comentarioEntrada,
            'salida'                => $this->salida?Carbon::parse($this->salida)->toTimeString('minute'):null,
            'comentarioSalida'      => $this->comentarioSalida,
            'total'                 => $this->total?Carbon::parse($this->total)->toTimeString('minute'):null,
            'is_real'               => $this->is_real,
            'usuario'               => new UsuarioResource($this->usuario),
            'pausas'                => $this->pausas->count()?new PausaCollection($this->pausas):['total' => '00:00', 'data' => []],
            'extras'                => $this->extras->count()?new ExtraCollection($this->extras):['total' => '00:00', 'data' => []],
            // 'is_activa'             => !($this->salida) && ($this->entrada)
        ];
    }
}
