<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class="main-menu-header">
                <img class="img-40 img-radius" src="{{ asset('assets/images/avatar.svg') }}" alt="User-Profile-Image">
                <div class="user-details">
                    <span>{{ Auth::user()->name." ".Auth::user()->surname }}</span>
                    <span id="more-details">{{ Auth::user()->role }}<i class="ti-angle-down"></i></span>
                </div>
            </div>

            <div class="main-menu-content">
                <ul>
                    <li class="more-details">
                        <a href="{{ route('users.show', ['user' => Auth::user()->id]) }}"><i class="ti-user"></i>Detalles de perfil</a>
                        {{-- <a href="#!"><i class="ti-settings"></i>Configuracion</a> --}}
                        <a href="#" id="alert_logout"><i class="ti-layout-sidebar-left"></i>Salir</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">Menu</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="{{(\Request::segment(1)=='dashboard')?'active':''}}">
                <a href="{{ route('dashboard') }}">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{(\Request::segment(1)=='projects')?'active':''}}">
                <a href="{{ route('projects.index') }}">
                    <span class="pcoded-micon"><i class="ti-layers"></i><b>PY</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Proyectos</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{(\Request::segment(1)=='departments')?'active':''}}">
                <a href="{{ route('departments.index') }}">
                    <span class="pcoded-micon"><i class="ti-layers-alt"></i><b>DP</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Departamentos</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{(\Request::segment(1)=='processes')?'active':''}}">
                <a href="{{ route('processes.index') }}">
                    <span class="pcoded-micon"><i class="ti-loop"></i><b>PS</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Procesos</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{(\Request::segment(1)=='users')?'active':''}}">
                <a href="{{ route('users.index') }}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>US</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Usuarios</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>
