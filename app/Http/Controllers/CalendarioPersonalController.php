<?php

namespace App\Http\Controllers;

use App\Models\CalendarioPersonal;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarioPersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(CalendarioPersonal::all());
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
            'dia' => 'required',
            'jornada_id' => 'required|exists:jornada,id',
            'user_id' => 'required|exists:user,id',
            'year' => 'required',
        );

        $messages = array(
            'dia.required' => 'Por favor introduzca un dia',
            'jornada_id.required' => 'Debe indicar a una jornada',
            'jornada_id.exists' => 'La jornada indicada no existe',
            'user_id.required' => 'Debe indicar el usuario',
            'user_id.exists' => 'El usuario indicado no existe',
            'year.required' => 'Por favor introduzca el año',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje' => $mensaje], 406);
        }

        if (CalendarioPersonal::UpdateOrCreate(
            [
                'user_id' => $request->input('user_id'),
                'dia' => $request->input('dia'),
                'year' => $request->input('year'),
            ],
            [
                'jornada_id' => $request->input('jornada_id'),
            ]
        )) {
            return response()->json(['mensaje' => 'Calendario editado correctamente'], 201); //? ????
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la edicion del calendario'], 500);
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
        if (!$item = CalendarioPersonal::find($id)) {
            return response()->json(['mensaje' => 'Calendario no encontrado'], 404);
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
        if (!$item = CalendarioPersonal::find($id)) {
            return response()->json(['mensaje' => 'Calendario no encontrado'], 404);
        }

        if ($request->user()->departamento->name != "Sistemas") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }


        $item->fill($request->all());


        if ($item->save()) {
            return response()->json(['mensaje' => 'Calendario editado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la edicion del Calendario'], 500);
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
        if (!$item = CalendarioPersonal::find($id)) {
            return response()->json(['mensaje' => 'Calendario no encontrado'], 404);
        }

        $user = Usuario::find(Auth::id());

        // TODO: cambiar "Personal" por depart.id where name == Personal and centro_id == user.centro.id
        if ($user->departamento->name != "Sistemas") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }

        if ($item->delete()) {
            return response()->json(['mensaje' => 'Calendario eliminado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la eliminacion del Calendario'], 500);
        }
    }
}
