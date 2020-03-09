@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
      <div class="card">
	    <div class="card-header">
		    <h5 class="card-title mb-0">{{trans('vins_patio.Mis_Patios')}}</h5>
		</div>
		<div class="card-body">

<div class="row">

<div class="col-lg-12 col-xl-10 d-flex">
		<div class="row">
            <div class="col-lg-12 col-xl-3 d-flex">

                 <div class="chart chart-sm">

                     <canvas id="chart_1"></canvas>

                 </div>

             </div>

               <div class="col-lg-12 col-xl-3 d-flex">

                       <div class="chart chart-sm">
                         <canvas id="chart_2"></canvas>
                        </div>

               </div>

                <div class="col-lg-12 col-xl-3 d-flex stat-circle" >
                            <h3 id="Vehiculos_30dias" style="color: #60d356; position:absolute;">0</h3>
                           <div class="chart chart-sm">
                                 <canvas id="chart_3"></canvas>
                           </div>

                </div>
                <div class="col-lg-12 col-xl-3 d-flex stat-circle" >
                            <h3 id="Vehiculos_danos" style="color: #60d356; position:absolute">0</h3>
                           <div class="chart chart-sm">

                                 <canvas id="chart_4"></canvas>
                           </div>

                </div>


		</div>

		</div>

		<div class="col-lg-12 col-xl-2 text-uppercase">
		<p>{{trans('vins_patio.Capacidad_Total')}}</p>
		<p><div id="Capacidad_Total">0</div></p>

		<p>{{trans('vins_patio.Espacios_Disponibles')}}</p>
        <p><div id="Espacios_Disponibles">0</div></p>
        </div>

		</div>

	  </div>

	  </div>


        <div class="card">
	    <div class="card-header">
		    <h5 class="card-title mb-0">{{trans('vins_patio.Patio')}}</h5>
		</div>
		<div class="card-body">

		<div class='row'>
		    <div class='col-lg-12 col-xl-5 d-flex'>
		        <select id="Patios" class="custom-select mb-3">
                      <option value="" selected>{{trans('vins_patio.Seleccione')}}</option>
                      @foreach($patios as $patio)
                      <option value="{{ $patio->patio_id }}">{{ $patio->patio_nombre }}</option>
                      @endforeach



                </select>
		    </div>
		    <div class='col-lg-12 col-xl-5 d-flex'>
		        <select id="Bloques" class="custom-select mb-3">
                                  <option value="" selected>{{trans('vins_patio.Seleccione')}}</option>
                </select>
                <div style="display:none" id="Loading_1" class="spinner-border text-info mr-2"  role="status">
                	<span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class='col-lg-12 col-xl-2 d-flex'>
            <button id="Buscar" class="btn btn-info"><i  style="display:none" id="Loading_2" class="spinner-border spinner-border-sm" role="status"></i> {{trans('vins_patio.Buscar_Bloque')}}


            </button>
            </div>

		</div>
		</div>

		</div>

        <div class="card">

		<div id="TodosBloques" class="card-body">

		</div>

    </div>

    <div id="ModalVaciar" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">
          <div class="modal-header">
          <h4 style="color:red;"> Confirmar Vaciado</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

          </div>
          <div class="modal-body">

          <input id="data" name="data" type="hidden">
          <input id="patio_data" name="patio_data" type="hidden">

             <div class="form-group">
                          <label for="usrname"><span class="glyphicon glyphicon-user"></span> Seleccione estado de los VIN</label>

                          <select  class="form-control"  id="estado" name="estado">
                          <option value="2">Arribado</option>
                           <option value="8">Entregado</option>
                            <option value="7">Suprimido</option>
                          </select>
                        </div>
          </div>
          <div class="modal-footer">
            <button id="enviar" type="button" class="btn btn-primary">Enviar</button>
            <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

@endsection

@section('local-scripts')

<style>
        .stat-circle {

            position: relative;

        }

        .stat-circle h3 {
            position: absolute;
            font-size: 40px;
            line-height: 170px;
            text-align: center;
            width: 100%;
            font-weight: 100;
            left:0px
        }

        .stat-circle h5 {
            text-align: center;
        }

        .col_bloque{
        padding:5px;
        font-size:9px;
        min-width:30px;
        margin:1px !important;
        text-align:center;
        }

        .col_bloque_gris{
            background: #dedede;
        }

        .col_bloque_verde{
            background: #00ff20;
        }

        .col_bloque_amarillo{
            background: #feee00;
        }
        .col_bloque_rojo{
            background: #fc0102;
        }
        .col_bloque_azul{
            background: #02c1ff;
         }
         .col_bloque_blanco{
                     color: #ffffff;
                  }


        .col_bloqueH{
        padding:3px;
        white-space: nowrap;
        font-size:10px;
        margin:1px !important;
        }

        .col_bloqueT{
        white-space: nowrap;
        font-size:12px;
        margin:1px !important;
        }

        .cuadro{
        margin:15px;
        padding: 5px;
        }
    </style>

