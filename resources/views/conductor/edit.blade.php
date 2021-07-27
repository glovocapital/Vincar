@extends('layouts.app')
@section('title','Conductores Editar')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Editar Conductores</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">

                        <div class="col-md-4">
                            {!! Form::open(['route'=> ['conductores.update', Crypt::encrypt($conductor->conductor_id)], 'method'=>'PATCH', 'files' => true]) !!}

                            <div class="form-group">
                                    <label for="user_id" >Conductor <strong>*</strong></label>
                                    {!! Form::select('user_id', $usuario, $conductor->user_id,['placeholder'=>'Seleccione','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="">Foto Documentos <strong>*</strong></label>
                                    {!! Form::file('conductor_foto_documento'); !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_licencia_id" >Tipo de Licencia <strong>*</strong></label>
                                    {!! Form::select('tipo_licencia_id', $tipo_licencia, $conductor->tipo_licencia_id,['placeholder'=>'Seleccione','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="conductor_fecha_vencimiento" >Fecha de vencimiento <strong>*</strong></label>
                                     {!! Form::date('conductor_fecha_vencimiento', $conductor->conductor_fecha_vencimiento, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                                </div>

                            </div>
                        </div>

                        <div class="text-right pb-5">
                            {!! Form::submit('Actualizar Conductor ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
