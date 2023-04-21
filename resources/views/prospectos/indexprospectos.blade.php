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

  .color_text{
    color:#B3B3B3;
  }

  .btn-primary{
    color:#000;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header" style="background-color:#12160F; color:#B68911;">
  <h1 style="font-weight: bolder;">Cotizaciones en proceso</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">

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
            <div class="p-10 " style="display:inline-block;float: right;">
              <button class="btn btn-warning btn-sm btn">
                <a href="{{route('prospectos.create2')}}" style="color:#000;">
                  <i class="fas fa-address-book"></i> Nuevo Proyecto Prospecto
                </a>
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
                  <option value="2023-12-31">2023</option>
                </select>
            </div>          
          </h3>
        </div>
        <div class="panel-body">
          
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#12160F">
                  <th class="hide">#</th>
                  <th class="color_text">Usuario</th>
                  <th class="color_text">Cliente</th>
                  <th class="color_text">Nombre de Proyecto</th>
                  <th class="color_text">Diseñador</th>
                  <th class="color_text">Próxima actividad</th>
                  <th class="color_text">Fecha</th>
                  <th style="min-width:105px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(prospecto, index) in prospectos">
                  <td class="hide">@{{index+1}}</td>
                  <template>
                    <td>@{{prospecto.usuario}}</td>
                  </template>
                  <template>
                    <td>@{{prospecto.cliente}}</td>
                  </template>
                  <td>@{{prospecto.nombre}}</td>
                  <td>@{{prospecto.vendedor}}</td>
                  <td>@{{prospecto.actividad}}</td>
                  <td>@{{prospecto.fecha}}</td>
                  <td class="text-right">
                  <a class="btn btn-xs btn-info" title="Ver" :href="'/prospectos/'+prospecto.id">
                    <i class="far fa-eye"></i>
                  </a>
                  <a class="btn btn-xs btn-warning" title="Editar" :href="'/prospectos/'+prospecto.id+'/editar'">
                      <i class="fas fa-pencil-alt"></i>
                  </a>
                  <button class="btn btn-xs btn-success" title="Convertir el Proyecto" @click="convertirenproyecto(prospecto, index)">
                      <i class="fas fa-upload"></i>
                    </button>
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
                      <option value="{{$proyecto->id}}">{{$proyecto->nombre}}--{{$proyecto->cliente}}</option>
                  @endforeach
                </select>
                
                  <button class="btn btn-sm btn" >
                  <a href="{{route('prospectos.create')}}" style="color:white;">
                    <i class="fas fa-address-book"></i> Nuevo Proyecto
                  </a>
                  </button>           
            </div>

            <div class="form-group text-right">
                <button type="submit" class="" :disabled="cargando" @click='cotizacionueva()'>Aceptar</button>
                <button type="button" class="btn btn-default"
                        @click="proyecto_id=0; modalNuecaCotizacion=false;">
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
      prospectos: {!! json_encode($proyectos) !!},
      usuarioCargado: {{auth()->user()->id}},
      anio:'2023-12-31',
      tabla: {},
      locale: localeES,
      modalNuecaCotizacion: false,
      fecha_ini: '',
      fecha_fin: '',
      proyecto_id: '',
      cargando: false
    },
    filters: {
        formatoMoneda(numero) {
            return accounting.formatMoney(numero, "$", 2);
        },
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
        axios.post('/prospectos/listadoprospectos', {id: this.usuarioCargado , anio:this.anio})
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
      convertirenproyecto(prospecto, index){
        swal({
          title: 'Cuidado',
          text: "El Prospecto se convertirar en Proyecto "+prospecto.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.post('/prospectos/'+prospecto.id+'/convertir', {})
            .then(({data}) => {
              this.prospectos.splice(index, 1);
              swal({
                title: "Exito",
                text: "El Prospectos ha sido convertido en proyecto",
                type: "success"
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
          } //if confirmacion
        });
      },//fin borrar
      cotizacionueva() {
        if (this.proyecto_id == 0) {
          swal({
              title: "Error",
              text: "Debe de seleccionar un proyecto o crear uno nuevo para continuar.",
              type: "error"
          });
        }
        else{ 
          window.location.href = "/prospectos/"+this.proyecto_id+"/cotizar";
        }
      }
    }
});
</script>
@stop