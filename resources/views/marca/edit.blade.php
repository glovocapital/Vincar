@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')


<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nueva Marca</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> ['marcas.update', Crypt::encrypt($marca->marca_id)], 'method'=>'PATCH', 'files' => true]) !!}

                        <div class="form-group">

                                <label for="marca_nombre" >Nombre de la Marca <strong>*</strong></label>
                                {!! Form::text('marca_nombre', $marca->marca_nombre, ['placeholder'=>'Nombre', 'class'=>'form-control col-sm-9', 'required']) !!}

                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label for="marca_nombre" >Código de la Marca</label>
                            {!! Form::text('marca_codigo', $marca->marca_codigo, ['placeholder'=>'Código', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Logo de la marca </label>
                            {!! Form::file('logo_marca'); !!}
                        </div>

                    </div>

                </div>
                <div class="text-right pb-5">
                        {!! Form::submit('Actualizar Marca ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
