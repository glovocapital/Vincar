@extends('layouts.app')
@section('title','Empresa index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title float-left mt-3">Países</div>
                    <div class="float-right mt-3">
                        <button id='nuevo_pais' class="btn btn-primary block full-width m-b mb-3">Nuevo País</button>
                    </div>
                </div>

                <!-- /.card-header -->
                <div class="card-body overflow-auto">
                    <hr class="mb-4">
                    <!--    <div class="col-lg-12 pb-3 pt-2">
                            <a href="{{  route('pais.create') }}" class = 'btn btn-primary'>Crear Pais</a>
                        </div>
                    -->

                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTablePaises" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($paises as $pais)
                                <tr>
                                    <td><small>{{ $pais->pais_nombre }}</small></td>

                                    <td>
                                        <small>
                                            <a href="{{ route('pais.edit', Crypt::encrypt($pais->pais_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('pais.destroy', Crypt::encrypt($pais->pais_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@include('pais.partials.modal_nuevo_pais')
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#nuevo_pais').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoPais")[0].reset();

            $("#nuevoPais").modal('show');
        });

        $('#dataTablePaises').DataTable({
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
