@extends('layouts.app')
@section('title','Registrar Inspección')
@section('content')
<!-- Registrar datos básicos de la Inspección -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Registrar Inspección</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> 'inspeccion.store', 'method'=>'POST', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="inspeccion_fecha" >Fecha de Inspección <strong>*</strong></label>
                            {!! Form::date('inspeccion[inspeccion_fecha]', now('America/Santiago'), ['placeholder'=>'Fecha de Ingreso', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="responsable_id" >Responsable de la Inspección <strong>*</strong></label>
                                {!! Form::text(null, $responsable_nombres, ['class'=>'form-control col-sm-9', 'disabled']) !!}
                                {!! Form::hidden('inspeccion[responsable_id]', $responsable->user_id) !!}
                        </div>

                        <div class="form-group">
                                <label for="vin_id" >Código VIN <strong>*</strong></label>
                                {!! Form::select('inspeccion[vin_id]', $vins, null, ['placeholder'=>'Seleccione el Código', 'class'=>'form-control col-sm-9', 'required']) !!}
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
                            <label for="cliente_id" >Seleccionar Cliente <strong>*</strong></label>
                            {!! Form::select('inspeccion[cliente_id]', ['placeholder' => 'Seleccione el Cliente'], null, ['id' => 'cliente_id', 'class' => 'form-control']) !!}
                            {{-- {!! Form::select('user_id', $users, null,['id' => 'cliente', 'placeholder'=>'Seleccionar Cliente', 'class'=>'form-control col-sm-9 select-cliente', 'required'=>'required']) !!} --}}
                        </div>

                        <div class="form-group">
                            <label for="inspeccion_dano" >¿Hay daño qué reportar?<strong>*</strong></label>
                            <br />
                            <label>Sí</label>
                            <input type="radio" name="inspeccion[inspeccion_dano]" id="si_hay_dano" onchange="d1(this)" value="true" />
                            <span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>
                            <label>No</label>
                            <input type="radio" name="inspeccion[inspeccion_dano]" id="no_hay_dano" onchange="d1(this)" value="false" checked />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vin_estado_inventario_id" >Estado de Inventario <strong>*</strong></label>
                            {{-- {!! Form::select('vin_estado_inventario_id', $estadosInventario, null,['class'=>'form-control col-sm-9']) !!} --}}
                            <select name="inspeccion[vin_estado_inventario_id]" id="estado-inventario" class="form-control select-estado-inventario">
                                <option value="" selected>Seleccione Estado de Inventario</option>
                            @foreach($estadosInventario as $k => $v)
                                <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="bloque_sub_estado" style="display: none">
                            <label for="vin_sub_estado_inventario_id" >Sub-Estado de Inventario <strong>*</strong></label>
                            {!! Form::select('inspeccion[vin_sub_estado_inventario_id]', ['placeholder' => 'Seleccione Sub Estado de Inventario'], null,['id' => 'vin_sub_estado_inventario_id', 'class'=>'form-control']) !!}
                            {{-- {!! Form::select('vin_sub_estado_inventario_id', $subEstadosInventario, null,['class'=>'form-control col-sm-9']) !!} --}}
                        </div>
                    </div>
                </div>
                <div class="text-right pb-5" id="boton_inspeccion">
                    <button name="submit_1" value="1" type="submit" class="btn btn-primary block full-width m-b">Registrar Inspección</button>
                    {{-- {!! Form::submit('Registrar Inspección', ['name'=>'submit_1', 'value'=>'1', 'class' => 'btn btn-primary block full-width m-b']) !!} --}}
                    {{-- {!! Form::close() !!} --}}
                </div>

                <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registrar el Daño detectado en la Inspección -->
<div class="col-lg-12" id="bloque_dano" style="display: none">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Registrar Daño</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-md-4" style="border-right-style: solid; border-right-color: grey; border-right-width: 1px;">
                        <div class="form-group">
                            <label for="">Descripción de Pieza</label>
                        </div>
                        <div class="form-group">
                            <label for="pieza_categoria_id" >Categoría de Pieza <strong>*</strong></label>
                            {{-- {!! Form::select('pieza_categoria_id', $piezaCategorias, null,['placeholder'=>'Seleccionar Categoría', 'class'=>'form-control col-sm-9 select-empresa']) !!} --}}
                            <select name="pieza_categoria_id" id="pieza-categoria" class="form-control select-categoria">
                                <option value="" selected>Seleccione la Categoría</option>
                            @foreach($piezaCategorias as $k => $v)
                                <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pieza_subcategoria_id" >Sub-Categoría <strong>*</strong></label>
                            {!! Form::select('pieza_subcategoria_id', ['placeholder' => 'Seleccionar Sub-Categoría'], null,['id'=>'pieza-subcategoria', 'class'=>'form-control select-subcategoria']) !!}
                            <!-- <select name="pieza_subcategoria_id" id="pieza-subcategoria" class="form-control select-subcategoria">
                                <option value="" selected>Seleccionar Sub-Categoría</option>
                            @foreach($piezaSubCategorias as $k => $v)
                                <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                            @endforeach
                            </select> -->
                        </div>
                        <div class="form-group">
                                <label for="pieza_id" >Pieza <strong>*</strong></label>
                                {!! Form::select('dano_pieza[pieza_id]', ['placeholder' => 'Seleccione la pieza'], null, ['id' => 'pieza_id', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-4" style="border-right-style: solid; border-right-color: grey; border-right-width: 1px;">
                        <div class="form-group">
                            <label for="">Tipo, gravedad y ubicación del daño</label>
                        </div>

                        <div class="form-group">
                            <label for="tipo_dano_id" >Tipo de daño <strong>*</strong></label>
                            {!! Form::select('dano_pieza[tipo_dano_id]', $tipoDanos, null,['placeholder'=>'Seleccione Tipo de Daño', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="gravedad_id" >Gravedad <strong>*</strong></label>
                            {!! Form::select('dano_pieza[gravedad_id]', $gravedades, null,['placeholder'=>'Seleccione Gravedad del Daño', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="pieza_sub_area_id" >Sub Área Afectada <strong>*</strong></label>
                            {!! Form::select('dano_pieza[pieza_sub_area_id]', $subAreas, null,['placeholder'=>'Seleccionar Sub Área', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dano_pieza_observaciones" >Observaciones del daño <strong>*</strong></label>
                            {!! Form::textarea('dano_pieza[dano_pieza_observaciones]', null, ['placeholder'=>'Colocar observaciones', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>


                </div>
                <div class="text-right pb-5" id="boton_inspeccion_dano">
                    {!! Form::button('Anexar Foto', ['id'=>'btn-fotos', 'class' => 'btn btn-info block full-width m-b']) !!}
                    <button name="submit_2" value="2" type="submit" class="btn btn-primary block full-width m-b">Registrar Inspección</button>
                    {{-- {!! Form::submit('Registrar Inspección', ['name'=>'submit_2', 'value'=>'2', 'class' => 'btn btn-primary block full-width m-b']) !!} --}}
                    {{-- {!! Form::close() !!} --}}
                </div>

                <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Anexar Fotografía a la Inspección -->
<div class="col-lg-12" id="bloque_fotos" style="display: none">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Anexar Fotografía</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-md-4" style="border-right-style: solid; border-right-color: grey; border-right-width: 1px;">
                        <div class="form-group">
                            <label for="">Datos de la foto</label>
                        </div>

                        <div class="form-group">
                            <label for="">Fecha</label>
                            {!! Form::date('foto[foto_fecha]', now('America/Santiago'), ['placeholder'=>'Fecha de la foto', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="">Descripción</label>
                            {!! Form::text('foto[foto_descripcion]', null, ['placeholder'=>'Descripción Foto', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Coordenadas</label>
                        </div>

                        <div class="form-group">
                            <label for="">Latitud</label>
                            {!! Form::text('foto[foto_coord_lat]', null, ['placeholder'=>'Latitud', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="">Longitud</label>
                            {!! Form::text('foto[foto_coord_lon]', null, ['placeholder'=>'Longitud', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Foto</label>
                        </div>

                        <div class="form-group">
                            <label for="">Subir Foto</label>
                            {!! Form::file('foto_nombre_archivo'); !!}
                        </div>
                    </div>
                </div>

                <div class="text-right pb-5" id="boton_inspeccion_dano_fotos">
                    <button name="submit_3" value="3" type="submit" class="btn btn-primary block full-width m-b">Registrar Inspección</button>
                    {{-- {!! Form::submit('Registrar Inspección', ['name'=>'submit_3', 'value'=>'3', 'class' => 'btn btn-primary block full-width m-b']) !!} --}}
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
        function d1(button){
            if(button.value == 'false')
            {
                $('#bloque_dano').hide();
                $('#bloque_fotos').hide();
                $('#boton_inspeccion').show();
                $('#boton_inspeccion_dano').hide();
                $('#btn-fotos').hide();
                $('#boton_inspeccion_dano_fotos').hide();
            }else if(button.value == 'true')
            {
                $('#bloque_dano').show();
                $('#boton_inspeccion').hide();
                $('#boton_inspeccion_dano').show();
                $('#btn-fotos').show();
                $('#boton_inspeccion_dano_fotos').show();
            }
        }
        $(document).ready(function () {
            // Evento de presionar el botón de añadir fotos
            $("#btn-fotos").click(function(e){
                e.preventDefault();

                $(this).hide();
                $('#boton_inspeccion_dano').hide();
                $('#boton_inspeccion_dano_fotos').show();
                $('#bloque_fotos').show();
            });

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

                        $("#cliente_id").html("<option value=''>Seleccione el Cliente</option>");
                        for (var i = 0; i < arr_ids.length; i++){
                            $("#cliente_id").append("<option value='" + arr_ids[i] + "'>" + arr_users[i] + "</option>");
                        }

                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                }else{
                    $("#cliente_id").html("<option value=''>Seleccione el Cliente</option>");
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

            $(".select-categoria").change(function (e) {

                e.preventDefault();

                var id = $(this).val();

                if (id != ''){

                    var url = "/inspeccion/obtener_subcategorias_pieza/";

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

                        var arr_subcategorias = $.map(res.subcategorias, function (e1) {
                            return e1;
                        });

                        $("#pieza-subcategoria").html("<option value=''>Seleccionar Sub-Categoría</option>");
                        for (var i = 0; i < arr_ids.length; i++){
                            $("#pieza-subcategoria").append("<option value='" + arr_ids[i] + "'>" + arr_subcategorias[i] + "</option>");
                        }

                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                }else{
                    $("#pieza-subcategoria").html("<option value=''>Seleccionar Sub-Categoría</option>");
                }



                /**/

            });


            $(".select-subcategoria").change(function (e) {

                e.preventDefault();

                var id = $(this).val();

                if (id != ''){

                    var url = "/inspeccion/obtener_piezas/";

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

                        var arr_piezas = $.map(res.piezas, function (e1) {
                            return e1;
                        });

                        $("#pieza_id").html("<option value=''>Seleccione la pieza</option>");
                        for (var i = 0; i < arr_ids.length; i++){
                            $("#pieza_id").append("<option value='" + arr_ids[i] + "'>" + arr_piezas[i] + "</option>");
                        }

                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                }else{
                    $("#pieza_id").html("<option value=''>Seleccione la pieza</option>");
                }



                /**/

                });
        });
    </script>
@endsection
