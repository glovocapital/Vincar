@extends('layouts.app')
@section('title','Agregar Rutas')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Agregar Guías y Rutas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">

                {!! Form::open(['route'=>'tour.crearutas', 'method'=>'POST', 'files' => true]) !!}

                <div class="row">
                    {!! Form::hidden('id_tour', $id_tour ) !!}

                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                            <label for="guia_fecha" >De:</label>
                            {!! Form::date('guia_fecha', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                        <div class="form-group">
                                <label for="vin_numero" >Vin <strong>*</strong></label>
                                {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control"]) !!}
                        </div>
                    </div>
                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                            <label for="origen" >Fecha de la Guia:</label>
                            {!! Form::text('origen', null, ['placeholder'=>'Dirección de origen', 'id' => 'search_term', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label for="destino" >A:</label>
                            {!! Form::text('destino', null, ['placeholder'=>'Dirección de destino', 'id' => 'search_term2', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                            <label for="empresa_id" >Cliente <strong> *</strong></label>
                                {!! Form::select('empresa_id', $empresas, null,['id' => 'empresa_id', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente' , 'required']) !!}
                            </div>
                        <div class="form-group">
                            <label for="">Cargar Guia</label>
                            {!! Form::file('guia_ruta'); !!}
                        </div>
                    </div>

                </div>

                <div class="text-right pb-5">

                    {!! Form::submit('Agregar Ruta', ['class' => 'btn btn-success block full-width m-b']) !!}
                    {!! Form::close() !!}
                    <a type="button" href="{{route('tour.index')}}" class = 'btn btn-success'>Regresar a Tours</a>
                </div>




            </div>
        </div>
    </div>
</div>




@stop

@section('local-scripts')
<script type="text/javascript">
function activatePlacesSearch () {
    var input = document.getElementById('search_term');
    var autocomplete = new google.maps.places.Autocomplete(input);

    var input2 = document.getElementById('search_term2');
    var autocomplete2 = new google.maps.places.Autocomplete(input2);
};
</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuv9D5qlHfGojPTq0-XCbV_errPQh_wFg&libraries=places&callback=activatePlacesSearch"></script>

@endsection