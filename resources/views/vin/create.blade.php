@extends('layouts.app')
@section('title','Registro de VIN en el Sistema')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Registrar VIN</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> 'vin.store', 'method'=>'POST']) !!}


                <div class="form-group">
                    <div class="row">
                        <label for="vin_patente" class="col-sm-3">Patente <strong>*</strong></label>
                        {!! Form::text('vin_patente', null, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
            </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_modelo" class="col-sm-3">Modelo <strong>*</strong></label>
                        {!! Form::text('vin_modelo', null, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_marca" class="col-sm-3">Marca <strong>*</strong></label>
                        {!! Form::text('vin_marca', null, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>


                <div class="form-group">
                        <div class="row">
                            <label for="vin_color" class="col-sm-3">Color <strong>*</strong></label>
                            {!! Form::text('vin_color', null, ['placeholder'=>'Color', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="vin_motor" class="col-sm-3">Motor <strong>*</strong></label>
                            {!! Form::text('vin_motor', null, ['placeholder'=>'Motor', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="vin_segmento" class="col-sm-3">Segmento <strong>*</strong></label>
                            {!! Form::text('vin_segmento', null, ['placeholder'=>'Segmento', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="vin_fec_ingreso" class="col-sm-3">Fecha ingreso <strong>*</strong></label>
                            {!! Form::text('vin_fec_ingreso', null, ['placeholder'=>'Fecha de Ingreso', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="empresa_id" class="col-sm-3">Empresa <strong>*</strong></label>
                       {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Seleccionar Empresa', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="user_id" class="col-sm-3">Seleccionar Cliente <strong>*</strong></label>
                       {!! Form::select('user_id', $users, null,['placeholder'=>'Seleccionar Cliente', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_estado_inventario_id" class="col-sm-3">Estado de Inventario <strong>*</strong></label>
                        {!! Form::select('vin_estado_inventario_id', $estadosInventario, null,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="vin_sub_estado_inventario_id" class="col-sm-3">Sub-Estado de Inventario <strong>*</strong></label>
                        {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, null,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar vin ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop

<!--Funcion para ocultar y mostrar input segun seleccion-->
<script language="javascript" type="text/javascript">
    function d1(selectTag){
    if(selectTag.value == '0')
    {
        $('#bloque_archivo').hide();
        document.getElementById('archivo').disabled = true;
    }else if(selectTag.value == '1')
    {
        $('#bloque_archivo').show();

     document.getElementById('archivo').disabled = false;
    }else if(selectTag.value == '2')
    {
        $('#bloque_archivo').hide();
        document.getElementById('archivo').disabled = true;
    }
    }
    </script>
<!--Fin Funcion para ocultar y mostrar input segun seleccion-->



