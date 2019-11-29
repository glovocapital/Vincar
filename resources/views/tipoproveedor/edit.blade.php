@extends('layouts.app')
@section('title','Tipo de Proveedor Editar')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Tipo Proveedor</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                                {!! Form::open(['route'=> ['proveedor.update', Crypt::encrypt($proveedor->tipo_proveedor_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                <label for="tipo_proveedor_desc" >Nombre del Proveedor <strong>*</strong></label>
                                {!! Form::text('tipo_proveedor_desc', $proveedor->tipo_proveedor_desc, ['placeholder'=>'Nombre del país', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="text-right pb-5">
                            {!! Form::submit('Actualizar Tipo de proveedor', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
