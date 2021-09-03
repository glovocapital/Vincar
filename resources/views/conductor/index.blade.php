@extends('layouts.app')
@section('title','Conductores index')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <div class="card-title float-left mt-3">Conductores</div>
                <div class="float-right mt-3">
                    <button id='nuevo_conductor' class="btn btn-primary block full-width m-b mb-3">Nuevo Conductor</button>
                </div>
                <button class="btn block float-right mt-3 mb-3 mr-2" onclick="mostrarAlertas();" id="btnAlertas">
                    Alertas&nbsp;<span id="nroAlertas" class="label label-default">0</span>
                </button>
            </div>

            <div class="card-body overflow-auto">
                <div class="table-responsive">
                    <table class="table table-hover table-sm nowrap" id="dataTableConductores" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Conductor</th>
                                <th>Tipo de Licencia</th>
                                <th>Fecha de vencimiento</th>
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
                        @foreach($conductor as $p)
                            @php
                                $f1 = date_create(date('Y-m-d'));
                                $f2 = date_create($p->conductor_fecha_vencimiento);
                                $interval = date_diff($f1, $f2);
                                $signo = $interval->format('%R');
                                $dias = $interval->format('%a');

                                if ($signo == '-' || $dias <= 30) {
                                    $color = 'text-danger';
                                    $alertas ++;
                                    $alerta = new stdClass();
                                    $alerta->nombre = $p->belongstoUser->user_nombre ." ". $p->belongstoUser->user_apellido;

                                    if($signo == '+') {
                                        $texto = "faltan";
                                    } else {
                                        $texto = "atrasado";
                                    }

                                    $alerta->descripcion = "Licencia de Conducir (".$texto." ".$dias." días)";
                                    array_push($arregloAlertas, $alerta);
                                } else {
                                    $color = '';
                                }
                            @endphp
                            <tr>
                                <td><small>{{ $p->belongstoUser->user_nombre }} {{ $p->belongstoUser->user_apellido }}</small></td>
                                <td><small>{{ $p->oneLicencia->tipo_licencia_nombre }}</small></td>
                                <td><small>{{ $p->conductor_fecha_vencimiento }}</small></td>
                                <td><small> <a href="{{route('conductores.download', Crypt::encrypt($p->conductor_id)) }}">Documento</small> </td>

                                <td>
                                    <small>
                                        <a href="{{ route('conductores.edit', Crypt::encrypt($p->conductor_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                            <a href = "{{ route('conductores.destroy', Crypt::encrypt($p->conductor_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
                            <th>Nombre</th>
                            <th>Tipo Alerta</th>
                        </tr>
                    </thead>
                    @foreach($arregloAlertas as $a)
                        <tr>
                            <td><small>{{ $a->nombre }}</small></td>
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
@include('conductor.partials.modal_nuevo_conductor')
@stop

@section("local-scripts")
<script>
    $(document).ready(function() {
        $('#nuevo_conductor').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoConductor")[0].reset();
            $("#nuevoConductor").modal('show');
        });

        $('#dataTableConductores').DataTable({
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
        if(@php echo $alertas @endphp > 0) {
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

    function mostrarAlertas() {
        if(@php echo $alertas @endphp > 0) {
            $("#modalAlertas").modal("show");
        } else {
            alert("Actualmente no hay alertas");
        }
    }
</script>
@endsection
