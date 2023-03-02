<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JornadaResource extends JsonResource
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
            'nombre'                => $this->nombre,
            'descripcion'           => $this->descripcion,
            'entrada'               => $this->entrada?Carbon::parse($this->entrada)->toTimeString('minute'):null,
            'salida'                => $this->salida?Carbon::parse($this->salida)->toTimeString('minute'):null,
            'total'                 => $this->total?Carbon::parse($this->total)->toTimeString('minute'):null,
            'tipo_jornada'          => new TipoJornadaResource($this->tipoJornada)
        ];
    }
}
