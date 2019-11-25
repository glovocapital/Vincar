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
                            <td><small>{{ $us->name . ' ' . $us->usu_apellido }}</small></td>
                            <td><small>{{ $us->email }}</small></td>
                            <td><small>{{ $us->oneRol->rol_desc }}</small></td>
                            <td><small>{{ $us->oneEmpresa->empresa_razon_social }}</small></td>


                            <td>
                                <small>
                                    <a href="{{ route('usuarios.edit',  Crypt::encrypt($us->id)) }}" class="btn-empresa"><i class="far fa-edit"></i></a>
                                </small>



                            </td>
                            <!--
                            <td>
                                <div class="center">
                                    <input type="checkbox" {{ ($us->usu_estado == 1) ? "checked" : "" }} data-toggle="toggle" data-on="Activo" data-off="Inactivo" data-onstyle="success" data-offstyle="danger" h="{{ $us->id }}" value="{{ $us->usu_estado }}" class="desactivarUsuario">

                                </div>

                            </td>
                            -->
                        </tr>

                    @endforeach
                    </tbody>
                </table>

            </div>
            </div>
        </div>
    </div>
@stop
