<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JornadaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($data) {
            return [
                'id'                    => $data->id,
                'descripcion'           => $data->descripcion,
                'entrada'               => $data->entrada ? Carbon::parse($data->entrada)->toTimeString('minute') : null,
                'salida'                => $data->salida ? Carbon::parse($data->salida)->toTimeString('minute') : null,
                'total'                 => $data->total ? Carbon::parse($data->total)->toTimeString('minute') : null,
                'tipo_jornada'          => new TipoJornadaResource($data->tipoJornada)
            ];
        });
    }
}
