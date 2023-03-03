<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartamentoResource;
use App\Models\Centro;
use App\Models\Departamento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(DepartamentoResource::collection(Departamento::all()));
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
            'nombre' => 'required'
        );

        $messages = array(
            'nombre.required' => 'Por favor introduzca el nombre del departamento',
        );

        $validator = Validator::make($request->all(), $rules, $messages );

        if ($validator -> fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje'=>$mensaje], 406);
        }

        $departamento = new Departamento();

        $departamento->nombre = $request->input('nombre');

        if ($departamento->save()) {
            return response()->json(['mensaje' => 'Departamento creado correctamente'], 201);
        }else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la creacion del departamento'], 500);
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
        if (! $item = Departamento::find($id)){
            return response()->json(['mensaje' => 'Departamento no encontrado'], 404);
        }
        return response()->json(new DepartamentoResource($item));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$item = Departamento::find($id)) {
            return response()->json(['mensaje' => 'Departamento no encontrado'], 404);
        }

        if ($request->user()->centro()->id != $item->centro->id || $request->user()->departamento->name != "Personal") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }


        $item->fill($request->all());


        if ($item->save()) {
            return response()->json(['mensaje' => 'Departamento editado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la edicion del departamento'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$item = Departamento::find($id)) {
            return response()->json(['mensaje' => 'Departamento no encontrado'], 404);
        }

        $user= Usuario::find(Auth::id());

        // TODO: cambiar "Personal" por depart.id where name == Personal and centro_id == user.centro.id
        if ($user->centro()->id != $item->centro->id || $user->departamento->name != "Personal") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }

        if ($item->delete()) {
            return response()->json(['mensaje' => 'Departamento eliminado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la eliminacion del departamento'], 500);
        }
    }
}
