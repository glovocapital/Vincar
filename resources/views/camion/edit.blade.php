@extends('layouts.app')
@section('title','Camiones Editar')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Editar Camiones</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            {!! Form::open(['route'=> ['camiones.update', Crypt::encrypt($camiones->camion_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                <label for="camion_patente" >Patente <strong>*</strong></label>
                                {!! Form::text('camion_patente', $camiones->camion_patente, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="camion_anio" >Año <strong>*</strong></label>
                                {!! Form::number('camion_anio', $camiones->camion_anio, ['placeholder'=>'Año ', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="camion_marca" >Marca <strong>*</strong></label>
                                {!! Form::text('camion_marca', $camiones->camion_marca, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="empresa_id" >Empresa <strong>*</strong></label>
                                {!! Form::select('empresa_id', $empresa, $camiones->empresa_razon_social, ['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="camion_modelo" >Modelo <strong>*</strong></label>
                                {!! Form::text('camion_modelo', $camiones->camion_modelo, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Actualizar Camión', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
