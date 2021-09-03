<!-- Modal -->
<div id="nuevoDestino" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Conductor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'destinos.store', 'method'=>'POST', 'id' => 'formNuevoDestino']) !!}
            <div class="modal-body">
                <div class="row  row-fluid">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="destino_codigo" >Código del Destino <strong>*</strong></label>
                            {!! Form::text('destino_codigo', null, ['placeholder'=>'Código del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="destino_nombre" >Nombre del Destino <strong>*</strong></label>
                            {!! Form::text('destino_nombre', null, ['placeholder'=>'Nombre del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar Destino ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
            {!! Form::close() !!}

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->
