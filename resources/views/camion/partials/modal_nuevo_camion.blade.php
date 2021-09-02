<!-- Modal -->
<div id="nuevoCamion" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Camión</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'camiones.store', 'method'=>'POST', 'files' => true, 'id' => 'formNuevoCamion']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="camion_patente" >Patente <strong>*</strong></label>
                            {!! Form::text('camion_patente', null, ['placeholder'=>'Patente', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="camion_anio" >Año <strong>*</strong></label>
                            {!! Form::number('camion_anio', '2020', ['min' => '1980','placeholder'=>'Año', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="camion_fecha_revision" >Próxima Revisión <strong>*</strong></label>
                                {!! Form::date('camion_fecha_revision', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="marca_id" >Marca <strong>*</strong></label>
                            <select name="marca_id" id="marca_id" class="form-control col-sm-9" placeholder="Marca">
                                <option value="">Marca</option>
                                @foreach ($marcas as $marca_id => $marca_nombre)
                                <option value="{{ $marca_id }}" required>{{ ucwords($marca_nombre) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="empresa_id">Empresa <strong>*</strong></label>
                            {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Empresa','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="">Foto del documento del Camión</label>
                            {!! Form::file('camion_foto_documento'); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="camion_modelo">Modelo <strong>*</strong></label>
                            {!! Form::text('camion_modelo', null, ['placeholder'=>'Modelo', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="camion_fecha_circulacion" >Permiso de Circulación <strong>*</strong></label>
                                {!! Form::date('camion_fecha_circulacion', null, [ 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="text-right pb-5">
                    {!! Form::submit('Registrar Camión ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
            </div>
            {!! Form::close() !!}

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->
