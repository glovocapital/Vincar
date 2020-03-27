@extends('layouts.app')
@section('title','Camiones index')
@section('content')
@include('flash::message')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Remolque</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::open(['route'=> 'remolque.store', 'method'=>'POST', 'files' => true]) !!}

                            <div class="form-group">
                                    <label for="remolque_patente" >Patente <strong>*</strong></label>
                                    {!! Form::text('remolque_patente', null, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="remolque_anio" >Año <strong>*</strong></label>
                                {!! Form::number('remolque_anio', '2020', ['min' => '1980','placeholder'=>'Año', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="remolque_fecha_circulacion" >Permiso de Circulación <strong>*</strong></label>
                                 {!! Form::date('remolque_fecha_circulacion', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="remolque_marca">Marca <strong>*</strong></label>
                                    {!! Form::text('remolque_marca', null, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}

                            </div>

                            <div class="form-group">
                                <label for="empresa_id">Empresa <strong>*</strong></label>
                                {!! Form::select('empresa_id', $empresa, null,['placeholder'=>'Empresa','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="remolque_fecha_revision" >Próxima Revisión <strong>*</strong></label>
                                 {!! Form::date('remolque_fecha_revision', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="remolque_modelo" class="col-sm-3">Modelo<strong>*</strong></label>
                                    {!! Form::text('remolque_modelo', null, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                    <label for="remolque_capacidad" >Capacidad <strong>*</strong></label>
                                    {!! Form::number('remolque_capacidad', '0', ['min' => '0','placeholder'=>'Capaciad', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="">Subir Foto</label>
                                {!! Form::file('remolque_foto_documento'); !!}
                            </div>
                        </div>
                    </div>


                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Remolque ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
                        <h3 class="card-title">Listado de Remolques</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                    <!--   <div class="col-lg-12 pb-3 pt-2">
                                <a href="{{  route('remolque.create') }}" class = 'btn btn-primary'>Crear Camión</a>
                            </div>
                    -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTableCamion" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Patente</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Año</th>
                                    <th>Capacidad</th>
                                    <th>Fecha de circulación</th>
                                    <th>Revisión</th>
                                    <th>Empresa</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($remolque as $p)

                                <tr>
                                    <td><small>{{ $p->remolque_patente }}</small></td>
                                    <td><small>{{ $p->remolque_marca }}</small></td>
                                    <td><small>{{ $p->remolque_modelo }}</small></td>
                                    <td><small>{{ $p->remolque_anio }}</small></td>
                                    <td><small>{{ $p->remolque_capacidad }}</small></td>
                                    <td><small>{{ $p->remolque_fecha_circulacion }}</small></td>
                                    <td><small>{{ $p->remolque_fecha_revision }}</small></td>

                                    <td><small>{{ $p->belongsToEmpresa->empresa_razon_social }}</small></td>

                                    <td><small> <a href="{{route('remolque.download', Crypt::encrypt($p->remolque_id)) }}">Documento</small> </td>

                                    <td>
                                        <small>
                                            <a href="{{ route('remolque.edit', Crypt::encrypt($p->remolque_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                                <a href = "{{ route('remolque.destroy', Crypt::encrypt($p->remolque_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
