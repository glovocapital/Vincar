@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Editar usuario</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> ['empresa.update', Crypt::encrypt($empresa->empresa_id)], 'method'=>'PATCH']) !!}

                <div class="form-group">
                    <div class="row">
                        <label for="empresa_rut" class="col-sm-3">Rut de la Empresa <strong>*</strong></label>
                        {!! Form::text('empresa_rut', $empresa->empresa_rut, ['placeholder'=>'Rut de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                            <label for="empresa_nombre" class="col-sm-3">Razón Social <strong>*</strong></label>
                      {!! Form::text('empresa_nombre', $empresa->empresa_razon_social, ['placeholder'=>'Nombre o razón social de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="empresa_giro" class="col-sm-3">Rubro o giro de la empresa <strong>*</strong></label>
                            {!! Form::text('empresa_giro', $empresa->empresa_giro, ['placeholder'=>'Rut del usuario', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="empresa_direccion" class="col-sm-3">Dirección <strong>*</strong></label>
                        {!! Form::text('empresa_direccion', $empresa->empresa_direccion, ['placeholder'=>'Dirección de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="pais_id" class="col-sm-3">Pais <strong>*</strong></label>
                        {!! Form::select('pais_id', $pais, $empresa->pais_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="empresa_telefono_contacto" class="col-sm-3">Número de contacto <strong>*</strong></label>
                        {!! Form::text('empresa_telefono_contacto', $empresa->empresa_telefono_contacto, ['placeholder'=>'Número de contacto', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                            <label for="empresa_nombre_contacto" class="col-sm-3">Nombre de contacto <strong>*</strong></label>
                            {!! Form::text('empresa_nombre_contacto', $empresa->empresa_nombre_contacto, ['placeholder'=>'Número de contacto', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                        <div class="row">
                            <label for="usu_tlf" class="col-sm-3">Es proveedor? </label>
                            <select name="es_proveedor" onchange="d1(this)" class="form-control col-sm-9">
                                <option value="0">Seleccionar</option>
                                <option value="1">Si</option>
                                <option value='2'>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="bloque_archivo">
                        <div class="row">
                            <label for="empresa_id" class="col-sm-3">Tipo de proveedor <strong>*</strong></label>
                            {!! Form::select('tipo_proveedor', $tipo_proveedor, $empresa->tipo_proveedor_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
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
