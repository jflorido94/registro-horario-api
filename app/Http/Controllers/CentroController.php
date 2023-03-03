<?php

namespace App\Http\Controllers;

use App\Http\Resources\CentroCollection;
use App\Http\Resources\CentroResource;
use App\Models\Centro;
use App\Models\Departamento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CentroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(new CentroCollection(Centro::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if ($request->user()->departamento->name != "Sistemas") {
        //     return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        // }

        $rules = array(
            'nombre' => 'required',
            'cif' => 'required',
            'localidad' => 'required'
        );

        $messages = array(
            'nombre.required' => 'Por favor introduzca el nombre del centro',
            'cif.required' => 'Por favor introduzca el CIF del centro',
            'localidad.required' => 'Por favor introduzca la localidad del centro',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje' => $mensaje], 406);
        }

        if (Centro::create($request->all())) {
            return response()->json(['mensaje' => 'Centro creado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la creacion del centro'], 500);
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
        if (!$item = Centro::find($id)) {
            return response()->json(['mensaje' => 'Centro no encontrado'], 404);
        }
        return response()->json(new CentroResource($item));
    }

    /**
     *
     */
    public function addDepartamento($id, Request $request)
    {
        if (!$item = Centro::find($id)) {
            return response()->json(['mensaje' => 'Centro no encontrado'], 404);
        }

        foreach ($request->departamentos as $departamento) {
            if (!$dep = Departamento::find($departamento)) {
                return response()->json(['mensaje' => 'Departamento no encontrado'], 404);
            }
        }
        $item->departamentos()->sync($request->departamentos);
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
        if (!$item = Centro::find($id)) {
            return response()->json(['mensaje' => 'Centro no encontrado'], 404);
        }

        if ($request->user()->centro()->id != $item->id || $request->user()->departamento->name != "Personal") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }


        $item->fill($request->all());


        if ($item->save()) {
            return response()->json(['mensaje' => 'Centro editado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la edicion del centro'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$item = Centro::find($id)) {
            return response()->json(['mensaje' => 'Centro no encontrado'], 404);
        }

        $user= Usuario::find(Auth::id());

        // TODO: cambiar "Personal" por depart.id where name == Personal and centro_id == user.centro.id
        if ($user->centro()->id != $item->id || $user->departamento->name != "Personal") {
            return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
        }

        if ($item->delete()) {
            return response()->json(['mensaje' => 'Centro eliminado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la eliminacion del centro'], 500);
        }
    }
}
