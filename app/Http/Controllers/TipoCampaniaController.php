<?php

namespace App\Http\Controllers;

use App\TipoCampania;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\PreventBackHistory;

class TipoCampaniaController extends Controller
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
        $tipoCampanias = TipoCampania::all();

        return view('tipo_campania.index', compact('tipoCampanias'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function show(TipoCampania $tipoCampania)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoCampania $tipoCampania)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoCampania $tipoCampania)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoCampania  $tipoCampania
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoCampania $tipoCampania)
    {
        //
    }
}
