<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>
    @section('title')
      Intercorp
    @show
  </title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
  <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('assets/img/logos/icono.png') }}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
  <!-- global css -->
  <link href="{{ mix('css/vendor.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ mix('css/app.css')}}" rel="stylesheet" type="text/css">
  <!-- end of global css -->
  @yield('header_styles')
</head>

<body>
<!-- header logo: style can be found in header-->
<header class="header">
  <nav class="navbar navbar-static-top" role="navigation">
    <a href="/dashboard" class="logo">
      <img src="{{asset('assets/img/logo_blue.png')}}" alt="logo" style="width:100%; height:100%;" />
    </a>
    <div>
      <a href="javascript:void(0)" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </div>
    <div class="navbar-right">
      <ul class="nav navbar-nav">
        <li class="dropdown notifications-menu">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
            <i class="far fa-bell black"></i>
            <span class="label label-danger">0</span>
          </a>
          <ul class="dropdown-menu dropdown-notifications table-striped">
            <li class="dropdown-footer"><a href="javascript:void(0)">View All messages</a></li>
          </ul>
        </li>
        <!-- User Account: style can be found in dropdown-->
        <li class="dropdown user user-menu">
          <a href="javascript:void(0)" class="dropdown-toggle padding-user" data-toggle="dropdown">
            <img src="{{asset('assets/img/authors/user.jpg')}}" class="img-circle img-responsive pull-left" alt="User Image">
            <div class="riot">
              <div>
                <i class="caret"></i>
              </div>
            </div>
          </a>
          <ul class="dropdown-menu">
            <!-- User name-->
            <li class="user-name text-center">
              <span>{{auth()->user()->name}}</span>
            </li>
            <!-- Menu Body -->
            <!-- <li class="p-t-3">
                <a href="user_profile">
                    Profile<i class="fa fa-fw fa-user pull-right"></i>
                </a>
            </li>
            <li>
                <a href="edit_user">
                    Settings <i class="fa fa-fw fa-cog pull-right"></i>
                </a>
            </li>
            <li>
                <a href="lockscreen">
                    Lock <i class="fa fa-fw fa-lock pull-right"></i>
                </a>
            </li> -->
            <li>
              <a id="logout-anchor" href="javascript:void(0)">
                Logout <i class="fas fa-sign-out-alt pull-right"></i>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<div class="wrapper">
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="left-aside">
    <!-- sidebar: style can be found in sidebar-->
    <section class="sidebar">
      <div id="menu" role="navigation">
        <div class="nav_profile">
          <div class="media profile-left">
            <a class="pull-left profile-thumb" href="javascript:void(0)">
              <img src="{{asset('assets/img/authors/user.jpg')}}" class="img-circle" alt="User Image">
            </a>
            <div class="content-profile">
              <h4 class="media-heading">{{auth()->user()->name}}</h4>
              <!-- <p>@{{auth()->user()->tipo}}</p> -->
              <p></p>
            </div>
          </div>
        </div>
        <ul class="navigation">

          <!-- Dashboard -->
          <li {!! (Request::is('dashboard') ? 'class="active"' : '') !!}>
            <a href="{{ URL::to('dashboard') }}">
              <i class="menu-icon fas fa-dashboard"></i>
              <span class="mm-text ">Dashboard</span>
            </a>
          </li>

          <!-- Catalogos -->
          <li  {!! (Request::is('tiposClientes*') ||
                    Request::is('clientes*') ||
                    Request::is('proveedores*') ||
                    Request::is('proyectos*') ||
                    Request::is('subproyectos*') ||
                    Request::is('materiales*') ||
                    Request::is('productos*')
                  ? 'class="active"' : '') !!}>
            <a href="javascript:;">
              <i class="menu-icon fas fa-atlas"></i>
              <span>Catálogos</span>
              <span class="fa arrow"></span>
            </a>
            <ul class="sub-menu">
              <li {!! (Request::is('tiposClientes*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('tiposClientes') }}">
                  <i class="fas fa-address-book"></i> Tipos Clientes
                </a>
              </li>
              <li {!! (Request::is('clientes*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('clientes') }}">
                  <i class="fas fa-user-tie"></i> Clientes
                </a>
              </li>
              <li {!! (Request::is('proveedores*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('proveedores') }}">
                  <i class="fas fa-truck-loading "></i> Proveedores
                </a>
              </li>
              <li {!! (Request::is('proyectos*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('proyectos') }}">
                  <i class="fas fa-folder-open "></i> Proyectos
                </a>
              </li>
              <li {!! (Request::is('subproyectos*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('subproyectos') }}">
                  <i class="far fa-folder-open "></i> Sub Proyectos
                </a>
              </li>
              <li {!! (Request::is('materiales*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('materiales') }}">
                  <i class="fas fa-atom "></i> Materiales
                </a>
              </li>
              <li {!! (Request::is('productos*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('productos') }}">
                  <i class="fas fa-parking "></i> Productos
                </a>
              </li>
            </ul>
          </li>

        </ul>
        <!-- / .navigation -->
      </div>
      <!-- menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <aside class="right-aside view-port-height" id="right-aside">
    @yield('content')
  </aside>
  <!-- /.right-side -->
</div>

<footer>
  <div class="row">
    <div class="col-md-offset-2 col-md-8">
      <strong>&#169; 2018 789.mx</strong><br />
      <span>Powered By <a href="http://www.789.mx" target="_blank">789.mx</a></span>
    </div>
    <div class="col-md-2 text-right">
      <a href="#content" class="btn btn-default" style="font-size:16px;">
        <i class="fas fa-arrow-up"></i>
      </a>
    </div>
  </div>
</footer>

<div class="hidden">
	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		{{ csrf_field() }}
	</form>
</div>

<!-- ./wrapper -->
<!-- global js -->
<!-- <script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script> -->
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});
</script>
<!-- end of page level js -->
<!-- page level js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

</html>
