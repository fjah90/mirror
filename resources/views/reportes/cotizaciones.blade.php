@extends('layouts/default')

{{-- Page title --}}
@section('title')
Reportes | @parent
@stop

@section('header_styles')
<style>
  .marg025 {margin: 0 25px;}
</style>
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Reporte</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
    <div class="row">
        <div class="col-sm-12">
          <div class="panel product-details">
            <div class="panel-heading">
              <h3 class="panel-title">Reporte de Cotizaciones</h3>
            </div>
            <div class="panel-body">
                <div id="oculto_filtros" class="hide">
                    <dropdown id="fecha_ini_control" class="marg025">
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                          v-model="fecha_ini" readonly
                          style="width:120px;"
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser"
                          v-model="fecha_ini"/>
                        </li>
                      </template>
                    </dropdown>
                    <dropdown id="fecha_fin_control" class="marg025">
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                          v-model="fecha_fin" readonly
                          style="width:120px;"
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser"
                          v-model="fecha_fin"/>
                        </li>
                      </template>
                    </dropdown>
                    <div class="marg025 btn-group" id="select_clientes" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_clientes" id="select_clientes">
                        <option v-for="(option, index) in datos_select.clientes" v-bind:value="option" >
                            @{{ option }}
                          </option>
                          
                        </select>
                    </div>
                    <div class="marg025 btn-group" id="select_proyectos" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_proyectos" id="select_proyectos">
                          <option v-for="option in datos_select.proyectos" v-bind:value="option">
                            @{{ option }}
                          </option>
                          
                        </select>
                    </div>
                    <div class="marg025 btn-group" id="select_ids" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_ids" id="select_ids">
                          <option v-for="option in datos_select.ids" v-bind:value="option">
                            @{{ option }}
                          </option>
                          
                        </select>
                    </div>
                  </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    
                    <table class="table table-striped text-center" id="tabla">
                      <thead>
                        <tr>
                          <th class="text-center">Fecha</th>
                          <th class="text-center"><strong>Número Cotización</strong></th>
                          <th class="text-center"><strong>Cliente</strong></th>
                          <th class="text-center"><strong>Proyecto</strong></th>
                          <th class="text-center"><strong>Marca</strong></th>
                          <th class="text-center"><strong>Monto</strong></th>
                          <th class="text-center"><strong>Moneda</strong></th>
                          <th class="text-center"><strong>Usuario</strong></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(cotizacion, index) in cotizaciones">
                          <td>@{{cotizacion.fecha_formated}}</td>
                          <td>@{{cotizacion.id}}</td>
                          <td>@{{cotizacion.prospecto.cliente.nombre}}</td>
                          <td>@{{cotizacion.prospecto.nombre}}</td>
                          <td>
                            <template v-for="(entrada, index) in cotizacion.entradas">
                              <span > @{{(entrada.producto ? entrada.producto.proveedor.empresa:'')}}</span><br/>
                            </template>
                          </td>
                          <td>@{{cotizacion.total | formatoMoneda}}</td>
                          <td>@{{cotizacion.moneda}}</td>
                          <td>@{{cotizacion.user.name}}</td>
                        </tr>
                        
                      </tbody>
                      <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:right">Total MXN:</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="7" style="text-align:right">Total USD:</th>
                            <th></th>
                        </tr>
                    </tfoot>
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
</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script>
Vue.config.devtools = true;
const app = new Vue({
    el: '#content',
    data: {
      cotizaciones: ({!! JSON_encode($cotizaciones) !!}),
      fecha_ini: '',
      fecha_fin: '',
      valor_clientes:'Clientes',
      valor_proyectos:'Proyectos',
      valor_ids:'Cotización',
      datos_select:{clientes:[], proyectos:[], ids:[]},   
      tabla: {},
      locale: localeES
    },
    mounted(){
        var vue =this;
        this.cotizaciones=this.cotizaciones;
        console.log(this.cotizaciones[0].prospecto.cliente.nombre)
        console.log(this.cotizaciones[0].entradas[0].cantidad)
      this.tabla = $("#tabla").DataTable({
          "dom": 'f<"#fechas_container.pull-left">ltip',
          "order":[],
          initComplete: function () {
            
            //Crear y llenar los select para clientes 
            vue.datos_select.clientes.push('Clientes')
            vue.datos_select.clientes.push('');
            this.api().column(2).data().sort().unique().each(function(d,j){   
              vue.datos_select.clientes.push(d);
            });
            //Crear y llenar los select para clientes 
            vue.datos_select.proyectos.push('Proyectos')
            vue.datos_select.proyectos.push('');
            this.api().column(3).data().sort().unique().each(function(d,j){   
              vue.datos_select.proyectos.push(d);
            });

            vue.datos_select.ids.push('Cotización')
            vue.datos_select.ids.push('');
            this.api().column(1).data().sort().unique().each(function(d,j){   
              vue.datos_select.ids.push(d);
            });
          },

          
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            var formato = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            //datos de la tabla con filtros aplicados
            var datos= api.columns([5,6], {search: 'applied'}).data();
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
            var nCells = row.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(totalMxn, "$", 2);

            var secondRow = $(row).next()[0]; 
            var nCells = secondRow.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(totalUsd, "$", 2);
        }
      });
      $("#fechas_container").append($("#fecha_ini_control"));
      $("#fechas_container").append($("#fecha_fin_control"));
      $("#fechas_container").append($("#select_clientes"));
      $("#fechas_container").append($("#select_proyectos"));
      $("#fechas_container").append($("#select_ids"));

      $.fn.dataTableExt.afnFiltering.push(
        function( settings, data, dataIndex ) {
          var min  = vue.fecha_ini;
          var max  = vue.fecha_fin;
          var fecha = data[0]||0; // Our date column in the table

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
    },
    watch: {
      fecha_ini: function (val) {
        this.tabla.draw();
      },
      fecha_fin: function (val) {
        this.tabla.draw();
      },
      valor_clientes:function(val){
        this.tabla.columns(2).search(this.valor_clientes).draw();
      },
      valor_proyectos:function(val){
        this.tabla.columns(3).search(this.valor_proyectos).draw();
      },
      valor_ids:function(val){
        this.tabla.columns(1).search(this.valor_ids).draw();
      },
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
      
    }
});
</script>
@stop