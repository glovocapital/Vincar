@extends('layouts.app')
@section('title','Destino index')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <div class="card-title float-left mt-3">Destinos</div>
                <div class="float-right mt-3">
                    <button id='nuevo_destino' class="btn btn-primary block full-width m-b mb-3">Nuevo Destino</button>
                </div>
            </div>

            <div class="card-body overflow-auto">
                <!-- <a href="{{  route('destinos.create') }}" class = 'btn btn-primary'>Crear Destino</a> -->

                <div class="table-responsive">
                    <table class="table table-hover table-sm nowrap" id="dataTableDestinos" width="100%" cellspacing="0">
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
</div>
@include('destinos.partials.modal_nuevo_destino')
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#nuevo_destino').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoDestino")[0].reset();
            $("#nuevoDestino").modal('show');
        });

        $('#dataTableDestinos').DataTable({
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
