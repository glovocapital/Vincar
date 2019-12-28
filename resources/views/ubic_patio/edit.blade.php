@extends('layouts.app')
@section('title','Editar ubicación en bloque')
@section('content')

<!-- Modificar datos de un bloque -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Editar Ubicación en Bloque</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::open(['route'=> ['ubic_patio.update', Crypt::encrypt($ubic_patio->ubic_patio_id)], 'method'=>'PATCH', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="" >Datos de la posición en el bloque</label>
                        </div>

                        {!! Form::hidden('bloque_id', $ubic_patio->bloque_id) !!}

                        <div class="form-group">
                            <label for="bloque_nombre" >Código Vin <strong>*</strong></label>
                            {!! Form::text('vin_codigo', $ubic_patio->oneVin->vin_codigo, ['class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                                <label for="ubic_patio_fila" >Fila <strong>*</strong></label>
                                {!! Form::text('ubic_patio_fila', $ubic_patio->ubic_patio_fila, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="ubic_patio_columna" >Columna <strong>*</strong></label>
                                {!! Form::text('ubic_patio_columna', $ubic_patio->ubic_patio_columna, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="ubic_patio_ocupada" >¿Ubicación ocupada? <strong>*</strong></label>
                            <select name="ubic_patio_ocupada" class="form-control col-sm-9">
                                @if($ubic_patio->ubic_patio_ocupada)
                                <option value="1" selected>Si</option>
                                <option value="2" >No</option>
                                @else
                                <option value="1">Si</option>
                                <option value="2" selected>No</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-right pb-5" id="boton_bloque">
                    {!! Form::submit('Modificar Ubicación', ['class' => 'btn btn-primary block full-width m-b']) !!}
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