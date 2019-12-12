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
<section class="content-header">
  <h1>Dashboard</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  @role('Administrador')
    <div class="row">
      <div class="col-md-4 col-md-offset-8 form-horizontal">
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
        </div>
      </div>
    </div>
  @endrole  
  <div class="row">
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="fas fa-user-check text-warning"></i>
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
          <i class="fab fa-opencart text-success"></i>
        </div>
        <div class="text-right">
          <h3><b id="widget_count3">@{{data.prospectos}}</b></h3>
          <p>Proyectos en proceso</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-3">
      <div class="widget-bg-color-icon card-box">
        <div class="bg-icon pull-left">
          <i class="far fa-thumbs-up text-danger"></i>
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
          <i class="far fa-clock text-info"></i>
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
        <div class="panel-heading">
          <h3 class="panel-title">Próximas Actividades</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                <div class="col-sm-12">
                  <div class="col-sm-6">
                    <label>
                      Ultimos:
                      <select name="proxDias" id="proxDias" >
                        <option value="7">7</option>
                        <option value="15">15</option>
                        <option value="30">30</option>
                      </select>
                      dias
                    </label>
                  </div>
                </div>
                <table class="table table-striped text-center" id="tablaProximasActividades">
                  <thead>
                    <tr>
                      <th class="text-center">Cliente</th>
                      <th class="text-center"><strong>Proyecto</strong></th>
                      <th class="text-center"><strong>Próxima Actividad</strong></th>
                      <th class="text-center"><strong>Tipo</strong></th>
                      <th class="text-center"><strong>Acciones</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(actividad, index) in data.proximasActividades">
                      <td>@{{actividad.cliente_nombre}}</td>
                      <td>@{{actividad.prospecto_nombre}}</td>
                      <td>@{{actividad.fecha_formated}}</td>
                      <td>@{{actividad.tipo_actividad}}</td>
                      <td>
                          <a title="Ver" href="/prospectos/39" class="btn btn-info">
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

  <!--Ultimas Cotizaciones -->
  <div class="row">
    <div class="col-sm-12">
      <div class="panel product-details">
        <div class="panel-heading">
          <h3 class="panel-title">Ultimas Cotizaciones</h3>
        </div>
        <div class="panel-body">
            <div id="oculto" class="hide">
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
                      <option disabled selected hidden>Cliente</option>
                      <option value=""></option>
                      
                    </select>
                </div>
                <div class="marg025 btn-group" id="cotizaciones_proyectos" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_cotizaciones_proyectos" id="select_cotizaciones_proyectos">
                      <option disabled selected hidden>Proyecto</option>
                      <option value=""></option>
                      
                    </select>
                </div>
              </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                
                <table class="table table-striped text-center" id="tablaCotizaciones">
                  <thead>
                    <tr>
                      <th class="text-center">Cliente</th>
                      <th class="text-center"><strong>Proyecto</strong></th>
                      <th class="text-center"><strong>Número Cotización</strong></th>
                      <th class="text-center"><strong>Fecha</strong></th>                     
                      <th class="text-center"><strong>Total</strong></th>
                      <th class="text-center"><strong>Acciones</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(cotizacion, index) in data.cotizaciones">
                      <td>@{{cotizacion.cliente_nombre}}</td>
                      <td>@{{cotizacion.prospecto_nombre}}</td>
                      <td>@{{cotizacion.id}}</td>
                      <td>@{{cotizacion.fecha_formated}}</td>
                      <td>@{{cotizacion.total | formatoMoneda}}</td>
                      <td class="text-warning">
                          <a title="Ver" :href="'/prospectos/'+cotizacion.prospecto_id" class="btn btn-info">
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

  <!--Cotizaciones Acceptadas -->
  <!--Ultimas Cotizaciones -->
  <div class="row">
    <div class="col-sm-12">
      <div class="panel product-details">
        <div class="panel-heading">
          <h3 class="panel-title">Ultimas Cotizaciones Aceptadas</h3>
        </div>
        <div class="panel-body">
            <div id="oculto" class="hide">
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
                      <option disabled selected hidden>Cliente</option>
                      <option value=""></option>
                      
                    </select>
                </div>
                <div class="marg025 btn-group" id="aceptadas_proyectos" >
                    <select name="proxDias" class="form-control" size="1" v-model="valor_aceptadas_proyectos" id="select_aceptadas_proyectos">
                      <option disabled selected hidden>Proyecto</option>
                      <option value=""></option>
                      
                    </select>
                </div>
              </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                
                <table class="table table-striped text-center" id="tablaAceptadas">
                  <thead>
                    <tr>
                      <th class="text-center">Cliente</th>
                      <th class="text-center"><strong>Proyecto</strong></th>
                      <th class="text-center"><strong>Número Cotización</strong></th>
                      <th class="text-center"><strong>Fecha</strong></th>                     
                      <th class="text-center"><strong>Total</strong></th>
                      <th class="text-center"><strong>Acciones</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(cotizacion, index) in data.aceptadas">
                      <td>@{{cotizacion.cliente_nombre}}</td>
                      <td>@{{cotizacion.prospecto_nombre}}</td>
                      <td>@{{cotizacion.id}}</td>
                      <td>@{{cotizacion.fecha_formated}}</td>
                      <td>@{{cotizacion.total | formatoMoneda}}</td>
                      <td class="text-warning">
                          <a title="Ver" :href="'/prospectos/'+cotizacion.prospecto_id" class="btn btn-info">
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
            <div class="panel-heading">
              <h3 class="panel-title">Total Facturado VS Cobrado</h3>
            </div>
            <div class="panel-body">
              <div class="row">
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
                <div class="col-lg-12">
                    <div class="ct-chart ct-perfect-fourth" id="porCobrarGrafica">
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
<script src="{{ URL::asset('js/plugins/chartist/chartist.min.js') }}" ></script>
<link href="{{ URL::asset('css/chartist.min.css') }}" rel="stylesheet" type="text/css">
<script>
  const app = new Vue({
    el: '#content',
    data: {
      prospectos: {},
      usuarioCargado: {{auth()->user()->id}},
      tablaCotizaciones: {},
      tablaAceptadas: {},
      tablaProximasActividades:{},
      data: {!! json_encode($data) !!},
      locale: localeES,
      //variables tabla cotizaciones
      cotizaciones_fecha_ini: '',
      cotizaciones_fecha_fin: '',
      valor_cotizaciones_clientes:'Cliente',
      valor_cotizaciones_proyectos:'Proyecto',
      //variables tabla cotizaciones aceptadas
      aceptadas_fecha_ini: '',
      aceptadas_fecha_fin: '',
      valor_aceptadas_clientes:'Cliente',
      valor_aceptadas_proyectos:'Proyecto',
      //variables gráfica cuentas por cobrar
      porCobrar_fecha_ini: '',
      porCobrar_fecha_fin: '',
      porCobrar_data: {}
    },
    mounted(){
      //Tabla cotizaciones
      this.tablaCotizaciones = this.tableFactory("#tablaCotizaciones", "cotizaciones");
      this.tablaAceptadas = this.tableFactory("#tablaAceptadas", "aceptadas");
      this.tablaProximasActividades=$("#tablaProximasActividades").DataTable();
      var vue = this;
      porCobrar_data=vue.data.cuentasCobrar;
      this.grafica();
      //Filtrado para rango de fechas
      $.fn.dataTableExt.afnFiltering.push(
        function( settings, data, dataIndex ) {
          var fecha = data[3] || 0;
          if(settings.nTable.id === 'tablaCotizaciones'){
            var min  = vue.cotizaciones_fecha_ini;
            var max  = vue.cotizaciones_fecha_fin;
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
        console.log('yes');
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
      }
    },
    filters:{
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$", 2);
    },
  },
    methods: {
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      tableFactory(table, prefix){
        var baseClientes;
        var baseProyectos;
        newTable=$(table).DataTable({
          "dom": 'f<"#'+prefix+'_fechas_container.pull-left">ltip',
          initComplete: function () {
            //Crear y llenar los select para clientes y proyectos
            baseClientes=$("#"+prefix+"_clientes").clone();
            var selectClientes= baseClientes.children('#select_'+prefix+'_clientes');
            this.api().column(0).data().sort().unique().each(function(d,j){   
              option='<option value="'+d+'">'+d+'</option>',     
              selectClientes.append((option));
            });
            baseProyectos=$("#"+prefix+"_proyectos").clone();
            var selectProyectos= baseProyectos.children('#select_'+prefix+'_proyectos');
            this.api().column(1).data().sort().unique().each(function(d,j){   
              option='<option value="'+d+'">'+d+'</option>',     
              selectProyectos.append((option));
            });
          }
        });
        //Agregarcontroles de fecha y selects
        $("#"+prefix+"_fechas_container").append($("#"+prefix+"_fecha_ini_control").clone());
        $("#"+prefix+"_fechas_container").append($("#"+prefix+"_fecha_fin_control").clone());
        $("#"+prefix+"_fechas_container").append(baseClientes);
        $("#"+prefix+"_fechas_container").append(baseProyectos);
        return newTable;
      },
      grafica(){
        var graphData={labels:[], series:[]};
        var serie1=[]
        var serie2=[]
        var serie3=[]
        porCobrar_data.forEach(element => {
          graphData['labels'].push(element.cotizacion_id)
          serie1.push(element.total);
          serie2.push(element.facturado);
          serie3.push(element.pagado);
        });
        var options = {
          seriesBarDistance: 10
        };

        var responsiveOptions = [
          ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];
        graphData.series.push(serie1, serie2, serie3)
        console.log(graphData);
        new Chartist.Bar('.ct-chart', graphData,options, responsiveOptions);
      },
      cargar(){
        axios.post('/prospectos/listado', {id: this.usuarioCargado})
        .then(({data}) => {
          $("#oculto").append($("#fecha_ini_control"));
          $("#oculto").append($("#fecha_fin_control"));
          this.tabla.destroy();
          this.prospectos = data.prospectos;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tabla = $("#tabla").DataTable({
              "dom": 'f<"#fechas_container.pull-left">ltip',
              "order": [[ 4, "desc" ]]
            });
            $("#fechas_container").append($("#fecha_ini_control"));
            $("#fechas_container").append($("#fecha_fin_control"));
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