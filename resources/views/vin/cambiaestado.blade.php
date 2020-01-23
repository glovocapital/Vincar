@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Estado Vin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">

                                {!! Form::open(['route'=> ['vin.cambiaestado', Crypt::encrypt($vin->vin_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                    <label for="vin_codigo" >Código VIN </label>
                                    {!! Form::text('vin_codigo', $vin->vin_codigo, ['class'=>'form-control col-sm-9', 'required', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_patente" >Patente </label>
                                {!! Form::text('vin_patente', $vin->vin_patente, ['class'=>'form-control col-sm-9', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_marca" >Marca </label>
                                    {!! Form::text('vin_marca', $vin->vin_marca, ['class'=>'form-control col-sm-9', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_modelo" >Modelo </label>
                                    {!! Form::text('vin_modelo', $vin->vin_modelo, ['class'=>'form-control col-sm-9', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                <label for="empresa_id" >Empresa </label>
                                {!! Form::select('empresa_id', $empresas, $user->belongsToEmpresa->empresa_id, ['class'=>'form-control col-sm-9', 'disabled', 'readonly']) !!}
                            </div>


                            <div class="form-group">
                                <label for="vin_estado_inventario_id" >Estado de Inventario </label>
                                {!! Form::select('vin_estado_inventario_id', $estadosInventario, $vin->vin_estado_inventario_id,['class'=>'form-control col-sm-9']) !!}

                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="text-right  pb-5">
                        <a href="{{ route('vin.index') }}" class = 'btn btn-success'>Regresar a VINs</a>

                            {!! Form::submit('Actualizar estado', ['class' => 'btn btn-primary block full-width m-b']) !!}
                            {!! Form::close() !!}
                    </div>
                </div>

                </div>
            </div>
        </div>
</div>

@stop



