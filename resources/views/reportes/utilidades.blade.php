@extends('layouts/default')

{{-- Page title --}}
@section('title')
Reportes | @parent
@stop

@section('header_styles')
<style>
  .marg025 {margin: 0 25px;}
  #tabla_length{
    float: left !important;
  }#tabla_filter{
    display: inline-block !important;
    float: none;
  }
  .color_text{
    color:#B3B3B3;
  }
</style>
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header" style="background-color:#12160F; color:#caa678;">
  <h1 style="font-weight: bolder;">Reporte</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
    <div class="row">
        <div class="col-sm-12">
          <div class="panel product-details">
            <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
              <h3 class="panel-title">Reporte de Operaciones
                <button style="background-color:transparent; border:none;float: right;">
                  <i class=" fa fa-file-pdf" v-on:click="pdf" style="color:#eb1b3d;font-size: 20px;"></i>
                </button>
                <button style="background-color:transparent; border:none;float: right;">
                  <i class="excel fa fa-file-excel" v-on:click="excel" style="color: #3ca906; font-size:20px;"></i>
                </button>

              </h3>
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
                        <select name="proxDias" class="form-control" size="1" v-model="valor_cotizaciones" id="selectcotizaciones" style="width:100%">
                        <option v-for="(option, index) in datos_select.cotizaciones" v-bind:value="option.valor" >
                            @{{ option.opcion }}
                          </option>
                          
                        </select>
                    </div>
                    <div class="marg025 btn-group" id="select_proyectos" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_proyectos" id="selectproyectos" style="width:100%">
                          <option v-for="option in datos_select.proyectos" v-bind:value="option.valor">
                            @{{ option.opcion }}
                          </option>
                          
                        </select>
                    </div>
                    <div class="marg025 btn-group" id="select_clientes" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_clientes" id="selectclientes" style="width:100%">
                          <option v-for="option in datos_select.clientes" v-bind:value="option.valor">
                            @{{ option.opcion }}
                          </option>
                          
                        </select>
                    </div>
                    <div class=" btn-group" id="select_usuarios" style="margin:0px 10px">
                        <select name="proxDias" class="form-control" size="1" v-model="valor_usuarios" id="selectusuarios" style="width:100%">
                          <option v-for="option in datos_select.usuarios" v-bind:value="option.valor">
                            @{{ option.opcion }}
                          </option>
                          
                        </select>
                    </div>
                  </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    
                    <table class="table table-striped text-center" id="tabla">
                      <thead>
                        <tr style="background-color:#12160F;">
                          <th class="text-center color_text">Número de Cotización</th>
                          <th class="text-center color_text"><strong>Cliente</strong></th>
                          <th class="text-center color_text"><strong>Proyecto</strong></th>
                          <th class="text-center color_text"><strong>Monto</strong></th>
                          <th class="text-center color_text"><strong>Moneda</strong></th>
                          <th class="text-center color_text"><strong>Número Compra</strong></th>
                          <th class="text-center color_text"><strong>Costo</strong></th>
                          <th class="text-center color_text"><strong>Utilidad</strong></th>
                          <th class="text-center color_text"><strong>%</strong></th>
                          <th class="text-center color_text"><strong>Usuario</strong></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(dato, index) in datos">
                          <td>@{{dato.cotizaciones_id}}</td>
                          <td>@{{dato.cliente_nombre}}</td>
                          <td>@{{dato.proyecto_nombre}}</td>
                          <td  v-bind:style= "[dato.cotizaciones_moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{dato.cotizaciones_total | formatoMoneda}}</td>
                          <td v-bind:style= "[dato.cotizaciones_moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{dato.cotizaciones_moneda | formatodolares}}</td>
                          <td>@{{dato.numero}}</td>
                          <td v-bind:style= "[dato.cotizaciones_moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{dato.total | formatoConvertirMoneda(dato.cotizaciones_moneda, dato.moneda)}}</td>
                          <td v-bind:style= "[dato.cotizaciones_moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{dato.total | formatoUtilidad(dato.cotizaciones_moneda, dato.moneda, dato.cotizaciones_total)}}</td>
                          <td>% @{{dato.total | formatoPorcentaje(dato.cotizaciones_moneda, dato.moneda, dato.cotizaciones_total)}}</td>
                          <td>
                            @{{dato.nombre_usuario}}
                          </td>
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
      fecha_ini: '01-01-2021',
      fecha_fin: '31-12-2021',
      valor_cotizaciones:'Cotizaciones',
      valor_proyectos:'Proyectos',
      valor_clientes:'Clientes',
      valor_usuarios:'Usuarios',
      datos_select:{cotizaciones:[], proyectos:[], clientes:[],usuarios:[] ,},   
      tabla: {},
      locale: localeES,
      proyectoSelect:null,
      cotizacionSelect:null,
      usuarioSelect:null,
      clienteSelect:null,
      totalmventas:'',
      totalmcosto:'',
      totalmutilidad:'',
      totaldventas:'',
      totaldcosto:'',
      totaldutilidad:''
    },
    mounted(){
        var vue =this;
        this.proyectoSelect= $('#selectproyectos').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectproyectos").select2('data');
          vue.valor_proyectos = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
        this.cotizacionSelect= $('#selectcotizaciones').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectcotizaciones").select2('data');
          vue.valor_cotizaciones = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
        this.clienteSelect= $('#selectclientes').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectclientes").select2('data');
          vue.valor_clientes = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
        this.usuarioSelect= $('#selectusuarios').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectusuarios").select2('data');
          vue.valor_usuarios = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
      this.tabla = $("#tabla").DataTable({
          "dom": '<"#fechas_container.pull-left"f>tlp',
          "order":[],
          initComplete: function () {
            
            //Crear y llenar los select para cotizaciones 
            vue.datos_select.cotizaciones.push({valor:'Cotizaciones',opcion:'Cotizaciones'})
            vue.datos_select.cotizaciones.push({opcion :'Todos', valor :''})
            //vue.datos_select.cotizaciones.push('');
            this.api().column(0).data().sort().unique().each(function(d,j){     
              var b = d.replace("&amp;", " &");

              var a = {
                opcion : b,
                valor : b
              };  

              if (b == "") {
                vue.datos_select.cotizaciones.push({opcion :'Todos', valor :''})
              }
              else{
                vue.datos_select.cotizaciones.push(a);
              }
              //vue.datos_select.cotizaciones.push((d.replace("&amp;", " &")));
            });
            //Crear y llenar los select para proyecto 
            vue.datos_select.proyectos.push({valor:'Proyectos',opcion:'Proyectos'})
            vue.datos_select.proyectos.push({opcion :'Todos', valor :''})
            //vue.datos_select.proyectos.push('');
            this.api().column(2).data().sort().unique().each(function(d,j){   
              var b = d.replace("&amp;", " &");

              var a = {
                opcion : b,
                valor : b
              };  

              if (b == "") {
                vue.datos_select.proyectos.push({opcion :'Todos', valor :''})
              }
              else{
                vue.datos_select.proyectos.push(a);
              }
              //vue.datos_select.proyectos.push(d);
            });

            vue.datos_select.usuarios.push({valor:'Usuarios',opcion:'Usuarios'})
            vue.datos_select.usuarios.push({opcion :'Todos', valor :''})
            //vue.datos_select.usuarios.push('');
            this.api().column(9).data().sort().unique().each(function(d,j){   
              var b = d.replace("&amp;", " &");

              var a = {
                opcion : b,
                valor : b
              };  

              if (b == "") {
                vue.datos_select.usuarios.push({opcion :'Todos', valor :''})
              }
              else{
                vue.datos_select.usuarios.push(a);
              }
              //vue.datos_select.usuarios.push(d);
            });

            vue.datos_select.clientes.push({valor:'Clientes',opcion:'Clientes'})
            vue.datos_select.clientes.push({opcion :'Todos', valor :''})
            //vue.datos_select.clientes.push('');
            this.api().column(1).data().sort().unique().each(function(d,j){   
              var b = d.replace("&amp;", " &");

              var a = {
                opcion : b,
                valor : b
              };  

              if (b == "") {
                vue.datos_select.clientes.push({opcion :'Todos', valor :''})
              }
              else{
                vue.datos_select.clientes.push(a);
              }
              //vue.datos_select.clientes.push(d);
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
                if(datos[1][index]=="Dólares"){
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
            vue.totalmventas = accounting.formatMoney(ventaMxn, "$", 2);
            vue.totalmcosto = accounting.formatMoney(totalMxn, "$", 2);
            vue.totalmutilidad = accounting.formatMoney(ventaMxn-totalMxn, "$", 2);

            vue.totaldventas = accounting.formatMoney(ventaUsd, "$", 2);
            vue.totaldcosto = accounting.formatMoney(totalUsd, "$", 2);
            vue.totaldutilidad = accounting.formatMoney(ventaUsd-totalUsd, "$", 2);

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
      $("#fechas_container").append($("#select_usuarios"));

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
      valor_usuarios:function(val){
        this.tabla.columns(9).search(this.valor_usuarios).draw();
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
        formatodolares(valor){
            return valor == 'Dolares'?'Dólares':'Pesos';
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
        formatoPorcentaje(value, monedaCotizacion, monedaCompra, totalCotizacion){
            if(monedaCotizacion == monedaCompra){
                  return (((totalCotizacion-value) / totalCotizacion) * 100).toFixed(2);
              }
            else if(monedaCotizacion == "Dolares" && monedaCompra == "Pesos"){
                  return (((totalCotizacion-(value/19)) / totalCotizacion) * 100).toFixed(2);
              }
            else if(monedaCotizacion == "Pesos" && monedaCompra == "Dolares"){
                  return (((totalCotizacion-(value*19)) / totalCotizacion) * 100).toFixed(2);
              }
        },
    },
    methods: {
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      pdf(){
        datos = this.tabla.rows( {order:'current' , search:'applied' } ).data(); 
        var datosfinal = {
          datos : [],
          totalMxnVentas: this.totalmventas,
          totalMxnCosto: this.totalmcosto,
          totalMxnUtilidad: this.totalmutilidad,
          totalUsdCosto: this.totaldcosto,
          totalUsdVentas: this.totaldventas,
          totalUsdUtilidad: this.totaldutilidad
        };
        var dat = [];

        for (var i = 0; i <= datos.length - 1; i++) {
          var data = {}
          Object.assign(data, datos[i]);
          //console.log(data);
          datosfinal.datos.push(data);
        }

        //console.log(datosfinal);

        var formData = objectToFormData(datosfinal, {indices: true});

        //console.log(datos);

        axios.post('/reportes/utilidades/pdf', formData,{headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          swal({
            title: "Reporte generado",
            text: "",
            type: "success"
          }).then(()=>{
            const link = document.createElement("a");
            link.href = '/storage/reportes/utilidades.pdf';
            link.download = 'ReporteUtilidades.pdf';
            link.click();
            //window.open('/storage/reportes/utilidades.pdf', '_blank').focus();
          });
        })
        .catch(({response}) => {
          console.error(response);
          this.cargando = false;
          swal({
            title: "Error",
            text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
            type: "error"
          });
        });

      },
      excel(){
        datos = this.tabla.rows( {order:'current' , search:'applied' } ).data(); 
        var datosfinal = {
          datos : [],
          totalMxnVentas: this.totalmventas,
          totalMxnCosto: this.totalmcosto,
          totalMxnUtilidad: this.totalmutilidad,
          totalUsdCosto: this.totaldcosto,
          totalUsdVentas: this.totaldventas,
          totalUsdUtilidad: this.totaldutilidad
        };
        var dat = [];

        for (var i = 0; i <= datos.length - 1; i++) {
          var data = {}
          Object.assign(data, datos[i]);
          //console.log(data);
          datosfinal.datos.push(data);
        }

        //console.log(datosfinal);

        var formData = objectToFormData(datosfinal, {indices: true});

        //console.log(datos);

        axios.post('/reportes/utilidades/excel', formData,{headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          swal({
            title: "Reporte generado",
            text: "",
            type: "success"
          }).then(()=>{
            window.open('/storage/reportes/ReporteUtilidades.xls', '_blank').focus();
          });
        })
        .catch(({response}) => {
          console.error(response);
          this.cargando = false;
          swal({
            title: "Error",
            text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
            type: "error"
          });
        });

      }
      
    }
});
</script>
@stop