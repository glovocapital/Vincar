@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Modificar Vin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-4">
                                {!! Form::open(['route'=> ['vin.update', Crypt::encrypt($vin->vin_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                    <label for="vin_codigo" >Código VIN <strong>*</strong></label>
                                    {!! Form::text('vin_codigo', $vin->vin_codigo, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_patente" >Patente <strong>*</strong></label>
                                {!! Form::text('vin_patente', $vin->vin_patente, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_marca" >Marca <strong>*</strong></label>
                                    {!! Form::text('vin_marca', $vin->vin_marca, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_modelo" >Modelo <strong>*</strong></label>
                                    {!! Form::text('vin_modelo', $vin->vin_modelo, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="vin_color" >Color <strong>*</strong></label>
                                    {!! Form::text('vin_color', $vin->vin_color, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_motor" >Motor <strong>*</strong></label>
                                {!! Form::text('vin_motor', $vin->vin_motor, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_segmento" >Segmento <strong>*</strong></label>
                                    {!! Form::text('vin_segmento', $vin->vin_segmento, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_fec_ingreso" >Fecha ingreso <strong>*</strong></label>
                                    {!! Form::text('vin_fec_ingreso', $vin->vin_fec_ingreso, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="empresa_id" >Empresa <strong>*</strong></label>
                                {{-- {!! Form::select('empresa_id', $empresas, $user->belongsToEmpresa->empresa_id, ['class'=>'form-control col-sm-9', 'required'=>'required']) !!} --}}
                                <select name="empresa_id" id="empresa" class="form-control select-empresa">
                                    <option value="">Seleccionar Empresa</option>
                                @foreach($empresas as $k => $v)
                                    @if($k == $user->belongsToEmpresa->empresa_id)
                                        <option value="{!! Crypt::encrypt($user->belongsToEmpresa->empresa_id) !!}" selected>{{$user->belongsToEmpresa->empresa_razon_social}}</option>
                                    @else
                                        <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                                {!! Form::select('user_id', $users, $vin->user_id,['id' => 'user_id', 'class'=>'form-control', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_estado_inventario_id" >Estado de Inventario <strong>*</strong></label>
                                    {{-- {!! Form::select('vin_estado_inventario_id', $estadosInventario, $vin->vin_estado_inventario_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!} --}}
                                    <select name="vin_estado_inventario_id" id="estado-inventario" class="form-control select-estado-inventario">
                                        <option value="">Seleccione Estado de Inventario</option>
                                    @foreach($estadosInventario as $k => $v)
                                        @if($k == $vin->vin_estado_inventario_id)
                                            <option value="{!! Crypt::encrypt($vin->vin_estado_inventario_id) !!}" selected>{{$estadosInventario[$vin->vin_estado_inventario_id]}}</option>
                                        @else
                                            <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="vin_sub_estado_inventario_id" >Sub-Estado de Inventario <strong>*</strong></label>
                                {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, $vin->vin_sub_estado_inventario_id,['id' => 'vin_sub_estado_inventario_id', 'class'=>'form-control']) !!}
                            </div>
                        </div>

                    </div>
                    <div class="text-right pb-5">
                            {!! Form::submit('Actualizar VIN', ['class' => 'btn btn-primary block full-width m-b']) !!}
                            {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                            <p><strong>*</strong> Campos obligatorios</p>
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

