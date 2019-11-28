@extends('layouts.app')
@section('title','Creación Destinos')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Crear Destinos</h5>
            </div>

            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> 'destinos.store', 'method'=>'POST']) !!}

                <div class="form-group">
                        <div class="row">
                            <label for="destino_codigo" class="col-sm-3">Código del Destino <strong>*</strong></label>
                            {!! Form::text('destino_codigo', null, ['placeholder'=>'Código del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>


                <div class="form-group">
                    <div class="row">
                        <label for="destino_nombre" class="col-sm-3">Nombre del Destino <strong>*</strong></label>
                        {!! Form::text('destino_nombre', null, ['placeholder'=>'Nombre del destino', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="text-center pb-5">
                    {!! Form::submit('Registrar Destino ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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



