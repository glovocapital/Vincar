@extends('layouts.app')
@section('title','Creación camiones')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Crear Camiones</h5>
            </div>

            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> 'camiones.store', 'method'=>'POST']) !!}

                <div class="form-group">
                    <div class="row">
                        <label for="camion_patente" class="col-sm-3">Patente <strong>*</strong></label>
                        {!! Form::text('camion_patente', null, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <label for="camion_marca" class="col-sm-3">Marca <strong>*</strong></label>
                        {!! Form::text('camion_marca', null, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="camion_modelo" class="col-sm-3">Modelo <strong>*</strong></label>
                        {!! Form::text('camion_modelo', null, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="camion_anio" class="col-sm-3">Año <strong>*</strong></label>
                        {!! Form::number('camion_anio', null, ['placeholder'=>'Año', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>



                <div class="form-group">
                    <div class="row">
                        <label for="empresa_id" class="col-sm-3">Empresa <strong>*</strong></label>
                        {!! Form::select('empresa_id', $empresa, null,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>


                <div class="text-center pb-5">
                    {!! Form::submit('Registrar Camión ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop





