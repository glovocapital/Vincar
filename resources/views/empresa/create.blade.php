@extends('layouts.app')
@section('title','Creación Empresas')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Crear Empresas</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> 'empresas.store', 'method'=>'POST']) !!}


                <div class="form-group">
                    <div class="row">
                        <label for="empresa_rut" class="col-sm-3">Rut de la Empresa <strong>*</strong></label>
                        {!! Form::text('empresa_rut', null, ['placeholder'=>'Rut del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
            </div>

                <div class="form-group">
                    <div class="row">
                        <label for="empresa_nombre" class="col-sm-3">Razón Social <strong>*</strong></label>
                        {!! Form::text('empresa_nombre', null, ['placeholder'=>'Nombre o Razón Social', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="usu_nombre" class="col-sm-3">Rubro o giro de la empresa <strong>*</strong></label>
                        {!! Form::text('empresa_giro', null, ['placeholder'=>'Apellido del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>


                <div class="form-group">
                        <div class="row">
                            <label for="usu_direccion" class="col-sm-3">Dirección <strong>*</strong></label>
                            {!! Form::text('empresa_direccion', null, ['placeholder'=>'Dirección', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <label for="usu_email" class="col-sm-3">Email <strong>*</strong></label>
                        {!! Form::text('usu_email', old('email'), ['class'=>'form-control col-sm-9', 'placeholder'=>'Email']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="usu_pass" class="col-sm-3">Contraseña <strong>*</strong></label>
                        {{ Form::password('usu_pass',array('placeholder'=>'Contraseña','class' => 'form-control col-sm-9', 'pattern' => '^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$' ,'required')) }}
                    </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <label for="usu_pass_rep" class="col-sm-3">Repita Contraseña <strong>*</strong></label>
                        {{ Form::password('usu_pass_confirmation',array('placeholder'=>'Repita la contraseña','class' => 'form-control col-sm-9', 'pattern' => '^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$' ,'required')) }}
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
                        <label for="usu_tlf" class="col-sm-3">Teléfono </label>
                        {!! Form::text('usu_tlf', null, ['placeholder'=>'Telefono', 'class'=>'form-control col-sm-9']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="usu_nombre_cargo" class="col-sm-3">Cargo </label>
                        {!! Form::text('usu_nombre_cargo', null, ['placeholder'=>'Nombre del cargo', 'class'=>'form-control col-sm-9']) !!}
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



