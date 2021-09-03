<!-- Modal -->
<div id="nuevoTipoProveedor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Tipo de Proveedor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'proveedor.store', 'method'=>'POST', 'id' => 'formNuevoTipoProveedor']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="tipo_proveedor_desc" >Nombre del Proveedor <strong>*</strong></label>
                            {!! Form::text('tipo_proveedor_desc', null, ['placeholder'=>'Nombre del Proveedor', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>

                <br />
                <br />
                <br />

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar Tipo de Proveedor ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
