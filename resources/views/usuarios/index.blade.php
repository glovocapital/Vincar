@extends('layouts.app')
@section('title','Usuarios index')
@section('content')
@include('flash::message')

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Usuario</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::open(['route'=> 'usuarios.store', 'method'=>'POST']) !!}

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

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar usuario', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Listado de empresas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>
                <div class="card-body overflow-auto">
                <!--   <div class="col-lg-12 pb-3 pt-2">
                            <a href="{{ route('usuarios.create') }}" class = 'btn btn-primary'>Crear nuevo Usuario</a>
                        </div>
                -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableAusentismo" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Nombres</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Empresa</th>
                                <th>Acci&oacute;n</th>
                                <!-- <th>Desactivar</th>  -->
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($usuarios as $us)
                                <tr>
                                    <td><small>{{ $us->user_nombre . ' ' . $us->user_apellido }}</small></td>
                                    <td><small>{{ $us->email }}</small></td>
                                    <td><small>{{ $us->oneRol->rol_desc }}</small></td>
                                    <td><small>{{ $us->belongsToEmpresa->empresa_razon_social }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('usuarios.edit',  Crypt::encrypt($us->user_id)) }}" class="btn-empresa"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('usuarios.destroy', Crypt::encrypt($us->user_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
                                            </a>
                                        </small>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('local-scripts')
    <script>
        $(function(){
            $('.rut').keyup(function(){
                $("#validador").html('<span style="color:red;" aria-hidden="true">&times;</span>');
                var Ts = $(this).val().split("-");
                var T = Ts[0];

                var M=0,S=1;
                for(;T;T=Math.floor(T/10))
                    S=(S+T%10*(9-M++%6))%11;

                if(Ts[0].length==7 || Ts[0].length==8){

                    if(Ts.length == 2){
                        if(S-1 == Ts[1]){
                            $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                        } else if ((S-1 == -1) && ((Ts[1] == 'K') ||(Ts[1] == 'k'))) {
                            $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                        }
                    }
                }
            });

            setTimeout(function(){
                $('.rut').trigger("keyup");
            },1000);
        });

    </script>
@endsection
