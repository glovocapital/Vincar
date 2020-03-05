@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">

        <div class="row mb-2 mb-xl-4">
            <div class="col-auto d-none d-sm-block">
                <h3>{{trans('comun.Bienvenido')}}, {{Auth::user()->user_nombre}}!</h3>
            </div>

            <div class="col-auto ml-auto text-right mt-n1">
							<span class="dropdown mr-2">
                            </span>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-xl-8 col-md-8">
                <div class="row">
                    <div class="col-lg-12 col-xl-4 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <span class="badge badge-info float-right">{{trans('comun.Hoy')}}</span>
                                <h5 class="card-title mb-0">{{trans('comun.Total_Recibido')}}</h5>
                            </div>
                            <div class="card-body my-2">
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0 font-weight-light">
                                            <div id="Total_Recibido">0</div>
                                        </h2>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span id="Total_Recibido_por" class="text-muted">0%</span>
                                    </div>
                                </div>

                                <div class="progress progress-sm shadow-sm mb-1">
                                    <div id="Total_Recibido_progress" class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xl-4 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <span class="badge badge-primary float-right">{{trans('comun.Hoy')}}</span>
                                <h5 class="card-title mb-0">{{trans('comun.Total_Salidas')}}</h5>
                            </div>
                            <div class="card-body my-2">
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0 font-weight-light">
                                            <div id="Total_Salidas">0</div>
                                        </h2>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span id="Total_Salidas_por" class="text-muted">0%</span>
                                    </div>
                                </div>

                                <div class="progress progress-sm shadow-sm mb-1">
                                    <div id="Total_Salidas_progress" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xl-4 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <span class="badge badge-warning float-right">{{trans('comun.Total')}}</span>
                                <h5 class="card-title mb-0">{{trans('comun.Unidades_Danadas')}}</h5>
                            </div>
                            <div class="card-body my-2">
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0 font-weight-light">
                                            <div id="Unidades_Danadas">0</div>
                                        </h2>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span id="Unidades_Danadas_por" class="text-muted">0%</span>
                                    </div>
                                </div>

                                <div class="progress progress-sm shadow-sm mb-1">
                                    <div id="Unidades_Danadas_progress" class="progress-bar bg-warning" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-xl-12 d-flex">
                        <div class="card" style="width: 100%">
                            <div class="card-header">
                                <h5 class="card-title">{{trans('comun.Ruta_dia')}}</h5>
                            </div>
                            <div class="card-body" >
                                <div class="content" id="base_map" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-12 col-xl-4 col-md-4">

                <div class="card flex-fill w-100" style="font-size: 10px">
                    <div class="card-header">
                        <div class="card-actions float-right">
                            <div class="dropdown show">
                                <a  data-display="static">
                                    <i class="align-middle" data-feather="clock"></i>
                                </a>


                            </div>
                        </div>
                        <h5 class="card-title mb-0">{{trans('comun.Tiempo_espera')}}</h5>
                    </div>

                    <div class="card-body d-flex">

                            <table width="100%" class="table mb-0 borderless">

                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        <img style="max-width:70%"  src="{{asset('base/img/svg/dyp.svg')}}" class="img-fluid" />
                                        {{trans('comun.Picking')}}
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
                                        <img style="max-width:70%"  src="{{asset('base/img/svg/car-wash.svg')}}" class="img-fluid" />
                                        {{trans('comun.Limpieza')}}
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
                                        <img style="max-width:70%"  src="{{asset('base/img/svg/picking.svg')}}" class="img-fluid" />
                                        {{trans('comun.Inspeccion')}}
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
                                        <img style="max-width:70%" src="{{asset('base/img/svg/car.svg')}}" class="img-fluid" />
                                        {{trans('comun.Parking')}}
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

                    <div class="row">
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="stat-circle">
                                <h3 id="Total" style="color: #55ae90;;">0</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                    <circle style="fill:none;stroke:#55ae90;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                </svg>
                                <h5>{{trans('comun.Total')}}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="stat-circle">
                                <h3 id="Pendiente" style="color: #808080;;">0</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                    <circle style="fill:none;stroke:#808080;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                </svg>
                                <h5>{{trans('comun.Pendiente')}}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="stat-circle">
                                <h3 id="Gestionados" style="color: #60d356;;">0</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                    <circle style="fill:none;stroke:#60d356;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                </svg>
                                <h5>{{trans('comun.Gestionados')}}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="stat-circle">
                                <h3 id="Rechazados" style="color: #ea3b3b;;">0</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
                                    <circle style="fill:none;stroke:#ea3b3b;stroke-width:8;stroke-miterlimit:10;" cx="46" cy="46" r="40"></circle>
                                </svg>
                                <h5>{{trans('comun.Rechazados')}}</h5>
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