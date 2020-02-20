@extends('layouts.app')
@section('title','Planificación index')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Buscar Vin</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['route'=> 'planificacion.index', 'method'=>'get']) !!}
                <div class="row">
                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                                <label for="vin_numero" >Vin <strong>*</strong></label>
                                {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'cols' => 40, 'style' => 'resize:none']) !!}
                        </div>
                    </div>

                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                                <label for="empresa_id" >Seleccionar Cliente <strong>*</strong></label>
                                {!! Form::select('empresa_id', $empresas, null,['id' => 'cliente', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                        </div>

                        <div class="form-group">
                            <label for="estado_nombre" >Seleccionar Estado <strong>*</strong></label>
                            {!! Form::select('estadoinventario_id', $estadosInventario, null,['id' => 'estadoinventario', 'placeholder'=>'Estado', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                        </div>
                    </div>

                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                                <label for="user_id" >Seleccionar Patio <strong>*</strong></label>
                                {!! Form::select('patio_id', $patios, null,['id' => 'patio', 'placeholder'=>'Patio', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                        </div>
                        <div class="form-group">
                            <label for="marca_nombre" >Seleccionar Marca <strong>*</strong></label>
                            {!! Form::select('marca_id', $marcas, null,['id' => 'marca', 'placeholder'=>'Marca', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                        </div>
                    </div>
                </div>
                <div class="text-right pb-5">
                    @if(count($tabla_vins) > 0)
                    <button type="button" class="btn btn-success btn-lote-vins">Asignar Tareas por lotes</i></button>
                    @endif
                {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de VIN (Resultado de la búsqueda)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="tab le-responsive">
                    <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all" />Seleccionar Todos</th>
                                <th>Vin</th>
                                <th>Patente</th>
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Color</th>
                                <!-- <th>Motor</th> -->
                                <th>Segmento</th>
                                <th>Fecha de Ingreso</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <!--  <th>Sub Estado Inventario </th>  -->
                                <!--   <th>Gestión de Registro</th> -->
                                <th>Acciones de VIN</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tabla_vins as $vin)
                            <tr>
                                <td><input type="checkbox" class="check-tarea" value="{{ $vin->vin_id }}" name="checked_vins[]" id="check-vin-{{ $vin->vin_id }}"></td>
                                <td id="vin-codigo-{{ $vin->vin_id }}"><small>{{ $vin->vin_codigo }}</small></td>
                                <td><small>{{ $vin->vin_patente }}</small></td>
                                <td><small>{{ $vin->vin_modelo }}</small></td>
                                <td><small>{{ $vin->vin_marca }}</small></td>
                                <td><small>{{ $vin->vin_color }}</small></td>
                                <!-- <td><small>{{ $vin->vin_motor }}</small></td> -->
                                <td><small>{{ $vin->vin_segmento }}</small></td>
                                <td><small>{{ $vin->vin_fec_ingreso }}</small></td>
                                <td><small>{{ $vin->empresa_razon_social }}</small></td>
                                <td><small>{{ $vin->vin_estado_inventario_desc }}</small></td>

                                <!--   <td>


                                    <small>
                                        <a href = "{{ route('vin.destroy', Crypt::encrypt($vin->vin_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-vin"><i class="far fa-trash-alt"></i>
                                    </a>
                                    </small>

                                </td> -->

                                <td>
                                    <small>
                                        <a href="{{ route('vin.edit', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>

                                    <small>
                                        <a href="{{ route('vin.editarestado', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Cambiar Estado"><i class="fa fa-flag-checkered"></i></a>
                                    </small>

                                    <small>
                                        <button value="{{ $vin->vin_id }}" class="btn btn-xs btn-success btn-tarea"  title="Solicitar Tarea"><i class="far fa-lightbulb"></i></button>
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

<br />

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Campañas Solicitadas</strong></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableCampanias" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Código VIN</th>
                                <th>Descripción Campañas</th>
                                <th>Fecha Finalización</th>
                                <th>Observaciones</th>
                                <th>Usuario Solicitante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($campanias as $campania)
                        <tr>
                            <td><small>{{ $campania->codigoVin() }}</small></td>
                            <td><small>
                            @foreach($arrayTCampanias as $tipoCamp)
                                @foreach($tipoCamp as $tCamp)
                                    @if($campania->campania_id === $tCamp->campania_id)
                                        <button class="btn btn-xs btn-info">{{ $tCamp->tipo_campania_descripcion }}</button>
                                    @endif
                                @endforeach
                            @endforeach
                            </small></td>
                            <td><small>{{ $campania->campania_fecha_finalizacion }}</small></td>
                            <td><small>{{ $campania->campania_observaciones }}</small></td>
                            <td><small>{{ $campania->nombreUsuario() }}</small></td>
                            <td>
                                <small>
                                    <a href="{{ route('campania.edit', Crypt::encrypt($campania->campania_id)) }}" class="btn-bloque" title="Editar Campania"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                    <a href = "{{ route('campania.destroy', Crypt::encrypt($campania->campania_id)) }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-bloque" title="Eliminar campaña"><i class="far fa-trash-alt"></i></a>
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

<div class="col-lg-12">
<div class="mx-auto col-sm-12 main-section" id="myTab" role="tablist">
    <ul class="nav nav-tabs justify-content-end">
        <li class="nav-item">
            <a class="nav-link active" id="tareas-asignadas-tab" data-toggle="tab" href="#tareas-asignadas" role="tab" aria-controls="tareas-asignadas" aria-selected="true">Tareas Asignadas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tareas-finalizadas-tab" data-toggle="tab" href="#tareas-finalizadas" role="tab" aria-controls="tareas-finalizadas" aria-selected="false">Tareas Finalizadas</a>				   	
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tareas-asignadas" role="tabpanel" aria-labelledby="tareas-asignadas-tab">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Tareas Asignadas</strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTableCampanias" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Código VIN</th>
                                        <th>Prioridad</th>
                                        <th>Fecha Finalización</th>
                                        <th>Hora Término</th>
                                        <th>Responsable</th>
                                        <th>¿Finalizada?</th>
                                        <th>Tipo Tarea</th>
                                        <th>Destino</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($tareas as $tarea)
                                <tr>
                                    <td><small>{{ $tarea->codigoVin() }}</small></td>
                                    @if($tarea->tarea_prioridad == 0)
                                    <td><small>Baja</small></td>
                                    @elseif($tarea->tarea_prioridad == 1)
                                    <td><small>Media</small></td>
                                    @elseif($tarea->tarea_prioridad == 2)
                                    <td><small>Alta</small></td>
                                    @endif
                                    <td><small>{{ $tarea->tarea_fecha_finalizacion }}</small></td>
                                    <td><small>{{ $tarea->tarea_hora_termino }}</small></td>
                                    <td><small>{{ $tarea->nombreResponsable() }}</small></td>
                                    @if($tarea->tarea_finalizada)
                                    <td><small>Sí</small></td>
                                    @else
                                    <td><small>No</small></td>
                                    @endif
                                    <td><small>{{ $tarea->oneTipoTarea() }}</small></td>
                                    <td><small>{{ $tarea->oneTipoDestino() }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('planificacion.edit', Crypt::encrypt($tarea->tarea_id)) }}" class="btn-bloque" title="Editar Tarea"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('planificacion.destroy', Crypt::encrypt($tarea->tarea_id)) }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-bloque" title="Eliminar tarea"><i class="far fa-trash-alt"></i></a>
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
        <div class="tab-pane fade" id="tareas-finalizadas" role="tabpanel" aria-labelledby="tareas-finalizadas-tab">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Tareas Finalizadas</strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTableCampanias" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Código VIN</th>
                                        <th>Prioridad</th>
                                        <th>Fecha Finalización</th>
                                        <th>Hora Término</th>
                                        <th>Responsable</th>
                                        <th>¿Finalizada?</th>
                                        <th>Tipo Tarea</th>
                                        <th>Destino</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($tareas_finalizadas as $tarea_finalizada)
                                <tr>
                                    <td><small>{{ $tarea_finalizada->codigoVin() }}</small></td>
                                    @if($tarea_finalizada->tarea_prioridad == 0)
                                    <td><small>Baja</small></td>
                                    @elseif($tarea_finalizada->tarea_prioridad == 1)
                                    <td><small>Media</small></td>
                                    @elseif($tarea_finalizada->tarea_prioridad == 2)
                                    <td><small>Alta</small></td>
                                    @endif
                                    <td><small>{{ $tarea_finalizada->tarea_fecha_finalizacion }}</small></td>
                                    <td><small>{{ $tarea_finalizada->tarea_hora_termino }}</small></td>
                                    <td><small>{{ $tarea_finalizada->nombreResponsable() }}</small></td>
                                    @if($tarea_finalizada->tarea_finalizada)
                                    <td><small>Sí</small></td>
                                    @else
                                    <td><small>No</small></td>
                                    @endif
                                    <td><small>{{ $tarea_finalizada->oneTipoTarea() }}</small></td>
                                    <td><small>{{ $tarea_finalizada->oneTipoDestino() }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('planificacion.edit', Crypt::encrypt($tarea_finalizada->tarea_id)) }}" class="btn-bloque" title="Editar Tarea"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('planificacion.destroy', Crypt::encrypt($tarea_finalizada->tarea_id)) }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-bloque" title="Eliminar tarea"><i class="far fa-trash-alt"></i></a>
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
</div>
</div>


@include('planificacion.partials.modal_asignar_tarea')
@include('planificacion.partials.modal_asignar_tarea_lotes')

@stop
@section('local-scripts')


<script>
        $(document).ready(function () {
            var checked = false;

            $('.check-all').on('click',function(){

                if(checked == false) {
                $('.check-tarea').prop('checked', true);
                    checked = true;
                } else {
                $('.check-tarea').prop('checked', false);
                    checked = false;
                }
            });

            $('.btn-lote-vins').click(function (e){
                e.preventDefault();

                var vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();

                var url = "/planificacion/obtener_codigos_vins";

                var request = {
                    _token: $("input[name='_token']").attr("value"),
                    vin_ids: vin_ids,
                };

                $.post(url, request, function (res) {
                    //Validar primero si algo salió mal
                    if(!res.success){
                        alert(
                            "Error inesperado al solicitar la información.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento de obtener
                    }

                    var arr_codigos = $.map(res.codigos, function (e1) {
                        return e1;
                    });

                    $("#vin_codigo_lote").html("<h6>VIN: " + arr_codigos[0] + "</h6>");
                    $("#vin_codigo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + vin_ids[0] + "]'  value='" + vin_ids[0] + "'/>");

                    for (var i = 1; i < arr_codigos.length; i++){
                        $("#vin_codigo_lote").append("<h6>VIN: " + arr_codigos[i] + "</h6>");
                        $("#vin_codigo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + vin_ids[i] + "]' value='" + vin_ids[i] + "'/>");
                    }

                    $("#asignarTareaModalLote").modal('show');

                }).fail(function () {
                    alert('Error: Respuesta de datos inválida');
                });
            });

            //Modal Solicitar Tarea
            $('.btn-tarea').click(function (e) {
                e.preventDefault();

                var vin_id = $(this).val();
                var vin_codigo = $("#vin-codigo-" + vin_id).children().html();

                $(".vin-id").val(vin_id);
                $("#vin_codigo").html("<h4>VIN: " + vin_codigo + "</h4>");

                $("#asignarTareaModal").modal('show');
            });
        });
    </script>
@endsection


