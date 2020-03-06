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
              <h3 class="panel-title">Reporte de Utilidades</h3>
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
                    <div class="marg025 btn-group" id="select_cotizaciones" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_cotizaciones" id="select_cotizaciones">
                        <option v-for="(option, index) in datos_select.cotizaciones" v-bind:value="option" >
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
                    <div class="marg025 btn-group" id="select_clientes" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_clientes" id="select_clientes">
                          <option v-for="option in datos_select.clientes" v-bind:value="option">
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
                          <th class="text-center">Número de Cotización</th>
                          <th class="text-center"><strong>Cliente</strong></th>
                          <th class="text-center"><strong>Proyecto</strong></th>
                          <th class="text-center"><strong>Monto</strong></th>
                          <th class="text-center"><strong>Moneda</strong></th>
                          <th class="text-center"><strong>Numero Compra</strong></th>
                          <th class="text-center"><strong>Costo</strong></th>
                          <th class="text-center"><strong>Utilidad</strong></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(dato, index) in datos">
                          <td>@{{dato.cotizaciones_id}}</td>
                          <td>@{{dato.cliente_nombre}}</td>
                          <td>@{{dato.proyecto_nombre}}</td>
                          <td>@{{dato.cotizaciones_total | formatoMoneda}}</td>
                          <td>@{{dato.cotizaciones_moneda}}</td>
                          <td>@{{dato.numero}}</td>
                          <td>@{{dato.total | formatoConvertirMoneda(dato.cotizaciones_moneda, dato.moneda)}}</td>
                          <td>@{{dato.total | formatoUtilidad(dato.cotizaciones_moneda, dato.moneda, dato.cotizaciones_total)}}</td>
                        </tr>
                        
                      </tbody>
                      <tfoot>
                        <tr>
                            <th colspan="5" style="text-align:right"></th>
                            <th>Total Ventas</th>
                            <th>Total Costo</th>
                            <th>Total Utilidad</th>
                        </tr>
                        <tr>
                            <th colspan="5" style="text-align:right">MXN:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="5" style="text-align:right">USD:</th>
                            <th></th>
                            <th></th>
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

const app = new Vue({
    el: '#content',
    data: {
      datos: {!! json_encode($datos) !!},
      fecha_ini: '',
      fecha_fin: '',
      valor_cotizaciones:'Cotizaciones',
      valor_proyectos:'Proyectos',
      valor_clientes:'Clientes',
      datos_select:{cotizaciones:[], proyectos:[], clientes:[]},   
      tabla: {},
      locale: localeES
    },
    mounted(){
        var vue =this;
      this.tabla = $("#tabla").DataTable({
          "dom": 'f<"#fechas_container.pull-left">ltip',
          "order":[],
          initComplete: function () {
            
            //Crear y llenar los select para cotizaciones 
            vue.datos_select.cotizaciones.push('Cotizaciones')
            vue.datos_select.cotizaciones.push('');
            this.api().column(0).data().sort().unique().each(function(d,j){     
              vue.datos_select.cotizaciones.push((d.replace("&amp;", " &")));
            });
            //Crear y llenar los select para proyecto 
            vue.datos_select.proyectos.push('Proyectos')
            vue.datos_select.proyectos.push('');
            this.api().column(2).data().sort().unique().each(function(d,j){   
              vue.datos_select.proyectos.push(d);
            });

            vue.datos_select.clientes.push('Clientes')
            vue.datos_select.clientes.push('');
            this.api().column(1).data().sort().unique().each(function(d,j){   
              vue.datos_select.clientes.push(d);
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
            //datos de la tabla con filtros aplicados calcular costo
            var datos= api.columns([6,4], {search: 'applied'}).data();
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
            //datos de la tabla con filtros aplicados calcular venta
            var datos= api.columns([3,4,0], {search: 'applied'}).data();
            var numeroCotizacion=[];
            var ventaMxn = 0;
            var ventaUsd = 0;
            //suma de montos
            datos[0].forEach(function(element, index){
                if(numeroCotizacion.includes(datos[2][index])){

                }else{
                    numeroCotizacion.push(datos[2][index])
                    if(datos[1][index]=="Dolares"){
                        ventaUsd+=formato(element)
                    }else{
                        ventaMxn+=formato(element)
                    }
                }    
            });
 
            // Actualizar
            var secondRow =$(row).next()[0];
            var nCells = secondRow.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(ventaMxn, "$", 2);
            nCells[2].innerHTML = accounting.formatMoney(totalMxn, "$", 2);
            nCells[3].innerHTML = accounting.formatMoney(ventaMxn-totalMxn, "$", 2);

            var thirdRow = $(row).next().next()[0]; 
            var nCells = thirdRow.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(ventaUsd, "$", 2);
            nCells[2].innerHTML = accounting.formatMoney(totalUsd, "$", 2);
            nCells[3].innerHTML = accounting.formatMoney(ventaUsd-totalUsd, "$", 2);
        }
      });
    //   $("#fechas_container").append($("#fecha_ini_control"));
    //   $("#fechas_container").append($("#fecha_fin_control"));
      $("#fechas_container").append($("#select_cotizaciones"));
      $("#fechas_container").append($("#select_proyectos"));
      $("#fechas_container").append($("#select_clientes"));

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
      valor_cotizaciones:function(val){
        this.tabla.columns(0).search(this.valor_cotizaciones).draw();
      },
      valor_proyectos:function(val){
        this.tabla.columns(2).search(this.valor_proyectos).draw();
      },
      valor_clientes:function(val){
        this.tabla.columns(1).search(this.valor_clientes).draw();
      },
    },
    filters:{
        formatoMoneda(numero){
            return accounting.formatMoney(numero, "$", 2);
        },
        formatoCurrency(valor){
            return valor=='Dolares'?'USD':'MXN';
        },
        date(value){
  			return moment(value, 'YYYY-MM-DD  hh:mm:ss').format('DD/MM/YYYY');
        },
        formatoConvertirMoneda(value, monedaCotizacion, monedaCompra){
  			if(monedaCotizacion == monedaCompra){
                  return accounting.formatMoney(value, "$", 2);
              }
            else if(monedaCotizacion == "Dolares" && monedaCompra == "Pesos"){
                  return accounting.formatMoney((value/19), "$", 2);
              }
            else if(monedaCotizacion == "Pesos" && monedaCompra == "Dolares"){
                  return accounting.formatMoney((value*19), "$", 2);
              }
        },
        formatoUtilidad(value, monedaCotizacion, monedaCompra, totalCotizacion){
  			if(monedaCotizacion == monedaCompra){
                  return accounting.formatMoney((totalCotizacion-value), "$", 2);
              }
            else if(monedaCotizacion == "Dolares" && monedaCompra == "Pesos"){
                  return accounting.formatMoney((totalCotizacion-(value/19)), "$", 2);
              }
            else if(monedaCotizacion == "Pesos" && monedaCompra == "Dolares"){
                  return accounting.formatMoney((totalCotizacion-(value*19)), "$", 2);
              }
        },
    },
    methods: {
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      
    }
});
</script>
@stop