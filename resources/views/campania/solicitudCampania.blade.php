@extends('layouts.app')
@section('title','Solicitud de Campaña index')
@section('content')
@include('flash::message')
<div class="row">
<div class="col-lg-12">
        <div class="ibox float-e-margins text-center">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Buscar Vin</h3>

                </div>
                <div class="card-body overflow-auto">
                    {!! Form::open(['route'=> 'solicitud_campania.index2', 'method'=>'post']) !!}
                    <div class="row">
                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                    <label for="vin_numero" >Vin / Patente</label>
                                    {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VIN', 'id' => 'vin_numero','rows' => 4, 'class'=>"form-control"]) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="estado_nombre" >Seleccionar Estado </label>
                                {!! Form::select('estadoinventario_id', $estadosInventario, null,['id' => 'estadoinventario', 'placeholder'=>'Estado', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                    <label for="user_id" >Seleccionar Patio </label>
                                    {!! Form::select('patio_id', $patios, null,['id' => 'patio', 'placeholder'=>'Patio', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>
                            <div class="form-group">
                                <label for="marca_id" >Seleccionar Marca </label>
                                <select name="marca_id" id="marca" class="form-control col-sm-9 select-cliente" placeholder="Marca">
                                    <option value="">Marca</option>
                                    @foreach ($marcas as $marca_id => $marca_nombre)
                                    <option value="{{ $marca_id }}">{{ ucwords($marca_nombre) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-right pb-5">
                        @if(count($tabla_vins) > 0)
                        <button type="button" class="btn btn-danger btn-predespacho-vins btn-rol12">Agendar entrega por lote</button>
                        <button type="button" class="btn btn-success btn-lote-vins">Asignar Campañas por lotes</i></button>
                        @endif

                        {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}

                        {!! Form::close() !!}

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
 <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Vin</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body overflow-auto">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm nowrap" id="dataTableCamp" width="100%" cellspacing="0">
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
                                        <th>Acciones de VIN</th>

                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($tabla_vins as $vin)
                                    @if(isset($vin))
                                    <tr>
                                        <td><input type="checkbox" class="check-campania" value="{{ $vin->vin_id }}" name="checked_vins[]" id="check-vin-{{ $vin->vin_id }}"></td>
                                        <td id="vin-codigo-{{ $vin->vin_id }}"><small>{{ $vin->vin_codigo }}</small></td>
                                        <td><small>{{ $vin->vin_patente }}</small></td>
                                        <td><small>{{ strtoupper($vin->marca_nombre) }}</small></td>
                                        <td><small>{{ $vin->vin_modelo }}</small></td>
                                        <td><small>{{ $vin->vin_color }}</small></td>
                                    <!-- <td><small>{{ $vin->vin_motor }}</small></td> -->
                                        <td><small>{{ $vin->vin_segmento }}</small></td>
                                        <td><small>{{ $vin->vin_fec_ingreso }}</small></td>
                                        <td><small>{{ $vin->empresa_razon_social }}</small></td>
                                        <td><small>{{ $vin->vin_estado_inventario_desc }}</small></td>
                                        @if(isset($vin->patio_nombre))
                                        <td><small>{{ $vin->patio_nombre }}</small></td>
                                        @else
                                        <td><small></small></td>
                                        @endif
                                        @if(isset($vin->bloque_nombre))
                                        <td><small>{{ $vin->bloque_nombre }}</small></td>
                                        @else
                                        <td><small></small></td>
                                        @endif
                                        @if(isset($vin->ubic_patio_id))
                                        <td><small>Fila: {{ $vin->ubic_patio_fila }}, Columna: {{ $vin->ubic_patio_columna }}</small></td>
                                        @else
                                        <td><small></small></td>
                                        @endif

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

                                                <a href="{{ route('vin.editarestado', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Cambiar Estado"><i class="fas fa-flag-checkered"></i></a>

                                            </small>

                                            <small>
                                                <a type="button" value="{{ $vin->vin_id }}" class="btn-campania-modal"  title="Solicitar Campaña"><i class="fas fa-lightbulb"></i></a>
                                            </small>
                                            <!-- <small>
                                                <a type="button"  value="{{ $vin->vin_id }}" class="btn-agendar"  title="Agendar Entrega"><i class="far fa-address-book"></i></a>
                                            </small> -->

                                        </td>
                                    </tr>
                                    @endif
                                @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>



@include('campania.partials.modal_solicitud_campania')
@include('campania.partials.modal_solicitar_campania_lotes')
@include('vin.partials.modal_predespacho')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Listado de Campañas</strong></h3>
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
</div>

@stop
@section('local-scripts')


<script>
        $(document).ready(function () {
            //Modal Solicitar Campaña

            $("#dataTableCamp tbody").on("click",".btn-campania-modal", function (e) {

                e.preventDefault();

                var vin_id = $(this).attr("value");
                var vin_codigo = $("#vin-codigo-" + vin_id).children().html();

                $(".vin-id").val(vin_id);
                $("#vin_codigo").html("VIN: " + vin_codigo);

                $("#solicitudCampaniaModal").modal('show');
            });


            // Mostrar modal de Agendamiento de VINs
            $("#dataTableCamp tbody").on("click",".btn-agendar", function (e) {

                e.preventDefault();

                var vin_id = $(this).attr("value");

                var vin_ids = [vin_id];

                var vin_codigo = $("#vin-codigo-" + vin_id).children().html();

                $(".vin-id").val(vin_id);
                $("#vin_codigo_predespacho").html("VIN: " + vin_codigo);
                $("#vin_codigo_predespacho").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]' value='" + vin_ids[0] + "'/>");

                $("#predespachoModal").modal('show');
            });

            // modal predespacho lotes
            $("#agendamiento_tipo_1").on('change', function (e) {
                e.preventDefault();

                if ($("#agendamiento_tipo_1").prop("checked", true)) {
                    // Ocultar sección de traslado
                    $("#datos_traslado_1").css("display", "none");
                    $("#ruta_origen_1").css("display", "none");
                    $("#search_term_ruta").removeAttr("required");
                    $("#ruta_destino_1").css("display", "none");
                    $("#search_term_ruta_2").removeAttr("required");

                    // Mostrar sección de retiro
                    $("#datos_usuario_1").css("display", "block");
                    $("#nombre_usuario_1").css("display", "block");
                    $("#usuario_nombre").attr("required", "required");
                    $("#apellido_usuario_1").css("display", "block");
                    $("#usuario_apellido").attr("required", "required");
                    $("#rut_usuario_1").css("display", "block");
                    $("#usuario_rut").attr("required", "required");
                    $("#email_usuario_1").css("display", "block");
                    $("#email").attr("required", "required");
                } else if ($("#agendamiento_tipo_2").prop("checked", true)){
                    // Ocultar sección de retiro
                    $("#datos_usuario_1").css("display", "none");
                    $("#nombre_usuario_1").css("display", "none");
                    $("#usuario_nombre").removeAttr("required");
                    $("#apellido_usuario_1").css("display", "none");
                    $("#usuario_apellido").removeAttr("required");
                    $("#rut_usuario_1").css("display", "none");
                    $("#usuario_rut").removeAttr("required");
                    $("#email_usuario_1").css("display", "none");
                    $("#email").removeAttr("required");

                    // Mostrar sección de traslado
                    $("#datos_traslado_1").css("display", "block");
                    $("#ruta_origen_1").css("display", "block");
                    $("#search_term_ruta").attr("required", "required");
                    $("#ruta_destino_1").css("display", "block");
                    $("#search_term_ruta_2").attr("required", "required");
                }
            });

            $("#agendamiento_tipo_2").on('change', function (e) {
                e.preventDefault();

                if ($("#agendamiento_tipo_2").prop("checked", true)) {
                    // Ocultar sección de retiro
                    $("#datos_usuario_1").css("display", "none");
                    $("#nombre_usuario_1").css("display", "none");
                    $("#usuario_nombre").removeAttr("required");
                    $("#apellido_usuario_1").css("display", "none");
                    $("#usuario_apellido").removeAttr("required");
                    $("#rut_usuario_1").css("display", "none");
                    $("#usuario_rut").removeAttr("required");
                    $("#email_usuario_1").css("display", "none");
                    $("#email").removeAttr("required");

                    // Mostrar sección de traslado
                    $("#datos_traslado_1").css("display", "block");
                    $("#ruta_origen_1").css("display", "block");
                    $("#search_term_ruta").attr("required", "required");
                    $("#ruta_destino_1").css("display", "block");
                    $("#search_term_ruta_2").attr("required", "required");
                } else if ($("#agendamiento_tipo_1").prop("checked", true)){
                    // Ocultar sección de traslado
                    $("#datos_traslado_1").css("display", "none");
                    $("#ruta_origen_1").css("display", "none");
                    $("#search_term_ruta").removeAttr("required");
                    $("#ruta_destino_1").css("display", "none");
                    $("#search_term_ruta_2").removeAttr("required");

                    // Mostrar sección de retiro
                    $("#datos_usuario_1").css("display", "block");
                    $("#nombre_usuario_1").css("display", "block");
                    $("#usuario_nombre").attr("required", "required");
                    $("#apellido_usuario_1").css("display", "block");
                    $("#usuario_apellido").attr("required", "required");
                    $("#rut_usuario_1").css("display", "block");
                    $("#usuario_rut").attr("required", "required");
                    $("#email_usuario_1").css("display", "block");
                    $("#email").attr("required", "required");
                }
            });

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
                    $("#error0_predespacho").hide();
                    $("#error1_predespacho").hide();
                    $("#predespachoModal").modal('show');
                }).fail(function () {
                    alert('Error: Debe seleccionar al menos un vin de la lista');
                });
            });

            // // Agendar el VIN
            // $('#btn-pre-despacho').on('click',function(e){
            //     e.preventDefault();

            //     $("#error_0").hide();
            //     $("#error_1").hide();

            //     $.post("{{route('vin.predespacho')}}", $("#PredespachoVins").serialize(), function (res) {

            //         $dat = res;
            //      //  console.log($dat);

            //         if($dat.error==0) $("#error0_predespacho").show();
            //         else {$("#error1_predespacho").show();  $("#error1").html($dat.mensaje); }



            //     }).fail(function () {
            //         alert('Error: ');
            //     });
            //     $('#btn-guardar-campania-lotes').attr("disabled", true);

            // });

            // Agendar el lote de VINs
            $('#btn-pre-despacho').on('click',function(e){
                e.preventDefault();

                $("#error0_predespacho").hide();
                $("#error1_predespacho").hide();

                $.post("{{route('vin.predespacho')}}", $("#PredespachoVins").serialize(), function (res) {
                    $dat = res;

                    if($dat.error == 0) {
                        $("#error0_predespacho").show();
                        $("#PredespachoVins")[0].reset();
                    } else {
                        $("#error1_predespacho").show();
                        $("#error1_predespacho").html($dat.mensaje);
                    }
                }).fail(function () {
                    alert('Error: ');
                });
            });

            $('#btn-cerrar-pre-despacho').click(function (e) {
                e.preventDefault();

                $("#PredespachoVins")[0].reset();
            });


            var checked = false;

            $('.check-all').on('click',function(){

                if(checked == false) {
                $('.check-campania').prop('checked', true);
                    checked = true;
                } else {
                $('.check-campania').prop('checked', false);
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

                        $("#solicitarCampaniaModalLote").modal('show');

                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                }
            });
        });
    </script>
@endsection


