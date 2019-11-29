@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Editar Cliente</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                                {!! Form::open(['route'=> ['empresa.update', Crypt::encrypt($empresa->empresa_id)], 'method'=>'PATCH']) !!}

                            <div class="form-group">
                                <label for="empresa_rut" >Rut <strong>*</strong></label>
                                {!! Form::text('empresa_rut', $empresa->empresa_rut, ['placeholder'=>'Rut de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="empresa_direccion" >Dirección <strong>*</strong></label>
                                {!! Form::text('empresa_direccion', $empresa->empresa_direccion, ['placeholder'=>'Dirección de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}

                            </div>

                            <div class="form-group">
                                <label for="empresa_telefono_contacto" >Teléfono </label>
                                {!! Form::text('empresa_telefono_contacto', $empresa->empresa_telefono_contacto, ['placeholder'=>'Número de contacto', 'class'=>'form-control col-sm-9', 'required']) !!}

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="empresa_nombre" >Razón Social <strong>*</strong></label>
                                {!! Form::text('empresa_nombre', $empresa->empresa_razon_social, ['placeholder'=>'Nombre o razón social de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}

                            </div>
                            <div class="form-group">
                                <label for="empresa_giro" >Giro <strong>*</strong></label>
                                {!! Form::text('empresa_giro', $empresa->empresa_giro, ['placeholder'=>'Giro', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="usu_tlf" >Es proveedor? </label>
                                <select name="es_proveedor" onchange="d1(this)" class="form-control col-sm-9">
                                    <option value="0">Seleccionar</option>
                                    <option value="1">Si</option>
                                    <option value='2'>No</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pais_id" >Pais <strong>*</strong></label>
                                {!! Form::select('pais_id', $pais, $empresa->pais_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>

                            <div class="form-group">
                                <label for="empresa_nombre_contacto" >Contacto de la empresa </label>
                                {!! Form::text('empresa_nombre_contacto', $empresa->empresa_nombre_contacto, ['placeholder'=>'Número de contacto', 'class'=>'form-control col-sm-9', 'required']) !!}

                            </div>
                            <div class="form-group" id="bloque_archivo">
                                <label for="empresa_id" >Tipo de proveedor <strong>*</strong></label>
                                {!! Form::select('tipo_proveedor', $tipo_proveedor, $empresa->tipo_proveedor_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>
                        </div>
                    </div>



                <div class="text-center pb-5">
                        {!! Form::submit('Actualizar usuario', ['class' => 'btn btn-primary block full-width m-b']) !!}
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

<!--Funcion para ocultar y mostrar input segun seleccion-->
<script language="javascript" type="text/javascript">
    function d1(selectTag){
    if(selectTag.value == '0')
    {
        $('#bloque_archivo').hide();
        document.getElementById('archivo').disabled = true;
    }else if(selectTag.value == '1')
    {
        $('#bloque_archivo').show();

     document.getElementById('archivo').disabled = false;
    }else if(selectTag.value == '2')
    {
        $('#bloque_archivo').hide();
        document.getElementById('archivo').disabled = true;
    }
    }
    </script>
<!--Fin Funcion para ocultar y mostrar input segun seleccion-->
