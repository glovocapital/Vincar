@extends('layouts.app')
@section('title','Usuarios index')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h4>Listado de Usuarios</h4>
            </div>
            <hr class="mb-4">
            <div class="col-lg-12 pb-3 pt-2">
                <a href="{{ route('usuarios.create') }}" class = 'btn btn-primary'>Crear nuevo Usuario</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
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
@stop
