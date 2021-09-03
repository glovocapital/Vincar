<!-- Modal -->
<div id="nuevoPatio" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Patio</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'patio.store', 'method'=>'POST', 'files' => true, 'id' => 'formNuevoPatio']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" >Datos Básicos</label>
                        </div>

                        <div class="form-group">
                            <label for="patio_nombre" >Nombre del Patio <strong>*</strong></label>
                            {!! Form::text('patio_nombre', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="patio_bloques" >Cantidad de Bloques <strong>*</strong></label>
                            {!! Form::text('patio_bloques', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <br />
                        <br />
                        <br />

                        <div class="form-group">
                            <label for="" >Coordenadas Geográficas</label>
                        </div>

                        <div class="form-group">
                            <label for="patio_coord_lat" >Latitud <strong>*</strong></label>
                            {!! Form::text('patio_coord_lat', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="patio_coord_lon" >Longitud <strong>*</strong></label>
                            {!! Form::text('patio_coord_lon', null, ['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" >Datos de Ubicación</label>
                        </div>

                        <div class="form-group">
                            <label for="region_id" >Region <strong>*</strong></label>
                            <select name="region_id" id="region" class="form-control select-region" required>
                                <option value="" selected>Seleccione Región</option>
                            @foreach($regiones as $k => $v)
                                <option value="{!! Crypt::encrypt($k) !!}">{{$v}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comuna_id" >Seleccionar Comuna <strong>*</strong></label>
                            {!! Form::select('comuna_id', ['placeholder' => 'Seleccionar Comuna'], null, ['id' => 'comuna_id', 'class' => 'form-control']) !!}
                        </div>

                        <br />

                        <div class="form-group">
                            <label for="patio_direccion" >Dirección <strong>*</strong></label>
                            {!! Form::textarea('patio_direccion', null,['class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>
                </div>

                <br />
                <br />

                <div class="text-center pb-5" id="boton_patio">
                    {!! Form::submit('Registrar Patio', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
