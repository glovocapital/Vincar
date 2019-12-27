@extends('layouts.app')
@section('title','Crear nuevo bloque')
@section('content')

<!-- Registrar datos de un bloque -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Registrar Bloque</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::open(['route'=> 'bloque.store', 'method'=>'POST', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="" >Datos Básicos</label>
                        </div>

                        {!! Form::hidden('patio_id', $patio_id) !!}

                        <div class="form-group">
                            <label for="bloque_nombre" >Nombre del Bloque <strong>*</strong></label>
                            {!! Form::text('bloque_nombre', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="bloque_filas" >Número de filas <strong>*</strong></label>
                                {!! Form::text('bloque_filas', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="bloque_columnas" >Número de columnas <strong>*</strong></label>
                                {!! Form::text('bloque_columnas', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" >Coordenadas Geográficas</label>
                        </div>

                        <div class="form-group">
                            <label for="bloque_coord_lat" >Latitud <strong>*</strong></label>
                            {!! Form::text('bloque_coord_lat', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="bloque_coord_lon" >Longitud <strong>*</strong></label>
                                {!! Form::text('bloque_coord_lon', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="text-right pb-5" id="boton_bloque">
                    {!! Form::submit('Registrar Bloque', ['class' => 'btn btn-primary block full-width m-b']) !!}
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