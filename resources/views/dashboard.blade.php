@extends('layouts/default')

{{-- Page title --}}
@section('title')
Dashboard | @parent
@stop

@section('header_styles')

<style>
  .marg025 {margin: 0 25px;}

  .card-header {
    border: none;
  }

  .card-title {
    font-size: 16px;
    color: #777;
  }
   .color_text{
    color:#B3B3B3;
  }

  /*mail tiles sales, visits etc*/
  .card-box {
    padding: 21px 16px 30px;
    border: 1px solid rgba(54, 64, 74, 0.12);
    -webkit-border-radius: 3px;
    border-radius: 3px;
    -moz-border-radius: 5px;
    background-clip: padding-box;
    margin-bottom: 25px;
    background-color: #ffffff;
    color: #777;
  }

  .widget-bg-color-icon .bg-icon {
    height: 80px;
    width: 80px;
    text-align: center;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    -moz-border-radius: 50%;
    background-clip: padding-box;
  }

  .widget-bg-color-icon .bg-icon i {
    font-size: 34px;
    line-height: 65px;
  }

  .widget-bg-color-icon h3 {
    margin-top: 9px;
  }

  .widget-bg-color-icon p {
    margin: 0;
  }

  /*sales chart*/
  #sales_chart {
    height: 300px;
    width: 100%;
  }

  .morris-hover.morris-default-style .morris-hover-point {
    color: #555 !important;
  }

  /*timeline widget*/
  .widget-timeline {
    color: #555;
  }

  .widget-timeline ul {
    padding-left: 0;
  }

  .widget-timeline ul li {
    padding: 18px 3px;
  }

  .widget-timeline img {
    width: 39px;
    height: 39px;
    border-radius: 50%;
  }

  .widget-timeline .timeline {
    padding-left: 51px;
  }

  /*table*/
  .product-details .panel-body {
    padding-bottom: 0;
  }

  #product-details tbody>tr>td {
    padding: 13px;
  }

  #product-details tbody>tr>td:last-child {
    width: 160px;
  }

  #product_status canvas {
    margin-bottom: -2px;
  }

  .update_btn {
    margin-top: -8px;
  }

  /************* lobibox css ***************/
  .lobibox-notify-wrapper.right {
    z-index: 9999999;
  }

  .lobibox-notify {
    box-shadow: -8px 6px 20px 2px #aaa;
    width: 460px !important;
    height: 115px;
    border-radius: 10px;
  }

  .lobibox-notify-msg {
    font-size: 14px;
    margin-left: 30px;
  }

  .lobibox-notify .lobibox-notify-title {
    font-size: 18px;
    margin-left: 30px;
    font-family: 'Montserrat Alternates', sans-serif;
  }

  .lobibox-notify .lobibox-notify-body {
    margin: 20px 20px 10px 125px;
  }

  .lobibox-notify-msg {
    margin-top: 8px;
    font-family: 'Montserrat Alternates', sans-serif;
  }

  .lobibox-notify-body {
    border-left: 2px solid #fff;
  }

  .lobibox-notify-icon img {
    margin-left: 15px;
  }

  .lobibox-notify.lobibox-notify-info {
    background: linear-gradient(to bottom right, #12b1ec, #1FC0C0);
    border-color: transparent;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header" style="background-color:#12160F; color:#B68911;">
  <h1>Dashboard</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  @role('Administrador|Dirección')
    <div class="row">
      <div class="col-md-4 form-horizontal">
        <div class="form-group p-10">
          <!--
          BASE DE DATOS
            
                <select class="form-control" @change="CHANGEBDD()" v-model="bdd" style="width:auto;display:inline-block;">
                  <option value="mysql">MX</option>
                  <option value="mysql2">USA</option>
                </select>
              -->
            </div>
      </div>
      <div class="col-md-4 form-horizontal">
      </div>
      <div class="col-md-4 form-horizontal">
        <div class="form-group p-10">
            <label class="col-md-3 control-label" for="example-select">Datos de: </label>
            <div class="col-md-9">
              <select name="" id="selectUsuario" class="form-control" @change="cargar()" v-model="usuarioCargado">
                <option value="todos">Todos</option>
                @foreach($data['usuarios'] as $usuario)
                  <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="p-10">
              Año  
                <select class="form-control" @change="cargar()" v-model="anio" style="width:auto;display:inline-block;">
                  <option value="Todos">Todos</option>
                  <option value="2019-12-31">2019</option>
                  <option value="2020-12-31">2020</option>
                  <option value="2021-12-31">2021</option>
                  <option value="2022-12-31">2022</option>
                  <option value="2023-12-31">2023</option>
                  <option value="2024-12-31">2024</option>
                </select>
            </div>
        </div>
      </div>
    </div>
  @endrole  
  <div class="row">
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="fas fa-user-check text-warning" style="color:#000;"></i>
        </div>
        <div class="text-right">
          <h3 class="text-dark"><b>@{{data.clientes}}</b></h3>
          <p>Clientes Registrados</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="fab fa-opencart text-warning"></i>
        </div>
        <div class="text-right">
          <h3><b id="widget_count3">@{{data.prospectos}}</b></h3>
          <p>Proyectos Prospectos</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="far fa-thumbs-up text-default" style="color:#B3B3B3;"></i>
        </div>
        <div class="text-right">
          <h3 class="text-dark"><b>@{{data.proyectosAprobados}}</b></h3>
          <p>Proyectos Aprobados</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="far fa-clock text-dark" style="color:#000;"></i>
        </div>
        <div class="text-right">
          <h3 class="text-dark"><b>@{{data.ordenesProceso}}</b></h3>
          <p>Ordenes en Proceso</p>
        </div>
      </div>
    </div>
  </div>
  <!--Proximas Actividades -->
  <div class="row">
    <div class="col-sm-12">
      <div class="panel product-details">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title">Próximas Actividades</h3>
        </div>
        <div class="panel-body">
          <div id="oculto_actividades" class="hide">
            <dropdown id="actividades_fecha_ini_control" class="marg025">
              <div class="input-group">
                <div class="input-group-btn">
                  <btn class="dropdown-toggle" style="background-color:#fff;">
                    <i class="fas fa-calendar"></i>
                  </btn>
                </div>
                <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                  v-model="actividades_fecha_ini" readonly
                  style="width:120px;"
                />
              </div>
              <template slot="dropdown">
                <li>
                  <date-picker :locale="locale" :today-btn="false"
                  format="dd/MM/yyyy" :date-parser="dateParser"
                  v-model="actividades_fecha_ini"/>
                </li>
              </template>
            </dropdown>
            <dropdown id="actividades_fecha_fin_control" class="marg025">
              <div class="input-group">
                <div class="input-group-btn">
                  <btn class="dropdown-toggle" style="background-color:#fff;">
                    <i class="fas fa-calendar"></i>
                  </btn>
                </div>
                <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                  v-model="actividades_fecha_fin" readonly
                  style="width:120px;"
                />
              </div>
              <template slot="dropdown">
                <li>
                  <date-picker :locale="locale" :today-btn="false"
                  format="dd/MM/yyyy" :date-parser="dateParser"
                  v-model="actividades_fecha_fin"/>
                </li>
              </template>
            </dropdown>
            <div class="marg025 btn-group" id="actividades_clientes" >
                <select name="proxDias" class="form-control" size="1" v-model="valor_actividades_clientes" id="select_actividades_clientes">
                <option v-for="(option, index) in datos_select_actividades.clientes" v-bind:value="option" >
                    @{{ option }}
                  </option>
                  
                </select>
            </div>
            <div class="marg025 btn-group" id="actividades_proyectos" >
                <select name="proxDias" class="form-control" size="1" v-model="valor_actividades_proyectos" id="select_actividades_proyectos">
                  <option v-for="option in datos_select_actividades.proyectos" v-bind:value="option">
                    @{{ option }}
                  </option>
                  
                </select>
            </div>
          </div><br>
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table table-striped text-center" id="tablaActividades">
                  <thead>
                    <tr style="background-color:#12160F">
                      <th class="text-center color_text">Cliente</th>
                      <th class="text-center color_text"><strong>Proyecto</strong></th>
                      <th class="text-center color_text"><strong>Tipo</strong></th>
                      <th class="text-center color_text"><strong>Próxima Actividad</strong></th>
                      <th class="text-center color_text"><strong>¿Es prospecto?</strong></th>
                      <th class="text-center color_text"><strong>Acciones</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(actividad, index) in data.proximasActividades">
                      <td>@{{actividad.cliente_nombre}}</td>
                      <td>@{{actividad.prospecto_nombre}}</td>
                      <td>@{{actividad.tipo_actividad}}</td>
                      <td>@{{actividad.fecha_formated}}</td>
                      <td>@{{actividad.es_prospecto}}</td>
                      <td class="col-md-2">
                        <a title="Ver" :href="'/prospectos/'+actividad.prospecto_id" class="btn btn-xs btn-info">
                            Ver <i class="far fa-eye"></i>
                          </a>
                          <a class="btn btn-xs btn-warning" title="Editar" :href="'/prospectos/'+actividad.prospecto_id+'/editar'">
                            Editar<i class="fas fa-pencil-alt"></i>
                          </a>  
                      </td>
                      
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-sm-12 p-0">
              <span id="product_status"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--Ultimas Cotizaciones -->
  <div class="row">
    <div class="col-sm-12">
      <div class="panel product-details">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title">Ultimas Cotizaciones</h3>
        </div>
        <div class="panel-body">
            <div id="oculto_cotizaciones" class="hide">
                <dropdown id="cotizaciones_fecha_ini_control" class="marg025">
                  <div class="input-group">
                    <div class="input-group-btn">
                      <btn class="dropdown-toggle" style="background-color:#fff;">
                        <i class="fas fa-calendar"></i>
                      </btn>
                    </div>
                    <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                      v-model="cotizaciones_fecha_ini" readonly
                      style="width:120px;"
                    />
                  </div>
                  <template slot="dropdown">
                    <li>
                      <date-picker :locale="locale" :today-btn="false"
                      format="dd/MM/yyyy" :date-parser="dateParser"
                      v-model="cotizaciones_fecha_ini"/>
                    </li>
                  </template>
                </dropdown>
                <dropdown id="cotizaciones_fecha_fin_control" class="marg025">
                  <div class="input-group">
                    <div class="input-group-btn">
                      <btn class="dropdown-toggle" style="background-color:#fff;">
                        <i class="fas fa-calendar"></i>
                      </btn>
                    </div>
                    <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                      v-model="cotizaciones_fecha_fin" readonly
                      style="width:120px;"
                    />
                  </div>
                  <template slot="dropdown">
                    <li>
                      <date-picker :locale="locale" :today-btn="false"
                      format="dd/MM/yyyy" :date-parser="dateParser"
                      v-model="cotizaciones_fecha_fin"/>
                    </li>
                  </template>
                </dropdown>
                <div class="marg025 btn-group" id="cotizaciones_clientes" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_cotizaciones_clientes" id="select_cotizaciones_clientes">
                    <option v-for="(option, index) in datos_select_cotizaciones.clientes" v-bind:value="option" >
                        @{{ option }}
                      </option>
                      
                    </select>
                </div>
                <div class="marg025 btn-group" id="cotizaciones_proyectos" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_cotizaciones_proyectos" id="select_cotizaciones_proyectos">
                      <option v-for="option in datos_select_cotizaciones.proyectos" v-bind:value="option">
                        @{{ option }}
                      </option>
                      
                    </select>
                </div>
              </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                
                <table class="table table-striped text-center" id="tablaCotizaciones">
                  <thead>
                    <tr style="background-color:#12160F">
                      <th class="text-center color_text">Cliente</th>
                      <th class="text-center color_text"><strong>Proyecto</strong></th>
                      <th class="text-center color_text"><strong>Número Cotización</strong></th>
                      <th class="text-center color_text"><strong>Fecha</strong></th>                     
                      <th class="text-center color_text"><strong>Total</strong></th>
                      <th class="text-center color_text"><strong>Usuario</strong></th>
                      <th class="text-center color_text"><strong>Acciones</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(cotizacion, index) in data.cotizaciones">
                      <td>@{{cotizacion.cliente_nombre}}</td>
                      <td>@{{cotizacion.prospecto_nombre}}</td>
                      <td>@{{cotizacion.id}}</td>
                      <td>@{{cotizacion.fecha_formated}}</td>
                      <td v-bind:style= "[cotizacion.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{cotizacion.total | formatoMoneda}} @{{cotizacion.moneda|formatoCurrency}}</td>
                      <td>@{{cotizacion.user_name}}</td>
                      <td class="text-warning">
                          <a title="Ver" :href="'/prospectos/'+cotizacion.prospecto_id+'/cotizar'" class="btn btn-info">
                            Ver <i class="far fa-eye"></i>
                          </a>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-sm-12 p-0">
              <span id="product_status"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!--Compras pendiente de Aprobar -->
  <div class="row">
    <div class="col-sm-12">
      <div class="panel product-details">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title">Ordenes de Compra Pendientes De Aprobar</h3>
        </div>
        <div class="panel-body">
            <div id="oculto_compras" class="hide">
                <dropdown id="compras_fecha_ini_control" class="marg025">
                  <div class="input-group">
                    <div class="input-group-btn">
                      <btn class="dropdown-toggle" style="background-color:#fff;">
                        <i class="fas fa-calendar"></i>
                      </btn>
                    </div>
                    <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                      v-model="compras_fecha_ini" readonly
                      style="width:120px;"
                    />
                  </div>
                  <template slot="dropdown">
                    <li>
                      <date-picker :locale="locale" :today-btn="false"
                      format="dd/MM/yyyy" :date-parser="dateParser"
                      v-model="compras_fecha_ini"/>
                    </li>
                  </template>
                </dropdown>
                <dropdown id="compras_fecha_fin_control" class="marg025">
                  <div class="input-group">
                    <div class="input-group-btn">
                      <btn class="dropdown-toggle" style="background-color:#fff;">
                        <i class="fas fa-calendar"></i>
                      </btn>
                    </div>
                    <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                      v-model="compras_fecha_fin" readonly
                      style="width:120px;"
                    />
                  </div>
                  <template slot="dropdown">
                    <li>
                      <date-picker :locale="locale" :today-btn="false"
                      format="dd/MM/yyyy" :date-parser="dateParser"
                      v-model="compras_fecha_fin"/>
                    </li>
                  </template>
                </dropdown>
                <!--
                <div class="marg025 btn-group" id="cotizaciones_clientes" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_compras_clientes" id="select_compras_clientes">
                    <option v-for="(option, index) in datos_select_compras.clientes" v-bind:value="option" >
                        @{{ option }}
                      </option>
                      
                    </select>
                </div>
                <div class="marg025 btn-group" id="compras_proyectos" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_compras_proyectos" id="select_compras_proyectos">
                      <option v-for="option in datos_select_compras.proyectos" v-bind:value="option">
                        @{{ option }}
                      </option>
                      
                    </select>
                </div>
              -->
             
              </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                
                <table class="table table-striped text-center" id="tablaCompras">
                  <thead>
                    <tr style="background-color:#12160F">
                      <th class="text-center color_text"># Orden</th>
                      <th class="text-center color_text">Ejecutivo</th>
                      <th class="text-center color_text">Cliente</th>
                      <th class="text-center color_text">Proyecto</th>
                      <th class="text-center color_text"><strong>Proveedor</strong></th>
                      <th class="text-center color_text"><strong>Producto</strong></th>
                      <th class="text-center color_text"><strong>Cantidad</strong></th>
                      <th class="text-center color_text"><strong>Moneda</strong></th>
                      <th class="text-center color_text"><strong>Total</strong></th>
                      <th class="text-center color_text"><strong>Status</strong></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(compra, index) in data.compras">
                      <td>@{{compra.numero}}</td>
                      <td>@{{compra.proyecto.cotizacion.user.name}}</td>
                      <td>@{{compra.proyecto.proyecto}}</td>
                      <td>@{{compra.cliente.nombre}}</td>
                      <td>@{{compra.proveedor_empresa}}</td>
                      <td>
                        <span v-for="(entrada, index) in compra.entradas">
                          @{{index+1}}.- @{{entrada.producto.nombre}} <br />
                        </span>
                      </td>
                      <td>
                        <span v-for="(entrada, index) in compra.entradas">
                          @{{index+1}}.-
                            <span v-if="entrada.conversion">@{{entrada.cantidad_convertida}} @{{entrada.conversion}}</span>
                            <span v-else>@{{entrada.cantidad}} @{{entrada.medida}}</span>
                          <br />
                        </span>
                      </td>
                      <td v-bind:style= "[compra.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">
                        @{{compra.moneda}}
                        
                      </td>
                      <td v-bind:style= "[compra.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">
                        @{{compra.total | formatoMoneda}} 
                      </td>
                      <td>@{{compra.status}}</td>
                      <td class="text-right">
                      <template v-if="compra.status!='Pendiente' && compra.status!='Cancelada'">
                        <a class="btn btn-xs btn-info" title="Ver"
                          :href="'/proyectos-aprobados/'+compra.proyecto_id+'/ordenes-compra/'">
                          <i class="far fa-eye"></i>
                        </a>
                        <a v-if="compra.archivo" class="btn btn-xs btn-warning" title="PDF" :href="'storage/'+compra.archivo"
                          :download="'ROBINSON-PO '+compra.numero+' '+compra.cliente_nombre+' '+compra.proyecto_nombre+'.pdf'">
                          <i class="far fa-file-pdf"></i>
                        </a>
                      </template>
                    
                  </td>
                      
                    </tr>

                  <tfoot>
                    <tr>
                        <td colspan="10" style="text-align:right">Total MXN:</td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                      <td colspan="10" style="text-align:right">Total USD:</td>
                      <td>
                      </td>
                    </tr>
                  </tfoot>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-sm-12 p-0">
              <span id="product_status"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--Cotizaciones Acceptadas -->
  <!--Ultimas Cotizaciones -->
  <div class="row">
    <div class="col-sm-12">
      <div class="panel product-details">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title">Ultimas Cotizaciones Aceptadas</h3>
        </div>
        <div class="panel-body">
            <div id="oculto_aceptadas" class="hide">
                <dropdown id="aceptadas_fecha_ini_control" class="marg025">
                  <div class="input-group">
                    <div class="input-group-btn">
                      <btn class="dropdown-toggle" style="background-color:#fff;">
                        <i class="fas fa-calendar"></i>
                      </btn>
                    </div>
                    <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                      v-model="aceptadas_fecha_ini" readonly
                      style="width:120px;"
                    />
                  </div>
                  <template slot="dropdown">
                    <li>
                      <date-picker :locale="locale" :today-btn="false"
                      format="dd/MM/yyyy" :date-parser="dateParser"
                      v-model="aceptadas_fecha_ini"/>
                    </li>
                  </template>
                </dropdown>
                <dropdown id="aceptadas_fecha_fin_control" class="marg025">
                  <div class="input-group">
                    <div class="input-group-btn">
                      <btn class="dropdown-toggle" style="background-color:#fff;">
                        <i class="fas fa-calendar"></i>
                      </btn>
                    </div>
                    <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                      v-model="aceptadas_fecha_fin" readonly
                      style="width:120px;"
                    />
                  </div>
                  <template slot="dropdown">
                    <li>
                      <date-picker :locale="locale" :today-btn="false"
                      format="dd/MM/yyyy" :date-parser="dateParser"
                      v-model="aceptadas_fecha_fin"/>
                    </li>
                  </template>
                </dropdown>
                <div class="marg025 btn-group" id="aceptadas_clientes" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_aceptadas_clientes" id="select_aceptadas_clientes">
                      <option v-for="(option, index) in datos_select_aceptadas.clientes" v-bind:value="option" >
                        @{{ option }}
                      </option>
                      
                    </select>
                </div>
                <div class="marg025 btn-group" id="aceptadas_proyectos" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_aceptadas_proyectos" id="select_aceptadas_proyectos">
                      <option v-for="(option, index) in datos_select_aceptadas.proyectos" v-bind:value="option" >
                        @{{ option }}
                      </option>
                      
                    </select>
                </div>
              </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                
                <table class="table table-striped text-center" id="tablaAceptadas">
                  <thead>
                    <tr style="background-color:#12160F">
                      <th class="text-center color_text">Cliente</th>
                      <th class="text-center color_text"><strong>Proyecto</strong></th>
                      <th class="text-center color_text"><strong>Número Cotización</strong></th>
                      <th class="text-center color_text"><strong>Fecha</strong></th>                     
                      <th class="text-center color_text"><strong>Total</strong></th>
                      <th class="text-center color_text"><strong>Acciones</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(cotizacion, index) in data.aceptadas">
                      <td>@{{cotizacion.cliente_nombre}}</td>
                      <td>@{{cotizacion.prospecto_nombre}}</td>
                      <td>@{{cotizacion.id}}</td>
                      <td>@{{cotizacion.fecha_formated}}</td>
                      <td v-bind:style= "[cotizacion.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{cotizacion.total | formatoMoneda}} @{{cotizacion.moneda| formatoCurrency}}</td>
                      <td class="text-warning">
                          <a title="Ver" :href="'/proyectos-aprobados/'+cotizacion.id_aprobado+'/ordenes-compra'" class="btn btn-info">
                            Ver <i class="far fa-eye"></i>
                          </a>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-sm-12 p-0">
              <span id="product_status"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- Total Facturado VS Cobrado --}}
  <div class="row">
      <div class="col-sm-12">
          <div class="panel product-details">
            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
              <h3 class="panel-title">Total Facturado VS Cobrado</h3>
            </div>
            <div class="panel-body">
              {{--TODO filtros--}}
              <div class="row hide">
                  <div class="col-md-6  pull-left">
                      <dropdown id="porCobrar_fecha_ini_control" class="marg025">
                          <div class="input-group">
                            <div class="input-group-btn">
                              <btn class="dropdown-toggle" style="background-color:#fff;">
                                <i class="fas fa-calendar"></i>
                              </btn>
                            </div>
                            <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                              v-model="porCobrar_fecha_ini" readonly
                              style="width:120px;"
                            />
                          </div>
                          <template slot="dropdown">
                            <li>
                              <date-picker :locale="locale" :today-btn="false"
                              format="dd/MM/yyyy" :date-parser="dateParser"
                              v-model="porCobrar_fecha_ini"/>
                            </li>
                          </template>
                        </dropdown>
                        <dropdown id="porCobrar_fecha_fin_control" class="marg025">
                          <div class="input-group">
                            <div class="input-group-btn">
                              <btn class="dropdown-toggle" style="background-color:#fff;">
                                <i class="fas fa-calendar"></i>
                              </btn>
                            </div>
                            <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                              v-model="porCobrar_fecha_fin" readonly
                              style="width:120px;"
                            />
                          </div>
                          <template slot="dropdown">
                            <li>
                              <date-picker :locale="locale" :today-btn="false"
                              format="dd/MM/yyyy" :date-parser="dateParser"
                              v-model="porCobrar_fecha_fin"/>
                            </li>
                          </template>
                        </dropdown>
                        <div class="marg025 btn-group">
                          <button type="button" class="btn btn-primary">Cargar</button>
                        </div>    
                  </div>
              </div>
              <div class="row">
                <div class="col-lg-12" v-for="(row, index) in data.totalCuentas">
                  <div class="col-sm-4">
                    <h3><strong>Total: </strong>@{{row.total|formatoMoneda}} @{{row.moneda| formatoCurrency}}</h3>
                  </div>
                  <div class="col-sm-4">
                    <h3><strong>Facturado: </strong>@{{row.facturado|formatoMoneda}} @{{row.moneda| formatoCurrency}}</h3>
                  </div>
                  <div class="col-sm-4">
                    <h3><strong>Pagado: </strong>@{{row.pagado|formatoMoneda}} @{{row.moneda| formatoCurrency}}</h3>
                  </div>
                </div>
                <div class="col-lg-12">
                    <canvas  id="porCobrarGrafica">
                    </div>
                </div>
              </div>
            </div>
          </div>
      </div>  
  </div>
