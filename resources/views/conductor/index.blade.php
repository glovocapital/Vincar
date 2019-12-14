@extends('layouts.app')
@section('title','Conductores index')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Conductor</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::open(['route'=> 'conductores.store', 'method'=>'POST', 'files' => true]) !!}

                            <div class="form-group">
                                <label for="user_id" >Conductor <strong>*</strong></label>
                                {!! Form::select('user_id', $usuario, null,['placeholder'=>'Seleccione','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="">Foto Documentos <strong>*</strong></label>
                                {!! Form::file('conductor_foto_documento'); !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_licencia_id" >Tipo de Licencia <strong>*</strong></label>
                                {!! Form::select('tipo_licencia_id', $tipo_licencia, null,['placeholder'=>'Seleccione','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="conductor_fecha_vencimiento" >Fecha de vencimiento <strong>*</strong></label>
                                 {!! Form::date('conductor_fecha_vencimiento', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Conductor ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
                        <h3 class="card-title">Listado de Conductores</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                    <!--   <div class="col-lg-12 pb-3 pt-2">
                                <a href="{{  route('camiones.create') }}" class = 'btn btn-primary'>Crear Camión</a>
                            </div>
                    -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTableCamion" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Conductor</th>
                                    <th>Tipo de Licencia</th>
                                    <th>Fecha de vencimiento</th>
                                    <th>Documento</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($conductor as $p)
                            <tr>
                                <td><small>{{ $p->belongstoUser->user_nombre }} {{ $p->belongstoUser->user_apellido }}</small></td>
                                <td><small>{{ $p->oneLicencia->tipo_licencia_nombre }}</small></td>
                                <td><small>{{ $p->conductor_fecha_vencimiento }}</small></td>

                                <td><small> <a href="{{route('conductores.download', Crypt::encrypt($p->conductor_id)) }}">Documento</small> </td>
                                    <td>
                                        <small>
                                            <a href="{{ route('conductores.edit', Crypt::encrypt($p->conductor_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                                <a href = "{{ route('conductores.destroy', Crypt::encrypt($p->conductor_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@stop

<script>
       $(document).ready(function() {
    $('#dataTableCamion').DataTable();
} );
</script>
