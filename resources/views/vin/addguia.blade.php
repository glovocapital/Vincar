@extends('layouts.app')
@section('title','Actualizar Registro de VIN')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Agregar Guia Vin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['route'=> ['vin.addguia', Crypt::encrypt($vin->vin_id)], 'method'=>'PATCH', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="guia_fecha" >Fecha de la Guía:</label>
                                {!! Form::date('guia_fecha', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="guia_numero" >Número de Guía:</label>
                                {!! Form::text('guia_numero', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cargar Guia del VIN </label>
                                {!! Form::file('guia_vin', ['id' => 'guia_vin', 'required']); !!}
                                <small>Tamaño máximo de archivo 20 Mb</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Cargar Guia ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        <a href="{{ route('vin.index') }}" class = 'btn btn-success'>Regresar a VIN</a>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
</div>

@stop

@section('local-scripts')
    <script>
        $(document).ready(function () {
            const MAXIMO_TAMANO_BYTES = 20000000; // 1MB = 1 millón de bytes
            const $inputFile = document.querySelector("#guia_vin");

            $inputFile.addEventListener("change", function () {
                // si no hay archivo, regresa
                if (this.files.length <= 0) return;

                // Validar el archivo
                const archivo = this.files[0];
                if (archivo.size > MAXIMO_TAMANO_BYTES) {
                    const tamanoEnMb = MAXIMO_TAMANO_BYTES / 1000000;
                    alert('El máximo tamaño de archivo permitido es ' + tamanoEnMb + ' MB');
                    // Limpiar el formulario
                    $inputFile.value = "";
                    return;
                }
            });
        });
    </script>
@endsection



