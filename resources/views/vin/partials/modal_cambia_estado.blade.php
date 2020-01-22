
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">



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
                        <label for="empresa_id" >Empresa </label>
                        {!! Form::select('empresa_id', $empresas, $user->belongsToEmpresa->empresa_id, ['class'=>'form-control col-sm-9', 'disabled', 'readonly']) !!}

                    </div>

                    <div class="form-group">
                        <label for="user_id" >Seleccionar Cliente </label>
                        {!! Form::select('user_id',$users, $vin->user_nombres,['class'=>'form-control col-sm-9', 'disabled', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        <label for="vin_estado_inventario_id" >Estado de Inventario </label>
                        {!! Form::select('vin_estado_inventario_id', $estadosInventario, $vin->vin_estado_inventario_id,['class'=>'form-control col-sm-9']) !!}

                    </div>
                </div>


            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
