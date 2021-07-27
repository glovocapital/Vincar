@extends('layouts.app')
@section('title','Campaña index')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de Campañas</strong></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>

            <div class="card-body overflow-auto">
                <div class="row">
                    <a href="{{ route('solicitud_campania.index') }}" class = 'btn btn-success'>Regresar a Solicitud Campañas</a>
                </div>

                <br />

                <div class="table-responsive">
                    <table class="table table-hover table-sm nowrap" id="dataTableCampanias" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Código VIN</th>
                                <th>Descripción Campañas</th>
                                <th>Fecha Finalización</th>
                                <th>Observaciones</th>
                                <th>Usuario Solicitante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($campanias as $campania)
                        <tr>
                            <td><small>{{ $campania->codigoVin() }}</small></td>
                            <td><small>
                            @foreach($arrayTCampanias as $tipoCamp)
                                @foreach($tipoCamp as $tCamp)
                                    @if($campania->campania_id === $tCamp->campania_id)
                                        <button class="btn btn-xs btn-info">{{ $tCamp->tipo_campania_descripcion }}</button>
                                    @endif
                                @endforeach
                            @endforeach
                            </small></td>
                            <td><small>{{ $campania->campania_fecha_finalizacion }}</small></td>
                            <td><small>{{ $campania->campania_observaciones }}</small></td>
                            <td><small>{{ $campania->nombreUsuario() }}</small></td>
                            <td>
                                <small>
                                    <a href="{{ route('campania.edit', Crypt::encrypt($campania->campania_id)) }}" class="btn-bloque" title="Editar Campania"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                    <a href = "{{ route('campania.destroy', Crypt::encrypt($campania->campania_id)) }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-bloque" title="Eliminar campaña"><i class="far fa-trash-alt"></i></a>
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
