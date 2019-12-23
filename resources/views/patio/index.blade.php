@extends('layouts.app')
@section('title','Patio index')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de Patios</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <a href="{{ route('patio.create') }}" class = 'btn btn-primary'>Nuevo Patio</a>
                </div>

                <br />
                
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Nro. de bloques</th>
                                <th>Latitud</th>
                                <th>Longitud</th>
                                <th>Dirección</th>
                                <th>Región</th>
                                <th>Provincia</th>
                                <th>Comuna</th>
                                <th>Acci&oacute;n</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($patios as $patio)

                            <tr>
                                <td><small>{{ $patio->patio_nombre }}</small></td>
                                <td><small>{{ $patio->patio_bloques }}</small></td>
                                <td><small>{{ $patio->patio_coord_lat }}</small></td>
                                <td><small>{{ $patio->patio_coord_lon }}</small></td>
                                <td><small>{{ $patio->patio_direccion }}</small></td>
                                <td><small>{{ $patio->oneRegion() }}</small></td>
                                <td><small>{{ $patio->oneProvincia() }}</small></td>
                                <td><small>{{ $patio->oneComuna() }}</small></td>
                                <td>
                                    <small>
                                        <a href="{{ route('vin.edit', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                            <a href = "{{ route('vin.destroy', Crypt::encrypt($vin->vin_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-vin"><i class="far fa-trash-alt"></i>
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