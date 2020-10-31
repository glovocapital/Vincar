<!-- Modal -->
<div id="predespachoModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Predespacho</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'vin.predespacho', 'method'=>'POST', 'id'=>"PredespachoVins"]) !!}

                <div class="row row-fluid">
                    <div class="col-md-12">
                        <div class="form-group" id="codigos_vin">
                            <div name="vin_codigo_predespacho" id="vin_codigo_predespacho">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <label for="vin_estado_inventario_id" >Autorizar Entrega </label>
                            {!! Form::select('predespacho', ['1' => 'Preparar para entrega'], null,['placeholder'=>'Seleccione la Opción','class'=>'form-control col-sm-9', 'required'=>'required']) !!}

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <label for="vin_fecha" >Agendar fecha de entrega </label>
                            {{ Form::date('vin_fecha_despacho', new \DateTime(), ['class' => 'form-control', 'id' => 'vin_fecha_despacho', 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            Tipo de Agendamiento
                            <br/>
                            <label>Retiro: 
                                <input type="radio" name="tipo_agendamiento_id" id="agendamiento_tipo_1" value="1" required>
                            </label>
                            <label>Traslado: 
                                <input type="radio" name="tipo_agendamiento_id" id="agendamiento_tipo_2" value="2">
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 pt-2" style="display: none;" id="datos_usuario_1">
                        <div class="form-group">
                            <hr />
                            <h4>Datos del usuario</h4>
                        </div>
                    </div>
                    
                    <div class="col-md-6" style="display: none;" id="nombre_usuario_1">
                        <div class="form-group">
                            <label for="usuario_nombre" >Nombres </label>
                            {!! Form::text('usuario_nombre', null, ['placeholder'=>'Nombres del usuario','class'=>'form-control col-sm-12', 'id'=>'usuario_nombre']) !!}
                        </div>
                    </div>

                    <div class="col-md-6" style="display: none;" id="apellido_usuario_1">
                        <div class="form-group">
                            <label for="usuario_apellido" >Apellidos </label>
                            {!! Form::text('usuario_apellido', null, ['placeholder'=>'Apellidos del usuario','class'=>'form-control col-sm-12', 'id'=>'usuario_apellido']) !!}
                        </div>
                    </div>

                    <div class="col-md-6" style="display: none;" id="rut_usuario_1">
                        <div class="form-group">
                            <label for="usuario_rut" >RUT </label>
                            {!! Form::text('usuario_rut', null, ['placeholder'=>'RUT','class'=>'form-control col-sm-12', 'id'=>'usuario_rut']) !!}
                        </div>
                    </div>

                    <div class="col-md-6" style="display: none;" id="email_usuario_1">
                        <div class="form-group">
                            <label for="email" >Email </label>
                            {!! Form::email('email', null, ['placeholder'=>'Email','class'=>'form-control col-sm-12', 'id'=>'email']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 pt-2" style="display: none;" id="datos_traslado_1">
                        <div class="form-group">
                            <hr />
                            <h4>Datos del traslado</h4>
                        </div>
                    </div>

                    <div class="col-md-6" style="display: none;" id="ruta_origen_1">
                        <div class="form-group">
                            <label for="ruta_origen" >De:</label>
                            {!! Form::text('ruta_origen', null, ['placeholder'=>'Dirección de origen', 'id' => 'search_term_ruta', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                    <div class="col-md-6" style="display: none;" id="ruta_destino_1">
                        <div class="form-group">
                            <label for="ruta_destino" >A:</label>
                            {!! Form::text('ruta_destino', null, ['placeholder'=>'Dirección de destino', 'id' => 'search_term_ruta_2', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>
                </div>

                <div id="error0_predespacho" style="display:none; padding:5px" class="alert alert-success" role="alert">Datos Guardados</div>
                <div id="error1_predespacho" style="display:none; padding:5px" class="alert alert-danger" role="alert">Error al Guardar</div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-cerrar-pre-despacho" data-dismiss="modal">Cerrar Modal</button>

                <button id="btn-pre-despacho" type="button" class="btn btn-primary block full-width m-b">Asignar Entrega</button>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->
