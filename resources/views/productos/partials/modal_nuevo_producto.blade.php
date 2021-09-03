<!-- Modal -->
<div id="nuevoProducto" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Producto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'productos.store', 'method'=>'POST', 'id' => 'formNuevoProducto']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="producto_codigo" >Código de Producto <strong>*</strong></label>
                            {!! Form::text('producto_codigo', null, ['placeholder'=>'Código del producto', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="producto_nombre" >Descripción <strong>*</strong></label>
                            {!! Form::text('producto_descripcion', null, ['placeholder'=>'Descripción', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>

                <br />
                <br />
                <br />

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar Producto ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
