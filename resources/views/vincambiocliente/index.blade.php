@extends('layouts.app')
@section('title','Planificación index')
@section('content')
    @include('flash::message')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                <div class="card-body overflow-auto">
                    <div id="error-msg-busqueda" class="alert alert-danger alert-dismissible"></div>
                    {!! Form::open(['route'=> 'planificacion.index2', 'method'=>'post', 'id'=>'VinForm']) !!}
                    <div class="row">
                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="vin_numero" >Vin <strong>*</strong></label>
                                {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control"]) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="empresa_id" >Seleccionar Cliente <strong>*</strong></label>
                                {!! Form::select('empresa_id', $empresas, null,['id' => 'cliente', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>


                        </div>


                    </div>
                    <div class="text-right pb-5">

                            <button type="button" style="display:none" class="btn-rol btn btn-success btn-lote-vins">Cambio de propietario VIN</button>


                            <button id="btn-src" type="button" class="btn btn-primary block full-width m-b">Buscar vins</button>

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
                <div class="card-body overflow-auto">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="TablaVins" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all" />Seleccionar Todos</th>
                                <th>Vin</th>
                                <th>Patente</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Color</th>
                                <!-- <th>Motor</th> -->
                                <th>Segmento</th>
                                <th>Fecha de Ingreso</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th>Patio</th>
                                <th>Bloque</th>
                                <th>Ubicación</th>


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

    <br />





    @include('vincambiocliente.partials.modal_asignar_tarea_lotes')

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



            $('#btn-src').on('click',function(e){
                e.preventDefault();


                datatablesButtons.rows().remove();

                var_roles=0;

                $.post("{{route('planificacion.index5_json')}}", $("#VinForm").serialize(), function (res) {
                    if (res.error == 1){
                        $('#error-msg-busqueda').append('<font color="white">' + res.message + '</font>');

                        return;
                    } else {
                        $('#error-msg-busqueda').remove();
                    }

                    $("#resultado_busqueda_vins").val(JSON.stringify(res));

                    $(res).each(function( index , value ) {

                        if(var_roles==0){

                            $(".btn-rol").show();

                            var_roles=1;
                        }

                        datatablesButtons.row.add( [
                            value.vin_id_checkbox,
                            value.vin_codigo,
                            value.vin_patente,
                            value.marca_nombre,
                            value.vin_modelo,
                            value.vin_color,
                            value.vin_segmento,
                            value.vin_fec_ingreso,
                            value.empresa_razon_social,
                            value.vin_estado_inventario_desc,
                            value.patio_nombre,
                            value.bloque_nombre,
                            value.ubic_patio,
                            value.botones_vin,
                        ]).draw( false );

                        // datatablesButtons.row.add( [
                        //     '<input type="checkbox" class="check-tarea" value="'+value.vin_id+'" name="checked_vins[]" id="check-vin-'+value.vin_id+'">',
                        //     value.vin_codigo,
                        //     value.vin_patente,
                        //     value.marca_nombre.toUpperCase(),
                        //     value.vin_modelo,
                        //     value.vin_color,
                        //     value.vin_segmento,
                        //     value.vin_fec_ingreso,
                        //     value.empresa_razon_social,
                        //     value.vin_estado_inventario_desc,

                        //     (typeof value.patio_nombre !== 'undefined')?value.patio_nombre:"",
                        //     (typeof value.bloque_nombre !== 'undefined')?value.bloque_nombre:"",
                        //     (typeof value.ubic_patio_id !== 'undefined')?('Fila: '+value.ubic_patio_fila+', Columna: '+value.ubic_patio_columna):""

                        // ] ).draw( false );

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
                $('#btn-guardar-campania-lotes').attr("disabled", true);

                $.post("{{route('vin.cambio')}}", datos, function (res) {

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
                        $("#vin_codigo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                        for (var i = 1; i < arr_codigos.length; i++){
                            $("#vin_codigo_lote").append("<h6>VIN: " + arr_codigos[i] + "</h6>");
                            $("#vin_codigo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + i + "]' value='" + vin_ids[i] + "'/>");
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
