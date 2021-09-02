<!-- Modal -->
<div id="nuevoConductor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Conductor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'conductores.store', 'method'=>'POST', 'files' => true, 'id' => 'formNuevoConductor']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    @if (count ($usuario) < 1)
                        <p style="color: red">&nbsp;&nbsp;&nbsp;&nbsp;<i>No existen candidatos, debe añadir al menos un nuevo usuario con rol transportista en la vista de usuarios</i></p>
                    @else
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="user_id" >Conductor <strong>*</strong></label>
                            {!! Form::select('user_id', $usuario, null,['placeholder'=>'Seleccione','class'=>'form-control col-sm-9', 'required'=>'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="">Foto del Documento <strong>*</strong></label>
                            {!! Form::file('conductor_foto_documento'); !!}
                        </div>
                    </div>
                    @endif

                    @if (count ($usuario) > 1)
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
                    @endif
                </div>

                @if (count ($usuario) > 1)
                <div class="text-right pb-5">
                    {!! Form::submit('Registrar Licencia ', ['class' => 'btn btn-primary block full-width m-b']) !!}
                </div>

                <div class="text-center texto-leyenda">
                    <p><strong>*</strong> Campos obligatorios</p>
                </div>
                @endif
            </div>
            {!! Form::close() !!}

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->
