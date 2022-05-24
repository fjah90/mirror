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
</style>
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 style="font-weight: bolder;">Reporte</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
    <div class="row">
        <div class="col-sm-12">
          <div class="panel product-details">
            <div class="panel-heading">
              <h3 class="panel-title">Reporte de Cotizaciones

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
                    <dropdown id="fecha_ini_control" style="margin:0px 10px">
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                          v-model="fecha_ini" readonly
                          style="width:100px;"
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
                    <dropdown id="fecha_fin_control" class="" style="margin:0px 10px">
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                          v-model="fecha_fin" readonly
                          style="width:100px;"
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
                    <div class=" btn-group" id="select_clientes" style="margin:0px 10px">
                        <select name="proxDias" class="form-control" size="1" v-model="valor_clientes" id="selectclientes" style="width:100%">
                        <option v-for="(option, index) in datos_select.clientes" v-bind:value="option.valor" >
                            @{{ option.opcion }}
                          </option>
                          
                        </select>
                    </div>
                    <div class=" btn-group" id="select_proyectos" style="margin:0px 10px">
                        <select name="proxDias" class="form-control" size="1" v-model="valor_proyectos" id="selectproyectos" tabindex="-1" style="width:100%">
                          <option v-for="option in datos_select.proyectos" v-bind:value="option.valor">
                            @{{ option.opcion }}
                          </option>
                          
                        </select>
                    </div>
                    <div class=" btn-group" id="select_ids" style="margin:0px 10px">
                        <select name="proxDias" class="form-control" size="1" v-model="valor_ids" id="selectids" style="width:100%">
                          <option v-for="option in datos_select.ids" v-bind:value="option.valor">
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
                    <div class=" btn-group" id="select_status" style="margin:0px 10px">
                        <select name="selectstatus" class="form-control" size="1" v-model="valor_status" id="selectstatus" style="width:100%">
                          <option v-for="option in datos_select.status" v-bind:value="option.valor">
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
                        <tr style="background-color:#fa7702">
                          <th class="text-center">Fecha</th>
                          <th class="text-center"><strong>Número Cotización</strong></th>
                          <th class="text-center"><strong>Fecha Aprobación</strong></th>
                          <th class="text-center"><strong>Cliente</strong></th>
                          <th class="text-center"><strong>Proyecto</strong></th>
                          <th class="text-center"><strong>Marca</strong></th>
                          <th class="text-center"><strong>Proveedores</strong></th>
                          <th class="text-center"><strong>IVA</strong></th>
                          <th class="text-center"><strong>Monto</strong></th>
                          <th class="text-center"><strong>Moneda</strong></th>
                          <th class="text-center"><strong>Usuario</strong></th>
                          <th class="text-center"><strong>Status</strong></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($cotizaciones as $cotizacion)
                        <tr>
                          <td>{{$cotizacion->fecha_formated}}</td>
                          <td>{{$cotizacion->id}}</td>
                          <td>
                            @if($cotizacion->proyecto_aprobado)
                            {{$cotizacion->proyecto_aprobado->created_at}}
                            @endif
                          </td>
                          <td>{{$cotizacion->prospecto->cliente->nombre}}</td>
                          <td>{{$cotizacion->prospecto->nombre}}</td>
                          <td>
                            <template>
                              @foreach($cotizacion->entradas2() as $entrada)
                              <span >@if($entrada->empresa == null || $entrada->empresa == '')Por Definir
                              @else{{$entrada->empresa}}
                              @endif</span><br/>
                              @endforeach
                            </template>
                          </td>
                          @if($cotizacion->monda == 'Dolares')
                          <td style="color:#266e07">
                            <template>
                              @foreach($cotizacion->entradas2() as $entrada)
                              <span > ${{number_format($entrada->total_importe, 2, '.', ',')}}</span><br/>
                              @endforeach
                            </template>
                          </td>
                          <td style="color:#266e07">${{number_format($cotizacion->iva, 2, '.', ',')}}</td>
                          <td style="color:#266e07">${{number_format($cotizacion->total, 2, '.', ',')}}</td>
                          <td style="color:#266e07">{{str_replace('ol','ól',$cotizacion->moneda)}}</td>
                          @else
                          <td style="color:#150a9b">
                            <template>
                              @foreach($cotizacion->entradas2() as $entrada)
                              <span > ${{number_format($entrada->total_importe, 2, '.', ',')}}</span><br/>
                              @endforeach
                            </template>
                          </td>
                          <td style="color:#150a9b">${{number_format($cotizacion->iva, 2, '.', ',')}}</td>
                          <td style="color:#150a9b">${{number_format($cotizacion->total, 2, '.', ',')}}</td>
                          <td style="color:#150a9b">{{str_replace('ol','ól',$cotizacion->moneda)}}</td>
                          @endif
                          <td>{{$cotizacion->user->name}}</td>
                          @if($cotizacion->aceptada == 0)
                          <td>Pendiente</td>
                          @else
                          <td>Aceptada</td>
                          @endif
                          
                        </tr>
                        @endforeach
                        
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
<script src="{{ URL::asset('js/plugins/zangdar/zangdar.min.js') }}" ></script>
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
      valor_usuarios:'Usuarios',
      valor_status:'Status',
      datos_select:{clientes:[], proyectos:[], ids:[], usuarios:[],status:[{valor:'Status',opcion:'Status'},{valor:'',opcion:'Todos'},{valor:'Aceptada',opcion:'Aceptada'},{valor:'Pendiente',opcion:'Pendiente'}]},   
      tabla: {},
      locale: localeES,
      proyectoSelect:null,
      cotizacionSelect:null,
      clienteSelect:null,
      usuarioSelect:null,
      statusSelect:null,
      totalm:'',
      totald:'',
      cargando:false
    },
    mounted(){
        var vue =this;

        this.proyectoSelect= $('#selectproyectos').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectproyectos").select2('data');
          vue.valor_proyectos = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
        this.cotizacionSelect= $('#selectids').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectids").select2('data');
          vue.valor_ids = value[0].id
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

        this.statusSelect= $('#selectstatus').select2({ width: '100px'}).on('select2:select',function () {       
          var value = $("#selectstatus").select2('data');
          vue.valor_status = value[0].id
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
        //console.log(this.valor_proyectos);
        


        this.cotizaciones=this.cotizaciones;
        console.log(this.cotizaciones[0].prospecto.cliente.nombre)
        console.log(this.cotizaciones[0].entradas[0].cantidad)
      this.tabla = $("#tabla").DataTable({
          "dom": '<"#fechas_container.pull-left"f>tlp',
          "order":[],
          initComplete: function () {
            
            //Crear y llenar los select para clientes 
            vue.datos_select.clientes.push({valor:'Clientes',opcion:'Clientes'})
            vue.datos_select.clientes.push({opcion :'Todos', valor :''})
            //vue.datos_select.clientes.push('');
            this.api().column(3).data().sort().unique().each(function(d,j){   
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
            //Crear y llenar los select para clientes 
            vue.datos_select.proyectos.push({valor:'Proyectos',opcion:'Proyectos'})
            vue.datos_select.proyectos.push({opcion :'Todos', valor :''})
            //vue.datos_select.proyectos.push('');
            this.api().column(4).data().sort().unique().each(function(d,j){   
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

            vue.datos_select.ids.push({valor:'Cotización',opcion:'Cotización'})
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


            vue.datos_select.usuarios.push({valor:'Usuarios',opcion:'Usuarios'})
            vue.datos_select.usuarios.push({opcion :'Todos', valor :''})
            //vue.datos_select.usuarios.push('');
            this.api().column(10).data().sort().unique().each(function(d,j){   
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
            var datos= api.columns([8,9], {search: 'applied'}).data();
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
      
      $("#fechas_container").append($("#select_clientes"));
      $("#fechas_container").append($("#select_proyectos"));
      $("#fechas_container").append($("#select_ids"));
      $("#fechas_container").append($("#select_usuarios"));
      $("#fechas_container").append($("#select_status"));
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
      valor_clientes:function(val){
        this.tabla.columns(3).search(this.valor_clientes).draw();
      },
      valor_proyectos:function(val){
        console.log(val);
        this.tabla.columns(4).search(this.valor_proyectos).draw();
      },
      valor_ids:function(val){
        this.tabla.columns(1).search(this.valor_ids).draw();
      },
      valor_usuarios:function(val){
        this.tabla.columns(10).search(this.valor_usuarios).draw();
      },
      valor_status:function(val){
        this.tabla.columns(11).search(this.valor_status).draw();
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
      pdf(){
        this.cargando=true;
        var uno = document.getElementById('boton');
        uno.innerHTML = 'Cargando';
        
        datos = this.tabla.rows( {order:'current' , search:'applied'} ).data(); 
        var datosfinal = {
          datos : [],
          totalMxn: this.totalm,
          totalUsd: this.totald
        };
        var dat = [];

        for (var i = 0; i <= datos.length - 1; i++) {
          var data = {}
          Object.assign(data, datos[i]);
          datosfinal.datos.push(data);
        }

        //console.log(datosfinal);

        var formData = objectToFormData(datosfinal, {indices: true});

        //console.log(datos);

        axios.post('/reportes/cotizaciones/pdf', formData,{headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          swal({
            title: "Reporte generado",
            text: "",
            type: "success"
          }).then(()=>{
            this.cargando=false;
            var uno = document.getElementById('boton');
            uno.innerHTML = 'PDF';
            const link = document.createElement("a");
            link.href = '/storage/reportes/cotizaciones.pdf';
            link.download = 'ReporteCotizaciones.pdf';
            link.click();
            //window.open('/storage/reportes/cotizaciones.pdf', '_blank').focus();
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

        axios.post('/reportes/cotizaciones/excel', formData,{headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          swal({
            title: "Reporte generado",
            text: "",
            type: "success"
          }).then(()=>{
            window.open('/storage/reportes/ReporteCotizaciones.xls', '_blank').focus();
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