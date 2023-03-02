<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CentroCollection extends ResourceCollection
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
                'id'                    => $data->id,
                'nombre'                => $data->nombre,
                'localidad'             => $data->localidad,
                //TODO Añadir ruta de la api para cada recurso en las colecciones
                // '_links'                => [
                //     'self'                  => [
                //         'href'                  => route('centro_s', ['id' => $data->id]),
                //         'type'                  => 'GET'
                //     ],
                //     'update'                => [
                //         'href'                  => route('centro_u', ['id' => $data->id]),
                //         'type'                  => 'POST'
                //     ],
                //     'departamentos'         => [
                //         'href'                  => route('departamento_l'),
                //         'type'                  => 'GET'
                //     ],
                //     'empleados'             => [
                //         'href'                  => route('user_l'),
                //         'type'                  => 'GET'
                //     ],
                // ]
            ];
        });
    }
}
