@extends('layouts.app')
@section('title','Vin index')
@section('custom_styles')
<link href="{{asset('css/switch_button.css')}}" type="text/css" rel="stylesheet">
@endsection
@section('content')
@include('flash::message')

     <!--SUPER ADMINISTRADOR y ADMINISTRADOR -->
    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2)
        <div class="row">
            <div class="col-lg-3">
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
            <div class="col-lg-3">
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
                                        {{ Form::button('<i class="fa fa-car"></i> Vehiculos N/N', ['type' => 'submit', 'class' => 'btn btn-primary btn-vehiculo-n-n block full-width m-b'] )  }}
                                    </div>
                                    {!! Form::close() !!}
                                    <div class="text  pb-3">
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="card card-default text-center">
                        <div class="card-header">
                            <h3 class="card-title">Exportar Tabla</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text pb-3">
                                        <button type="button" class="btn btn-info btn-busqueda-vin-lote btn-rol13" style="display:none">Exportar Lista de VINs</button>
                                        {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_busqueda_vins_form']) !!}
                                        {{ Form::button('<i class="fa fa-file-excel"></i>Listado de VINs ', ['id' => 'btn-listado-vins', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-listado-vins', 'disabled'] )  }}
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="text  pb-3">
                                    Selecciona los VINs y haz click para exportar tu búsqueda
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="card card-default text-center">
                        <div class="card-header">
                            <h3 class="card-title">Histórico VINs</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text pb-3">
                                        <button type="button" class="btn btn-info btn-historico-vin-lote btn-rol13" style="display:none">Exportar Histórico por lotes</button>

                                        {!! Form::open(['route'=> 'historico_vin.exportHistoricoVinLote', 'method'=>'post', 'id' => 'historico_lote_form']) !!}
                                        {{ Form::button('<i class="fa fa-file-excel"></i> Descargar Histórico ', ['id' => 'btn-descargar-historico', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="text  pb-3">
                                    Selecciona los VINs y haz click para exportar histórico por lotes
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
                                        {{ Form::button('<i class="fa fa-car"></i> Vehiculos N/N', ['type' => 'submit', 'class' => 'btn btn-primary btn-vehiculo-n-n block full-width m-b'] )  }}
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
                                    <div class="text pb-3">
                                        <button type="button" class="btn btn-info btn-busqueda-vin-lote btn-rol13" style="display:none">Exportar Lista de VINs</button>
                                        <hr />
                                        <br />
                                        {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_busqueda_vins_form']) !!}
                                        {{ Form::button('<i class="fa fa-file-excel"></i> Descargar Listado de VINs ', ['id' => 'btn-listado-vins', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-listado-vins', 'disabled'])  }}
                                    </div>
                                    {!! Form::close() !!}
                                    <div class="text  pb-3">
                                    Selecciona los VINs y haz click para exportar tu búsqueda
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
                            <h3 class="card-title">Histórico VINs</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text pb-3">
                                        <button type="button" class="btn btn-info btn-historico-vin-lote btn-rol13" style="display:none">Exportar Histórico por lotes</button>
                                        <hr />
                                        <br />
                                        {!! Form::open(['route'=> 'historico_vin.exportHistoricoVinLote', 'method'=>'post', 'id' => 'historico_lote_form']) !!}
                                        {{ Form::button('<i class="fa fa-file-excel"></i> Descargar Histórico ', ['id' => 'btn-descargar-historico', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="text  pb-3">
                                    Selecciona los VINs y haz click para exportar histórico por lotes
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
                            <h3 class="card-title">Exportar Tabla</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    {!! Form::open(['route'=> 'vin.exportResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_busqueda_vins_form']) !!}
                                    <div class="text pb-3">
                                        <button type="button" class="btn btn-info btn-busqueda-vin-lote btn-rol13" style="display:none">Exportar Lista de VINs</button>
                                        <hr />
                                        <br />
                                        {{ Form::button('<i class="fa fa-file-excel"></i> Descargar Listado de VINs ', ['id' => 'btn-listado-vins', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b btn-listado-vins', 'disabled'] )  }}
                                    </div>
                                    {!! Form::close() !!}
                                    <div class="text  pb-3">
                                    Selecciona los VINs y haz click para exportar tu búsqueda
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
                            <h3 class="card-title">Histórico VINs</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text pb-3">
                                        <button type="button" class="btn btn-info btn-historico-vin-lote btn-rol13" style="display:none">Exportar Histórico por lotes</button>
                                        <hr />
                                        <br />
                                        {!! Form::open(['route'=> 'historico_vin.exportHistoricoVinLote', 'method'=>'post', 'id' => 'historico_lote_form']) !!}
                                        {{ Form::button('<i class="fa fa-file-excel"></i> Descargar Histórico ', ['id' => 'btn-descargar-historico', 'type' => 'submit', 'class' => 'btn btn-info block full-width m-b', 'disabled'] )  }}
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="text  pb-3">
                                    Selecciona los VINs y haz click para exportar histórico por lotes
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
                                        <label for="marca_nombre" >Seleccionar Marca <strong>*</strong></label>
                                        {!! Form::select('marca_id', $marcas, null,['id' => 'marca', 'placeholder'=>'Marca', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="text-right pb-5" id="botones">

                                    <button type="button" class="btn btn-danger btn-predespacho-vins btn-rol12" style="display:none">Asignar para entrega</button>

                                    <button type="button" class="btn btn-success btn-lote-vins btn-rol" style="display:none">Carga de guías por lotes</button>

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



    @if(Auth::user()->rol_id == 4)
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins text-center">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Buscar Vin</h3>
                        </div>
                        <div class="card-body">
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
                                        <label for="marca_nombre" >Seleccionar Marca <strong>*</strong></label>
                                        {!! Form::select('marca_id', $marcas, null,['id' => 'marca', 'placeholder'=>'Marca', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="text-right pb-5">
                                    <button type="button" class="btn btn-danger btn-predespacho-vins btn-rol4" style="display:none">Asignar para entrega</button>

                                    <button type="button" style="display:none" class="btn btn-success btn-lote-vins btn-rol">Carga de guías por lotes</button>

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
                                    <!-- <th><input type="checkbox" class="check-all" />Seleccionar Todos</th> -->
                                    <th>
                                        {!! Form::open(['route'=> 'vin.exportMasivoResultadoBusquedaVins', 'method'=>'POST', 'id' => 'resultado_masivo_busqueda_vins_form']) !!}
                                            Descargar Todos <br/>
                                            <input type="hidden" name="vin_ids" value="" id="resultado_busqueda_vins" />
                                            {{ Form::button('<i class="fa fa-file-excel"></i> Excel', ['id' => 'btn-listado-masivo', 'type' => 'submit', 'class' => 'btn btn-sm btn-success block full-width m-b btn-listado-masivo-vins', 'disabled'] )  }}
                                        {!! Form::close() !!}
                                    </th>
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
                                <tbody>

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


    @include('vin.partials.modal_asignar_tarea_lotes')
    @include('vin.partials.modal_cambia_estado')
    @include('vin.partials.modal_historico_vin')
    @include('vin.partials.modal_predespacho')
    @include('vin.partials.modal_vehiculo_n_n')

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
                    var arr_vins = $.map(res.vins, function (e1) {
                        return e1;
                    });

                    var arr_vin_ids = $.map(res.vin_ids, function (e1) {
                        return e1;
                    });

                    for (var i = 0; i < arr_vins.length; i++){
                        $("#vin_id_nn").append("<option value=" + arr_vin_ids[i] + ">" + arr_vins[i] + "</option>");
                    }
                    
                    $("#vehiculoN_NModal").modal('show');
                }).fail(function () {
                    alert('Error: ');
                });

            });

            $('#vin_id_nn').on('change', function(e) {
                e.preventDefault();

                var sel = $(this).val();

                if($.isNumeric(sel)) {
                    
                    var url = "vehiculo_nn/" + sel +"/data_vin_nn";                                    

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

                        var arr_fotos = $.map(res.fotos, function (e1) {
                            return e1;
                        }); 

                        if(arr_fotos.length > 0){
                            $('#fotos_nn').append('<h4 id="titulo_fotos_nn">Fotos Pre-inspección</h4>'); 
                            $('#fotos_nn').append('<h5 id="nota_fotos_nn">Serán añadidas al realizar la inspección del VIN</h5>'); 
                            $('#fotos_nn').append('<table class="table table-borderless table-hover" id="thumbnail_nn"></table>'); 
                        }

                        for (var i = 0; i < arr_fotos.length; i++){
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
                            } else if (value.rol_id == 4){
                                $(".btn-rol4").show();
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
    <script type="text/javascript">
        function activatePlacesSearch () {
            var input_origen = document.getElementById('search_term_ruta');
            var autocomplete = new google.maps.places.Autocomplete(input_origen);
            var input_destino = document.getElementById('search_term_ruta_2');
            var autocomplete2 = new google.maps.places.Autocomplete(input_destino);
        };
    </script>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=activatePlacesSearch"></script>
@endsection
