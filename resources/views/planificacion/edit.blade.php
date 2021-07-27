@extends('layouts.app')
@section('title','Editar Campania')
@section('content')

<div id="editSolicitudCampania" class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h4 class="modal-title">Editar Tarea</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            {!! Form::open(['route'=> ['planificacion.update', Crypt::encrypt($tarea->tarea_id)], 'method'=>'PATCH']) !!}
            <div class="card-body overflow-auto">
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h3 name="vin_codigo" id="vin_codigo">{{ $vin_codigo }}</h3>
                            <input type="hidden" class="vin-id" name="vin_id" value="{{ $tarea->vin_id }}" />
                            <input type="hidden" class="vin-id" name="tarea_id" value="{{ $tarea->tarea_id }}" />
                            <input type="hidden" class="vin-id" name="campania_id" value="{{ $campania_id }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tarea_prioridad" >Prioridad <strong>*</strong></label>
                            <select name="tarea_prioridad" class="form-control col-sm-12 select-prioridad" required>
                                @if($tarea->tarea_prioridad == 0)
                                <option value="0" selected>Baja</option>
                                <option value="1">Media</option>
                                <option value="2">Alta</option>
                                @elseif($tarea->tarea_prioridad == 1)
                                <option value="0">Baja</option>
                                <option value="1" selected>Media</option>
                                <option value="2">Alta</option>
                                @elseif($tarea->tarea_prioridad == 1)
                                <option value="0">Baja</option>
                                <option value="1">Media</option>
                                <option value="2" selected>Alta</option>
                                @else
                                <option value="">Seleccionar Prioridad</option>
                                <option value="0">Baja</option>
                                <option value="1">Media</option>
                                <option value="2">Alta</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipo_campanias" >Campañas Disponibles <strong>*</strong></label>
                            <select name="tipo_campanias[]" id="tipo_campanias" class="form-control col-sm-12 select-tipo-campanias" multiple disabled>
                            @foreach($tipo_campanias_array as $k => $v)
                                @php($enc = false)
                                @foreach($tCampanias as $tCamp)
                                    @if($v === $tCamp->tipo_campania_descripcion)
                                        <option value="{{ $tCamp->tipo_campania_id }}" selected>{{ $tCamp->tipo_campania_descripcion }}</option>
                                        @php($enc = true)
                                    @endif
                                @endforeach
                                @if(!$enc)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>

                        <label for="tarea_fecha_finalizacion">Fecha de finalización de tareas solicitadas</label>
                        <div class="input-group">
                            <input type="date" id="tarea_fecha_finalizacion" class="form-control"  name="tarea_fecha_finalizacion" value="{{$tarea->tarea_fecha_finalizacion}}" required/><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tarea_responsable_id" >Usuario Responsable <strong>*</strong></label>
                            <select name="tarea_responsable_id" class="form-control col-sm-12 select-responsable" required>
                                @foreach($responsables_array as $k => $v)
                                    @if($tarea->user_id == $k)
                                    <option value="{{ $k }}" selected>{{ $tarea->nombreResponsable() }}</option>
                                    @else
                                    <option value="{{ $k }}">{{ $v }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipo_tarea_id" >Tarea <strong>*</strong></label>
                            <select name="tipo_tarea_id" class="form-control col-sm-12 select-tipo-tarea" required>
                                @foreach($tipo_tareas_array as $k => $v)
                                    @if($tarea->tipo_tarea_id == $k)
                                    <option value="{{ $k }}" selected>{{ $tarea->oneTipoTarea() }}</option>
                                    @else
                                    <option value="{{ $k }}">{{ $v }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipo_destino_id" >Destino <strong>*</strong></label>
                            <select name="tipo_destino_id" class="form-control col-sm-12 select-tipo-destino" required>
                                @foreach($tipo_destinos_array as $k => $v)
                                    @if($tarea->tipo_destino_id == $k)
                                    <option value="{{ $k }}" selected>{{ $tarea->oneTipoDestino() }}</option>
                                    @else
                                    <option value="{{ $k }}">{{ $v }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tarea_hora_termino" >Hora de Término <strong>*</strong></label>
                            {{ Form::time('tarea_hora_termino', $tarea->tarea_hora_termino, ['class'=>'form-control col-sm-12 select-tipo-destino', 'required' => 'required']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Actualizar', ['class' => 'btn btn-primary block full-width m-b btn-actualizar-tarea', 'id'=>'btn-actualizar-tarea']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@stop
