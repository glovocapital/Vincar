@extends('layouts.app')
@section('title','Inspección index')
@section('content')


<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de Inspecciones</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <a href="{{ route('inspeccion.create') }}" class = 'btn btn-primary'>Nueva Inspección</a>
                </div>

                <br />
                
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Fecha Inspección</th>
                                <th>Nro. de Inspección</th>
                                <th>Código VIN</th>
                                <th>Responsable</th>
                                <th>Cliente</th>
                                <th>Estado Inventario</th>
                                <th>Sub Estado Inventario</th>
                                <th>¿Hay daño?</th>
                                <th>Pieza</th>
                                <th>Tipo de Daño</th>
                                <th>Gravedad</th>
                                <th>Sub Área</th>
                                <th>Foto</th>
                                <th>Acci&oacute;n</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($inspecciones as $inspeccion)
                            @foreach($danosArray as $dano)
                                @if($dano->inspeccion_id == $inspeccion->inspeccion_id)
                                <tr>
                                    <td><small>{{ $inspeccion->inspeccion_fecha }}</small></td>
                                    <td><small>{{ $inspeccion->inspeccion_id }}</small></td>
                                    <td><small>{{ $inspeccion->vin_id }}</small></td>
                                    <td><small>{{ $inspeccion->oneResponsable->user_nombre.' '.$inspeccion->oneResponsable->user_apellido }}</small></td>
                                    <td><small>{{ $inspeccion->oneCliente->user_nombre.' '.$inspeccion->oneCliente->user_apellido }}</small></td>
                                    <td><small>{{ $inspeccion->oneVinEstadoInventario() }}</small></td>
                                    @if($inspeccion->vin_sub_estado_inventario_id != null)
                                        <td><small>{{ $inspeccion->oneVinSubEstadoInventario() }}</small></td>
                                    @else
                                        <td><small></small></td>
                                    @endif
                                    @if($inspeccion->inspeccion_dano)
                                        <td><small>Sí</small></td>
                                    @else
                                        <td><small>No</small></td>
                                    @endif
                                    <td><small>{{ $dano->onePieza->pieza_descripcion }}</small></td>
                                    <td><small>{{ $dano->oneTipoDano->tipo_dano_descripcion }}</small></td>
                                    <td><small>{{ $dano->oneGravedad() }}</small></td>
                                    <td><small>{{ $dano->oneSubArea() }}</small></td>
                                    <td><small>
                                            <div class="thumbnail">
                                                <img src="{{ asset($dano->oneFoto->foto_ubic_archivo) }}" alt="{{ $dano->oneFoto->foto_ubic_archivo }}">
                                            </div>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <a href="{{ route('inspeccion.create_dano', Crypt::encrypt($dano->dano_pieza_id)) }}" class="btn-dano"  title="Añadir Daño"><i class="far fa-plus-square"></i></a>
                                        </small>
                                        <small>
                                            <a href="{{ route('inspeccion.edit_dano', Crypt::encrypt($dano->dano_pieza_id)) }}" class="btn-dano"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('inspeccion.destroy_dano', Crypt::encrypt($dano->dano_pieza_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-dano"><i class="far fa-trash-alt"></i></a>
                                        </small>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

