@extends('layouts.app')
@section('title','Registrar Patio')
@section('content')
<!-- Registrar datos básicos de un patio -->
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Registrar Patio</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::open(['route'=> 'patio.store', 'method'=>'POST', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="" >Datos Básicos <strong>*</strong></label>
                        </div>

                        <div class="form-group">
                            <label for="patio_nombre" >Nombre del Patio <strong>*</strong></label>
                            {!! Form::text('patio_nombre', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="patio_bloques" >Cantidad de Bloques <strong>*</strong></label>
                                {!! Form::text('patio_bloques', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="" >Coordenadas Geográficas <strong>*</strong></label>
                        </div>

                        <div class="form-group">
                            <label for="patio_coord_lat" >Latitud <strong>*</strong></label>
                            {!! Form::text('patio_coord_lat', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="patio_coord_lon" >Longitud <strong>*</strong></label>
                                {!! Form::text('patio_coord_lon', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" >Datos de Ubicación <strong>*</strong></label>
                        </div>

                        <div class="form-group">
                            <label for="region_id" >Region <strong>*</strong></label>
                            <select name="region_id" id="region" class="form-control select-region" required>
                                <option value="" selected>Seleccione Región</option>
                            @foreach($regiones as $k => $v)
                                <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comuna_id" >Seleccionar Comuna <strong>*</strong></label>
                            {!! Form::select('comuna_id', ['placeholder' => 'Seleccionar Comuna'], null, ['id' => 'comuna_id', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <label for="patio_direccion" >Dirección <strong>*</strong></label>
                            {!! Form::textarea('patio_direccion', null,['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="text-right pb-5" id="boton_patio">
                    {!! Form::submit('Registrar Patio', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
