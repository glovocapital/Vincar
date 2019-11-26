@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Editar usuario</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> ['usuarios.update', $usuario->user_id], 'method'=>'PATCH']) !!}
                <div class="form-group">
                    <div class="row">
                      <label for="usu_nombre" class="col-sm-3">Nombre del usuario <strong>*</strong></label>
                      {!! Form::text('usu_nombre', $usuario->user_nombre, ['placeholder'=>'Nombre del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="usu_apellido" class="col-sm-3">Apellido del usuario <strong>*</strong></label>
                      {!! Form::text('usu_apellido', $usuario->user_apellido, ['placeholder'=>'Apellido del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                 </div>

                <div class="form-group">
                    <div class="row">
                      <label for="usu_email" class="col-sm-3">Email del usuario <strong>*</strong></label>
                      {!! Form::text('usu_email', $usuario->email, ['class'=>'form-control col-sm-9', 'placeholder'=>'Email']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="password" class="col-sm-3">Contraseña <strong>*</strong></label>
                        <input id="password" type="password" class="form-control col-sm-9{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <script>{{ $errors->first('password') }}</script>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                    <label for="password-confirm" class="col-sm-3">Repita Contraseña <strong>*</strong></label>
                    <input id="password-confirm" type="password" class="form-control col-sm-9" name="password_confirmation">
                    </div>
                 </div>

                <div class="form-group">
                    <div class="row">
                      <label for="emp_id" class="col-sm-3">Empresa <strong>*</strong></label>
                      {!! Form::select('emp_id', $empresa, $usuario->empresa_id,['class'=>'form-control col-sm-9', 'required'=>'required', 'onchange' => 'cambiarSubrubro(this)']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="usu_tlf" class="col-sm-3">Teléfono </label>
                      {!! Form::text('usu_tlf', $usuario->user_telefono, ['placeholder'=>'Telefono', 'class'=>'form-control col-sm-9']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="usu_nombre_cargo" class="col-sm-3">Cargo </label>
                      {!! Form::text('usu_nombre_cargo', $usuario->user_nombre_cargo, ['placeholder'=>'Nombre del cargo', 'class'=>'form-control col-sm-9']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <label for="rol_id" class="col-sm-3">Rol <strong>*</strong></label>
                      {!! Form::select('rol_id', $roles, $usuario->rol_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>



                <div class="form-group">
                      <label for="usu_estado">Estado <strong>*</strong></label>
                      {!! Form::checkbox('usu_estado', 1, null, ['class' => 'form-control col-sm-9', 'data-toggle' => 'toggle', 'data-on' => 'Activo', 'data-off' => 'Inactivo', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', ($usuario->usu_estado == 1) ? "checked" : "" , 'data-style' => 'float-right']) !!}
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
