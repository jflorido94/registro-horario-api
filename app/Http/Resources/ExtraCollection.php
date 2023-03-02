<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExtraCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        date_default_timezone_set('UTC');
        return  [
            'total' => date('H:i', $this->collection->sum(function ($value) {
                return strtotime($value->total);
            })),
            'data'  => $this->collection->map(function ($data) {
                return [
                    'id'        => $data->id,
                    'inicio'    => $data->inicio,
                    'fin'       => $data->fin,
                    'total'     => $data->total,
                    'motivo'    => $data->motivo,
                ];
            }),
        ];
    }
}
