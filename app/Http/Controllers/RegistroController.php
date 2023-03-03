<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalendarioCollection;
use App\Http\Resources\JornadaResource;
use App\Http\Resources\RegistroCollection;
use App\Http\Resources\RegistroResource;
use App\Models\CalendarioCentro;
use App\Models\CalendarioUsuario;
use App\Models\CentroDepartamento;
use App\Models\Extra;
use App\Models\Jornada;
use App\Models\Motivo;
use App\Models\Pausa;
use App\Models\Registro;
use App\Models\TipoJornada;
use App\Models\Usuario;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class RegistroController extends Controller
{
    public function getCalendar()
    {
        return response()->json(new CalendarioCollection(CalendarioCentro::all()));
    }

    public function getToday()
    {

        $user = Usuario::find(Auth::id());
        // dd($user->cendep()->centro());
        $today = Carbon::now()->toDateString();


        if (!($calendario =  CalendarioUsuario::where('dia', $today)->where('usuario_id', $user->id)->first())) {
            $calendario = CalendarioCentro::where('dia', $today)->where('centro_id', 1)->first();
        }

        $jornada = Jornada::with('tipoJornada')->find($calendario['jornada_id']);

        $registro = Registro::where('dia', $today)->where('usuario_id', $user->id)->first();

        return response()->json(['registro' => $registro ? new RegistroResource($registro) : null, 'jornada' => new JornadaResource($jornada)]);
    }

    private function sumar(Collection $times, String $campo)
    {
        date_default_timezone_set('UTC');
        $total = strtotime('00:00:00');

        foreach ($times as $time) {
            $sum = strtotime($time->$campo);
            $total = $total + $sum;
        }

        return $total;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = Usuario::find(Auth::id());

        return response()->json(new RegistroCollection(Registro::where('usuario_id', Auth::id())->orderBy('dia', 'desc')->get()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$item = Registro::where([
            ['usuario_id', $id],
            ['dia', $request->input('dia')]
        ])->get()) {
            return response()->json(['mensaje' => 'Dia no encontrado'], 404);
        }
        return response()->json($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function showById($id)
    {
        if (!$item = Registro::find($id)) {
            return response()->json(['mensaje' => 'Dia no encontrado'], 404);
        }
        return response()->json($item);
    }

    /**
     * Store or Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if (is_null($id)) {
        $id = Auth::id();
        // }

        $registro = Registro::updateOrCreate(
            [
                'usuario_id' => $id,
                'dia' => $request->input('dia'),
            ],
            [
                //TODO: If null
                'entrada' => $request->input('entrada'),
                'salida' => $request->input('salida')
            ]
        );

        $pausaNueva = $request->input('pausas');
        $pausaAntigua = date('H:i', $this->sumar($registro->pausas,'total'));

        if ($pausaNueva != $pausaAntigua) {
            // Borrar las pausas antiguas
            $registro->pausas()->delete();

            // Añadir la nueva pausa
            $pausa = new Pausa();
            $pausa->inicio = '00:00';
            $pausa->total = $pausaNueva;
            $registro->pausas()->save($pausa);

            $pausaAntigua = $pausaNueva;

            $registro->is_real = false;
        }

        $extraNueva = $request->input('extras');
        $extraAntigua = date('H:i', $this->sumar($registro->extras,'total'));

        if ($extraNueva != $extraAntigua) {
            // Borrar las extras antiguas
            $registro->extras()->delete();

            // Añadir la nueva extra
            $extra = new Extra();
            $extra->inicio = '00:00';
            $extra->total = $extraNueva;
            $registro->extras()->save($extra);

            $extraAntigua = $extraNueva;

            $registro->is_real = false;
        }

        //Calcular total
        $total = Carbon::today();
        $total = $total->add($registro->salida->diffAsCarbonInterval($registro->entrada));
        $total = $total->add(CarbonInterval::createFromFormat('H:i', $extraAntigua));
        $total = $total->sub(CarbonInterval::createFromFormat('H:i', $pausaAntigua));
        $registro->total =  $total;


        if ($registro->save()) {
            return response()->json(['message' => 'Dia editado correctamente'], 201);
        } else {
            return response()->json(['message' => 'Ocurrió un error durante la edicion del dia'], 500);
        }
    }

    /**
     * Start the day of the user loged
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function startDay(Request $request)
    {
        if (!($dia = Registro::firstOrNew(
            [
                'usuario_id' => Auth::id(),
                'dia' => Carbon::now()->isoFormat('YYYY/MM/DD'),
            ]
        )) || $dia->entrada) {
            return response()->json(['mensaje' => 'Jornada ya comenzada (ERROR LOGICO)'], 403);
        };

        if (($dia->pausas()->latest()->first())) {
            return response()->json(['mensaje' => 'Jornada en pausa (ERROR LOGICO)'], 500);
        } elseif (($dia->extras()->latest()->first())) {
            return response()->json(['mensaje' => 'No puedes iniciar la jornada mientras estes haciendo horas extras'], 500);
        } elseif (($dia->salida)) {
            return response()->json(['mensaje' => 'Jornada finalizada (ERROR LOGICO)'], 500);
        } else {
            $dia->entrada = $request->input('hora') ? Carbon::createFromFormat('H:i', $request->input('hora')) : Carbon::now();
            if (!empty($request->input('comentario'))) {
                $dia->comentarioEntrada = $request->input('comentario');
            }
        }

        if ($dia->save()) {
            return response()->json(['mensaje' => 'Dia iniciado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error al iniciar el dia'], 500);
        }
    }


    /**
     * End the day of the user loged
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endDay(Request $request)
    {
        if (!($dia = Registro::where('usuario_id', Auth::id())
            ->where('dia', Carbon::today())
            ->first()
        ) || !$dia->entrada) {
            return response()->json(['mensaje' => 'Jornada no iniciada'], 403);
        }
        if ($dia->salida) {
            return response()->json(['mensaje' => 'Jornada ya finalizada'], 403);
        } elseif ($dia->extras()->latest()->first()) {
            return response()->json(['mensaje' => 'Realizando horas extra, por favor termine las horas extras antes'], 403);
        } elseif ($dia->pausas()->latest()->first() && !($dia->pausas()->latest()->first()->fin)) {
            return response()->json(['mensaje' => 'Jornada en pausa, por favor termine la pausa antes'], 403);
        } else {
            $dia->salida = $request->input('hora') ? Carbon::createFromFormat('H:i', $request->input('hora')) : Carbon::now();
            $total = $dia->total ? Carbon::createFromFormat('H:i', $dia->total) : Carbon::today();
            $dia->is_real = true;

            $pausas = date('H:i', $this->sumar($dia->pausas, 'total'));
            $extras = date('H:i', $this->sumar($dia->extras, 'total'));

            date_default_timezone_set('Europe/Madrid');

            // dump($total);
            $total = $total->add($dia->salida->diffAsCarbonInterval($dia->entrada));
            // dump($total);
            // dump($extras = CarbonInterval::createFromFormat('H:i',$extras));
            $total = $total->add($extras = CarbonInterval::createFromFormat('H:i', $extras));
            // dump($total);
            // dump($pausas = CarbonInterval::createFromFormat('H:i',$pausas));
            $total = $total->sub($pausas = CarbonInterval::createFromFormat('H:i', $pausas));
            // dump($total);

            $dia->total = $total;
            if (!empty($request->input('comentario'))) {
                $dia->comentarioSalida = $request->input('comentario');
            }
        }

        // dump($dia);
        if ($dia->save()) {
            return response()->json(['mensaje' => 'Dia finalizado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error al finalizar el dia'], 500);
        }
    }

    /**
     * Start the extra time of the user loged
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function startExtra(Request $request)
    {
        Validator::make($request->all(), [
            'motivo' => 'required|exists:motivos,id'
        ])->validate();

        if (!($dia = Registro::firstOrNew(
            [
                'usuario_id' => Auth::id(),
                'dia' => Carbon::now()->isoFormat('YYYY/MM/DD'),
            ]
        )) || ($dia->extras()->latest()->first() && !($dia->extras()->latest()->first()->fin))) {
            return response()->json(['mensaje' => 'Horas extras ya comenzadas (ERROR LOGICO)'], 403);
        }
        if (($dia->entrada) && !($dia->salida)) {
            return response()->json(['mensaje' => 'Jornada activa (ERROR LOGICO)'], 500);
        } elseif ($dia->pausas()->latest()->first() && !($dia->pausas()->latest()->first()->fin)) {
            return response()->json(['mensaje' => 'Jornada en pausa (ERROR LOGICO)'], 500);
        }
        // else {

        // }
        if ($dia->save() && $dia->extras()->create([
            'inicio' => $request->input('hora') ? Carbon::createFromFormat('H:i', $request->input('hora')) : Carbon::now(),
            'motivo_id' => $request->input('motivo')
        ])) {
            return response()->json(['mensaje' => 'Horas extras iniciadas correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error al iniciar las horas extras'], 500);
        }
    }

    /**
     * The user logedback from the last extra started
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endExtra(Request $request)
    {
        if (!($dia = Registro::where('usuario_id', Auth::id())
            ->where('dia', Carbon::today())
            ->first()
        ) || (!($extra = $dia->extras()->latest()->first()) || ($extra->fin))) {
            return response()->json(['mensaje' => 'Horas extra no iniciadas'], 403);
        }

        if ($dia->entrada && !$dia->salida) {
            return response()->json(['mensaje' => 'Jornada activa (ERROR LOGICO)'], 403);
        } elseif ($dia->pausas()->latest()->first() && !($dia->pausas()->latest()->first()->fin)) {
            return response()->json(['mensaje' => 'Jornada en pausa (ERROR LOGICO)'], 403);
        } else {
            $hora = $request->input('hora') ? Carbon::createFromFormat('H:i', $request->input('hora')) : Carbon::now();

            $extra->fin = $hora;

            $extra->total = Carbon::today()->add($extra->fin->diffAsCarbonInterval($extra->inicio));
        }
        // return dd($extra);

        if ($extra->save()) {
            return response()->json(['mensaje' => 'Horas extra finalizadas correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error al finalizar las horas extra'], 500);
        }
    }

    /**
     * Start a break of the user loged
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function startBreak(Request $request)
    {

        Validator::make($request->all(), [
            'motivo' => 'required|exists:motivos,id'
        ])->validate();

        // dd($request->all());

        if (!($dia = Registro::where('usuario_id', Auth::id())
            ->where('dia', Carbon::today())
            ->first()
        ) || !$dia->entrada) {
            return response()->json(['mensaje' => 'Jornada no iniciada'], 403);
        }

        if ($dia->salida) {
            return response()->json(['mensaje' => 'Jornada ya finalizada (ERROR LOGICO)'], 403);
        } elseif ($dia->pausas()->latest()->first() && !($dia->pausas()->latest()->first()->fin)) {
            return response()->json(['mensaje' => 'Jornada en pausa (ERROR LOGICO)'], 403);
        } elseif (($dia->extras()->latest()->first()) && !($dia->extras()->latest()->first()->fin)) {
            return response()->json(['mensaje' => 'Horas extras iniciadas (ERROR LOGICO)'], 403);
        } else {
            $dia->pausas()->create([
                'inicio' => $request->input('hora') ? Carbon::createFromFormat('H:i', $request->input('hora')) : Carbon::now(),
                'motivo_id' => $request->input('motivo')
            ]);
        }

        if ($dia->save()) {
            return response()->json(['mensaje' => 'Dia pausado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error al pausar el dia'], 500);
        }
    }

    /**
     * The user logedback from the last break started
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function back(Request $request)
    {
        if (!($dia = Registro::where('usuario_id', Auth::id())
            ->where('dia', Carbon::today())
            ->first()
        ) || !$dia->entrada) {
            return response()->json(['mensaje' => 'Jornada no iniciada'], 403);
        }

        if ($dia->salida) {
            return response()->json(['mensaje' => 'Jornada ya finalizada (ERROR LOGICO)'], 403);
        } elseif (!($pausa = $dia->pausas()->latest()->first()) || ($pausa->fin)) {
            return response()->json(['mensaje' => 'No estas en una pausa (ERROR LOGICO)'], 403);
        } elseif (($dia->extras()->latest()->first()) && !($dia->extras()->latest()->first()->fin)) {
            return response()->json(['mensaje' => 'Realizando horas extra (ERROR LOGICO)'], 403);
        } else {

            $back = $request->input('hora') ? Carbon::createFromFormat('H:i', $request->input('hora')) : Carbon::now();

            $pausa->fin = $back;

            $pausa->total = Carbon::today()->add($pausa->fin->diffAsCarbonInterval($pausa->inicio));
        }

        if ($pausa->save()) {
            return response()->json(['mensaje' => 'Vuelta al trabajo correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error al volver de la pausa'], 500);
        }
    }

    public function complete() //recibir mes concreto o rango de fechas o autocompletar ultimos 3 meses
    {
        $tz = '+0';
        $ndias = 0;

        $cal_centro = CalendarioCentro::with('jornada')
            ->where('centro_id', CentroDepartamento::find(Auth::user()->centro_departamento_id)->centro_id)
            ->whereDate('dia', '>=', Carbon::today($tz)->firstOfMonth())
            ->whereDate('dia', '<=', Carbon::today($tz)->lastOfMonth());

        $cal_usuario = CalendarioUsuario::with('jornada')
            ->where('usuario_id', Auth::id())
            ->whereDate('dia', '>=', Carbon::today()->firstOfMonth())
            ->whereDate('dia', '<=', Carbon::today()->lastOfMonth());

        $period = CarbonPeriod::create(Carbon::today($tz)->firstOfMonth(), Carbon::today($tz)->lastOfMonth());

        foreach ($period as $day) {

            if (!Registro::where('usuario_id', Auth::id())
                ->where('dia', $day)
                ->first()) {

                $cp_cal_centro = clone $cal_centro;
                $cp_cal_usuario = clone $cal_usuario;

                $diacalendario = $cp_cal_usuario->where('dia', $day)->first() ? $cp_cal_usuario->where('dia', $day)->first() : $cp_cal_centro->where('dia', $day)->first();

                $jornada = $diacalendario->jornada()->first();

                if (!Registro::create([

                    'usuario_id' => Auth::id(),
                    'dia' => $day,

                    'entrada' => $jornada->entrada,
                    'salida' => $jornada->salida,
                    'is_real' => false,

                    'total' => $jornada->total,
                ])) {
                    $ndias++;
                }
                //Crear pausas falsas
            }
        }

        return response()->json(['mensaje' => 'Registros autocompletados correctamente en ' . $ndias . ' dias'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $user = Auth::id();


        $dia = Registro::where([
            ['usuario_id', $user],
            ['id', $id]
        ])->first();
        // TODO: cambiar un poco esto si se puede
        // return response()->json($dia);
        if ($error = $dia->delete()) {
            return response()->json(['message' => 'Dia eliminado correctamente'], 201);
        } else {
            return response()->json(['message' => 'Ocurrió un error durante la eliminacion del dia'], 500);
        }
    }
}
