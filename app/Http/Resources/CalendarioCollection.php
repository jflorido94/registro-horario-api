<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CalendarioCollection extends ResourceCollection
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
                'title'                    => $data->jornada->nombre,
                'start'                 => Carbon::parse($data->dia)->toDateString(),
                'allDay'                => 'true',
                'display'               => 'background',
                // 'textColor'             => 'black',

                'backgroundColor'       => $data->jornada->nombre=='Intensiva'?'lightgray':($data->jornada->tipoJornada->vacaciones?'yellow':($data->jornada->tipoJornada->libre?'coral':'white')),
                'jornada'               => new JornadaResource($data->jornada)
            ];
        });
    }
}
