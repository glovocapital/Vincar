@extends('layouts.app')
@section('title','Empresa index')
@section('content')
@include('flash::message')

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="card card-default">
            <div class="card-header">
                <div class="card-title float-left mt-3">Clientes</div>
                <div class="float-right mt-3">
                    <button id='nuevo_cliente' class="btn btn-primary block full-width m-b mb-3">Nuevo Cliente</button>
                </div>
            </div>

            <div class="card-body overflow-auto">
            <!--   <div class="col-lg-12 pb-3 pt-2">
                        <a href="{{  route('empresa.create') }}" class = 'btn btn-primary'>Crear nueva Empresa</a>
                    </div>
            -->
                <div class="table-responsive">
                    <table class="table table-hover table-sm nowrap" id="dataTableEmpresas" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Giro</th>
                                <th>Rut</th>
                                <th>Dirección</th>
                                <th>Pais</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($empresa as $emp)
                            <tr>
                                <td><small>{{ $emp->empresa_razon_social }}</small></td>
                                <td><small>{{ $emp->empresa_giro }}</small></td>
                                <td><small>{{ $emp->empresa_rut }}</small></td>
                                <td><small>{{ $emp->empresa_direccion }}</small></td>
                                <td><small>{{ $emp->onePais->pais_nombre }}</small></td>
                                <td><small>{{ $emp->empresa_telefono_contacto }}</small></td>
                                <td><small>{{ $emp->empresa_email_contacto }}</small></td>

                                <td>
                                    <small>
                                        <a href="{{ route('empresa.edit', Crypt::encrypt($emp->empresa_id)) }}" class="btn-empresa"  title="Editar"><i class="far fa-edit"></i></a>
                                    </small>
                                    <small>
                                        <a href = "{{ route('empresa.destroy', Crypt::encrypt($emp->empresa_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
                                        </a>
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('empresa.partials.modal_nuevo_cliente')
@stop

<!--Funcion para ocultar y mostrar input segun seleccion-->
@section('local-scripts')
<script language="javascript" type="text/javascript">
    function d1(button) {
        if(button.value == 'false') {
            $('#bloque_archivo').hide();
            // document.getElementById('archivo').disabled = true;
        } else if (button.value == 'true') {
            $('#bloque_archivo').show();
        // document.getElementById('archivo').disabled = false;
        }
    }

    $(function() {
        $('.rut').keyup(function(){
            $("#validador").html('<span style="color:red;" aria-hidden="true">&times;</span>');

            var Ts = $(this).val().split("-");
            var T = Ts[0];
            var M=0,S=1;

            for(;T;T=Math.floor(T/10)) {
                S=(S+T%10*(9-M++%6))%11;
            }
            //return S?S-1:'k';

            if(Ts[0].length==7 || Ts[0].length==8) {
                if(Ts.length == 2) {
                    if(S-1 == Ts[1]) {
                        $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                    } else if ((S-1 == -1) && ((Ts[1] == 'K') ||(Ts[1] == 'k'))) {
                        $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                    }
                }
            }
        });
    });

    $(document).ready(function() {
        $('#nuevo_cliente').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoCliente")[0].reset();

            $("#nuevoCliente").modal('show');
        });

        datatablesButtons = $('[id="dataTableEmpresas"]').DataTable({
            searching: true,
            bSortClasses: false,
            deferRender:true,
            responsive: false,
            lengthChange: !1,
            pageLength: 10,
            @if(Session::get('lang')=="es")
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            @endif
        });
    });
</script>
<!--Fin Funcion para ocultar y mostrar input segun seleccion-->
@endsection
