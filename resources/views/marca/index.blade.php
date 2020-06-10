@extends('layouts.app')
@section('title','Empresa index')
@section('content')
@include('flash::message')

    <div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nueva Marca</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> 'marcas.store', 'method'=>'POST','files' => true]) !!}
                        <div class="form-group">

                                <label for="marca_nombre" >Nombre de la marca <strong>*</strong></label>
                                {!! Form::text('marca_nombre', null, ['placeholder'=>'Nombre', 'class'=>'form-control col-sm-9', 'required']) !!}

                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label for="marca_nombre" >Código de la marca</label>
                            {!! Form::text('marca_codigo', null, ['placeholder'=>'Código', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Logo de la marca (Extensión SVG) </label>
                            {!! Form::file('logo_marca') !!}
                        </div>
                    </div>

                </div>

                <div class="text-right pb-5">
                        {!! Form::submit('Agregar Marca ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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




    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
                <div class="card card-default">
                        <div class="card-header">
                          <h3 class="card-title">Listado de Marcas</h3>
                          <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTablePais" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Logo</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($marca_vehiculo as $p)

                                            <tr>
                                                <td><small>{{ $p->marca_codigo }}</small></td>
                                                <td><small>{{ $p->marca_nombre }}</small></td>
                                                <td style="text-align: center"><small>
                                                        @if ($p->img!="")
                                                        <img height='30px' width="30px" src='{{ $p->img }}'/>
                                                        @endif
                                                    </small></td>

                                                <td>
                                                    <small>
                                                        <a href="{{ route('marcas.edit', Crypt::encrypt($p->marca_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                                    </small>
                                                    <small>
                                                            <a href = "{{ route('marcas.destroy', Crypt::encrypt($p->marca_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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

