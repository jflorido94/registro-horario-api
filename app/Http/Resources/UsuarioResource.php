<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $arrayData = [
            'id'                    => $this->id,
            'nombre'                => $this->nombre,
            'apellidos'             => $this->apellidos,
            'email'                 => $this->email,
            // 'departamento'          => $this->departamento->nombre,
            // 'centro_de_trabajo'     => $this->centro->nombre,
            // 'jefe_de_departamento'  => $this->departamento->responsable->nombre." ".$this->departamento->responsable->apellido,
            // 'registro_actual'        => $this->registros->where('dia',Carbon::today())->first(),
        ];
        // if ($this->responsabilidad) {
        //     $arrayData['responsable_de'] = $this->responsabilidad->nombre;
        // }

        return $arrayData;
    }
}
