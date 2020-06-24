@extends('layouts.app')
@section('title','Vin index')
@section('content')
@include('flash::message')


    <!-- BUSQUEDA DE VIN   -->
    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins text-center">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Buscar Vin</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['route'=> 'vin.index3', 'method'=>'post', 'id' => 'VinForm']) !!}
                            <div class="row">
                                <div class="col-md-4" id="wrapper_2">
                                    <div class="form-group">
                                        <label for="vin_numero" >Vin <strong>*</strong></label>
                                        {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control"]) !!}
                                    </div>
                                </div>

                                <div class="col-md-6" id="wrapper_2">

                                    <div class="form-group">
                                        <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                                        {!! Form::select('empresa_id', $empresas, null,['id' => 'cliente', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                                    </div>

                                </div>

                            </div>

                            <div class="text-right pb-5" id="botones">

                                    <button type="button" class="btn btn-warning btn-edo-vins btn-rol13" style="display:none">Cambiar estado por lotes</button>

                                    <button id="btn-src" type="button" class="btn btn-primary block full-width m-b">Buscar vins</button>

                            {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Vins</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="TablaVins" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="check-all" />Seleccionar Todos</th>
                                    <th>Vin</th>
                                    <th>Patente</th>

                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Color</th>

                                    <th>Fecha de Ingreso</th>
                                    <th>Cliente</th>
                                    <th>Estado</th>
                                    <th>Guia</th>
                                    <th>Patio</th>
                                    <th>Bloque</th>
                                    <th>Ubicación</th>



                                    <!--  <th>Sub Estado Inventario </th>  -->
                                    <!--   <th>Gestión de Registro</th> -->
                                    <th>Acciones de VIN</th>

                                </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('vincambiocliente.partials.modal_cambia_dueno')


@stop
@section('local-scripts')



    <script>
        $(document).ready(function () {
            var checked = false;

            datatablesButtons = $('[id="TablaVins"]').DataTable({
                responsive: true,
                lengthChange: !1,
                pageLength: 100,
                @if(Session::get('lang')=="es")
                language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                @endif
                buttons: ["copy", "print"],
            });


            // Búsqueda global de VINs
            $('.btn-busqueda-vin-lote').click(function (e){
                e.preventDefault();

                var vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();

                if(vin_ids.length > 0){
                    $("#resultado_busqueda_vins_form").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (var i = 1; i < vin_ids.length; i++){
                        $("#resultado_busqueda_vins_form").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + i + "]' value='" + vin_ids[i] + "'/>");
                    }
                    $("#btn-listado-vins").removeAttr("disabled");
                } else {
                    alert("Debe seleccionar al menos un vin del listado.");
                    $("#btn-listado-vins").attr("disabled", "disabled");
                }


            });
            $('#btn-src').on('click',function(e){
                e.preventDefault();

                datatablesButtons.rows().remove();

                var_roles=0;

                // console.log($("#VinForm").serialize());

                $.post("{{route('vin.index_json')}}", $("#VinForm").serialize(), function (res) {

                    // $("#resultado_busqueda_vins").val(JSON.stringify(res));

                    $(res).each(function( index , value ) {

                        if(var_roles==0){
                            $(".btn-expor").attr("disabled", false);
                            $(".btn-rol").show();
                            if(value.rol_id == 1 ||  value.rol_id == 3){
                                $(".btn-rol13").show();
                                $(".btn-rol12").show();
                            }
                            var_roles=1;
                        }

                        datatablesButtons.row.add( [
                            '<input type="checkbox" class="check-tarea" value="'+value.vin_id+'" name="checked_vins[]" id="check-vin-'+value.vin_id+'">',
                            value.vin_codigo,
                            value.vin_patente,
                            value.vin_marca,
                            value.vin_modelo,
                            value.vin_color,
                            value.vin_fec_ingreso,
                            value.empresa_razon_social,
                            value.vin_estado_inventario_desc,
                            '<font color="'+((value.vin_downloadGuiaN == "Sin Guia")?"Blue":"Green")+'">'+value.vin_downloadGuiaN+'</font>',
                                (typeof value.patio_nombre !== 'undefined')?value.patio_nombre:"",
                                (typeof value.bloque_nombre !== 'undefined')?value.bloque_nombre:"",
                                (typeof value.ubic_patio_id !== 'undefined')?('Fila: '+value.ubic_patio_fila+', Columna: '+value.ubic_patio_columna):"",

                                '<small>'+
                                    '<a href="#" type="button" class="btn-historico"  value="'+value.vin_encrypt+'" title="Ver Historico"><i class="fas fa fa-lightbulb"></i></a>'+
                                '</small>'+
                                ((value.rol_id == 1 || value.rol_id == 2  || value.rol_id == 3)?(

                                '<small>'+
                                '<a href="'+value.vin_edit+'" type="button" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>'+
                                '</small>'+
                                    '<small>'+
                                    '<a  href="'+value.vin_editarestado+'" type="button" class="btn-vin"  title="Cambiar Estado"><i class="fas fa-flag-checkered"></i></a>'+
                                    '</small>'

                                ):"")+
                                '<small>'+
                                ((value.vin_downloadGuiaN == "Sin Guia")?'<a href="'+value.vin_guia+'" type="button" class="btn-vin"  title="Cargar Guía"><i class="fas fa fa-barcode"></i></a>':'<a href="'+value.vin_downloadGuia+'" type="button" class="btn-vin"  title="Descargar Guía"><i class="fas fa fa-barcode2"></i></a>')+

                                '</small>'
                        ]).draw( false );

                    });

                    datatablesButtons.columns.adjust().draw();

                }).fail(function () {
                    alert('Error: ');
                });

            });



            $('.check-all').on('click',function(){
                if(checked == false) {
                    $('.check-tarea').prop('checked', true);
                    checked = true;
                } else {
                    $('.check-tarea').prop('checked', false);
                    checked = false;
                }
            });

            $('.btn-edo-vins').click(function (e){
                e.preventDefault();
                var vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();
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
                    $("#vin_codigo_edo_lote").html("<h6>VIN: " + arr_codigos[0] + "</h6>");
                    $("#vin_codigo_edo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (var i = 1; i < arr_codigos.length; i++){
                        $("#vin_codigo_edo_lote").append("<h6>VIN: " + arr_codigos[i] + "</h6>");
                        $("#vin_codigo_edo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + i + "]' value='" + vin_ids[i] + "'/>");
                    }
                    $("#cambiarEdoModalLote").modal('show');
                }).fail(function () {
                    alert('Error: Debe seleccionar al menos un vin de la lista');
                });
            });

            //Modal Solicitar Tarea
            $('.btn-edo_tarea').click(function (e) {
                e.preventDefault();
                var vin_id = $(this).val();
                var vin_codigo = $("#vin-codigo-" + vin_id).children().html();
                $(".vin-id").val(vin_id);
                $("#vin_codigo_edo").html("<h4>VIN: " + vin_codigo + "</h4>");
                $("#cambiarEdoModalLote").modal('show');
            });


             //modal predespacho
             $('.btn-predespacho-vins').click(function (e){
                e.preventDefault();
                var vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();
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
                    $("#vin_codigo_predespacho").html("<h6>VIN: " + arr_codigos[0] + "</h6>");
                    $("#vin_codigo_predespacho").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (var i = 1; i < arr_codigos.length; i++){
                        $("#vin_codigo_predespacho").append("<h6>VIN: " + arr_codigos[i] + "</h6>");
                        $("#vin_codigo_predespacho").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + i + "]' value='" + vin_ids[i] + "'/>");
                    }
                    $("#predespachoModal").modal('show');
                }).fail(function () {
                    alert('Error: Debe seleccionar al menos un vin de la lista');
                });
            });

            //Modal Histórico del VIN
            $('#TablaVins tbody').on('click', '.btn-historico', function (e) {
                e.preventDefault();
                var id_vin = $(this).attr("value");
                var url = "/historico_vin/historicoVin/" + id_vin;
                $.get(url, function (res) {
                    //Validar primero si algo salió mal
                    if(!res.success){
                        alert(
                            "Error inesperado al solicitar la información.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento de obtener
                    }
                    var arr_eventos = $.map(res.historico_vin, function (e1) {
                        return e1;
                    });
                    // Limpiar la tabla del modal antes de mostrar el historial del vin
                    $("#eventos_vin").empty();
                    for (var i = 0; i < arr_eventos.length; i++){
                        $("#eventos_vin").append("<tr>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['vin_codigo'] + "</td>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['historico_fecha'] + "</td>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['historico_estado'] + "</td>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['responsable'] + "</td>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['origen'] + "</td>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['destino'] + "</td>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['empresa'] + "</td>");
                        $("#eventos_vin").append("<td>" + arr_eventos[i]['descripcion'] + "</td>");
                        $("#eventos_vin").append("</tr>");
                    }
                    $("#historicoVin").modal('show');
                }).fail(function () {
                    alert('Error: Datos no encontrados o incorrectos');
                });
            });
        });
    </script>

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
                    alert('Error: Debe seleccionar al menos un vin de la lista');
                });
            });

            $('#btn-guardar-estados-lotes').on('click',function(e){
                e.preventDefault();

                $("#error0").hide();
                $("#error1").hide();

                $.post("{{route('vin.storeModalCambiaEstado')}}", $("#EstadosVins").serialize(), function (res) {

                    $dat = res;

                    if($dat.error==0) $("#error0").show();
                    else {$("#error1").show();  $("#error1").html($dat.mensaje); }

                }).fail(function () {
                    alert('Error: ');
                });

            });

            $('#btn-pre-despacho').on('click',function(e){
                e.preventDefault();

                $("#error_0").hide();
                $("#error_1").hide();

                $.post("{{route('vin.predespacho')}}", $("#PredespachoVins").serialize(), function (res) {

                    $dat = res;
                 //  console.log($dat);

                    if($dat.error==0) $("#error0_predespacho").show();
                    else {$("#error1_predespacho").show();  $("#error1").html($dat.mensaje); }



                }).fail(function () {
                    alert('Error: ');
                });
                $('#btn-pre-despacho').attr("disabled", true);

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

            // Histórico de Vins por lotes
            $('.btn-historico-vin-lote').click(function (e){
                e.preventDefault();

                var vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();

                if(vin_ids.length > 0){
                    $("#historico_lote_form").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (var i = 1; i < vin_ids.length; i++){
                        $("#historico_lote_form").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + i + "]' value='" + vin_ids[i] + "'/>");
                    }
                    $("#btn-descargar-historico").removeAttr("disabled");
                } else {
                    alert("Debe seleccionar al menos un vin del listado.");
                    $("#btn-descargar-historico").attr("disabled", "disabled");
                }


            });
        });
    </script>


@endsection