@extends('layouts.app')
@section('title','Vin index')
@section('custom_styles')
<link href="{{asset('css/switch_button.css')}}" type="text/css" rel="stylesheet">
<style>
.verticalLine {
    border-left: thin solid lightgrey;
  }
</style>
@endsection
@section('content')
@include('flash::message')

    <!-- BUSQUEDA DE VIN   -->
    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins text-center">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Buscar Vin</h3>
                        </div>
                        <div class="card-body overflow-auto">
                            <div id="error-msg-busqueda"></div>
                            {!! Form::open(['route'=> 'vin.index3', 'method'=>'post', 'id' => 'VinForm']) !!}
                            <div class="row">
                                <div class="col-md-4" id="wrapper_2">
                                    <div class="form-group">
                                        <label for="vin_numero" >Vin <strong>*</strong></label>
                                        {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control"]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4" id="wrapper_2">

                                    <div class="form-group">
                                        <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
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
                                        <label for="marca_id" >Seleccionar Marca <strong>*</strong></label>

                                        <select name="marca_id" id="marca" class="form-control col-sm-9 select-cliente">
                                            <option value="" >Marca</option>
                                            @foreach ($marcas as $marca_id => $marca_nombre)
                                            <option value="{{ $marca_id }}">{{ ucwords($marca_nombre) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right pb-1" id="botones">

                                    <button type="button" class="btn btn-danger btn-predespacho-vins btn-rol12" style="display:none">Asignar para entrega</button>

                                    <button type="button" class="btn btn-success btn-lote-vins btn-rol" style="display:none">Cargar guía por lote</button>

                                    <button type="button" class="btn btn-warning btn-edo-vins btn-rol13" style="display:none">Cambiar estado por lote</button>

                                    <button id="btn-src" type="button" class="btn btn-primary block full-width m-b">Buscar vins</button>

                            {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



    @if(Auth::user()->rol_id == 4)
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins text-center">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Buscar Vin</h3>
                        </div>
                        <div class="card-body overflow-auto">
                            <div id="error-msg-busqueda"></div>
                            {!! Form::open(['route'=> 'vin.index2', 'method'=>'post', 'id'=>'VinForm']) !!}
                            <div class="row">
                                <div class="col-md-4" id="wrapper_2">
                                    <div class="form-group">
                                        <label for="vin_numero" >Vin <strong>*</strong></label>
                                        {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control"]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4" id="wrapper_2">

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
                                        <label for="marca_id" >Seleccionar Marca <strong>*</strong></label>
                                        <select name="marca_id" id="marca" class="form-control col-sm-9 select-cliente">
                                            <option value="" >Marca</option>
                                            @foreach ($marcas as $marca_id => $marca_nombre)
                                            <option value="{{ $marca_id }}">{{ ucwords($marca_nombre) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right pb-1">

                                    <button type="button" class="btn btn-danger btn-predespacho-vins btn-rol4" style="display:none">Asignar para entrega</button>

                                    <button type="button" style="display:none" class="btn btn-success btn-lote-vins btn-rol">Cargar guía por lote</button>

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

                        <hr />

                        <!--SUPER ADMINISTRADOR y ADMINISTRADOR -->
                        @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2)
                        <div class="row float-right">
                            <div>
                                {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                                <div class="text pb-1">
                                    {{ Form::button('<i class="fa fa-briefcase"></i> Registrar VIN', ['type' => 'submit', 'class' => 'btn btn-success block full-width m-b'] )  }}
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <div class="pl-2">
                                {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                                <div class="text pb-1">
                                    {{ Form::button('<i class="fa fa-car"></i> Vehiculos N/N', ['type' => 'submit', 'class' => 'btn btn-primary btn-vehiculo-n-n block full-width m-b'] )  }}
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <div class="pl-2">
                                <div class="text pb-1">
                                    <button type="button" class="btn btn-info btn-busqueda-vin-lote btn-rol13" style="display:none">Exportar Lista de VINs</button>
                                    {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_busqueda_vins_form']) !!}
                                    {{ Form::button('<i class="fa fa-file-excel"></i>Listado de VINs ', ['id' => 'btn-listado-vins', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-listado-vins', 'disabled'] )  }}
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="pl-2">
                                <div class="text pb-1">
                                    <button type="button" class="btn btn-info btn-historico-vin-lote btn-rol13" style="display:none">Exportar Histórico por lote</button>

                                    {!! Form::open(['route'=> 'historico_vin.exportHistoricoVinLote', 'method'=>'post', 'id' => 'historico_lote_form']) !!}
                                    {{ Form::button('<i class="fa fa-file-excel"></i> Histórico Lotes ', ['id' => 'btn-descargar-historico', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- OPERADOR LOGISTICO  -->
                        @if(Auth::user()->rol_id == 3)
                        <div class="row float-right">
                            <div class="pl-2">
                                {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                                <div class="text pb-1">
                                    {{ Form::button('<i class="fa fa-car"></i> Vehiculos N/N', ['type' => 'submit', 'class' => 'btn btn-primary btn-vehiculo-n-n block full-width m-b'] )  }}
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <div class="pl-2">
                                <div class="text pb-1">
                                    <button type="button" class="btn btn-info btn-busqueda-vin-lote btn-rol13" style="display:none">Exportar Lista de VINs</button>
                                    {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_busqueda_vins_form']) !!}
                                    {{ Form::button('<i class="fa fa-file-excel"></i>Listado de VINs ', ['id' => 'btn-listado-vins', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-listado-vins', 'disabled'] )  }}
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="pl-2">
                                <div class="text pb-1">
                                    <button type="button" class="btn btn-info btn-historico-vin-lote btn-rol13" style="display:none">Exportar Histórico por lote</button>

                                    {!! Form::open(['route'=> 'historico_vin.exportHistoricoVinLote', 'method'=>'post', 'id' => 'historico_lote_form']) !!}
                                    {{ Form::button('<i class="fa fa-file-excel"></i> Histórico Lotes ', ['id' => 'btn-descargar-historico', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- CUSTOMERS -->
                        @if(Auth::user()->rol_id == 4)
                        <div class="row float-right">
                            <div>
                                {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                                <div class="text pb-1">
                                    {{ Form::button('<i class="fa fa-briefcase"></i> Registrar VIN', ['type' => 'submit', 'class' => 'btn btn-success block full-width m-b'] )  }}
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <div class="pl-2">
                                <div class="text pb-1">
                                    <button type="button" class="btn btn-info btn-busqueda-vin-lote btn-rol13" style="display:none">Exportar Lista de VINs</button>
                                    {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_busqueda_vins_form']) !!}
                                    {{ Form::button('<i class="fa fa-file-excel"></i>Listado de VINs ', ['id' => 'btn-listado-vins', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-listado-vins', 'disabled'] )  }}
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="pl-2">
                                <div class="text pb-1">
                                    <button type="button" class="btn btn-info btn-historico-vin-lote btn-rol13" style="display:none">Exportar Histórico por lote</button>

                                    {!! Form::open(['route'=> 'historico_vin.exportHistoricoVinLote', 'method'=>'post', 'id' => 'historico_lote_form']) !!}
                                    {{ Form::button('<i class="fa fa-file-excel"></i> Histórico Lotes ', ['id' => 'btn-descargar-historico', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        {!! Form::open(['route'=> 'vin.exportMasivoResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_masivo_busqueda_vins_form']) !!}
                            <input type="hidden" name="vin_ids" value="" id="resultado_busqueda_vins" />
                            {{ Form::button('<i class="fa fa-file-excel"></i> Descargar Todos', ['id' => 'btn-listado-masivo', 'type' => 'submit', 'class' => 'btn btn-success block full-width m-b btn-listado-masivo-vins', 'disabled', 'style' => 'display:none'] )  }}
                        {!! Form::close() !!}
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
                                    <th>Segmento</th>
                                    <th>Cliente</th>
                                    <th>Estado</th>
                                    <th>Patio</th>
                                    <th>Bloque</th>
                                    <th>Ubicación</th>
                                    <th>Guia</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Fecha de Agendado</th>
                                    <th>Fecha de Entrega</th>



                                    <!--  <th>Sub Estado Inventario </th>  -->
                                    <!--   <th>Gestión de Registro</th> -->
                                    <th>Acciones de VIN</th>

                                </tr>
                                </thead>
                                <tbody id="tableRows">

                                @foreach($tabla_vins as $vin)
                                    @if(isset($vin))
                                        <tr>
                                            <td><input type="checkbox" class="check-tarea" value="{{ $vin->vin_id }}" name="checked_vins[]" id="check-vin-{{ $vin->vin_id }}"></td>

                                            <td id="vin-codigo-{{ $vin->vin_id }}"><small>{{ $vin->vin_codigo }}</small></td>
                                            <td><small>{{ $vin->vin_patente }}</small></td>
                                            <td><small>{{ $vin->oneMarca->marca_nombre }}</small></td>
                                            <td><small>{{ $vin->vin_modelo }}</small></td>
                                            <td><small>{{ $vin->vin_color }}</small></td>
                                            <td><small>{{ $vin->vin_segmento }}</small></td>
                                            <td><small>{{ $vin->vin_fec_ingreso }}</small></td>
                                            <td><small>{{ $vin->vin_fecha_entrega }}</small></td>
                                            <td><small>{{ $vin->empresa_razon_social }}</small></td>
                                            <td><small>{{ $vin->vin_estado_inventario_desc }}</small></td>
                                            <td>
                                                <small>
                                                    <a href="{{route('vin.downloadGuia', Crypt::encrypt($vin->vin_id)) }}">
                                                        Guia</a>
                                                </small>
                                            </td>
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
                                            <td>
                                                <small>
                                                    <a href="#" type="button" class="btn-historico"  value="{{ Crypt::encrypt($vin->vin_id) }}" title="Ver Historico"><i class="fas fa fa-lightbulb"></i></a>
                                                </small>

                                                @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2  || auth()->user()->rol_id == 3)
                                                    <small>
                                                        <a href="{{ route('vin.edit', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>
                                                    </small>

                                                    <small>
                                                        <a href="{{ route('vin.editarestado', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Cambiar Estado"><i class="fas fa-flag-checkered"></i></a>
                                                    </small>
                                                @endif

                                                <small>
                                                    <a href="{{ route('vin.guia', Crypt::encrypt($vin->vin_id)) }}" class=" btn-vin"  title="Cargar Guía"><i class="fas fa fa-barcode"></i></a>
                                                </small>


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


    @include('vin.partials.modal_asignar_guia_lote')
    @include('vin.partials.modal_cambia_estado')
    @include('vin.partials.modal_historico_vin')
    @include('vin.partials.modal_predespacho')
    @include('vin.partials.modal_vehiculo_n_n')

@stop
@section('local-scripts')
    <script>
        $(document).ready(function () {
            let checked = false;

            const MAXIMO_TAMANO_BYTES = 20000000; // 1MB = 1 millón de bytes
            const $inputFile = document.querySelector("#guia_vin");

            $inputFile.addEventListener("change", function () {
                // si no hay archivo, regresa
                if (this.files.length <= 0) return;

                // Validar el archivo
                const archivo = this.files[0];
                if (archivo.size > MAXIMO_TAMANO_BYTES) {
                    const tamanoEnMb = MAXIMO_TAMANO_BYTES / 1000000;
                    alert('El máximo tamaño de archivo permitido es ' + tamanoEnMb + ' MB');
                    // Limpiar el formulario
                    $inputFile.value = "";
                    return;
                }
            });

            //Carga de Modal Vehículos N/N
            $('.btn-vehiculo-n-n').click(function (e) {
                e.preventDefault();

                $("#vin_id_nn").empty();
                $("#vin_id_nn").append("<option value=''>Seleccione VIN</option>");

                $.get("{{route('vehiculo_nn')}}", function (res) {
                    if(!res.success){
                        alert(
                            "Error inesperado al solicitar la información.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento de obtener
                    }
                    let arr_vins = $.map(res.vins, function (e1) {
                        return e1;
                    });

                    let arr_vin_ids = $.map(res.vin_ids, function (e1) {
                        return e1;
                    });

                    for (let i = 0; i < arr_vins.length; i++){
                        $("#vin_id_nn").append("<option value=" + arr_vin_ids[i] + ">" + arr_vins[i] + "</option>");
                    }

                    $("#vehiculoN_NModal").modal('show');
                }).fail(function () {
                    alert('Error: ');
                });

            });

            $('#vin_id_nn').on('change', function(e) {
                e.preventDefault();

                let sel = $(this).val();

                if($.isNumeric(sel)) {
                    $('#fotos_nn').empty();

                    let url = "vehiculo_nn/" + sel +"/data_vin_nn";

                    $.get(url, function (res) {
                        if(!res.success){
                            alert(
                                "Error inesperado al solicitar la información.\n\n" +
                                "MENSAJE DEL SISTEMA:\n" +
                                res.message + "\n\n"
                            );
                            return;  // Finaliza el intento de obtener
                        }

                        $("#user_id_nn").val(res.user.user_id);
                        $("#usuario_responsable_nn").val(res.user.user_nombre + ' ' + res.user.user_apellido);

                        let arr_fotos = $.map(res.fotos, function (e1) {
                            return e1;
                        });

                        if(arr_fotos.length > 0){
                            $('#fotos_nn').append('<h4 id="titulo_fotos_nn">Fotos Pre-inspección</h4>');
                            $('#fotos_nn').append('<h5 id="nota_fotos_nn">Serán añadidas al realizar la inspección del VIN</h5>');
                            $('#fotos_nn').append('<table class="table table-borderless table-hover" id="thumbnail_nn"></table>');
                        }

                        for (let i = 0; i < arr_fotos.length; i++){
                            if (i == 0){
                                $("#thumbnail_nn").empty();
                            }

                            $("#thumbnail_nn").append("<td><img src='/" + arr_fotos[i].foto_ubic_archivo + "' alt='/" + arr_fotos[i].foto_ubic_archivo + "' width='100' height='100'></td>");
                        }

                        $("#vin_codigo_n_n").attr('value', res.vin.vin_codigo).val(res.vin.vin_codigo);
                        $("#vin_patente_n_n").attr('value', res.vin.vin_patente).val(res.vin.vin_patente);
                        $("#vin_modelo_n_n").attr('value', res.vin.vin_modelo).val(res.vin.vin_modelo);
                        $("#vin_marca_nn").val(res.vin.vin_marca);
                        $("#vin_marca_nombre_n_n").val(res.marca);
                        $("#vin_color_n_n").attr('value', res.vin.vin_color).val(res.vin.vin_color);
                        $("#vin_motor_n_n").attr('value', res.vin.vin_motor).val(res.vin.vin_motor);
                        $("#vin_procedencia_n_n").attr('value', res.vin.vin_procedencia).val(res.vin.vin_procedencia);
                        $("#vin_destino_n_n").attr('value', res.vin.vin_destino).val(res.vin.vin_destino);
                    }).fail(function () {
                        alert('Error: ');
                    });
                } else {
                    $("#user_id_nn").val('');
                    $("#usuario_responsable_nn").val('');
                    $('#fotos_nn').empty();
                    $("#thumbnail_nn").empty();
                    $("#vin_codigo_n_n").attr('value', '').val('');
                    $("#vin_patente_n_n").attr('value', '').val('');
                    $("#vin_modelo_n_n").attr('value', '').val('');
                    $("#vin_marca_nn").val('');
                    $("#vin_marca_nombre_n_n").attr('value', '').val('');
                    $("#vin_color_n_n").attr('value', '').val('');
                    $("#vin_motor_n_n").attr('value', '').val('');
                }
            });

            $('#btn-send-vehiculo-n-n').click(function (e) {
                e.preventDefault();

                if($.isNumeric($("#vin_id_nn").val())){
                    $.post("{{route('vehiculo_nn.registrarVin')}}", $("#form-vehiculo-nn").serialize(), function (res) {
                        if(!res.success){
                            alert(
                                "Error inesperado al solicitar la información.\n\n" +
                                "MENSAJE DEL SISTEMA:\n" +
                                res.message + "\n\n"
                            );
                            return;  // Finaliza el intento de obtener
                        }

                        $("#user_id_nn").val('');
                        $("#usuario_responsable_nn").val('');
                        $('#fotos_nn').empty();
                        $("#thumbnail_nn").empty();
                        $("#vin_codigo_n_n").attr('value', '').val('');
                        $("#vin_patente_n_n").attr('value', '').val('');
                        $("#vin_modelo_n_n").attr('value', '').val('');
                        $("#vin_marca_nn").val('');
                        $("#vin_marca_nombre_n_n").attr('value', '').val('');
                        $("#vin_color_n_n").attr('value', '').val('');
                        $("#vin_motor_n_n").attr('value', '').val('');

                        $('#vin_id_nn option').each(function() {
                            if ( $(this).val() == $('#vin_id_nn').val() ) {
                                $(this).remove();
                                $("#messages_n_n").empty();
                                $("#messages_n_n").fadeIn();
                            }
                        });

                        $("#messages_n_n").append('<p id="success-msg" class="bg-success" style="color: white;">' + res.message + '</p>').fadeOut(4500);
                    }).fail(function () {
                        alert('Error: ');
                    });
                } else {
                    alert("No se han enviado datos. Por favor seleccione un VIN.");
                }
            });

            $('#btn-cerrar-vehiculos-nn').click(function (e) {
                e.preventDefault();

                $("#form-vehiculo-nn")[0].reset();
                $("#fotos_nn").empty();
                $("#vin_codigo_n_n").val('');
                $("#vin_patente_n_n").val('');
                $("#vin_modelo_n_n").val('');
                $("#vin_color_n_n").val('');
                $("#vin_motor_n_n").val('');
            });

            // Búsqueda global de VINs
            $('.btn-busqueda-vin-lote').click(function (e){
                e.preventDefault();

                let vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();

                if(vin_ids.length > 0){
                    $("#resultado_busqueda_vins_form").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (let i = 1; i < vin_ids.length; i++){
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

                $('#error-msg-busqueda').empty();

                var_roles=0;

                $.post("{{route('vin.index_json')}}", $("#VinForm").serialize(), function (res) {
                    let array_vin_ids = [];

                    if (res.error == 1){
                        $('#error-msg-busqueda').append('<font color="red">' + res.message + '</font>');

                        return;
                    }

                    let array_resultados = [];

                    $(res).each(function( index , value ) {
                        array_vin_ids.push(value.vin_id);

                        if(var_roles==0){
                            $(".btn-expor").attr("disabled", false);
                            $(".btn-listado-masivo-vins").attr("disabled", false);
                            $(".btn-listado-masivo-vins").show();
                            $(".btn-rol").show();
                            if(value.rol_id == 1 ||  value.rol_id == 3){
                                $(".btn-rol13").show();
                                $(".btn-rol12").show();
                            } else if (value.rol_id == 4){
                                $(".btn-rol4").show();
                            }
                            var_roles=1;
                        }

                        let array_registro = [
                            value.vin_id_checkbox,
                            value.vin_codigo,
                            value.vin_patente,
                            value.marca_nombre,
                            value.vin_modelo,
                            value.vin_color,
                            value.vin_segmento,
                            value.empresa_razon_social,
                            value.vin_estado_inventario_desc,
                            value.patio_nombre,
                            value.bloque_nombre,
                            value.ubic_patio,
                            value.color_texto_guia,
                            value.vin_fec_ingreso,
                            value.vin_fecha_agendado,
                            value.vin_fecha_entrega,
                            value.botones_vin,
                        ];

                        array_resultados.push(array_registro);
                    });

                    $("#resultado_busqueda_vins").attr('value', array_vin_ids);
                    $("#btn-listado-masivo").removeAttr('disabled');

                    if (array_resultados.length > 0) {
                        $('[id="TablaVins"]').DataTable().destroy();
                        let tabla = $('[id="TablaVins"]').DataTable({
                            searching: true,
                            bSortClasses: false,
                            deferRender:true,
                            responsive: false,
                            pageLength: 50,
                            buttons: ["copy", "print"],
                            data: array_resultados,
                            order: [[1, "asc"]],
                            columns: [
                                {
                                    width: "1%",
                                    data: array_resultados.vin_id_checkbox
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_codigo
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_patente
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.marca_nombre
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_modelo
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_color
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_segmento
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.empresa_razon_social
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_estado_inventario_desc
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.patio_nombre
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.bloque_nombre
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.ubic_patio
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.color_texto_guia
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_fec_ingreso
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_fecha_agendado
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.vin_fecha_entrega
                                },
                                {
                                    width: "1%",
                                    data: array_resultados.botones_vin
                                }
                            ],
                            language: {
                                sProcessing: "Procesando...",
                                sLengthMenu: "Mostrar _MENU_ registros",
                                sZeroRecords: "No se encontraron resultados",
                                sEmptyTable: "Ningún dato disponible en esta tabla",
                                sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                                sInfoPostFix: "",
                                sSearch: "Buscar:",
                                sUrl: "",
                                sInfoThousands: ",",
                                sLoadingRecords: "Cargando...",
                                oPaginate: {
                                    sFirst: "Primero",
                                    sLast: "Último",
                                    sNext: "Siguiente",
                                    sPrevious: "Anterior"
                                },
                                oAria: {
                                    sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                                    sSortDescending: ": Activar para ordenar la columna de manera descendente"
                                }
                            }
                        });

                        tabla.responsive.recalc().columns.adjust().draw();
                    } else {
                        $('[id="TablaVins"]').DataTable().clear();
                        $('[id="TablaVins"]').DataTable().draw();
                    }
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
                let vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();
                let url = "planificacion/obtener_codigos_vins";
                let request = {
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
                    let arr_codigos = $.map(res.codigos, function (e1) {
                        return e1;
                    });
                    $("#vin_codigo_edo_lote").html("<h6>VIN: " + arr_codigos[0] + "</h6>");
                    $("#vin_codigo_edo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (let i = 1; i < arr_codigos.length; i++){
                        $("#vin_codigo_edo_lote").append("<h6>VIN: " + arr_codigos[i] + "</h6>");
                        $("#vin_codigo_edo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + i + "]' value='" + vin_ids[i] + "'/>");
                    }
                    $("#cambiarEdoModalLote").modal('show');
                }).fail(function () {
                    alert('Error: Debe seleccionar al menos un vin de la lista');
                });
            });

            $('.btn-download-guias-vin').on('click', function(e){
                e.preventDefault;
                vin_id = $(this).val();

                alert(vin_id);

                // $.get("route('vin.downloadGuiasVin', Crypt::encrypt($vin->vin_id))", function (res) {
            });

            //Modal Solicitar Tarea
            $('.btn-edo_tarea').click(function (e) {
                e.preventDefault();
                let vin_id = $(this).val();
                let vin_codigo = $("#vin-codigo-" + vin_id).children().html();
                $(".vin-id").val(vin_id);
                $("#vin_codigo_edo").html("<h4>VIN: " + vin_codigo + "</h4>");
                $("#cambiarEdoModalLote").modal('show');
            });


             //modal predespacho
            $('.btn-predespacho-vins').click(function (e){
                e.preventDefault();
                let vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();
                let url = "planificacion/obtener_codigos_vins";
                let request = {
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
                    let arr_codigos = $.map(res.codigos, function (e1) {
                        return e1;
                    });
                    $("#vin_codigo_predespacho").html("<h6>VIN: " + arr_codigos[0] + "</h6>");
                    $("#vin_codigo_predespacho").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (let i = 1; i < arr_codigos.length; i++){
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

            //Modal Histórico del VIN
            $('#TablaVins tbody').on('click', '.btn-historico', function (e) {
                e.preventDefault();
                let id_vin = $(this).attr("value");
                let url = "/historico_vin/historicoVin/" + id_vin;
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
                    let arr_eventos = $.map(res.historico_vin, function (e1) {
                        return e1;
                    });
                    // Limpiar la tabla del modal antes de mostrar el historial del vin
                    $("#eventos_vin").empty();
                    for (let i = 0; i < arr_eventos.length; i++){
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
            let checked = false;
            $('.check-all').on('click',function(){
                if(checked == false) {
                    $('.check-tarea').prop('checked', true);
                    checked = true;
                } else {
                    $('.check-tarea').prop('checked', false);
                    checked = false;
                }
            });

            // Carga de guías por lotes
            $('.btn-lote-vins').click(function (e){
                e.preventDefault();
                let vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();
                let url = "planificacion/obtener_codigos_vins";
                let request = {
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
                    let arr_codigos = $.map(res.codigos, function (e1) {
                        return e1;
                    });
                    $("#form-carga-guia-lote")[0].reset();

                    $("#vin_codigo_lote").html("<h6>VIN: " + arr_codigos[0] + "</h6>");
                    $("#vin_codigo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (let i = 1; i < arr_codigos.length; i++){
                        $("#vin_codigo_lote").append("<h6>VIN: " + arr_codigos[i] + "</h6>");
                        $("#vin_codigo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + i + "]' value='" + vin_ids[i] + "'/>");
                    }

                    $("#empresa_id").val('');
                    $("#guia_fecha").val('');
                    $("#guia_numero").val('');
                    $("#guia_vin").val('');
                    $("#asignarGuiaModalLote").modal('show');
                }).fail(function () {
                    alert('Error: Debe seleccionar al menos un vin de la lista');
                });
            });

            $('#btn-cancelar-carga-guia-lote').click(function (e) {
                e.preventDefault();

                $("#form-carga-guia-lote")[0].reset();
                $("#vin_codigo_lote").empty();
                $("#empresa_id").val('');
                $("#guia_fecha").val('');
                $("#guia_numero").val('');
                $("#guia_vin").val('');
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

            // Agendamiento (pre-despacho) de VINs
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
                $("#error0_predespacho").empty();
                $("#error1_predespacho").empty();
            });

            //Modal Solicitar Tarea
            $('.btn-tarea').click(function (e) {
                e.preventDefault();
                let vin_id = $(this).val();
                let vin_codigo = $("#vin-codigo-" + vin_id).children().html();
                $(".vin-id").val(vin_id);
                $("#vin_codigo").html("<h4>VIN: " + vin_codigo + "</h4>");
                $("#asignarTareaModal").modal('show');
            });

            // Histórico de Vins por lotes
            $('.btn-historico-vin-lote').click(function (e){
                e.preventDefault();

                let vin_ids = $('[name="checked_vins[]"]:checked').map(function(){
                    return this.value;
                }).get();

                if(vin_ids.length > 0){
                    $("#historico_lote_form").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + 0 + "]'  value='" + vin_ids[0] + "'/>");
                    for (let i = 1; i < vin_ids.length; i++){
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
