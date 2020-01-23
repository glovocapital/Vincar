
<!-- Modal -->
<div id="cambioEstadoModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar Estado</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> ['vin.cambiaestado', Crypt::encrypt($vin->vin_id)], 'method'=>'PATCH']) !!}
                <div class="form-group">
                    <label for="vin_codigo" >Código VIN </label>
                    {!! Form::text('vin_codigo', $vin->vin_codigo, ['class'=>'form-control col-sm-9', 'required', 'readonly']) !!}
                </div>
                <div class="form-group">
                    <label for="vin_patente" >Patente </label>
                    {!! Form::text('vin_patente', $vin->vin_patente, ['class'=>'form-control col-sm-9', 'readonly']) !!}
                </div>

                <div class="form-group">
                    <label for="vin_marca" >Marca </label>
                    {!! Form::text('vin_marca', $vin->vin_marca, ['class'=>'form-control col-sm-9', 'readonly']) !!}
                </div>

                <div class="form-group">
                    <label for="vin_modelo" >Modelo </label>
                    {!! Form::text('vin_modelo', $vin->vin_modelo, ['class'=>'form-control col-sm-9', 'readonly']) !!}
                </div>

                <div class="form-group">
                    <label for="vin_estado_inventario_id" >Estado de Inventario </label>
                    {!! Form::select('vin_estado_inventario_id', $estadosInventario, $vin->vin_estado_inventario_id,['class'=>'form-control col-sm-9']) !!}
                </div>
                <div class="text-right pb-5">
                    {!! Form::submit('Actualizar estado', ['class' => 'btn btn-primary block full-width m-b']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin modal -->



