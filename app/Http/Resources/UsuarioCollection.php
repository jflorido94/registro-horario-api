<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsuarioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  $this->collection->map(function ($data) {
            return [
                'id'                => $data->id,
                'nombre'            => $data->nombre,
                'apellidos'         => $data->apellidos,
                'email'             => $data->email,
                // 'departamento'      => $data->departamento->nombre,
                // 'centro_de_trabajo' => $data->centro->nombre,

            ];
        });
    }
}
