@extends('layouts.app')
@section('title','Tour index')
@section('custom_styles')
<link href="{{asset('css/switch_button.css')}}" type="text/css" rel="stylesheet">
@section('content')
@include('flash::message')

    <div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nuevo Tour</h3>

            </div>
            <div class="card-body">

                    {!! Form::open(['route'=> 'tour.store', 'method'=>'POST']) !!}
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="transporte_id" >Proveedor de Transporte <strong> *</strong></label>
                                    {!! Form::select('transporte_id', $transporte, null,['id' => 'proveedor_id', 'placeholder'=>'Proveedor de Transporte', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="camion_id" >Camión<strong> *</strong></label>
                                    {!! Form::select('camion_id', $camion, null,['id' => 'camion', 'placeholder'=>'Camión', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                </div>
                            </div>


                            <div class="col-md-4" id="wrapper_2">
                                <div class="form-group">
                                    <label for="remolque_id" >Remolque<strong> *</strong></label>
                                    {!! Form::select('remolque_id', $remolque, null,['id' => 'patio', 'placeholder'=>'Remolque', 'class'=>'form-control col-sm-9 select-cliente' , 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="conductor_id" ><strong> Conductor *</strong></label>
                                    {!! Form::select('conductor_id', $conductor, null,['id' => 'conductor', 'placeholder'=>'Conductor', 'class'=>'form-control col-sm-9 select-cliente', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-4" id="wrapper_2">
                                <div class="form-group">
                                    <label for="tour_fecha_inicio" >Fecha de Inicio <strong>*</strong></label>
                                    {!! Form::date('tour_fecha_inicio', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="text-right pb-5">

                        {!! Form::submit('Siguiente ', ['class' => 'btn btn-success block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>

            </div>
        </div>
    </div>
</div>
    </div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
                <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Listado de Tours</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                                <hr class="mb-4">

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTablePais" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Proveedor de Transporte</th>
                                                <th>Patente Camión</th>
                                                <th>Patente Remolque</th>
                                                <th>Conductor</th>
                                                <th>Fecha Inicio</th>
                                                <th>Estatus</th>
                                                <th>Comentarios</th>
                                                <th>Iniciar Tour</th>
                                                <th>Finalizar Tour</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($tours as $tour)
                                            <tr>
                                                <td><small>{{ $tour->oneProveedor->empresa_razon_social }}</small></td>
                                                <td><small>{{ $tour->belongsToCamion->camion_patente }}</small></td>
                                                <td><small>{{ $tour->belongsToRemolque->remolque_patente }}</small></td>
                                                <td><small>{{ $tour->oneConductor->user_nombre }} {{ $tour->oneConductor->user_apellido }}</small></td>
                                                <td><small>{{ $tour->tour_fec_inicio}}</small></td>

                                                @if($tour->tour_iniciado)
                                                    @if(!$tour->tour_finalizado)
                                                        <td><small><button class="btn btn-info btn-sm rounded">En Tránsito</button></small></td>
                                                    @else
                                                        <td><small><button class="btn btn-success btn-sm rounded">Finalizado</button></small></td>
                                                    @endif
                                                @else
                                                    @if($tour->tour_finalizado)
                                                        <td><small><button class="btn btn-danger btn-sm rounded">Cancelado</button></small></td>
                                                    @else
                                                        <td><small><button class="btn btn-warning btn-sm rounded">Pendiente</button></small></td>
                                                    @endif
                                                @endif

                                                <td><small>{{ $tour->tour_comentarios}}</small></td>

                                                <td>
                                                    <div class="switch-button1">
                                                    @if (($tour->tour_fec_inicio != \Carbon\Carbon::today()->toDateString()) && (!$tour->tour_iniciado))
                                                    <p title="Modificar tour">N/A</p>
                                                    @else
                                                    {!! Form::open(['route' => 'tour.iniciar_tour', 'method'=>'POST']) !!}
                                                        <input type="checkbox" name="switch-button1" id="switch-label-iniciar-{{$tour->tour_id}}" class="switch-button1__checkbox" value="{{$tour->tour_iniciado}}"{{$tour->tour_iniciado ?' checked':''}}/>
                                                        <label for="switch-label-iniciar-{{$tour->tour_id}}" class="switch-button1__label"></label>
                                                    {!! Form::close() !!}
                                                    @endif
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="switch-button2">
                                                    @if (($tour->tour_fec_inicio != \Carbon\Carbon::today()->toDateString()) && (!$tour->tour_iniciado))
                                                    <p title="Modificar tour">N/A</p>
                                                    @else
                                                    {!! Form::open(['route' => 'tour.finalizar_tour', 'method'=>'POST']) !!}
                                                        <input type="checkbox" name="switch-button2" id="switch-label-finalizar-{{$tour->tour_id}}" class="switch-button2__checkbox" value="{{$tour->tour_finalizado}}"{{$tour->tour_finalizado ?' checked':''}}/>
                                                        <label for="switch-label-finalizar-{{$tour->tour_id}}" class="switch-button2__label"></label>
                                                    {!! Form::close() !!}
                                                    @endif
                                                    </div>
                                                </td>

                                                <td>
                                                    @if($tour->tour_iniciado)
                                                        @if(!$tour->tour_finalizado)
                                                            <small>
                                                                <a href="{{ route('tour.editrutas', Crypt::encrypt($tour->tour_id)) }}" class=" btn-vin"  title="Rutas"><i class="fas fa fa-barcode"></i></a>
                                                            </small>
                                                        @endif
                                                    @else
                                                        @if($tour->tour_finalizado)
                                                            <small>
                                                                <a href="{{ route('tour.edit', Crypt::encrypt($tour->tour_id)) }}" class="btn-empresa" title="Editar Tour"><i class="far fa-edit"></i></a>
                                                            </small>
                                                            <small>
                                                                <a href = "{{ route('tour.delete', Crypt::encrypt($tour->tour_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa" title="Eliminar Tour"><i class="far fa-trash-alt"></i></a>
                                                            </small>
                                                        @else
                                                            <small>
                                                                <a href="{{ route('tour.edit', Crypt::encrypt($tour->tour_id)) }}" class="btn-empresa"  title="Editar Tour"><i class="far fa-edit"></i></a>
                                                            </small>
                                                            <small>
                                                                <a href="{{ route('tour.editrutas', Crypt::encrypt($tour->tour_id)) }}" class=" btn-vin"  title="Gestión de Rutas"><i class="fas fa fa-barcode"></i></a>
                                                            </small>
                                                            <small>
                                                                <a href = "{{ route('tour.delete', Crypt::encrypt($tour->tour_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa" title="Eliminar Tour"><i class="far fa-trash-alt"></i></a>
                                                            </small>
                                                        @endif
                                                    @endif
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
</div>
@stop
@section('local-scripts')
    <script>
        $(document).ready(function () {
            $(".switch-button1__checkbox").change(function() {
                // e.preventDefault();

                var input_id = $(this).attr('id');
                var cad = 'switch-label-iniciar-';
                var tour_id = input_id.substring(cad.length);

                var iniciado = false;
                //Si el checkbox está seleccionado
                if($(this).is(":checked")) {
                    iniciado = true;
                }

                var request = {
                    _token: $("input[name='_token']").attr("value"),
                    iniciado: iniciado,
                    tour_id
                };

                var url = 'tour/iniciarTour';

                $.post(url, request, function (res) {
                    if(!res.success){
                        alert(
                            "Error inesperado al intentar iniciar el tour.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento
                    }
                    $("#switch-label-iniciar-" + tour_id).attr('disabled', 'disabled');
                }).fail(function () {
                    alert('Error: Fallo al intentar iniciar el tour.');
                });

            });

            $(".switch-button2__checkbox").change(function() {
                // e.preventDefault();

                var input_id = $(this).attr('id');
                var cad = 'switch-label-finalizar-';
                var tour_id = input_id.substring(cad.length);

                var finalizado = false;
                //Si el checkbox está seleccionado
                if($(this).is(":checked")) {
                    finalizado = true;
                }

                var request = {
                    _token: $("input[name='_token']").attr("value"),
                    finalizado: finalizado,
                    tour_id
                };

                var url = 'tour/finalizarTour';

                $.post(url, request, function (res) {
                    if(!res.success){
                        alert(
                            "Error inesperado al intentar finalizar el tour.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento
                    }
                    $("#switch-label-finalizar-" + tour_id).attr('disabled', 'disabled');
                }).fail(function () {
                    alert('Error: Fallo al intentar finalizar el tour.');
                });
            });
        });
    </script>
@endsection
