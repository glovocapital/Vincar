@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Cliente</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                                {!! Form::open(['route'=> ['vin.update', Crypt::encrypt($vin->vin_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                    <label for="vin_codigo" >Código VIN <strong>*</strong></label>
                                    {!! Form::text('vin_codigo', $vin->vin_codigo, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_patente" >Patente <strong>*</strong></label>
                                {!! Form::text('vin_patente', $vin->vin_patente, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>


                            <div class="form-group">
                                    <label for="vin_modelo" >Modelo <strong>*</strong></label>
                                    {!! Form::text('vin_modelo', $vin->vin_modelo, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                                {!! Form::select('user_id', $users, $vin->user_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="vin_marca" >Marca <strong>*</strong></label>
                                    {!! Form::text('vin_marca', $vin->vin_marca, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                    <label for="vin_color" >Color <strong>*</strong></label>
                                    {!! Form::text('vin_color', $vin->vin_color, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_motor" >Motor <strong>*</strong></label>
                                {!! Form::text('vin_motor', $vin->vin_motor, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_estado_inventario_id" >Estado de Inventario <strong>*</strong></label>
                                    {!! Form::select('vin_estado_inventario_id', $estadosInventario, $vin->vin_estado_inventario_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                                </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="vin_segmento" >Segmento <strong>*</strong></label>
                                    {!! Form::text('vin_segmento', $vin->vin_segmento, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_fec_ingreso" >Fecha ingreso <strong>*</strong></label>
                                    {!! Form::text('vin_fec_ingreso', $vin->vin_fec_ingreso, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group" id="bloque_archivo">
                                    <label for="empresa_id" >Empresa <strong>*</strong></label>
                                    {!! Form::select('empresa_id', $empresas, $vin->empresa_id, ['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group" id="bloque_archivo">
                                <label for="vin_sub_estado_inventario_id" >Sub-Estado de Inventario <strong>*</strong></label>
                                {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, $vin->vin_sub_estado_inventario_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                        </div>

                    </div>
                    <div class="text-right pb-5">
                            {!! Form::submit('Actualizar VIN', ['class' => 'btn btn-primary block full-width m-b']) !!}
                            {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                            <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
</div>

@stop
