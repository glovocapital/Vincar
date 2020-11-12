@extends('layouts.app')
@section('title','Vin index')
@section('custom_styles')
<link href="{{asset('css/switch_button.css')}}" type="text/css" rel="stylesheet">
@endsection
@section('content')
@include('flash::message')

    <!--SUPER ADMINISTRADOR y ADMINISTRADOR -->
    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 4)
        <div class="row">
            <div class="col-lg-12">
                <div class="mx-auto col-sm-12 main-section" id="myTab" role="tablist">
                    <ul class="nav nav-tabs justify-content-end">
                        <li class="nav-item">
                            <a class="nav-link {{ !(request('from_entregado') || request('to_entregado')) ? 'active' : '' }}" id="vin_agendados-tab" data-toggle="tab" href="#vin_agendados" role="tab" aria-controls="vin_agendados" aria-selected="{{ !(request('from_entregado') || request('to_entregado')) ? 'true' : 'false' }}">VIN Agendados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request('from_entregado') || request('to_entregado')) ? 'active' : '' }}" id="vin_entregados-tab" data-toggle="tab" href="#vin_entregados" role="tab" aria-controls="vin_entregados" aria-selected="{{ (request('from_entregado') || request('to_entregado')) ? 'true' : 'false' }}">VIN Entregados</a>
                        </li>
                <!--      <li class="nav-item">
                            <a class="nav-link" id="tareas-historicos-tab" data-toggle="tab" href="#tareas-historicos" role="tab" aria-controls="tareas-historicos" aria-selected="false">Histórico  Tareas</a>
                        </li>  -->
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade {{ !(request('from_entregado') || request('to_entregado')) ? 'show active' : '' }}" id="vin_agendados" role="tabpanel" aria-labelledby="vin_agendados-tab">
                            <div class="ibox float-e-margins">
                                <div class="card card-default">
                                    <div class="card-header">
                                        <h3 class="card-title">VIN agendados para entrega</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            {!! Form::open(['route'=> 'entrega.agendadosExport', 'method'=>'POST']) !!}
                                                <div class="text pb-3">
                                                    {{ Form::button('<i class="fa fa-file-excel"></i> Exportar VINs Agendados ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-expor'] )  }}
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                        <form method="get" action="{{ url('entrega') }}">
                                            <div class="row row-filters">
                                                <div class="col-md-6 text-right">
                                                    <div class="form-inline form-dates">
                                                        <label for="from" class="form-label-sm">Fecha</label>&nbsp;
                                                        <div class="input-group">
                                                            <input type="date" class="form-control form-control-sm" name="from" id="from" placeholder="Desde" value="{{ request('from') }}">
                                                        </div>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control form-control-sm" name="to" id="to" placeholder="Hasta" value="{{ request('to') }}">
                                                        </div>
                                                        &nbsp;
                                                        <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-hover" id="dataTableCampanias" width="100%" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th>Código VIN</th>
                                                    <th>Patente</th>
                                                    <th>Marca</th>
                                                    <th>Vin Color</th>
                                                    <th>Fecha <br/> Agendamiento</th>
                                                    <th>Empresa</th>
                                                    <th>Patio</th>
                                                    <th>Ubicación</th>
                                                    
                                                    @if(Auth::user()->rol_id != 4)
                                                        <th>Bloquear <br/> Entrega</th>
                                                    @endif

                                                    <th>Tipo Agendamiento</th>
                                                    <th>Desde</th>
                                                    <th>Hacia</th>
                                                    @if(Auth::user()->rol_id != 4)
                                                        <th>Acciones</th>
                                                    @endif
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php($item = 0)
                                                @foreach($vin_agendados as $vin_agendado)
                                                    @php($item++)

                                                    @if(isset($vin_agendado))
                                                        <tr>

                                                            <td><small>{{ $vin_agendado->vin_codigo }}</small></td>
                                                            <td><small>{{ $vin_agendado->vin_patente }}</small></td>
                                                            <td><small>{{ strtoupper($vin_agendado->oneMarca->marca_nombre) }}</small></td>
                                                            <td><small>{{ $vin_agendado->vin_color }}</small></td>
                                                            <td><small>{{ date("d-m-Y", strtotime($vin_agendado->vin_fecha_agendado)) }}</small></td>
                                                            <td><small>{{ $vin_agendado->empresa_razon_social }}</small></td>
                                                            <td><small>{{ strtoupper($vin_agendado->patio_nombre) }}</small></td>
                                                            <td><small>BLOQUE: {{ $vin_agendado->bloque_nombre }} <br/> FILA: {{ $vin_agendado->ubic_patio_fila }} <br/> COLUMNA: {{ $vin_agendado->ubic_patio_columna }}</small></td>
                                                            
                                                            @if(Auth::user()->rol_id != 4)
                                                                <td>
                                                                    <div class="switch-button">
                                                                    {!! Form::open(['route' => 'vin.bloquea_entrega', 'method'=>'POST']) !!}
                                                                        <input type="checkbox" name="switch-button" id="switch-label-{{$item}}" class="switch-button__checkbox" value="{{$vin_agendado->vin_id}}"{{$vin_agendado->vin_bloqueado_entrega ?' checked':''}}/>
                                                                        <label for="switch-label-{{$item}}" class="switch-button__label"></label>
                                                                    {!! Form::close() !!}
                                                                    </div>
                                                                </td>
                                                            @endif

                                                            @if($vin_agendado->tipo_agendamiento_id == 1)
                                                                <td><small>Retiro</small></td>
                                                            @else
                                                                <td><small>Traslado</small></td>
                                                            @endif

                                                            <td><small>{{ $vin_agendado->predespacho_origen }}</small></td>
                                                            <td><small>{{ $vin_agendado->predespacho_destino }}</small></td>

                                                            @if(Auth::user()->rol_id != 4)
                                                                <td>
                                                                    <small>
                                                                        <a href = "{{ route('entrega.infoPredespacho', Crypt::encrypt($vin_agendado->vin_id)) }}" class="btn-bloque btn-sm" title="Ver info predespacho"><i class="fas fa-info-circle"></i></a>
                                                                    </small>

                                                                    <small>
                                                                        <a href = "{{ route('vin.desagendado', Crypt::encrypt($vin_agendado->vin_id)) }}" onclick="return confirm('¿Esta seguro que desea quitar el agendamiento del VIN?')" class="btn-bloque btn-sm" title="Eliminar Agendamiento"><i class="far fa-trash-alt"></i></a>
                                                                    </small>
                                                                </td>
                                                            @endif
                                                            
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

                        <div class="tab-pane fade {{ (request('from_entregado') || request('to_entregado')) ? 'show active' : '' }}" id="vin_entregados" role="tabpanel" aria-labelledby="vin_entregados-tab" >
                            <div class="ibox float-e-margins">
                                <div class="card card-default">
                                    <div class="card-header">
                                        <h3 class="card-title">VIN entregados</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            {!! Form::open(['route'=> 'vin.entregaExportResultadoBusquedaVins', 'method'=>'POST']) !!}
                                                <div class="text pb-3">
                                                    {{ Form::button('<i class="fa fa-file-excel"></i> Exportar historial de entregas ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-expor'] )  }}
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="col-lg-12">
                                            <form method="get" action="{{ url('entrega') }}">
                                                <div class="row row-filters">
                                                    <div class="col-md-6 text-right">
                                                        <div class="form-inline form-dates">
                                                            <label for="from_entregado" class="form-label-sm">Fecha</label>&nbsp;
                                                            <div class="input-group">
                                                                <input type="date" class="form-control form-control-sm" name="from_entregado" id="from_entregado" placeholder="Desde" value="{{ request('from_entregado') }}">
                                                            </div>
                                                            <div class="input-group">
                                                                <input type="date" class="form-control form-control-sm" name="to_entregado" id="to_entregado" placeholder="Hasta" value="{{ request('to_entregado') }}">
                                                            </div>
                                                            &nbsp;
                                                            <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="dataTableCampanias" width="100%" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th>Código VIN</th>
                                                    <th>Patente</th>
                                                    <th>Vin Color</th>
                                                    <th>Fecha Agendamiento</th>
                                                    <th>Fecha Retiro</th>
                                                    <th>Días Transcurridos</th>
                                                    <th>Última Ubicación</th>
                                                    <th>Responsable</th>
                                                    <th>Empresa</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($vin_entregados as $entregado)
                                                    @if(isset($entregado))
                                                        <tr>
                                                            <td><small>{{ $entregado->vin_codigo }}</small></td>
                                                            <td><small>{{ $entregado->vin_patente }}</small></td>
                                                            <td><small>{{ $entregado->vin_color }}</small></td>
                                                            <td><small>{{ $entregado->vin_fecha_agendado }}</small></td>
                                                            <td><small>{{ $entregado->entrega_fecha }}</small></td>
                                                            
                                                            @php($agendado = \Carbon\Carbon::createFromFormat('Y-m-d', $entregado->vin_fecha_agendado))
                                                            @php($fechaEntregado = \Carbon\Carbon::createFromFormat('Y-m-d', $entregado->entrega_fecha))
                                                            <td><small>{{ $agendado->diff($fechaEntregado)->days }}</small></td>
                                                            
                                                            @if($entregado->origen_texto)
                                                                <td><small>{{ $entregado->origen_texto }}</small></td>
                                                            @else
                                                                <td><small></small></td>
                                                            @endif

                                                            @php($responsable = \App\User::find($entregado->responsable_id))

                                                            <td><small>{{ $responsable->user_nombre . " " . $responsable->user_apellido }}</small></td>

                                                            <td><small>{{ $entregado->empresa_razon_social }}</small></td>

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

                        <div class="tab-pane fade " id="tareas-historicos" role="tabpanel" aria-labelledby="tareas-historicos-tab">
                            <div class="ibox float-e-margins">
                                <div class="card card-default">
                                    <div class="card-header">
                                        <h3 class="card-title">Histórico Tareas</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            {!! Form::open(['route'=> 'vin.entregaExportResultadoBusquedaVins', 'method'=>'POST']) !!}
                                            <div class="text pb-3">
                                                {{ Form::button('<i class="fa fa-file-excel"></i> Exportar historial de tareas ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-expor'] )  }}
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="TareaCampanias" width="100%" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th>Código VIN</th>
                                                    <th>Prioridad</th>

                                                    <th>Destino</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($vin_entregados_dia as $htarea)
                                                    <tr>
                                                        <td><small>{{ $htarea->vin_codigo }}</small></td>
                                                        <td><small>{{ $htarea->vin_patente }}</small></td>

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
        </div>
    @endif
