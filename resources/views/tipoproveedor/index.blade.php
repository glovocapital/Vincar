@extends('layouts.app')
@section('title','Tipo de proveedor index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title float-left mt-3">Tipos de Proveedor</div>
                    <div class="float-right mt-3">
                        <button id='nuevo_tipo_proveedor' class="btn btn-primary block full-width m-b mb-3">Nuevo Tipo de Proveedor</button>
                    </div>
                </div>

                <div class="card-body overflow-auto">
                    <!--  <a href="{{  route('proveedor.create') }}" class = 'btn btn-primary'>Crear Tipo de Proveedor</a> -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableProveedores" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acción</th>
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
    </div>
</div>
@include('tipoproveedor.partials.modal_nuevo_tipo_proveedor')
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#nuevo_tipo_proveedor').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoTipoProveedor")[0].reset();
            $("#nuevoTipoProveedor").modal('show');
        });

        $('#dataTableProveedores').DataTable({
            searching: true,
            bSortClasses: false,
            deferRender:true,
            responsive: false,
            lengthChange: !1,
            pageLength: 10,
            @if(Session::get('lang')=="es")
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            @endif
        });
    });
</script>
@endsection
