<!-- Modal -->
<div id="solicitudCampaniaModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Asignar Campaña</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'campania.storeModal', 'method'=>'POST']) !!}
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h3 name="vin_codigo" id="vin_codigo"></h3>
                            <input type="hidden" class="vin-id" name="vin_id" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo_campanias" >Campañas Disponibles <strong>*</strong></label>
                            {!! Form::select('tipo_campanias[]', $tipo_campanias_array, null,['id' => 'tipo_campanias', 'rows' => '7', 'class'=>'form-control col-sm-12 select-tipo-campanias', 'multiple' => 'multiple', 'required'=>'required']) !!}
                        </div>
                        
                        <label for="campania_fecha_finalizacion">Fecha de finalización de tareas solicitadas</label>
                        <div class="input-group">
                            <input type="date" id="campania_fecha_finalizacion" class="form-control"  name="campania_fecha_finalizacion" required/><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>
                        <div id="mensaje1" class="error" style="color: red; font-weight: bold"> Fecha requerida.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="campania_observaciones">Observaciones</label>
                        {!! Form::textarea('campania_observaciones', null, ['placeholder'=>'Colocar observaciones', 'class'=>'form-control col-sm-12']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary block full-width m-b btn-guardar-campania', 'id'=>'btn-guardar-campania']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->