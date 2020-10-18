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
                                <!-- <div class="content" id="base_map" style="height: 300px;"> -->
                                <iframe
                                    width="100%"
                                    height="600"
                                    frameborder="0" style="border:0"
                                    src="https://www.google.com/maps/embed/v1/directions?origin=San Pablo 1391, Santiago, Chile&destination=Pasaje Manuel Montt 157, Santiago, Chile&key=AIzaSyAKi5ps17L1x9-SVP65NaCUpVBX2GFziaI" allowfullscreen>
                                </iframe>
                                <!-- </div> -->
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

                                    <table width="100%" class="table mb-0 borderless">

                                        <tbody>
                                        <tr>
                                            <td class="text-center">
                                                Rutal 1
                                            </td>
                                            <td style='width: 90%' class="text-right">
                                                <div style=" position: relative;">
                                                <div class="progress progress-sm shadow-sm mb-1" style="background: #ddd; position: relative;">
                                                    <div id="Tareas_progress" class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                    <div style="position: absolute; top: -3px; right: 0px; ">
                                                        <span style="font-size: 14px; z-index: 0" class="fa fa-check-circle bg-cyan"></span>
                                                    </div>

                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td class="text-center">
                                                Ruta 2
                                            </td>
                                            <td style='width: 90%' class="text-right">
                                                <div style=" position: relative;">
                                                <div class="progress progress-sm shadow-sm mb-1" style="background: #ddd; position: relative;">
                                                    <div id="DyP_progress" class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                    <div style="position: absolute; top: -3px; right: 0px; ">
                                                        <span style="font-size: 14px; z-index: 0" class="fa fa-check-circle bg-cyan"></span>
                                                    </div>

                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td class="text-center">
                                                Ruta 3
                                            </td>
                                            <td style='width: 90%' class="text-right">
                                                <div style=" position: relative;">
                                                <div class="progress progress-sm shadow-sm mb-1" style="background: #ddd; position: relative;">
                                                    <div id="Lavados_progress" class="progress-bar bg-warning" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                    <div style="position: absolute; top: -3px; right: 0px; ">
                                                        <span style="font-size: 14px; z-index: 0" class="fa fa-check-circle bg-cyan"></span>
                                                    </div>

                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td class="text-center">
                                                Ruta 4
                                            </td>
                                            <td style='width: 90%' class="text-right">
                                                <div style=" position: relative;">
                                                <div class="progress progress-sm shadow-sm mb-1" >
                                                    <div id="Carga_progress" class="progress-bar bg-cyan" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <div style="position: absolute; top: -3px; right: 0px; ">
                                                    <span style="font-size: 14px; z-index: 0" class="fa fa-check-circle bg-cyan"></span>
                                                </div>

                                                </div>

                                            </td>

                                        </tr>
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
                    $("#Tareas_progress").width(res.Tareas+"%");
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



            var defaultMap = {
                zoom: 14,
                center: {
                    lat: 40.712784,
                    lng: -74.005941
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            
            if(!(typeof google === 'undefined'))

            google.maps.Map(document.getElementById("base_map"), defaultMap);

        });
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-aWrwgr64q4b3TEZwQ0lkHI4lZK-moM4&callback=initMap">
    </script>


@endsection