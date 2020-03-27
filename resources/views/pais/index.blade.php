@extends('layouts.app')
@section('title','Empresa index')
@section('content')
@include('flash::message')

    <div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Creación de Paises</h3>

            </div>
            <div class="card-body">

                <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                    {!! Form::open(['route'=> 'pais.store', 'method'=>'POST']) !!}
                    <div class="form-group">
                        <div class="row">
                            <label for="pais_nombre" class="col-sm-3">Nombre del País <strong>*</strong></label>
                            {!! Form::text('pais_nombre', null, ['placeholder'=>'Nombre del País', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar País ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
                <div class="card card-default">
                        <div class="card-header">
                          <h3 class="card-title">Listado de Paises</h3>
                          <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                                <hr class="mb-4">
                            <!--    <div class="col-lg-12 pb-3 pt-2">
                                    <a href="{{  route('pais.create') }}" class = 'btn btn-primary'>Crear Pais</a>
                                </div>
                            -->

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTablePais" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pais as $p)

                                            <tr>
                                                <td><small>{{ $p->pais_nombre }}</small></td>
                                                <td>
                                                    <small>
                                                        <a href="{{ route('pais.edit', Crypt::encrypt($p->pais_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                                    </small>
                                                    <small>
                                                            <a href = "{{ route('pais.destroy', Crypt::encrypt($p->pais_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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

