@extends('layouts.app')
@section('title','Patio index')
@section('content')
@include('flash::message')

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
                            <label for="" >Datos Básicos</label>
                        </div>

                        <div class="form-group">
                            <label for="patio_nombre" >Nombre del Patio <strong>*</strong></label>
                            {!! Form::text('patio_nombre', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                                <label for="patio_bloques" >Cantidad de Bloques <strong>*</strong></label>
                                {!! Form::text('patio_bloques', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <br />
                        <br />
                        <br />

                        <div class="form-group">
                            <label for="" >Coordenadas Geográficas</label>
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
                            <label for="" >Datos de Ubicación</label>
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

                        <br />

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

                <div class="container">
                    <div class="row">
                        <div class="col-lg-2">
                            <a href="{{ route('patio.cargar_patios') }}" class = 'btn btn-success'>Carga de Patios</a>
                        </div>
                        <div class="col-lg-2">
                            {!! Form::open(['route'=> 'patio.download', 'method'=>'GET']) !!}
                            {!! Form::submit('Descargar planilla ', ['class' => 'btn btn-warning block full-width m-b']) !!}
                            {!! Form::close() !!}
                        </div>

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
                <h3 class="card-title">Listado de Patios</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>

            <div class="card-body">
                <!-- <div class="row">
                    <a href=" route('patio.create') " class = 'btn btn-primary'>Nuevo Patio</a>
                </div>

                <br /> -->

                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr rowspan=2>
                                <th>Nombre</th>
                                <th colspan="2">Bloques - Acciones
                                </th>

                                <th>Latitud</th>
                                <th>Longitud</th>
                                <th>Dirección</th>
                                <th>Región</th>
                                <!-- <th>Provincia</th> -->
                                <th>Comuna</th>
                                <th>Acci&oacute;n</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($patios as $patio)

                            <tr>
                                <td><small>{{ $patio->patio_nombre }}</small></td>
                                <td style="text-align: center"><small>{{ $patio->patio_bloques }}
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <a href = "{{ route('bloque.index', Crypt::encrypt($patio->patio_id))  }}" class="btn-bloque" title="Ver Bloques"><i class="far fa-eye"></i></a>
                                    </small>
                                    <small>
                                        <a href = "{{ route('bloque.create', Crypt::encrypt($patio->patio_id))  }}" class="btn-bloque" title="Añadir Bloque"><i class="far fa-plus-square"></i></a>
                                    </small>
                                </td>
                                <td><small>{{ $patio->patio_coord_lat }}</small></td>
                                <td><small>{{ $patio->patio_coord_lon }}</small></td>
                                <td><small>{{ $patio->patio_direccion }}</small></td>
                                <td><small>{{ $patio->oneRegion() }}</small></td>
                                <!-- <td><small> $patio->oneProvincia() </small></td> -->
                                <td><small>{{ $patio->oneComuna() }}</small></td>
                                <td>
                                    <small>
                                        <a href="{{ route('patio.edit', Crypt::encrypt($patio->patio_id)) }}" class="btn-patio"  title="Editar Patio"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                        <a href = "{{ route('patio.destroy', Crypt::encrypt($patio->patio_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-patio"  title="Eliminar Patio"><i class="far fa-trash-alt"></i></a>
                                    </small>
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
            });
        });
    </script>
@endsection
