@extends('layouts.app')
@section('title','Creación Empresas')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Crear Proveedor</h5>
            </div>

            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> 'proveedor.store', 'method'=>'POST']) !!}

                <div class="form-group">
                    <div class="row">
                        <label for="tipo_proveedor_desc" class="col-sm-3">Nombre del Proveedor <strong>*</strong></label>
                        {!! Form::text('tipo_proveedor_desc', null, ['placeholder'=>'Nombre del Proveedor', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar Tipo de Proveedor ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop
