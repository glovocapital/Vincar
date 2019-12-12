@extends('layouts.app')
@section('title','Remolque Editar')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Editar Remolques</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            {!! Form::open(['route'=> ['remolque.update', Crypt::encrypt($remolque->remolque_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                <label for="remolque_patente" >Patente <strong>*</strong></label>
                                {!! Form::text('remolque_patente', $remolque->remolque_patente, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="remolque_anio" >Año <strong>*</strong></label>
                                {!! Form::number('remolque_anio', $remolque->remolque_anio, ['placeholder'=>'Año ', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="remolque_fecha_circulacion" >Permiso de Circulación <strong>*</strong></label>
                                 {!! Form::date('remolque_fecha_circulacion', $remolque->remolque_fecha_circulacion, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>


                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="remolque_marca" >Marca <strong>*</strong></label>
                                {!! Form::text('remolque_marca', $remolque->remolque_marca, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="empresa_id" >Empresa <strong>*</strong></label>
                                {!! Form::select('empresa_id', $empresa, $remolque->empresa_id, ['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="remolque_fecha_revision" >Próxima Revisión <strong>*</strong></label>
                                {!! Form::date('remolque_fecha_revision', $remolque->remolque_fecha_revision, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="remolque_modelo" >Modelo <strong>*</strong></label>
                                {!! Form::text('remolque_modelo', $remolque->remolque_modelo, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="remolque_modelo" >Capacidad <strong>*</strong></label>
                                {!! Form::number('remolque_capacidad', $remolque->remolque_capacidad, ['min' => '0','placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>


                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Actualizar Remolque', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
