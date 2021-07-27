@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')


<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nuevo Modelo</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> ['modelos.update', Crypt::encrypt($modelo->modelo_id)], 'method'=>'PATCH']) !!}
                        <div class="form-group">
                            <label for="marca_id" >Nombre de la Marca <strong>*</strong></label>
                            {!! Form::select('marca_id', $marca, $modelo->marca_id, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modelo_alias" >Alias </label>
                            {!! Form::text('modelo_alias', $modelo->modelo_alias, ['placeholder'=>' Alias', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo_nombre" >Modelo <strong>*</strong></label>
                            {!! Form::text('modelo_nombre', $modelo->modelo_nombre, ['placeholder'=>'Ingrese Modelo', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo_tipo" >Tipo de Vehiculo <strong>*</strong></label>
                            {!! Form::text('modelo_tipo', $modelo->modelo_tipo, ['placeholder'=>'Tipo', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                </div>
                <div class="text-right pb-5">
                        {!! Form::submit('Modificar Modelo ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
