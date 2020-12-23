<?php

namespace App\Http\Controllers;

use App\Exports\VinsAgendadosExport;
use App\Vin;
use App\Empresa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckSession;
use App\Predespacho;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class FacturacionController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      // $this->middleware(PreventBackHistory::class);
      $this->middleware(CheckSession::class);
  }
  public function prefactura()
  {
      $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
        ->orderBy('empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

      return view('facturacion.prefactura', compact( 'empresas'));
  }
  public function generarPrefactura()
  {
      return view('facturacion.prefactura');
  }
}
