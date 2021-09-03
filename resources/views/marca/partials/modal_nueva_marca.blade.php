<!-- Modal -->
<div id="nuevaMarca" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva Marca</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route'=> 'marcas.store', 'method'=>'POST','files' => true, 'id' => 'formNuevaMarca']) !!}
            <div class="modal-body">
                <div class="row row-fluid">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="marca_nombre" >Nombre de la marca <strong>*</strong></label>
                            {!! Form::text('marca_nombre', null, ['placeholder'=>'Nombre', 'class'=>'form-control col-sm-9', 'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="marca_nombre" >Código de la marca</label>
                            {!! Form::text('marca_codigo', null, ['placeholder'=>'Código', 'class'=>'form-control col-sm-9']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Logo de la marca (Extensión SVG) </label>
                            {!! Form::file('logo_marca') !!}
                        </div>
                    </div>
                </div>

                <br />
                        <br />
                        <br />
                        <br />

                        <div class="text-center pb-5">
                    {!! Form::submit('Agregar Marca ', ['class' => 'btn btn-primary block full-width m-b']) !!}
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
