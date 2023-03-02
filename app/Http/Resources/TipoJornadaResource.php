<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoJornadaResource extends JsonResource
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
            'id'            => $this->id,
            'nombre'        => $this->nombre,
            'libre'         => $this->libre,
            'personal'      => $this->personal,
            'remunerado'    => $this->remunerado,
            'vacaciones'    => $this->vacaciones,
        ];
    }
}
