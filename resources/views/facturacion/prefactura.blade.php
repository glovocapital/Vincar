@extends('layouts.app')
@section('title','Pre Facturacion')
@section('content')
@include('flash::message')

<div class="col-lg-12">

    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Generar Pre Facturacion</h3>

            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  {!! Form::open(['route'=> 'prefacturacion.generar', 'method'=>'POST', 'id' => 'frmGenerarPrefactura']) !!}
                  <div class="form-group">
                      <label for="empresa_id">Empresa <strong>*</strong></label>
                      {!! Form::select('empresa_id', $empresas, null,['placeholder'=>'Empresa','class'=>'form-control col-sm-9', 'required'=>'required', 'id'=>'empresa_id']) !!}
                      (mostrar pre-facturas pendientes)
                  </div>

                    <div class="form-group">
                        <label for="fecha_ini" >Fecha Inicial <strong>*</strong></label>
                        {!! Form::date('fecha_ini', null, [ 'class'=>'form-control col-sm-9', 'required', 'id'=>'fecha_ini']) !!}
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin" >Fecha Final <strong>*</strong></label>
                        {!! Form::date('fecha_fin', null, [ 'class'=>'form-control col-sm-9', 'required', 'id'=>'fecha_fin']) !!}
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin" >Cobro por Unidades Despachadas<strong>*</strong></label>
                        <select id="cmbTipoPrefactura" class="form-control col-sm-9" >
                          <option value="despachadas">Unidades Despachadas</option>
                          <option value="inventario">Unidades Inventario</option>
                        </select>
                    </div>
                    <div class="text-right pb-5">
                      <button id="btnConsultar" class="btn btn-primary block full-width m-b">Consultar</button>
                      <br><br>
                      *** Generar listado de pre-facturas pendientes en cuanto a OC y Nro de Factura.
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="col-md-4">
                  <div id="lblUltimaPrefactura" style="display:none;">
                    <div class="card">
                      <div class="card-header">
                        Última Prefactura
                      </div>
                      <div class="card-body" id="txtUltimaPrefactura">

                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div id="lblDivisas" style="display:none;"></div>

                  ** botón generar guarda la prefactura y se queda a la espera de la OC
                </div>

                <div id="tablaPrefactura" class="col-sm-12">

                </div><br>
                <div id="descuentoPrefactura" class="col-sm-12 col-md-6 col-lg-4" style="display:none; margin:auto;">
                  <div class="card">
                    <div class="card-header bg-primary text-white">
                      Descuento
                    </div>
                    <div class="card-body">
                      <div class="form-horizontal">
                        <h5 class="card-title">Ingrese los datos del descuento</h5>
                        <label class="label">Ingrese Tipo de Descuento:</label>
                          <select class="form-control" id="cmbTipoDescuento" onchange="tipoDescuento(this.value);">
                            <option selected value="">Seleccione Tipo de Descuento</option>
                            <option value="porcentaje">Porcentaje</option>
                            <option value="monto">Monto</option>
                          </select>
                          <label class="label">Ingrese Valor de Descuento:</label>
                          <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="lblTipoDescuento"></span>
                            <input type="text" class="form-control numeric" id="montoDescuento">
                          </div>
                          <div class="form-group">
                            <label for="txtMotivoDescuento">Motivo del Descuento</label>
                            <textarea class="form-control" id="txtMotivoDescuento" rows="3"></textarea>
                          </div>
                          <div class="col-sm-12 col-lg-12 text-right">
                            <button class="btn btn-primary block" id="btnSimularDescuento" onclick="simularDescuento();">Simular Descuento</button>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-lg-12 text-right">
                  <button class="btn btn-primary block" id="btnGrabarPrefactura" onclick="grabarPrefactura();">Grabar Pre-Factura</button>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('local-scripts')
<script>
var GLOBAL_prefactura = "";
var GLOBAL_prefactura_detalle = "";
var GLOBAL_prefactura_descuento = "";

