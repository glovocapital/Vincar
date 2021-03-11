<!-- Modal -->
<div id="vehiculoN_NModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Vehículos No Registrados</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            {!! Form::open(['route'=> 'vehiculo_nn.registrarVin', 'method'=>'POST', 'id'=>"form-vehiculo-nn"]) !!}
            <div class="modal-body">
                <div id="messages_n_n"></div>

                <div class="row row-fluid">
                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="vin_id" >Seleccione Vehículo </label>
                            {!! Form::select('vin_id', [], null,['id' => 'vin_id_nn', 'placeholder'=>'Seleccione VIN','class'=>'form-control col-sm-9', 'required'=>'required']) !!}

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" id='user_id_nn' name='user_id' value='' />
                            <label for="usuario_responsable_nn" >Responsable </label>
                            {!! Form::text('usuario_responsable_nn', '', ['class' => 'form-control', 'id' => 'usuario_responsable_nn', 'readonly', 'required']) !!}
                        </div>
                    </div>
                </div>

                <!-- Fotos de Pre-Inspección de VIN -->
                <div class="col-md-12 mt-3 pb-3" id="fotos_nn">

                </div>

                <div class="col-lg-12 pb-3">
                    <div class="form-inline">
                        <label for="vin_codigo">VIN </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="input-group">
                            {!! Form::text('vin_codigo', '', ['class' => 'form-control', 'id' => 'vin_codigo_n_n', 'readonly', 'required']) !!}
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <label for="vin_patente">Patente </label>&nbsp;&nbsp;
                        <div class="input-group">
                            {!! Form::text('vin_patente', '', ['class' => 'form-control', 'id' => 'vin_patente_n_n', 'readonly', 'required']) !!}
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <label for="vin_modelo">Modelo </label>&nbsp;&nbsp;
                        <div class="input-group">
                            {!! Form::text('vin_modelo', '', ['class' => 'form-control', 'id' => 'vin_modelo_n_n', 'readonly', 'required']) !!}
                        </div>
                    </div>
                    <div class="form-inline mt-3">
                        <label for="vin_marca">Marca </label>&nbsp;
                        <div class="input-group">
                            <input type="hidden" id='vin_marca_nn' name='vin_marca' value='' />
                            {!! Form::text('vin_marca_nombre', '', ['class' => 'form-control', 'id' => 'vin_marca_nombre_n_n', 'readonly', 'required']) !!}
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <label for="vin_color">Color </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="input-group">
                            {!! Form::text('vin_color', '', ['class' => 'form-control', 'id' => 'vin_color_n_n', 'readonly', 'required']) !!}
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <label for="vin_motor">Motor </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="input-group">
                            {!! Form::text('vin_motor', '', ['class' => 'form-control', 'id' => 'vin_motor_n_n', 'readonly', 'required']) !!}
                        </div>
                    </div>
                    <div class="form-inline mt-3">
                        <label for="vin_procedencia_nn">Procedencia del VIN </label>&nbsp;
                        <div class="input-group">
                            {!! Form::text('vin_procedencia_nn', '', ['class' => 'form-control', 'id' => 'vin_procedencia_n_n', 'readonly', 'required']) !!}
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="vin_destino_nn">Destino del VIN </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="input-group">
                            {!! Form::text('vin_destino_nn', '', ['class' => 'form-control', 'id' => 'vin_destino_n_n', 'readonly', 'required']) !!}
                        </div>
                        &nbsp;&nbsp;&nbsp;
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-cerrar-vehiculos-nn" data-dismiss="modal">Cancelar</button>

                <button id="btn-send-vehiculo-n-n" type="submit" class="btn btn-success block full-width m-b">Agregar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Fin modal -->
