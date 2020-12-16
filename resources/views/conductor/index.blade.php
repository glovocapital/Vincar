@extends('layouts.app')
@section('title','Conductores index')
@section('content')
@include('flash::message')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-end">
                        <h3 class="card-title pb-3">Registro de Conductores</h3>
                        <p>
                            <a href="{{ route('conductores.create_conductor') }}" type="button" class="btn btn-success">Crear Conductor</a>
                        </p>
                    </div>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::open(['route'=> 'conductores.store', 'method'=>'POST', 'files' => true]) !!}

                            <div class="form-group">
                                <label for="user_id" >Conductor <strong>*</strong></label>
                                {!! Form::select('user_id', $usuario, null,['placeholder'=>'Seleccione','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="">Foto del Documento <strong>*</strong></label>
                                {!! Form::file('conductor_foto_documento'); !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_licencia_id" >Tipo de Licencia <strong>*</strong></label>
                                {!! Form::select('tipo_licencia_id', $tipo_licencia, null,['placeholder'=>'Seleccione','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="conductor_fecha_vencimiento" >Fecha de vencimiento <strong>*</strong></label>
                                 {!! Form::date('conductor_fecha_vencimiento', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Licencia ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Conductores</h3>
                        <button class="btn float-right" onclick="mostrarAlertas();" id="btnAlertas">
                          Alertas&nbsp;<span id="nroAlertas" class="label label-default">0</span>
                        </button>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTableCamion" width="100%" cellspacing="0">
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
                              if ($signo == '-' || $dias <= 30){
                                $color = 'text-danger';
                                $alertas ++;
                                $alerta = new stdClass();
                                $alerta->nombre = $p->belongstoUser->user_nombre ." ". $p->belongstoUser->user_apellido;
                                if($signo == '+'){
                                  $texto = "faltan";
                                }
                                else{
                                  $texto = "atrasado";
                                }
                                $alerta->descripcion = "Licencia de Conducir (".$texto." ".$dias." días)";
                                array_push($arregloAlertas, $alerta);
                              }
                              else{
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
@stop
@section("local-scripts")
<script>
       $(document).ready(function() {
    $('#dataTableCamion').DataTable();
    cargaAlertas();
} );
function cargaAlertas(){
  if(@php echo $alertas @endphp > 0){
    $("#nroAlertas").html(@php echo $alertas @endphp);
    $("#btnAlertas").addClass("btn-warning");
    $("#nroAlertas").addClass("blink");
    $("#modalAlertas").modal("show");
  }
  else{
    $("#nroAlertas").html(0);
    $("#btnAlertas").removeClass("btn-warning");
    $("#btnAlertas").addClass("btn-success");
    $("#nroAlertas").removeClass("blink");
  }
}

function mostrarAlertas(){
  if(@php echo $alertas @endphp > 0){
    $("#modalAlertas").modal("show");
  }
  else{
    alert("Actualmente no hay alertas");
  }
}
</script>
@endsection
