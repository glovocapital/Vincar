@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')
<div id="downloadGuiasVin" class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Descarga de Guias del Vin: {{$vin_codigo}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row row-fluid">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nro. Guía</th>
                                <th>Cliente</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guias as $guia)
                            <tr>
                                <td>{{ $guia->guia_fecha }}</td>
                                <td>{{ $guia->guia_numero }}</td>
                                <td>{{ $guia->empresa->empresa_razon_social }}</td>
                                <td><a href="{{route('vin.descargarGuia', Crypt::encrypt($guia->guia_id))}}" type="button" class="btn"  title="Descargar Guía"><i class="fas fa fa-barcode2"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right pb-5">
                    <a href="{{ route('vin.index') }}" class = 'btn btn-success'>Regresar a VIN</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
