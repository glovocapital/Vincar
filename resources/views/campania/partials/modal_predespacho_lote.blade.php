<!-- Modal -->
<div id="predespachoModalLote" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Predespacho</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'vin.predespacho', 'method'=>'POST', 'id'=>"PredespachoVinsLote"]) !!}

                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group" id="codigos_vin">
                            <div name="vin_codigo_predespacho" id="vin_codigo_predespacho_lote">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="vin_estado_inventario_id" >Autorizar Entrega </label>
                            {!! Form::select('predespacho', ['1' => 'Preparar para entrega'], null,['placeholder'=>'Seleccione la Opción','class'=>'form-control col-sm-9', 'required'=>'required']) !!}

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="vin_fecha" >Agendar fecha de entrega </label>
                            {{ Form::date('vin_fecha_despacho', new \DateTime(), ['class' => 'form-control', 'id' => 'vin_fecha_despacho', 'required']) }}
                        </div>
                    </div>


                </div>

                <div id="error0_predespacho_lote" style="display:none; padding:5px" class="alert alert-success" role="alert">Datos Guardados</div>
                <div id="error1_predespacho_lote" style="display:none; padding:5px" class="alert alert-danger" role="alert">Error al Guardar</div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar Modal</button>

                <button id="btn-pre-despacho-lote" type="button" class="btn btn-primary block full-width m-b">Asignar Entrega</button>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->