@extends('layouts.app')
@section('title','Info de Predespacho')
@section('custom_styles')
<link href="{{asset('css/switch_button.css')}}" type="text/css" rel="stylesheet">
@endsection
@section('content')
@include('flash::message')

    <!--SUPER ADMINISTRADOR y ADMINISTRADOR -->
    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2)
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Datos de Predespacho</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>
                <div class="card-body overflow-auto">
                <!--   <div class="col-lg-12 pb-3 pt-2">
                            <a href="{{  route('empresa.create') }}" class = 'btn btn-primary'>Crear nueva Empresa</a>
                        </div>
                -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableAusentismo" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código VIN</th>
                                    <th>Responsable</th>
                                    <th>Tipo Agendamiento</th>
                                    @if($predespacho->tipo_agendamiento_id == 1)
                                        <th>Usuario que retira</th>
                                        <th>RUT</th>
                                        <th>Email</th>
                                    @else
                                        <th>Desde</th>
                                        <th>Hacia</th>
                                    @endif
                                    <th>Acci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><small>{{ $predespacho->codigoVin() }}</small></td>
                                    <td><small>{{ $predespacho->nombreResponsable() }}</small></td>
                                    @if($predespacho->tipo_agendamiento_id == 1)
                                        <td><small>Retiro</small></td>
                                    @else
                                        <td><small>Traslado</small></td>
                                    @endif

                                    @if($predespacho->tipo_agendamiento_id == 1)
                                        <td><small>{{ $predespacho->nombreUsuario() }}</small></td>
                                        <td><small>{{ $predespacho->user->user_rut }}</small></td>
                                        <td><small>{{ $predespacho->user->email }}</small></td>
                                    @else
                                        <td><small>{{ $predespacho->predespacho_origen }}</small></td>
                                        <td><small>{{ $predespacho->predespacho_destino }}</small></td>
                                    @endif

                                    <td>
                                        <small>
                                            <a type="button" href="{{route('entrega.index')}}" class="btn btn-primary block full-width m-b">Regresar</a>
                                        </small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
@stop
