@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Agregar Guia Vin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['route'=> ['vin.addguia', Crypt::encrypt($vin->vin_id)], 'method'=>'PATCH', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="guia_fecha" >Fecha de la Guía:</label>
                                {!! Form::date('guia_fecha', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="guia_numero" >Número de Guía:</label>
                                {!! Form::text('guia_numero', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cargar Guia del VIN </label>
                                {!! Form::file('guia_vin', array('required')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Cargar Guia ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        <a href="{{ route('vin.index') }}" class = 'btn btn-success'>Regresar a VIN</a>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
</div>

@stop



