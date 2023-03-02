<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RegistroCollection extends ResourceCollection
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
                'dia'                   => Carbon::parse($data->dia)->toDateString(),
                'entrada'               => $data->entrada?Carbon::parse($data->entrada)->toTimeString('minute'):null,
                'comentarioEntrada'     => $data->comentarioEntrada,
                'salida'                => $data->salida?Carbon::parse($data->salida)->toTimeString('minute'):null,
                'comentarioSalida'      => $data->comentarioSalida,
                'total'                 => $data->total?Carbon::parse($data->total)->toTimeString('minute'):null,
                'is_real'               => $data->is_real,
                'usuario'               => new UsuarioResource($data->usuario),
                'pausas'                => $data->pausas->count()?new PausaCollection($data->pausas):['total' => '00:00', 'data' => []],
                'extras'                => $data->extras->count()?new ExtraCollection($data->extras):['total' => '00:00', 'data' => []],
                // 'is_activa'             => !($data->salida) && ($data->entrada)
            ];
        });
    }
}
