@extends('layouts.app')
@section('title','Camiones index')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Camión</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::open(['route'=> 'camiones.store', 'method'=>'POST', 'files' => true]) !!}

                            <div class="form-group">
                                    <label for="camion_patente" >Patente <strong>*</strong></label>
                                    {!! Form::text('camion_patente', null, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="camion_anio" >Año <strong>*</strong></label>
                                {!! Form::number('camion_anio', '2020', ['min' => '1980','placeholder'=>'Año', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="camion_fecha_revision" >Próxima Revisión <strong>*</strong></label>
                                 {!! Form::date('camion_fecha_revision', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="camion_marca">Marca <strong>*</strong></label>
                                    {!! Form::text('camion_marca', null, ['placeholder'=>'Marca', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="empresa_id" >Empresa <strong>*</strong></label>
                                {!! Form::select('empresa_id', $empresa, null,['placeholder'=>'Empresa','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="">Subir Foto</label>
                                {!! Form::file('camion_foto_documento'); !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="camion_modelo">Modelo <strong>*</strong></label>
                                {!! Form::text('camion_modelo', null, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="camion_fecha_circulacion" >Permiso de Circulación <strong>*</strong></label>
                                 {!! Form::date('camion_fecha_circulacion', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Camión ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
                        <h3 class="card-title">Listado de Camiones</h3>
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
                                    <th>Patente</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Año</th>
                                    <th>Fecha de circulación</th>
                                    <th>Revisión</th>
                                    <th>Empresa</th>
                                    <th>Documento</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($camion as $p)

                                <tr>
                                    <td><small>{{ $p->camion_patente }}</small></td>
                                    <td><small>{{ $p->camion_marca }}</small></td>
                                    <td><small>{{ $p->camion_modelo }}</small></td>
                                    <td><small>{{ $p->camion_anio }}</small></td>
                                    <td><small>{{ $p->camion_fecha_circulacion }}</small></td>
                                    <td><small>{{ $p->camion_fecha_revision }}</small></td>

                                    <td><small>{{ $p->belongsToEmpresa->empresa_razon_social }}</small></td>

                                    <td><small> <a href="{{route('camiones.download', Crypt::encrypt($p->camion_id)) }}">Documento</small> </td>

                                    <td>
                                        <small>
                                            <a href="{{ route('camiones.edit', Crypt::encrypt($p->camion_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                                <a href = "{{ route('camiones.destroy', Crypt::encrypt($p->camion_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
