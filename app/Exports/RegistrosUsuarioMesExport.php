<?php

namespace App\Exports;

use App\Http\Resources\UserResource;
use App\Models\CalendarioCentro;
use App\Models\CalendarioUsuario;
use App\Models\Centro;
use App\Models\CentroDepartamento;
use App\Models\Departamento;
use App\Models\DiaTipoUser;
use App\Models\Jornada;
use App\Models\Usuario;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;

class RegistrosUsuarioMesExport implements WithEvents
{

    public $year;
    public $mes;
    public $startDay;
    public $endDay;

    public $user;

    public $registros;

    public $cal_centro;
    public $cal_usuario;


    public function __construct($user_id = null) //TODO: la lista de que usuario descargo?
    {
        $fecha = Carbon::today()->subMonth();
        $this->year = Carbon::instance($fecha)->format('Y');
        $this->mes = mb_convert_case(Carbon::instance($fecha)->locale('es')->monthName, MB_CASE_TITLE);
        $this->startDay = Carbon::instance($fecha)->firstOfMonth()->startOfDay();
        $this->endDay = Carbon::instance($fecha)->lastOfMonth()->endOfDay();


        $this->user = Usuario::find(Auth::id());

        $this->registros = $this->user->registros()->whereBetween('dia', [$this->startDay, $this->endDay])->whereNotNull('id')->get(); //TODO: Hasta que fecha recuperar? Segun si es mes actual o no

        $this->cal_centro = CalendarioCentro::with('jornada')
            ->where('centro_id', CentroDepartamento::find($this->user->centro_departamento_id)->centro_id)
            ->whereDate('dia', '>=', $this->startDay)
            ->whereDate('dia', '<=', $this->endDay);

        $this->cal_usuario = CalendarioUsuario::with('jornada')
            ->where('usuario_id', 1)
            ->whereDate('dia', '>=', $this->startDay)
            ->whereDate('dia', '<=', $this->endDay)->get();
    }

    public function registerEvents(): array
    {
        // return dd([$this]);
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app\plantilla.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $this->populateSheet($sheet);

                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }
    private function populateSheet($sheet)
    {
        $centro = Centro::find(CentroDepartamento::find($this->user->centro_departamento_id)->centro_id);
        $dep = Departamento::find(CentroDepartamento::find($this->user->centro_departamento_id)->departamento_id);
        $jefe = Usuario::find(CentroDepartamento::find($this->user->centro_departamento_id)->usuario_id);

        $totalPausas = "00:00";
        $totalJornadas = "00:00";
        $totalExtras = "00:00";

        $sheet->setCellValue('C3', $centro->nombre);
        $sheet->setCellValue('C4', $centro->cif);
        $sheet->setCellValue('C5', $centro->localidad);
        $sheet->setCellValue('C6', $dep->nombre);

        $sheet->setCellValue('C7', $jefe->nombre . " " . $jefe->apellidos);
        $sheet->setCellValue('C8', $this->mes . "-" . $this->year);

        $sheet->setCellValue('G3', $this->user->nombre);
        $sheet->setCellValue('G4', $this->user->apellidos);
        $sheet->setCellValue('G8', $this->endDay->isoFormat('DD-MM-YYYY'));


        foreach ($this->registros as $registro) {
            $dia = Carbon::instance($registro->dia)->day;
            $fila = $dia + 10;

            $cp_cal_centro = clone $this->cal_centro;
            $cp_cal_usuario = clone $this->cal_usuario;

            $diacalendario = $cp_cal_usuario->where('dia', $registro->dia)->first() ?
                $cp_cal_usuario->where('dia', $registro->dia)->first() :
                $cp_cal_centro->where('dia', $registro->dia)->first();

            $jornada = $diacalendario->jornada()->first();

            // *** HORA DE ENTRADA Y SALIDA
            if ($registro->entrada) {
                $sheet->setCellValue('B' . $fila, $registro->entrada->format('H:i'));
            }
            if ($registro->salida) {
                $sheet->setCellValue('C' . $fila, $registro->salida->format('H:i'));
            }

            // *** TIEMPO DE PAUSAS
            if ($registro->salida && $registro->entrada) {

                $tiempoOficina = Carbon::today()->add($registro->salida->diffAsCarbonInterval($registro->entrada->format('H:i')));

                $pausaInMinutes = $jornada->total->diffInMinutes($tiempoOficina, false);

                if ($pausaInMinutes < 0) {
                    $diffInFormat = "-" . gmdate("H:i", abs($pausaInMinutes) * 60); //! Editar y ver que hacer para que no aparezca numero negativo (Preguntar)
                } else {
                    $diffInFormat = gmdate("H:i", $pausaInMinutes * 60);
                }

                $sheet->setCellValue('D' . $fila, $diffInFormat);

                $this->acumular($totalPausas, $pausaInMinutes);


                // *** TOTAL JORNADA

                $sheet->setCellValue('E' . $fila, $jornada->total->format('H:i'));

                $this->acumular($totalJornadas, $this->stringToMinutos($jornada->total->format('H:i')));
            }

            // *** HORAS EXTRAS

            if (($extras =$this->sumar($registro->extras, 'total')) != "00:00") {
                $sheet->setCellValue('F' . $fila, $extras);

                $this->acumular($totalExtras, $this->stringToMinutos($extras));
                $this->acumular($totalJornadas, $this->stringToMinutos($extras));

                if($total = $sheet->getCell('E'.$fila)->getValue()){
                    $this->acumular($total, $this->stringToMinutos($extras));

                    $sheet->setCellValue('E' . $fila, $total);

                }else {
                    $sheet->setCellValue('E' . $fila, $extras);
                }

            }


            // *** COMENTARIOS
            $sheet->setCellValue('G' . $fila, $registro->comentarioEntrada . (($registro->comentarioEntrada&&$registro->comentarioSalida)?" - ":"") . $registro->comentarioSalida);
            // dump(($registro->comentarioEntrada&&$registro->comentarioSalida));


            // dump($registro);
            // dump($totalJornadas);
            // dump($registro->entrada->format('H:i'));
            // dump($registro->salida->format('H:i'));

            // dump($diffInFormat);

        }

        // *** TOTALES
        $sheet->setCellValue('D' . 42, $totalPausas);
        $sheet->setCellValue('E' . 42, $totalJornadas);
        $sheet->setCellValue('F' . 42, $totalExtras);

        // dump($sheet->getCell('E25')->getValue());
        // dump($sheet->getCell('E26')->getValue());
        // die;
    }

