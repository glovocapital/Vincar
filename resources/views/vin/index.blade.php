@extends('layouts.app')
@section('title','Vin index')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Cliente</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                                {!! Form::open(['route'=> 'vin.store', 'method'=>'POST']) !!}

                            <div class="form-group">
                                    <label for="vin_codigo" >Código VIN <strong>*</strong></label>
                                    {!! Form::text('vin_codigo', null, ['placeholder'=>'Código', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_patente" >Patente <strong>*</strong></label>
                                {!! Form::text('vin_patente', null, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>


                            <div class="form-group">
                                    <label for="vin_modelo" >Modelo <strong>*</strong></label>
                                    {!! Form::text('vin_modelo', null, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                                {!! Form::select('user_id', $users, null,['placeholder'=>'Seleccionar Cliente', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="vin_marca" >Marca <strong>*</strong></label>
                                    {!! Form::text('vin_marca', null, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                    <label for="vin_color" >Color <strong>*</strong></label>
                                    {!! Form::text('vin_color', null, ['placeholder'=>'Color', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_motor" >Motor <strong>*</strong></label>
                                {!! Form::text('vin_motor', null, ['placeholder'=>'Motor', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_estado_inventario_id" >Estado de Inventario <strong>*</strong></label>
                                    {!! Form::select('vin_estado_inventario_id', $estadosInventario, null,['class'=>'form-control col-sm-9']) !!}
                                </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="vin_segmento" >Segmento <strong>*</strong></label>
                                    {!! Form::text('vin_segmento', null, ['placeholder'=>'Segmento', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_fec_ingreso" >Fecha ingreso <strong>*</strong></label>
                                    {!! Form::date('vin_fec_ingreso', null, ['placeholder'=>'Fecha de Ingreso', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group" id="bloque_archivo">
                                    <label for="empresa_id" >Empresa <strong>*</strong></label>
                                    {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Seleccionar Empresa', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group" id="bloque_archivo">
                                <label for="vin_sub_estado_inventario_id" >Sub-Estado de Inventario <strong>*</strong></label>
                                {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, null,['class'=>'form-control col-sm-9']) !!}
                            </div>

                        </div>

                    </div>
                    <div class="text-right pb-5">
                            {!! Form::submit('Registrar vin ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                            {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                            <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
</div>

    <br />


    <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Listado de empresas</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">



            <div class="table-responsive">
                <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                    <thead>
	                    <tr>
	                        <th>Patente</th>
                            <th>Modelo</th>
                            <th>Marca</th>
	                        <th>Color</th>
                            <th>Motor</th>
                            <th>Segmento</th>
                            <th>Fecha de Ingreso</th>
                            <th>Cliente</th>
                            <th>Estado Inventario</th>
                            <th>Sub Estado Inventario </th>
	                        <th>Acci&oacute;n</th>

	                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vins as $vin)

                        <tr>
                            <td><small>{{ $vin->vin_patente }}</small></td>
                            <td><small>{{ $vin->vin_modelo }}</small></td>
                            <td><small>{{ $vin->vin_marca }}</small></td>
                            <td><small>{{ $vin->vin_color }}</small></td>
                            <td><small>{{ $vin->vin_motor }}</small></td>
                            <td><small>{{ $vin->vin_segmento }}</small></td>
                            <td><small>{{ $vin->vin_fec_ingreso }}</small></td>
                            <td><small>{{ $vin->user_id }}</small></td>
                            <td><small>{{ $vin->vin_estado_inventario_id }}</small></td>
                            <td><small>{{ $vin->vin_sub_estado_inventario_id }}</small></td>

                            <td>
                                <small>
                                    <a href="{{ route('vin.edit', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>
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

