@extends('layouts.app')
@section('title','Guías index')
@section('content')
@include('flash::message')

<div class="col-lg-12">

    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Generar Pre Facturacion</h3>

            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  {!! Form::open(['route'=> 'prefacturacion.generar', 'method'=>'POST']) !!}
                  <div class="form-group">
                      <label for="empresa_id">Empresa <strong>*</strong></label>
                      {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Empresa','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                  </div>

                    <div class="form-group">
                        <label for="fecha_ini" >Fecha Inicial <strong>*</strong></label>
                        {!! Form::date('fecha_ini', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin" >Fecha Final <strong>*</strong></label>
                        {!! Form::date('fecha_fin', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                    <div class="text-right pb-5">
                        {!! Form::submit('Consultar ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

@stop
