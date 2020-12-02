@extends('layouts.app')
@section('title','Guías index')
@section('content')
@include('flash::message')

<div class="col-lg-12">

    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de guías</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form method="get" action="{{ url('guia') }}">
                    <div class="row row-filters">
                        <div>
                            <div class="form-inline form-dates pb-3">
                                <label for="from" class="form-label-sm">Desde</label>&nbsp;
                                <div class="input-group">
                                    <input type="date" class="form-control form-control-sm" name="from" id="from" placeholder="Desde" value="{{ request('from') }}">
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="from" class="form-label-sm">Hasta</label>&nbsp;
                                <div class="input-group">
                                    <input type="date" class="form-control form-control-sm" name="to" id="to" placeholder="Hasta" value="{{ request('to') }}">
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="guia_numero" class="form-label-sm">Nro. de Guía</label>
                                &nbsp;
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="guia_numero" id="guia_numero" placeholder="Nro. de Guía" value="{{ request('guia_numero') }}">
                                </div>
                            </div>
                            <div class="form-inline pb-3">
                                <label for="empresa" class="form-label-sm">Empresa</label> 
                                &nbsp;
                                <div class="input-group">
                                {!! Form::select('empresa_id', $empresas, request('empresa_id'),['id' => 'cliente', 'placeholder'=>'Cliente', 'class'=>'form-control form-control-sm col-sm-9 select-cliente']) !!}
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableGuias" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Número de Guía</th>
                                <th>Fecha</th>
                                <th>Empresa</th>
                                <th>¿Carga entregada?</th>
                                <th>Estado</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($guias as $guia)

                            <tr>
                                <td><small>{{ $guia->guia_numero }}</small></td>
                                <td><small>{{ $guia->guia_fecha }}</small></td>
                                <td><small>{{ $guia->empresa->empresa_razon_social }}</small></td>

                                @if ($guia->guia_carga_entregada)
                                    <td><small><button class="btn btn-success btn-sm rounded">Sí</button></small></td>
                                @else
                                    <td><small><button class="btn btn-danger btn-sm rounded">No</button></small></td>
                                @endif

                                @if ($guia->deleted_at != null)
                                    <td><small><button class="btn btn-danger btn-sm rounded">Inactiva</button></small></td>
                                @else
                                    <td><small><button class="btn btn-success btn-sm rounded">Activa</button></small></td>
                                @endif
                                
                                <td>
                                    <small>
                                        <a href="{{ route('guia.downloadGuia', Crypt::encrypt($guia->guia_id)) }}" type="button" class="btn-guia"  title="Descargar Guía"><i class="fas fa fa-barcode2"></i></a>
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