$(document).ready(function(){
  $("#descuentoPrefactura").hide();
  $("#cmbTipoDescuento").val("");
  $("#btnGrabarPrefactura").prop("disabled", true);
  $("#frmGenerarPrefactura").submit(function(e){
    return false;
  });
  $("#fecha_fin").change(function(){
    traeDivisas();
  });
  $("#empresa_id").change(function(){
    traeTarifasEmpresa();
    traerUltimaPrefactura();
  });
  if($("#fecha_fin").val() != ''){
    traeDivisas();
  }
  if($("#empresa_id").val() != ''){
    traerUltimaPrefactura();
  }

});
function simularDescuento(){
  var tipo = $("#cmbTipoDescuento").val();
  var monto = $("#montoDescuento").val();
  var totalTabla = $("#totalTabla").html();
  var total = totalTabla.replace('.', '');

  if(tipo == 'porcentaje'){
    if(parseInt(monto) > 100){
      alert('El porcentaje de descuento no puede ser mayor a 100 %');
    }
    else{
      var conDescuento = total - ((total * monto) / 100);
      alert('El monto total es de: ' + totalTabla + ', y con el descuento quedaría en: ' + moneda(conDescuento) );
    }
  }
  if(tipo == 'monto'){
    if(parseInt(monto) > parseInt(total)){
      alert('El monto del descuento no puede ser mayor al total prefacturado.');
    }
    else{
      var conDescuento = parseInt(total)-parseInt(monto);
      alert('El monto total es de: ' + totalTabla + ', y con el descuento quedaría en: ' + moneda(conDescuento) );
    }
  }
  if(tipo == ''){
    alert('No se ha seleccionado un tipo de descuento');
  }
}

function grabarPrefactura(){
  var tipoDescuento = $("#cmbTipoDescuento").val();
  var montoDescuento = $("#montoDescuento").val();
  var motivoDescuento = $("#txtMotivoDescuento").val();
  GLOBAL_prefactura_descuento = {
    prefactura_descuento_tipo: tipoDescuento,
    prefactura_descuento_monto: montoDescuento,
    prefactura_descuento_motivo: motivoDescuento,
    prefactura_descuento_user_id: {{ Auth::user()->user_id }}
  }

  var respuesta = confirm('¿Está seguro que desea guardar la prefactura generada?');
  if(respuesta){
    $.ajax({
      url: '{{route('prefacturacion.grabar')}}',
      method: 'POST',
      async: false,
      dataType: 'json',
      data: {
          prefactura: JSON.stringify(GLOBAL_prefactura),
          prefactura_detalle: JSON.stringify(GLOBAL_prefactura_detalle),
          prefactura_descuento: JSON.stringify(GLOBAL_prefactura_descuento),
          _token: $("input[name='_token']").attr("value")
      },

      success: function (data) {
        if (!data || !Object.keys(data).length) {
          // No hay datos
          Swal.fire('Advertencia', 'No se encontraron datos para mostrar<br>Verifique los datos ingresados e intente nuevamente', 'warning');
        }
        else{
          alert("Los datos se grabaron correctamente");
          inicializaTabla();
          $("#descuentoPrefactura").hide();
          $("#btnGrabarPrefactura").prop("disabled", true);
        }
      }, error: function (jqXHR, error, errorThrown) {
          console.log(jqXHR, error, errorThrown);
          Swal.fire("Error!", "Contacte al administrador", "error");
      }
  });
  }
}

$.fn.isValid = function(){
  return this[0].checkValidity();
}
$("#btnConsultar").click(function(event){
  traerPrefactura();
});
function tipoDescuento(valor){
  switch (valor) {
    case "":
      $("#lblTipoDescuento").html("");
      break;
    case "porcentaje":
      $("#lblTipoDescuento").html("%");
      break;
    case "monto":
      $("#lblTipoDescuento").html("$");
      break;

  }
}

function traeTarifasEmpresa(){
  var empresa = $("#empresa_id").val();
}

function traerUltimaPrefactura(){
  var empresa = $("#empresa_id").val();
  $("#lblUltimaPrefactura").show();
  $("#txtUltimaPrefactura").html("");
  $.ajax({
    url: '{{route('prefacturacion.traeUltima')}}',
    method: 'POST',
    async: false,
    dataType: 'json',
    data: {
        empresa: empresa,
        _token: $("input[name='_token']").attr("value")
    },

    success: function (data) {
      if (!data || !Object.keys(data).length) {
        $("#txtUltimaPrefactura").html('<div class="text-danger">No hay prefacturas anteriores</div>');
      }
      else{
        $("#txtUltimaPrefactura").append("Periodo Prefacturación :<br>Desde: <b>" + data[0]['prefactura_fecha_inicio'] + "</b> hasta : <b>" + data[0]['prefactura_fecha_final'] + "</b><br> Número de OC: <b>" + data[0]['prefactura_numero_orden'] + "</b>");
      }
    }, error: function (jqXHR, error, errorThrown) {
        console.log(jqXHR, error, errorThrown);
        Swal.fire("Error!", "Contacte al administrador", "error");
    }
});
}

