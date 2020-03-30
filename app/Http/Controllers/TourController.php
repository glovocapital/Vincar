<?php

namespace App\Http\Controllers;

use App\Camion;
use App\Conductor;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckSession;
use App\User;
use App\Empresa;
use App\Remolque;
use App\Tour;
use DB;

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


        $conductor = User::select(DB::raw("CONCAT(user_nombre,' ', user_apellido) AS conductor_nombres"), 'users.user_id')
            ->join('conductors', 'users.user_id', '=', 'conductors.user_id' )
            ->where('deleted_at', null)
            ->pluck('conductor_nombres', 'users.user_id')
            ->all();

        $empresas = Empresa::select('empresa_id', 'empresa_razon_social')
            ->orderBy('empresa_id')
            ->where('deleted_at', null)
            ->pluck('empresa_razon_social', 'empresa_id')
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

        return view('transporte.index', compact('tour','empresas','camion','transporte','remolque','conductor'));
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

        $conductor_id = Conductor::where('user_id',$request->conductor_id)
            ->first();

        $camion_id = Camion::where('camion_id',$request->camion_id)
            ->first();

        $remolque_id = Remolque::where('remolque_id',$request->remolque_id)
            ->first();


        $fecha_viaje = date_create($request->tour_fecha_inicio);

        $licencia_valida = date_create($conductor_id->conductor_fecha_vencimiento);

        $revision_remolque = date_create($remolque_id->remolque_fecha_revison);
        $permiso_remolque = date_create($remolque_id->remolque_fecha_circulacion);

        $permiso_camion = date_create($camion_id->camion_fecha_circulacion);
        $revision_camion = date_create($camion_id->camion_fecha_revision);


        $diferencia_licencia = $licencia_valida->diff($fecha_viaje)->days;
        $diferencia_revision_camion = $revision_camion->diff($fecha_viaje)->days;
        $diferencia_permiso_camion = $permiso_camion->diff($fecha_viaje)->days;
        $diferencia_revision_remolque = $revision_remolque->diff($fecha_viaje)->days;
        $diferencia_permiso_remolque = $permiso_remolque->diff($fecha_viaje)->days;



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
            $tour->cliente_id = $request->cliente_id;
            $tour->remolque_id = $request->remolque_id;
            $tour->proveedor_id = $request->transporte_id;
            $tour->conductor_id = $request->conductor_id;
            $tour->tour_fec_inicio = $request->tour_fecha_inicio;
            $tour->tour_finalizado = false;
            $tour->save();

            flash('El Tour se creo correctamente.')->success();
            return view('transporte.addrutas');

        }catch (\Exception $e) {

//dd($e);

            flash('Error al crear el Tour.')->error();
           //flash($e->getMessage())->error();
            return redirect('tour');
        }


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

    public function storeModalAddRutas()
    {
        return 0;
    }
}
