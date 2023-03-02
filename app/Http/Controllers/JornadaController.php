<?php

namespace App\Http\Controllers;

use App\Http\Resources\JornadaCollection;
use App\Models\Jornada;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JornadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(new JornadaCollection(Jornada::all()));
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
            'descripcion' => 'required',
            'tipo_jor_id' => 'required|exists:tipo_jors,id',
        );

        $messages = array(
            'name.required' => 'Por favor introduzca un nombre para la jornada',
            'descripcion.required' => 'Por favor introduzca una descripcion para la jornada',
            'tipo_jor_id.required' => 'Debe indicar a que tipo de jornada pertenece',
            'tipo_jor_id.exists' => 'El tipo de jornada indicado no existe',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje' => $mensaje], 406);
        }

        if (Jornada::create($request->all())) {
            return response()->json(['mensaje' => 'Jornada creada correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la creacion de la jornada'], 500);
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
        if (!$item = Jornada::find($id)) {
            return response()->json(['mensaje' => 'Jornada no encontrada'], 404);
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
        if (!$item = Jornada::find($id)) {
            return response()->json(['mensaje' => 'Jornada no encontrada'], 404);
        }

        if ($request->user()->departamento->name != "Sistemas") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }


        $item->fill($request->all());


        if ($item->save()) {
            return response()->json(['mensaje' => 'Jornada editada correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la edicion de la Jornada'], 500);
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
        if (!$item = Jornada::find($id)) {
            return response()->json(['mensaje' => 'Jornada no encontrada'], 404);
        }

        $user = Usuario::find(Auth::id());

        // TODO: cambiar "Personal" por depart.id where name == Personal and centro_id == user.centro.id
        if ( $user->departamento->name != "Sistemas") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }

        if ($item->delete()) {
            return response()->json(['mensaje' => 'Jornada eliminada correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la eliminacion de la Jornada'], 500);
        }
    }
}
