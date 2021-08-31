
<!-- Modal -->
<div id="nuevoUsuario" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Usuario</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'usuarios.store', 'method'=>'POST', 'id' => 'formNuevoUsuario']) !!}
            <div class="modal-body">
                <div class='row row-fluid'>
                    <div class="col-md-4">
                        <label for="user_rut" >Rut <strong>*</strong></label>
                        <div class="input-group" >
                            {!! Form::text('user_rut', null, ['placeholder'=>'Rut del usuario', 'class'=>'form-control col-sm-9 rut', 'required']) !!}
                            <div class="input-group-append">
                                <span class="input-group-text" id="validador">
                                    <span style="color:red;" aria-hidden="true">&times;</span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="user_nombre" >Nombre <strong>*</strong></label>
                            {!! Form::text('user_nombre', null, ['placeholder'=>'Nombre del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="user_nombre" >Apellido del usuario <strong>*</strong></label>
                            {!! Form::text('user_apellido', null, ['placeholder'=>'Apellido del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="user_telefono" >Teléfono <strong>*</strong></label>
                            {!! Form::text('user_telefono', null, ['placeholder'=>'Telefono', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="user_email" >Email <strong>*</strong></label>
                            {!! Form::text('user_email', old('email'), ['class'=>'form-control col-sm-9', 'placeholder'=>'Email', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_id" >Empresa <strong>*</strong></label>
                            {!! Form::select('empresa_id', $empresa, null,['placeholder'=>'Seleccionar Empresa', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="user_cargo" >Cargo del usuario <strong>*</strong></label>
                            {!! Form::text('user_cargo', null, ['placeholder'=>'Cargo del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="user_pass" >Contraseña <strong>*</strong></label>
                            {{ Form::password('user_pass',array('placeholder'=>'Contraseña','class' => 'form-control col-sm-9', 'required')) }}
                            @if ($errors->has('user_pass'))
                                <span class="invalid-feedback" role="alert">
                                    <script>{{ $errors->first('user_pass') }}</script>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="user_pass_rep" >Repita Contraseña <strong>*</strong></label>
                            {{ Form::password('user_pass_confirmation',array('placeholder'=>'Repita la contraseña','class' => 'form-control col-sm-9', 'required')) }}
                        </div>

                        <div class="form-group">
                            <label for="rol_id" >Rol <strong>*</strong></label>
                            {!! Form::select('rol_id', $roles, null,['placeholder'=>'Seleccionar Rol', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar usuario', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
