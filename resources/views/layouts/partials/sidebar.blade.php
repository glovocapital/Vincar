<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link brand-image img-circle elevation-3 opacity: .8 brand-text font-weight-light" href="{{route('home')}}">


      <span class="">Vincar</span>
      <h6>Cloud Warehouse & Transport</h6>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->user_nombre.' '.Auth::user()->user_apellido}}</a>
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
                <a href="usuarios" class="nav-link">
                  <i class="far nav-icon"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="empresa" class="nav-link">
                  <i class="far nav-icon"></i>
                  <p>Empresas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="camiones" class="nav-link">
                  <i class="far nav-icon"></i>
                  <p>Camiones</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far nav-icon"></i>
                  <p>Conductores</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="destinos" class="nav-link">
                  <i class="far nav-icon"></i>
                  <p>Destinos</p>
                </a>
              </li>
              <li class="nav-item">
                    <a href="pais" class="nav-link">
                      <i class="far nav-icon"></i>
                      <p>Paises</p>
                    </a>
                  </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
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
                <a href="#" class="nav-link">
                  <i class="far nav-icon"></i>
                  <p>Productos y Servicios</p>
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
                <a href="vin" class="nav-link">
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
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
