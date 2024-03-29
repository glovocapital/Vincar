@extends('layouts.app')
@section('title','Agregar Rutas')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Agregar Rutas al Tour</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                {!! Form::open(['route'=>'tour.crearutas', 'method'=>'POST', 'files' => true]) !!}

                <div class="row">
                    {!! Form::hidden('id_tour', $tour_id ) !!}

                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                                <label for="vin_numero" >Vin <strong>*</strong></label>
                                {!! Form::textarea('vin_numero', null, ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control"]) !!}
                        </div>
                        <div class="form-group">
                            <label for="empresa_id" >Cliente <strong> *</strong></label>
                                {!! Form::select('empresa_id', $empresas, null,['id' => 'empresa_id', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente' , 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                            <label for="origen" >De:</label>
                            {!! Form::text('ruta_origen', null, ['placeholder'=>'Dirección de origen', 'id' => 'search_term', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label for="destino" >A:</label>
                            {!! Form::text('ruta_destino', null, ['placeholder'=>'Dirección de destino', 'id' => 'search_term2', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4" id="wrapper_2">
                        <div class="form-group">
                            <label for="guia_fecha" >Fecha de la Guía:</label>
                            {!! Form::date('guia_fecha', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label for="guia_numero" >Número de Guía:</label>
                            {!! Form::text('guia_numero', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                </div>

                <div class="text-right pb-5">

                    {!! Form::submit('Agregar Ruta', ['class' => 'btn btn-success block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Modificar Rutas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                {!! Form::open(['route'=> ['tour.update_rutas', Crypt::encrypt($tour_id)], 'method'=>'PATCH', 'files' => true]) !!}

                <div class="row">
                    @php($i = 0)
                    @foreach ($vins_guia_array as $vg)
                        {!! Form::hidden('ruta_id[' . $i . ']', $vg[2]) !!}
                        {!! Form::hidden('guia_id[' . $i . ']', $vg[6]) !!}
                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="vin_numero" >Vin <strong>*</strong></label>
                                {!! Form::textarea('vin_numero['. $i .']', $vg[3], ['placeholder'=>'Ingrese VINS', 'id' => 'vin_numero', 'rows' => 4, 'class'=>"form-control", 'title' => 'Sin información de VINs se elimina esta ruta-guía']) !!}
                            </div>
                            <div class="form-group">
                                <label for="empresa_id" >Cliente <strong> *</strong></label>
                                    {!! Form::select('empresa_id[' . $i . ']', $empresas, $vg[0],['id' => 'empresa_id', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-9 select-cliente' , 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="marca_nombre" >De:</label>
                                {!! Form::text('ruta_origen['. $i .']', $vg[1][0], ['placeholder'=>'Nombre', 'class'=>'form-control col-sm-9', 'disabled']) !!}
                                {!! Form::hidden('ruta_origen['. $i .']', $vg[1][0]) !!}
                            </div>
                            <div class="form-group">
                                <label for="marca_nombre" >A:</label>
                                {!! Form::text('ruta_destino['. $i .']', $vg[1][1], ['placeholder'=>'Nombre', 'class'=>'form-control col-sm-9', 'disabled']) !!}
                                {!! Form::hidden('ruta_destino['. $i .']', $vg[1][1]) !!}
                            </div>
                        </div>

                        <div class="col-md-4" id="wrapper_2">
                            <div class="form-group">
                                <label for="guia_fecha" >Fecha de la Guía:</label>
                                {!! Form::date('guia_fecha[' . $i . ']', $vg[5], ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="guia_numero" >Número de Guía:</label>
                                {!! Form::text('guia_numero[' . $i . ']', $vg[4], ['class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>
                        @php($i++)
                        <div class="col-md-12" >
                            <hr />
                        </div>
                    @endforeach
                </div>

                <div class="text-right pb-5">
                {!! Form::submit('Actualizar Rutas ', ['class' => 'btn btn-success block full-width m-b']) !!}
                {!! Form::close() !!}
                    <a type="button" href="{{route('tour.tour')}}" class="btn btn-primary block full-width m-b">Regresar</a>
                </div>

                <div class="text-right pb-5">


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

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.key') }}&libraries=places&callback=activatePlacesSearch"></script>

@endsection
