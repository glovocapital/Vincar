@extends('layouts.app')
@section('title','Registrar Inspección')
@section('content')
<!-- Registrar datos básicos de la Inspección -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Añadir Daño a Inspección Nro. {{$inspeccion->inspeccion_id}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> 'inspeccion.store_dano', 'method'=>'POST', 'files' => true]) !!}
                        {!! Form::hidden('inspeccion_id', $inspeccion->inspeccion_id) !!}
                        <div class="form-group">
                            <label for="inspeccion_fecha" >Fecha de Inspección</label>
                            {!! Form::date(null, $inspeccion->inspeccion_fecha, ['class'=>'form-control col-sm-9', 'disabled']) !!}
                        </div>

                        <div class="form-group">
                            <label for="responsable_id" >Responsable de la Inspección</label>
                            {!! Form::text(null, $responsable_nombres, ['class'=>'form-control col-sm-9', 'disabled']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                    <div class="form-group">
                            <label for="vin_id" >Código VIN</label>
                            {!! Form::text(null, $vin->vin_codigo, ['class'=>'form-control col-sm-9', 'disabled']) !!}
                        </div>

                        <div class="form-group">
                            <label for="cliente_id" >Cliente <strong>*</strong></label>
                            {!! Form::text(null, $cliente_nombres, ['id' => 'cliente_id', 'class' => 'form-control col-sm-9', 'disabled']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">                            
                        <div class="form-group">
                            <label for="vin_estado_inventario_id" >Estado de Inventario <strong>*</strong></label>
                            {!! Form::text(null, $inspeccion->oneVinEstadoInventario(), ['id' => 'vin_estado_inventario_id', 'class' => 'form-control col-sm-9', 'disabled']) !!}
                        </div>
                        @if(isset($inspeccion->vin_sub_estado_inventario_id))
                        <div class="form-group" id="bloque_sub_estado" style="display: none">
                            <label for="vin_sub_estado_inventario_id" >Sub-Estado de Inventario <strong>*</strong></label>
                            {!! Form::text(null, $inspeccion->oneVinSubEstadoInventario(), ['id' => 'vin_sub_estado_inventario_id', 'class' => 'form-control col-sm-9', 'disabled']) !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                            {{-- {!! Form::select(null, $piezaCategorias, null,['placeholder'=>'Seleccionar Categoría', 'class'=>'form-control col-sm-9 select-empresa']) !!} --}}
                            <select name="pieza_categoria_id" id="pieza-categoria" class="form-control select-categoria">
                                <option value="" selected>Seleccione la Categoría</option>
                            @foreach($piezaCategorias as $k => $v)
                                <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pieza_subcategoria_id" >Sub-Categoría <strong>*</strong></label>
                            {!! Form::select(null, ['placeholder' => 'Seleccionar Sub-Categoría'], null,['id'=>'pieza-subcategoria', 'class'=>'form-control select-subcategoria']) !!}
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

<!-- Anexar Fotografía del Daño -->
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
            <div class="card-body">
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
@stop

@section('local-scripts')
    <script>
        $(document).ready(function () {
            // Evento de presionar el botón de añadir fotos
            $("#btn-fotos").click(function(e){
                e.preventDefault();

                $(this).hide();
                $('#boton_inspeccion_dano').hide();
                $('#boton_inspeccion_dano_fotos').show();
                $('#bloque_fotos').show(); 
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