function traerPrefactura(){
  var fini = $("#fecha_ini").val();
  var ffin = $("#fecha_fin").val();
  var empresa = $("#empresa_id").val();
  var tipo = $("#cmbTipoPrefactura").val();

  GLOBAL_prefactura = {
    prefactura_empresa_id: empresa,
    prefactura_fecha_inicio: fini,
    prefactura_fecha_final: ffin,

    prefactura_user_id_creacion: {{ Auth::user()->user_id }},
    prefactura_user_id_actualizacion: {{ Auth::user()->user_id }}
  }

  if($("#frmGenerarPrefactura").isValid()){
    $("#montoDescuento").val(0);
    $("#txtMotivoDescuento").val("");
      $.ajax({
        url: '{{route('prefacturacion.generar')}}',
        method: 'POST',
        async: false,
        dataType: 'json',
        data: {
            fini: fini,
            ffin: ffin,
            empresa: empresa,
            tipo: tipo,
            _token: $("input[name='_token']").attr("value")
        },

        success: function (data) {
          if (!data || !Object.keys(data).length) {
            // No hay datos
            inicializaTabla();
            Swal.fire('Advertencia', 'No se encontraron datos para mostrar<br>Verifique los datos ingresados e intente nuevamente', 'warning');
          }
          else{
            inicializaTabla();
            cargarDatosTabla(data);
            $("#descuentoPrefactura").show();
            $("#btnGrabarPrefactura").prop("disabled", false);
          }
        }, error: function (jqXHR, error, errorThrown) {
            console.log(jqXHR, error, errorThrown);
            Swal.fire("Error!", "Contacte al administrador", "error");
        }
    });
  }
}

function traeDivisas(){
  // agregar divisa UTM
  $("#lblDivisas").html("");
  $("#lblDivisas").hide();
  var ffin = $("#fecha_fin").val();
  if(ffin != ""){
      $.ajax({
        url: '{{route('prefacturacion.divisasDia')}}',
        method: 'POST',
        async: false,
        dataType: 'json',
        data: {
            fecha:ffin,
            _token: $("input[name='_token']").attr("value")
        },

        success: function (data) {
          if (!data || !Object.keys(data).length) {
            // No hay datos
            console.log('divisasDia: No hay datos');
          }
          else{
            $("#lblDivisas").show();
            $("#lblDivisas").html('');
            var contenido = "";
            $.each(data, function(k,v){
              contenido += "<b>" + v.tipo + "</b>: " + v.valor + " [Fecha: " + v.fecha + "]</br>";
            });
            $("#lblDivisas").html('<div class="card"><div class="card-header">Detalle de Divisas</div><div class="card-body">'+contenido+'</div></div>');
          }
        }, error: function (jqXHR, error, errorThrown) {
            console.log(jqXHR, error, errorThrown);
            Swal.fire("Error!", "Contacte al administrador", "error");
        }
    });
  }
}

function inicializaTabla(){
  // agregar columnas "valor día", "Tamaño de cobro" y "total valorizado"

  $("#tablaPrefactura").html("");
  var contenido = "";
  contenido += '<h4 class="text-center">Prefacturación de Estadía Cliente: ' + $("#empresa_id  option:selected").text() + '</h4>';
  contenido += '<div class="container">';
  contenido += '<table id="tblPrefactura" class="table table-hover dataTable no-footer dtr-inline">';
  contenido += '<thead><tr><th>Vin</th><th>Marca</th><th>Modelo</th><th>Fecha Inicio</th><th>Fecha Final</th><th>Cantidad Días</th><th>Valor</th><th>Total</th></tr></thead><tbody></tbody>';
  contenido += '<tfoot><tr><th colspan="7" style="text-align:right">Total:</th><th id="totalTabla" style="text-align:right"></th></tr></tfoot></table>';
  contenido += '</div>';
  $("#tablaPrefactura").append(contenido);
}

function cargarDatosTabla(data){
  var datos = [];
  $("#totalTabla").html(0);
  var totalTabla = 0;
  $.each(data, function (i, item) {
      var obj = [
          item.vin,
          item.marca_nombre,
          item.vin_modelo,
          item.dias,
          item.tarifa,
          item.fini,
          item.ffin,
          item.vin_id
      ];
      totalTabla += (parseInt(item.dias) * parseInt(item.tarifa));
      datos.push(obj);
  });
  GLOBAL_prefactura_detalle = datos;
  $("#totalTabla").html(moneda(totalTabla));
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
                        targets: [3,4,5],
                        className: 'text-right'
                    }
                  ],
      columns: [
          {data: "0"},
          {data: "1"},
          {data: "2"},
          {data: "5"},
          {data: "6"},
          {data: "3"},
          {
              data : 4,
              render: function ( data, type, row ) {
                  return data;
              }
          },
          {
              data : 0,
              render: function ( data, type, row ) {
                var total = parseInt(row[3]) * parseInt(row[4]);
                var color = '';
                if(total <= 0){
                  color = 'text-danger';
                }
                return '<span class="'+color+'">' + total + '</span>';
              }
          }
      ]

  });
}
function moneda(num) {
  if(typeof(num) == undefined || isNaN(num) || num == null){
      num = 0;
  }
  return num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
}
</script>
@endsection
