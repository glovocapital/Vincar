@extends('layouts.app')
@section('title','Carga Masiva Crear')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nuevo Carga Masiva</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> 'vin.loadexcel', 'method'=>'POST', 'enctype' => 'multipart/form-data','files'=> true]) !!}

                        <div class="form-group">
                            <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                            {!! Form::select('user_id', $users, null,['id' => 'cliente', 'placeholder'=>'Seleccionar Cliente', 'class'=>'form-control col-sm-9 select-cliente', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="vin_estado_inventario_id" >Estado de Inventario <strong>*</strong></label>
                            {!! Form::select('vin_estado_inventario_id', $estadosInventario, null,['class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="">Subir Foto</label>
                            {!! Form::file('carga_masiva'); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vin_sub_estado_inventario_id" >Sub-Estado de Inventario <strong>*</strong></label>
                            {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, null,['class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_id" >Empresa <strong>*</strong></label>
                            {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Seleccionar Empresa', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="text-right pb-5">
                    {!! Form::submit('Cargar Archivos', ['class' => 'btn btn-primary block full-width m-b']) !!}
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



