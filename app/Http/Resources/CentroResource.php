<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CentroResource extends JsonResource
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
            'CIF'                   => $this->cif,
            'localidad'             => $this->localidad,
            'departamentos'         => new DepartamentoCollection($this->departamentos),
            // 'trabajadores'          => new UserCollection($this->usuarios),

        ];
    }
}
