@extends('layouts.app')
@section('title','Registro de VIN en el Sistema')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Registrar Vin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-4">
                                {!! Form::open(['route'=> 'vin.store', 'method'=>'POST']) !!}

                            <div class="form-group">
                                    <label for="vin_codigo" >Código VIN <strong>*</strong></label>
                                    {!! Form::text('vin_codigo', null, ['placeholder'=>'Código', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_patente" >Patente <strong>*</strong></label>
                                {!! Form::text('vin_patente', null, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_marca" >Marca <strong>*</strong></label>
                                    {!! Form::text('vin_marca', null, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_modelo" >Modelo <strong>*</strong></label>
                                    {!! Form::text('vin_modelo', null, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="vin_color" >Color <strong>*</strong></label>
                                    {!! Form::text('vin_color', null, ['placeholder'=>'Color', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="vin_motor" >Motor <strong>*</strong></label>
                                {!! Form::text('vin_motor', null, ['placeholder'=>'Motor', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_segmento" >Segmento <strong>*</strong></label>
                                    {!! Form::text('vin_segmento', null, ['placeholder'=>'Segmento', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="vin_fec_ingreso" >Fecha ingreso <strong>*</strong></label>
                                    {!! Form::date('vin_fec_ingreso', null, ['placeholder'=>'Fecha de Ingreso', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="empresa_id" >Empresa <strong>*</strong></label>
                                {{-- {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Seleccionar Empresa', 'class'=>'form-control col-sm-9 select-empresa', 'required'=>'required']) !!} --}}
                                <select name="empresa_id" id="empresa" class="form-control select-empresa">
                                    <option value="" selected>Seleccione la Empresa</option>
                                @foreach($empresas as $k => $v)
                                    <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                                {!! Form::select('user_id', ['placeholder' => 'Seleccione el Cliente'], null, ['id' => 'user_id', 'class' => 'form-control']) !!}
                                {{-- {!! Form::select('user_id', $users, null,['id' => 'cliente', 'placeholder'=>'Seleccionar Cliente', 'class'=>'form-control col-sm-9 select-cliente', 'required'=>'required']) !!} --}}
                            </div>

                            <div class="form-group">
                                <label for="vin_estado_inventario_id" >Estado de Inventario <strong>*</strong></label>
                                {{-- {!! Form::select('vin_estado_inventario_id', $estadosInventario, null,['class'=>'form-control col-sm-9']) !!} --}}
                                <select name="vin_estado_inventario_id" id="estado-inventario" class="form-control select-estado-inventario">
                                    <option value="" selected>Seleccione Estado de Inventario</option>
                                @foreach($estadosInventario as $k => $v)
                                    <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="vin_sub_estado_inventario_id" >Sub-Estado de Inventario <strong>*</strong></label>
                                {!! Form::select('vin_sub_estado_inventario_id', ['placeholder' => 'Seleccione Sub Estado de Inventario'], null,['id' => 'vin_sub_estado_inventario_id', 'class'=>'form-control']) !!}
                                {{-- {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, null,['class'=>'form-control col-sm-9']) !!} --}}
                            </div>

                        </div>

                    </div>
                    <div class="text-right pb-5">
                            {!! Form::submit('Registrar vin ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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

<!--Funcion para ocultar y mostrar input segun seleccion-->
<script language="javascript" type="text/javascript">
    function d1(selectTag){
    if(selectTag.value == '0')
    {
        $('#bloque_archivo').hide();
        document.getElementById('archivo').disabled = true;
    }else if(selectTag.value == '1')
    {
        $('#bloque_archivo').show();

     document.getElementById('archivo').disabled = false;
    }else if(selectTag.value == '2')
    {
        $('#bloque_archivo').hide();
        document.getElementById('archivo').disabled = true;
    }
    }
    </script>
<!--Fin Funcion para ocultar y mostrar input segun seleccion-->



