<?php

namespace App\Http\Controllers;

use App\Camion;
use App\Conductor;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Guia;
use App\GuiaVins;
use App\Remolque;
use App\Tour;
use App\Rutas;
use App\RutasVin;
use App\Vin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Crypt;

class TourController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(PreventBackHistory::class);
        $this->middleware(CheckSession::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $conductor = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS conductor_nombres"), 'users.user_id')
            ->join('conductors', 'users.user_id', '=', 'conductors.user_id' )
            ->where('users.deleted_at', null)
            ->pluck('conductor_nombres', 'users.user_id')
            ->all();

        $camion = Camion::select('camion_id', 'camion_patente')
            ->orderBy('camion_id')
            ->where('deleted_at', null)
            ->pluck('camion_patente', 'camion_id')
            ->all();

        $remolque = Remolque::select('remolque_id', 'remolque_patente')
            ->orderBy('remolque_id')
            ->pluck('remolque_patente', 'remolque_id')
            ->all();

        $transporte = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('empresa_es_proveedor', true)
            ->where('tipo_proveedor_id', 8)
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $tour = Tour::all();

        return view('transporte.index', compact('tour', 'empresas', 'camion', 'transporte', 'remolque', 'conductor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $conductor_id = Conductor::where('user_id',$request->conductor_id)
            ->first();

        $camion_id = Camion::where('camion_id',$request->camion_id)
            ->first();

        $remolque_id = Remolque::where('remolque_id',$request->remolque_id)
            ->first();

        $fecha_viaje = new Carbon($request->tour_fecha_inicio);

        $licencia_valida = new Carbon($conductor_id->conductor_fecha_vencimiento);

        $revision_remolque = new Carbon($remolque_id->remolque_fecha_revision);
        $permiso_remolque = new Carbon($remolque_id->remolque_fecha_circulacion);

        $permiso_camion = new Carbon($camion_id->camion_fecha_circulacion);
        $revision_camion = new Carbon($camion_id->camion_fecha_revision);

/*
        $diferencia_licencia = $licencia_valida->diff($fecha_viaje)->days;
        $diferencia_revision_camion = $revision_camion->diff($fecha_viaje)->days;
        $diferencia_permiso_camion = $permiso_camion->diff($fecha_viaje)->days;
        $diferencia_revision_remolque = $revision_remolque->diff($fecha_viaje)->days;
        $diferencia_permiso_remolque = $permiso_remolque->diff($fecha_viaje)->days;

*/

        $diferencia_licencia = $fecha_viaje->diffInDays($licencia_valida);
        $diferencia_revision_camion = $fecha_viaje->diffInDays($revision_camion);
        $diferencia_permiso_camion = $fecha_viaje->diffInDays($permiso_camion);
        $diferencia_revision_remolque = $fecha_viaje->diffInDays($revision_remolque);
        $diferencia_permiso_remolque = $fecha_viaje->diffInDays($permiso_remolque);



        if($diferencia_licencia <= 15 || $fecha_viaje > $licencia_valida )
        {
            flash('No se puede crear el tour con este conductor, licencia de conducir vencida o a punto de vencer')->error();
            return redirect('tour');
        }

        if($diferencia_revision_camion <= 15 || $diferencia_permiso_camion <= 15 || $fecha_viaje > $permiso_camion || $fecha_viaje > $revision_camion)
        {
            flash('No se puede crear el tour con este camión, debe revisar el permisos de circulación o fecha de revisión del mismo')->error();
            return redirect('tour');
        }

        if($diferencia_revision_remolque <= 15 || $diferencia_permiso_remolque <= 15 || $fecha_viaje > $revision_remolque || $fecha_viaje > $revision_remolque)
        {
            flash('No se puede crear el tour con este remolque, debe revisar el permisos de circulación o fecha de revisión del mismo')->error();
            return redirect('tour');
        }


        try {

            $tour = new Tour();
            $tour->camion_id = $request->camion_id;
            // $tour->cliente_id = $request->cliente_id;
            $tour->remolque_id = $request->remolque_id;
            $tour->proveedor_id = $request->transporte_id;
            $tour->conductor_id = $request->conductor_id;
            $tour->tour_fec_inicio = $request->tour_fecha_inicio;
            $tour->tour_finalizado = false;
            $tour->save();

            $id_tour = $tour->tour_id;

            flash('El Tour se creo correctamente.')->success();
            return view('transporte.addrutas', compact('id_tour', 'empresas'));

        }catch (\Exception $e) {

            flash('Error al crear el Tour.')->error();
           //flash($e->getMessage())->error();
            return redirect('tour');
        }


    }

    public function addrutas()
    {
        return view('transporte.addrutas');
    }


    public function crearutas(Request $request)
    {
        $id_tour = $request->id_tour;

        try
        {
            DB::beginTransaction();
            $ruta_existe = Rutas::where('tour_id', $id_tour)
                ->where('ruta_origen', $request->origen)
                ->where('ruta_destino', $request->destino)
                ->exists();

            $cuenta = 0;

            $ruta = null;
                    
            // Si no existe la ruta se crea la ruta en la base de datos asociada al tour
            if(!$ruta_existe){
                $ruta = new Rutas();
                $ruta->ruta_origen = $request->origen;
                $ruta->ruta_destino = $request->destino;
                $ruta->tour_id = $id_tour;
            
                $ruta->save()
            } else{
                // Existe la ruta, entonces se consulta el registro para usar más adelante.
                $ruta = Rutas::where('tour_id', $id_tour)->first();
            }

            $guia = new Guia();
            $guia->guia_fecha = $request->guia_fecha;
            $guia->empresa_id = $request->empresa_id;

            $path = "";

            if($request->file('guia_ruta') !== null){
                $fotoGuiaTour = $request->file('guia_ruta');
                $extensionFoto = $fotoGuiaTour->extension();
                $path = $fotoGuiaTour->storeAs(
                    'guiasTour',
                    "guia de tour ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                );
            }

            $guia->guia_ruta = $path;

            if($guia->save()){
                DB::insert('INSERT INTO ruta_guias (ruta_id, guia_id) VALUES (?, ?)', [$ruta->ruta_id, $guia->guia_id]);
            }

            if(!empty($request->vin_numero)){
                // Si el campo de VINs del formulario viene con VINS
                // Se procede a extraer uno por uno los VINS separados por salto de línea
                foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                    if($row !== ""){
                        $arreglo_vins[] = trim($row);
                    }
                }

                // Por cada uno de los VINs pasados desde el formulario realizar las acciones correspondientes
                foreach($arreglo_vins as $v){
                    // Validar si el VIN existe en la base de datos.
                    $validate = DB::table('vins')
                        ->where('vin_codigo', $v)
                        ->orWhere('vin_patente', $v)
                        ->exists();

                    if($validate == true)
                    {
                        // Existe el VIN, luego se procede a consultar el registro para usarlo.
                        $vin = DB::table('vins')
                            ->where('vin_codigo', $v)
                            ->orWhere('vin_patente', $v)
                            ->first();

                        // Validar si ya existe previamente un registro donde el VIN esté asociado a una guía 
                        $val2 = GuiaVins::where('vin_id', $vin->vin_id)->exists();

                        if(!$val2){
                            // Si no existe la asociación, entonces se asocia el VIN a la guía $guia
                            $guiavin = new GuiaVins();
                            $guiavin->vin_id = $vin->vin_id;
                            $guiavin->guia_id = $guia->guia_id;
                            $guiavin->save();
                            $cuenta++;
                        } else {
                            // Si existe la asociación, entonces se consulta el registro correspondiente.
                            $guia_vin = GuiaVins::where('vin_id', $vin->vin_id)->first();

                            // SOLO DEBE EXISTIR UN REGISTRO DEL VIN ASOCIADO CON UNA GUÍA
                            if($guia->guia_id !== $guia_vin->guia_id){
                                // Si la guía actual no es la misma guía donde estaba registrado el vin
                                // se elimina la asociación anterior y se crea una nueva.
                                GuiaVins::where('vin_id', $vin->vin_id)->delete();

                                $guiavin = new GuiaVins();
                                $guiavin->vin_id = $vin->vin_id;
                                $guiavin->guia_id = $guia->guia_id;
                                $guiavin->save();   
                                $cuenta++;   
                            }
                        }
                    } else {
                        flash('¡Error! VIN: ' . $v . 'no existe en la base de datos.')->error();
                    }
                }

                if($cuenta > 0){
                    // Se añadieron los vins a la guía. Se aceptan los cambios a la base de datos.
                    DB::commit();
                    flash('La ruta se agregó correctamente.')->success();
                    return view('transporte.addrutas', compact('id_tour'));
                } else {
                    // No se añadieron vins a la guía, se echa para atrás el cambio a la base de datos
                    DB::rollBack();
                    flash('Error: No se creó la ruta. VINs no válidos')->error();
                    return view('transporte.addrutas', compact('id_tour'));
                }
            } else{
                DB::rollBack();
                flash('Error: Debe proporcionar al menos un VIN válido.')->error();
                return view('transporte.addrutas', compact('id_tour'));
            }
        }  catch (\Exception $e) {

            DB::rollBack();
            flash('Error al añadir las rutas al Tour.')->error();
            flash($e->getMessage())->error();
            return redirect('tour');
        }
    }

    public function crearutas2(Request $request)
    {
        $id_tour = $request->id_tour;

        try
        {
            DB::beginTransaction();
            $ruta_existe = Rutas::where('tour_id', $id_tour)
                ->where('ruta_origen', $request->origen_id)
                ->where('ruta_destino', $request->destino_id)
                ->exists();

            $cuenta = 0;

            $ruta = null;

            // Si no existe la ruta se crea la ruta en la base de datos asociada al tour
            if(!$ruta_existe){
                $ruta = new Rutas();
                $ruta->ruta_origen = $request->origen;
                $ruta->ruta_destino = $request->destino;
                $ruta->tour_id = $id_tour;
            
                $ruta->save()
            } else{
                // Existe la ruta, entonces se consulta el registro para usar más adelante.
                $ruta = Rutas::where('tour_id', $id_tour)->first();
            }

            $guia = new Guia();
            $guia->guia_fecha = $request->guia_fecha;
            $guia->empresa_id = $request->empresa_id;

            $path = "";

            if($request->file('guia_ruta') !== null){
                $fotoGuiaTour = $request->file('guia_ruta');
                $extensionFoto = $fotoGuiaTour->extension();
                $path = $fotoGuiaTour->storeAs(
                    'guiasTour',
                    "guia de tour ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                );
            }

            $guia->guia_ruta = $path;

            if($guia->save()){
                DB::insert('INSERT INTO ruta_guias (ruta_id, guia_id) VALUES (?, ?)', [$ruta->ruta_id, $guia->guia_id]);
            }

            if(!empty($request->vin_numero)){
                // Si el campo de VINs del formulario viene con VINS
                // Se procede a extraer uno por uno los VINS separados por salto de línea
                foreach(explode(PHP_EOL,$request->vin_numero) as $row){
                    if($row !== ""){
                        $arreglo_vins[] = trim($row);
                    }
                }

                // Por cada uno de los VINs pasados desde el formulario realizar las acciones correspondientes
                foreach($arreglo_vins as $v){
                    // Validar si el VIN existe en la base de datos.
                    $validate = DB::table('vins')
                        ->where('vin_codigo', $v)
                        ->orWhere('vin_patente', $v)
                        ->exists();

                    if($validate == true)
                    {
                        // Existe el VIN, luego se procede a consultar el registro para usarlo.
                        $vin = DB::table('vins')
                            ->where('vin_codigo', $v)
                            ->orWhere('vin_patente', $v)
                            ->first();

                        // Validar si ya existe previamente un registro donde el VIN esté asociado a una guía 
                        $val2 = GuiaVins::where('vin_id', $vin->vin_id)->exists();


                        if(!$val2){
                            // Si no existe la asociación, entonces se asocia el VIN a la guía $guia
                            $guiavin = new GuiaVins();
                            $guiavin->vin_id = $vin->vin_id;
                            $guiavin->guia_id = $guia->guia_id;
                            $guiavin->save();
                            $cuenta++;
                        } else {
                            // Si existe la asociación, entonces se consulta el registro correspondiente.
                            $guia_vin = GuiaVins::where('vin_id', $vin->vin_id)->first();

                            // SOLO DEBE EXISTIR UN REGISTRO DEL VIN ASOCIADO CON UNA GUÍA
                            if($guia->guia_id !== $guia_vin->guia_id){
                                // Si la guía actual no es la misma guía donde estaba registrado el vin
                                // se elimina la asociación anterior y se crea una nueva.
                                GuiaVins::where('vin_id', $vin->vin_id)->delete();

                                $guiavin = new GuiaVins();
                                $guiavin->vin_id = $vin->vin_id;
                                $guiavin->guia_id = $guia->guia_id;
                                $guiavin->save();   
                                $cuenta++;   
                            }
                        }
                    } else {
                        flash('Error. VIN: ' . $v . 'no existe en la base de datos.')->error();
                    }
                }

                if($cuenta > 0){
                    // Se añadieron vins a la ruta. Se aceptan los cambios a la base de datos.
                    DB::commit();
                    flash('La ruta se agrego correctamente.')->success();
                    return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                } else {
                    // No se añadieron vins a la ruta, se echa para atrás el cambio a la base de datos
                    flash('Error: No se creó la ruta. VINs no válidos')->error();
                    DB::rollBack();
                    return view('tour.editrutas', compact('id_tour'));
                }
            } else{
                DB::rollBack();
                flash('Error: Debe proporcionar al menos un VIN válido.')->error();
                return view('tour.editrutas', compact('id_tour'));
            }
        }  catch (\Exception $e) {

            DB::rollBack();
            flash('Error al editar el Tour. Modifiaciones canceladas')->error();
            flash($e->getMessage())->error();
            return redirect('tour');
        }
    }


    // Esta función debe revisarse en virtud de que ahora sólo habrá una guía por destino
    // dado que los destinos son direcciones específicas. Y por esta razón cada guía es para
    // una dirección específica. Se debe proporcionar la posibilidad de modificar toda la información
    // de la ruta y su guía respectiva, hasta el punto de desechar por completo la información anterior.
    public function editrutas($id)
    {
        $tour_id =  Crypt::decrypt($id);
        $tour = Tour::findOrfail($tour_id);

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        $rutas = DB::table('tours')
            ->join('rutas','rutas.tour_id','=','tours.tour_id')
            ->where('tours.tour_id', $tour_id)
            ->where('rutas.deleted_at', null)
            ->select()
            ->get();

        $guia_vins = DB::table('tours')
            ->join('rutas','rutas.tour_id','=','tours.tour_id')
            ->join('ruta_guias','ruta_guias.ruta_id','=', 'rutas.ruta_id')
            ->join('guia_vins','guia_vins.guia_id','=', 'ruta_guias.guia_id')
            ->join('vins','vins.vin_id','=','guia_vins.vin_id')
            ->where('tours.tour_id', $tour_id)
            ->select()
            ->get();       

        $rutas_array = [];
        $i = 0;
        $enc = false;
        // Revisar a partir de acá para acomodar de nuevo la lógica de cómo se envían
        // los datos al formulario de edición de ruta + guías.
        foreach($rutas as $ruta){
            if ($i == 0){
                $rutas_array = [[$ruta->ruta_origen, $ruta->ruta_destino]];
            } else {
                for($j = 0; $j < count($rutas_array); $j++){
                    if(($ruta->ruta_origen == $rutas_array[$j][0]) && ($ruta->ruta_destino == $rutas_array[$j][1])){
                        $enc = true;
                    } else {
                        continue;
                    }
                }
                if(!$enc){
                    array_push($rutas_array, [$ruta->ruta_origen, $ruta->ruta_destino]);
                } 
                $enc = false;
            }
            $i++;
        }

        $vins_guia_array = [];
        foreach ($rutas_array as $ruta){
            $cadena_vins = "";
            foreach($guia_vins as $guia_vin){
                if(($ruta[0] == $vin_ruta->ruta_origen) && ($ruta[1] == $vin_ruta->ruta_destino)){
                    $cadena_vins .= $vin_ruta->vin_codigo . "\n";
                }
            }
            
            array_push($vins_ruta_array, [$ruta, $cadena_vins]);
        }

        return view('transporte.editrutas', compact('tour_id', 'rutas','vin_ruta', 'vins_ruta_array', 'empresas'));
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

            foreach(explode(PHP_EOL,$request->vin_numero[$i]) as $row){
                if($row !== ""){
                    $arreglo_vins[] = trim($row);
                }
            }

            try
            {
                DB::beginTransaction();

                if($arreglo_vins !== []){
                    $cuenta = 0;

                    foreach($arreglo_vins as $codigo){
                        if($request->file('guia_ruta') !== null){
                            $fotoGuiaTour = $request->file('guia_ruta');
                            $extensionFoto = $fotoGuiaTour->extension();
                            $path = $fotoGuiaTour->storeAs(
                                'guiasTour',
                                "guia de tour ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                            );
                        }


                        // Buscar si la ruta ya existe para este tour
                        $ruta_existe = Rutas::where('tour_id', $id_tour)
                            ->where('ruta_origen', $request->origen_id[$i])
                            ->where('ruta_destino', $request->destino_id[$i])
                            ->exists();
                        
                        // Si no existe la ruta se crea la ruta en la base de datos asociada al tour
                        if(!$ruta_existe){
                            $ruta = new Rutas();
                            $ruta->ruta_origen = $request->origen_id[$i];
                            $ruta->ruta_destino = $request->destino_id[$i];
                            $ruta->tour_id = $id_tour;
                            if(isset($path)){
                                $ruta->ruta_guia = $path;
                            }
                        
                            $ruta->save();
                        } else{
                            // Si la ruta sí existe, entonces se consulta.
                            $ruta = Rutas::where('tour_id', $id_tour)
                                ->where('ruta_origen', $request->origen_id[$i])
                                ->where('ruta_destino', $request->destino_id[$i])
                                ->first();
                        }
                                            
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
                                
                            // Validar si existe asociación de este VIN con rutas anteriores.
                            $val2 = RutasVin::where('vin_id', $vin->vin_id)->exists();
                            
                            // Si no existe ninguna asociación del VIN con otra ruta, se crea la asociación
                            // correspondiente con esta ruta
                            if(!$val2){
                                $rutavin = new RutasVin();
                                $rutavin->vin_id = $vin->vin_id;
                                $rutavin->ruta_id = $ruta->ruta_id;
                                $rutavin->save();

                                $cuenta++;
                            } else {
                                $ruta_vin = RutasVin::where('vin_id', $vin->vin_id)->first();
                                
                                // Si ya existe otra asociación vin-ruta, entonces se elimina para crear
                                // la nueva asociación. En caso contrario, no se hace nada nuevo.
                                if($ruta->ruta_id !== $ruta_vin->ruta_id){
                                    RutasVin::where('vin_id', $vin->vin_id)
                                        ->where('ruta_id', $ruta_vin->ruta_id)
                                        ->delete();

                                    $rutavin = new RutasVin();
                                    $rutavin->vin_id = $vin->vin_id;
                                    $rutavin->ruta_id = $ruta->ruta_id;
                                    $rutavin->save();
                                    
                                    $cuenta++;
                                }
                            }
                        } else {
                            flash('Error. VIN: ' . $codigo . 'no existe en la base de datos.')->error();
                        }
                    }


                    // Si se eliminaron VINs de una ruta se buscan para eliminarlos de la BD
                    // Es una verificación adicional para no dejar registros de más (erróneos).
                    $ruta = Rutas::where('tour_id', $id_tour)
                                ->where('ruta_origen', $request->origen_id[$i])
                                ->where('ruta_destino', $request->destino_id[$i])
                                ->first();

                    $array_vin_cods = Vin::join('rutas_vins', 'rutas_vins.vin_id', '=', 'vins.vin_id')
                        ->where('rutas_vins.ruta_id', $ruta->ruta_id)
                        ->select('vins.vin_codigo')
                        ->pluck('vins.vin_codigo');

                    foreach($array_vin_cods as $vin_cod){
                        if(array_search($vin_cod, $arreglo_vins) === false){
                            $vin_eliminar_id = Vin::where('vin_codigo', $vin_cod)
                                ->value('vin_id');
                            
                            RutasVin::where('vin_id', $vin_eliminar_id)
                                ->where('ruta_id', $ruta_vin->ruta_id)
                                ->delete();
                            
                            $cuenta++;
                        }
                    }


                    if($cuenta > 0){
                        // Se añadieron vins a la ruta. Se aceptan los cambios a la base de datos.
                        DB::commit();
                        flash('La ruta: '. $request->origen_id[$i] . ' - ' . $request->destino_id[$i] .' se modificó correctamente.')->success();                        
                    } else {
                        // No se añadieron ni eliminaron vins en la ruta. No hay modificación.
                        flash('No se modificó la ruta: '. $request->origen_id[$i] . ' - ' . $request->destino_id[$i] .'.')->success();
                    } 
                } else{
                    // Este es el caso de cuando viene vacía la casilla de código.
                    // Se elimina la ruta
                    Rutas::where('tour_id', $id_tour)
                        ->where('ruta_origen', $request->origen_id[$i])
                        ->where('ruta_destino', $request->destino_id[$i])
                        ->delete();

                    flash('Ruta: '. $request->origen_id[$i] . ' - ' . $request->destino_id[$i] .' eliminada correctamente.')->success();
                    DB::commit();
                }
            }  catch (\Exception $e) {
                DB::rollBack();
                flash('Error al actualizar rutas del Tour.')->error();
                flash($e->getMessage())->error();
                return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
            }
        }
        
        flash('Rutas del tour actualizadas correctamente.')->success();
        return redirect('tour');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
