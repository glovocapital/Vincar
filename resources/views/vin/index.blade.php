@extends('layouts.app')
@section('title','Vin index')
@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vins</li>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h4>Listado de Vins</h4>
            </div>
            <hr class="mb-4">
            <div class="col-lg-12 pb-3 pt-2">
                <a href="{{  route('vin.create') }}" class = 'btn btn-primary'>Crear nuevo Vin</a>
            </div>

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
                                    <a href="{{ route('vin.edit', Crypt::encrypt($vin->id)) }}" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>
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
@stop
