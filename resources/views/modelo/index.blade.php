@extends('layouts.app')
@section('title','Empresa index')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nuevo Modelo</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> 'modelos.store', 'method'=>'POST']) !!}
                        <div class="form-group">
                            <label for="marca_id" >Nombre de la Marca <strong>*</strong></label>
                            {!! Form::select('marca_id', $marca, null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modelo_alias" >Alias </label>
                            {!! Form::text('modelo_alias', null, ['placeholder'=>' Alias', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo_nombre" >Modelo <strong>*</strong></label>
                            {!! Form::text('modelo_nombre', null, ['placeholder'=>'Ingrese Modelo', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo_tipo" >Tipo de Vehiculo <strong>*</strong></label>
                            {!! Form::text('modelo_tipo', null, ['placeholder'=>'Tipo', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                </div>
                <div class="text-right pb-5">
                        {!! Form::submit('Registrar Modelo ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
                          <h3 class="card-title">Listado de Modelos</h3>
                          <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                                <hr class="mb-4">


                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTablePais" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Tipo</th>
                                                <th>Alias</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($modelo as $p)

                                            <tr>
                                                <td><small>{{ $p->BelongsMarca->marca_nombre }}</small></td>
                                                <td><small>{{ $p->modelo_nombre }}</small></td>
                                                <td><small>{{ $p->modelo_tipo }}</small></td>
                                                <td><small>{{ $p->modelo_alias }}</small></td>
                                                <td>
                                                    <small>
                                                        <a href="{{ route('modelos.edit', Crypt::encrypt($p->modelo_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                                    </small>
                                                    <small>
                                                            <a href = "{{ route('modelos.destroy', Crypt::encrypt($p->modelo_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
</div>

@stop



<script>
       $(document).ready(function() {
    $('#dataTablePais').DataTable();
} );
</script>
