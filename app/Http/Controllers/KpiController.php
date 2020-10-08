<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KpiController extends Controller
{
    public function index()
    {
        return view('kpi.index');
    }
}
