@extends('layouts.app')
@section('title','Vin index')
@section('content')


<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de Vins</h3>
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
                                <th>Fecha Inspección</th>
                                <th>Código VIN</th>
                                <th>Responsable</th>
                                <th>Cliente</th>
                                <th>Estado Inventario</th>
                                <th>Sub Estado Inventario</th>
                                <th>¿Hay daño?</th>
                                <th>Acci&oacute;n</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($inspecciones as $inspeccion)
                            <tr>
                                <td><small>{{ $inspeccion->inspeccion_fecha }}</small></td>
                                <td><small>{{ $inspeccion->vin_id }}</small></td>
                                <td><small>{{ $inspeccion->responsable_id }}</small></td>
                                <td><small>{{ $inspeccion->cliente_id }}</small></td>
                                <td><small>{{ $inspeccion->oneVinEstadoInventario() }}</small></td>
                                @if($inspeccion->vin_sub_estado_inventario_id != null)
                                <td><small>{{ $inspeccion->oneVinSubEstadoInventario() }}</small></td>
                                @else
                                <td><small></small></td>
                                @endif
                                <td><small>{{ $inspeccion->inspeccion_dano }}</small></td>

                                <td>
                                    <small>
                                        <a href="{{ route('inspeccion.edit', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                            <a href = "{{ route('inspeccion.destroy', Crypt::encrypt($vin->vin_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-vin"><i class="far fa-trash-alt"></i>
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

