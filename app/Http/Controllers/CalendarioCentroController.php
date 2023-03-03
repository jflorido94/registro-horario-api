<?php

namespace App\Http\Controllers;

use App\Models\CalendarioCentro;
use App\Models\Usuario;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarioCentroController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return response()->json(CalendarioCentro::all());
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
      'centro_id' => 'required|exists:centro,id',
      'departamento_id' => 'required|exists:departamento,id',
      'turno' => 'required',
      'year' => 'required',
    );

    $messages = array(
      'dia.required' => 'Por favor introduzca un dia',
      'jornada_id.required' => 'Debe indicar a una jornada',
      'jornada_id.exists' => 'La jornada indicada no existe',
      'centro_id.required' => 'Debe indicar el centro',
      'centro_id.exists' => 'El centro indicado no existe',
      'departamento_id.required' => 'Debe indicar el departamento',
      'departamento_id.exists' => 'El departamento indicado no existe',
      'turno.required' => 'Por favor introduzca el turno',
      'year.required' => 'Por favor introduzca el año',
    );

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      $mensaje = $validator->messages();
      return response()->json(['mensaje' => $mensaje], 406);
    }

    if (CalendarioCentro::create($request->all())) {
      return response()->json(['mensaje' => 'Calendario creado correctamente'], 201);
    } else {
      return response()->json(['mensaje' => 'Ocurrió un error durante la creacion del calendario'], 500);
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
    if (!$item = CalendarioCentro::find($id)) {
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
    if (!$item = CalendarioCentro::find($id)) {
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
    if (!$item = CalendarioCentro::find($id)) {
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

  public function generar(Request $request)
  {
    // if ($request->user()->departamento->name != "Sistemas") {
    //     return response()->json(['mensaje' => 'No tienes permiso para realizar esa accion'], 403);
    // }

    $rules = array(
      'inicio' => 'required',
      'fin' => 'required'
    );

    $messages = array(
      'inicio.required' => 'Por favor introduzca un dia',
      'fin.required' => 'Debe indicar a una jornada',
    );

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      $mensaje = $validator->messages();
      return response()->json(['mensaje' => $mensaje], 406);
    }

    dump($request);

    $fecha_ini = Carbon::createFromFormat('d/m/Y', $request->input('inicio'));
    $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->input('fin'));
    $rango = CarbonPeriod::create($fecha_ini, $fecha_fin);
    $ndias = 0;

    foreach ($rango as $day) {
      $next_day = $day->copy()->addDay();
      $send = [
        'dia' => Carbon::parse($day)->toDateString(),
        'jornada_id' => $day->isWeekend() ? 3 : ($next_day->isWeekend() ? 2 : 1),
        'centro_id' => 1,
      ];

    //   dd($send);
      CalendarioCentro::updateOrCreate(
        [
          'dia' => $send['dia'],
          'centro_id' => $send['centro_id']
        ],
        [
          'jornada_id' => $send['jornada_id']
        ]
      );
      $ndias++;
      // CalendarioCentro::create($send);
    }

    return response()->json(['mensaje' => 'Calendario generado correctamente ' . $ndias . ' dias'], 201);
  }

  // ? Puedo unificar que calendario es en una tabla y los dias pertenecer a dicho calendario
  // ? Tambien puedo hacer clave compuesta el dia (con el año) y el calendario,
  // ? para conseguir que no se repitan fechas para un mismo calendario.
}
