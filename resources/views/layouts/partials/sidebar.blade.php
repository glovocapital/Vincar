<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="home" class="brand-link">
    <img src="{{ asset('adminlte/dist/img/logo-128x128.png') }}"
          alt="Logo Vincar"
          class="brand-image img-circle elevation-3"
          style="opacity: .8">
    <span class="brand-text font-weight-light">Vincar</span>
    <h5>Cloud Warehouse & Transport</h5>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{asset('adminlte/dist/img/avatar5.png')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
      @if(AUTH::check())
        <a href="#" class="d-block">{{Auth::user()->user_nombre.' '.Auth::user()->user_apellido}}</a>
      @endif
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-sliders-h"></i>
            <p>
              Administrador
              <i class="right fas fa-angle-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('usuarios.index') }}">
                <i class="far nav-icon"></i>
                <p>Usuarios</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('empresa.index') }}">
                <i class="far nav-icon"></i>
                <p>Clientes</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('camiones.index') }}">
                <i class="far nav-icon"></i>
                <p>Camiones</p>
              </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('remolque.index') }}">
                  <i class="far nav-icon"></i>
                  <p>Remolques</p>
                </a>
              </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far nav-icon"></i>
                <p>Conductores</p>
              </a>
            </li>
            <li class="nav-item">
                  <a class="nav-link" href="{{ route('destinos.index') }}">
            <i class="far nav-icon"></i>
                <p>Destinos</p>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('pais.index') }}">
                  <i class="far nav-icon"></i>
                    <p>Paises</p>
                  </a>
                </li>
            <li class="nav-item">
              <a href="inspeccion" class="nav-link">
                <i class="far nav-icon"></i>
                <p>Daños y Faltantes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far nav-icon"></i>
                <p>Marca / Modelo</p>
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('proveedor.index') }}">
              <i class="far nav-icon"></i>
              <p>Tipo Proveedor</p>
            </a>
          </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('productos.index') }}">
              <i class="far nav-icon"></i>
              <p>Productos</p>
            </a>
          </li>
            <li class="nav-item">
                  <a class="nav-link" href="{{ route('servicios.index') }}">
                <i class="far nav-icon"></i>
                <p>Servicios</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far nav-icon"></i>
                <p>Patio y Posiciones</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-car-alt"></i>
            <p>
              Vins
              <i class="right fas fa-angle-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far nav-icon"></i>
                <p>Patios</p>
              </a>
            </li>
            <li class="nav-item">
                  <a class="nav-link" href="{{ route('vin.index') }}">

                <i class="far nav-icon"></i>
                <p>Vins</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far nav-icon"></i>
                <p>Tareas</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-door-open"></i>
              <p>
                  {{ __('Logout') }}
              </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

@section('sidebar-scripts')
<script>
    /** add active class and stay opened when selected */
$(document).ready(function () {
  var url = window.location;
  const allLinks = document.querySelectorAll('.nav-item a');
  const currentLink = [...allLinks].filter(e => {
    return e.href == url;
  });

  currentLink[0].classList.add("active")
  currentLink[0].closest(".nav-treeview").style.display="block";
  currentLink[0].closest(".has-treeview").classList.add("active");
});
</script>
@endsection
