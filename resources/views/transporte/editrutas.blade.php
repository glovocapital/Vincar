@extends('layouts.app')
@section('title','Agregar Rutas')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Agregar Rutas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">

                {!! Form::open(['route'=>'tour.crearutas', 'method'=>'POST', 'files' => true]) !!}

                <div class="row">
                    @foreach ($vin_ruta as $vr)
                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="vin_numero" >Vin <strong>*</strong></label>
                                {!! Form::textarea('vin_numero', $vr->vin_codigo, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control"]) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="marca_nombre" >De:</label>
                                {!! Form::text('origen_id', $vr->ruta_origen, ['placeholder'=>'Nombre', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="marca_nombre" >A:</label>
                                {!! Form::text('destino_id', $vr->ruta_destino, ['placeholder'=>'Nombre', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="">Cargar Guia</label>
                                {!! Form::file('guia_ruta'); !!}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-right pb-5">
                <a type="button" href="{{route('tour.index')}}" class="btn btn-primary block full-width m-b">Regresar</a>

                </div>




            </div>
        </div>
    </div>
</div>




@stop
