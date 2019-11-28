@extends('layouts.app')
@section('title','Destinos Editar')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Editar Destinos</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> ['destinos.update', Crypt::encrypt($destino->destino_id)], 'method'=>'PATCH']) !!}

                <div class="form-group">
                    <div class="row">
                        <label for="destino_codigo" class="col-sm-3">Código del Destino <strong>*</strong></label>
                        {!! Form::text('destino_codigo', $destino->destino_codigo, ['placeholder'=>'Código del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="destino_nombre" class="col-sm-3">Nombre del Destino <strong>*</strong></label>
                            {!! Form::text('destino_nombre', $destino->destino_nombre, ['placeholder'=>'Nombre del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                <div class="text-center pb-5">
                        {!! Form::submit('Actualizar Destino', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>


                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop
