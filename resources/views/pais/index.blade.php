@extends('layouts.app')
@section('title','Empresa index')
@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Empresas</li>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h4>Listado de empresas</h4>
            </div>
            <hr class="mb-4">
            <div class="col-lg-12 pb-3 pt-2">
                <a href="{{  route('pais.create') }}" class = 'btn btn-primary'>Crear Pais</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTablePais" width="100%" cellspacing="0">
                    <thead>
	                    <tr>
	                        <th>Nombre</th>
	                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pais as $p)

                        <tr>
                            <td><small>{{ $p->pais_nombre }}</small></td>
                            <td>
                                <small>
                                    <a href="{{ route('pais.edit', Crypt::encrypt($p->pais_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                        <a href = "{{ route('pais.destroy', Crypt::encrypt($p->pais_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
    $('#dataTablePais').DataTable();
} );
</script>