<script>
		$(function() {

		 config_1 =  {
                      				type: "doughnut",
                      				data: {
                      					labels: ['Vacio'],
                      					datasets: [{
                      						data: ['100'],
                      						backgroundColor: ['#dee4e4'],
                      						borderColor: 'transparent'
                      					}]
                      				},
                      				options: {
                      					maintainAspectRatio: false,
                      					cutoutPercentage: 65,
                      					legend: {
                      						display: false
                      					},
                      					tooltips:{

                      					enabled: true,


                      					},
                      					animation: {
                                          	animateScale: true,
                                          	animateRotate: true
                                          },
                                          title: {
                                          	display: true,
                                          	text: '{{trans('vins_patio.Porc_vehiculo')}}',
                                          	position: 'bottom'
                                          },
                      				}
                      			};
 config_2 =  {
                      				type: "doughnut",
                      				data: {
                      					labels: ['Vacio'],
                      					datasets: [{
                      						data: ['100'],
                      						backgroundColor: ['#dee4e4'],
                      						borderColor: 'transparent'
                      					}]
                      				},
                      				options: {
                      					maintainAspectRatio: false,
                      					cutoutPercentage: 65,
                      					legend: {
                      						display: false
                      					},
                      					tooltips:{

                      					enabled: true,


                      					},
                      					animation: {
                                          	animateScale: true,
                                          	animateRotate: true
                                          },
                                          title: {
                                          	display: true,
                                          	text: '{{trans('vins_patio.Capacidad')}}',
                                          	position: 'bottom'
                                          },
                      				}
                      			};
                     config_3 =  {
                      				type: "doughnut",
                      				data: {
                                    labels: ['Vacio'],
                      					datasets: [{
                      						data: ['100'],
                      						backgroundColor: ['#dee4e4'],
                      						borderColor: 'transparent'
                      					}]
                      				},
                      				options: {
                      					maintainAspectRatio: false,
                      					cutoutPercentage: 65,
                      					legend: {
                      						display: false
                      					},
                      					tooltips:{

                      					enabled: true,


                      					},
                      					animation: {
                                          	animateScale: true,
                                          	animateRotate: true
                                          },
                                          title: {
                                          	display: true,
                                          	text: '{{trans('vins_patio.Vehiculos_30dias')}}',
                                          	position: 'bottom'
                                          },
                      				}
                      			};
                 config_4 =  {
                      				type: "doughnut",
                      				data: {
                                    labels: ['Vacio'],
                      					datasets: [{
                      						data: ['100'],
                      						backgroundColor: ['#dee4e4'],
                      						borderColor: 'transparent'
                      					}]
                      				},
                      				options: {
                      					maintainAspectRatio: false,
                      					cutoutPercentage: 65,
                      					legend: {
                      						display: false
                      					},
                      					tooltips:{

                      					enabled: true,


                      					},
                      					animation: {
                                          	animateScale: true,
                                          	animateRotate: true
                                          },
                                          title: {
                                          	display: true,
                                          	text: '{{trans('vins_patio.Vehiculos_danos')}}',
                                          	position: 'bottom'
                                          },
                      				}
                      			};


			window.chart_1 = new Chart(document.getElementById("chart_1"),config_1);

			window.chart_2 = new Chart(document.getElementById("chart_2"),config_2);

			window.chart_3 = new Chart(document.getElementById("chart_3"),config_3);

			window.chart_4 = new Chart(document.getElementById("chart_4"),config_4);



		});
	</script>

    <script>
        $(function(){



            $("#Patios").change(function(){
            $("#Bloques").html("");
            $("#Bloques").append("<option value='' selected>{{trans('vins_patio.Seleccione')}}</option>");

            $("#Loading_1").show();
            valor = $("#Patios").val();
            $.ajax({
                            url: '{{route('patio.bloques')}}',
                            type: 'GET',
                            dataType: 'json',
                            data:'patio_id='+valor,
                            success: function(res) {
                            console.log(res);

                            mostrarEstadistica(res.dashboard);

                            $("#Loading_1").hide();

                            res.bloques.forEach(function(datos) {
                            $("#Bloques").append("<option value='"+datos.bloque_id+"'>"+datos.bloque_nombre+"</option>");
                            });

                            }

                             });
            });


            $("#Buscar").click(function(){
                       valorPatio = $("#Patios").val();
                       valorBloques = $("#Bloques").val();


                        $("#Loading_2").show();

                        $.ajax({
                                        url: '{{route('patio.todos_bloques')}}',
                                        type: 'GET',
                                        dataType: 'json',
                                        data:'patio_id='+valorPatio+'&bloque_id='+valorBloques,
                                        success: function(res) {
                                        console.log(res);

                                        $("#Loading_2").hide();

                                        $("#TodosBloques").html("");

                                        res.bloques.forEach(function(datos) {

                                        totalsector= datos.bloque_filas*datos.bloque_columnas;

                                        Bloque_c='';

                                        usados=0;

                                        Bloque_c = Bloque_c + '<div class="row "><div class="col col_bloqueH col_bloque_blanco">'+"Calle 01"+'</div>';

                                                                                for (var ijc = 1; ijc <= datos.bloque_columnas; ijc++) {
                                                                                if(i<ijc) i = "0"+ijc;
                                                                                 Bloque_c = Bloque_c+'<div class="col col_bloque">P'+ijc+'</div>';
                                                                                }

                                                                                Bloque_c = Bloque_c+'<div class="col col_bloque"></div></div>';

                                        for (var i = 1; i <= datos.bloque_filas; i++) {

                                        usados_l=0;

                                        if(i<10) i = "0"+i;



                                           Bloque_c = Bloque_c + '<div class="row "><div class="col col_bloqueH "> Calle: '+ i + '</div>';

                                           for (var ijc = 1; ijc <= datos.bloque_columnas; ijc++) {
                                             color = 'col_bloque_gris';
                                             num = ijc;
                                           res.ubicados.forEach(function(ubic) {

                                                if(parseInt(ubic.ubic_patio_columna) == ijc && parseInt(ubic.ubic_patio_fila) == i && parseInt(datos.bloque_id) == ubic.bloque_id){

                                                    var fecha1 = moment(ubic.vin_fec_ingreso);
                                                    var f = new Date();
                                                    var fecha2 = moment(f.getFullYear()+"-"+(f.getMonth() +1)+"-"+f.getDate());
                                                    var dias =fecha2.diff(fecha1, 'days');

                                                    usados++; usados_l++;

                                                    if(ubic.vin_estado_inventario_id==4) color = 'col_bloque_verde';
                                                    if(ubic.vin_estado_inventario_id==5){
                                                     color = 'col_bloque_verde';
                                                     if(parseInt(dias)>30) color = 'col_bloque_amarillo';

                                                    }

                                                    if(ubic.vin_estado_inventario_id==6)  color = 'col_bloque_rojo';
                                                    if(ubic.vin_estado_inventario_id==7)  color = 'col_bloque_rojo';

                                                    var vin_marca = new String(ubic.vin_marca);
                                                    console.log(vin_marca.toLowerCase());

                                                    num = "<img style='width:24px' data-toggle='tooltip' data-html='true' title='VIN:"+ubic.vin_codigo+"<br>["+ubic.vin_estado_inventario_desc+"]' src ='{{asset('base/img/svg/')}}/"+vin_marca.toLowerCase()+".svg'/>";

                                                }
                                           });

                                                Bloque_c = Bloque_c+'<div class="col col_bloque '+color+'">'+num+'</div>';



                                           }


                                           Bloque_c = Bloque_c + '<div class="col col_bloqueH"><button class="btn btn-danger btn-sm vaciar" data="'+datos.bloque_id+'_'+i+'" usados="'+usados_l+'"><i class="fas fa-times"></i></button></div></div>';
                                        }

                                          Dtotalsector= totalsector-usados;

                                          Bloque_='<div class="row "> <div class="col col_bloqueT">'+datos.bloque_nombre+'</div>'
                                                                                                                           +'<div class="col col_bloqueT text-rigth">'
                                                                                                                           +'<button class="btn btn-danger btn-sm vaciar" data="'+datos.bloque_id+'" usados="'+usados+'">Vaciar Bloque</button> </div> </div>'
                                                                                                                           +'<div class="row"><div class="col col_bloqueT">utilizados '+usados+' de '+totalsector+' [Disponibles: '+Dtotalsector+']</div></div>';



                                        $("#TodosBloques").append('<div class="float-left cuadro">'+Bloque_+Bloque_c+'</div>');

                                        });

                                        $('[data-toggle="tooltip"]').tooltip();

                                                                                 $(".vaciar").on("click",function(){
                                                                                 data = $(this).attr('data');
                                                                                 usado = $(this).attr('usados');
                                                                                 patio = $("#Patios").val();

                                                                                 if(parseInt(usado)>0){

                                                                                 $('.modal-body input[name=data]').val(data);

                                                                                 $('.modal-body input[name=patio_data]').val(patio);


                                                                                    $("#ModalVaciar").modal("show");




                                                                                   $("#enviar").on("click",function(){

                                                                                    estado_ = $('.modal-body select[name=estado]').val();

                                                                                    data_ = $('.modal-body input[name=data] ').val();

                                                                                    patio_data_ = $('.modal-body input[name=patio_data]').val()



                                                                                     $.ajax({
                                                                                                                            url: '{{route('patio.vaciar_bloques')}}',
                                                                                                                            type: 'GET',
                                                                                                                            dataType: 'json',
                                                                                                                            data:'patio_id='+patio_data_+'&bloque_id='+data_+'&estado_id='+estado_,
                                                                                                                            success: function(res) {

                                                                                                                             console.log(res);

                                                                                                                            if(res.Err==0){
                                                                                                                             $("#ModalVaciar").modal("hide");
                                                                                                                             $("#Buscar").click();
                                                                                                                            }


                                                                                                                            }

                                                                                       });

                                                                                    });
                                                                                 }

                                                                                 });

                                        }



                                         });
                        });



            $.ajax({
                url: '{{ route('patio.dashboard') }}',
                type: 'GET',
                dataType: 'json', // added data type
                success: function(res) {

                    mostrarEstadistica(res);


                }
            });

            function mostrarEstadistica(res){
            console.log(res);

                                $("#Capacidad_Total").text(res.Capacidad_Total);
                                $("#Espacios_Disponibles").text(res.Espacios_Disponibles);

                                config_1.data.labels=[];
                                config_1.data.datasets.forEach(function(dataset) {
                                    dataset.data=[];
                                    dataset.backgroundColor=[];
                                });

                                res.Porc_vehiculo.forEach(function(datos) {

                                config_1.data.labels.push(datos.Patio);

                                        config_1.data.datasets.forEach(function(dataset) {
                                         					dataset.data.push(datos.Data);
                                         					dataset.backgroundColor.push(datos.backgroundColor);
                                         });


                                });

                                window.chart_1.update();

                                config_2.data.labels=[];
                                config_2.data.datasets.forEach(function(dataset) {
                                    dataset.data=[];
                                    dataset.backgroundColor=[];
                                });

                                res.Capacidad.forEach(function(datos) {

                                config_2.data.labels.push(datos.Patio);

                                        config_2.data.datasets.forEach(function(dataset) {
                                         					dataset.data.push(datos.Data);
                                         					dataset.backgroundColor.push(datos.backgroundColor);
                                         });

                                });

                                window.chart_2.update();

                                ht_=0;
                                ht_1=0;

                                config_3.data.labels=[];
                                config_3.data.datasets.forEach(function(dataset) {
                                    dataset.data=[];
                                    dataset.backgroundColor=[];
                                });

                                res.Vehiculos_30dias.forEach(function(datos) {

                                config_3.data.labels.push(datos.Vehiculos);

                                        config_3.data.datasets.forEach(function(dataset) {
                                            if(ht_1==0) ht_1 = datos.Data; ht_=ht_+datos.Data;
                                         					dataset.data.push(datos.Data);
                                         					dataset.backgroundColor.push(datos.backgroundColor);
                                         });

                                });

                                window.chart_3.update();

                                if(ht_>0)
                                $("#Vehiculos_30dias").text(Math.round(ht_1*100/ht_)+"%");
                                else
                                $("#Vehiculos_30dias").text("0%");

                                ht_=0;
                                ht_1=0;

                                config_4.data.labels=[];
                                config_4.data.datasets.forEach(function(dataset) {
                                    dataset.data=[];
                                    dataset.backgroundColor=[];
                                });

                                res.Vehiculos_danos.forEach(function(datos) {

                                config_4.data.labels.push(datos.Vehiculos);

                                        config_4.data.datasets.forEach(function(dataset) {
                                            if(ht_1==0) ht_1 = datos.Data; ht_=ht_+datos.Data;
                                         					dataset.data.push(datos.Data);
                                         					dataset.backgroundColor.push(datos.backgroundColor);
                                         });

                                });

                                window.chart_4.update();

                                if(ht_>0)
                                $("#Vehiculos_danos").text(Math.round(ht_1*100/ht_)+"%");
                                 else
                                 $("#Vehiculos_danos").text("0%");
            }





        });
    </script>




@endsection