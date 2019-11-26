@extends('layouts.app')
@section('title','Usuario Crear')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Crear usuario</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> 'usuarios.store', 'method'=>'POST']) !!}
                <div class="form-group">
                    <div class="row">
                        <label for="user_nombre" class="col-sm-3">Nombre del usuario <strong>*</strong></label>
                        {!! Form::text('user_nombre', null, ['placeholder'=>'Nombre del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="user_nombre" class="col-sm-3">Apellido del usuario <strong>*</strong></label>
                        {!! Form::text('user_apellido', null, ['placeholder'=>'Apellido del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="user_rut" class="col-sm-3">Rut del usuario <strong>*</strong></label>
                            {!! Form::text('user_rut', null, ['placeholder'=>'Rut del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>



                <div class="form-group">
                    <div class="row">
                        <label for="user_email" class="col-sm-3">Email <strong>*</strong></label>
                        {!! Form::text('user_email', old('email'), ['class'=>'form-control col-sm-9', 'placeholder'=>'Email']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="user_pass" class="col-sm-3">Contraseña <strong>*</strong></label>
                        {{ Form::password('user_pass',array('placeholder'=>'Contraseña','class' => 'form-control col-sm-9', 'pattern' => '^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$' ,'required')) }}
                    </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <label for="user_pass_rep" class="col-sm-3">Repita Contraseña <strong>*</strong></label>
                        {{ Form::password('user_pass_confirmation',array('placeholder'=>'Repita la contraseña','class' => 'form-control col-sm-9', 'pattern' => '^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$' ,'required')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="rol_id" class="col-sm-3">Rol <strong>*</strong></label>
                       {!! Form::select('rol_id', $roles, null,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="empresa_id" class="col-sm-3">Empresa <strong>*</strong></label>
                        {!! Form::select('empresa_id', $empresa, null,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="user_telefono" class="col-sm-3">Teléfono </label>
                        {!! Form::text('user_telefono', null, ['placeholder'=>'Telefono', 'class'=>'form-control col-sm-9']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="user_cargo" class="col-sm-3">Cargo del usuario <strong>*</strong></label>
                        {!! Form::text('user_cargo', null, ['placeholder'=>'Cargo del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
            </div>







                <div class="text-center pb-5">
                    {!! Form::submit('Registrar usuarios', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop



