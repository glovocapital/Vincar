@extends('layouts.app')
@section('title','Tipo de campaña index')
@section('content')

<div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Tipo de Campaña</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['route'=> 'tipo_campania.store', 'method'=>'POST']) !!}

                            <div class="form-group">
                                <label for="tipo_campania_descripcion" >Nombre de la Campaña <strong>*</strong></label>
                                {!! Form::text('tipo_campania_descripcion', null, ['placeholder'=>'Tipo de Campaña', 'class'=>'form-control col-sm-9', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="text-right pb-5">
                        {!! Form::submit('Registrar Tipo de Campaña ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                        {!! Form::close() !!}
                    </div>

                    <div class="text-center texto-leyenda">
                        <p><strong>*</strong> Campo obligatorio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Listado de tipo de campañas</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
               <!--  <a href="{{  route('proveedor.create') }}" class = 'btn btn-primary'>Crear Tipo de Proveedor</a> -->


               <div class="table-responsive">
                    <table class="table table-hover" id="dataTableCampanias" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tipoCampanias as $tipoCampania)

                            <tr>
                                <td><small>{{ $tipoCampania->tipo_campania_descripcion }}</small></td>
                                <td>
                                    <small>
                                        <a href="{{ route('tipo_campania.edit', Crypt::encrypt($tipoCampania->tipo_campania_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                            <a href = "{{ route('tipo_campania.destroy', Crypt::encrypt($tipoCampania->tipo_campania_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
@stop



@section('local-scripts')
<script>
       $(document).ready(function() {
    $('#dataTableCampanias').DataTable();
} );
</script>
@endsection
