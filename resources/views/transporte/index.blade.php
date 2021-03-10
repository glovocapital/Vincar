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
                        <div class="card map-card" style="width: 100%">
                            <div class="card-body" style="padding: 0%">
                                <div id="map"></div>
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
                                            <div id="datos-mapa"></div>
                                            <div id="ubicacion-obtenida"></div>
                                            @foreach($rutas as $ruta)
                                            <tr class="mt-5 mb-5">
                                                <td class="item-ruta" id="ruta-{{ $ruta->ruta_id }}" value="{{ $ruta->ruta_id }}">Ruta: {{ $ruta->ruta_origen }} - {{ $ruta->ruta_destino }}
                                                    <input type="hidden" name="ruta_id-crypt" id="ruta_id-crypt-{{ $ruta->ruta_id }}" value="{{ Crypt::encrypt($ruta->ruta_id) }}">
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

        /* Always set the map height explicitly to define the size of the div
         * element that contains the map.
         */

        .map-card {
            height: 600px;
        }
        #map {
            height: 100%;
            width: 100%;
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
    </script>

    <script>
        var ruta_id = null;
        var ubicacion = null;
        var coordenadas = null;
        var ubicacionData = null;
        var datosOrigen = null;
        var datosDestino = null;
        var coordenadasOrigen = null;
        var coordenadasDestino = null;
        var ultimaUbicacion = null;
        var coordenadasUltimaUbicacion = null;

        var marker = null;

        let map;

        function cargarValoresMapa(){
            if ($('#ruta-a-cargar').val() != undefined) {
                ruta_id = $('#ruta-a-cargar').val();

                if ($('#centro-mapa').val() != undefined) {
                    ubicacion = JSON.parse($('#centro-mapa').val());
                    coordenadas = ubicacion.results[0].geometry.location;
                    ubicacionData = {
                        position:{lat:coordenadas.lat,lng:coordenadas.lng},
                        name:ubicacion.results[0].formatted_address,
                    };
                }

                if ($('#datos-origen').val() != undefined) {
                    datosOrigen = JSON.parse($('#datos-origen').val());
                    coordenadasOrigen = datosOrigen.results[0].geometry.location;
                }

                if ($('#datos-destino').val() != undefined) {
                    datosDestino = JSON.parse($('#datos-destino').val());
                    coordenadasDestino = datosDestino.results[0].geometry.location;
                }

                if ($('#ultima-ubicacion').val() != undefined) {
                    ultimaUbicacion = JSON.parse($('#ultima-ubicacion').val());
                    coordenadasUltimaUbicacion = ultimaUbicacion.results[0].geometry.location;
                }
            }
        }

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                // center: { lat: ubicacionData.position.lat, lng: ubicacionData.position.lng },
                center: {lat: -33.455105935358176, lng: -70.65376861595341}, // Santiago de Chile.
                zoom: 10,
            });
        }

        function calcRoute() {
            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();
            // var start = document.getElementById('start').value;
            var start = coordenadasOrigen;
            // var end = document.getElementById('end').value;
            var end = coordenadasDestino;
            var request = {
                origin: start,
                destination: end,
                travelMode: 'DRIVING',
                provideRouteAlternatives: true
            };
            directionsRenderer.setMap(map);
            directionsService.route(request, function(result, status) {
                if (status == 'OK') {
                    directionsRenderer.setDirections(result);
                }
            });
        }

        function update(){
            var ruta_id_crypt = $('#id-ruta-crypt').val();
            var url = "tour/ubicacion_actual/" + ruta_id_crypt;

            if (ruta_id_crypt != undefined) {
                $.get(url, function (res) {
                    if(!res.success){
                        alert(
                            "Error inesperado al solicitar la información.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento de obtener
                    }

                    const ubicObtenida = {
                        lat:  res.ubicacion.ubicacion_latitud,
                        lng:  res.ubicacion.ubicacion_longitud,
                    };

                    $('#ubicacion-obtenida').empty();
                    $('#ubicacion-obtenida').append("<input type='hidden' name='ubicacion-actual' id='ubicacion-actual' value='" + JSON.stringify(ubicObtenida) + "'/>");
                }).fail(function () {
                    alert('Error: ');
                });
            }
        }


        function putMarker () {
            if ($('#ubicacion-actual').val() != undefined){
                var ubicacion = JSON.parse($('#ubicacion-actual').val());

                if (marker != null) {
                    marker.setMap(null);
                }

                var imagen = '{{asset("base/img/svg/gps2.png")}}';
                marker = new google.maps.Marker({
                    position: {lat:parseFloat(ubicacion.lat),lng:parseFloat(ubicacion.lng)},
                    title:"Posición actual del vehículo",
                    icon: imagen
                });

                // To add the marker to the map, call setMap();
                marker.setMap(map);
            }
        }

        setInterval(update, 5000);
        setInterval(putMarker, 5000);

        $(document).ready(function () {
            var checked = false;

            //Carga de mapa de rutas
            $('.item-ruta').click(function (e) {
                e.preventDefault();

                var ruta_id = $(this).attr('value');
                var ruta_id_crypt = $('#ruta_id-crypt-' + ruta_id).val();
                var cad = '#ruta_origen-' + ruta_id;
                var ruta_origen = $('#ruta_origen-' + ruta_id).val();
                var ruta_destino = $('#ruta_destino-' + ruta_id).val();

                var url = "tour/datos_ubicacion_ruta/" + ruta_id_crypt;

                $.get(url, function (res) {
                    if(!res.success){
                        alert(
                            "Error inesperado al solicitar la información.\n\n" +
                            "MENSAJE DEL SISTEMA:\n" +
                            res.message + "\n\n"
                        );
                        return;  // Finaliza el intento de obtener
                    }

                    $('#datos-mapa').empty();
                    $("#datos-mapa").append("<input type='hidden' name='ruta-a-cargar' id='ruta-a-cargar' value='" + ruta_id + "'/>");
                    $("#datos-mapa").append("<input type='hidden' name='ultima-ubicacion' id='ultima-ubicacion' value='" + res.ubicacion + "'/>");
                    $("#datos-mapa").append("<input type='hidden' name='datos-origen' id='datos-origen' value='" + res.origen + "'/>");
                    $("#datos-mapa").append("<input type='hidden' name='datos-destino' id='datos-destino' value='" + res.destino + "'/>");
                    $("#datos-mapa").append("<input type='hidden' name='centro-mapa' id='centro-mapa' value='" + res.centroMapa + "'/>");
                    $("#datos-mapa").append("<input type='hidden' name='id-ruta-crypt' id='id-ruta-crypt' value='" + ruta_id_crypt + "'/>");
                    cargarValoresMapa();
                    calcRoute();
                }).fail(function () {
                    alert('Error: ');
                });
            });
        });
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.key') }}&callback=initMap&libraries=">
    </script>
@endsection
