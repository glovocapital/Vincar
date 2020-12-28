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
use App\TipoLicencia;
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
        $tours = Tour::where('tour_iniciado', true)
            ->where('tour_finalizado', false)
            ->get();

        $rutas = [];

        foreach ($tours as $tour){
            $tourRutas = Ruta::where('tour_id', $tour->tour_id)->get();

            foreach ($tourRutas as $tourRuta){
                array_push($rutas, $tourRuta);
            }
        }

        return view('transporte.index', compact('rutas'));
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

        if ($fechaViaje <= Carbon::yesterday()){
            flash('Error: Fecha incorrecta. La fecha no puede ser anterior al día actual.')->error();
            return back()->withInput();
        }

        $conductor = Conductor::where('user_id',$request->conductor_id)->first();
        $tipoLicenciaId = $conductor->tipo_licencia_id;
        $camion = Camion::where('camion_id', $request->camion_id)->first();
        $remolque = Remolque::where('remolque_id', $request->remolque_id)->first();
        $capacidad = $remolque->remolque_capacidad;
        $licencia = TipoLicencia::find($tipoLicenciaId)->tipo_licencia_nombre;


        if (!($capacidad <= 4 && $tipoLicenciaId == 4) && !($capacidad > 0 && $tipoLicenciaId == 5) && !($capacidad > 0 && $tipoLicenciaId == 10)) {
                flash('Error: Capacidad del Remolque (' . $capacidad . ' vehículos), no corresponde con el tipo de Licencia (' . $licencia . ') de conducir.')->error();
                return back()->withInput();
        }

        // if (!$this->validarData($conductor, 'conductor', $fechaViaje)){
        //     return back()->withInput();
        // }


        // if (!$this->validarData($camion, 'camion', $fechaViaje)){
        //     return back()->withInput();
        // }


        // if (!$this->validarData($remolque, 'remolque', $fechaViaje)){
        //     return back()->withInput();
        // }

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

        } catch (\Exception $e) {
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
    * tour_id: ID del tour si se está modificando.
    */
    // protected function validarData($data, $modelo, $fechaViaje, $tour_id=null)
    // {
    //     $cadenaConsulta = $modelo . '_id';

    //     if ($modelo === 'conductor') {
    //         $id_modelo = $data->user_id;
    //     } else {
    //         $id_modelo = $data->$cadenaConsulta;
    //     }


    //     $noDisponible = Tour::where('tour_id', '!=', $tour_id)
    //         ->where($cadenaConsulta, $id_modelo)
    //         ->where('tour_iniciado', true)
    //         ->where('tour_finalizado', false)
    //         ->exists();

    //     if($noDisponible){
    //         flash('Error: Conductor no disponible.')->error();
    //         return false;
    //     }

    //     // $otroTour =

    //     $fechaOtroTour = Tour::where('tour_id', '!=', $tour_id)
    //         ->where($cadenaConsulta, $id_modelo)
    //         ->where('tour_iniciado', false)
    //         ->where('tour_finalizado', false)
    //         ->orderByDesc('tour_fec_inicio')
    //         ->limit(1)
    //         ->value('tour_fec_inicio');

    //     if ($fechaOtroTour !== null){
    //         $fechaInicioOtroTour = new Carbon($fechaOtroTour);

    //         $diferenciaFechaNuevoTour = $fechaViaje->diffInDays($fechaInicioOtroTour);

    //         if($diferenciaFechaNuevoTour <= 7){
    //             flash('Error: ' . ucfirst($modelo) . ' con otro viaje agendado en menos de una semana.')->error();
    //             return false;
    //         }
    //     }

    //     return true;
    // }

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
    public function editTour($id)
    {
        $id_tour = Crypt::decrypt($id);

        $tour = Tour::findOrFail($id_tour);

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

        return view('transporte.edittour', compact('tour', 'camion', 'transporte', 'remolque', 'conductor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTour(Request $request, $id)
    {
        $id_tour = Crypt::decrypt($id);

        $tour = Tour::findOrFail($id_tour);

        // No se puede modificar un tour que ya haya arrancado, o que ya haya iniciado y finalizado correctamente.
        // Sólo se permitirá en el caso de tours cancelados o que estén pendientes por arrancar.
        if ($tour->tour_iniciado && !$tour->finalizado) {
            flash('Error: Tour en desarrollo. No puede ser modificado.')->error();
            return back();
        } elseif ($tour->tour_iniciado && $tour->finalizado) {
            flash('Error: Tour previamente completado correctamente. No puede ser modificado.')->error();
            return back();
        }

        // Validaciones
        $fechaViaje = new Carbon($request->tour_fecha_inicio);

        if ($fechaViaje < Carbon::today()){
            flash('Error: Fecha incorrecta. La fecha no puede ser anterior al día actual.')->error();
            return back()->withInput();
        }

        $conductor = Conductor::where('user_id', $request->conductor_id)->first();
        $camion = Camion::where('camion_id', $request->camion_id)->first();
        $remolque = Remolque::where('remolque_id', $request->remolque_id)->first();
        $tipoLicenciaId = $conductor->tipo_licencia_id;
        $capacidad = $remolque->remolque_capacidad;
        $licencia = TipoLicencia::find($tipoLicenciaId)->tipo_licencia_nombre;


        if (!($capacidad <= 4 && $tipoLicenciaId == 4) && !($capacidad > 0 && $tipoLicenciaId == 5) && !($capacidad > 0 && $tipoLicenciaId == 10)) {
                flash('Error: Capacidad del Remolque (' . $capacidad . ' vehículos), no corresponde con el tipo de Licencia (' . $licencia . ') de conducir.')->error();
                return back()->withInput();
        }

        // if (!$this->validarData($conductor, 'conductor', $fechaViaje, $id_tour)){
        //     return back()->withInput();
        // }


        // if (!$this->validarData($camion, 'camion', $fechaViaje, $id_tour)){
        //     return back()->withInput();
        // }


        // if (!$this->validarData($remolque, 'remolque', $fechaViaje, $id_tour)){
        //     return back()->withInput();
        // }

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
            flash('No se puede modificar el tour con este conductor, licencia de conducir vencida o a punto de vencer')->error();
            return redirect()->action('TourController@tour')->withInput();
        }

        // La revisión y permiso de circulación del camión deben tener al menos 16 días de vigencia al momento del viaje.
        // La fecha del viaje no puede ser posterior al vencimiento de la revisión y/o permiso de circulación del camión.
        if($diferenciaRevisionCamion <= 15 || $diferenciaPermisoCamion <= 15 || $fechaViaje > $permisoCamion || $fechaViaje > $revisionCamion)
        {
            flash('No se puede modificar el tour con este camión, debe revisar el permisos de circulación o fecha de revisión del mismo')->error();
            return redirect()->action('TourController@tour')->withInput();
        }

        // La revisión y permiso de circulación del remolque deben tener al menos 16 días de vigencia al momento del viaje.
        // La fecha del viaje no puede ser posterior al vencimiento de la revisión y/o permiso de circulación del remolque.
        if($diferenciaRevisionRemolque <= 15 || $diferenciaPermisoRemolque <= 15 || $fechaViaje > $revisionRemolque || $fechaViaje > $revisionRemolque)
        {
            flash('No se puede modificar el tour con este remolque, debe revisar el permisos de circulación o fecha de revisión del mismo')->error();
            return redirect()->action('TourController@tour')->withInput();
        }

        try {
            if(($tour->camion_id != $request->camion_id) || ($tour->remolque_id != $request->remolque_id) || ($tour->proveedor_id != $request->transporte_id)
                || ($tour->conductor_id != $request->conductor_id) || ($tour->tour_fec_inicio != $request->tour_fecha_inicio)){
                    $tour->tour_comentarios = "Tour actualizado. Información modificada.";
                }

            $tour->camion_id = $request->camion_id;
            $tour->remolque_id = $request->remolque_id;
            $tour->proveedor_id = $request->transporte_id;
            $tour->conductor_id = $request->conductor_id;
            $tour->tour_fec_inicio = $request->tour_fecha_inicio;
            $tour->tour_finalizado = false;

            if ($tour->save()) {
                flash('El Tour se actualizó correctamente.')->success();
                return redirect()->action('TourController@tour');
            } else {
                flash('Error actualizando información del Tour.')->error();
                return redirect()->action('TourController@tour')->withInput();
            }
        }catch (\Exception $e) {
            flash('Error al intentar actualizar el Tour.')->error();
           //flash($e->getMessage())->error();
            return redirect()->action('TourController@tour')->withInput();
        }
    }

    public function trash($id){
        $id_tour =  Crypt::decrypt($id);

        try {
            // Eliminar primero las guías asociadas
            $guias = Guia::join('ruta_guias','ruta_guias.guia_id','=', 'guias.guia_id')
                ->join('rutas','rutas.ruta_id','=','ruta_guias.ruta_id')
                ->where('rutas.tour_id', $id_tour)
                ->get();

            if ($guias) {
                foreach ($guias as $guia) {
                    $guiaBorrar = Guia::findOrFail($guia->guia_id);

                    $guiaBorrar->delete();
                }
            }

            // Eliminar luego el tour.
            $tour = Tour::findOrfail($id_tour);
            $tour->delete();

            flash('Los todos los datos del Tour han sido eliminados satisfactoriamente.')->success();
            return redirect()->route('tour.tour');
        }catch (\Exception $e) {
            flash('Error al intentar eliminar los datos del Tour.')->error();
            return redirect()->route('tour.tour');
        }
    }

    public function restore($id){
        $id_tour =  Crypt::decrypt($id);

        $tour = Tour::onlyTrashed()->where('tour_id', $id_tour)->firstOrFail();

        $tour->restore();

        return redirect()->route('tour.tour');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id_tour =  Crypt::decrypt($id);

        $tour = Tour::onlyTrashed()->where('tour_id', $id_tour)->firstOrFail();

        $tour->forceDelete();

        return redirect()->route('tour.tour');
    }

    protected function finalizarTourNoIniciado($tour_id)
    {
        $tour = Tour::findOrFail($tour_id);

        $tour->tour_finalizado = true;
        $tour->tour_comentarios = "Tour cancelado o no iniciado en fecha correspondiente.";

        $tour->save();
    }

    /**
     * Iniciar un tour manualmente a nivel administrativo.
     */

    public function iniciarTour(Request $request)
    {
        $tour_id =  $request->tour_id;
        $tour = Tour::findOrfail($tour_id);

        try{
            if ($request->iniciado){
                $tour->tour_iniciado = $request->iniciado;
                $tour->tour_fec_hora_iniciado = Carbon::now()->toDateTimeString();
                $tour->tour_comentarios = 'Tour iniciado.';
            } else {
                $tour->tour_iniciado = $request->iniciado;
                $tour->tour_fec_hora_iniciado = null;
                $tour->tour_comentarios = 'Tour restablecido a estado no iniciado.';
            }

            if($tour->save()){
                if($request->iniciado){
                    $mensaje = "Tour iniciado correctamente.";
                } else {
                    $mensaje = "Tour de nuevo en estado no iniciado.";
                }
            }
        }  catch (\Throwable $th) {
            flash('Error cambiando estado de inicio del tour.')->error();

            return response()->json([
                'success' => false,
                'message' => "Error iniciando el tour",
            ]);
        }
        flash('Cambiado correctamente estado de inicio del tour.')->success();

        return response()->json([
            'success' => true,
            'message' => $mensaje,
        ]);
    }

    /**
     * Finalizar un tour manualmente a nivel administrativo.
     */

    public function finalizarTour(Request $request)
    {
        $tour_id =  $request->tour_id;
        $tour = Tour::findOrfail($tour_id);

        try{
            if ($tour->tour_iniciado) {
                if ($request->finalizado){
                    $tour->tour_finalizado = $request->finalizado;
                    $tour->tour_fec_fin = Carbon::now()->toDateTimeString();
                } else {
                    $tour->tour_finalizado = $request->finalizado;
                    $tour->tour_fec_fin = null;
                }

                if($tour->save()){
                    if($request->finalizado){
                        $mensaje = "Tour finalizado correctamente.";
                    } else {
                        $mensaje = "Tour de nuevo en estado no finalizado.";
                    }
                }
            } else {
                flash('Error de finalización del tour: El tour no está iniciado.')->error();

                return response()->json([
                    'success' => false,
                    'message' => "Error finalizando el tour: Tour no iniciado previamente.",
                ]);
            }
        }  catch (\Throwable $th) {
            flash('Error cambiando estado de finalización del tour.')->error();

            return response()->json([
                'success' => false,
                'message' => "Error finalizando el tour",
            ]);
        }
        flash('Cambiado correctamente estado de finalización del tour.')->success();

        return response()->json([
            'success' => true,
            'message' => $mensaje,
        ]);
    }
}
