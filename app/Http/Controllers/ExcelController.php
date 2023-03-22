<?php

namespace App\Http\Controllers;

use App\Exports\RegistrosUsuarioMesExport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends Controller
{
    public function bajar(int $mes, Request $request){
        // // $admin_id = Auth::guard('admin')->user()->id;

        $filenameExport = "RegistroMensual.xlsx";
        try{
            return Excel::download(new RegistrosUsuarioMesExport(Auth::id(), $mes), $filenameExport);
        } catch (Exception $e) {
            dd($e);
        }

    }
}
