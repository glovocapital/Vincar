@extends('layouts.app')
@section('title','Tour index')
@section('content')
@include('flash::message')

    <div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nuevo Tour</h3>

            </div>
            <div class="card-body">

                    {!! Form::open(['route'=> 'tour.store', 'method'=>'POST']) !!}
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="transporte_id" >Proveedor de Transporte <strong> *</strong></label>
                                    {!! Form::select('transporte_id', $transporte, null,['id' => 'proveedor_id', 'placeholder'=>'Proveedor de Transporte', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="camion_id" >Camión<strong> *</strong></label>
                                    {!! Form::select('camion_id', $camion, null,['id' => 'camion', 'placeholder'=>'Camión', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                </div>
                            </div>


                            <div class="col-md-4" id="wrapper_2">
                                <div class="form-group">
                                    <label for="remolque_id" >Remolque<strong> *</strong></label>
                                    {!! Form::select('remolque_id', $remolque, null,['id' => 'patio', 'placeholder'=>'Remolque', 'class'=>'form-control col-sm-9 select-cliente' , 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="conductor_id" ><strong> Conductor *</strong></label>
                                    {!! Form::select('conductor_id', $conductor, null,['id' => 'camion', 'placeholder'=>'Conductor', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-4" id="wrapper_2">
                                <div class="form-group">
                                    <label for="tour_fecha_inicio" >Fecha de Inicio <strong>*</strong></label>
                                     {!! Form::date('tour_fecha_inicio', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="text-right pb-5">

                        {!! Form::submit('Siguiente ', ['class' => 'btn btn-success block full-width m-b']) !!}
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

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
                <div class="card card-default">
                        <div class="card-header">
                          <h3 class="card-title">Listado de Tours</h3>
                          <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                                <hr class="mb-4">

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTablePais" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Proveedor de Transporte</th>
                                                <th>Patente Camión</th>
                                                <th>Patente Remolque</th>
                                                <th>Conductor</th>
                                                <th>Fecha</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($tour as $t)


                                            <tr>
                                                <td><small>{{ $t->oneProveedor->empresa_razon_social }}</small></td>
                                                <td><small>{{ $t->belongsToCamion->camion_patente }}</small></td>
                                                <td><small>{{ $t->belongsToRemolque->remolque_patente }}</small></td>
                                                <td><small>{{ $t->oneConductor->user_nombre }} {{ $t->oneConductor->user_apellido }}</small></td>
                                                <td><small>{{ $t->tour_fec_inicio}}</td><small>
                                                <td>
                                                   <small>
                                                        <a href="{{ route('tour.edit', Crypt::encrypt($t->tour_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                                    </small>
                                                    <small>
                                                            <a href = "{{ route('tour.destroy', Crypt::encrypt($t->tour_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i></a>
                                                    </small>
                                                    <small>
                                                        <a href="{{ route('tour.editrutas', Crypt::encrypt($t->tour_id)) }}" class=" btn-vin"  title="Rutas"><i class="fas fa fa-barcode"></i></a>
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
</div>



@stop
