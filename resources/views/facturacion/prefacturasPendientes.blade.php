@extends('layouts.app')
@section('title','Prefacturas Pendientes')
@section('content')
@include('flash::message')

<div class="col-lg-12">

    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Pre Facturas Pendientes</h3>

            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  {!! Form::open(['route'=> 'prefacturacion.generar', 'method'=>'POST', 'id' => 'frmGenerarPrefactura']) !!}
                  <div class="form-group">
                      <label for="empresa_id">Empresa </label>
                      {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Todas','class'=>'form-control col-sm-9', 'id'=>'empresa_id']) !!}
                      (mostrar pre-facturas pendientes)
                  </div>
                  <div class="form-group">
                      <label for="cmbPendientes">¿Sólo Pendientes? </label>
                      <select id="cmbPendientes" class="form-control col-sm-9">
                        <option value="SI">Si, sólo pendientes</option>
                        <option value="TODAS">No, todas</option>
                      </select>
                  </div>
                    <div class="text-right pb-5">
                      <button id="btnConsultar" class="btn btn-primary block full-width m-b">Consultar</button>
                    </div>
                </div>

              </div>
            </div>
            <div id="tablaPrefactura" class="col-sm-12">

            </div>
        </div>

    </div>

</div>
<div class="modal" id="modalDetallePrefactura" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle Prefactura</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="modalNroOrden" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ingresar Nro.OC</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@stop
@section('local-scripts')
<script>

$(document).ready(function(){
  $("#frmGenerarPrefactura").submit(function(e){
    return false;
  });
});
$("#btnConsultar").click(function(event){
  traerPrefacturasPendientes();
});
function inicializaTabla(){
  var pendientes = $("#cmbPendientes").val();
  var texto = "";
  if (pendientes == 'SI'){
    texto = "Prefacturas Pendientes";
  }
  else{
    texto = "Todas las Prefacturas"
  }
  $("#tablaPrefactura").html("");
  var contenido = "";
  contenido += '<h4 class="text-center">' + texto + ', Empresa: ' + $("#empresa_id  option:selected").text() + '</h4>';
  contenido += '<div class="container">';
  contenido += '<table id="tblPrefactura" class="table table-hover dataTable no-footer dtr-inline">';
  contenido += '<thead><tr><th>Empresa</th><th>Fecha Inicio</th><th>Fecha Final</th><th>Nro.Orden</th><th>Monto</th><th>Descuento</th><th>Total</th><th>Cantidad Vines</th><th>Acción</th></tr></thead><tbody></tbody>';
  contenido += '</table>';
  contenido += '</div>';
  $("#tablaPrefactura").append(contenido);
}
function traerPrefacturasPendientes(){
  var empresa = $("#empresa_id").val();
  var pendientes = $("#cmbPendientes").val();
  $.ajax({
    url: '{{route('prefacturacion.traePrefacturas')}}',
    method: 'POST',
    async: false,
    dataType: 'json',
    data: {
        empresa: empresa,
        pendientes: pendientes,
        _token: $("input[name='_token']").attr("value")
    },

    success: function (data) {
      inicializaTabla();
      cargarDatosTabla(data);
    }, error: function (jqXHR, error, errorThrown) {
        console.log(jqXHR, error, errorThrown);
        Swal.fire("Error!", "Contacte al administrador", "error");
    }
});
}
function cargarDatosTabla(data){
  var datos = [];
  $.each(data, function (i, item) {
      var obj = [
          item.prefactura_id,
          item.prefactura_fecha_inicio,
          item.prefactura_fecha_final,
          item.prefactura_numero_orden,
          item.monto,
          item.cant_vines,
          item.empresa,
          item.descuento
      ];
      datos.push(obj);
  });
  $('#tblPrefactura').DataTable().destroy();
  $('#tblPrefactura').DataTable({
      data: datos,
      autoWidth: true,
      responsive: false,
      pageLength: datos.length,
      language: {
          "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      },
      dom: 'Bfrtip',
        buttons: [
          {'extend': 'excel', 'text': 'Excel', 'title': 'Prefactura_Cliente'},
          {'extend': 'print', 'text': 'Imprimir'},
        ],
      "autoWidth": true,
      columnDefs: [
                    {
                        targets: [3,4,5, 6, 7],
                        className: 'text-right'
                    }
                  ],
      columns: [
          {data: 6},
          {data: 1},
          {data: 2},
          {data: 3},
          { data: 4,
            render: function(data, type, row){
              return moneda(data);
            }
          },
          { data: 7,
            render: function(data, type, row){
              return moneda(data);
            }
          },
          { data: 4,
            render: function(data, type, row){
              return moneda(data - row[7]);
            }
          },
          {data: 5},
          {
              data : 0,
              render: function ( data, type, row ) {
                var boton = '<div class="btn-group" role="group">';
                boton += '<button class="btn btn-primary" onclick="verPrefactura(' + data + ')">Ver</button>';
                if (row[3] == 0){
                  boton += '<button class="btn btn-secondary" onclick="ingresarNroOc(' + data + ')">Nro.OC</button>';
                }
                boton += '</div>';
                  return boton;
              }
          },
      ]
  });
}
function moneda(num) {
  if(typeof(num) == undefined || isNaN(num) || num == null){
      num = 0;
  }
  return num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
}

function verPrefactura(id){
  $("#modalDetallePrefactura").modal("show");
}
function ingresarNroOc(id){
  $("#modalNroOrden").modal("show");
}


</script>
@endsection
