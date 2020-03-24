@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Editar Paises</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">

                <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                    {!! Form::open(['route'=> ['pais.update', Crypt::encrypt($pais->pais_id)], 'method'=>'PATCH']) !!}
                    <div class="form-group">
                        <div class="row">
                            <label for="pais_nombre" class="col-sm-3">Nombre del País <strong>*</strong></label>
                            {!! Form::text('pais_nombre', $pais->pais_nombre, ['placeholder'=>'Nombre del país', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Actualizar País', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@stop
