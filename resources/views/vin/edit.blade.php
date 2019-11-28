@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Registrar VIN</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
            {!! Form::open(['route'=> ['vin.update', Crypt::encrypt($vin->vin_id)], 'method'=>'PATCH']) !!}

                <div class="form-group">
                    <div class="row">
                        <label for="vin_codigo" class="col-sm-3">Código VIN <strong>*</strong></label>
                        {!! Form::text('vin_codigo', $vin->vin_codigo, ['class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_patente" class="col-sm-3">Patente <strong>*</strong></label>
                        {!! Form::text('vin_patente', $vin->vin_patente, ['class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_modelo" class="col-sm-3">Modelo <strong>*</strong></label>
                        {!! Form::text('vin_modelo', $vin->vin_modelo, ['class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_marca" class="col-sm-3">Marca <strong>*</strong></label>
                        {!! Form::text('vin_marca', $vin->vin_marca, ['class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>


                <div class="form-group">
                        <div class="row">
                            <label for="vin_color" class="col-sm-3">Color <strong>*</strong></label>
                            {!! Form::text('vin_color', $vin->vin_color, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="vin_motor" class="col-sm-3">Motor <strong>*</strong></label>
                            {!! Form::text('vin_motor', $vin->vin_motor, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="vin_segmento" class="col-sm-3">Segmento <strong>*</strong></label>
                            {!! Form::text('vin_segmento', $vin->vin_segmento, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="vin_fec_ingreso" class="col-sm-3">Fecha ingreso <strong>*</strong></label>
                            {!! Form::text('vin_fec_ingreso', $vin->vin_fec_ingreso, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="empresa_id" class="col-sm-3">Empresa <strong>*</strong></label>
                       {!! Form::select('empresa_id', $empresas, $vin->empresa_id, ['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="user_id" class="col-sm-3">Seleccionar Cliente <strong>*</strong></label>
                       {!! Form::select('user_id', $users, $vin->user_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_estado_inventario_id" class="col-sm-3">Estado de Inventario <strong>*</strong></label>
                        {!! Form::select('vin_estado_inventario_id', $estadosInventario, $vin->vin_estado_inventario_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_sub_estado_inventario_id" class="col-sm-3">Sub-Estado de Inventario <strong>*</strong></label>
                        {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, $vin->vin_sub_estado_inventario_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="text-center pb-5">
                    {!! Form::submit('Actualizar VIN', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop