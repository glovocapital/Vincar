@extends('layouts.app')
@section('title','Vin index')
@section('content')

<!--SUPER ADMINISTRADOR -->
@if(Auth::user()->rol_id == 1)
<div class="row">
    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title text-center">Cargar Vehiculos </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                            <div class="text  pb-3">
                                {{ Form::button('<i class="fa fa-briefcase"></i> Registrar vin', ['type' => 'submit', 'class' => 'btn btn-success block full-width m-b'] )  }}
                            </div>
                                {!! Form::close() !!}
                            <div class="text  pb-3">
                                <a href="{{ route('vin.download') }}">Descargar formato de archivo de carga</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title">Vehiculos N/N  </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                            <div class="text pb-3">
                                {{ Form::button('<i class="fa fa-car"></i> Vehiculos N/N', ['type' => 'submit', 'class' => 'btn btn-primary block full-width m-b', 'disabled'] )  }}
                            </div>
                                {!! Form::close() !!}
                            <div class="text  pb-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title">Exportar Tabla</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST']) !!}
                            <div class="text pb-3">
                            @if(count($tabla_vins) > 0)
                                {{ Form::button('<i class="fa fa-file-excel"></i> Exportar VIN ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b'] )  }}
                                <input type="hidden" name="resultado_busqueda" value="{{json_encode($tabla_vins)}}" id="resultado_busqueda_vins" />
                            @else
                                {{ Form::button('<i class="fa fa-file-excel"></i> Exportar VIN ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                            @endif
                            </div>
                                {!! Form::close() !!}
                            <div class="text  pb-3">
                                Haz click para exportar tu búsqueda
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- OPERADOR LOGISTICO  -->
@if(Auth::user()->rol_id == 3)
<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title">Vehiculos N/N  </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                            <div class="text pb-3">
                                {{ Form::button('<i class="fa fa-car"></i> Vehiculos N/N', ['type' => 'submit', 'class' => 'btn btn-primary block full-width m-b', 'disabled'] )  }}
                            </div>
                                {!! Form::close() !!}
                            <div class="text  pb-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title">Exportar Tabla</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST']) !!}
                            <div class="text pb-3">
                            @if(count($tabla_vins) > 0)
                                {{ Form::button('<i class="fa fa-file-excel"></i> Exportar VIN ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b'] )  }}
                                <input type="hidden" name="resultado_busqueda" value="{{json_encode($tabla_vins)}}" id="resultado_busqueda_vins" />
                            @else
                                {{ Form::button('<i class="fa fa-file-excel"></i> Exportar VIN ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                            @endif
                            </div>
                                {!! Form::close() !!}
                            <div class="text  pb-3">
                                Haz click para exportar tu búsqueda
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- CUSTOMERS -->
@if(Auth::user()->rol_id == 4)
<div class="row">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title text-center">Cargar Vehiculos </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                            <div class="text  pb-3">
                                {{ Form::button('<i class="fa fa-briefcase"></i> Registrar vin', ['type' => 'submit', 'class' => 'btn btn-success block full-width m-b'] )  }}
                            </div>
                                {!! Form::close() !!}
                            <div class="text  pb-3">
                                <a href="{{ route('vin.download') }}">Descargar formato de archivo de carga</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="card card-default text-center">
                <div class="card-header">
                    <h3 class="card-title">Exportar Tabla</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST']) !!}
                            <div class="text pb-3">
                            @if(count($tabla_vins) > 0)
                                {{ Form::button('<i class="fa fa-file-excel"></i> Exportar VIN ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b'] )  }}
                                <input type="hidden" name="resultado_busqueda" value="{{json_encode($tabla_vins)}}" id="resultado_busqueda_vins" />
                            @else
                                {{ Form::button('<i class="fa fa-file-excel"></i> Exportar VIN ', ['type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                            @endif
                            </div>
                                {!! Form::close() !!}
                            <div class="text  pb-3">
                                Haz click para exportar tu búsqueda
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
                    {!! Form::open(['route'=> 'vin.index', 'method'=>'get']) !!}
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
                                <label for="marca_nombre" >Seleccionar Marca <strong>*</strong></label>
                                {!! Form::select('marca_id', $marcas, null,['id' => 'marca', 'placeholder'=>'Marca', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        @if(count($tabla_vins) > 0)
                        <button type="button" class="btn btn-success btn-lote-vins">Carga de guías por lotes</i></button>
                            @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3)
                                <button type="button" class="btn btn-warning btn-edo-vins">Cambia Estado por lotes</i></button>
                            @endif
                        @endif
                        {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}
                        {!! Form::close() !!}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endif



@if(Auth::user()->rol_id == 4)
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins text-center">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Buscar Vin</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route'=> 'vin.index2', 'method'=>'get']) !!}
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
                                <label for="marca_nombre" >Seleccionar Marca <strong>*</strong></label>
                                {!! Form::select('marca_id', $marcas, null,['id' => 'marca', 'placeholder'=>'Marca', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        @if(count($tabla_vins) > 0)
                        <button type="button" class="btn btn-success btn-lote-vins">Carga de guías por lotes</i></button>
                            @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3)
                                <button type="button" class="btn btn-warning btn-edo-vins">Cambia Estado por lotes</i></button>
                            @endif
                        @endif
                        {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}
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
                            <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="check-all" />Seleccionar Todos</th>
                                        <th>Vin</th>
                                        <th>Patente</th>

                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Color</th>
                                        <th>Segmento</th>
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
                                <tbody>

                                @foreach($tabla_vins as $vin)
                                @if(isset($vin))
                                <tr>
                                    <td><input type="checkbox" class="check-tarea" value="{{ $vin->vin_id }}" name="checked_vins[]" id="check-vin-{{ $vin->vin_id }}"></td>

                                    <td id="vin-codigo-{{ $vin->vin_id }}"><small>{{ $vin->vin_codigo }}</small></td>
                                    <td><small>{{ $vin->vin_patente }}</small></td>
                                    <td><small>{{ $vin->vin_marca }}</small></td>
                                    <td><small>{{ $vin->vin_modelo }}</small></td>
                                    <td><small>{{ $vin->vin_color }}</small></td>
                                    <td><small>{{ $vin->vin_segmento }}</small></td>
                                    <td><small>{{ $vin->vin_fec_ingreso }}</small></td>
                                    <td><small>{{ $vin->empresa_razon_social }}</small></td>
                                    <td><small>{{ $vin->vin_estado_inventario_desc }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{route('vin.downloadGuia', Crypt::encrypt($vin->vin_id)) }}">
                                                Guia
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

                                        @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3)
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



@include('vin.partials.modal_asignar_tarea_lotes')
@include('vin.partials.modal_cambia_estado')

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

        $('.btn-edo-vins').click(function (e){
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

                $("#vin_codigo_edo_lote").html("<h6>VIN: " + arr_codigos[0] + "</h6>");
                $("#vin_codigo_edo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[0] +  "' name='vin_ids[" + vin_ids[0] + "]'  value='" + vin_ids[0] + "'/>");

                for (var i = 1; i < arr_codigos.length; i++){
                    $("#vin_codigo_edo_lote").append("<h6>VIN: " + arr_codigos[i] + "</h6>");
                    $("#vin_codigo_edo_lote").append("<input type='hidden' class='vin-id-" + vin_ids[i] +  "' name='vin_ids[" + vin_ids[i] + "]' value='" + vin_ids[i] + "'/>");
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
                    alert('Error: Debe seleccionar al menos un vin de la lista');
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
