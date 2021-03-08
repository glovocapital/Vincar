<?php

namespace App\Http\Controllers;

use DB;
use App\Empresa;
use App\Guia;
use App\GuiaVin;
use App\GuiaVinRuta;
use App\HistoricoTour;
use App\Remolque;
use App\Ruta;
use App\RutaGuia;
use App\Tour;
use App\Vin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RutaController extends Controller
{
    public function creaRutas(Request $request)
    {
        $guia = Guia::where('guia_numero', $request->guia_numero)
            ->where('empresa_id', $request->empresa_id)
            ->first();

        // Verificar si la guía ya está asignada a una ruta existente.
        $existeGuia= false;

        if ($guia){
            $existeGuia = RutaGuia::where('guia_id', $guia->guia_id)
                ->exists();
        }

        if($existeGuia){
            flash('Número de Guía ya asignada a otra ruta. Por favor intente con otra.')->error();
            return back()->withInput();
        }

        // Validación de la cantidad de VINs asignados al Tour

        $id_tour = $request->id_tour;

        $tour = Tour::find($id_tour);

        if ($tour) {
            $remolque = Remolque::find($tour->remolque_id);

            // Obtener cuántos VINs están previamente asignados a otras rutas del Tour.
            $cantidadVinsTour = Tour::join('rutas', 'rutas.tour_id', '=', 'tours.tour_id')
                ->join('ruta_guias','ruta_guias.ruta_id', '=', 'rutas.ruta_id')
                ->join('guia_vin_rutas', 'guia_vin_rutas.guia_id', '=', 'ruta_guias.guia_id')
                ->where('tours.tour_id', $id_tour)
                ->count();
        } else {
            flash('Error: Tour no encontrado. Informar al administrador.')->error();
            return back()->withInput();
        }

        // Extraer los VINs que se enviaron en el formulario
        if(!empty($request->vin_numero)){
            // Si el campo de VINs del formulario viene con VINS
            // Se procede a extraer uno por uno los VINS separados por salto de línea
            foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                if($row !== ""){
                    $arreglo_vins[] = trim($row);
                }
            }
        }

        // Contar cuántos VINs fueron enviados
        $vinsRuta = count($arreglo_vins);

        if ($remolque) {
            // Validar que la cantidad de VINs asignados previamente más los enviados no exceden la capacidad del remolque.
            if (($cantidadVinsTour + $vinsRuta) > $remolque->remolque_capacidad) {
                flash('Excedida la capacidad del remolque de ' . $remolque->remolque_capacidad . ' vehículos. Vehículos previamente asignados al tour: ' . $cantidadVinsTour . '.')->error();
                return back()->withInput();
            }
        } else {
            flash('Error: Remolque no encontrado. Informar al administrador.')->error();
            return back()->withInput();
        }

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        try
        {
            DB::beginTransaction();

            $cuenta = 0;

            $ruta = new Ruta();
            $ruta->ruta_origen = $request->ruta_origen;
            $ruta->ruta_destino = $request->ruta_destino;
            $ruta->tour_id = $id_tour;

            if ($ruta->save()) {
                $guia = new Guia();
                $guia->guia_fecha = $request->guia_fecha;
                $guia->guia_numero = trim($request->guia_numero);
                $guia->empresa_id = $request->empresa_id;

                if($guia->save()){
                    $rutaGuia = new RutaGuia();
                    $rutaGuia->ruta_id = $ruta->ruta_id;
                    $rutaGuia->guia_id = $guia->guia_id;
                    if (!$rutaGuia->save()) {
                        DB::rollback();
                        flash('Error de asociación de ruta con la guía.')->error();
                        return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                    }
                } else {
                    DB::rollback();
                    flash('Error guardando información de la guía.')->error();
                    return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                }

                $historicoTour = new HistoricoTour();
                $historicoTour->tour_id = $id_tour;
                $historicoTour->ruta_id = $ruta->ruta_id;
                $historicoTour->cliente_id = $request->empresa_id;
                $historicoTour->historico_tour_fecha_inicio = $tour->tour_fec_inicio;
                $historicoTour->proveedor_id = $tour->proveedor_id;
                $historicoTour->historico_tour_numero_guia_ruta = $guia->guia_numero;
                $historicoTour->historico_tour_descripcion = 'Creación de Ruta de ' . $ruta->ruta_origen . ' hasta ' . $ruta->ruta_destino . '.';

                if ($historicoTour->save()) {
                    flash('Registro histórico de creación de ruta creado correctamente.')->success();
                } else {
                    DB::rollback();
                    flash('Error guardando histórico de creación de ruta.')->error();
                    return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                }

                if(!empty($arreglo_vins)){
                    // Por cada uno de los VINs pasados desde el formulario realizar las acciones correspondientes
                    foreach($arreglo_vins as $v){
                        // Validar si el VIN existe en la base de datos.
                        $validate = DB::table('vins')
                            ->where('vin_codigo', $v)
                            ->orWhere('vin_patente', $v)
                            ->exists();

                        if($validate)
                        {
                            // Existe el VIN, luego se procede a consultar el registro para usarlo.
                            $vin = Vin::where('vin_codigo', $v)
                                ->orWhere('vin_patente', $v)
                                ->first();

                            // Validación para verificar que los VINs pertenecen a la empresa emisora de la guía
                            $empresaVin = Vin::join('users', 'vins.user_id','=','users.user_id')
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                ->where('vins.vin_codigo', $vin->vin_codigo)
                                ->select('empresas.empresa_id')
                                ->first();

                            if($guia->empresa_id != $empresaVin->empresa_id){
                                flash('Error. El VIN ingresado como: ' . $vin->vin_codigo . ' no pertenece a la empresa que emitió la guía.')->error();
                                DB::rollBack();
                                return back()->withInput();
                            }

                            // Validar si ya existe previamente un registro donde el VIN esté asociado a una guía
                            $val2 = GuiaVinRuta::where('vin_id', $vin->vin_id)->exists();

                            if(!$val2){
                                // Si no existe la asociación, entonces se asocia el VIN a la guía $guia
                                $guiaVinRuta = new GuiaVinRuta();
                                $guiaVinRuta->vin_id = $vin->vin_id;
                                $guiaVinRuta->guia_id = $guia->guia_id;
                                $guiaVinRuta->ruta_id = $ruta->ruta_id;
                                $guiaVinRuta->save();
                                $cuenta++;
                            } else {
                                // Si existe la asociación, entonces se consulta el registro correspondiente.
                                $guia_vin = GuiaVinRuta::where('vin_id', $vin->vin_id)->first();

                                // SOLO DEBE EXISTIR UN REGISTRO DEL VIN ASOCIADO CON UNA GUÍA PARA ESTA RUTA
                                if($guia->guia_id !== $guia_vin->guia_id){
                                    // Si la guía actual no es la misma guía donde estaba registrado el vin
                                    // se elimina la asociación anterior y se crea una nueva.
                                    GuiaVinRuta::where('vin_id', $vin->vin_id)->delete();

                                    $guiaVinRuta = new GuiaVinRuta();
                                    $guiaVinRuta->vin_id = $vin->vin_id;
                                    $guiaVinRuta->guia_id = $guia->guia_id;
                                    $guiaVinRuta->ruta_id = $ruta->ruta_id;
                                    $guiaVinRuta->save();
                                    $cuenta++;
                                }
                            }

                            $guiaVin = new GuiaVin();
                            $guiaVin->vin_id = $vin->vin_id;
                            $guiaVin->guia_id = $guia->guia_id;
                            $guiaVin->save();

                            $historicoTour = new HistoricoTour();
                            $historicoTour->tour_id = $id_tour;
                            $historicoTour->ruta_id = $ruta->ruta_id;
                            $historicoTour->vin_id = $vin->vin_id;
                            $historicoTour->cliente_id = $request->empresa_id;
                            $historicoTour->historico_tour_fecha_inicio = $tour->tour_fec_inicio;
                            $historicoTour->proveedor_id = $tour->proveedor_id;
                            $historicoTour->historico_tour_numero_guia_ruta = $guia->guia_numero;
                            $historicoTour->historico_tour_descripcion = 'VIN: ' . $vin->vin_codigo . ' añadido a la ruta de ' . $ruta->ruta_origen . ' hasta ' . $ruta->ruta_destino . '.';

                            if ($historicoTour->save()) {
                                flash('Histórico de incorporación del VIN: ' . $vin->vin_codigo . ' en la ruta correctamente.')->success();
                            } else {
                                DB::rollback();
                                flash('Error guardando histórico de incorporación del VIN: ' . $vin->vin_codigo . ' en la ruta.')->error();
                                return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                            }
                        } else {
                            DB::rollback();
                            flash('¡Error! VIN: ' . $v . 'no existe en la base de datos.')->error();
                            return back()->withInput();
                        }
                    }

                    if($cuenta > 0){
                        // Se añadieron los vins a la ruta. Se aceptan los cambios a la base de datos.
                        DB::commit();
                        flash('La ruta se agregó correctamente.')->success();
                        return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                    } else {
                        // No se añadieron vins a la ruta, se echa para atrás el cambio a la base de datos
                        DB::rollBack();
                        flash('Error: No se creó la ruta. VINs no válidos')->error();
                        return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                    }
                } else{
                    DB::rollBack();
                    flash('Error: Debe proporcionar al menos un VIN válido.')->error();
                    return view('transporte.editrutas', compact('id_tour', 'empresas'));
                }
            } else {
                DB::rollback();
                flash('Error actualizando información del Tour.')->error();
                return redirect()->action('TourController@tour')->withInput();
            }
        }  catch (\Exception $e) {
            DB::rollBack();
            flash('Error al añadir las rutas al Tour.')->error();
            flash($e->getMessage())->error();
            return redirect('tour.tour');
        }
    }

    public function addRutas()
    {
        return view('transporte.addrutas');
    }

    public function adminRutas()
    {
        return view('transporte.admin_rutas');
    }

    public function editRutas($id)
    {
        $tour_id =  Crypt::decrypt($id);
        $tour = Tour::findOrfail($tour_id);

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $guia_vin_rutas = GuiaVinRuta::join('guias','guias.guia_id','=','guia_vin_rutas.guia_id')
            ->join('ruta_guias','ruta_guias.guia_id','=', 'guias.guia_id')
            ->join('rutas','rutas.ruta_id','=','ruta_guias.ruta_id')
            ->join('vins','vins.vin_id','=','guia_vin_rutas.vin_id')
            ->where('rutas.tour_id', $tour_id)
            ->select('guia_vin_ruta_id', 'guia_vin_rutas.vin_id', 'guia_vin_rutas.guia_id', 'vin_codigo')
            ->get();

        $viajes = Tour::join('rutas','rutas.tour_id','=','tours.tour_id')
            ->join('ruta_guias','ruta_guias.ruta_id','=', 'rutas.ruta_id')
            ->join('guias','guias.guia_id','=','ruta_guias.guia_id')
            ->where('tours.tour_id', $tour_id)
            ->get();

        $vins_guia_array = [];

        foreach ($viajes as $viaje){
            $cadena_vins = "";
            $empresa_id = 0;
            $guia_numero = '';
            $guia_fecha = '';
            $guia_id = 0;
            $ruta_id = 0;

            $ruta_simple = [$viaje->ruta_origen, $viaje->ruta_destino];

            foreach($guia_vin_rutas as $guia_vin){
                if(($viaje->guia_id == $guia_vin->guia_id)){
                    $cadena_vins .= $guia_vin->vin_codigo . "\n";
                }
            }

            $empresa_id = $viaje->empresa_id;
            $guia_numero = $viaje->guia_numero;
            $guia_fecha = $viaje->guia_fecha;
            $guia_id = $viaje->guia_id;
            $ruta_id = $viaje->ruta_id;

            array_push($vins_guia_array, [$empresa_id, $ruta_simple, $ruta_id, $cadena_vins, $guia_numero, $guia_fecha, $guia_id]);
        }

        return view('transporte.editrutas', compact('tour_id', 'vins_guia_array', 'empresas'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRutas(Request $request, $id)
    {
        $id_tour = Crypt::decrypt($id);

        $size = count($request->vin_numero);

        for($i = 0 ; $i < $size; $i++){
            $arreglo_vins = [];

            foreach(explode(PHP_EOL, $request->vin_numero[$i]) as $row){
                if($row !== ""){
                    $arreglo_vins[] = trim($row);
                }
            }

            // Validación de la cantidad de VINs asignados al Tour
            $tour = Tour::find($id_tour);

            if ($tour) {
                $remolque = Remolque::find($tour->remolque_id);

                // Obtener cuántos VINs están previamente asignados a otras rutas del Tour.
                $cantidadVinsTour = Tour::join('rutas', 'rutas.tour_id', '=', 'tours.tour_id')
                    ->join('ruta_guias','ruta_guias.ruta_id', '=', 'rutas.ruta_id')
                    ->join('guia_vin_rutas', 'guia_vin_rutas.guia_id', '=', 'ruta_guias.guia_id')
                    ->where('tours.tour_id', $id_tour)
                    ->where('rutas.ruta_id', '!=', $request->ruta_id[$i])
                    ->count();
            } else {
                flash('Error: Tour no encontrado. Informar al administrador.')->error();
                return back()->withInput();
            }

            try
            {
                DB::beginTransaction();

                // Contar cuántos VINs fueron enviados
                $vinsRuta = count($arreglo_vins);

                if ($remolque) {
                    // Validar que la cantidad de VINs asignados previamente más los enviados no exceden la capacidad del remolque.
                    if (($cantidadVinsTour + $vinsRuta) > $remolque->remolque_capacidad) {
                        flash('Excedida la capacidad del remolque de ' . $remolque->remolque_capacidad . ' vehículos. Vehículos previamente asignados al tour: ' . $cantidadVinsTour . '.')->error();
                        return back()->withInput();
                    }
                } else {
                    flash('Error: Remolque no encontrado. Informar al administrador.')->error();
                    return back()->withInput();
                }

                $guiaOriginal = Guia::findOrFail($request->guia_id[$i]);

                // Recorrer el arreglo de VINs enviados
                if($arreglo_vins !== []){
                    // Verificar si se cambió el número de la guía.
                    $cambioGuia = false;

                    $rutaModificar = Ruta::where('tour_id', $id_tour)
                        ->where('ruta_id', $request->ruta_id[$i])
                        ->first();

                    if (($guiaOriginal->guia_numero !== $request->guia_numero[$i]) || ($guiaOriginal->empresa_id != $request->empresa_id[$i]) || ($guiaOriginal->guia_fecha !== $request->guia_fecha[$i])) {
                        // Verificar si la guía nueva enviada ya está asignada a una ruta existente.
                        $verificaGuia = Guia::where('guia_numero', $request->guia_numero[$i])
                            ->where('empresa_id', $request->empresa_id[$i])
                            ->first();

                        $existeGuia= false;

                        if ($verificaGuia){
                            $existeGuia = RutaGuia::where('guia_id', $verificaGuia->guia_id)
                                ->exists();
                        }

                        if($existeGuia){
                            return back()->with('error', 'Número de Guía ya asignada a otra ruta. Por favor intente con otra.')->withInput();
                        }

                        $cambioGuia = true;

                        // Crear nueva guía
                        $guia = new Guia();
                        $guia->guia_numero = $request->guia_numero[$i];
                        $guia->empresa_id = $request->empresa_id[$i];
                        $guia->guia_fecha = $request->guia_fecha[$i];
                    } else {
                        $guia = $guiaOriginal;
                    }

                    if ($guia->save()) {
                        // Si la guía es una nueva guía, actualizar las relaciones guia_vin_rutas y
                        // ruta_guias a la nueva guia
                        if ($cambioGuia) {
                            $ruta = Ruta::where('tour_id', $id_tour)
                                ->where('ruta_id', $request->ruta_id[$i])
                                ->first();

                            GuiaVinRuta::where('guia_id', $guiaOriginal->guia_id)
                                ->update([
                                    'guia_id' => $guia->guia_id,
                                ]);

                            RutaGuia::where('ruta_id', $ruta->ruta_id)
                                ->where('guia_id', $guiaOriginal->guia_id)
                                ->update([
                                    'guia_id' => $guia->guia_id,
                                ]);

                            $guiaOriginal->delete();
                        }

                        $historicoTour = new HistoricoTour();
                        $historicoTour->tour_id = $id_tour;
                        $historicoTour->ruta_id = $request->ruta_id[$i];
                        $historicoTour->cliente_id = $guia->empresa_id;
                        $historicoTour->historico_tour_fecha_inicio = $tour->tour_fec_inicio;
                        $historicoTour->proveedor_id = $tour->proveedor_id;
                        $historicoTour->historico_tour_numero_guia_ruta = $guia->guia_numero;
                        $historicoTour->historico_tour_descripcion = 'Modificación de datos de Ruta de ' . $rutaModificar->ruta_origen . ' hasta ' . $rutaModificar->ruta_destino . '.';

                        if ($historicoTour->save()) {
                            flash('Registro histórico de modificación de ruta creado correctamente.')->success();
                        } else {
                            DB::rollback();
                            flash('Error guardando histórico de modificación de ruta.')->error();
                            return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)])->withInput();
                        }
                    } else {
                        DB::rollBack();
                        flash('Error almacenando guía en la base de datos.')->error();
                        flash($e->getMessage())->error();
                        return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)])->withInput();
                    }

                    $cuenta = 0;

                    foreach($arreglo_vins as $codigo){
                        // Validar si el vin a agregar existe en la base de datos
                        $validate = DB::table('vins')
                            ->where('vin_codigo', $codigo)
                            ->orWhere('vin_patente', $codigo)
                            ->exists();

                        // Si existe en la base de datos entonces se procede con nuevas acciones
                        if($validate)
                        {
                            // Consultar el VIN comprobado
                            $vin = Vin::where('vin_codigo', $codigo)
                                ->orWhere('vin_patente', $codigo)
                                ->first();

                            // Validación para verificar que los VINs pertenecen a la empresa emisora de la guía
                            $empresaVin = Vin::join('users', 'vins.user_id','=','users.user_id')
                                ->join('empresas','users.empresa_id','=','empresas.empresa_id')
                                ->where('vins.vin_codigo', $vin->vin_codigo)
                                ->select('empresas.empresa_id')
                                ->first();

                            if($guia->empresa_id != $empresaVin->empresa_id){
                                flash('Error actualizando ruta. El VIN: ' . $vin->vin_codigo . ' y la empresa seleccionada para la guía no se corresponden entre sí.')->error();
                                return back()->withInput();
                            }

                            // Validar si existe asociación de este VIN con guías anteriores.
                            $val2 = GuiaVinRuta::where('vin_id', $vin->vin_id)->exists();

                            if($val2){
                                // Buscar con cuál guía está asociado el VIN.
                                $guia_vin = GuiaVinRuta::where('vin_id', $vin->vin_id)->first();

                                // Si ya existe otra asociación vin-guía, entonces se elimina para crear
                                // la nueva asociación. En caso contrario, no se hace nada nuevo.
                                if($guia->guia_id !== $guia_vin->guia_id){
                                    GuiaVinRuta::where('vin_id', $vin->vin_id)
                                        ->where('guia_id', $guia_vin->guia_id)
                                        ->delete();

                                    $guiavin = new GuiaVinRuta();
                                    $guiavin->vin_id = $vin->vin_id;
                                    $guiavin->guia_id = $guia->guia_id;
                                    $guiavin->ruta_id = $rutaModificar->ruta_id;
                                    $guiavin->save();

                                    $cuenta++;
                                }
                            } else { // Si no existe asociación guía-vin se crea la nueva asociación del VIN con la guía
                                $guiavin = new GuiaVinRuta();
                                $guiavin->vin_id = $vin->vin_id;
                                $guiavin->guia_id = $guia->guia_id;
                                $guiavin->ruta_id = $rutaModificar->ruta_id;
                                $guiavin->save();

                                $cuenta++;
                            }

                            if ($cambioGuia) {
                                $guiaVin = new GuiaVin();
                                $guiaVin->vin_id = $vin->vin_id;
                                $guiaVin->guia_id = $guia->guia_id;
                                $guiaVin->save();
                            }

                            $historicoTour = new HistoricoTour();
                            $historicoTour->tour_id = $id_tour;
                            $historicoTour->ruta_id = $request->ruta_id[$i];
                            $historicoTour->vin_id = $vin->vin_id;
                            $historicoTour->cliente_id = $empresaVin->empresa_id;
                            $historicoTour->historico_tour_fecha_inicio = $tour->tour_fec_inicio;
                            $historicoTour->proveedor_id = $tour->proveedor_id;
                            $historicoTour->historico_tour_numero_guia_ruta = $guia->guia_numero;
                            $historicoTour->historico_tour_descripcion = 'Modificación de ruta. VIN: ' . $vin->vin_codigo . ' añadido a la ruta de ' . $rutaModificar->ruta_origen . ' hasta ' . $rutaModificar->ruta_destino . '.';

                            if ($historicoTour->save()) {
                                flash('Modificación de ruta. Histórico de incorporación del VIN: ' . $vin->vin_codigo . ' en la ruta correctamente.')->success();
                            } else {
                                DB::rollback();
                                flash('Error guardando histórico de incorporación del VIN: ' . $vin->vin_codigo . ' en la ruta al modificarla.')->error();
                                return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)])->withInput();
                            }
                        } else {
                            flash('Error. VIN: ' . $codigo . 'no existe en la base de datos.')->error();
                        }
                    }

                    // Si se eliminaron VINs de una guía se buscan para eliminarlos de la BD
                    // Es una verificación adicional para no dejar registros de más (erróneos).
                    $array_vin_cods = Vin::join('guia_vin_rutas', 'guia_vin_rutas.vin_id', '=', 'vins.vin_id')
                        ->where('guia_vin_rutas.guia_id', $request->guia_id[$i])
                        ->select('vins.vin_codigo')
                        ->pluck('vins.vin_codigo');

                    foreach($array_vin_cods as $vin_cod){
                        if(!in_array($vin_cod, $arreglo_vins)){
                            $vin_eliminar_id = Vin::where('vin_codigo', $vin_cod)
                                ->value('vin_id');

                            GuiaVinRuta::where('vin_id', $vin_eliminar_id)
                                ->where('guia_id', $request->guia_id[$i])
                                ->delete();

                            $cuenta++;

                            $historicoTour = new HistoricoTour();
                            $historicoTour->tour_id = $id_tour;
                            $historicoTour->ruta_id = $request->ruta_id[$i];
                            $historicoTour->vin_id = $vin_eliminar_id;
                            $historicoTour->cliente_id = $guia->empresa_id;
                            $historicoTour->historico_tour_fecha_inicio = $tour->tour_fec_inicio;
                            $historicoTour->proveedor_id = $tour->proveedor_id;
                            $historicoTour->historico_tour_numero_guia_ruta = $guia->guia_numero;
                            $historicoTour->historico_tour_descripcion = 'Modificación de ruta. VIN: ' . $vin_cod . ' eliminado de la ruta de ' . $rutaModificar->ruta_origen . ' hasta ' . $rutaModificar->ruta_destino . '.';

                            if ($historicoTour->save()) {
                                flash('Modificación de ruta. Histórico de eliminación del VIN: ' . $vin_cod . ' de la ruta registrado correctamente.')->success();
                            } else {
                                DB::rollback();
                                flash('Error guardando histórico de eliminación del VIN: ' . $vin_cod . ' de la ruta al modificarla.')->error();
                                return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)])->withInput();
                            }
                        }
                    }

                    if($cuenta > 0){
                        // Lista de vins modificada. Se aceptan los cambios a la base de datos.
                        flash('La ruta: '. $request->ruta_origen[$i] . ' - ' . $request->ruta_destino[$i] . ' con guía:' . $guia->guia_numero . ' se modificó correctamente.')->success();
                    } else {
                        if ($cambioGuia){
                            // No se añadieron ni eliminaron vins en la ruta pero cambió la guía.
                            flash('Guía modificada para la ruta: '. $request->ruta_origen[$i] . ' - ' . $request->ruta_destino[$i] .'.')->success();
                        } else {
                            // No se añadieron ni eliminaron vins en la ruta. No hay modificación.
                            flash('Sin cambios en ruta: '. $request->ruta_origen[$i] . ' - ' . $request->ruta_destino[$i] .' con guía:' . $guia->guia_numero . '.' )->success();
                        }
                    }

                    DB::commit();
                } else{
                    // Este es el caso de cuando viene vacía la casilla de VINs.
                    // Se elimina la guia
                    flash('Guía: '. $guiaOriginal->guia_numero .' eliminada correctamente.')->success();

                    $guiaOriginal->delete();

                    DB::commit();
                }
            }  catch (\Exception $e) {
                DB::rollBack();
                flash('Error al actualizar rutas del Tour.')->error();
                flash($e->getMessage())->error();
                return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)])->withInput();
            }
        }

        flash('Rutas del tour actualizadas correctamente.')->success();
        return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
    }
}
