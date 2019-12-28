@extends('layouts.app')
@section('title','Ubicación en Patio index')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Ubicaciones (posiciones) de los vehículos en el Bloque: <strong>{{ $bloque_nombre }}</strong></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            
            <div class="card-body">
                <!-- <div class="row">
                    <a href="{{ route('patio.create') }}" class = 'btn btn-primary'>Nuevo Patio</a>
                </div>

                <br /> -->
                <div class="row">
                    <a href="{{ route('bloque.index', Crypt::encrypt($patio_id)) }}" class = 'btn btn-success'>Regresar a bloques</a>
                </div>

                <br />

                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Vin</th>
                                <th>Fila</th>
                                <th>Columna</th>
                                <th>¿Ocupada?</th>
                                <th>Acci&oacute;n</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($ubic_patios as $ubic_patio)

                            <tr>
                                @if($ubic_patio->vin_id != null)
                                <td><small>{{ $ubic_patio->oneVin->vin_codigo }}</small></td>
                                @else
                                <td><small> -- </small></td>
                                @endif
                                
                                <td><small>{{ $ubic_patio->ubic_patio_fila }}</small></td>
                                <td><small>{{ $ubic_patio->ubic_patio_columna }}</small></td>
                                
                                @if($ubic_patio->ubic_patio_ocupada)
                                <td><small>Sí</small></td>
                                @else
                                <td><small> No </small></td>
                                @endif
                                <td>
                                    <small>
                                        <a href="{{ route('ubic_patio.edit', Crypt::encrypt($ubic_patio->ubic_patio_id)) }}" class="btn-bloque" title="Editar Ubicación"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                        <a href = "{{ route('ubic_patio.destroy', Crypt::encrypt($ubic_patio->ubic_patio_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-bloque" title="Eliminar Ubicación"><i class="far fa-trash-alt"></i>
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