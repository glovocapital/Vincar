@extends('layouts.app')
@section('title','Editar Campania')
@section('content')

<div id="editSolicitudCampania" class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h4 class="modal-title">Editar Campaña</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            {!! Form::open(['route'=> ['campania.update', Crypt::encrypt($campania->campania_id)], 'method'=>'PATCH']) !!}
            <div class="card-body">
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h3 name="vin_codigo" id="vin_codigo">{{ $vin_codigo }}</h3>
                            <input type="hidden" class="vin-id" name="vin_id" value="{{ $campania->vin_id }}" />
                            <input type="hidden" class="vin-id" name="campania_id" value="{{ $campania->campania_id }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo_campanias" >Campañas Disponibles <strong>*</strong></label>                        
                            <select name="tipo_campanias[]" id="tipo_campanias" class="form-control col-sm-12 select-tipo-campanias" required multiple>
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
                        
                        <label for="campania_fecha_finalizacion">Fecha de finalización de tareas solicitadas</label>
                        <div class="input-group">
                            <input type="date" id="campania_fecha_finalizacion" class="form-control"  name="campania_fecha_finalizacion" value="{{ $campania->campania_fecha_finalizacion }}" required/><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>
                        <div id="mensaje1" class="error" style="color: red; font-weight: bold"> Fecha requerida.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="campania_observaciones">Observaciones</label>
                        {!! Form::textarea('campania_observaciones',  $campania->campania_observaciones, ['placeholder'=>'Colocar observaciones', 'class'=>'form-control col-sm-12']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Actualizar', ['class' => 'btn btn-primary block full-width m-b btn-guardar-campania', 'id'=>'btn-guardar-campania']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@stop