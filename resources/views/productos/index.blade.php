@extends('layouts.app')
@section('title','Productos index')
@section('content')
@include('flash::message')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Producto</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>

                {!! Form::open(['route'=> 'productos.store', 'method'=>'POST']) !!}
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="producto_codigo" >Código de Producto <strong>*</strong></label>
                                {!! Form::text('producto_codigo', null, ['placeholder'=>'Código del producto', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="producto_nombre" >Descripción <strong>*</strong></label>
                                {!! Form::text('producto_descripcion', null, ['placeholder'=>'Descripción', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Producto ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Listado de Productos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>

                <div class="card-body overflow-auto">
                    <!-- <a href="{{  route('productos.create') }}" class = 'btn btn-primary'>Crear Destino</a> -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableProductos" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($producto as $p)
                                <tr>
                                    <td><small>{{ $p->producto_codigo }}</small></td>
                                    <td><small>{{ $p->producto_descripcion }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('productos.edit', Crypt::encrypt($p->producto_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('productos.destroy', Crypt::encrypt($p->producto_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
</div>
@stop

@section('local-scripts')
<script>
    $(document).ready(function() {
        $('#dataTableProductos').DataTable({
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
    });
</script>
@endsection
