@extends('layouts.app')
@section('title','Tipo de proveedor index')
@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tipo de Proveedor</li>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h4>Listado de Proveedores</h4>
            </div>
            <hr class="mb-4">
            <div class="col-lg-12 pb-3 pt-2">
                <a href="{{  route('proveedor.create') }}" class = 'btn btn-primary'>Crear Tipo de Proveedor</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTableProveedores" width="100%" cellspacing="0">
                    <thead>
	                    <tr>
	                        <th>Nombre</th>
	                    </tr>
                    </thead>
                    <tbody>
                    @foreach($proveedores as $p)

                        <tr>
                            <td><small>{{ $p->tipo_proveedor_desc }}</small></td>
                            <td>
                                <small>
                                    <a href="{{ route('proveedor.edit', Crypt::encrypt($p->tipo_proveedor_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                        <a href = "{{ route('proveedor.destroy', Crypt::encrypt($p->tipo_proveedor_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
    $('#dataTableProveedores').DataTable();
} );
</script>
