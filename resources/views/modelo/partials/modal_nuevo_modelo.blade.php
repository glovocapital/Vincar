<!-- Modal -->
<div id="nuevoModelo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Modelo</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'modelos.store', 'method'=>'POST', 'id' => 'formNuevoModelo']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="marca_id" >Nombre de la Marca <strong>*</strong></label>
                            {!! Form::select('marca_id', $marca, null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modelo_alias" >Alias </label>
                            {!! Form::text('modelo_alias', null, ['placeholder'=>' Alias', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo_nombre" >Modelo <strong>*</strong></label>
                            {!! Form::text('modelo_nombre', null, ['placeholder'=>'Ingrese Modelo', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo_tipo" >Tipo de Vehiculo <strong>*</strong></label>
                            {!! Form::text('modelo_tipo', null, ['placeholder'=>'Tipo', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                </div>

                <br />
                <br />
                <br />
                <br />

                <div class="text-center pb-5">
                        {!! Form::submit('Registrar Modelo ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
