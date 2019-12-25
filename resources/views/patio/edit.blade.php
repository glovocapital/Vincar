@extends('layouts.app')
@section('title','Modificar Patio')
@section('content')
<!-- Registrar datos básicos de un patio -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Editar Patio</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::open(['route'=> ['patio.update', Crypt::encrypt($patio->patio_id)], 'method'=>'PATCH', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="" >Datos Básicos</label>
                        </div>

                        <div class="form-group">
                            <label for="patio_nombre" >Nombre del Patio <strong>*</strong></label>
                            {!! Form::text('patio_nombre', $patio->patio_nombre, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="patio_bloques" >Cantidad de Bloques <strong>*</strong></label>
                                {!! Form::text('patio_bloques', $patio->patio_bloques, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <br />
                        <br />
                        <br />

                        <div class="form-group">
                            <label for="" >Coordenadas Geográficas</label>
                        </div>

                        <div class="form-group">
                            <label for="patio_coord_lat" >Latitud <strong>*</strong></label>
                            {!! Form::text('patio_coord_lat', $patio->patio_coord_lat, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="patio_coord_lon" >Longitud <strong>*</strong></label>
                                {!! Form::text('patio_coord_lon', $patio->patio_coord_lon, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" >Datos de Ubicación</label>
                        </div>

                        <div class="form-group">
                            <label for="region_id" >Region <strong>*</strong></label>
                            <select name="region_id" id="region" class="form-control select-region" required>
                            <option value="" selected>Seleccionar Región</option>
                            @foreach($regiones as $k => $v)
                                @if($k == $patio->region_id)
                                    <option value="{{Crypt::encrypt($k)}}" selected>{{$v}}</option>
                                @else
                                    <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comuna_id" >Seleccionar Comuna <strong>*</strong></label>
                            <select name="comuna_id" id="comuna_id" class="form-control" required>
                            <option value="" selected>Seleccionar Comuna</option>
                            @foreach($comunas as $k => $v)
                                @if($k == $patio->comuna_id)
                                    <option value="{{$k}}" selected>{{$v}}</option>
                                @else
                                    <option value="{{$k}}">{{$v}}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>

                        <br />

                        <div class="form-group">
                            <label for="patio_direccion" >Dirección <strong>*</strong></label>
                            {!! Form::textarea('patio_direccion', $patio->patio_direccion,['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="text-right pb-5" id="boton_patio">
                    {!! Form::submit('Actualizar Patio', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
            $(".select-region").change(function (e) {

                e.preventDefault();

                var id = $(this).val();

                if (id != ''){

                    var url = "/patio/obtener_comunas/";
                    
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

                        var arr_comunas = $.map(res.comunas, function (e1) {
                            return e1;
                        });

                        $("#comuna_id").html("<option value=''>Seleccionar Comuna</option>");
                        for (var i = 0; i < arr_ids.length; i++){
                            $("#comuna_id").append("<option value='" + arr_ids[i] + "'>" + arr_comunas[i] + "</option>");
                        }

                    }).fail(function () {
                        alert('Error: Respuesta de datos inválida');
                    });
                }else{
                    $("#comuna_id").html("<option value=''>Seleccionar Comuna</option>");
                }



                /**/

            });
        });
    </script>
@endsection
