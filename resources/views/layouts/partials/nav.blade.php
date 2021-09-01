
<nav id="sidebar" class="sidebar">
  <div class="sidebar-content ">
    <a class="sidebar-brand" href="{{ route('home') }}">
      <span class="align-middle">
        <img width="90%" src="{{asset('base/img/vincar2.png')}}">
      </span>
    </a>

    <ul class="sidebar-nav">
      <li class="sidebar-item active">
        <a href="{{ route('home') }}" class="sidebar-link">
          <i class="align-middle" data-feather="home"></i> <span class="align-middle">{{ trans('menu.Inicio') }}</span>
        </a>
      </li>

    @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2)
      <li class="sidebar-item">
        <a href="#Administrador" data-toggle="collapse" class="sidebar-link">
          <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">{{trans('menu.Administrador')}}</span>
        </a>

        <ul id="Administrador" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
          <li class="sidebar-item @if(request()->route()->getName()=='usuarios.index') active @endif"><a class="sidebar-link" href="{{ route('usuarios.index') }}">{{trans('menu.Usuarios')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='empresa.index') active @endif"><a class="sidebar-link" href="{{ route('empresa.index') }}">{{trans('menu.Clientes')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='camiones.index') active @endif"><a class="sidebar-link" href="{{ route('camiones.index') }}">{{trans('menu.Camiones')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='remolque.index') active @endif"><a class="sidebar-link" href="{{ route('remolque.index') }}">{{trans('menu.Remolques')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='conductores.index') active @endif"><a class="sidebar-link" href="{{ route('conductores.index') }}">{{trans('menu.Conductores')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='destinos.index') active @endif"><a class="sidebar-link" href="{{ route('destinos.index') }}">{{trans('menu.Destinos')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='pais.index') active @endif"><a class="sidebar-link" href="{{ route('pais.index') }}">{{trans('menu.Paises')}}</a></li>

          <li class="sidebar-item @if(request()->route()->getName()=='marcas.index') active @endif"><a class="sidebar-link" href="{{ route('marcas.index') }}">{{trans('menu.Marcas')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='modelos.index') active @endif"><a class="sidebar-link" href="{{ route('modelos.index') }}">{{trans('menu.Modelos')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='proveedor.index') active @endif"><a class="sidebar-link" href="{{ route('proveedor.index') }}">{{trans('menu.Proveedores')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='productos.index') active @endif"><a class="sidebar-link" href="{{ route('productos.index') }}">{{trans('menu.Productos')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='servicios.index') active @endif"><a class="sidebar-link" href="{{ route('servicios.index') }}">{{trans('menu.Servicios')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='patio.index') active @endif"><a class="sidebar-link" href="{{ route('patio.index') }}">{{trans('menu.Patios_Posiciones')}}</a></li>
        </ul>
      </li>
    @endif

    @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2 || auth()->user()->rol_id == 3 || auth()->user()->rol_id == 4)

      <li class="sidebar-item">
        <a href="#Vins" data-toggle="collapse" class="sidebar-link collapsed">
          <i class="align-middle" data-feather="layout"></i> <span class="align-middle">Vins</span>
        </a>
        <ul id="Vins" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
          <li class="sidebar-item @if(request()->route()->getName()=='patio.vins_patio') active @endif"><a class="sidebar-link" href="{{ route('patio.vins_patio') }}">{{trans('menu.Patios')}}</a></li>
          <li class="sidebar-item @if(request()->route()->getName()=='vin.index') active @endif"><a class="sidebar-link" href="{{ route('vin.index') }}">{{trans('menu.Vins')}}</a></li>

          @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2 || auth()->user()->rol_id == 3 || auth()->user()->rol_id == 4)
          <li class="sidebar-item">
            <a href="#projects" data-toggle="collapse" class="sidebar-link collapsed">
              {{trans('menu.Planificacion')}}
            </a>
            <ul id="projects" class="sidebar-dropdown list-unstyled collapse">
                <li class="sidebar-item @if(request()->route()->getName()=='planificacion.index') active @endif">
                    <a class="sidebar-link " href="{{ route('planificacion.index') }}">Tareas</a>
                </li>
            </ul>
          </li>
          @if(auth()->user()->rol_id != 4)
          <li class="sidebar-item @if(request()->route()->getName()=='vin.traspasovin') active @endif"><a class="sidebar-link" href="{{ route('vin.traspasovin') }}">Traspaso de VIN</a></li>
          @endif
          <li class="sidebar-item @if(request()->route()->getName()=='entrega.index') active @endif"><a class="sidebar-link" href="{{ route('entrega.index') }}">Entrega de VIN</a></li>
          @if(auth()->user()->rol_id != 4)
          <li class="sidebar-item @if(request()->route()->getName()=='guia.index') active @endif"><a class="sidebar-link" href="{{ route('guia.index') }}">Guías</a></li>
          @endif
          @endif
        </ul>
      </li>
      @endif



            @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3)
            <li class="sidebar-item">
                <a href="#DanoFaltante" data-toggle="collapse" class="sidebar-link collapsed">
                  <i class="align-middle" data-feather="alert-triangle"></i> <span class="align-middle">{{trans('menu.Dano_Faltante')}}</span>
                </a>
                <ul id="DanoFaltante" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
                <li class="sidebar-item @if(request()->route()->getName()=='inspeccion.index') active @endif"><a class="sidebar-link" href="{{ route('inspeccion.index') }}">{{trans('menu.Dano_Faltante')}}</a></li>

                </li>
            </ul>
        </li>
            @endif



      <li class="sidebar-item">
        <a href="#Servicio" data-toggle="collapse" class="sidebar-link collapsed">
          <i class="align-middle" data-feather="users"></i> <span class="align-middle">{{trans('menu.Solicitud_Servicio')}}</span>
        </a>
        <ul id="Servicio" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">

            @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 4)
                <li class="sidebar-item @if(request()->route()->getName()=='solicitud_campania.index') active @endif">
                    <a class="sidebar-link" href="{{ route('solicitud_campania.index') }}">Campañas</a>
                </li>
            @endif
            @if(auth()->user()->rol_id == 1)
                <li class="sidebar-item"><a class="sidebar-link" href="">{{trans('menu.Rutas_entrega')}}</a></li>
            @endif
        </ul>
      </li>

      @if(auth()->user()->rol_id == 1)
        <li class="sidebar-item">
            <a href="#Transporte" data-toggle="collapse" class="sidebar-link collapsed">
            <i class="align-middle" data-feather="truck"></i> <span class="align-middle">{{trans('menu.Transporte')}}</span>
            </a>
            <ul id="Transporte" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
            <!-- <li class="sidebar-item"><a class="sidebar-link" href="">{{--trans('menu.Generar_Guia')--}}</a></li> -->
            <li class="sidebar-item"><a class="sidebar-link" href="{{ route('tour.index') }}">{{trans('menu.Panel_Tours')}}</a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="{{ route('tour.tour') }}">{{trans('menu.Gestion_viajes')}}</a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="">{{trans('menu.Administracion_Ruta')}}</a></li>
            <!-- <li class="sidebar-item"><a class="sidebar-link" href="">{{--trans('menu.Disponibilidad')--}}</a></li> -->
            </ul>
        </li>
    @endif

    @if(auth()->user()->rol_id == 1)
        <li class="sidebar-item">
            <a href="#PreFacturacion" data-toggle="collapse" class="sidebar-link collapsed">
                <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">{{trans('menu.PreFacturacion')}}</span>
            </a>
            <ul id="PreFacturacion" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
                <li class="sidebar-item">

                <li class="sidebar-item">
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('facturacion.prefactura') }}">{{trans('menu.PreFacturacion')}}</a></li>
                </li>
            </ul>
        </li>
    @endif

    @if(auth()->user()->rol_id == 1)
        <li class="sidebar-item">
            <a href="#KPI" data-toggle="collapse" class="sidebar-link collapsed">
                <i class="align-middle" data-feather="grid"></i> <span class="align-middle">KPI</span>
            </a>
            <ul id="KPI" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
              <li class="sidebar-item"><a class="sidebar-link" href="{{ route('kpi.index') }}">{{trans('menu.KPI')}}</a></li>
            </ul>
        </li>
    @endif

    @if(auth()->user()->rol_id == 1)
        <li class="sidebar-item">
            <a href="#Estadisticas" data-toggle="collapse" class="sidebar-link collapsed">
                <i class="align-middle" data-feather="bar-chart"></i> <span class="align-middle">{{trans('menu.Estadisticas')}}</span>
            </a>
            <ul id="Estadisticas" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">

            </ul>
        </li>
     @endif

    <!-- FIN DE MENU-->
    </ul>
  </div>
</nav>
