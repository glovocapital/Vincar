@extends('layouts.app')
@section('title','Camiones Editar')
@section('content')


<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Editar Camiones</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            {!! Form::open(['route'=> ['camiones.update', Crypt::encrypt($camion->camion_id)], 'method'=>'PATCH', 'files' => true]) !!}

                            <div class="form-group">
                                <label for="camion_patente" >Patente <strong>*</strong></label>
                                {!! Form::text('camion_patente', $camion->camion_patente, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="camion_anio" >Año <strong>*</strong></label>
                                {!! Form::number('camion_anio', $camion->camion_anio, ['placeholder'=>'Año ', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="camion_fecha_revision" >Próxima Revisión <strong>*</strong></label>
                                {!! Form::date('camion_fecha_revision', $camion->camion_fecha_revision, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="marca_id" >Marca <strong>*</strong></label>
                                <select name="marca_id" id="marca_id" class="form-control">
                                    <option value="">Marca</option>
                                    @foreach ($marcas as $marca_id => $marca_nombre)
                                        <option value="{{ $marca_id }}" {{ old('marca_id', $camion->camion_marca) == $marca_id ? ' selected' : '' }}>
                                            {{ ucwords($marca_nombre) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="empresa_id" >Empresa <strong>*</strong></label>
                                {!! Form::select('empresa_id', $empresas, $camion->empresa_id, ['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="">Subir Foto</label>
                                {!! Form::file('camion_foto_documento',$camion->camion_foto_documento); !!}
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="camion_modelo" >Modelo <strong>*</strong></label>
                                {!! Form::text('camion_modelo', $camion->camion_modelo, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="camion_fecha_circulacion" >Permiso de Circulación <strong>*</strong></label>
                                 {!! Form::date('camion_fecha_circulacion', $camion->camion_fecha_circulacion, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Actualizar Camión', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
