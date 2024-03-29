@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Actualizar Cliente</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::open(['route'=> ['empresa.update', Crypt::encrypt($empresa->empresa_id)], 'method'=>'PATCH']) !!}

                        <label for="user_rut" >Rut <strong>*</strong></label>

                        <div class="input-group">

                            {!! Form::text('empresa_rut', $empresa->empresa_rut, ['placeholder'=>'Rut de la empresa', 'class'=>'form-control col-sm-9 rut', 'required']) !!}

                            <div class="input-group-append">
                                    <span class="input-group-text" id="validador">
                                        <span style="color:red;" aria-hidden="true">&times;</span>
                                    </span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="empresa_nombre" >Razón Social <strong>*</strong></label>
                            {!! Form::text('empresa_nombre', $empresa->empresa_razon_social, ['placeholder'=>'Nombre o razón social de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="pais_id" >Pais <strong>*</strong></label>
                            {!! Form::select('pais_id', $pais, $empresa->pais_id,['class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="empresa_direccion" >Dirección <strong>*</strong></label>
                            {!! Form::text('empresa_direccion', $empresa->empresa_direccion, ['placeholder'=>'Dirección de la empresa', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_telefono_contacto" >Teléfono </label>
                            {!! Form::text('empresa_telefono_contacto', $empresa->empresa_telefono_contacto, ['placeholder'=>'Número de contacto', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_nombre_contacto" >Contacto de la empresa </label>
                            {!! Form::text('empresa_nombre_contacto', $empresa->empresa_nombre_contacto, ['placeholder'=>'Número de contacto', 'class'=>'form-control col-sm-9', 'required']) !!}

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="empresa_giro" >Giro <strong>*</strong></label>
                            {!! Form::text('empresa_giro', $empresa->empresa_giro, ['placeholder'=>'Giro', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa_telefono_contacto" >Email </label>
                            {!! Form::email('empresa_email_contacto', $empresa->empresa_email_contacto, ['placeholder'=>'Email', 'class'=>'form-control col-sm-9']) !!}
                        </div>

                        <div class="form-group">
                            <label for="es_proveedor" >Es proveedor? </label>
                            @if($empresa->empresa_es_proveedor)
                            <label>Sí</label>
                            <input type="radio" name="es_proveedor" id="si_es_proveedor" onchange="d1(this)" value="true" checked />
                            <label>No</label>
                            <input type="radio" name="es_proveedor" id="no_es_proveedor" onchange="d1(this)" value="false" />
                            {{-- {!! Form::radio('es_proveedor', 'true'); !!} --}}
                            @endif
                            @if(!$empresa->empresa_es_proveedor)
                            <label>Sí</label>
                            <input type="radio" name="es_proveedor" id="si_es_proveedor" onchange="d1(this)" value="true" />
                            <label>No</label>
                            <input type="radio" name="es_proveedor" id="no_es_proveedor" onchange="d1(this)" value="false" checked />
                            {{-- {!! Form::radio('es_proveedor', 'false', true); !!} --}}
                            @endif
                        </div>
                        @if(!isset($empresa->tipo_proveedor_id))
                        <div class="form-group" name="bloque" id="bloque_archivo" style="display: none">
                        @else
                        <div class="form-group" name="bloque" id="bloque_archivo">
                        @endif
                            <label for="empresa_id" >Tipo de proveedor <strong>*</strong></label>
                            {!! Form::select('tipo_proveedor', $tipo_proveedor, $empresa->tipo_proveedor_id,['placeholder'=>'Seleccione Tipo de Proveedor', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                </div>



                <div class="text-right pb-5">
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

        <script>
            $(function(){

                $('.rut').keyup(function(){

                    $("#validador").html('<span style="color:red;" aria-hidden="true">&times;</span>');


                    var Ts = $(this).val().split("-");
                    var T = Ts[0];


                    var M=0,S=1;
                    for(;T;T=Math.floor(T/10))
                        S=(S+T%10*(9-M++%6))%11;
                    //return S?S-1:'k';

                    if(Ts[0].length==7 || Ts[0].length==8){

                        if(Ts.length == 2){
                            if(S-1 == Ts[1]){
                                $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                            } else if ((S-1 == -1) && ((Ts[1] == 'K') ||(Ts[1] == 'k'))) {
                                $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                            }
                        }
                    }


                });

                setTimeout(function(){
                    $('.rut').trigger("keyup");
                },1000);


            });

        </script>
@endsection
