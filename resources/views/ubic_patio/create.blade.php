@extends('layouts.app')
@section('title','Crear nueva ubicación')
@section('content')

<!-- Registrar datos de un bloque -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Registrar nueva ubicación en el bloque: {{ $bloque_nombre }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::open(['route'=> 'ubic_patio.store', 'method'=>'POST', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="" >Datos de ubicación dentro del bloque</label>
                        </div>

                        {!! Form::hidden('bloque_id', $bloque_id) !!}

                        <div class="form-group">
                            <label for="bloque_nombre" >Código Vin <strong>*</strong></label>
                            {!! Form::text('vin_codigo', null, ['class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                                <label for="ubic_patio_fila" >Fila <strong>*</strong></label>
                                {!! Form::text('ubic_patio_fila', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="ubic_patio_columna" >Columna <strong>*</strong></label>
                                {!! Form::text('ubic_patio_columna', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="ubic_patio_ocupada" >¿Ubicación ocupada? <strong>*</strong></label>
                            <select name="ubic_patio_ocupada" class="form-control col-sm-9">
                                <option value="1">Si</option>
                                <option value="2" selected>No</option>
                            </select>
                        </div>
                    </div>  
                </div>
                <div class="text-right pb-5" id="boton_bloque">
                    {!! Form::submit('Registrar Ubicación', ['class' => 'btn btn-primary block full-width m-b']) !!}
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