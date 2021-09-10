@extends('layouts.app')
@section('title','Guías index')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Listado de guías - Mostrando: Últimos 3 meses</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <form method="get" action="{{ url('guia') }}">
                    <div class="row row-filters">
                        <div>
                            <h6 class="pb-2">Buscar en histórico de guías</h6>

                            <div class="form-inline pb-3">
                                <label for="from" class="form-label-sm">Desde</label>&nbsp;
                                <div class="input-group">
                                    <input type="date" class="form-control" name="from" id="from" placeholder="Desde" value="{{ request('from') }}">
                                </div>

                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="from" class="form-label-sm">Hasta</label>&nbsp;
                                <div class="input-group">
                                    <input type="date" class="form-control" name="to" id="to" placeholder="Hasta" value="{{ request('to') }}">
                                </div>

                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="guia_numero" class="form-label-sm ">Nro. de Guía</label>
                                &nbsp;
                                <div class="input-group">
                                    <input type="text" class="form-control" name="guia_numero" id="guia_numero" placeholder="Nro. de Guía" value="{{ request('guia_numero') }}">
                                </div>
                            </div>

                            <div class="form-inline pb-3">
                                <label for="vin_numero" class="form-label-sm">Vin <strong>*</strong></label>
                                &nbsp;&nbsp;
                                {!! Form::text('vin_numero', null, ['placeholder'=>'Ingrese VIN', 'id' => 'vin_numero', 'class'=>"form-control"]) !!}

                                &nbsp;&nbsp;&nbsp;
                                <label for="empresa" class="form-label-sm">Empresa</label>
                                &nbsp;
                                <div class="input-group">
                                {!! Form::select('empresa_id', $empresas, request('empresa_id'),['id' => 'cliente', 'placeholder'=>'Cliente', 'class'=>'form-control col-sm-10 select-cliente']) !!}
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover table-sm nowrap" id="dataTableGuias" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Número de Guía</th>
                                <th>Fecha</th>
                                <th>Empresa</th>
                                <th>VINs</th>
                                <th>¿Carga entregada?</th>
                                <th>Estado</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($guias as $guia)
                            <tr>
                                <td><small>{{ $guia->guia_numero }}</small></td>
                                <td><small>{{ $guia->guia_fecha }}</small></td>
                                <td><small>{{ $guia->empresa->empresa_razon_social }}</small></td>
                                <td><small>
                                @foreach ($guia->vins() as $vin)
                                {{ $vin->vin_codigo }},
                                @endforeach
                                </small></td>

                                @if ($guia->guia_carga_entregada)
                                    <td><small><button class="btn btn-success btn-sm rounded">Sí</button></small></td>
                                @else
                                    <td><small><button class="btn btn-danger btn-sm rounded">No</button></small></td>
                                @endif

                                @if ($guia->deleted_at != null)
                                    <td><small><button class="btn btn-danger btn-sm rounded">Anulada</button></small></td>
                                @else
                                    <td><small><button class="btn btn-success btn-sm rounded">Activa</button></small></td>
                                @endif

                                <td>
                                    <small>
                                        <a href="{{ route('guia.downloadGuia', Crypt::encrypt($guia->guia_id)) }}" type="button" class="btn-guia"  title="Descargar Guía"><i class="fas fa fa-barcode2"></i></a>
                                    </small>
                                    <small>
                                        <a href="{{ route('guia.delete', Crypt::encrypt($guia->guia_id)) }}" type="button" onclick="return confirm('¿Está seguro que desea anular esta guía?')" class="btn-guia"  title="Anular Guía"><i class="far fa-trash-alt"></i></a>
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

@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#dataTableGuias').DataTable({
            searching: false,
            bSortClasses: false,
            deferRender:true,
            responsive: false,
            lengthChange: !1,
            pageLength: 15,
            @if(Session::get('lang')=="es")
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            @endif
        });
    });
</script>
@endsection