@stop
@section('local-scripts')
    <script>
        $('#from').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'dd/mm/yyyy'
        });
        $('#to').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'dd/mm/yyyy'
        });
    </script>


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

            $(".switch-button__checkbox").change(function() {
                //e.preventDefault();

                var vin_id = $(this).val();
                
                var bloqueado = false;
                //Si el checkbox está seleccionado
                if($(this).is(":checked")) {
                    bloqueado = true;
                }

                var request = {
                    _token: $("input[name='_token']").attr("value"),
                    bloqueado: bloqueado,
                    vin_id
                };

                var url = 'vin/bloqueaEntrega';

                $.post(url, request, function (res) {
                    if(!res.success){
                        alert(
                            "Error inesperado al intentar bloquear entrega de VIN.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento de bloquear
                    }
                }).fail(function () {
                    alert('Error: Fallo al intentar bloquear entrega de VIN.');
                });
            
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

                    var array_vin_ids = [];

                    $(res).each(function( index , value ) {
                        array_vin_ids.push(value.vin_id);

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
                            value.marca_nombre.toUpperCase(),
                            value.vin_modelo,
                            value.vin_color,
                            value.vin_segmento,
                            value.empresa_razon_social,
                            value.vin_estado_inventario_desc,
                            (typeof value.patio_nombre !== 'undefined')?value.patio_nombre:"",
                            (typeof value.bloque_nombre !== 'undefined')?('<small>BLOQUE: ' + value.bloque_nombre + '</small>'):"",
                            (typeof value.ubic_patio_id !== 'undefined')?('Fila: '+value.ubic_patio_fila+', Columna: '+value.ubic_patio_columna):"",
                            '<font color="'+((value.vin_downloadGuiaN == "Sin Guia")?"Blue":"Green")+'">'+value.vin_downloadGuiaN+'</font>',
                            value.vin_fec_ingreso,
                            value.vin_fecha_agendado,
                            value.vin_fecha_entrega,
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

                            '</small>',
                        ]).draw( false );

                    });

                    $("#resultado_busqueda_vins").attr('value', array_vin_ids);
                    $("#btn-listado-masivo").removeAttr('disabled')
                    
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
                $('#btn-guardar-campania-lotes').attr("disabled", true);

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

@section('local-scripts')
<script>
    $(document).ready(function () {
        $(".switch-button__checkbox").change(function() {
            //e.preventDefault();

            var vin_id = $(this).val();
            
            var bloqueado = false;
            //Si el checkbox está seleccionado
            if($(this).is(":checked")) {
                bloqueado = true;
            }

            var request = {
                _token: $("input[name='_token']").attr("value"),
                bloqueado: bloqueado,
                vin_id
            };

            var url = 'vin/bloqueaEntrega';

            $.post(url, request, function (res) {
                if(!res.success){
                    alert(
                        "Error inesperado al intentar bloquear entrega de VIN.\n\n" +
                        "MENSAJE DEL SISTEMA:\n" +
                        res.message + "\n\n"
                    );
                    return;  // Finaliza el intento de bloquear
                }
            }).fail(function () {
                alert('Error: Fallo al intentar bloquear entrega de VIN.');
            });
        
        });
    });
</script>