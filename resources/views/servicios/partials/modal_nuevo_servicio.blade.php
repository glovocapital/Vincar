<!-- Modal -->
<div id="nuevoServicio" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Servicio</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'servicios.store', 'method'=>'POST', 'id' => 'formNuevoServicio']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="producto_id" >Código Producto <strong>*</strong></label>
                            {!! Form::select('producto_id', $producto, null,['placeholder'=>'Código de Produto', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="divisa_id" >Divisa <strong>*</strong></label>
                            {!! Form::select('divisa_id', $divisa, null,['placeholder'=>'Divisa', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cliente_id" >Cliente <strong>*</strong></label>
                            {!! Form::select('cliente_id', $cliente, null,['placeholder'=>'Cliente', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="marca_id" >Caracteristicas <strong>*</strong></label>
                            {!! Form::select('caracteristica_id', $caracteristicasvin, null,['placeholder'=>'Caracteristicas', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="valor_asociado_id" >Valor Asociado <strong>*</strong></label>
                            {!! Form::select('valor_asociado_id', $valor_asociado, null,['placeholder'=>'Valor Asociado', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="marca_id" >Marca </label>
                            {!! Form::select('marca_id', $marca, null,['placeholder'=>'Marca', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" >Costo Servicio <strong>*</strong></label>
                            {{ Form::number('servicio_costo','0', ['min' => '0','placeholder'=>'Costo', 'class'=>'form-control col-sm-9', 'required'=>'required','step' => '0.1']) }}
                        </div>
                    </div>
                </div>

                <br />
                <br />
                <br />

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar servicio', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
