@extends('layouts.app')
@section('title','Servicios index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title float-left mt-3">Servicios</div>
                    <div class="float-right mt-3">
                        <button id='nuevo_servicio' class="btn btn-primary block full-width m-b mb-3">Nuevo Servicio</button>
                    </div>
                </div>

                <div class="card-body overflow-auto">
                    <!--   <div class="col-lg-12 pb-3 pt-2">
                                <a href="{{ route('servicios.create') }}" class = 'btn btn-primary'>Crear nuevo Usuario</a>
                            </div>
                    -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableServicios" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>C. Producto</th>
                                    <th>Cliente</th>
                                    <th>Tamaño</th>
                                    <th>Valor Asociado</th>
                                    <th>Divisa</th>
                                    <th>Costo</th>
                                    <th>Acci&oacute;n</th>
                                <!-- <th>Desactivar</th>  -->
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($servicios as $us)
                                <tr>
                                    <td><small>{{ $us->oneProducto->producto_codigo }}</small></td>
                                    <td><small>{{ $us->oneEmpresa->empresa_razon_social }}</small></td>
                                    <td><small>{{ $us->oneCaracteristicas->caracteristica_vin_nombre }}</small></td>
                                    <td><small>{{ $us->oneValorA->valor_asociado_tipo }}</small></td>
                                    <td><small>{{ $us->oneDivisa->divisa_tipo }}</small></td>
                                    <td><small>{{ $us->servicios_precio }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('servicios.edit',  Crypt::encrypt($us->servicios_id)) }}" class="btn-empresa"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('servicios.destroy', Crypt::encrypt($us->servicios_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@include('servicios.partials.modal_nuevo_servicio')
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#nuevo_servicio').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoServicio")[0].reset();
            $("#nuevoServicio").modal('show');
        });

        $('#dataTableServicios').DataTable({
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
