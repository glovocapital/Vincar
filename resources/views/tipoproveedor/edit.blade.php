@extends('layouts.app')
@section('title','Empresa Editar')
@section('content')

        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Editar Tipo de proveedores</h5>
            </div>
            <hr class="mb-4">
            <div class="ibox-content col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 mt-4">
                {!! Form::open(['route'=> ['proveedor.update', Crypt::encrypt($proveedor->tipo_proveedor_id)], 'method'=>'PATCH']) !!}

                <div class="form-group">
                    <div class="row">
                        <label for="tipo_proveedor_desc" class="col-sm-3">Nombre del Tipo de Proveedor <strong>*</strong></label>
                        {!! Form::text('tipo_proveedor_desc', $proveedor->tipo_proveedor_desc, ['placeholder'=>'Nombre del país', 'class'=>'form-control col-sm-9', 'required']) !!}
                    </div>
                </div>

                <div class="text-center pb-5">
                        {!! Form::submit('Actualizar Tipo de proveedor', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>


                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
        </div>
@stop
