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
  <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}"/>
  <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('images/favicon.ico') }}">
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
      <img src="{{asset('images/logo.jpg')}}" alt="logo" style="width:100%; height:100%;" />
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
              <p>{{auth()->user()->email}}</p>
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
                    Request::is('categorias*') ||
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
              <li {!! (Request::is('categorias*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('categorias') }}">
                  <i class="fas fa-cubes "></i> Categorias Productos
                </a>
              </li>
              <li {!! (Request::is('productos*') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('productos') }}">
                  <i class="fas fa-parking "></i> Productos
                </a>
              </li>
            </ul>
          </li>

          <!-- Prospectos -->
          <li {!! (Request::is('prospectos*') ? 'class="active"' : '') !!} >
            <a href="{{ URL::to('prospectos') }}">
              <i class="menu-icon fas fa-comments-dollar"></i>
              <span>Prospectos</span>
            </a>
          </li>

          <!-- Cuentas Cobrar -->
          <li {!! (Request::is('cuentas-cobrar*') ? 'class="active"' : '') !!} >
            <a href="{{ URL::to('cuentas-cobrar') }}">
              <i class="menu-icon fas fa-hand-holding-usd"></i>
              <span>Cuentas por Cobrar</span>
            </a>
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
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/accounting@0.4.1/accounting.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.2/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script>
$(function() {
  $('#logout-anchor').on('click', function(){
    $('#logout-form').submit();
  });
  $('[data-toggle="tooltip"]').tooltip();
});
var localeES = {
  uiv: {
    datePicker: {
      clear: 'Limpiar',
      today: 'Hoy',
      month: 'Mes',
      month1: 'Enero',
      month2: 'Febrero',
      month3: 'Marzo',
      month4: 'Abril',
      month5: 'Mayo',
      month6: 'Junio',
      month7: 'Julio',
      month8: 'Agosto',
      month9: 'Septiembre',
      month10: 'Octubre',
      month11: 'Noviembre',
      month12: 'Diciembre',
      year: 'Año',
      week1: 'Lun',
      week2: 'Mar',
      week3: 'Mie',
      week4: 'Jue',
      week5: 'Vie',
      week6: 'Sab',
      week7: 'Dom'
    },
    timePicker: {
      am: 'AM',
      pm: 'PM'
    },
    modal: {
      cancel: 'Cancelar',
      ok: 'OK'
    }
  }
}
var colorPallet = [
  //America’s Top 50 Crayola's Crayon Colors from
  //http://www.sensationalcolor.com/color-resources/favorite-crayon-colors-11997
  //https://www.crayola.com/explore-colors/
  '#0066ff', //Blue 1903
  '#02a4d3', //Cerulean 1990
  '#652dc1', //Purple Heart 1997
  '#003366', //Midnight Blue 1958
  '#458b74', //Aquamarine 195
  '#00cc99', //Caribbean Green 1998
  '#c3cde6', //Periwinkle 1949
  '#1560bd', //Denim 1993
  '#da3287', //Cerise 1993
  '#67c8ff', //battery charge blue 1972
  '#93ccea', //Cornflower 1949
  '#ed0a3f', //Red 1903
  '#0095b6', //Blue Green 1934
  '#ff00cc', //Hot Magenta 1972
  '#6456b7', //Blue Violet 1934
  '#009dc4', //Pacific Blue 1993
  '#d6aedd', //Purple Mountain Majesty 1993
  '#00755e', //Tropical Rain Forest 1993
  '#c62d42', //Brick Red 1949
  '#4f69c6', //Indigo 1999
  '#01a368', //Green 1903
  '#c9a0dc', //Wisteria 1993
  '#5fa777', //Forest Green 1957
  '#6b3fa0', //Royal Purple 1990
  '#c154c1', //Fuchsia 1990
  '#7ba05b', //Asparagus 1993
  '#ff00cc', //Purple Pizzazz 1991
  '#803790', //Vivid Violet 1998
  '#76d7ea', //Sky Blue 1957
  '#843179', //Plum 1957
  '#ccff00', //Electric Lime 1991
  '#0066cc', //Navy Blue 1957
  '#00cccc', //Robin Egg Blue 1993
  '#000000', //Black 1903
  '#008080', //Teal Blue 1990
  '#fd0e35', //Scarlet 1998
  '#ffb7d5', //Cotton Candy 1998
  '#f653a6', //Magenta 1903
  '#9999cc', //Blue Bell 1998
  '#fe6f5e', //Bittersweet 1949
  '#ffa6c9', //Carnation Pink 1949
  '#e30b5c', //Razzmatazz 1993
  '#c9c0bb', //Silver 1949
  '#e97451', //Burnt Sienna 1903
  '#29ab87', //Jungle Green 1990
  '#ff9966', //Atomic Tangerine 1972
  '#9a68af', //ultra violet 1997
  '#01796f', //Pine Green 1949
  '#66ff66', //Screamin Green 1972
  '#ffff66', //Laser Lemon 1972
];
</script>
<!-- end of page level js -->
<!-- page level js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

</html>
