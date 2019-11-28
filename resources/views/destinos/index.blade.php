@extends('layouts.app')
@section('title','Destino index')
@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Destino</li>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h4>Listado Destinos</h4>
            </div>
            <hr class="mb-4">
            <div class="col-lg-12 pb-3 pt-2">
                <a href="{{  route('destinos.create') }}" class = 'btn btn-primary'>Crear Destino</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTableDestino" width="100%" cellspacing="0">
                    <thead>
	                    <tr>
                            <th>Código</th>
	                        <th>Nombre</th>
	                    </tr>
                    </thead>
                    <tbody>
                    @foreach($destino as $p)

                        <tr>
                            <td><small>{{ $p->destino_codigo }}</small></td>
                            <td><small>{{ $p->destino_nombre }}</small></td>

                            <td>
                                <small>
                                    <a href="{{ route('destinos.edit', Crypt::encrypt($p->destino_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                        <a href = "{{ route('destinos.destroy', Crypt::encrypt($p->destino_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
    $('#dataTableDestino').DataTable();
} );
</script>
