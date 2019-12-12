@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')

<!-- Registrar Daño adicional detectado en la Inspección -->
<div class="col-lg-12" id="bloque_dano">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Registrar Daño Adicional</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4" style="border-right-style: solid; border-right-color: grey; border-right-width: 1px;">
                        <div class="form-group">
                            <label for="">Descripción de Pieza</label>
                        </div>
                        <div class="form-group">
                            <label for="pieza_categoria_id" >Categoría de Pieza <strong>*</strong></label>
                            <select name="pieza_categoria_id" id="pieza-categoria" class="form-control select-categoria">
                                <option value="">Seleccione la Categoría</option>
                            @foreach($piezaCategorias as $k => $v)
                                <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pieza_subcategoria_id" >Sub-Categoría <strong>*</strong></label>
                            {!! Form::select(null, ['placeholder' => 'Seleccionar Sub-Categoría'], null,['id'=>'pieza-subcategoria', 'class'=>'form-control select-subcategoria']) !!}
                        </div>
                        <div class="form-group">
                                <label for="pieza_id" >Pieza <strong>*</strong></label>
                                {!! Form::select('dano_pieza[pieza_id]', $piezas, $dano->pieza_id, ['id' => 'pieza_id', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-4" style="border-right-style: solid; border-right-color: grey; border-right-width: 1px;">
                        <div class="form-group">
                            <label for="">Tipo, gravedad y ubicación del daño</label>
                        </div>

                        <div class="form-group">
                            <label for="tipo_dano_id" >Tipo de daño <strong>*</strong></label>
                            {!! Form::select('dano_pieza[tipo_dano_id]', $tipoDanos, $dano->tipo_dano_id,['placeholder'=>'Seleccione Tipo de Daño', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="gravedad_id" >Gravedad <strong>*</strong></label>
                            {!! Form::select('dano_pieza[gravedad_id]', $gravedades, $dano->gravedad_id,['placeholder'=>'Seleccione Gravedad del Daño', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="pieza_sub_area_id" >Sub Área Afectada <strong>*</strong></label>
                            {!! Form::select('dano_pieza[pieza_sub_area_id]', $subAreas, $dano->pieza_sub_area_id,['placeholder'=>'Seleccionar Sub Área', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dano_pieza_observaciones" >Observaciones del daño <strong>*</strong></label>
                            {!! Form::textarea('dano_pieza[dano_pieza_observaciones]', $dano->dano_pieza_observaciones, ['placeholder'=>'Colocar observaciones', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    
                </div>
                <div class="text-right pb-5" id="boton_inspeccion_dano">
                    {!! Form::button('Anexar Foto', ['id'=>'btn-fotos', 'class' => 'btn btn-info block full-width m-b']) !!}
                    <button name="submit_2" value="2" type="submit" class="btn btn-primary block full-width m-b">Registrar Daño</button>
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

@foreach($fotos as $foto)
<!-- Anexar Fotografía del Daño -->
<div class="col-lg-12" id="bloque_fotos">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Anexar Fotografía</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4" style="border-right-style: solid; border-right-color: grey; border-right-width: 1px;">
                        <div class="form-group">
                            <label for="">Datos de la foto</label>
                        </div>

                        <div class="form-group">
                            <label for="foto[foto_fecha]">Fecha</label>
                            {!! Form::date('foto[foto_fecha]', substr($foto->foto_fecha, 0, 10), ['placeholder'=>'Fecha de la foto', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="foto[foto_descripcion]">Descripción</label>
                            {!! Form::text('foto[foto_descripcion]', $foto->foto_descripcion, ['placeholder'=>'Descripción Foto', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Coordenadas</label>
                        </div>

                        <div class="form-group">
                            <label for="">Latitud</label>
                            {!! Form::text('foto[foto_coord_lat]', $foto->foto_coord_lat, ['placeholder'=>'Latitud', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="">Longitud</label>
                            {!! Form::text('foto[foto_coord_lon]', $foto->foto_coord_lon, ['placeholder'=>'Longitud', 'class'=>'form-control col-sm-9']) !!}
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
                    <button name="submit_3" value="3" type="submit" class="btn btn-primary block full-width m-b">Registrar Daño</button>
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
@endforeach

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

