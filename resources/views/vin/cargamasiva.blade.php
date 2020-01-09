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
                            <label for="">Cargar Documento *</label>
                            {!! Form::file('carga_masiva', array('required' => 'required')); !!}
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



