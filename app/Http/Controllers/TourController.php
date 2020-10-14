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
use App\GuiaVin;
use App\Remolque;
use App\Tour;
use App\Ruta;
use App\RutaGuia;
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
        return view('transporte.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tour()
    {
        $toursIniciales = Tour::all();
        
        $tours = [];

        foreach ($toursIniciales as $tour){
            $fecha_inicio = new Carbon($tour->tour_fec_inicio);
            
            if (($fecha_inicio < Carbon::today()) && (!$tour->tour_iniciado) && (!$tour->tour_finalizado)){
                $this->finalizarTourNoIniciado($tour->tour_id);
            } else {
                array_push($tours, $tour);
            }
        }

        $conductor = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS conductor_nombres"), 'users.user_id')
            ->join('conductors', 'users.user_id', '=', 'conductors.user_id' )
            ->where('users.deleted_at', null)
            ->pluck('conductor_nombres', 'users.user_id')
            ->all();

        $camion = Camion::select('camion_id', 'camion_patente')
            ->orderBy('camion_id')
            ->pluck('camion_patente', 'camion_id')
            ->all();

        $remolque = Remolque::select('remolque_id', 'remolque_patente')
            ->orderBy('remolque_id')
            ->pluck('remolque_patente', 'remolque_id')
            ->all();

        $transporte = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->where('empresa_es_proveedor', true)
            ->where('tipo_proveedor_id', 8)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        return view('transporte.tour', compact('tours', 'camion', 'transporte', 'remolque', 'conductor'));
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
        $fechaViaje = new Carbon($request->tour_fecha_inicio);

        if ($fechaViaje < Carbon::today()){
            flash('Error: Fecha incorrecta. La fecha no puede ser anterior al día actual.')->error();
            return back()->withInput();
        }

        $conductor = Conductor::where('user_id',$request->conductor_id)->first();

        if (!$this->validarData($conductor, 'conductor', $fechaViaje)){
            return back()->withInput();
        }

        $camion = Camion::where('camion_id', $request->camion_id)->first();

        if (!$this->validarData($camion, 'camion', $fechaViaje)){
            return back()->withInput();
        }

        $remolque = Remolque::where('remolque_id', $request->remolque_id)->first();

        if (!$this->validarData($remolque, 'remolque', $fechaViaje)){
            return back()->withInput();
        }

        $licenciaValida = new Carbon($conductor->conductor_fecha_vencimiento);

        $revisionRemolque = new Carbon($remolque->remolque_fecha_revision);
        $permisoRemolque = new Carbon($remolque->remolque_fecha_circulacion);

        $permisoCamion = new Carbon($camion->camion_fecha_circulacion);
        $revisionCamion = new Carbon($camion->camion_fecha_revision);

        // Asignación de fechas por Carbon
        $diferenciaLicencia = $fechaViaje->diffInDays($licenciaValida);
        $diferenciaRevisionCamion = $fechaViaje->diffInDays($revisionCamion);
        $diferenciaPermisoCamion = $fechaViaje->diffInDays($permisoCamion);
        $diferenciaRevisionRemolque = $fechaViaje->diffInDays($revisionRemolque);
        $diferenciaPermisoRemolque = $fechaViaje->diffInDays($permisoRemolque);

        // La licencia debe tener al menos 16 días de vigencia al momento del viaje. 
        // La fecha del viaje no puede ser posterior al vencimiento de la licencia
        if($diferenciaLicencia <= 15 || $fechaViaje > $licenciaValida )
        {
            flash('No se puede crear el tour con este conductor, licencia de conducir vencida o a punto de vencer')->error();
            return redirect()->action('TourController@tour')->withInput();
        }

        // La revisión y permiso de circulación del camión deben tener al menos 16 días de vigencia al momento del viaje.
        // La fecha del viaje no puede ser posterior al vencimiento de la revisión y/o permiso de circulación del camión.
        if($diferenciaRevisionCamion <= 15 || $diferenciaPermisoCamion <= 15 || $fechaViaje > $permisoCamion || $fechaViaje > $revisionCamion)
        {
            flash('No se puede crear el tour con este camión, debe revisar el permisos de circulación o fecha de revisión del mismo')->error();
            return redirect()->action('TourController@tour')->withInput();
        }

        // La revisión y permiso de circulación del remolque deben tener al menos 16 días de vigencia al momento del viaje.
        // La fecha del viaje no puede ser posterior al vencimiento de la revisión y/o permiso de circulación del remolque.
        if($diferenciaRevisionRemolque <= 15 || $diferenciaPermisoRemolque <= 15 || $fechaViaje > $revisionRemolque || $fechaViaje > $revisionRemolque)
        {
            flash('No se puede crear el tour con este remolque, debe revisar el permisos de circulación o fecha de revisión del mismo')->error();
            return redirect()->action('TourController@tour')->withInput();
        }

        try {
            $tour = new Tour();
            $tour->camion_id = $request->camion_id;
            $tour->remolque_id = $request->remolque_id;
            $tour->proveedor_id = $request->transporte_id;
            $tour->conductor_id = $request->conductor_id;
            $tour->tour_fec_inicio = $request->tour_fecha_inicio;
            $tour->tour_finalizado = false;
            $tour->tour_comentarios = "Tour agendado. Pendiente por iniciar";
            $tour->save();

            $id_tour = $tour->tour_id;

            flash('El Tour se creó correctamente. Ahora puede añadir las rutas.')->success();
            return redirect()->action('TourController@tour');

        }catch (\Exception $e) {
            flash('Error al crear el Tour.')->error();
           //flash($e->getMessage())->error();
            return redirect()->action('TourController@tour')->withInput();
        }
    }

    /**  Validar que un recurso: Conductor, Camión o Remolque no estén asignados a un viaje activo o
    * a realizarse en al menos 1 semana.
    *
    * Parámetros:
    * data: datos del modelo enviado
    * modelo: nombre del modelo en minúsculas
    * fechaViaje: fecha del nuevo viaje a realizarse
    */
    protected function validarData($data, $modelo, $fechaViaje)
    {
        $cadenaConsulta = $modelo . '_id';

        if ($modelo === 'conductor') {
            $id_modelo = $data->user_id;
        } else {
            $id_modelo = $data->$cadenaConsulta;
        }
        
        $noDisponible = Tour::where($cadenaConsulta, $id_modelo)
            ->where('tour_iniciado', true)
            ->where('tour_finalizado', false)
            ->exists();
        
        if($noDisponible){
            flash('Error: Conductor no disponible.')->error();
            return false;
        }
        
        $fechaOtroTour = Tour::where($cadenaConsulta, $id_modelo)
            ->where('tour_iniciado', false)
            ->where('tour_finalizado', false)
            ->orderByDesc('tour_fec_inicio')
            ->limit(1)
            ->value('tour_fec_inicio');

        if ($fechaOtroTour !== null){
            $fechaInicioOtroTour = new Carbon($fechaOtroTour);
        
            $diferenciaFechaNuevoTour = $fechaViaje->diffInDays($fechaInicioOtroTour);

            if($diferenciaFechaNuevoTour <= 7){
                flash('Error: ' . ucfirst($modelo) . ' con otro viaje agendado en menos de una semana.')->error();
                return false;
            }
        }
        
        return true;
    }

    public function addrutas()
    {
        return view('transporte.addrutas');
    }

    public function adminRutas()
    {
        return view('transporte.admin_rutas');
    }


    public function crearutas(Request $request)
    {
        $guia = Guia::where('guia_numero', $request->guia_numero)->first();

        // Verificar si la guía ya está asignada a una ruta existente.
        $existeGuia= false;

        if ($guia){
            $existeGuia = RutaGuia::where('guia_id', $guia->guia_id)
                ->exists();
        }

        if($existeGuia){
            return back()->with('error', 'Número de Guía ya asignada a otra ruta. Por favor intente con otra.')->withInput();
        }
        
        $id_tour = $request->id_tour;
        
        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();
        
        try
        {
            DB::beginTransaction();

            // Verificar si la ruta enviada existe o no.
            $ruta_existe = Ruta::where('tour_id', $id_tour)
                ->where('ruta_origen', $request->origen)
                ->where('ruta_destino', $request->destino)
                ->exists();

            $cuenta = 0;

            $ruta = null;
                    
            // Si no existe la ruta se crea la ruta en la base de datos asociada al tour
            if(!$ruta_existe){
                $ruta = new Ruta();
                $ruta->ruta_origen = $request->origen;
                $ruta->ruta_destino = $request->destino;
                $ruta->tour_id = $id_tour;
            
                $ruta->save();
            } else{
                // Existe la ruta, entonces se consulta el registro para usar más adelante.
                $ruta = Ruta::where('tour_id', $id_tour)->first();
            }

            $guia = new Guia();
            $guia->guia_fecha = $request->guia_fecha;
            $guia->guia_numero = trim($request->guia_numero);
            $guia->empresa_id = $request->empresa_id;
            
            // $path = "";

            // if($request->file('guia_ruta') !== null){
            //     $fotoGuiaTour = $request->file('guia_ruta');
            //     $extensionFoto = $fotoGuiaTour->extension();
            //     $path = $fotoGuiaTour->storeAs(
            //         'guiasTour',
            //         "guia de tour ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
            //     );
            // }

            // $guia->guia_ruta = $path;
            
            // Si se almacena exitosamente la guía, entonces se crea la relación entre la guía y la ruta
            if($guia->save()){
                $rutaGuia = new RutaGuia();
                $rutaGuia->ruta_id = $ruta->ruta_id;
                $rutaGuia->guia_id = $guia->guia_id;
                $rutaGuia->save();
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
                    
                    if($validate)
                    {
                        // Existe el VIN, luego se procede a consultar el registro para usarlo.
                        $vin = Vin::where('vin_codigo', $v)
                            ->orWhere('vin_patente', $v)
                            ->first();
                        
                        // Validar si ya existe previamente un registro donde el VIN esté asociado a una guía 
                        $val2 = GuiaVin::where('vin_id', $vin->vin_id)->exists();
                        
                        if(!$val2){
                            // Si no existe la asociación, entonces se asocia el VIN a la guía $guia
                            $guiavin = new GuiaVin();
                            $guiavin->vin_id = $vin->vin_id;
                            $guiavin->guia_id = $guia->guia_id;
                            $guiavin->save();
                            $cuenta++;
                        } else {
                            // Si existe la asociación, entonces se consulta el registro correspondiente.
                            $guia_vin = GuiaVin::where('vin_id', $vin->vin_id)->first();

                            // SOLO DEBE EXISTIR UN REGISTRO DEL VIN ASOCIADO CON UNA GUÍA
                            if($guia->guia_id !== $guia_vin->guia_id){
                                // Si la guía actual no es la misma guía donde estaba registrado el vin
                                // se elimina la asociación anterior y se crea una nueva.
                                GuiaVin::where('vin_id', $vin->vin_id)->delete();

                                $guiavin = new GuiaVin();
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
                    // Se añadieron los vins a la ruta. Se aceptan los cambios a la base de datos.
                    DB::commit();
                    flash('La ruta se agregó correctamente.')->success();
                    return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                } else {
                    // No se añadieron vins a la ruta, se echa para atrás el cambio a la base de datos
                    flash('Error: No se creó la ruta. VINs no válidos')->error();
                    DB::rollBack();
                    return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                }

                // if($cuenta > 0){
                //     // Se añadieron los vins a la ruta. Se aceptan los cambios a la base de datos.
                //     DB::commit();
                //     flash('La ruta se agregó correctamente.')->success();
                //     return view('transporte.addrutas', compact('id_tour', 'empresas'));
                // } else {
                //     // No se añadieron vins a la guía, se echa para atrás el cambio a la base de datos
                //     DB::rollBack();
                //     flash('Error: No se creó la ruta. VINs no válidos')->error();
                //     return view('transporte.addrutas', compact('id_tour', 'empresas'));
                // }
            } else{
                DB::rollBack();
                flash('Error: Debe proporcionar al menos un VIN válido.')->error();
                return view('transporte.editrutas', compact('id_tour', 'empresas'));
            }
        }  catch (\Exception $e) {

            DB::rollBack();
            flash('Error al añadir las rutas al Tour.')->error();
            flash($e->getMessage())->error();
            return redirect('tour.tour');
        }
    }

    public function crearutas2(Request $request)
    {
        dd("Aquí, pero no debe ser aquí.");
        $existeGuia = Guia::where('guia_numero', $request->guia_numero)
            ->exists();

        if($existeGuia){
            return back()->with('error', 'Número de Guía ya existente en base de datos. Por favor intente con otra.');
        }

        $id_tour = $request->id_tour;
        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();

        try
        {
            DB::beginTransaction();
            $ruta_existe = Ruta::where('tour_id', $id_tour)
                ->where('ruta_origen', $request->origen_id)
                ->where('ruta_destino', $request->destino_id)
                ->exists();

            $cuenta = 0;

            $ruta = null;

            // Si no existe la ruta se crea la ruta en la base de datos asociada al tour
            if(!$ruta_existe){
                $ruta = new Ruta();
                $ruta->ruta_origen = $request->origen;
                $ruta->ruta_destino = $request->destino;
                $ruta->tour_id = $id_tour;
            
                $ruta->save();
            } else{
                // Existe la ruta, entonces se consulta el registro para usar más adelante.
                $ruta = Ruta::where('tour_id', $id_tour)->first();
            }

            $guia = new Guia();
            $guia->guia_fecha = $request->guia_fecha;
            $guia->guia_numero = $request->guia_numero;
            $guia->empresa_id = $request->empresa_id;

            // $path = "";

            // if($request->file('guia_ruta') !== null){
            //     $fotoGuiaTour = $request->file('guia_ruta');
            //     $extensionFoto = $fotoGuiaTour->extension();
            //     $path = $fotoGuiaTour->storeAs(
            //         'guiasTour',
            //         "guia de tour ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
            //     );
            // }

            // $guia->guia_ruta = $path;

            if($guia->save()){
                DB::insert('INSERT INTO ruta_guias (ruta_id, guia_id, created_at, updated_at) VALUES (?, ?, ?, ?)', [$ruta->ruta_id, $guia->guia_id, Carbon::now(), Carbon::now()]);
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
                        $val2 = GuiaVin::where('vin_id', $vin->vin_id)->exists();


                        if(!$val2){
                            // Si no existe la asociación, entonces se asocia el VIN a la guía $guia
                            $guiavin = new GuiaVin();
                            $guiavin->vin_id = $vin->vin_id;
                            $guiavin->guia_id = $guia->guia_id;
                            $guiavin->save();
                            $cuenta++;
                        } else {
                            // Si existe la asociación, entonces se consulta el registro correspondiente.
                            $guia_vin = GuiaVin::where('vin_id', $vin->vin_id)->first();

                            // SOLO DEBE EXISTIR UN REGISTRO DEL VIN ASOCIADO CON UNA GUÍA
                            if($guia->guia_id !== $guia_vin->guia_id){
                                // Si la guía actual no es la misma guía donde estaba registrado el vin
                                // se elimina la asociación anterior y se crea una nueva.
                                GuiaVin::where('vin_id', $vin->vin_id)->delete();

                                $guiavin = new GuiaVin();
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
                    return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
                }
            } else{
                DB::rollBack();
                flash('Error: Debe proporcionar al menos un VIN válido.')->error();
                return redirect()->route('tour.editrutas', ['id' => Crypt::encrypt($id_tour)]);
            }
        }  catch (\Exception $e) {

            DB::rollBack();
            flash('Error al editar el Tour. Modifiaciones canceladas')->error();
            flash($e->getMessage())->error();
            return redirect('tour');
        }
    }

    public function editrutas($id)
    {
        $tour_id =  Crypt::decrypt($id);
        $tour = Tour::findOrfail($tour_id);
        
        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_razon_social')
            ->pluck('empresa_razon_social', 'empresa_id')
            ->all();
        
        $guia_vins = GuiaVin::join('guias','guias.guia_id','=','guia_vins.guia_id')
            ->join('ruta_guias','ruta_guias.guia_id','=', 'guias.guia_id')
            ->join('rutas','rutas.ruta_id','=','ruta_guias.ruta_id')
            ->join('vins','vins.vin_id','=','guia_vins.vin_id')
            ->where('rutas.tour_id', $tour_id)
            ->select('guia_vin_id', 'guia_vins.vin_id', 'guia_vins.guia_id', 'vin_codigo')
            ->get(); 

        $viajes = Tour::join('rutas','rutas.tour_id','=','tours.tour_id')
            ->join('ruta_guias','ruta_guias.ruta_id','=', 'rutas.ruta_id')
            ->join('guias','guias.guia_id','=','ruta_guias.guia_id')
            ->where('tours.tour_id', $tour_id)
            ->get();
            
        $vins_guia_array = [];
        $fecha_guias_array = [];

        foreach ($viajes as $viaje){
            $cadena_vins = "";
            $e = 0;
            $empresa_id = 0;
            $guia_numero = '';
            $guia_id = 0;
            $ruta_id = 0;

            $ruta_simple = [$viaje->ruta_origen, $viaje->ruta_destino];
            
            foreach($guia_vins as $guia_vin){
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
            $cadena_vins = "";
            $empresa_id = 0;
            $guia_numero = '';
            $guia_fecha = '';
            $guia_id = 0;
            $ruta_id = 0;
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

            foreach(explode(PHP_EOL,$request->vin_numero[$i]) as $row){
                if($row !== ""){
                    $arreglo_vins[] = trim($row);
                }
            }
            
            try
            {
                DB::beginTransaction();

                $path = "";
                $fotoGuiaTour = $request->file('guia_ruta');
                
                if($fotoGuiaTour && array_key_exists($i, $fotoGuiaTour)){
                    $extensionFoto = $fotoGuiaTour[$i]->extension();
                    $path = $fotoGuiaTour[$i]->storeAs(
                        'guiasTour',
                        "guia de tour ".'- '.Auth::id().' - '.date('Y-m-d').' - '.\Carbon\Carbon::now()->timestamp.'.'.$extensionFoto
                    );
                    
                    $guia = new Guia();
                    $guia->guia_fecha = $request->guia_fecha[$i];
                    $guia->empresa_id = $request->empresa_id[$i];
                    $guia->guia_ruta = $path;
                } else {
                    $guia = Guia::findOrFail($request->guia_id[$i]);
                    $guia->guia_fecha = $request->guia_fecha[$i];
                    $guia->empresa_id = $request->empresa_id[$i];
                }
                
                // $guia->save();

                // Buscar si la ruta ya existe para este tour
                $ruta_existe = Ruta::where('tour_id', $id_tour)
                    ->where('ruta_origen', $request->origen_id[$i])
                    ->where('ruta_destino', $request->destino_id[$i])
                    ->exists();
                
                $ruta = null;

                // Si no existe la ruta se crea la ruta en la base de datos asociada al tour
                if(!$ruta_existe){
                    $ruta = new Ruta();
                    $ruta->ruta_origen = $request->origen_id[$i];
                    $ruta->ruta_destino = $request->destino_id[$i];
                    $ruta->tour_id = $id_tour;
            
                    // $ruta->save();

                    // DB::insert('INSERT INTO ruta_guias (ruta_id, guia_id) VALUES (?, ?)', [$ruta->ruta_id, $guia->guia_id]);
                } else{
                    // Si la ruta sí existe, entonces se consulta.
                    $ruta = Ruta::where('tour_id', $id_tour)
                        ->where('ruta_origen', $request->origen_id[$i])
                        ->where('ruta_destino', $request->destino_id[$i])
                        ->first();
                }
                
                if($arreglo_vins !== []){
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
                            
                            // Validar si existe asociación de este VIN con guías anteriores.
                            $val2 = GuiaVin::where('vin_id', $vin->vin_id)->exists();
                            
                            // Si no existe ninguna asociación del VIN con otra guía, se crea la asociación
                            // correspondiente con esta guía
                            if(!$val2){
                                $guiavin = new GuiaVin();
                                $guiavin->vin_id = $vin->vin_id;
                                $guiavin->guia_id = $guia->guia_id;
                                $guiavin->save();
                                
                                $cuenta++;
                            } else {
                                $guia_vin = GuiaVin::where('vin_id', $vin->vin_id)->first();
                                
                                // Si ya existe otra asociación vin-guía, entonces se elimina para crear
                                // la nueva asociación. En caso contrario, no se hace nada nuevo.
                                if($guia->guia_id !== $guia_vin->guia_id){
                                    GuiaVin::where('vin_id', $vin->vin_id)
                                        ->where('guia_id', $guia_vin->guia_id)
                                        ->delete();

                                    $guiavin = new GuiaVin();
                                    $guiavin->vin_id = $vin->vin_id;
                                    $guiavin->guia_id = $guia->guia_id;
                                    $guiavin->save();
                                    
                                    $cuenta++;
                                }
                            }
                        } else {
                            flash('Error. VIN: ' . $codigo . 'no existe en la base de datos.')->error();
                        }
                    }


                    /// VERIFICAR LA LÓGICA A PARTIR DE ACÁ.
                    // Si se eliminaron VINs de una guía se buscan para eliminarlos de la BD
                    // Es una verificación adicional para no dejar registros de más (erróneos).
                    $array_vin_cods = Vin::join('guia_vins', 'guia_vins.vin_id', '=', 'vins.vin_id')
                        ->where('guia_vins.guia_id', $request->guia_id[$i])
                        ->select('vins.vin_codigo')
                        ->pluck('vins.vin_codigo');
                    
                    foreach($array_vin_cods as $vin_cod){
                        if(!in_array($vin_cod, $arreglo_vins)){
                            $vin_eliminar_id = Vin::where('vin_codigo', $vin_cod)
                                ->value('vin_id');
                            
                            GuiaVin::where('vin_id', $vin_eliminar_id)
                                ->where('guia_id', $request->guia_id[$i])
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
                    Ruta::where('tour_id', $id_tour)
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

    protected function finalizarTourNoIniciado($tour_id)
    {
        $tour = Tour::findOrFail($tour_id);

        $tour->tour_finalizado = true;
        $tour->tour_comentarios = "Tour cancelado o no iniciado.";

        $tour->save();
    }
}
