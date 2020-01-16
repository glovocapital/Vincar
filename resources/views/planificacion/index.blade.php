@extends('layouts.app')
@section('title','Planificación index')
@section('content')

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
                {!! Form::open(['route'=> 'planificacion.index', 'method'=>'get']) !!}
                <div class="row">
                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                                <label for="vin_numero" >Vin <strong>*</strong></label>
                                {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'cols' => 40, 'style' => 'resize:none']) !!}
                        </div>
                    </div>

                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                                <label for="user_id" >Seleccionar Cliente <strong>*</strong></label>
                                {!! Form::select('user_id', $empresas, null,['id' => 'cliente', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente']) !!}
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
                <div class="text-right pb-5">
                {!! Form::submit('Buscar vin ', ['class' => 'btn btn-primary block full-width m-b', 'id'=>'btn-src']) !!}
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de VINs (Resultado de la búsqueda)</h3>
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
                                <!-- <th>Motor</th> -->
                                <th>Segmento</th>
                                <th>Fecha de Ingreso</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <!--  <th>Sub Estado Inventario </th>  -->
                                <!--   <th>Gestión de Registro</th> -->
                                <th>Acciones de VIN</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tabla_vins as $vin)
                            <tr>
                                <td id="vin-codigo-{{ $vin->vin_id }}"><small>{{ $vin->vin_codigo }}</small></td>
                                <td><small>{{ $vin->vin_patente }}</small></td>
                                <td><small>{{ $vin->vin_modelo }}</small></td>
                                <td><small>{{ $vin->vin_marca }}</small></td>
                                <td><small>{{ $vin->vin_color }}</small></td>
                                <!-- <td><small>{{ $vin->vin_motor }}</small></td> -->
                                <td><small>{{ $vin->vin_segmento }}</small></td>
                                <td><small>{{ $vin->vin_fec_ingreso }}</small></td>
                                <td><small>{{ $vin->empresa_razon_social }}</small></td>
                                <td><small>{{ $vin->vin_estado_inventario_desc }}</small></td>

                                <!--   <td>


                                    <small>
                                        <a href = "{{ route('vin.destroy', Crypt::encrypt($vin->vin_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-vin"><i class="far fa-trash-alt"></i>
                                    </a>
                                    </small>

                                </td> -->

                                <td>
                                    <small>
                                        <a href="{{ route('vin.edit', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>

                                    <small>
                                        <a href="{{ route('vin.editarestado', Crypt::encrypt($vin->vin_id)) }}" class="btn-vin"  title="Cambiar Estado"><i class="fa fa-flag-checkered"></i></a>
                                    </small>

                                    <small>
                                        <button value="{{ $vin->vin_id }}" class="btn btn-xs btn-success btn-tarea"  title="Solicitar Tarea"><i class="far fa-lightbulb"></i></button>
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

<br />

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Tareas Solicitadas (Campañas Solicitadas)</strong></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableCampanias" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Código VIN</th>
                                <th>Descripción Campañas</th>
                                <th>Fecha Finalización</th>
                                <th>Observaciones</th>
                                <th>Usuario Solicitante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($campanias as $campania)
                        <tr>
                            <td><small>{{ $campania->codigoVin() }}</small></td>
                            <td><small>
                            @foreach($arrayTCampanias as $tipoCamp)
                                @foreach($tipoCamp as $tCamp)
                                    @if($campania->campania_id === $tCamp->campania_id)   
                                        <button class="btn btn-xs btn-info">{{ $tCamp->tipo_campania_descripcion }}</button>
                                    @endif
                                @endforeach
                            @endforeach
                            </small></td>
                            <td><small>{{ $campania->campania_fecha_finalizacion }}</small></td>
                            <td><small>{{ $campania->campania_observaciones }}</small></td>
                            <td><small>{{ $campania->nombreUsuario() }}</small></td>
                            <td>
                                <small>
                                    <a href="{{ route('campania.edit', Crypt::encrypt($campania->campania_id)) }}" class="btn-bloque" title="Editar Campania"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                    <a href = "{{ route('campania.destroy', Crypt::encrypt($campania->campania_id)) }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-bloque" title="Eliminar campaña"><i class="far fa-trash-alt"></i></a>
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

<div class="col-lg-12">
    
</div>

@include('planificacion.partials.modal_asignar_tarea')

@stop
@section('local-scripts')


<script>
        $(document).ready(function () {
            //Modal Solicitar Tarea
            $('.btn-tarea').click(function (e) {
                e.preventDefault();

                var vin_id = $(this).val();
                var vin_codigo = $("#vin-codigo-" + vin_id).children().html();
                
                $(".vin-id").val(vin_id);
                $("#vin_codigo").html("VIN: " +vin_codigo);

                $("#asignarTareaModal").modal('show');
            });
        });
    </script>
@endsection