</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script src="{{ URL::asset('js/plugins/chartist/Chart.min.js') }}" ></script>
<script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}" ></script>
<link href="{{ URL::asset('css/Chart.min.css') }}" rel="stylesheet" type="text/css">
<script>
  const app = new Vue({
    el: '#content',
    data: {
      prospectos: {},
      usuarioCargado: {{auth()->user()->id}},
      anio:'2023-12-31',
      bdd: '',
      tablaCotizaciones: {},
      tablaCompras: {},
      tablaAceptadas: {},
      tablaActividades: {},
      data: {!! json_encode($data) !!},
      locale: localeES,
      //variables tabla actividades
      actividades_fecha_ini: '',
      actividades_fecha_fin: '',
      valor_actividades_clientes:'Cliente',
      valor_actividades_proyectos:'Proyecto',
      datos_select_actividades:{clientes:[], proyectos:[]},
      datos_select_actividades_clientes:[],
      datos_select_actividades_proyectos:[],
      //variables tabla cotizaciones
      cotizaciones_fecha_ini: '',
      cotizaciones_fecha_fin: '',
      valor_cotizaciones_clientes:'Cliente',
      valor_cotizaciones_proyectos:'Proyecto',
      datos_select_cotizaciones:{clientes:[], proyectos:[]},
      datos_select_cotizaciones_clientes:[],
      datos_select_cotizaciones_proyectos:[],

      //variables tabla compras
      compras_fecha_ini: '',
      compras_fecha_fin: '',
      valor_compras_clientes:'Cliente',
      valor_compras_proyectos:'Proyecto',
      datos_select_compras:{clientes:[], proyectos:[]},
      datos_select_compras_clientes:[],
      datos_select_compras_proyectos:[],

      //variables tabla cotizaciones aceptadas
      aceptadas_fecha_ini: '',
      aceptadas_fecha_fin: '',
      valor_aceptadas_clientes:'Cliente',
      valor_aceptadas_proyectos:'Proyecto',
      datos_select_aceptadas:{clientes:[], proyectos:[]},
      datos_select_aceptadas_clientes:[],
      datos_select_aceptadas_proyectos:[],

      //variables gráfica cuentas por cobrar
      porCobrar_fecha_ini: '',
      porCobrar_fecha_fin: '',
      porCobrar_data: {},
      graficaPorCobrar:null
    },
    computed: {
        totales_cotizaciones() {
            var dolares = 0, pesos = 0;
            this.data.compras.forEach(function (compra) {
                if (compra.moneda == "Pesos") pesos += compra.total;
                else dolares += compra.total;
            });
            return {"dolares": dolares, "pesos": pesos}
        }
    },
    mounted(){
      $.fn.dataTable.moment('DD/MM/YYYY');
      //Tabla cotizaciones
      this.tablaCotizaciones = this.tableFactory("#tablaCotizaciones", "cotizaciones", this.datos_select_cotizaciones);
      this.tablaCompras = this.tableFactory("#tablaCompras", "compras", this.datos_select_compras);
      this.tablaAceptadas = this.tableFactory("#tablaAceptadas", "aceptadas", this.datos_select_aceptadas);
      this.tablaActividades = this.tableFactory("#tablaActividades", "actividades", this.datos_select_actividades);
      var vue = this;
      this.porCobrar_data=this.data.cuentasCobrar;
      this.grafica();
      //Filtrado para rango de fechas
      $.fn.dataTableExt.afnFiltering.push(
        function( settings, data, dataIndex ) {
          var fecha = data[3] || 0;
          if(settings.nTable.id === 'tablaActividades'){
            var min  = vue.actividades_fecha_ini;
            var max  = vue.actividades_fecha_fin;
          }
          if(settings.nTable.id === 'tablaCotizaciones'){
            var min  = vue.cotizaciones_fecha_ini;
            var max  = vue.cotizaciones_fecha_fin;
          }
          if(settings.nTable.id === 'tablaCompras'){
            var min  = vue.compras_fecha_ini;
            var max  = vue.compras_fecha_fin;
          }
          if(settings.nTable.id === 'tablaAceptadas'){
            var min  = vue.aceptadas_fecha_ini;
            var max  = vue.aceptadas_fecha_fin;
          }
          
           // Our date column in the table
          var startDate   = moment(min, "DD/MM/YYYY");
          var endDate     = moment(max, "DD/MM/YYYY");
          var diffDate = moment(fecha, "DD/MM/YYYY");
          // console.log(min=="",max=="",diffDate.isSameOrAfter(startDate),diffDate.isSameOrBefore(endDate),diffDate.isBetween(startDate, endDate));
          if (min=="" && max=="") return true;
          if (max=="" && diffDate.isSameOrAfter(startDate)) return true;
          if (min=="" && diffDate.isSameOrBefore(endDate)) return true;
          if (diffDate.isBetween(startDate, endDate, null, '[]')) return true;
          return false;
        }
      );
      //grafica cuentas cobrar

      
    },
    watch: {
      cotizaciones_fecha_ini: function (val) {
        
        this.tablaCotizaciones.draw();
      },
      cotizaciones_fecha_fin: function (val) {
        this.tablaCotizaciones.draw();
      },
      valor_cotizaciones_clientes: function(val){
        this.tablaCotizaciones.columns(0).search(this.valor_cotizaciones_clientes).draw();
      },
      valor_cotizaciones_proyectos: function(val){
        this.tablaCotizaciones.columns(1).search(this.valor_cotizaciones_proyectos).draw();
      },


      compras_fecha_ini: function (val) {
        this.tablaCompras.draw();
      },
      compras_fecha_fin: function (val) {
        this.tablaCompras.draw();
      },
      valor_compras_clientes: function(val){
        this.tablaCompras.columns(0).search(this.valor_compras_clientes).draw();
      },
      valor_compras_proyectos: function(val){
        this.tablaCompras.columns(1).search(this.valor_compras_proyectos).draw();
      },


      aceptadas_fecha_ini: function (val) {
        this.tablaAceptadas.draw();
      },
      aceptadas_fecha_fin: function (val) {
        this.tablaAceptadas.draw();
      },
      valor_aceptadas_clientes: function(val){
        this.tablaAceptadas.columns(0).search(this.valor_aceptadas_clientes).draw();
      },
      valor_aceptadas_proyectos: function(val){
        this.tablaAceptadas.columns(1).search(this.valor_aceptadas_proyectos).draw();
      },
      actividades_fecha_ini: function (val) {
        this.tablaActividades.draw();
      },
      actividades_fecha_fin: function (val) {
        this.tablaActividades.draw();
      },
      valor_actividades_clientes: function(val){
        this.tablaActividades.columns(0).search(this.valor_actividades_clientes).draw();
      },
      valor_actividades_proyectos: function(val){
        this.tablaActividades.columns(1).search(this.valor_actividades_proyectos).draw();
      }
    },
    filters:{
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$", 2);
    },
    formatoCurrency(valor){
      return valor=='Dolares'?'USD':'MXN';
    }
  },
    methods: {
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      tableFactory(table, prefix, datos){
        var clientes=[]
        var proyectos=[]
        if (table == '#tablaCompras') {
          newTable=$(table).DataTable({
          "dom": 'f<"#'+prefix+'_fechas_container.pull-left">ltip',
            "order":[[0,'desc']],

            //
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;


                var formato = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                //datos de la tabla con filtros aplicados
                var datos= api.columns([8,7], {search: 'applied'}).data();
                var totalMxn = 0;
                var totalUsd = 0;
                //suma de montos
                datos[0].forEach(function(element, index){
                    if(datos[1][index]=="Dolares"){
                        totalUsd+=formato(element)
                    }else{
                        totalMxn+=formato(element)
                    }
                });
     
                // Actualizar
                var nCells = row.getElementsByTagName('td');
                nCells[1].innerHTML = accounting.formatMoney(totalMxn, "$", 2);

                var secondRow = $(row).next()[0]; 
                var nCells = secondRow.getElementsByTagName('td');
                nCells[1].innerHTML = accounting.formatMoney(totalUsd, "$", 2);
            },

            //


            initComplete: function () {

              
              //Crear y llenar los select para clientes 
              clientes.push('Clientes')
              clientes.push('');
              this.api().column(0).data().sort().unique().each(function(d,j){   
                clientes.push(d);
              });
              //Crear y llenar los select para clientes 
              proyectos.push('Proyectos')
              proyectos.push('');
              this.api().column(1).data().sort().unique().each(function(d,j){   
                proyectos.push(d);
              });
            }
          });
        }
        else{
          newTable=$(table).DataTable({
            "dom": 'f<"#'+prefix+'_fechas_container.pull-left">ltip',
            "order":[],
            initComplete: function () {

              
              //Crear y llenar los select para clientes 
              clientes.push('Clientes')
              clientes.push('');
              this.api().column(0).data().sort().unique().each(function(d,j){   
                clientes.push(d);
              });
              //Crear y llenar los select para clientes 
              proyectos.push('Proyectos')
              proyectos.push('');
              this.api().column(1).data().sort().unique().each(function(d,j){   
                proyectos.push(d);
              });
            }
          });
        }
        
        //LLenar datos en Selects
        datos.clientes=[...clientes]
        datos.proyectos=[...proyectos]
          
        //Agregarcontroles de fecha y selects
        $("#"+prefix+"_fechas_container").append($("#"+prefix+"_fecha_ini_control"));
        $("#"+prefix+"_fechas_container").append($("#"+prefix+"_fecha_fin_control"));
        $("#"+prefix+"_fechas_container").append($("#"+prefix+"_clientes"));
        $("#"+prefix+"_fechas_container").append($("#"+prefix+"_proyectos"));
        return newTable;
      },
      restarControls(){
        $("#oculto_cotizaciones").append($("#cotizaciones_fecha_ini_control"));
        $("#oculto_cotizaciones").append($("#cotizaciones_fecha_fin_control"));
        $("#oculto_cotizaciones").append($("#cotizaciones_clientes"));
        $("#oculto_cotizaciones").append($("#cotizaciones_proyectos"));

        $("#oculto_compras").append($("#compras_fecha_ini_control"));
        $("#oculto_compras").append($("#compras_fecha_fin_control"));
        $("#oculto_compras").append($("#compras_clientes"));
        $("#oculto_compras").append($("#compras_proyectos"));

        $("#oculto_aceptadas").append($("#aceptadas_fecha_ini_control"));
        $("#oculto_aceptadas").append($("#aceptadas_fecha_fin_control"));
        $("#oculto_aceptadas").append($("#aceptadas_clientes"));
        $("#oculto_aceptadas").append($("#aceptadas_proyectos"));

        $("#oculto_actividades").append($("#actividades_fecha_ini_control"));
        $("#oculto_actividades").append($("#actividades_fecha_fin_control"));
        $("#oculto_actividades").append($("#actividades_clientes"));
        $("#oculto_actividades").append($("#actividades_proyectos"));
      },
      grafica(){
        var ctx = $('#porCobrarGrafica')[0].getContext('2d');
        var graphData={labels:[], datasets:[]};
        var serie1=[]
        var serie2=[]
        var serie3=[]
        this.porCobrar_data.forEach(element => {
          graphData['labels'].push(element.cotizacion_id)
          serie1.push(element.total);
          serie2.push(element.facturado);
          serie3.push(element.pagado);
        });
        graphData.datasets=[
          {
            label:'Total',
            backgroundColor: 'BLACK',
            borderColor:'BLACK',
            borderWidth: 1,
            data:[...serie1]
          },
          {
            label:'Facturado',
            backgroundColor: '#B3B3B3;',
            borderColor:  '#B3B3B3;',
            borderWidth: 1,
            data:[...serie2]
          },
          {
            label:'Pagado',
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1,
            data:[...serie3]
          },
          ]
        if(!this.graficaPorCobrar){
          this.graficaPorCobrar = new Chart(ctx,{
            type:'bar', data:graphData,
            options: {
              responsive: true,
              legend: {
                position: 'top',
                },
              title: {
                display: true,
                text: 'Montos Cuentas por Cobrar'
              },
              tooltips: {
                  callbacks: {
                      label: function(tooltipItem, data) {
                          var value=tooltipItem.yLabel || '';
                          if(parseInt(value) >= 1000){
                            return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                          } else {
                            return '$' + value;
                          }
                      }
                  }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                      if(parseInt(value) >= 1000){
                        return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                      } else {
                        return '$' + value;
                      }
                    }
                  }
                }]
              }}});
        }else{
          console.log('update')
          this.graficaPorCobrar.data=graphData;
          this.graficaPorCobrar.update(graphData);
        }  
      },
      cargar(){
        axios.post('/dashboard/listado', {id: this.usuarioCargado, anio:this.anio})
        .then(({data}) => {
          console.log(data);
          this.restarControls();
          this.tablaCotizaciones.destroy();
          this.tablaCompras.destroy();
          this.tablaAceptadas.destroy();
          this.tablaActividades.destroy();
          this.data=data.data;
          this.porCobrar_data=this.data.cuentasCobrar;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tablaCotizaciones = this.tableFactory("#tablaCotizaciones", "cotizaciones", this.datos_select_cotizaciones);
            this.tablaCompras = this.tableFactory("#tablaCompras", "compras", this.datos_select_compras);
            this.tablaAceptadas = this.tableFactory("#tablaAceptadas", "aceptadas", this.datos_select_aceptadas);
            this.tablaActividades = this.tableFactory("#tablaActividades", "actividades", this.datos_select_actividades);
            
            this.grafica();
          });
        })
        .catch(({response}) => {
          console.error(response);
          swal({
            title: "Error",
            text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
            type: "error"
          });
        });
      },
      CHANGEBDD(){
        axios.post('/dashboard/changebdd', {bdd: this.bdd})
        .then(({data}) => {
          console.log(this.bdd);
          swal({
            title: "Exito",
            text: "BDD CAMBIADA",
            type: "success"
          }).then(()=>{

          });
        })
        .catch(({response}) => {
          console.error(response);
          swal({
            title: "Error",
            text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
            type: "error"
          });
        });
      }
    }})
</script>
@stop