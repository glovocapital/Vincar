@extends('layouts.app')
@section('title','Patio index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title float-left mt-3">Patios</div>

                    <div class="float-right mt-3 mr-2">
                        <button id='nuevo_patio' class="btn btn-primary block full-width m-b mb-3">Nuevo Patio</button>
                    </div>

                    <div class="block float-right mt-3 mb-3 mr-2">
                        {!! Form::open(['route'=> 'patio.download', 'method'=>'GET']) !!}
                        {!! Form::submit('Descargar planilla ', ['class' => 'btn btn-warning full-width']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="block float-right mt-3 mb-3 mr-2">
                    <a href="{{ route('patio.cargar_patios') }}" class = 'btn btn-success'>Carga de Patios</a>
                    </div>
                </div>

                <div class="card-header">
                    <h3 class="card-title">Listado de Patios</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>

                <div class="card-body overflow-auto">
                    <!-- <div class="row">
                        <a href=" route('patio.create') " class = 'btn btn-primary'>Nuevo Patio</a>
                    </div>

                    <br /> -->

                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTablePatios" width="100%" cellspacing="0">
                            <thead>
                                <tr rowspan=2>
                                    <th>Nombre</th>
                                    <th>Bloques</th>
                                    <th>Acciones Bloque</th>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th>Dirección</th>
                                    <th>Región</th>
                                    <!-- <th>Provincia</th> -->
                                    <th>Comuna</th>
                                    <th>Acci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($patios as $patio)
                                <tr>
                                    <td><small>{{ $patio->patio_nombre }}</small></td>
                                    <td style="text-align: center"><small>{{ $patio->patio_bloques }}
                                        </small>
                                    </td>
                                    <td style="text-align: center">
                                        <small>
                                            <a href = "{{ route('bloque.index', Crypt::encrypt($patio->patio_id))  }}" class="btn-bloque" title="Ver Bloques"><i class="far fa-eye"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('bloque.create', Crypt::encrypt($patio->patio_id))  }}" class="btn-bloque" title="Añadir Bloque"><i class="far fa-plus-square"></i></a>
                                        </small>
                                    </td>
                                    <td><small>{{ $patio->patio_coord_lat }}</small></td>
                                    <td><small>{{ $patio->patio_coord_lon }}</small></td>
                                    <td><small>{{ $patio->patio_direccion }}</small></td>
                                    <td><small>{{ $patio->oneRegion() }}</small></td>
                                    <!-- <td><small> $patio->oneProvincia() </small></td> -->
                                    <td><small>{{ $patio->oneComuna() }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('patio.edit', Crypt::encrypt($patio->patio_id)) }}" class="btn-patio"  title="Editar Patio"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('patio.destroy', Crypt::encrypt($patio->patio_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-patio"  title="Eliminar Patio"><i class="far fa-trash-alt"></i></a>
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
@include('patio.partials.modal_nuevo_patio')
@stop

@section('local-scripts')
    <script>
        $(document).ready(function () {
            $('#nuevo_patio').on('click', (e) => {
                e.preventDefault();

                $("#formNuevoPatio")[0].reset();
                $("#nuevoPatio").modal('show');
            });

            $('#dataTablePatios').DataTable({
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

            $(".select-region").change(function (e) {
                e.preventDefault();

                var id = $(this).val();

                if (id != '') {
                    var url = "/patio/obtener_comunas/";

                    $.get(url + id, id, function (res) {
                        //Validar primero si algo salió mal
                        if(!res.success) {
                            alert(
                                "Error inesperado al solicitar la información.\n\n" +
                                "MENSAJE DEL SISTEMA:\n" +
                                res.message + "\n\n"
                            );
                            return;  // Finaliza el intento de obtener
                        }

                        var arr_ids = $.map(res.ids, function (e1) {
                            return e1;
                        });

                        var arr_comunas = $.map(res.comunas, function (e1) {
                            return e1;
                        });

                        $("#comuna_id").html("<option value=''>Seleccionar Comuna</option>");
                        for (var i = 0; i < arr_ids.length; i++) {
                            $("#comuna_id").append("<option value='" + arr_ids[i] + "'>" + arr_comunas[i] + "</option>");
                        }
                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                } else {
                    $("#comuna_id").html("<option value=''>Seleccionar Comuna</option>");
                }
            });
        });
    </script>
@endsection
