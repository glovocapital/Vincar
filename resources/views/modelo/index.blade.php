@extends('layouts.app')
@section('title','Empresa index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title float-left mt-3">Modelos</div>
                    <div class="float-right mt-3 mr-2">
                        <button id='nuevo_modelo' class="btn btn-primary block full-width m-b mb-3">Nuevo Modelo</button>
                    </div>
                </div>

                <!-- /.card-header -->
                <div class="card-body overflow-auto">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableModelos" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Tipo</th>
                                    <th>Alias</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($modelo as $p)
                                <tr>
                                    <td><small>{{ $p->BelongsMarca->marca_nombre }}</small></td>
                                    <td><small>{{ $p->modelo_nombre }}</small></td>
                                    <td><small>{{ $p->modelo_tipo }}</small></td>
                                    <td><small>{{ $p->modelo_alias }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('modelos.edit', Crypt::encrypt($p->modelo_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('modelos.destroy', Crypt::encrypt($p->modelo_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@include('modelo.partials.modal_nuevo_modelo')
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#nuevo_modelo').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoModelo")[0].reset();
            $("#nuevoModelo").modal('show');
        });

        $('#dataTableModelos').DataTable({
            searching: true,
            bSortClasses: false,
            deferRender:true,
            responsive: false,
            lengthChange: !1,
            pageLength: 25,
            @if(Session::get('lang')=="es")
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            @endif
        });
    });
</script>
@endsection
