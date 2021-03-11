<!-- Modal -->
<div id="asignarGuiaModalLote" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="overflow-y: scroll;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Carga de guía por lote</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            {!! Form::open(['route'=> 'vin.storeModalGuiaLote', 'method'=>'POST', 'files' => true, 'id' => 'form-carga-guia-lote']) !!}
            <div class="modal-body">
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
                            <label for="empresa_id" >Cliente <strong> *</strong></label>
                                {!! Form::select('empresa_guia_id', $empresas, null,['id' => 'empresa_id', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente' , 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label for="guia_fecha" >Fecha de la Guía:</label>
                            {!! Form::date('guia_fecha', null, ['id' => 'guia_fecha', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="guia_numero" >Número de Guía:</label>
                            {!! Form::text('guia_numero', null, ['id' => 'guia_numero', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                            <div class="form-group">
                                <label for="">Cargar Guia del VIN </label>
                                {!! Form::file('guia_vin', ['id' => 'guia_vin', 'required']); !!}
                            </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id='btn-cancelar-carga-guia-lote' data-dismiss="modal">Cancelar</button>
                {!! Form::submit('Cargar Guia ', ['class' => 'btn btn-primary block full-width m-b', 'id' => 'btn-carga-guia-lote']) !!}

            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Fin modal -->
