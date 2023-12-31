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
<section class="content-header" style="background-color:#12160F; color:#B68911;">
  <h1 style="font-weight: bolder;">Reporte</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
    <div class="row">
        <div class="col-sm-12">
          <div class="panel product-details">
            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
              <h3 class="panel-title">Reporte de Compras
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
                          <btn class="dropdown-toggle" style="background-color:#000; color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                          v-model="fecha_ini" readonly
                          style="width:120px;"
                        />
                      </div><br>
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
                          <btn class="dropdown-toggle" style="background-color:#000; color:#fff;">
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
                    <div class="marg025 btn-group" id="select_proveedores" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_proveedores" id="selectproveedores" style="width:100%">
                        <option v-for="(option, index) in datos_select.proveedores" v-bind:value="option.valor" >
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
                    <div class="marg025 btn-group" id="select_ids" >
                        <select name="proxDias" class="form-control" size="1" v-model="valor_ids" id="selectids" style="width:100%">
                          <option v-for="option in datos_select.ids" v-bind:value="option.valor">
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
                        <tr style="background-color:#12160F">
                          <th class="text-center color_text">Fecha Aprobación</th>
                          <th class="text-center color_text"><strong>Número Compra</strong></th>
                          <th class="text-center color_text"><strong>Cotización</strong></th>
                          <th class="text-center color_text"><strong>Proveedor</strong></th>
                          <th class="text-center color_text"><strong>Cliente</strong></th>
                          <th class="text-center color_text"><strong>Proyecto</strong></th>                    
                          <th class="text-center color_text"><strong>Monto</strong></th>
                          <th class="text-center color_text"><strong>Moneda</strong></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(compra, index) in compras">
                          <td>@{{compra.created_at | date}}</td>
                          <td>@{{compra.id}}</td>
                          <td>@{{compra.cotizacion_id}}</td>
                          <td>@{{compra.proveedor_razon_social}}</td>
                          <td>@{{compra.cliente_nombre}}</td>
                          <td>@{{compra.proyecto_nombre}}</td>
                          <td v-bind:style= "[compra.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{compra.total | formatoMoneda}}</td>
                          <td v-bind:style= "[compra.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{compra.moneda | formatodolares}}</td>
                        </tr>
                        
                      </tbody>
                      <tfoot>
                        <tr>
                            <th colspan="6" style="text-align:right">Total MXN:</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right">Total USD:</th>
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
      compras: {!! json_encode($compras) !!},
      fecha_ini: '',
      fecha_fin: '',
      valor_proveedores:'Proveedores',
      valor_proyectos:'Proyectos',
      valor_ids:'Compra',
      datos_select:{proveedores:[], proyectos:[], ids:[]},   
      tabla: {},
      locale: localeES,
      proyectoSelect:null,
      cotizacionSelect:null,
      clienteSelect:null,
      totalm:'',
      totald:''
    },
    mounted(){
        var vue =this;
        this.proyectoSelect= $('#selectproyectos').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectproyectos").select2('data');
          vue.valor_proyectos = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
        this.cotizacionSelect= $('#selectproveedores').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectproveedores").select2('data');
          vue.valor_proveedores = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
        this.clienteSelect= $('#selectids').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectids").select2('data');
          vue.valor_ids = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });

      this.tabla = $("#tabla").DataTable({
          "dom": '<"#fechas_container.pull-left"f>tlp',
          "order":[],
          initComplete: function () {
            
            //Crear y llenar los select para proveedores 
            vue.datos_select.proveedores.push({opcion :'Proveedores', valor :'Proveedores'})
            //vue.datos_select.proveedores.push('');
            this.api().column(3).data().sort().unique().each(function(d,j){
              //console.log(d);     
              var b = d.replace("&amp;", " &");

              var a = {
                opcion : b,
                valor : b
              };  

              if (b == "") {
                vue.datos_select.proveedores.push({opcion :'Todos', valor :''})
              }
              else{
                vue.datos_select.proveedores.push(a);
              }

              //vue.datos_select.proveedores.push((d.replace("&amp;", " &")));
              //console.log(vue.datos_select.proveedores);
            });
            //Crear y llenar los select para proyecto 
            vue.datos_select.proyectos.push({valor:'Proyectos',opcion:'Proyectos'})
            vue.datos_select.proyectos.push({opcion :'Todos', valor :''})
            //vue.datos_select.proyectos.push('');
            this.api().column(5).data().sort().unique().each(function(d,j){   

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

            vue.datos_select.ids.push({valor:'Compra',opcion:'Compra'})
            vue.datos_select.ids.push({opcion :'Todos', valor :''})
            //vue.datos_select.ids.push('');
            this.api().column(1).data().sort().unique().each(function(d,j){   
              var b = d.replace("&amp;", " &");

              var a = {
                opcion : b,
                valor : b
              };  

              if (b == "") {
                vue.datos_select.ids.push({opcion :'Todos', valor :''})
              }
              else{
                vue.datos_select.ids.push(a);
              }
              //vue.datos_select.ids.push(d);
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
            var datos= api.columns([6,7], {search: 'applied'}).data();
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
 
            // Actualizar
            vue.totalm = accounting.formatMoney(totalMxn, "$", 2);
            vue.totald = accounting.formatMoney(totalUsd, "$", 2);

            var nCells = row.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(totalMxn, "$", 2);

            var secondRow = $(row).next()[0]; 
            var nCells = secondRow.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(totalUsd, "$", 2);
        }
      });
      
      $("#fechas_container").append($("#select_proveedores"));
      $("#fechas_container").append($("#select_proyectos"));
      $("#fechas_container").append($("#select_ids"));
      $("#fechas_container").append($("#fecha_ini_control"));
      $("#fechas_container").append($("#fecha_fin_control"));

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
      valor_proveedores:function(val){
        this.tabla.columns(3).search(this.valor_proveedores).draw();
      },
      valor_proyectos:function(val){
        this.tabla.columns(5).search(this.valor_proyectos).draw();
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
        },
        formatodolares(valor){
            return valor == 'Dolares'?'Dólares':'Pesos';
        },
        date(value){
  			return moment(value, 'YYYY-MM-DD  hh:mm:ss').format('DD/MM/YYYY');
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
          totalMxn: this.totalm,
          totalUsd: this.totald
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

        axios.post('/reportes/compras/pdf', formData,{headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          swal({
            title: "Reporte generado",
            text: "",
            type: "success"
          }).then(()=>{
            const link = document.createElement("a");
            link.href = '/storage/reportes/compras.pdf';
            link.download = 'ReporteCompras.pdf';
            link.click();

            //window.open('', '_blank').focus();
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
          totalMxn: this.totalm,
          totalUsd: this.totald
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

        axios.post('/reportes/compras/excel', formData,{headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          swal({
            title: "Reporte generado",
            text: "",
            type: "success"
          }).then(()=>{
            window.open('/storage/reportes/ReporteCompras.xls', '_blank').focus();
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