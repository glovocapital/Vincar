@extends('layouts.app')
@section('title','Agendados index')
@section('content')
    @include('flash::message')

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
                <li class="nav-item">
                    <a class="nav-link" id="tareas-historicos-tab" data-toggle="tab" href="#tareas-historicos" role="tab" aria-controls="tareas-historicos" aria-selected="false">Histórico  Tareas</a>
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

                            <div class="card-body overflow-auto">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm nowrap" id="TareaCampanias" width="100%" cellspacing="0">
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

                            <div class="card-body overflow-auto">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm nowrap" id="dataTableCampanias" width="100%" cellspacing="0">
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

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade " id="tareas-historicos" role="tabpanel" aria-labelledby="tareas-historicos-tab">
                    <div class="ibox float-e-margins">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Histórico Tareas</strong></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                                </div>
                            </div>

                            <div class="card-body overflow-auto">
                                <div class="col-lg-12">
                                    {!! Form::open(['route'=> 'campania.exportResultadoBusquedaVins', 'method'=>'POST']) !!}
                                    <div class="text pb-3">


                                            <input type="hidden" name="resultado_busqueda" value="{{json_encode($tareas_historicas)}}" id="resultado_busqueda_vins" />

                                            {{ Form::button('<i class="fa fa-file-excel"></i> Exportar historial de tareas ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-expor'] )  }}

                                    </div>
                                    {!! Form::close() !!}

                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm nowrap" id="TareaCampanias" width="100%" cellspacing="0">
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

                                        </tr>
                                        </thead>
                                        <tbody>

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

@stop
@section('local-scripts')


    <script>
        $(document).ready(function () {

            datatablesButtons = $('[id="TablaVins"]').DataTable({
                responsive: false,
                lengthChange: !1,
                pageLength: 100,
                @if(Session::get('lang')=="es")
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                @endif
                buttons: ["copy", "print"],
            });


            datatablesTareas = $('[id="TareaCampanias"]').DataTable({
                responsive: false,
                lengthChange: !1,
                pageLength: 100,
                @if(Session::get('lang')=="es")
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                @endif
                buttons: ["copy", "print"],
            });


            $('#btn-src').on('click',function(e){
                e.preventDefault();


                datatablesButtons.rows().remove();

                var_roles=0;

                $.post("{{route('planificacion.index5_json')}}", $("#VinForm").serialize(), function (res) {

                    $("#resultado_busqueda_vins").val(JSON.stringify(res));

                    $(res).each(function( index , value ) {

                        if(var_roles==0){

                            $(".btn-rol").show();

                            var_roles=1;
                        }

                        datatablesButtons.row.add( [
                            '<input type="checkbox" class="check-tarea" value="'+value.vin_id+'" name="checked_vins[]" id="check-vin-'+value.vin_id+'">',
                            value.vin_codigo,
                            value.vin_patente,
                            value.vin_marca,
                            value.vin_modelo,
                            value.vin_color,
                            value.vin_segmento,
                            value.vin_fec_ingreso,
                            value.empresa_razon_social,
                            value.vin_estado_inventario_desc,

                            (typeof value.patio_nombre !== 'undefined')?value.patio_nombre:"",
                            (typeof value.bloque_nombre !== 'undefined')?value.bloque_nombre:"",
                            (typeof value.ubic_patio_id !== 'undefined')?('Fila: '+value.ubic_patio_fila+', Columna: '+value.ubic_patio_columna):"",

                                '<small>'+
                                '<a target="_blank" href="'+value.vin_edit+'" type="button" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>'+
                                '</small>'+
                                '<small>'+
                                '<a target="_blank" href="'+value.vin_editarestado+'" type="button" class="btn-vin"  title="Cambiar Estado"><i class="fas fa-flag-checkered"></i></a>'+
                                '</small>'+


                                '<small>'+
                                '<a href="#" type="button" class="btn-tarea" vin_codigo="'+value.vin_codigo+'"  value="'+value.vin_encrypt+'" title="Solicitar Tarea"><i class="fas fa fa-lightbulb"></i></a>'+
                                '</small>'




                        ] ).draw( false );

                    });

                    datatablesButtons.columns.adjust().draw();

                }).fail(function () {
                    alert('Error: ');
                });

            });

            enviado = "";
            $('#btn-guardar-campania-lotes').on('click',function(e){
                e.preventDefault();
                $("#error0").hide();
                $("#error1").hide();

                 datos = $("#TareasVins").serialize();
                $("#error1").text("Error al Guardar");
                if(enviado == datos){
                    $("#error1").text("Sin cambios");
                    $("#error1").show();
                    return 0;
                }



                $.post("{{route('campania.storeModalTareaLotes')}}", datos, function (res) {

                    $dat = res;

                    console.log($dat, $dat.error);

                    if($dat.error==0){
                        $("#error0").show();

                        enviado = datos;

                        datatablesTareas.rows().remove();

                        $($dat.tareas).each(function( index , value ) {



                            datatablesTareas.row.add( [
                                value.vin_codigo,
                                ((value.tarea_prioridad==0)?'<small>Baja</small>':
                                    (value.tarea_prioridad==1)?'<small>Media</small>':
                                        (value.tarea_prioridad==2)?'<small>Alta</small>':
                                            (value.tarea_prioridad==3)?'<small>Urgente</small>':
                                                '<small>Sin prioridad</small>'),
                                value.tarea_fecha_finalizacion,
                                value.tarea_hora_termino,
                                value.nombreResponsable,
                                (value.tarea_finalizada)?'Sí':'No',
                                value.TipoTarea,
                                value.TipoDestino,

                                '<small>'+
                                '<a target="_blank" href="'+value.planificacion_edit+'" type="button" class="btn-bloque"  title="Editar Tarea"><i class="far fa-edit"></i></a>'+
                                '</small>'+
                                '<small>'+
                                '<a target="_blank" href="'+value.planificacion_destroy+'" type="button" onclick="return confirm(\'¿Esta seguro que desea eliminar este elemento?\')" class="btn-bloque"  title="Eliminar tarea"><i class="far fa-trash-alt"></i></a>'+
                                '</small>'







                            ] ).draw( false );

                        });

                        datatablesTareas.columns.adjust().draw();


                    }
                    else $("#error1").show();

                }).fail(function () {
                    alert('Error: ');
                });

            });

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
                if (vin_ids.length == 0){
                    alert("Debe seleccionar al menos un vin")
                } else {
                    var url = "planificacion/obtener_codigos_vins";
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
                }
            });
            //Modal Solicitar Tarea
            $("#TablaVins tbody").on("click",".btn-tarea", function (e) {
                e.preventDefault();
                var vin_id = $(this).attr("value");

                var vin_codigo = $(this).attr("vin_codigo");
                $(".vin-id").val(vin_id);
                $("#vin_codigo").html("<h4>VIN: " + vin_codigo + "</h4>");
                $("#asignarTareaModal").modal('show');
            });
        });
    </script>
@endsection
