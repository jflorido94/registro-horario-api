<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\TipoJornada;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TipoJornadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(TipoJornada::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->departamento->name != "Sistemas") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }

        $rules = array(
            'name' => 'required',
            'vacaciones' => 'required',
            'personal' => 'required',
            'remunerado' => 'required',
            'libre' => 'required'
        );

        $messages = array(
            'name.required' => 'Por favor introduzca un nombre para el tipo de jornada',
            'vacaciones.required' => 'Olvidó enviar el campo vacaciones',
            'personal.required' => 'Olvidó enviar el campo personal',
            'remunerado.required' => 'Olvidó enviar el campo remunerado',
            'libre.required' => 'Olvidó enviar el campo libre',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje' => $mensaje], 406);
        }

        if (Registro::create($request->all())) {
            return response()->json(['mensaje' => 'Tipo de jornada creado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la creacion del tipo de jornada'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$item = Registro::find($id)) {
            return response()->json(['mensaje' => 'Tipo de jornada no encontrado'], 404);
        }
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$item = Registro::find($id)) {
            return response()->json(['mensaje' => 'Tipo de jornada no encontrado'], 404);
        }

        if ($request->user()->centro()->id != $item->id || $request->user()->departamento->name != "Sistemas") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }


        $item->fill($request->all());


        if ($item->save()) {
            return response()->json(['mensaje' => 'Tipo de jornada editado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la edicion del tipo de jornada'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoJor  $TipoJor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$item = Registro::find($id)) {
            return response()->json(['mensaje' => 'Tipo de jornada no encontrado'], 404);
        }

        $user = Usuario::find(Auth::id());

        // TODO: cambiar "Personal" por depart.id where name == Personal and centro_id == user.centro.id
        if ( $user->departamento->name != "Sistemas") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }

        if ($item->delete()) {
            return response()->json(['mensaje' => 'Tipo de jornada eliminado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la eliminacion del tipo de jornada'], 500);
        }
    }
}