    /**
     ** Convierte una hora en formato 23:59 a minutos totales
     *  @return int
     */
    private function stringToMinutos(string $hora)
    {
        list($horas, $minutos) = explode(':', $hora);
        return ($horas * 60) + $minutos;
    }

    /**
     ** Suma los minutos a una hora en formato 23:59 pasada por referencia
     *
     * Puede sumar o restar minutos pasando la segunda variable como negativa o positiva
     */
    private function acumular(string &$referencia, int $minutos)
    {
        // Separar horas y minutos de la hora de referencia
        list($horasReferencia, $minutosReferencia) = explode(':', $referencia);
        // Sumar minutos al tiempo de referencia
        $minutosReferencia += $minutos;
        if ($minutosReferencia < 0) {
            $horasReferencia += floor($minutosReferencia / 60);
            $minutosReferencia = abs($minutosReferencia % 60);
        } else {
            $horasReferencia += floor($minutosReferencia / 60);
            $minutosReferencia = $minutosReferencia % 60;
        }
        // Formatear el resultado en "HH:MM"
        $referencia = sprintf("%02d:%02d", $horasReferencia, $minutosReferencia);
    }

    /**
     ** Obtiene el total de horas y minutos en formato 23:59 de una coleccion, pasandole el campo el cual sumar
     * @return string | null
     */
    private function sumar(Collection $times, String $campo)
    {
        $horasTotal = 0;
        $minutosTotal = 0;

        // dump($times);
        foreach ($times as $time) {
            // Separar horas y minutos del string
            if ($time->total) {
                list($horas, $minutos) = explode(':', $time->$campo);

                $horasTotal += $horas;
                $minutosTotal += $minutos;
            }
        }

        if ($minutosTotal < 0) {
            $minutosTotal += floor($minutosTotal / 60);
            $minutosTotal = abs($minutosTotal % 60);
        } else {
            $minutosTotal += floor($minutosTotal / 60);
            $minutosTotal = $minutosTotal % 60;
        }

        return sprintf("%02d:%02d", $horasTotal, $minutosTotal);
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //
    }
}
