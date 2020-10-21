@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <span class="badge badge-info float-right">Transporte</span>
                                <h5 class="card-title mb-0">Transporte</h5>
                            </div>
                            <div class="card-body my-2">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-3 col-xs-12">
                                        <div class="stat-circle">
                                            <h3 id="Total" style="color: #55ae90;;">0</h3>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                                <circle style="fill:none;stroke:#55ae90;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                            </svg>
                                            <h5>Fuera de horario</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-3 col-xs-12">
                                        <div class="stat-circle">
                                            <h3 id="Pendiente" style="color: #808080;;">0</h3>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                                <circle style="fill:none;stroke:#808080;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                            </svg>
                                            <h5>Tiempos</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-3 col-xs-12">
                                        <div class="stat-circle">
                                            <h3 id="Gestionados" style="color: #60d356;;">0</h3>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                                <circle style="fill:none;stroke:#60d356;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                            </svg>
                                            <h5>Flotas Activas</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-3 col-xs-12">
                                        <div class="stat-circle">
                                            <h3 id="Rechazados" style="color: #ea3b3b;;">0</h3>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                                <circle style="fill:none;stroke:#ea3b3b;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                            </svg>
                                            <h5>Entregas Aceptadas</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-12 col-md-12">
                <div class="row">
                    <div class="col-lg-8 col-xl-8 d-flex">
                        <div class="card" style="width: 100%">
                            <div class="card-body" style="padding: 0%" >
                                <iframe
                                    id="iframe-ruta"
                                    width="100%"
                                    height="600"
                                    frameborder="0" style="border:0"
                                    src="https://www.google.com/maps/embed/v1/directions?origin=undefined&destination=undefined&key={{ config('googlemaps.GOOGLE_MAPS_API_KEY') }}" allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4 col-md-4 d-flex">
                        <div class="card flex-fill w-100" style="font-size: 10px">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        <a  data-display="static">
                                            <i class="align-middle" data-feather="clock"></i>
                                        </a>


                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Status de Entrega</h5>
                            </div>

                            <div class="card-body d-flex">

                                    <table width="100%" class="table table-hover mb-0 borderless">

                                        <tbody>
                                        @if (count($rutas) == 0)
                                        <tr>
                                            <td class="text-center">
                                                <h3>No existen rutas activas.</h3>
                                            </td>
                                        </tr>
                                        @else
                                            @foreach($rutas as $ruta)
                                            <tr class="mt-5 mb-5">
                                                <td class="item-ruta" value="{{ $ruta->ruta_id }}">Ruta: {{ $ruta->ruta_origen }} - {{ $ruta->ruta_destino }}
                                                    <input type="hidden" id="ruta_origen-{{ $ruta->ruta_id }}" value="{{ $ruta->ruta_origen }}" />
                                                    <input type="hidden" id="ruta_destino-{{ $ruta->ruta_id }}" value="{{ $ruta->ruta_destino }}" />
                                                    <div style=" position: relative;">
                                                        <div class="progress progress-sm shadow-sm mb-1" style="background: #ddd; position: relative;">
                                                        @if (!$ruta->ruta_finalizada)
                                                            <div id="rutas_progress" class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                                        @else
                                                            <div id="rutas_progress" class="progress-bar bg-danger" role="progressbar" style="width: 100%"></div>
                                                        @endif
                                                        </div>
                                                        <div style="position: absolute; top: -3px; right: 0px; ">
                                                            <span style="font-size: 14px; z-index: 0" class="fa fa-check-circle bg-cyan"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('local-scripts')

    <style>
        .stat-circle {
            width: 90px;
            height: 90px;
            position: relative;
            float: left;
            margin-top: 8px;
            padding: 3px;

        }

        .stat-circle h3 {
            position: absolute;
            font-size: 20px;
            line-height: 88px;
            text-align: center;
            width: 100%;
            font-weight: 100;
            left:0px;
        }

        .stat-circle h5 {
            text-align: center;
        }
    </style>


    <script>
        $(function(){



            $.ajax({
                url: '{{ route('home.dashboard') }}',
                type: 'GET',
                dataType: 'json', // added data type
                success: function(res) {
                    console.log(res);
                    $("#rutas_progress").width(res.rutas+"%");
                    $("#DyP_progress").width(res.DyP+"%");
                    $("#Lavados_progress").width(res.Lavados+"%");
                    $("#Carga_progress").width(res.Carga+"%");

                    $("#Total").text(res.Total);
                    $("#Pendiente").text(res.Pendiente);
                    $("#Gestionados").text(res.Gestionados);
                    $("#Rechazados").text(res.Rechazados);


                    $("#Total_Recibido").text(res.Total_Recibido.Cantidad);
                    $("#Total_Recibido_por").text(res.Total_Recibido.Porcentaje+"%");
                    $("#Total_Recibido_progress").width(res.Total_Recibido.Porcentaje+"%");

                    $("#Total_Salidas").text(res.Total_Salidas.Cantidad);
                    $("#Total_Salidas_por").text(res.Total_Salidas.Porcentaje+"%");
                    $("#Total_Salidas_progress").width(res.Total_Salidas.Porcentaje+"%");

                    $("#Unidades_Danadas").text(res.Unidades_Danadas.Cantidad);
                    $("#Unidades_Danadas_por").text(res.Unidades_Danadas.Porcentaje+"%");
                    $("#Unidades_Danadas_progress").width(res.Unidades_Danadas.Porcentaje+"%");
                }
            });
        
        });

        // Initialize and add the map
        // function initMap() {
        //     // The location of Uluru
        //     var santiago = {lat:  -33.447487, lng: -70.673676};
        //     // The map, centered at Uluru
        //     var map = new google.maps.Map(
        //         document.getElementById('base_map'), {zoom: 13, center: santiago});
        //     // The marker, positioned at Uluru
        //     var marker = new google.maps.Marker({position: santiago, map: map});
        // }
    </script>

    <!-- <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.GOOGLE_MAPS_API_KEY') }}&callback=initMap">
    </script> -->

    <script>
         $(document).ready(function () {
            var checked = false;

            //Carga de mapa de rutas
            $('.item-ruta').click(function (e) {
                e.preventDefault();

                var ruta_id = $(this).attr('value');
                var cad = '#ruta_origen-' + ruta_id;
                var ruta_origen = $('#ruta_origen-' + ruta_id).val();
                var ruta_destino = $('#ruta_destino-' + ruta_id).val();

                var url = "https://www.google.com/maps/embed/v1/directions?origin=" + ruta_origen + "&destination=" + ruta_destino + "&key={{ config('googlemaps.GOOGLE_MAPS_API_KEY') }}";

                

                $('#iframe-ruta').attr('src', url);
            });
        });
    </script>


@endsection