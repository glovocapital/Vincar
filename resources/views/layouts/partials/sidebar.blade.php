<nav class="navbar navbar-expand navbar-light bg-white">
  <a class="sidebar-toggle d-flex mr-2">
    <i class="hamburger align-self-center"></i>
  </a>


  <div class="navbar-collapse collapse">
    <ul class="navbar-nav ml-auto">
      @if(request()->route()->getName()=='home')
        @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 2 || auth()->user()->rol_id == 3 || auth()->user()->rol_id == 4)
          <li class="nav-item dropdown">
            <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-toggle="dropdown">
              <div class="position-relative">
                <i class="align-middle" data-feather="message-circle"></i>
                <span class="indicator">{{$cantidad}}</span>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="messagesDropdown">

              <div class="list-group">

                @foreach($lastTasks as $task)

                <a href="#" class="list-group-item">
                  <div class="row no-gutters align-items-center">
                    <div class="col-2">
                      <img src="{{ asset('base/img/avatars/usuario.png') }}" class="avatar img-fluid rounded-circle" alt="{{$tar->user_nombre}} {{$tar->user_apellido}}">
                    </div>
                    <div class="col-10 pl-2">
                      <div class="text-dark">{{$task->user_nombre}} {{$task->user_apellido}}</div>
                      <div class="text-muted small mt-1">{{$task->tipo_tarea_descripcion}} [{{$task->vin_codigo}}]</div>
                      <div class="text-muted small mt-1">{{$task->tarea_fecha_finalizacion}}</div>
                    </div>
                  </div>
                </a>

                @endforeach


              </div>
              <div class="dropdown-menu-footer">
                <a href="#" class="text-muted">Mostrar todos los mensajes</a>
              </div>
            </div>
          </li>
        @endif
      @endif




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
