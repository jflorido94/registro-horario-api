<?php

namespace App\Http\Controllers;

use App\Models\Motivo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MotivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Motivo::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if ($request->user()->departamento->name != "Personal") {
        //     return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        // }

        $rules = array(
            'nombre' => 'required',
            'descripcion' => 'required',
            'is_pausa' => 'required',
        );

        $messages = array(
            'nombre.required' => 'Por favor introduzca el nombre del motivo',
            'descripcion.required' => 'Por favor introduzca una descripcion del motivo',
            'nombre.required' => 'Por favor introduzca el nombre del motivo',
        );

        $validator = Validator::make($request->all(), $rules, $messages );

        if ($validator -> fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje'=>$mensaje], 406);
        }

        $motivo = new Motivo();

        $motivo->nombre = $request->input('nombre');

        if ($motivo->save()) {
            return response()->json(['mensaje' => 'Motivo creado correctamente'], 201);
        }else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la creacion del motivo'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $item = Motivo::find($id)){
            return response()->json(['mensaje' => 'Motivo no encontrado'], 404);
        }
        return $item;
        // return response()->json(new MotivoResource($item));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Motivo  $motivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$item = Motivo::find($id)) {
            return response()->json(['mensaje' => 'Motivo no encontrado'], 404);
        }

        if ($request->user()->centro()->id != $item->centro->id || $request->user()->departamento->name != "Personal") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }


        $item->fill($request->all());


        if ($item->save()) {
            return response()->json(['mensaje' => 'Motivo editado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la edicion del motivo'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Motivo  $motivo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$item = Motivo::find($id)) {
            return response()->json(['mensaje' => 'Motivo no encontrado'], 404);
        }

        $user= Usuario::find(Auth::id());

        // TODO: cambiar "Personal" por depart.id where name == Personal and centro_id == user.centro.id
        if ($user->centro()->id != $item->centro->id || $user->departamento->name != "Personal") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }

        if ($item->delete()) {
            return response()->json(['mensaje' => 'Motivo eliminado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la eliminacion del motivo'], 500);
        }
    }
}
