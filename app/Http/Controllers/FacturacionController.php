<?php

namespace App\Http\Controllers;

use App\Exports\VinsAgendadosExport;
use App\Vin;
use App\Empresa;
use App\HistoricoVin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use App\Imports\VinsCollectionImport;
use App\TipoCampania;
use App\Entrega;
use App\Exports\BusquedaVinsExport;
use App\Exports\VinEntregadosExport;

use DB;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateInterval;
use DatePeriod;
use stdClass;
use App\DivisaValor;
use App\Divisa;
use App\Servicio;


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
  public function generarPrefactura(Request $request)
  {

    $query = Vin::join('users','users.user_id','=','vins.user_id')
        ->join('vin_estado_inventarios','vins.vin_estado_inventario_id','=','vin_estado_inventarios.vin_estado_inventario_id')
        ->join('empresas','users.empresa_id','=','empresas.empresa_id')
        ->join('marcas','vins.vin_marca','=','marcas.marca_id')
        ->leftjoin('guia_vins','guia_vins.vin_id','=','vins.vin_id')
        ->leftjoin('guias','guia_vins.guia_id','guias.guia_id')
        ->leftJoin('ubic_patios','ubic_patios.vin_id','=','vins.vin_id')
        ->leftJoin('bloques','ubic_patios.bloque_id','=','bloques.bloque_id')
        ->leftJoin('patios','bloques.patio_id','=','patios.patio_id')
        ->select('vins.vin_id','vin_codigo', 'vin_patente', 'marca_nombre', 'vin_modelo', 'vin_color', 'vin_segmento', 'vin_motor',
            'empresas.empresa_razon_social', 'vin_fec_ingreso', 'vin_fecha_agendado', 'vin_fecha_entrega','vins.vin_estado_inventario_id', 'vin_estado_inventario_desc',
            'patio_nombre', 'bloque_nombre', 'ubic_patio_id', 'ubic_patio_fila','ubic_patio_columna','guias.guia_ruta')
        ->where('empresas.empresa_id', $request->empresa);
    if($request->tipo == "inventario"){

          $tabla_vins = $query->get();
      $arreglo_vins = [];
          foreach ($tabla_vins as $vin) {
            $dias = $this->diasEnPatio($request->fini, $request->ffin, $vin->vin_id);
            if ($dias->dias > 0){
              $salida = new stdClass();
              $salida->vin = $vin->vin_codigo;
              $salida->marca_nombre = $vin->marca_nombre;
              $salida->vin_modelo = $vin->vin_modelo;
              $salida->dias = $dias->dias;
              $salida->tarifa = $this->valorDiarioVin($vin->vin_codigo, $request->empresa);
              $salida->fini = $dias->fini;
              $salida->ffin = $dias->ffin;
              array_push($arreglo_vins, $salida);
            }
          }
          return response()->json($arreglo_vins);
    }
    if($request->tipo == "despachadas"){

          $tabla_vins = $query->get();
      $arreglo_vins = [];
      foreach ($tabla_vins as $vin) {
        $dias = $this->diasTotalesEnPatio($request->fini, $request->ffin, $vin->vin_id);
        if ($dias->dias > 0){
          $salida = new stdClass();
          $salida->vin = $vin->vin_codigo;
          $salida->marca_nombre = $vin->marca_nombre;
          $salida->vin_modelo = $vin->vin_modelo;
          $salida->dias = $dias->dias;
          $salida->fini = $dias->fini;
          $salida->ffin = $dias->ffin;
          $salida->tarifa = $this->valorDiarioVin($vin->vin_codigo, $request->empresa);

          array_push($arreglo_vins, $salida);
        }
      }
      return response()->json($arreglo_vins);
    }

  }


  public function divisasDia(Request $request)
  {
    $arr = [];
    $query = Divisa::all('divisa_id', 'divisa_tipo');
    $tabla = $query;
    foreach ($tabla as $t) {
      $salida = new stdClass();
      $salida->tipo = $t->divisa_tipo;
      $aux = DivisaValor::where('divisa_id', $t->divisa_id)->where('divisa_valor_fecha', $request->fecha)->select('divisa_valor_valor')->orderBy('divisa_valor_fecha', 'DESC')->take(1)->value('divisa_valor_valor');
      $salida->valor =$aux;
      if ($salida->valor == null)
      {
        $aux = DivisaValor::where('divisa_id', $t->divisa_id)->where('divisa_valor_fecha', '<', $request->fecha)->select('divisa_valor_valor')->orderBy('divisa_valor_fecha', 'DESC')->take(1)->value('divisa_valor_valor');
        $salida->valor = $aux;
        if($salida->valor==null){
          $salida->valor = 0;
        }
      }
      $salida->valor = number_format($salida->valor, 2, ',', '.');
      $salida->fecha = DivisaValor::where('divisa_id', $t->divisa_id)->where('divisa_valor_fecha', $request->fecha)->select('divisa_valor_fecha')->orderBy('divisa_valor_fecha', 'DESC')->take(1)->value('divisa_valor_fecha');
      if ($salida->fecha == null)
      {
        $salida->fecha = DivisaValor::where('divisa_id', $t->divisa_id)->where('divisa_valor_fecha', '<', $request->fecha)->select('divisa_valor_fecha')->orderBy('divisa_valor_fecha', 'DESC')->take(1)->value('divisa_valor_fecha');
        if($salida->fecha == null){
          $salida->fecha = '0000-00-00';
        }
      }
      array_push($arr, $salida);
    }


    return response()->json($arr);
  }

  function diasEnPatio($fini, $ffin, $vin){
    $dias = 0;
    $begin = new DateTime($fini);
    $end = new DateTime($ffin);

    //$interval = DateInterval::createFromDateString('1 day');
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($begin, $interval, $end);
    $primero = 0;
    $ultimo = 0;
    $salida = new stdClass();
    foreach ($period as $dt) {
      // Se ve cual es el último estado del VIN
        $v = DB::table('historico_vins')->where([['vin_id', '=', $vin],['historico_vin_fecha', '<',  $dt->format("Y-m-d") ]])->orderBy('historico_vin_fecha', 'DESC')->orderBy('historico_vin_id', 'DESC')->limit(1)->get();
        if(count($v) > 0){
          if (in_array($v[0]->vin_estado_inventario_id, array(2, 4))) { // sigue en patio según el último movimiento
            if($primero == 0){
              $primero++;
              //$salida->fini = $v[0]->historico_vin_fecha;
              $salida->fini = $fini;
            }
            //$movimiento = DB::table('historico_vins')->where([['vin_id', '=', $vin],['historico_vin_fecha', '=', '\''. $dt->format("Y-m-d") .'\'']])->orderBy('historico_vin_fecha', 'DESC')->orderBy('historico_vin_id', 'DESC')->limit(1)->get();
            $dias++;
          }
        }
        $salida->ffin = $v[0]->historico_vin_fecha;
    }
    $salida->dias = $dias;
    return $salida;
  }
  function diasTotalesEnPatio($fini, $ffin, $vin)
  {
    $salida = new stdClass();
    $dias = 0;
    // SE BUSCA LA ULTIMA FECHA DE ENTREGA
    $v = DB::table('historico_vins')
      ->select('historico_vin_fecha')
      ->whereBetween('historico_vin_fecha', [$fini, $ffin])
      ->where([['vin_id', '=', $vin], ['vin_estado_inventario_id', '=',  '8']])
      ->orderBy('historico_vin_fecha', 'DESC')
      ->get();
    if(count($v) > 0){
      $end = $v[0]->historico_vin_fecha;
    }
    else{
      $salida->fini = '0000-00-00';
      $salida->ffin = '0000-00-00';
      $salida->dias = 0;
      return $salida;
    }
    // SE BUSCA LA ÚLTIMA FECHA DE ARRIBO QUE SEA MENOR O IGUAL A LA FECHA DE ENTREGADO SELECCIONADA
    $v = DB::table('historico_vins')
      ->select('historico_vin_fecha')
      ->where([['vin_id', '=', $vin],['historico_vin_fecha', '<=',  $end], ['vin_estado_inventario_id', '=',  '2']])
      ->orderBy('historico_vin_fecha', 'DESC')
      ->get();
    if(count($v) > 0){
      $begin = $v[0]->historico_vin_fecha;
      $dias = $this->dateDiffInDays($begin, $end);
    }
    $salida->fini = $begin;
    $salida->ffin = $end;
    $salida->dias = $dias;
    return $salida;
  }
  function dateDiffInDays($f1, $f2)
  {
    $start = strtotime($f1);
    $end = strtotime($f2);
    $days = $end - $start;
    $days = ceil($days/86400);
    return $days+1;

  }

  function valorDiarioVin($vin_codigo, $empresa){
    $precio = 0;
    $segmento = Vin::where('vin_codigo',$vin_codigo)->select('vin_segmento')->take(1)->value('vin_segmento');
    switch (strtoupper($segmento)) {
      case 'SMALL':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 1)->take(1)->value('servicios_precio');
        break;
      case 'MEDIUM':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 2)->take(1)->value('servicios_precio');
        break;
      case 'LARGE':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 3)->take(1)->value('servicios_precio');
        break;
      case 'EXTRA LARGE':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 4)->take(1)->value('servicios_precio');
        break;
      case 'S':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 1)->take(1)->value('servicios_precio');
        break;
      case 'M':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 2)->take(1)->value('servicios_precio');
          break;
      case 'L':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 3)->take(1)->value('servicios_precio');
          break;
      case 'XL':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 4)->take(1)->value('servicios_precio');
          break;
      case 'MINI BUS':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 5)->take(1)->value('servicios_precio');
          break;
      case 'BUS':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 6)->take(1)->value('servicios_precio');
          break;
      case 'CAMION':
        $precio = Servicio::where('cliente_id', $empresa)->where('producto_id', 2)->where('caracteristica_vin_id', 7)->take(1)->value('servicios_precio');
          break;
      default:
        $precio = 0;
        break;
    }
    if ($precio == null){
      return 0;
    }
    return $precio;
  }

}
