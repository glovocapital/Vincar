@extends('layouts.app')
@section('title','Vin index')
@section('content')


<div class="col-lg-4">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Cargar Vehiculos </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                    <div class="text pb-5">
                        {!! Form::submit('Registrar vin ', ['class' => 'btn btn-success block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Vehiculos N/N  </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                    <div class="text pb-5">
                        {!! Form::submit('Vehiculos N/N ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Exportar Tabla</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route'=> 'vin.cargamasiva', 'method'=>'GET']) !!}
                    <div class="text pb-5">

                        {!! Form::submit('Exportar VIN ', ['class' => 'btn btn-info block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
                    {!! Form::open(['route'=> 'vin.index', 'method'=>'get']) !!}
                    <div class="row">
                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                    <label for="vin_numero" >Vin <strong>*</strong></label>
                                    {!! Form::textarea('vin_numero[]', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'cols' => 40, 'style' => 'resize:none']) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                    <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                                    {!! Form::select('user_id', $users, null,['id' => 'cliente', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente']) !!}
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
                                {!! Form::select('marca_id', $marcas, null,['id' => 'estadoinventario', 'placeholder'=>'Marca', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="text-right pb-5">
                        {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}
                        {!! Form::close() !!}
                    </div>
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
                        <h3 class="card-title">Listado de Vins</h3>
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
                                        <th>Vin</th>
                                        <th>Patente</th>
                                        <th>Modelo</th>
                                        <th>Marca</th>
                                        <th>Color</th>
                                        <th>Motor</th>
                                        <th>Segmento</th>
                                        <th>Fecha de Ingreso</th>
                                        <th>Cliente</th>
                                        <th>Estado</th>
                                    <!--  <th>Sub Estado Inventario </th>  -->
                                        <th>Acci&oacute;n</th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($tabla_vins as $vin)
                                <tr>
                                    <td><small>{{ $vin->vin_codigo }}</small></td>
                                    <td><small>{{ $vin->vin_patente }}</small></td>
                                    <td><small>{{ $vin->vin_modelo }}</small></td>
                                    <td><small>{{ $vin->vin_marca }}</small></td>
                                    <td><small>{{ $vin->vin_color }}</small></td>
                                    <td><small>{{ $vin->vin_motor }}</small></td>
                                    <td><small>{{ $vin->vin_segmento }}</small></td>
                                    <td><small>{{ $vin->vin_fec_ingreso }}</small></td>
                                    <td><small>{{ $vin->user_nombre }} {{ $vin->user_apellido }}</small></td>
                                    <td><small>{{ $vin->vin_estado_inventario_desc }}</small></td>

                                    <td>
                                        <small>
                                            <a href="{{ route('vin.editarestado', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Cambiar Estado"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href="{{ route('vin.editarestado', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Cambiar Estado"><i class="far fa-clock"></i></a>
                                        </small>
                                       <!-- <small>
                                            <a href = "{{ route('vin.destroy', Crypt::encrypt($vin->vin_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-vin"><i class="far fa-trash-alt"></i>
                                        </a>
                                        </small> -->

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






@stop
@section('local-scripts')


<script>
        $(document).ready(function () {

            $(".select-empresa").change(function (e) {

                e.preventDefault();

                var id = $(this).val();

                if (id != ''){

                    var url = "/vin/obtener_usuarios_empresa/";

                    $.get(url + id, id, function (res) {
                        //Validar primero si algo salió mal
                        if(!res.success){
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

                        var arr_users = $.map(res.users, function (e1) {
                            return e1;
                        });

                        $("#user_id").html("<option value=''>Seleccione el Cliente</option>");
                        for (var i = 0; i < arr_ids.length; i++){
                            $("#user_id").append("<option value='" + arr_ids[i] + "'>" + arr_users[i] + "</option>");

                        }


                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                }else{
                    $("#user_id").html("<option value=''>Seleccione el Cliente</option>");
                }



                /**/

            });

            $(".select-estado-inventario").change(function (e) {

                e.preventDefault();

                if($(".select-estado-inventario option:selected").text() == "Disponible para la venta" || $(".select-estado-inventario option:selected").text() == "No disponible para la venta")
                {
                    //alert($(".select-estado-inventario option:selected").text());

                    $('#bloque_sub_estado').show();
                }else
                {
                    $('#bloque_sub_estado').hide();
                }

                var id = $(this).val();

                if (id != ''){

                    var url = "/vin/obtener_sub_estados/";

                    $.get(url + id, id, function (res) {
                        //Validar primero si algo salió mal
                        if(!res.success){
                            alert(
                                "Error inesperado al solicitar la información.\n\n" +
                                "MENSAJE DEL SISTEMA:\n" +
                                res.message + "\n\n"
                            );
                            return;  // Finaliza el intento de obtener
                        }

                        if(res.ids != null && res.subEstados != null ){
                            //alert(JSON.stringify(res));
                            var arr_ids = $.map(res.ids, function (e1) {
                                return e1;
                            });

                            var arr_subEstados = $.map(res.subEstados, function (e1) {
                                return e1;
                            });

                            $("#vin_sub_estado_inventario_id").html("<option value=''>Seleccione Sub Estado de Inventario</option>");
                            for (var i = 0; i < arr_ids.length; i++){
                                $("#vin_sub_estado_inventario_id").append("<option value='" + arr_ids[i] + "'>" + arr_subEstados[i] + "</option>");

                            }
                        } else {
                            $("#vin_sub_estado_inventario_id").html("<option value=''>Seleccione Sub Estado de Inventario</option>");
                        }
                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                }else{
                    $("#vin_sub_estado_inventario_id").html("<option value=''>Seleccione Sub Estado de Inventario</option>");
                }
                /**/

            });
        });
    </script>
@endsection


