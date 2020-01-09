@extends('layouts.app')
@section('title','Empresa index')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Nuevo Cliente</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> 'empresa.store', 'method'=>'POST']) !!}

                        <div class="form-group">
                            <label for="empresa_rut" >Rut <strong>*</strong></label>
                            {!! Form::text('empresa_rut', null, ['placeholder'=>'Rut del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_nombre" >Razón Social <strong>*</strong></label>
                            {!! Form::text('empresa_nombre', null, ['placeholder'=>'Nombre o Razón Social', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="pais_id" >Pais <strong>*</strong></label>
                            {!! Form::select('pais_id', $pais, null,['placeholder'=>'Seleccionar País', 'class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="empresa_direccion" >Dirección <strong>*</strong></label>
                            {!! Form::text('empresa_direccion', null, ['placeholder'=>'Dirección', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_telefono_contacto" >Teléfono </label>
                            {!! Form::text('empresa_telefono_contacto', null, ['placeholder'=>'Telefono', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_nombre_contacto" >Contacto de la empresa </label>
                            {!! Form::text('empresa_nombre_contacto', null, ['placeholder'=>'Nombre de contacto', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="empresa_giro" >Giro <strong>*</strong></label>
                            {!! Form::text('empresa_giro', null, ['placeholder'=>'Giro de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_telefono_contacto" >Email </label>
                            {!! Form::email('empresa_email_contacto', null, ['placeholder'=>'Email', 'class'=>'form-control col-sm-9']) !!}
                        </div>





                        <div class="form-group">
                            <label for="es_proveedor" >Es proveedor? </label>
                            <label>Sí</label>
                            <input type="radio" name="es_proveedor" id="si_es_proveedor" onchange="d1(this)" value="true" />
                            {{-- {!! Form::radio('es_proveedor', 'true'); !!} --}}
                            <label>No</label>
                            <input type="radio" name="es_proveedor" id="no_es_proveedor" onchange="d1(this)" value="false" checked />
                            {{-- {!! Form::radio('es_proveedor', 'false', true); !!} --}}
                        </div>

                        <div class="form-group" name="bloque" id="bloque_archivo" style="display: none">
                            <label for="tipo_proveedor_id" >Tipo de proveedor <strong>*</strong></label>
                            {!! Form::select('tipo_proveedor_id', $tipo_proveedor, null,['placeholder'=>'Seleccione Tipo de Proveedor', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                </div>
<br />

                <div class="text-right pb-5">
                        {!! Form::submit('Registrar Empresa ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
                <h3 class="card-title">Listado de empresas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
            <!--   <div class="col-lg-12 pb-3 pt-2">
                        <a href="{{  route('empresa.create') }}" class = 'btn btn-primary'>Crear nueva Empresa</a>
                    </div>
            -->
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableAusentismo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Giro</th>
                                <th>Rut</th>
                                <th>Dirección</th>
                                <th>Pais</th>
                                <th>Teléfono</th>
                                <th>Email</th>
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
                                <td><small>{{ $emp->empresa_telefono_contacto }}</small></td>
                                <td><small>{{ $emp->empresa_email_contacto }}</small></td>

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
</div>



@stop


<!--Funcion para ocultar y mostrar input segun seleccion-->
@section('local-scripts')
<script language="javascript" type="text/javascript">

    function d1(button){
        if(button.value == 'false')
        {
            $('#bloque_archivo').hide();
            // document.getElementById('archivo').disabled = true;
        }else if(button.value == 'true')
        {
            $('#bloque_archivo').show();

        // document.getElementById('archivo').disabled = false;
        }
    }
    </script>
<!--Fin Funcion para ocultar y mostrar input segun seleccion-->
@endsection
