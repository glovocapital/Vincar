@extends('layouts.app')
@section('title','Carga de patios')
@section('content')

<!-- Vista de la carga de archivos-->

<div class="container">

    <div class="row">
            <div class="col-md-12 col-md-offset-1">
                <div class="card card-default">

                    <div class="card-header">
                        <h3 class="card-title">Agregar patios masivamente</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action={!!url("patio/store_patios")!!} accept-charset="UTF-8" enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">Nuevo Archivo</label>
                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="file" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

@section('local-scripts')
@endsection