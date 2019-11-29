@extends('layouts.app')
@section('title','Destino index')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Destino</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                                {!! Form::open(['route'=> 'destinos.store', 'method'=>'POST']) !!}

                            <div class="form-group">
                                    <label for="destino_codigo" >Código del Destino <strong>*</strong></label>
                                    {!! Form::text('destino_codigo', null, ['placeholder'=>'Código del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="destino_nombre" >Nombre del Destino <strong>*</strong></label>
                                    {!! Form::text('destino_nombre', null, ['placeholder'=>'Nombre del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="text-right pb-5">
                            {!! Form::submit('Registrar Destino ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
               <!-- <a href="{{  route('destinos.create') }}" class = 'btn btn-primary'>Crear Destino</a> -->


            <div class="table-responsive">
                <table class="table table-hover" id="dataTableDestino" width="100%" cellspacing="0">
                    <thead>
	                    <tr>
                            <th>Código</th>
	                        <th>Nombre</th>
	                    </tr>
                    </thead>
                    <tbody>
                    @foreach($destino as $p)

                        <tr>
                            <td><small>{{ $p->destino_codigo }}</small></td>
                            <td><small>{{ $p->destino_nombre }}</small></td>

                            <td>
                                <small>
                                    <a href="{{ route('destinos.edit', Crypt::encrypt($p->destino_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                </small>
                                <small>
                                        <a href = "{{ route('destinos.destroy', Crypt::encrypt($p->destino_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
    $('#dataTableDestino').DataTable();
} );
</script>
