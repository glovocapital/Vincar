@extends('layouts.app')
@section('title','Empresa index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title float-left mt-3">Marcas</div>
                    <div class="float-right mt-3 mr-2">
                        <button id='nueva_marca' class="btn btn-primary block full-width m-b mb-3">Nueva Marca</button>
                    </div>
                </div>

                <!-- /.card-header -->
                <div class="card-body overflow-auto">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableMarcas" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Logo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($marca_vehiculo as $p)
                                <tr>
                                    <td><small>{{ $p->marca_codigo }}</small></td>
                                    <td><small>{{ ucfirst(strtolower($p->marca_nombre)) }}</small></td>
                                    <td style="text-align: center">
                                        <small>
                                        @if ($p->img!="")
                                            <img height='30px' width="30px" src='{{ $p->img }}'/>
                                        @endif
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <a href="{{ route('marcas.edit', Crypt::encrypt($p->marca_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('marcas.destroy', Crypt::encrypt($p->marca_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@include('marca.partials.modal_nueva_marca')
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#nueva_marca').on('click', (e) => {
            e.preventDefault();

            $("#formNuevaMarca")[0].reset();
            $("#nuevaMarca").modal('show');
        });

        $('#dataTableMarcas').DataTable({
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

