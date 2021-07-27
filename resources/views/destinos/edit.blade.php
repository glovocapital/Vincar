@extends('layouts.app')
@section('title','Destinos Editar')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Editar Destino</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-6">
                                {!! Form::open(['route'=> ['destinos.update', Crypt::encrypt($destino->destino_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                    <label for="destino_codigo" >Código del Destino <strong>*</strong></label>
                                    {!! Form::text('destino_codigo', $destino->destino_codigo, ['placeholder'=>'Código del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="destino_nombre" >Nombre del Destino <strong>*</strong></label>
                                    {!! Form::text('destino_nombre', $destino->destino_nombre, ['placeholder'=>'Nombre del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Actualizar Destino', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
