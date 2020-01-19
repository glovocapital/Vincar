@extends('layouts.app')
@section('title','Tipo de proveedor index')
@section('content')

    <div class="row">
<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Tipo Proveedor</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['route'=> 'proveedor.store', 'method'=>'POST']) !!}

                            <div class="form-group">
                                <label for="tipo_proveedor_desc" >Nombre del Proveedor <strong>*</strong></label>
                                {!! Form::text('tipo_proveedor_desc', null, ['placeholder'=>'Nombre del Proveedor', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Tipo de Proveedor ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campos obligatorios</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row">

    <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Listado de tipo de proveedores</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
               <!--  <a href="{{  route('proveedor.create') }}" class = 'btn btn-primary'>Crear Tipo de Proveedor</a> -->


               <div class="table-responsive">
                    <table class="table table-hover" id="dataTableProveedores" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($proveedores as $p)

                            <tr>
                                <td><small>{{ $p->tipo_proveedor_desc }}</small></td>
                                <td>
                                    <small>
                                        <a href="{{ route('proveedor.edit', Crypt::encrypt($p->tipo_proveedor_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                            <a href = "{{ route('proveedor.destroy', Crypt::encrypt($p->tipo_proveedor_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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




<script>
       $(document).ready(function() {
    $('#dataTableProveedores').DataTable();
} );
</script>
