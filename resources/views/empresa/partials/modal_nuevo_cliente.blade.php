<!-- Modal -->
<div id="nuevoCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Cliente</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'empresa.store', 'method'=>'POST', 'id' => 'formNuevoCliente']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-4">
                        <label for="user_rut" >Rut <strong>*</strong></label>
                        <div class="input-group" >
                            {!! Form::text('empresa_rut', null, ['placeholder'=>'Rut del usuario', 'class'=>'form-control col-sm-9 rut', 'required']) !!}
                            <div class="input-group-append">
                                <span class="input-group-text" id="validador">
                                    <span style="color:red;" aria-hidden="true">&times;</span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="empresa_nombre" >Razón Social <strong>*</strong></label>
                            {!! Form::text('empresa_nombre', null, ['placeholder'=>'Nombre o Razón Social', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="pais_id" >Pais <strong>*</strong></label>
                            {!! Form::select('pais_id', $pais, null,['placeholder'=>'Seleccionar País', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="empresa_direccion" >Dirección <strong>*</strong></label>
                            {!! Form::text('empresa_direccion', null, ['placeholder'=>'Dirección', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_telefono_contacto" >Teléfono </label>
                            {!! Form::text('empresa_telefono_contacto', null, ['placeholder'=>'Telefono', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_nombre_contacto" >Contacto de la empresa </label>
                            {!! Form::text('empresa_nombre_contacto', null, ['placeholder'=>'Nombre de contacto', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="empresa_giro" >Giro <strong>*</strong></label>
                            {!! Form::text('empresa_giro', null, ['placeholder'=>'Giro de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_telefono_contacto" >Email </label>
                            {!! Form::email('empresa_email_contacto', null, ['placeholder'=>'Email', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="es_proveedor" >Es proveedor? </label>
                            <label>Sí</label>
                            <input type="radio" name="es_proveedor" id="si_es_proveedor" onchange="d1(this)" value="true" />
                            {{-- {!! Form::radio('es_proveedor', 'true'); !!} --}}
                            <label>No</label>
                            <input type="radio" name="es_proveedor" id="no_es_proveedor" onchange="d1(this)" value="false" checked />
                            {{-- {!! Form::radio('es_proveedor', 'false', true); !!} --}}
                        </div>

                        <div class="form-group" name="bloque" id="bloque_archivo" style="display: none">
                            <label for="tipo_proveedor" >Tipo de proveedor <strong>*</strong></label>
                            {!! Form::select('tipo_proveedor', $tipo_proveedor, null,['placeholder'=>'Seleccione Tipo de Proveedor', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                </div>
                <br />

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar Empresa ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
            <div class="modal-footer">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Fin modal -->
