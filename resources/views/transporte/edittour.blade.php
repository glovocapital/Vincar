@extends('layouts.app')
@section('title','Tour index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Modificar Tour</h3>

                </div>
                <div class="card-body overflow-auto">

                        {!! Form::open(['route'=> ['tour.update', Crypt::encrypt($tour->tour_id)], 'method'=>'PATCH']) !!}
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4" >
                                    <div class="form-group">
                                        <label for="transporte_id" >Proveedor de Transporte <strong> *</strong></label>
                                        {!! Form::select('transporte_id', $transporte, old('transporte_id', $tour->proveedor_id),['id' => 'proveedor_id', 'placeholder'=>'Proveedor de Transporte', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="camion_id" >Camión<strong> *</strong></label>
                                        {!! Form::select('camion_id', $camion, old('camion_id', $tour->camion_id),['id' => 'camion', 'placeholder'=>'Camión', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                    </div>
                                </div>


                                <div class="col-md-4" id="wrapper_2">
                                    <div class="form-group">
                                        <label for="remolque_id" >Remolque<strong> *</strong></label>
                                        {!! Form::select('remolque_id', $remolque, old('remolque_id', $tour->remolque_id),['id' => 'patio', 'placeholder'=>'Remolque', 'class'=>'form-control col-sm-9 select-cliente' , 'required']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="conductor_id" ><strong> Conductor *</strong></label>
                                        {!! Form::select('conductor_id', $conductor, old('conductor_id', $tour->conductor_id),['id' => 'camion', 'placeholder'=>'Conductor', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4" id="wrapper_2">
                                    <div class="form-group">
                                        <label for="tour_fecha_inicio" >Fecha de Inicio <strong>*</strong></label>
                                        {!! Form::date('tour_fecha_inicio', old('tour_fecha_inicio', $tour->tour_fec_inicio), [ 'class'=>'form-control col-sm-9', 'required']) !!}
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="text-right pb-5">

                            {!! Form::submit('Modificar Tour ', ['class' => 'btn btn-success block full-width m-b']) !!}
                            {!! Form::close() !!}
                            <a type="button" href="{{route('tour.tour')}}" class="btn btn-primary block full-width m-b">Regresar</a>
                        </div>
                        <div class="text-center texto-leyenda">
                            <p><strong>*</strong> Campos obligatorios</p>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>

@stop
