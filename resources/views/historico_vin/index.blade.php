@extends('layouts.app')
@section('title','Histórico Vin index')
@section('content')
@include('flash::message')

<!--SUPER ADMINISTRADOR -->
@if(Auth::user()->rol_id == 1)
<div class="row">
    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title text-center">Histórico del VIN: {{ $vin->vin_codigo }} </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- OPERADOR LOGISTICO  -->
@if(Auth::user()->rol_id == 3)
<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title">Vehiculos N/N  </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- CUSTOMERS -->
@if(Auth::user()->rol_id == 4)
<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title text-center">Cargar Vehiculos </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- BUSQUEDA DE VIN   -->
@if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins text-center">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Buscar Historial para el Vin</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route'=> 'historico_vin.index', 'method'=>'get']) !!}
                    <div class="row">
                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                    <label for="vin_numero" >Vin <strong>*</strong></label>
                                    {!! Form::text('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'class'=>"form-control"]) !!}
                            </div>
                        </div>

                        <div class="text-right pb-5">
                            {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif



@if(Auth::user()->rol_id == 4)
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins text-center">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Buscar Historial para el Vin</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route'=> 'historico_vin.index', 'method'=>'get']) !!}
                    <div class="row">
                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                    <label for="vin_numero" >Vin <strong>*</strong></label>
                                    {!! Form::text('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'class'=>"form-control"]) !!}
                            </div>
                        </div>

                        <div class="text-right pb-5">
                            {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif



<div class="row">
 <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Histórico Vin: <strong>{{ $historico_vin[0]->oneVin->vin_codigo }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <!-- <th>Vin</th> -->
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Responsable</th>
                                        <th>Bloque Origen</th>
                                        <th>Bloque Destino</th>
                                        <th>Cliente</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($historico_vin as $evento)
                                <tr>
                                    <td><small>{{ $evento->oneVinEstadoInventario->vin_estado_inventario_desc }}</small></td>
                                    <td><small>{{ $evento->historico_vin_fecha }}</small></td>
                                    <td><small>{{ $evento->oneResponsable->user_nombre . " " . $evento->oneResponsable->user_apellido }}</small></td>
                                    <td><small>{{ $evento->oneOrigen->bloque_nombre }}</small></td>
                                    <td><small>{{ $evento->oneDestino->bloque_nombre }}</small></td>
                                    <td><small>{{ $evento->oneEmpresa->empresa_razon_social }}</small></td>
                                    <td><small>{{ $evento->historico_vin_descripcion }}</small></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

@stop
