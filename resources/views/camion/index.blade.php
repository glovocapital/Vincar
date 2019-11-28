@extends('layouts.app')
@section('title','Camiones index')
@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Camiones</li>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h4>Listado de Camiones</h4>
            </div>
            <hr class="mb-4">
            <div class="col-lg-12 pb-3 pt-2">
                <a href="{{  route('camiones.create') }}" class = 'btn btn-primary'>Crear Camión</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTableCamion" width="100%" cellspacing="0">
                    <thead>
	                    <tr>
                            <th>Patente</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>

                            <th>Empresa</th>
	                    </tr>
                    </thead>
                    <tbody>
                    @foreach($camion as $p)

                        <tr>
                            <td><small>{{ $p->camion_patente }}</small></td>
                            <td><small>{{ $p->camion_marca }}</small></td>
                            <td><small>{{ $p->camion_modelo }}</small></td>
                            <td><small>{{ $p->camion_anio }}</small></td>

                            <td><small>{{ $p->belongsToEmpresa->empresa_razon_social }}</small></td>

                            <td>
                                <small>
                                    <a href="{{ route('camiones.edit', Crypt::encrypt($p->camion_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                        <a href = "{{ route('camiones.destroy', Crypt::encrypt($p->camion_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@stop

<script>
       $(document).ready(function() {
    $('#dataTableCamion').DataTable();
} );
</script>
