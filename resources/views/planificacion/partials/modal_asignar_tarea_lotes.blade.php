<!-- Modal -->
<div id="asignarTareaModalLote" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Asignar Tarea</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'campania.storeModalTareaLotes', 'method'=>'POST']) !!}
                @csrf
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group" id="codigos_vin">
                            <div name="vin_codigo_lote" id="vin_codigo_lote">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tarea_prioridad" >Prioridad <strong>*</strong></label>
                            {!! Form::select('tarea_prioridad', [0 => 'Baja', '1' => 'Media', '2' => 'Alta', '3' => 'Urgente'], null, ['placeholder' => 'Seleccione...', 'class'=>'form-control col-sm-12 select-prioridad', 'required' => 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="tipo_campanias" >Campañas Seleccionadas <strong>*</strong></label>
                            <select name="tipo_campanias" id="tipo_campanias_lotes" multiple disabled class="form-control col-sm-12 select-tipo-campanias">
                            @foreach($arrayTCampanias as $key => $tCamp)
                                <option value="" selected>{{ $tCamp[$key]->tipo_campania_descripcion }}</option>
                            @endforeach
                            </select>
                        </div>
                        
                        <label for="tarea_fecha_finalizacion">Fecha de finalización de tareas solicitadas</label>
                        <div class="input-group">
                            <input type="date" id="tarea_fecha_finalizacion_lotes" class="form-control"  name="tarea_fecha_finalizacion" required/><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>
                        <div id="mensaje1" class="error" style="color: red; font-weight: bold"> Fecha requerida.</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tarea_responsable_id" >Usuario Responsable <strong>*</strong></label>
                            {!! Form::select('tarea_responsable_id', $responsables_array, null, ['placeholder' => 'Seleccione...', 'class'=>'form-control col-sm-12 select-responsable', 'required' => 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="tipo_tarea_id" >Tarea <strong>*</strong></label>
                            {!! Form::select('tipo_tarea_id', $tipo_tareas_array, null, ['placeholder' => 'Seleccione...', 'class'=>'form-control col-sm-12 select-tipo-tarea', 'required' => 'required']) !!}
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo_destino_id" >Destino <strong>*</strong></label>
                            {!! Form::select('tipo_destino_id', $tipo_destinos_array, null, ['placeholder' => 'Seleccione...', 'class'=>'form-control col-sm-12 select-tipo-destino', 'required' => 'required']) !!}
                        </div>
                        
                        <div class="form-group">
                            <label for="tarea_hora_termino" >Hora de Término <strong>*</strong></label>
                            {{ Form::time('tarea_hora_termino', null, ['class'=>'form-control col-sm-12 select-tipo-destino', 'required' => 'required']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary block full-width m-b btn-guardar-campania-lotes', 'id'=>'btn-guardar-campania-lotes']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->