@extends('layouts.app')
@section('title','Empresa index')
@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Empresas</li>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h4>Listado de empresas</h4>
            </div>
            <hr class="mb-4">
            <div class="col-lg-12 pb-3 pt-2">
                <a href="{{  route('empresa.create') }}" class = 'btn btn-primary'>Crear nueva Empresa</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                    <thead>
	                    <tr>
	                        <th>Empresa</th>
                            <th>Giro</th>
                            <th>Rut</th>
	                        <th>Dirección</th>
                            <th>Pais</th>
	                        <th>Acci&oacute;n</th>

	                    </tr>
                    </thead>
                    <tbody>
                    @foreach($empresa as $emp)

                        <tr>
                            <td><small>{{ $emp->empresa_razon_social }}</small></td>
                            <td><small>{{ $emp->empresa_giro }}</small></td>
                            <td><small>{{ $emp->empresa_rut }}</small></td>
                            <td><small>{{ $emp->empresa_direccion }}</small></td>
                            <td><small>{{ $emp->onePais->pais_nombre }}</small></td>

                            <td>
                                <small>
                                    <a href="{{ route('empresa.edit', Crypt::encrypt($emp->empresa_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                        <a href = "{{ route('empresa.destroy', Crypt::encrypt($emp->empresa_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
