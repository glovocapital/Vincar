<nav class="navbar navbar-expand navbar-light bg-white">
  <a class="sidebar-toggle d-flex mr-2">
    <i class="hamburger align-self-center"></i>
  </a>


  <div class="navbar-collapse collapse">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-toggle="dropdown">
          <div class="position-relative">
            <i class="align-middle" data-feather="message-circle"></i>
            <span class="indicator">3</span>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="messagesDropdown">
          <div class="dropdown-menu-header">
            <div class="position-relative">
              3 Nuevos mensajes
            </div>
          </div>
          <div class="list-group">
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <img src="{{ asset('base/img/avatars/avatar-5.jpg') }}" class="avatar img-fluid rounded-circle" alt="Ashley Briggs">
                </div>
                <div class="col-10 pl-2">
                  <div class="text-dark">Ashley Briggs</div>
                  <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
                  <div class="text-muted small mt-1">15m ago</div>
                </div>
              </div>
            </a>
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <img src="{{ asset('base/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded-circle" alt="Carl Jenkins">
                </div>
                <div class="col-10 pl-2">
                  <div class="text-dark">Carl Jenkins</div>
                  <div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
                  <div class="text-muted small mt-1">2h ago</div>
                </div>
              </div>
            </a>
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <img src="{{ asset('base/img/avatars/avatar-4.jpg') }}" class="avatar img-fluid rounded-circle" alt="Stacie Hall">
                </div>
                <div class="col-10 pl-2">
                  <div class="text-dark">Stacie Hall</div>
                  <div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
                  <div class="text-muted small mt-1">4h ago</div>
                </div>
              </div>
            </a>
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <img src="{{ asset('base/img/avatars/avatar-3.jpg') }}" class="avatar img-fluid rounded-circle" alt="Bertha Martin">
                </div>
                <div class="col-10 pl-2">
                  <div class="text-dark">Bertha Martin</div>
                  <div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
                  <div class="text-muted small mt-1">5h ago</div>
                </div>
              </div>
            </a>
          </div>
          <div class="dropdown-menu-footer">
            <a href="#" class="text-muted">Mostrar todos los mensajes</a>
          </div>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-toggle="dropdown">
          <div class="position-relative">
            <i class="align-middle" data-feather="bell-off"></i>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="alertsDropdown">
          <div class="dropdown-menu-header">
            4 New Notifications
          </div>
          <div class="list-group">
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <i class="text-danger" data-feather="alert-circle"></i>
                </div>
                <div class="col-10">
                  <div class="text-dark">Update completed</div>
                  <div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
                  <div class="text-muted small mt-1">2h ago</div>
                </div>
              </div>
            </a>
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <i class="text-warning" data-feather="bell"></i>
                </div>
                <div class="col-10">
                  <div class="text-dark">Lorem ipsum</div>
                  <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
                  <div class="text-muted small mt-1">6h ago</div>
                </div>
              </div>
            </a>
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <i class="text-primary" data-feather="home"></i>
                </div>
                <div class="col-10">
                  <div class="text-dark">Login from 192.186.1.1</div>
                  <div class="text-muted small mt-1">8h ago</div>
                </div>
              </div>
            </a>
            <a href="#" class="list-group-item">
              <div class="row no-gutters align-items-center">
                <div class="col-2">
                  <i class="text-success" data-feather="user-plus"></i>
                </div>
                <div class="col-10">
                  <div class="text-dark">New connection</div>
                  <div class="text-muted small mt-1">Anna accepted your request.</div>
                  <div class="text-muted small mt-1">12h ago</div>
                </div>
              </div>
            </a>
          </div>
          <div class="dropdown-menu-footer">
            <a href="#" class="text-muted">Show all notifications</a>
          </div>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-flag dropdown-toggle" href="#" id="languageDropdown" data-toggle="dropdown">
          @if(Session::get('lang')=="en")
            <img src="{{ asset('base/img/flags/us.png') }}" alt="Spanish" />
          @else
            <img src="{{ asset('base/img/flags/es.png') }}" alt="Spanish" />
          @endif

        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown">
          <a class="dropdown-item" href="{{ url('lang', ['en']) }}">
            <img src="{{ asset('base/img/flags/us.png') }}"  alt="English" width="20" class="align-middle mr-1" />
            <span class="align-middle">{{trans('menu.Ingles')}}</span>
          </a>
          <a class="dropdown-item" href="{{ url('lang', ['es']) }}">
            <img src="{{ asset('base/img/flags/es.png') }}"  alt="Spanish" width="20" class="align-middle mr-1" />
            <span class="align-middle">{{trans('menu.Espanol')}}</span>
          </a>

        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown">
          <i class="align-middle" data-feather="settings"></i>
        </a>

        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
          <img src="{{ asset('base/img/avatars/usuario.png') }}"  class="avatar img-fluid rounded-circle mr-1" alt="Usuario" /> <span class="text-dark">
            @if(AUTH::check())
              {{Auth::user()->user_nombre.' '.Auth::user()->user_apellido}}
            @endif
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href=""><i class="align-middle mr-1" data-feather="user"></i> {{trans('menu.Perfil')}}</a>

          <div class="dropdown-divider"></div>

          <a class="dropdown-item" href="#">{{trans('menu.Ayuda')}}</a>

          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            {{trans('menu.Salir')}}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </div>
</nav>