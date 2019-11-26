@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Editar usuario</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> ['usuarios.update', Crypt::encrypt($usuario->user_id)], 'method'=>'PATCH']) !!}
                <div class="form-group">
                    <div class="row">
                      <label for="user_nombre" class="col-sm-3">Nombre del usuario <strong>*</strong></label>
                      {!! Form::text('user_nombre', $usuario->user_nombre, ['placeholder'=>'Nombre del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="user_apellido" class="col-sm-3">Apellido del usuario <strong>*</strong></label>
                      {!! Form::text('user_apellido', $usuario->user_apellido, ['placeholder'=>'Apellido del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                 </div>

                 <div class="form-group">
                        <div class="row">
                          <label for="user_rut" class="col-sm-3">Rut del usuario <strong>*</strong></label>
                          {!! Form::text('user_rut', $usuario->user_rut, ['placeholder'=>'Rut del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                     </div>

                <div class="form-group">
                    <div class="row">
                      <label for="user_email" class="col-sm-3">Email del usuario <strong>*</strong></label>
                      {!! Form::text('user_email', $usuario->email, ['class'=>'form-control col-sm-9', 'placeholder'=>'Email']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="password" class="col-sm-3">Contraseña <strong>*</strong></label>
                        <input id="user_pass" type="password" class="form-control col-sm-9{{ $errors->has('user_pass') ? ' is-invalid' : '' }}" name="user_pass">
                        @if ($errors->has('user_pass'))
                            <span class="invalid-feedback" role="alert">
                                <script>{{ $errors->first('user_pass') }}</script>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                    <label for="password-confirm" class="col-sm-3">Repita Contraseña <strong>*</strong></label>
                    <input id="user_pass-confirm" type="password" class="form-control col-sm-9" name="user_pass_confirmation">
                    </div>
                 </div>

                <div class="form-group">
                    <div class="row">
                      <label for="emp_id" class="col-sm-3">Empresa <strong>*</strong></label>
                      {!! Form::select('empresa_id', $empresa, $usuario->empresa_id,['class'=>'form-control col-sm-9', 'required'=>'required', 'onchange' => 'cambiarSubrubro(this)']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="user_tlf" class="col-sm-3">Teléfono </label>
                      {!! Form::text('user_telefono', $usuario->user_telefono, ['placeholder'=>'Telefono', 'class'=>'form-control col-sm-9']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="user_cargo" class="col-sm-3">Cargo </label>
                      {!! Form::text('user_cargo', $usuario->user_cargo, ['placeholder'=>'Nombre del cargo', 'class'=>'form-control col-sm-9']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="rol_id" class="col-sm-3">Rol <strong>*</strong></label>
                      {!! Form::select('rol_id', $roles, $usuario->rol_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="text-center pb-5">
                    {!! Form::submit('Actualizar usuario', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop

