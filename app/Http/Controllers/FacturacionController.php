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
use App\Prefactura;
use App\PrefacturaDetalle;
use App\PrefacturaDescuento;

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
  public function prefacturasPendientes()
  {
      $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
        ->orderBy('empresa_razon_social')
        ->pluck('empresa_razon_social', 'empresa_id');

      return view('facturacion.prefacturasPendientes', compact( 'empresas'));
  }
  public function traePrefacturas(Request $request){
    $pendientes = $request->pendientes;
    $salida = [];
    if ($request->empresa == ''){
      // todas las empresas
      if ($pendientes == 'SI'){
        $prefactura = DB::table('prefacturas')->where('prefactura_numero_orden', 0)->select('prefactura_empresa_id', 'prefactura_id', 'prefactura_fecha_inicio', 'prefactura_fecha_final', 'prefactura_numero_orden')->orderBy('prefactura_id', 'desc')->get();
      }
      else{
        $prefactura = DB::table('prefacturas')->select('prefactura_empresa_id', 'prefactura_id', 'prefactura_fecha_inicio', 'prefactura_fecha_final', 'prefactura_numero_orden')->orderBy('prefactura_id', 'desc')->get();
      }
    }
    else{
      // empresa específica
      if ($pendientes == 'SI'){
        $prefactura = DB::table('prefacturas')->where('prefactura_empresa_id', $request->empresa)->where('prefactura_numero_orden', 0)->select('prefactura_empresa_id', 'prefactura_id', 'prefactura_fecha_inicio', 'prefactura_fecha_final', 'prefactura_numero_orden')->orderBy('prefactura_id', 'desc')->get();
      }
      else{
        $prefactura = DB::table('prefacturas')->where('prefactura_empresa_id', $request->empresa)->select('prefactura_empresa_id', 'prefactura_id', 'prefactura_fecha_inicio', 'prefactura_fecha_final', 'prefactura_numero_orden')->orderBy('prefactura_id', 'desc')->get();
      }
    }
    foreach ($prefactura as $pf) {
      // Recorremos las prefacturas seleccionadas para agregar los valores
      $montos = DB::table('prefactura_detalles')->where('prefactura_detalle_prefactura_id', $pf->prefactura_id)->select('prefactura_detalle_cant_dias', 'prefactura_detalle_valor_dia')->get();
      $total = 0;
      foreach ($montos as $m) {
        $total += $m->prefactura_detalle_cant_dias * $m->prefactura_detalle_valor_dia;
      }
      $emp = DB::table('empresas')->where('empresa_id', $pf->prefactura_empresa_id)->select('empresa_razon_social')->limit(1)->get();

      $item = new stdClass();
      $item->prefactura_id = $pf->prefactura_id;
      $item->prefactura_fecha_inicio = $pf->prefactura_fecha_inicio;
      $item->prefactura_fecha_final = $pf->prefactura_fecha_final;
      $item->prefactura_numero_orden = $pf->prefactura_numero_orden;
      $item->monto = $total;
      $item->cant_vines = count($montos);
      $item->empresa = $emp[0]->empresa_razon_social;
      $item->descuento = 0;
      $descuento = DB::table('prefactura_descuentos')->where('prefactura_descuento_prefactura_id', $pf->prefactura_id)->select('prefactura_descuento_tipo', 'prefactura_descuento_monto')->get();
      if(count($descuento) == 1){
        if($descuento[0]->prefactura_descuento_tipo == 'porcentaje'){
          $item->descuento = ($total * $descuento[0]->prefactura_descuento_monto / 100);
        }
        else{
          $item->descuento = $total - $descuento[0]->prefactura_descuento_monto;
        }
      }
      array_push($salida, $item);
    }
    if (count($salida) >= 1){
      return response()->json($salida);
    }
    return response()->json(array());
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
              $salida->vin_id = $vin->vin_id;
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
          $salida->vin_id = $vin->vin_id;
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
    $salida->fini = $fini;
    $salida->ffin = $ffin;
    $salida->dias = 0;
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
          $salida->ffin = $v[0]->historico_vin_fecha;
          $salida->dias = $dias;
        }
    }

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

  public function grabarPrefactura(Request $request){
    $prefactura = json_decode($request->prefactura);
    $prefactura_detalle = json_decode($request->prefactura_detalle);
    $prefactura_descuento = json_decode($request->prefactura_descuento);
    // GRABAR ENCABEZADO DE LA PREFACTURA
    $tabla_prefactura = new Prefactura;
    $tabla_prefactura->prefactura_empresa_id = $prefactura->prefactura_empresa_id;
    $tabla_prefactura->prefactura_fecha_inicio = $prefactura->prefactura_fecha_inicio;
    $tabla_prefactura->prefactura_fecha_final = $prefactura->prefactura_fecha_final;
    $tabla_prefactura->prefactura_user_id_creacion = $prefactura->prefactura_user_id_creacion;
    $tabla_prefactura->prefactura_user_id_actualizacion = $prefactura->prefactura_user_id_actualizacion;
    $tabla_prefactura->prefactura_numero_orden = 0;
    $tabla_prefactura->save();
    $id_prefactura = $tabla_prefactura->prefactura_id;

    // GRABAR DETALLE DE LA PRE-FACTURA
    foreach ($prefactura_detalle as $pd) {
      $tabla_prefactura_detalle = new PrefacturaDetalle;
      $tabla_prefactura_detalle->prefactura_detalle_prefactura_id = $id_prefactura;
      $tabla_prefactura_detalle->prefactura_detalle_vin_id = $pd[7];
      $tabla_prefactura_detalle->prefactura_detalle_fecha_inicio = $pd[5];
      $tabla_prefactura_detalle->prefactura_detalle_fecha_final = $pd[6];
      $tabla_prefactura_detalle->prefactura_detalle_cant_dias = $pd[3];
      $tabla_prefactura_detalle->prefactura_detalle_valor_dia =  $pd[4];
      $tabla_prefactura_detalle->save();
    }
    // GRABAR DESCUENTO DE LA PRE-Factura
    if($prefactura_descuento->prefactura_descuento_tipo != ''){
      $tabla_prefactura_descuento = new PrefacturaDescuento;
      $tabla_prefactura_descuento->prefactura_descuento_prefactura_id = $id_prefactura;
      $tabla_prefactura_descuento->prefactura_descuento_tipo = $prefactura_descuento->prefactura_descuento_tipo;
      $tabla_prefactura_descuento->prefactura_descuento_monto = $prefactura_descuento->prefactura_descuento_monto;
      $tabla_prefactura_descuento->prefactura_descuento_motivo = $prefactura_descuento->prefactura_descuento_motivo;
      $tabla_prefactura_descuento->prefactura_descuento_user_id = $prefactura_descuento->prefactura_descuento_user_id;
      $tabla_prefactura_descuento->save();
    }
    return json_encode(array("mensaje" => "OK", "detalle" => 0));
  }

  public function traeUltimaPrefactura(Request $request){
    $prefactura = DB::table('prefacturas')->where('prefactura_empresa_id', $request->empresa)->select('prefactura_fecha_inicio', 'prefactura_fecha_final', 'prefactura_numero_orden')->orderBy('prefactura_id', 'desc')->limit(1)->get();
    if (count($prefactura) == 1){
      return response()->json($prefactura);
    }
    return response()->json(array());
  }


}
