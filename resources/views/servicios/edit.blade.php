@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')
<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Servicio</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                                {!! Form::open(['route'=> ['servicios.update', Crypt::encrypt($servicio->servicios_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                <label for="producto_id" >Código Producto <strong>*</strong></label>
                                {!! Form::select('producto_id', $producto, $servicio->producto_id,['placeholder'=>'Código de Produto', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="divisa_id" >Divisa <strong>*</strong></label>
                                {!! Form::select('divisa_id', $divisa, $servicio->divisa_id,['placeholder'=>'Divisa', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>


                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cliente_id" >Cliente <strong>*</strong></label>
                                {!! Form::select('cliente_id', $cliente, $servicio->cliente_id,['placeholder'=>'Cliente', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="marca_id" >Marca <strong>*</strong></label>
                                {!! Form::select('marca_id', $marca, $servicio->marca_id,['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>


                        </div>

                        <div class="col-md-3">

                            <div class="form-group">
                                <label for="valor_asociado_id" >Valor Asociado <strong>*</strong></label>
                                {!! Form::select('valor_asociado_id', $valor_asociado, $servicio->valor_asociado_id,['placeholder'=>'Valor Asociado', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" >Costo Servicio <strong>*</strong></label>
                                {{ Form::number('servicio_costo', $servicio->servicios_precio, ['min' => '0','placeholder'=>'Costo', 'class'=>'form-control col-sm-9', 'required'=>'required']) }}
                            </div>

                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Actualizar servicio', ['class' => 'btn btn-primary block full-width m-b']) !!}
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

