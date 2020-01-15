<?php

namespace App\Http\Controllers;

use App\Campania;
use App\TipoCampania;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campanias = Campania::all()
            ->sortBy('campania_id');

        $tipo_campanias = TipoCampania::all()
            ->sortBy('tipo_campania_id');

        $arrayTCampanias = [];

        foreach ($campanias as $campania) {
            $tCampanias = DB::table('campania_vins')
                ->join('tipo_campanias', 'campania_vins.tipo_campania_id', '=', 'tipo_campanias.tipo_campania_id')
                ->select('campania_vins.campania_id', 'tipo_campanias.tipo_campania_descripcion')
                ->where('campania_vins.campania_id', $campania->campania_id)
                ->where('campania_vins.deleted_at', null)
                ->where('tipo_campanias.deleted_at', null)
                ->get();

                array_push($arrayTCampanias, $tCampanias);
        }

        return view('campania.index', compact('campanias', 'tipo_campanias', 'arrayTCampanias'));
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
    public function storeModal(Request $request)
    {
        try {
            DB::beginTransaction();

            $campania = new Campania();

            $campania->campania_fecha_finalizacion = $request->campania_fecha_finalizacion;
            $campania->campania_observaciones = $request->campania_observaciones;
            $campania->vin_id = $request->vin_id;
            $campania->user_id = Auth::user()->user_id;

            $campania->save();
            
            foreach ($request->tipo_campanias as $t_campania_id) {
                $tipo_campania_id = (int)$t_campania_id;
                DB::insert('INSERT INTO campania_vins (tipo_campania_id, campania_id) VALUES (?, ?)', [$tipo_campania_id, $campania->campania_id]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('vin.index')->with('error-msg', 'Error asignando campaña.');
        }

        return redirect()->back(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function show(Campania $campania)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function edit(Campania $campania)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campania $campania)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Campania  $campania
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campania $campania)
    {
        //
    }
}
