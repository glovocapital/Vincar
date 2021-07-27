@extends('layouts.app')
@section('title','Registrar nuevo Tipo de campaña')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Tipo de Campaña</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['route'=> 'tipo_campania.store', 'method'=>'POST']) !!}

                            <div class="form-group">
                                <label for="tipo_campania_descripcion" >Nombre de la Campaña <strong>*</strong></label>
                                {!! Form::text('tipo_campania_descripcion', null, ['placeholder'=>'Tipo de Campaña', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Tipo de Campaña ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campo obligatorio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

