@extends('layouts.app')
@section('title','Bloque index')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Bloques del Patio: <strong>{{ $patio_nombre }}</strong></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>

            <div class="card-body overflow-auto">
                <!-- <div class="row">
                    <a href="{{ route('patio.create') }}" class = 'btn btn-primary'>Nuevo Patio</a>
                </div>

                <br /> -->
                <div class="row">
                    <a href="{{ route('patio.index') }}" class = 'btn btn-success'>Regresar a patios</a>
                </div>

                <br />

                <div class="table-responsive">
                    <table class="table table-hover table-sm nowrap" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Nro. filas</th>
                                <th>Nro. columnas</th>
                                <th>Ubicaciones</th>
                                <th>Bloque</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($bloques as $bloque)

                            <tr>
                                <td><small>{{ $bloque->bloque_nombre }}</small></td>
                                <td><small>{{ $bloque->bloque_filas }}</small></td>
                                <td><small>{{ $bloque->bloque_columnas }}</small></td>
                                <td>
                                    <small>
                                        <a href="{{ route('ubic_patio.index', Crypt::encrypt($bloque->bloque_id)) }}" class="btn-bloque" title="Ver Ubicaciones"><i class="far fa-eye"></i></a>
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <a href="{{ route('bloque.edit', Crypt::encrypt($bloque->bloque_id)) }}" class="btn-bloque" title="Editar bloque"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                        <a href = "{{ route('bloque.destroy', Crypt::encrypt($bloque->bloque_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-bloque" title="Eliminar bloque"><i class="far fa-trash-alt"></i>
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
