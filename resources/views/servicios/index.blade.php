@extends('layouts.app')
@section('title','Servicios index')
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
                        {!! Form::open(['route'=> 'servicios.store', 'method'=>'POST']) !!}

                        <div class="form-group">
                            <label for="producto_id" >Código Producto <strong>*</strong></label>
                            {!! Form::select('producto_id', $producto, null,['placeholder'=>'Código de Produto', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="divisa_id" >Divisa <strong>*</strong></label>
                            {!! Form::select('divisa_id', $divisa, null,['placeholder'=>'Divisa', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>


                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cliente_id" >Cliente <strong>*</strong></label>
                            {!! Form::select('cliente_id', $cliente, null,['placeholder'=>'Cliente', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="marca_id" >Marca <strong>*</strong></label>
                            {!! Form::select('marca_id', $marca, null,['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>


                    </div>

                    <div class="col-md-3">

                        <div class="form-group">
                            <label for="valor_asociado_id" >Valor Asociado <strong>*</strong></label>
                            {!! Form::select('valor_asociado_id', $valor_asociado, null,['placeholder'=>'Valor Asociado', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" >Costo Servicio <strong>*</strong></label>
                            {{ Form::number('servicio_costo','0', ['min' => '0','placeholder'=>'Costo', 'class'=>'form-control col-sm-9', 'required'=>'required']) }}
                        </div>

                    </div>
                </div>

                <div class="text-right pb-5">
                    {!! Form::submit('Asignar servicio', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de servicios</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <!--   <div class="col-lg-12 pb-3 pt-2">
                            <a href="{{ route('servicios.create') }}" class = 'btn btn-primary'>Crear nuevo Usuario</a>
                        </div>
                -->
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>C. Producto</th>
                                <th>Cliente</th>
                                <th>Marca</th>
                                <th>Valor Asociado</th>
                                <th>Divisa</th>
                                <th>Costo</th>
                                <th>Acci&oacute;n</th>
                            <!-- <th>Desactivar</th>  -->
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($servicios as $us)
                            <tr>
                                <td><small>{{ $us->oneProducto->producto_codigo }}</small></td>
                                <td><small>{{ $us->oneEmpresa->empresa_razon_social }}</small></td>
                                <td><small>{{ $us->oneMarca->marca_nombre }}</small></td>
                                <td><small>{{ $us->oneValorA->valor_asociado_tipo }}</small></td>
                                <td><small>{{ $us->oneDivisa->divisa_tipo }}</small></td>
                                <td><small>{{ $us->servicios_precio }}</small></td>



                                <td>
                                    <small>
                                        <a href="{{ route('servicios.edit',  Crypt::encrypt($us->servicios_id)) }}" class="btn-empresa"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                            <a href = "{{ route('servicios.destroy', Crypt::encrypt($us->servicios_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
                                            </a>
                                    </small>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
