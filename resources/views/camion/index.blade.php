@extends('layouts.app')
@section('title','Camiones index')
@section('content')
@include('flash::message')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 style="color:white"><i class="icon fa fa-times-circle"></i> Por favor corrige los siguientes errores:</h5>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <div class="card-title float-left mt-3">Camiones</div>
                <div class="float-right mt-3">
                    <button id='nuevo_camion' class="btn btn-primary block full-width m-b mb-3">Nuevo Camión</button>
                </div>
                <button class="btn block float-right mt-3 mb-3 mr-2" onclick="mostrarAlertas();" id="btnAlertas">
                    Alertas&nbsp;<span id="nroAlertas" class="label label-default">0</span>
                </button>
            </div>

            <div class="card-body overflow-auto">
            <!--   <div class="col-lg-12 pb-3 pt-2">
                        <a href="{{  route('camiones.create') }}" class = 'btn btn-primary'>Crear Camión</a>
                    </div>
            -->
                <div class="table-responsive">
                    <table class="table table-hover table-sm nowrap" id="dataTableCamiones" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Patente</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Año</th>
                                <th>Venc.Permiso Circ.</th>
                                <th>Venc.Revisión Téc.</th>
                                <th>Empresa</th>
                                <th>Documento</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $alertas = 0;
                            $alerta = new stdClass();
                            $arregloAlertas = [];
                        @endphp

                        @foreach($camiones as $camion)
                        @php
                            $f1 = date_create(date('Y-m-d'));
                            $f2 = date_create($camion->camion_fecha_circulacion);
                            $interval = date_diff($f1, $f2);
                            $signo = $interval->format('%R');
                            $dias = $interval->format('%a');

                            if ($signo == '-' || $dias <= 30){
                                $color = 'text-danger';
                                $alertas ++;
                                $alerta = new stdClass();
                                $alerta->patente = $camion->camion_patente;

                                if($signo == '+'){
                                    $texto = "faltan";
                                } else {
                                    $texto = "atrasado";
                                }

                                $alerta->descripcion = "Permiso de Circulación (".$texto." ".$dias." días)";
                                array_push($arregloAlertas, $alerta);
                            } else {
                                $color = '';
                            }

                            $f2 = date_create($camion->camion_fecha_revision);
                            $interval2 = date_diff($f1, $f2);
                            $signo2 = $interval2->format('%R');
                            $dias2 = $interval2->format('%a');

                            if ($signo2 == '-' || $dias2 <= 30){
                                $color2 = 'text-danger';
                                $alertas ++;
                                $alerta = new stdClass();
                                $alerta->patente = $camion->camion_patente;

                                if($signo2 == '+'){
                                    $texto = "faltan";
                                } else {
                                    $texto = "atrasado";
                                }

                                $alerta->descripcion = "Revisión Técnica (".$texto." ".$dias2." días)";
                                array_push($arregloAlertas, $alerta);
                            } else {
                                $color2 = '';
                            }
                        @endphp
                            <tr>
                                <td><small>{{ $camion->camion_patente }}</small></td>
                                <td><small>{{ $camion->camion_marca }}</small></td>
                                <td><small>{{ $camion->camion_modelo }}</small></td>
                                <td><small>{{ $camion->camion_anio }}</small></td>
                                <td><small class="{{ $color }}">{{ $camion->camion_fecha_circulacion }}</small></td>
                                <td><small class="{{ $color2 }}">{{ $camion->camion_fecha_revision }}</small></td>
                                <td><small>{{ $camion->belongsToEmpresa->empresa_razon_social }}</small></td>
                                <td><small> <a href="{{route('camiones.download', Crypt::encrypt($camion->camion_id)) }}">Documento</small> </td>

                                <td>
                                    <small>
                                        <a href="{{ route('camiones.edit', Crypt::encrypt($camion->camion_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                        <a href = "{{ route('camiones.trash', Crypt::encrypt($camion->camion_id))  }}" onclick="return confirm('¿Está seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
                                        </a>
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--modal alertas -->
<div class="modal fade" id="modalAlertas" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header bg-warning"> <h4 class="modal-title text-white" id="myModalLabel">Alertas Actuales</h4></div>

            <div class="modal-body">
                <table id="tblayudantes" class="table-hover nowrap lineas" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Patente</th>
                            <th>Tipo Alerta</th>
                        </tr>
                    </thead>
                    @foreach($arregloAlertas as $a)
                        <tr>
                            <td><small>{{ $a->patente }}</small></td>
                            <td><small>{{ $a->descripcion }}</small></td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('camion.partials.modal_nuevo_camion')
@stop

@section("local-scripts")
<script>
    $(document).ready(function() {
        $('#nuevo_camion').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoCamion")[0].reset();
            $("#nuevoCamion").modal('show');
        });

        datatablesButtons = $('[id="dataTableCamiones"]').DataTable({
            searching: true,
            bSortClasses: false,
            deferRender:true,
            responsive: false,
            lengthChange: !1,
            pageLength: 10,
            @if(Session::get('lang')=="es")
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            @endif
        });

        cargaAlertas();
    });

    function cargaAlertas(){
        if(@php echo $alertas @endphp > 0){
            $("#nroAlertas").html(@php echo $alertas @endphp);
            $("#btnAlertas").addClass("btn-warning");
            $("#nroAlertas").addClass("blink");
            $("#modalAlertas").modal("show");
        } else {
            $("#nroAlertas").html(0);
            $("#btnAlertas").removeClass("btn-warning");
            $("#btnAlertas").addClass("btn-success");
            $("#nroAlertas").removeClass("blink");
        }
    }

    function mostrarAlertas(){
        if(@php echo $alertas @endphp > 0){
            $("#modalAlertas").modal("show");
        } else {
            alert("Actualmente no hay alertas");
        }
    }
</script>
@endsection
