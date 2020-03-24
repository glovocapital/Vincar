@extends('layouts.app')
@section('title','Tour index')
@section('content')

    <div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nuevo Tour</h3>

            </div>
            <div class="card-body">

                    {!! Form::open(['route'=> 'pais.store', 'method'=>'POST']) !!}
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-4" >

                                <div class="form-group">
                                    <label for="estado_nombre" >Cliente <strong> *</strong></label>
                                    {!! Form::select('cliente_id', $empresas, null,['id' => 'cliente_id', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="user_id" >Remolque<strong> *</strong></label>
                                    {!! Form::select('patio_id', $remolque, null,['id' => 'patio', 'placeholder'=>'Remolque', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>
                            </div>


                            <div class="col-md-4" id="wrapper_2">
                                <div class="form-group">

                                        <label for="user_id" >Proveedor de Transporte <strong> *</strong></label>
                                        {!! Form::select('patio_id', $transporte, null,['id' => 'proveedor_id', 'placeholder'=>'Proveedor de Transporte', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="camion_id" ><strong> Conductor *</strong></label>
                                    {!! Form::select('camion_id', $users, null,['id' => 'camion', 'placeholder'=>'Conductor', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>

                            </div>

                            <div class="col-md-4" id="wrapper_2">

                                <div class="form-group">
                                    <label for="camion_id" >Camión<strong> *</strong></label>
                                    {!! Form::select('camion_id', $camion, null,['id' => 'camion', 'placeholder'=>'Camión', 'class'=>'form-control col-sm-9 select-cliente']) !!}
                            </div>
                        </div>


                        </div>
                    </div>
                    <div class="text-right pb-5">
                        {!! Form::submit('Agregar Rutas ', ['class' => 'btn btn-success block full-width m-b']) !!}
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
                          <h3 class="card-title">Listado de Tours</h3>
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
{{--
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
                                                    </small>  -->
                                                </td>

                                            </tr>

                                        @endforeach
                                        </tbody>
--}}
                                    </table>

                                </div>

                        </div>
                </div>
        </div>
    </div>
</div>

@stop

