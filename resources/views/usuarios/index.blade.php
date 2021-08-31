@extends('layouts.app')
@section('title','Usuarios index')
@section('content')
@include('flash::message')

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuario</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="text-right pb-5">
                            <button id='nuevo_usuario' class="btn btn-primary block full-width m-b ml-5 mt-4">Nuevo Usuario</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Listado de empresas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                    </div>
                </div>
                <div class="card-body overflow-auto">
                <!--   <div class="col-lg-12 pb-3 pt-2">
                            <a href="{{ route('usuarios.create') }}" class = 'btn btn-primary'>Crear nuevo Usuario</a>
                        </div>
                -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm nowrap" id="dataTableUsuarios" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Nombres</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Empresa</th>
                                <th>Acci&oacute;n</th>
                                <!-- <th>Desactivar</th>  -->
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($usuarios as $us)
                                <tr>
                                    <td><small>{{ $us->user_nombre . ' ' . $us->user_apellido }}</small></td>
                                    <td><small>{{ $us->email }}</small></td>
                                    <td><small>{{ $us->oneRol->rol_desc }}</small></td>
                                    <td><small>{{ $us->belongsToEmpresa->empresa_razon_social }}</small></td>
                                    <td>
                                        <small>
                                            <a href="{{ route('usuarios.edit',  Crypt::encrypt($us->user_id)) }}" class="btn-empresa"><i class="far fa-edit"></i></a>
                                        </small>
                                        <small>
                                            <a href = "{{ route('usuarios.destroy', Crypt::encrypt($us->user_id))  }}" onclick="return confirm('¿Esta seguro que desea eliminar este elemento?')" class="btn-empresa"><i class="far fa-trash-alt"></i>
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
    @include('usuarios.partials.modal_nuevo_usuario')
@endsection

@section('local-scripts')
    <script>
        $(function(){
            $('.rut').keyup(function(){
                $("#validador").html('<span style="color:red;" aria-hidden="true">&times;</span>');
                var Ts = $(this).val().split("-");
                var T = Ts[0];

                var M=0,S=1;
                for(;T;T=Math.floor(T/10))
                    S=(S+T%10*(9-M++%6))%11;

                if(Ts[0].length==7 || Ts[0].length==8){

                    if(Ts.length == 2){
                        if(S-1 == Ts[1]){
                            $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                        } else if ((S-1 == -1) && ((Ts[1] == 'K') ||(Ts[1] == 'k'))) {
                            $("#validador").html('<i style="color:green"  class="fa fa-check"></i>');
                        }
                    }
                }
            });

            setTimeout(function(){
                $('.rut').trigger("keyup");
            },1000);
        });

    $(document).ready(function() {
        $('#nuevo_usuario').on('click', (e) => {
            e.preventDefault();

            $("#formNuevoUsuario")[0].reset();

            $("#nuevoUsuario").modal('show');
        });

        datatablesButtons = $('[id="dataTableUsuarios"]').DataTable({
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
    } );
    </script>
@endsection
