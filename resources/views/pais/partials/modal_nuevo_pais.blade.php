<!-- Modal -->
<div id="nuevoPais" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo País</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'pais.store', 'method'=>'POST', 'id' => 'formNuevoPais']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group">
                                <label for="pais_nombre" class="col-sm-3">Nombre del País <strong>*</strong></label>
                                {!! Form::text('pais_nombre', null, ['placeholder'=>'Nombre del País', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>

                <br />
                <br />
                <br />

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar País ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
