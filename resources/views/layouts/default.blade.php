<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            ROBINSON
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('images/favicon.ico') }}">
    <!--menu desarrollo-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <script src="{{ url('plugin/select2/select2.full.min.js') }}"></script>
  <![endif]-->
    <!-- global css -->
    <link href="{{ url('plugin/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
    <!-- end of global css -->
    @yield('header_styles')
</head>

<body>
    <!-- header logo: style can be found in header-->
    <header class="header">
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="/dashboard" class="logo">
                <img src="{{ asset('images/robinson-negro.png') }}" alt="logo" style="width:100%; height:100%;" />
            </a><br>
            <div>
                <a href="javascript:void(0)" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <!--i class="far fa-bell black"--></i>
                            <!--span class="label label-danger">0</span-->
                        </a>
                        <ul class="dropdown-menu dropdown-notifications table-striped">
                            <li class="dropdown-footer"><a href="javascript:void(0)">View All messages</a></li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown-->
                    <li class="dropdown user user-menu">
                        <a href="javascript:void(0)" class="dropdown-toggle padding-user" data-toggle="dropdown">
                            <img src="{{ asset('assets/img/authors/user.jpg') }}"
                                class="img-circle img-responsive pull-left" alt="User Image">
                            <div class="riot">
                                <div>
                                    <i class="caret"></i>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User name-->
                            <li class="user-name text-center">
                                <span style="color:#000;">{{ auth()->user()->name }}</span>
                            </li>
                            <!-- Menu Body -->
                            <li class="p-t-3">
                                <a href="{{ URL::to('mi_cuenta') }}">
                                    Mi Cuenta
                                    <i class="far fa-user-circle pull-right"
                                        style="font-size:16px; margin-top:3px;"></i>
                                </a>
                            </li>
                            <!-- <li>
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
                                    Salir <i class="fas fa-sign-out-alt pull-right"></i>
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
                                <img src="{{ asset('assets/img/authors/user.jpg') }}" class="img-circle"
                                    alt="User Image">
                            </a>
                            <div class="content-profile">
                                <h4 class="media-heading" style="color:#B08C62">{{ auth()->user()->name }}</h4>
                                <p>{{ auth()->user()->email }}</p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <ul class="navigation nav side-menu">
                        <!-- Dashboard -->
                        <li {!! Request::is('dashboard') ? 'class="active"' : '' !!}>
                            <a href="{{ URL::to('dashboard') }}">
                                <i class="menu-icon fas fa-home" style="color:#B08C62; font-size: 20px;"></i>
                                <span class="mm-text" style="color:#9F9F9F;">INICIO</span>
                            </a>
                        </li>

                        <hr class="divider-menu">
                        <!-- Proyectos que son prospectos-->
                        @can('Prospectos menu')
                            <li {!! Request::is('prospectos/prospectos*') ? 'class="active"' : '' !!}>
                                <a href="{{ URL::to('prospectos/prospectos') }}">
                                    <i class="menu-icon fas fa-calendar-check" style="color:#B08C62; font-size: 20px;"></i>
                                    <span class="mm-text" style="color:#9F9F9F;">Prospectos</span>
                                </a>
                            </li>
                        @endcan
                        @can('Proyectos menu')
                            <hr class="divider-menu">
                            <li {!! Request::is('prospectos/cotizaciones*') ? 'class="active"' : '' !!}>
                                <a href="{{ URL::to('prospectos/cotizaciones') }}">
                                    <i class="menu-icon fas fa-calendar-check" style="color:#B08C62; font-size: 20px;"></i>
                                    <span class="mm-text " style="color:#9F9F9F;">Proyectos</span>
                                </a>
                            </li>
                        @endcan
                        <!-- Proyectos
                          <li {!! Request::is('prospectos/cotizaciones*') ? 'class="active"' : '' !!}><a class="title"><i class="fas fa-calendar-check" style="color:#06a1ce; font-size: 20px;" ></i>  Proyectos <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                              <li {!! Request::is('prospectos/cotizaciones*') ? 'class="active"' : '' !!}>
                                <a href="{{ URL::to('prospectos/cotizaciones') }}" >
                                <i class="fas fa-address-book"></i>Lista de Proyectos
                                </a>
                              </li>
                            </ul>

                          </li>
                        -->
                        @can('Cotizaciones Menu')
                            <hr class="divider-menu">
                            <li {!! Request::is('prospectos*') ? 'class="active"' : '' !!}>
                                <a href="{{ URL::to('prospectos') }}">
                                    <i class="menu-icon fas fa-shopping-bag" style="color:#B08C62; font-size: 20px;"></i>
                                    <span class="mm-text" style="color:#9F9F9F;">Cotizaciones Aprobadas</span>
                                </a>
                            </li>

                            <hr class="divider-menu">
                            <li {!! Request::is('prospectos*') ? 'class="active"' : '' !!}>
                                <a href="{{ URL::to('cotizacionesdirectas') }}">
                                    <i class="menu-icon fas fa-shopping-bag" style="color:#B08C62; font-size: 20px;"></i>
                                    <span class="mm-text" style="color:#9F9F9F;">Cotizaciones Directas</span>
                                </a>
                            </li>
                        @endcan


                        <!--

            <hr class="divider-menu">

            -->

                        <!-- ordenes compra-->

                        <!--
          <li {!! Request::is('proyectos-aprobados*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('proyectos-aprobados') }}">
              <i class="menu-icon fas fa-cart-arrow-down" style="color:#907ff3; font-size: 20px;"></i>
              <span class="mm-text ">Ordenes</span>
            </a>
          </li>
        -->


                        {{-- @hasanyrole('Administrador|Usuario') --}}
                            <li {!! Request::is('ordenes-proceso*') ? 'class="active"' : '' !!} {!! Request::is('proyectos-aprobados*') ? 'class="active"' : '' !!}><a class="title"><i
                                        class="fa fa-cart-arrow-down" style="color:#B08C62; font-size: 20px;"></i> <b
                                        style="color:#9F9F9F;">Ordenes Compra </b><span class="fa fa-chevron-down"
                                        style="color:#9F9F9F;"></span></a>
                                <ul class="nav child_menu">
                                    <li {!! Request::is('proyectos-aprobados*') ? 'class="active"' : '' !!}>
                                        <a href="{{ URL::to('proyectos-aprobados') }}">
                                            <i class="menu-icon fas fa-file-signature"></i>
                                            <span style="color:#000;">Lista de Ordenes</span>
                                        </a>
                                    </li>
                                    <li {!! Request::is('ordenes-proceso*') ? 'class="active"' : '' !!}>
                                        <a href="{{ URL::to('ordenes-proceso') }}">
                                            <i class="menu-icon fas fa-clipboard-list"></i>
                                            <span style="color:#000;">Logística</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <hr class="divider-menu">
                        {{-- @endhasanyrole --}}


                        <!--
          @hasanyrole('Administrador|Usuario')
    <li {!! Request::is('cuentas-cobrar*') ? 'class="active"' : '' !!} {!! Request::is('cuentas-pagar*') ? 'class="active"' : '' !!}><a class="title"><i class="fa fa-calculator" style="color:#B08C62; font-size:20px;"></i>  <b style="color:#9F9F9F;">Contabilidad</b><span class="fa fa-chevron-down" style="color:#9F9F9F;"></span></a>
                                                       <ul class="nav child_menu">
                                                          <li {!! Request::is('cuentas-cobrar*') ? 'class="active"' : '' !!}>
                                                            <a href="{{ URL::to('cuentas-cobrar') }}">
                                                              <i class="menu-icon fas fa-hand-holding-usd"></i>
                                                              <span style="color:#000;">Cuentas por Cobrar</span>
                                                            </a>
                                                          </li>
                                                          <li {!! Request::is('cuentas-pagar*') ? 'class="active"' : '' !!}>
                                                            <a href="{{ URL::to('cuentas-pagar') }}">
                                                              <i class="menu-icon fas fa-receipt"></i>
                                                              <span style="color:#000;">Cuentas por Pagar</span>
                                                            </a>
                                                          </li>
                                                        </ul>
                                                      </li>
                                                      <hr class="divider-menu">
@endhasanyrole
        -->
                        @hasanyrole('Administrador|Usuario')
                            <!--Reportes

                                                      <li {!! Request::is('reportes*') ? 'class="active"' : '' !!}><a class="title"><i class="fas fa-file" style="color:#B08C62; font-size: 20px;"></i> <b style="color:#9F9F9F;">Reportes</b> <span class="fa fa-chevron-down" style="color:#9F9F9F;"></span></a>
                                                       <ul class="nav child_menu">
                                                          <li  {!! Request::is('reportes/cotizaciones') ? 'active' : '' !!}">
                                                        <a href="{{ URL::to('reportes/cotizaciones') }}">
                                                          <i class="fas fa-file-invoice-dollar" ></i> <span style="color:#000;">Cotizaciones</span>
                                                        </a>
                                                      </li>
                                                      <li {!! Request::is('reportes/cobros') ? 'class="active"' : '' !!}>
                                                        <a href="{{ URL::to('reportes/cobros') }}">
                                                          <i class="fas fa-file-invoice-dollar" ></i> <span style="color:#000;">Cobros</span>
                                                        </a>
                                                      </li>
                                                      <li >
                                                        <a href="{{ URL::to('reportes/compras') }}">
                                                          <i class="fas fa-file-invoice-dollar" ></i> <span style="color:#000;">Compras</span>
                                                        </a>
                                                      </li>
                                                      <li >
                                                        <a href="{{ URL::to('reportes/pagos') }}">
                                                          <i class="fas fa-file-invoice-dollar" ></i> <span style="color:#000;">Pagos</span>
                                                        </a>
                                                      </li>
                                                      <li >
                                                        <a href="{{ URL::to('reportes/saldoProveedores') }}">
                                                          <i class="fas fa-file-invoice-dollar"></i> <span style="color:#000;">Saldo Proveedores</span>
                                                        </a>
                                                      </li>
                                                      <li >
                                                        <a href="{{ URL::to('reportes/cuentaCliente') }}">
                                                          <i class="fas fa-file-invoice-dollar" ></i> <span style="color:#000;">Estado de Cuenta</span>
                                                        </a>
                                                      </li>
                                                      <li >
                                                        <a href="{{ URL::to('reportes/utilidades') }}">
                                                          <i class="fas fa-file-invoice-dollar" ></i> <span style="color:#000;">Reporte de operaciones</span>
                                                        </a>
                                                      </li>
                                                        </ul>
                                                      </li>
                                                      -->
                        @endhasanyrole


                        <!-- Administracion
          @hasanyrole('Administrador')
    <li  {!! Request::is('usuarios*') ? 'class="active"' : '' !!}>
                                                        <a href="javascript:;">
                                                          <i class="menu-icon fas fa-laptop"></i>
                                                          <span>Administracion</span>
                                                          <span class="fa arrow"></span>
                                                        </a>
                                                        <ul class="sub-menu">
                                                          <li {!! Request::is('usuarios*') ? 'class="active"' : '' !!}>
                                                            <a href="{{ URL::to('usuarios') }}">
                                                              <i class="fas fa-user"></i> Usuarios
                                                            </a>
                                                          </li>
                                                        </ul>
                                                      </li>
                                                      <hr class="divider-menu">
@endhasanyrole
          -->
                        <!--
          @hasanyrole('Administrador|Usuario')
    Catalogos
                                                      <li {!! Request::is('clientes*') ? 'class="active"' : '' !!} {!! Request::is('proveedores*') ? 'class="active"' : '' !!} {!! Request::is('agentesAduanales*') ? 'class="active"' : '' !!} {!! Request::is('productos*') ? 'class="active"' : '' !!}><a class="title"><i class="fa fa-signal" style="color:#f54c82; font-size: 20px;"></i>  Administración <span class="fa fa-chevron-down"></span></a>
                                                        <ul class="nav child_menu">
                                                          <li  {!! Request::is('clientes*') ? 'class="active"' : '' !!}>
                                                            <a href="{{ URL::to('clientes') }}">
                                                              <i class="fas fa-user-tie"></i> Clientes
                                                            </a>
                                                          </li>
                                                          <li {!! Request::is('proveedores*') ? 'class="active"' : '' !!}>
                                                            <a href="{{ URL::to('proveedores') }}">
                                                              <i class="fas fa-truck-loading " ></i> Proveedores
                                                            </a>
                                                          </li>

                                                        <li  {!! Request::is('agentesAduanales*') ? 'class="active"' : '' !!}>
                                                          <a href="{{ URL::to('agentesAduanales') }}">
                                                            <i class="fas fa-warehouse" ></i> Agentes Aduanales
                                                          </a>
                                                        </li>

                                                          <li {!! Request::is('productos*') ? 'class="active"' : '' !!}>
                                                            <a href="{{ URL::to('productos') }}">
                                                              <i class="fas fa-parking " ></i> Producto
                                                            </a>
                                                          </li>
                                                        </ul>
                                                      </li>
                                                      <hr class="divider-menu">
@endhasanyrole
          -->

                        <!--
          <li class="sub" {!! Request::is('tiposClientes*') ? 'class="active"' : '' !!}>
            <a href="{{ route('prospectos.create') }}">
              <i class="fas fa-address-book"></i> Nueva Cotización
            </a>
          </li>

          <li class="sub" {!! Request::is('clientes*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('prospectos') }}">
              <i class="fas fa-user-tie"></i> Cotizaciones Realizadas
            </a>
          </li>
          -->

                        <hr class="divider-menu">
                        <!--Catalogos de Apoyo-->

                        @can('Catalogos menu')
                            <li {!! Request::is('vendedores*') ? 'class="active"' : '' !!} {!! Request::is('Usuarios*') ? 'class="active"' : '' !!} {!! Request::is('tiposClientes*') ? 'class="active"' : '' !!}
                                {!! Request::is('categoriaClientes*') ? 'class="active"' : '' !!} {!! Request::is('proyectos*') ? 'class="active"' : '' !!} {!! Request::is('tiposProveedores*') ? 'class="active"' : '' !!}
                                {!! Request::is('unidadesMedida*') ? 'class="active"' : '' !!} {!! Request::is('subcategorias*') ? 'class="active"' : '' !!} {!! Request::is('categorias*') ? 'class="active"' : '' !!}
                                {!! Request::is('clientes*') ? 'class="active"' : '' !!} {!! Request::is('proveedores*') ? 'class="active"' : '' !!} {!! Request::is('agentesAduanales*') ? 'class="active"' : '' !!}
                                {!! Request::is('productos*') ? 'class="active"' : '' !!}>
                                <a class="title" style="cursor:pointer;"><i class="fa fa-book"
                                        style="color:#B08C62; font-size: 20px;"></i> <b
                                        style="color:#9F9F9F;">Catálogos</b><span class="fa fa-chevron-down"
                                        style="color:#9F9F9F;"></span></a>
                                <ul class="nav child_menu">
                                    @can('Clientes menu')
                                        <li {!! Request::is('clientes*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('clientes') }}">
                                                <i class="fas fa-user-tie"></i> <span style="color:#000;">Clientes</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Categoria clientes menu')
                                        <li {!! Request::is('categoriaClientes*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('categoriaClientes') }}">
                                                <i class="fas fa-folder-open"></i> <span style="color:#000;">Categoría de
                                                    Cliente</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Tipo clientes menu')
                                        <li {!! Request::is('tiposClientes*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('tiposClientes') }}">
                                                <i class="fas fa-address-book"></i> <span style="color:#000;">Tipo de
                                                    Clientes</span>
                                            </a>
                                        </li>
                                    @endcan
                                    <hr class="divider-menu">
                                    @can('Proveedores menu')
                                        <li {!! Request::is('proveedores*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('proveedores') }}">
                                                <i class="fas fa-truck-loading "></i> <span
                                                    style="color:#000;">Proveedores</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Tipos de proveedores menu')
                                        <li {!! Request::is('tiposProveedores*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('tiposProveedores') }}">
                                                <i class="fas fa-address-book"></i> <span style="color:#000;">Tipos de
                                                    Proveedores</span>
                                            </a>
                                        </li>
                                    @endcan
                                    <hr class="divider-menu">
                                    @can('Vendedores menu')
                                        <li {!! Request::is('vendedores*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('vendedores') }}">
                                                <i class="fas fa-address-card "></i> <span
                                                    style="color:#000;">Diseñadores</span>
                                            </a>
                                        </li>
                                    @endcan
                                    <hr class="divider-menu">
                                    @can('Productos menu')
                                        <li {!! Request::is('productos*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('productos') }}">
                                                <i class="fas fa-parking "></i> <span style="color:#000;">Producto</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Categoria de productos menu')
                                        <!--Se ajusto el nombre -->
                                        <li {!! Request::is('subcategorias*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('subcategorias') }}">
                                                <i class="fas fa-folder-open"></i> <span style="color:#000;">Categoria de
                                                    Productos</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Tipo de producto menu')
                                        <li {!! Request::is('categorias*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('categorias') }}">
                                                <i class="fas fa-cubes "></i> <span style="color:#000;">Tipo de
                                                    Producto</span>
                                            </a>
                                        </li>
                                    @endcan
                                    <hr class="divider-menu">
                                    @can('Categoria de proyectos menu')
                                        <li {!! Request::is('proyectos*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('proyectos') }}">
                                                <i class="fas fa-folder-open"></i> <span style="color:#000;">Categoria de
                                                    Proyectos</span>
                                            </a>
                                        </li>
                                    @endcan
                                    <hr class="divider-menu">
                                    <!--li  {!! Request::is('subproyectos*') ? 'class="active"' : '' !!}>
                                                          <a href="{{ URL::to('subproyectos') }}">
                                                            <i class="far fa-folder-open "></i> Tipo de Proyecto
                                                          </a>
                                                        </li-->
                                    @can('Tipo de producto menu')
                                        <li {!! Request::is('notas*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('notas') }}">
                                                <i class="fas fa-address-book"></i> <span style="color:#000;">Notas</span>
                                            </a>
                                        </li>
                                    @endcan
                                    <hr class="divider-menu">
                                    @can('Unidades de medida menu')
                                        <li {!! Request::is('unidadesMedida*') ? 'class="active"' : '' !!}>
                                            <a href="{{ URL::to('unidadesMedida') }}">
                                                <i class="fas fa-ruler-combined"></i> <span style="color:#000;">Unidades de
                                                    Medida</span>
                                            </a>
                                        </li>
                                    @endcan

                                </ul>
                            </li>
                            <hr class="divider-menu">
                        @endcan


                        @hasanyrole('Administrador|Usuario')
                            <!-- Administracion  -->
                            <li {!! Request::is('Usuarios*') ? 'class="active"' : '' !!}><a class="title" style="cursor:pointer;"><i class="fa fa-user"
                                        style="color:#B08C62; font-size:20px;"></i> <b style="color:#9F9F9F;">Gestión de
                                        Usuario</b><span class="fa fa-chevron-down" style="color:#9F9F9F;"></a>
                                <ul class="nav child_menu">
                                    @can('Usuarios menu')
                                        <li {!! Request::is('Usuarios*') ? 'class="active"' : '' !!} class="sub {!! Request::is('usuarios*') ? 'active' : '' !!}">
                                            <a href="{{ URL::to('usuarios') }}">
                                                <i class="fas fa-user"></i> <span style="color:#000;">Usuarios</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Rol menu')
                                        <li {!! Request::is('Usuarios*') ? 'class="active"' : '' !!} class="sub {!! Request::is('usuarios*') ? 'active' : '' !!}">
                                            <a href="{{ URL::to('usuarios/permisos') }}">
                                                <i class="fas fa-users"></i> <span style="color:#000;">Rol</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                            <hr class="divider-menu">
                        @endhasanyrole
                        <!--

          <li {!! Request::is('clientes*') ? 'class="active"' : '' !!}>
            <span class="title"><i class="fas fa-user-tie"></i> Clientes</span>
          </li>
          <li class="sub" {!! Request::is('tiposClientes*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('tiposClientes') }}">
              <i class="fas fa-address-book"></i> Tipo de Clientes
            </a>
          </li>

          <hr class="divider-menu">

          <li {!! Request::is('clientes*') ? 'class="active"' : '' !!}>
            <span class="title"><i class="fas fa-folder-open"></i> Proyectos</span>
          </li>
          <li class="sub" {!! Request::is('proyectos*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('proyectos') }}">
              <i class="fas fa-folder-open "></i> Categoría de Proyectos
            </a>
          </li>
          <li class="sub" {!! Request::is('subproyectos*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('subproyectos') }}">
              <i class="far fa-folder-open "></i> Tipo de Proyecto
            </a>
          </li>
          <li class="sub" {!! Request::is('prospectos*') ? 'class="active"' : '' !!} >
            <a href="{{ URL::to('prospectos') }}">
              <i class="menu-icon fas fa-folder-open "></i>
              <span>Proyectos en Proceso</span>
            </a>
          </li>
          <li class="sub" {!! Request::is('proyectos-aprobados*') ? 'class="active"' : '' !!} >
            <a href="{{ URL::to('proyectos-aprobados') }}">
              <i class="menu-icon fas fa-file-signature"></i>
              <span>Proyectos Aprobados</span>
            </a>
          </li>
          <hr class="divider-menu">


          <li {!! Request::is('clientes*') ? 'class="active"' : '' !!}>
            <span class="title"><i class="fas fa-truck-loading"></i> Proveedores</span>
          </li>
          <li class="sub" {!! Request::is('tiposProveedores*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('tiposProveedores') }}">
              <i class="fas fa-address-book"></i> Tipos de Proveedores
            </a>
          </li>

          <li class="sub" {!! Request::is('proveedores*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('proveedoresExtra') }}">
              <i class="fas fa-truck-loading "></i> Proveedores Extranjero
            </a>
          </li>

          <hr class="divider-menu">

          <li {!! Request::is('productos*') ? 'class="active"' : '' !!}>
            <span class="title"><i class="fas fa-parking"></i> Producto</span>
          </li>
          <li class="sub" {!! Request::is('unidadesMedida*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('unidadesMedida') }}">
              <i class="fas fa-ruler-combined"></i> Unidades de Medida
            </a>
          </li>
          <li class="sub" {!! Request::is('subcategorias*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('subcategorias') }}">
              <i class="fas fa-cubes "></i> Categoría de Productos
            </a>
          </li>
          <li class="sub" {!! Request::is('categorias*') ? 'class="active"' : '' !!}>
            <a href="{{ URL::to('categorias') }}">
              <i class="fas fa-cubes "></i> Tipo de Producto
            </a>
          </li>

          <hr class="divider-menu">

          <li {!! Request::is('productos*') ? 'class="active"' : '' !!} style=" color:blue">
            <span class="title" style="color:blue"><i class="fas fa-parking " ></i> Ventas</span>
          </li>
          <li class="sub" {!! Request::is('cuentas-cobrar*') ? 'class="active"' : '' !!} style="color:blue">
            <a href="javascript:;">
              <i class="menu-icon fas fa-hand-holding-usd" style="color:blue"></i>
              <span style="color:blue">Facturacion</span>
            </a>
          </li>
          <li class="sub" {!! Request::is('cuentas-cobrar*') ? 'class="active"' : '' !!} >
            <a href="{{ URL::to('cuentas-cobrar') }}">
              <i class="menu-icon fas fa-hand-holding-usd" style="color:blue"></i>
              <span style="color:blue">Cuentas por Cobrar</span>
            </a>
          </li>
          <hr class="divider-menu">

          <li {!! Request::is('cuentas-cobrar*') ? 'class="active"' : '' !!}  >
            <span class="title" style="color:red">
              <i class="menu-icon fas fa-hand-holding-usd"></i>
              Ordenes Compra
            </span>
          </li>
          <li class="sub" {!! Request::is('ordenes-proceso*') ? 'class="active"' : '' !!} >
            <a href="{{ URL::to('ordenes-proceso') }}">
              <i class="menu-icon fas fa-clipboard-list" style="color:red"></i>
              <span style="color:red">Ordenes Proceso</span>
            </a>
          </li>
          <li class="sub" {!! Request::is('cuentas-pagar*') ? 'class="active"' : '' !!} >
            <a href="{{ URL::to('cuentas-pagar') }}">
              <i class="menu-icon fas fa-receipt" style="color:red"></i>
              <span style="color:red">Cuentas por Pagar</span>
            </a>
          </li>
          <hr class="divider-menu">

          <li {!! Request::is('cuentas-pagar*') ? 'class="active"' : '' !!} >
            <span class="title">
              <i class="menu-icon fas fa-receipt"></i>
              Reportes
            </span>
          </li>
          <li class="sub {!! Request::is('reportes/cotizaciones') ? 'active' : '' !!}">
            <a href="{{ URL::to('reportes/cotizaciones') }}">
              <i class="fas fa-file-invoice-dollar"></i> Cotizaciones
            </a>
          </li>
          <li class="sub {!! Request::is('reportes/cobros') ? 'active' : '' !!}">
            <a href="{{ URL::to('reportes/cobros') }}">
              <i class="fas fa-file-invoice-dollar"></i> Cobros
            </a>
          </li>
          <li class="sub {!! Request::is('reportes/compras') ? 'active' : '' !!}">
            <a href="{{ URL::to('reportes/compras') }}">
              <i class="fas fa-file-invoice-dollar"></i> Compras
            </a>
          </li>
          <li class="sub {!! Request::is('reportes/pagos') ? 'active' : '' !!}">
            <a href="{{ URL::to('reportes/pagos') }}">
              <i class="fas fa-file-invoice-dollar"></i> Pagos
            </a>
          </li>
          <li class="sub {!! Request::is('reportes/saldoProveedores') ? 'active' : '' !!}">
            <a href="{{ URL::to('reportes/saldoProveedores') }}">
              <i class="fas fa-file-invoice-dollar"></i> Saldo Proveedores
            </a>
          </li>
          <li class="sub {!! Request::is('reportes/cuentaCliente') ? 'active' : '' !!}">
            <a href="{{ URL::to('reportes/cuentaCliente') }}">
              <i class="fas fa-file-invoice-dollar"></i> Cuenta Cliente
            </a>
          </li>
          <li class="sub {!! Request::is('reportes/utilidades') ? 'active' : '' !!}">
            <a href="{{ URL::to('reportes/utilidades') }}">
              <i class="fas fa-file-invoice-dollar"></i> Utilidades
            </a>
          </li>
          <hr class="divider-menu">
          -->

                    </ul>
                    <!-- / .navigation -->
                </div>
                <!-- menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <aside class="right-aside view-port-height" id="right-aside">
            <div id="loader">
                <div class="spinner"></div>
            </div>
            @yield('content')
        </aside>
        <!-- /.right-side -->
    </div>

    <footer>
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <strong>&#169; {{ date('Y') }} 789.mx</strong><br />
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @if (Auth::user())
        <script>
            $(document).ready(function() {
                var loader = $('#loader');
                console.log('loader')
                loader.hide();
            });
            $(function() {
                setInterval(function checkSession() {
                    $.get('/check-session', function(data) {
                        // if session was expired
                        if (data.guest) {
                            // redirect to login page
                            // location.assign('/auth/login');

                            // or, may be better, just reload page
                            location.reload();
                        }
                    });
                }, 60000); // every minute
            });
        </script>
    @endif
    <script>
        $(function() {
            $('#logout-anchor').on('click', function() {
                $('#logout-form').submit();
            });

            /*$('.select2').select2();*/
        });
        var translationsES = {
            countrySelectorLabel: 'Codigo de Pais',
            countrySelectorError: '',
            phoneNumberLabel: '',
            example: 'Ejemplo:'
        };
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
