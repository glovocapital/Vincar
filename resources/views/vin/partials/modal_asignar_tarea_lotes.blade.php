<!-- Modal -->
<div id="asignarTareaModalLote" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Carga de guía por lote</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'vin.storeModalTareaLotes', 'method'=>'POST', 'files' => true]) !!}
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
                                <label for="">Cargar Guia del VIN </label>
                                {!! Form::file('guia_vin', array('required')); !!}
                            </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                {!! Form::submit('Cargar Guia ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->