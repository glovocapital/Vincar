<!-- Modal -->
<div id="cambiarEdoModalLote" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar Estado</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'vin.storeModalCambiaEstado', 'method'=>'POST']) !!}
                @csrf
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group" id="codigos_vin">
                            <div name="vin_codigo_edo_lote" id="vin_codigo_edo_lote">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vin_estado_inventario_id" >Estado de Inventario </label>
                            {!! Form::select('vin_estado_inventario_id', $estadosInventario, null,['class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                {!! Form::submit('Cambiar Estado ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->
