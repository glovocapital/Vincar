<!-- Modal -->
<div id="predespachoModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Predespacho</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'vin.predespacho', 'method'=>'POST', 'id'=>"PredespachoVins"]) !!}

                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group" id="codigos_vin">
                            <div name="vin_codigo_predespacho" id="vin_codigo_predespacho">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="vin_estado_inventario_id" >Agendar Entrega </label>
                            {!! Form::select('predespacho', ['1' => 'Preparar para entrega'], null,['placeholder'=>'Seleccione la Opción','class'=>'form-control col-sm-9', 'required'=>'required']) !!}

                        </div>
                    </div>


                </div>

                <div id="error0_predespacho" style="display:none; padding:5px" class="alert alert-success" role="alert">Datos Guardados</div>
                <div id="error1_predespacho" style="display:none; padding:5px" class="alert alert-danger" role="alert">Error al Guardar</div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

                <button id="btn-pre-despacho" type="button" class="btn btn-primary block full-width m-b">Asignar Entrega</button>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->
