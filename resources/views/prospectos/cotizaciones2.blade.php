@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Proyectos | @parent
@stop

@section('header_styles')
<style>
  .marg025 {margin: 0 25px;}
  #tabla_length{
    float: left !important;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 style="font-weight: bolder;">Cotizaciones en proceso</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            <div class="p-10" style="display:inline-block">
              Usuario
              @role('Administrador')
                <select class="form-control" @change="cargar()" v-model="usuarioCargado" style="width:auto;display:inline-block;">
                  <option value="Todos">Todos</option>
                  @foreach($usuarios as $usuario)
                  <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                  @endforeach
                </select>
              @endrole
            </div>
            <div class="p-10" style="display:inline-block;float: right;">
              <button class="btn btn-sm btn-primary" @click="modalNuecaCotizacion=true">
                  <i class="fas fa-address-book"></i> Nueva Cotización
              </button>
            </div>
            <div class="p-10">
              
            </div>
            <div class="p-10" style="display:inline-block">
              Año  
                <select class="form-control" @change="cargar()" v-model="anio" style="width:auto;display:inline-block;">
                  <option value="Todos">Todos</option>
                  <option value="2019-12-31">2019</option>
                  <option value="2020-12-31">2020</option>
                  <option value="2021-12-31">2021</option>
                  <option value="2022-12-31">2022</option>
                </select>
            </div>          
          </h3>
        </div>
        <div class="panel-body">
          
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#c37ff3">
                  <th class="hide">#</th>
                  <th>Usuario</th>
                  <th>Cliente</th>
                  <th>Nombre de Proyecto</th>
                  <th>Número de cotización</th>
                  <th>Total</th>
                  <th style="min-width:105px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cotizacion, index) in cotizaciones">
                  <td class="hide">@{{index+1}}</td>
                  <template>
                    <td>@{{cotizacion.user_name}}</td>
                  </template>
                  <template>
                    <td>@{{cotizacion.cliente_nombre}}</td>
                  </template>
                  <td>@{{cotizacion.prospecto_nombre}}</td>
                  <td>@{{cotizacion.numero}}</td>
                  <template>
                    <td>@{{cotizacion.total | formatoMoneda}} @{{cotizacion.moneda|formatoCurrency}}</td>
                  </template>
                  <td class="text-right">
                      <a class="btn btn-xs btn-success" title="PDF" :href="cotizacion.archivo"
                         :download="'C '+cotizacion.numero+' Intercorp '+cotizacion.cliente_nombre+' '+cotizacion.prospecto_nombre+'.pdf'">
                          <i class="far fa-file-pdf"></i>
                      </a>
                      
                      <a title="Ver" :href="'/prospectos/'+cotizacion.prospecto_id+'/cotizar'" class="btn btn-xs btn-info">
                            <i class="far fa-eye"></i>
                      </a>

                  </td>  
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Aceptar Modal -->
    <modal v-model="modalNuecaCotizacion" :title="'Nueva Cotización'" :footer="false">
        
            
            <div class="form-group">
                <label class="control-label">Seleccione un proyecto</label>
                <select name="proyecto_id" v-model="proyecto_id"
                            class="form-control" required id="proyecto-select" style="width: 300px;">
                  @foreach($proyectos as $proyecto)
                      <option value="{{$proyecto->id}}" @click="copiar3(index,{{$proyecto->id}});">{{$proyecto->nombre}}--{{$proyecto->cliente->nombre}}</option>
                  @endforeach
                </select>
                
                <button class="btn btn-sm btn-primary" style="display:inline-block;float: right;">
                  <a href="{{route('prospectos.create')}}" style="color:white;">
                    <i class="fas fa-address-book"></i> Nuevo Proyecto
                  </a>
                </button>
                
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                <button type="button" class="btn btn-default"
                        @click="aceptar.cotizacion_id=0; openAceptar=false;">
                    Cancelar
                </button>
            </div>
        
    </modal>
    <!-- /.Aceptar Modal -->

</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}" ></script>
<script>

const app = new Vue({
    el: '#content',
    data: {
      cotizaciones: {!! json_encode($cotizaciones) !!},
      usuarioCargado: {{auth()->user()->id}},
      anio:'2022-12-31',
      tabla: {},
      locale: localeES,
      modalNuecaCotizacion: false,
      fecha_ini: '',
      fecha_fin: '',
      proyecto_id: '',
      cargando: false
    },
    mounted(){
      $.fn.dataTable.moment( 'DD/MM/YYYY' );
      this.tabla = $("#tabla").DataTable({
        "dom": 'f<"#fechas_container.pull-left">tlip',
        "order": [[ 4, "desc" ]]
      });
      //$("#fechas_container").append($("#fecha_ini_control"));
      //$("#fechas_container").append($("#fecha_fin_control"));
      
      var vue = this;
      
      $.fn.dataTableExt.afnFiltering.push(
        function( settings, data, dataIndex ) {
          var min  = vue.fecha_ini;
          var max  = vue.fecha_fin;
          var fecha = data[4] || 0; // Our date column in the table

          var startDate   = moment(min, "DD/MM/YYYY");
          var endDate     = moment(max, "DD/MM/YYYY");
          var diffDate = moment(fecha, "YYYY-MM-DD");
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
      }
    },
    methods: {
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      cargar(){
        axios.post('/prospectos/listado3', {id: this.usuarioCargado , anio:this.anio})
        .then(({data}) => {
          //$("#oculto").append($("#fecha_ini_control"));
          //$("#oculto").append($("#fecha_fin_control"));
          this.tabla.destroy();
          this.cotizaciones = data.cotizaciones;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tabla = $("#tabla").DataTable({
              "dom": 'f<"#fechas_container.pull-left">ltip',
              "order": [[ 4, "desc" ]]
            });
            //$("#fechas_container").append($("#fecha_ini_control"));
            //$("#fechas_container").append($("#fecha_fin_control"));
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
    }
});
</script>
@stop
