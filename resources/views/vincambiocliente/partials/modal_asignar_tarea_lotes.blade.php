<!-- Modal -->
<div id="asignarTareaModalLote" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="overflow-y: scroll;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar Propietario</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'vin.cambio', 'method'=>'POST', 'id'=>"TareasVins"]) !!}
                @csrf
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group" id="codigos_vin">
                            <div name="vin_codigo_lote" id="vin_codigo_lote">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tarea_prioridad" >Nuevo Dueño <strong>*</strong></label>
                            {!! Form::select('cliente_nuevo', $empresas, null, ['placeholder' => 'Seleccione...', 'class'=>'form-control col-sm-12 select-prioridad', 'required' => 'required']) !!}
                        </div>
                    </div>
                </div>
                <div id="error0" style="display:none; padding:5px" class="alert alert-success" role="alert">Datos Guardados</div>
                <div id="error1" style="display:none; padding:5px" class="alert alert-danger" role="alert">Error al Guardar</div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

                <button id="btn-guardar-campania-lotes" type="button" class="btn btn-primary block full-width m-b btn-guardar-campania-lotes">Guardar</button>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->
