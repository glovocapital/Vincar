@extends('layouts.app')
@section('title','Destinos Editar')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Editar Producto</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                                {!! Form::open(['route'=> ['productos.update', Crypt::encrypt($producto->producto_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                    <label for="producto_codigo" >Código del Producto <strong>*</strong></label>
                                    {!! Form::text('producto_codigo', $producto->producto_codigo, ['placeholder'=>'Código del producto', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="producto_descripcion" >Descripción del Producto <strong>*</strong></label>
                                    {!! Form::text('producto_descripcion', $producto->producto_descripcion, ['placeholder'=>'Descripción de producto', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Actualizar Producto', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
