@extends('layouts.app')
@section('title','Productos index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title float-left mt-3">Productos</div>
                    <div class="float-right mt-3">
                        <button id='nuevo_producto' class="btn btn-primary block full-width m-b mb-3">Nuevo Producto</button>
                    </div>
                </div>

                <div class="card-body overflow-auto">
                    <!-- <a href="{{  route('productos.create') }}" class = 'btn btn-primary'>Crear Destino</a> -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableProductos" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($producto as $p)
                                <tr>
                                    <td><small>{{ $p->producto_codigo }}</small></td>
                                    <td><small>{{ $p->producto_descripcion }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('productos.edit', Crypt::encrypt($p->producto_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('productos.destroy', Crypt::encrypt($p->producto_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@include('productos.partials.modal_nuevo_producto')
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#nuevo_producto').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoProducto")[0].reset();
            $("#nuevoProducto").modal('show');
        });

        $('#dataTableProductos').DataTable({
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
