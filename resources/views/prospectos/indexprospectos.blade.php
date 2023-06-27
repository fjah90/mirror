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
  <h1 style="font-weight: bolder;">Prospectos</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">

          <h3 class="panel-title">
            <div class="p-10" style="display:inline-block">
              Diseñador  
              @role('Administrador|Dirección')
                <select class="form-control"  v-model="valor_disenadores" style="width:auto;display:inline-block;">
                <option value="">Todos</option>
                  @foreach($usuarios as $usuario)
                  <option value="{{$usuario->nombre}}">{{$usuario->nombre}}</option>
                  @endforeach
                </select>
              @endrole

              <!--
              @role('Administrador|Dirección')
                <select class="form-control" @change="cargar()" v-model="usuarioCargado" style="width:auto;display:inline-block;">
                  <option value="Todos">Todos</option>
                  @foreach($usuarios as $usuario)
                  <option value="{{$usuario->id}}">{{$usuario->nombre}}</option>
                  @endforeach
                </select>
              @endrole
-->
            </div>
            <div class="p-10 " style="display:inline-block;float: right;">
            <a href="#myModal" role="button" class="btn btn-warning btn-sm btn" data-toggle="modal" style="color:#000">
            <i class="fas fa-calendar"></i> </a>
              <button class="btn btn-warning btn-sm btn">
              @can('Prospectos nuevo')
                <a href="{{route('prospectos.create2')}}" style="color:#000;">
                  <i class="fas fa-address-book"></i> Nuevo Proyecto Prospecto
                </a>
              @endcan
              </button>
            </div>
            <!--Botones para apartados de proyectos activos o cancelados-->
            <div class="row">
              <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-dark" style="background-color:#FFCE56; color:#12160F;">
                  <a href="{{route('prospectos.indexprospectos')}}" style="color:#000;" id="">
                  <i class="fas fa-user-book"></i>TODOS
                  </a>
                </button>
                <button type="submit" class="btn btn-dark" style="background-color:#FFCE56; color:#12160F;">
                  <a href="{{url('/prospectos/Activo/indexprospectos')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>ACTIVOS
                  </a>
                </button>
                <button type="submit" class="btn btn-dark" style="background-color:#FFCE56; color:#12160F;">
                  <a href="{{url('/prospectos/Cancelado/indexprospectos')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>CANCELADOS
                  </a>
                </button>
              </div>
            </div>
            <div class="p-10">
              <button style="background-color:#FFCE56; color:#12160F;" class="btn btn-sm btn-primary" @click="modalTareas=true">
                  <i class="fas fa-star"></i> Tareas
                  @if( count ($tareas_pendientes) > 0)
                  <i class="fas fa-bell" style="    color: red; font-size: 22px; position: absolute; margin-top: -12px;"></i>
                  @endif
              </button>
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
                  <option value="2024-12-31">2024</option>
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
                  <th class="color_text">Cliente</th>
                  <th class="color_text">Nombre de Proyecto</th>
                  <th class="color_text">Diseñador</th>
                  <th class="color_text">Proyección de venta en USD</th>
                  <th class="color_text">Factibilidad</th>
                  <th class="color_text">Próxima actividad</th>
                  <th class="color_text">Fecha próxima actividad</th>
                  <th class="color_text">Estatus</th>
                  <th style="min-width:105px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(prospecto, index) in prospectos">
                  <td class="hide">@{{index+1}}</td>
                  <template>
                    <td>@{{prospecto.cliente}}</td>
                  </template>
                  <td>@{{prospecto.nombre}}</td>
                  <td>@{{prospecto.vendedor}}</td>
                  <td id="proyeccion_venta">@{{prospecto.proyeccion_venta|formatoMoneda}}</td>
                  <td>@{{prospecto.factibilidad}}</td>
                  <td>@{{prospecto.actividad}}</td>
                  <td>@{{format_date(prospecto.fecha)}}</td>
                  <td>@{{prospecto.estatus}}</td>
                  <td class="text-right">
                  @can('Prospectos ver')
                   <!--
                  <button class="btn btn-xs btn-info" title="Ver" @click="clickver(prospecto.id)"
                  ><i class="far fa-eye"></i></button>
                   -->
                  <a class="btn btn-xs btn-info" title="Ver" :href="'/prospectos/'+prospecto.id">
                    <i class="far fa-eye"></i>
                  </a>
                
                  @endcan
                  @can('Prospectos editar')
                  <!--
                  <button class="btn btn-xs btn-warning" title="Editar" @click="clickeditar(prospecto.id)"
                  ><i class="fas fa-pencil-alt"></i></button>
                   -->
                  <a class="btn btn-xs btn-warning" title="Editar" :href="'/prospectos/'+prospecto.id+'/editar'">
                      <i class="fas fa-pencil-alt"></i>
                  </a>
                 
                  @endcan
                  @can('Prospectos convertir')
                  <button class="btn btn-xs btn-success" title="Convertir el Proyecto" @click="convertirenproyecto(prospecto, index)">
                      <i class="fas fa-upload"></i>
                    </button>
                  @endcan
                  </td>  
                </tr>
              </tbody>
               <tfoot>
                  <tr>
                      <th colspan="3" style="text-align:right;">Total:</th>
                      <th colspan="6" id='totalsum'>${{number_format($proyectos->sum('proyeccion_venta'), 2, '.', ',')}}</th>
                  </tr>
              </tfoot>
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


    <!-- Tareas Modal -->
    <modal id='modal_tareas' v-model="modalTareas" :title="'Tareas'" :footer="false"  size="lg">

      <table id="tablatareas" class="table table-bordred"
              data-page-length="15">
              <thead>
                <tr style="background-color:#12160F">
                  <th class="hide">#</th>
                  <th class="color_text">Tarea</th>
                  <th class="color_text">Status</th>
                  <th class="color_text">Diseñador</th>
                  <th class="color_text">Director</th>
                  <th class="color_text">Fecha de creación</th>
                  <th class="color_text">Fecha de edición</th>
                  <th style="min-width:105px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(t, index) in tareas">
                 <td class="hide">@{{index + 1}}</td>
                 <td>@{{t.tarea}}</td>
                 <td>@{{t.status}}</td>
                 <td v-if="t.vendedor_id != null">@{{t.vendedor.nombre}}</td>
                 <td v-else></td>
                 <td v-if="t.director_id != null">@{{t.director.name}}</td>
                 <td v-else></td>
                 <td>@{{t.created_at}}</td>
                 <td>@{{t.updated_at}}</td>
                 <td>
                  <button class="btn btn-xs btn-success" title="Editar tarea" @click="editartarea(t, index)" :disabled="editando">
                      <i class="fas fa-pen"></i>
                    </button>
                    <!--
                    <button class="btn btn-xs btn-warning" title="Historial de tarea" @click="historialtarea(t, index)" :disabled="historialcargando">
                      <i class="fas fa-list"></i>
                    </button>
-->
                  </td>
                </tr>
              </tbody>
            </table>
            <input type="hidden" name="tarea_id" class="form-control" v-model="tarea.id" />
            @role('Administrador|Dirección')
                  <div class="form-group">
                      <label class="control-label text-danger">Tarea</label>
                      <textarea class="form-control" name="tarea" rows="3" cols="80"
                                v-model="tarea.tarea" requered></textarea>
                  </div>
                  <div class="form-group">
                  <label class="control-label">Diseñador</label>
                    <select class="form-control" v-model="tarea.vendedor_id" style="width: 300px;" readonly>
                    @foreach($vendedores as $vendedor)
                    <option value="{{$vendedor->id}}">{{$vendedor->nombre}}</option>
                    @endforeach
                  </select>
                    <label class="control-label">Status</label>
                    <select name="status" v-model="tarea.status"
                                class="form-control" required id="proyecto-select" style="width: 300px;">
                      
                          <option value="Pendiente">Pendiente</option>
                          <option value="En proceso">En proceso</option>
                          <option value="Terminada">Terminada</option>
                    </select>         
                </div>
            @endrole
            @role('Diseñadores')
                  <div class="form-group">
                      <label class="control-label text-danger">Tarea</label>
                      <textarea class="form-control" name="tarea" rows="3" cols="80"
                                v-model="tarea.tarea"></textarea>
                  </div>
                  <div class="form-group">
                  <label style="display:none;"  class="control-label">Diseñador</label>
                  
                    <select style="display:none;"  class="form-control" v-model="tarea.vendedor_id" style="width: 300px;" disabled>
                      @foreach($vendedores as $vendedor)
                      <option value="{{$vendedor->id}}">{{$vendedor->nombre}}</option>
                      @endforeach
                    </select>
                    <label id="directores_title" class="control-label">Directores</label>
                    <select id="directores_select" class="form-control" v-model="tarea.director_id" style="width: 300px;">
                      @foreach($directores as $director)
                      <option value="{{$director->id}}">{{$director->name}}</option>
                      @endforeach
                    </select>
                    <label class="control-label">Status</label>
                    <select name="status" v-model="tarea.status"
                                class="form-control" required id="proyecto-select" style="width: 300px;">
                      
                          <option value="Pendiente">Pendiente</option>
                          <option value="En proceso">En proceso</option>
                          <option value="Terminada">Terminada</option>
                    </select>         
                </div>

            @endrole
           
                
            
              
              <div class="form-group text-right">
                  <button type="submit" class="btn btn-default" :disabled="cargando" @click='guardartarea()'>Guardar</button>
                  <button type="button" class="btn btn-default"
                          @click="cancelartarea(); modalTareas=false;">
                      Cancelar
                  </button>
              </div>
          
        
    </modal>

    <!-- Historial Tareas Modal -->
    <modal id='modal_historial' v-model="modalHistorial" :title="'Historial de Tareas'" :footer="false"  size="lg">

      <table id="tablahistorial" class="table table-bordred"
              data-page-length="15" style="width:100%;">
              <thead>
                <tr style="background-color:#12160F">
                  <th class="hide">#</th>
                  <th class="color_text">Usuario</th>
                  <th class="color_text">Valor Anterior</th>
                  <th class="color_text">Valor Nuevo</th>
                  <th class="color_text">Fecha de edición</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(h, index) in historial">
                 <td class="hide">@{{index + 1}}</td>
                 <td>@{{h.usuario}}</td>
                 <td>@{{h.anterior}}</td>
                 <td>@{{h.nuevo}}</td>
                 <td>@{{h.fecha}}</td>
                </tr>
              </tbody>
            </table>    
            <div class="form-group text-right">
                <button type="button" class="btn btn-default"
                        @click="cancelarhistorial(); modalHistorial=false;">
                    Cancelar
                </button>
            </div>
          
        
    </modal>


    <!-- Modal eventos -->
    <modal v-model="modalEventos" :title="'Actividad'" :footer="false"  size="md">
        <div class="modal-header">
            <h4 class="modal-title" id="titulo_evento">Modal title</h4>
          </div>
          <div class="modal-body" id="descripcion_evento">
            <p>Modal body text goes here.</p>
          </div>
    
      <div class="form-group text-right">
          <button type="button" class="btn btn-default"
                  @click="modalEventos=false;">
              Cancelar
          </button>
      </div>
          
        
    </modal>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Próximas Actividades</h3>
            </div>
            <div class="modal-body">
                
                        <div id="calendar"></div>
                      
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            </div>
        </div>
      </div>
    </div>


   
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
      usuarioCargado: {!! json_encode($disenador_id) !!},
      vendedores:{!! json_encode($vendedores) !!},
      anio:{!! json_encode($anio2) !!},
      tabla: {},
      tabla2:{},
      tablahistorial:{},
      tareas: {!! json_encode($tareas) !!},
      historial:[],
      tarea:{
        id:'',
        tarea: '',
        status:'',
        vendedor_id: '',
        director_id:''
      },
      modalTareas: true,
      modalHistorial:false,
      modalEventos: false,
      modalCalendario:false,
      locale: localeES,
      modalNuecaCotizacion: false,
      fecha_ini: '',
      fecha_fin: '',
      proyecto_id: '',
      cargando: false,
      editando : false,
      historialcargando : false,
      select_disenadores:[],
      valor_disenadores:'Diseñadores',
    },
    filters: {
        formatoMoneda(numero) {
            return accounting.formatMoney(numero, "$", 2);
        },
    },
    mounted(){
      var vue =this;
      $.fn.dataTable.moment('DD/MM/YYYY');
      this.tabla = $("#tabla").DataTable({
        stateSave: true,
        "dom": 'f<"#fechas_container.pull-left">tlip',
        //Aqui lo que hace es que cambia los datos del footer siempre que haya un filtrado
        "footerCallback": function ( row, data, start, end, display ) {
          //tomamos los datos de nuestra tabla
            var api = this.api(), data;
            //como las cantidades vienen en formato les quitamos el formato y dejamo solo los valores numericos
            var formato = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            //datos de la tabla con filtros aplicados
            var datos= api.columns([4], {search: 'applied'}).data();
            var totalMxn = 0;
            //suma de montos
            datos[0].forEach(function(element, index){      
                    totalMxn+=formato(element)
            });
            // Actualizar el campo
            var nCells = row.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(totalMxn, "$", 2);
        }
       
        
      });

      this.tablahistorial = $("#tablahistorial").DataTable({
      });
  

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          height: 650,
          aspectRatio: 2,
          initialView: 'dayGridMonth',
          events: {!! json_encode($proximas_actividades) !!},
          eventColor: '#800080',
          eventClick: function(info) {
            document.getElementById("titulo_evento").innerHTML = info.event.title;
            document.getElementById("descripcion_evento").innerHTML = info.event.extendedProps.description;
              vue.modalEventos = true;
            },

        });
        $('#myModal').on('shown.bs.modal', function () {
            calendar.render();
          });
        
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
          var diffDate = moment(fecha, "DD/MM/YYYY");/***Ajustando la fecha en la vista de prospectos***/
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
      //filtramos por el disenador seleccionado
      valor_disenadores:function(val){
        this.tabla.columns(3).search(this.valor_disenadores).draw();
      },
    },
    methods: {
      dateParser(value){
        return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },

      format_date(value){
         if (value) {
           return moment(String(value)).format('DD/MM/YYYY')
          }
      },
      editartarea(tarea , index){
        var rol = {!! json_encode(auth()->user()->roles[0]->name) !!}; 
        if(rol == 'Diseñadores'){
          $('#directores_select').css('display','none');
          $('#directores_title').css('display','none');
          
        }
        else{
          $('#directores_select').css('display','block');
          $('#directores_title').css('display','block');
        }
        this.editando = true;
        this.tarea = tarea;
      },
      historialtarea(tarea , index){
        this.historialcargando = true;
        axios.get('/gethistorialtarea/'+tarea.id, {
        })
        .then(({data}) => {
          $('#tablahistorial').DataTable().destroy();
          //this.tablahistorial.destroy();
          console.log(data.historial);
          this.historial = data.historial;
          this.historialcargando = false;
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

        this.modalHistorial = true;
        $('#modal_tareas').css('z-index','1039');
        $('#modal_historial').css('z-index','1071');
        
      },
      cancelarhistorial(){
        $('#modal_tareas').css('z-index','1071');
        $('#modal_historial').css('z-index','1039');
        this.historialcargando = false;
      },
      cancelartarea(){
        this.tarea.tarea = '';
        this.tarea.id = '';   
        this.tarea.vendedor_id = null;
        this.tarea.director_id = null;
        this.tarea.status = 'Pendiente';
        this.cargando = false;
        this.editando = false;
        this.historialcargando = false;
        var rol = {!! json_encode(auth()->user()->roles[0]->name) !!}; 
        if(rol == 'Diseñadores'){        
          $('#directores_select').css('display','block');
          $('#directores_title').css('display','block');
        }
        else{
          $('#directores_select').css('display','none');
          $('#directores_title').css('display','none');
        }
        
      },
     guardartarea(){
        var formData = objectToFormData(this.tarea, {indices: true});
        this.cargando = true;
        if(this.tarea.id == ''){
          axios.post('/tareas', formData, {
              headers: {'Content-Type': 'multipart/form-data'}
          })
          .then(({data}) => {
            this.tarea.tarea = '';
            this.tarea.id = '';
            this.tareas.push(data.tarea);    
              swal({
                  title: "Exito",
                  text: "La tarea ha sido guardada",
                  type: "success"
              });
              this.cargando = false;
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
          
        }else{
          //console.log(this.tarea);
          axios.post('/tareasactualizar', formData, {
              headers: {'Content-Type': 'multipart/form-data'}
          })
          .then(({data}) => {
              swal({
                  title: "Exito",
                  text: "La tarea ha sido actualizada",
                  type: "success"
              });
              this.tarea.tarea = '';
              this.tarea.id = '';   
              this.tarea.vendedor_id = '';
              this.tarea.director_id = '';
              this.tarea.status = 'Pendiente';
              this.cargando = false;
              this.editando = false;
              this.tareas = data.tareas;
              this.modalTareas = false;
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
       
        
      },
      cargar(){
        this.tarea.vendedor_id  = this.usuarioCargado;
        axios.post('/prospectos/listadoprospectos', {id: this.usuarioCargado , anio:this.anio})
        .then(({data}) => {
          //$("#oculto").append($("#fecha_ini_control"));
          //$("#oculto").append($("#fecha_fin_control"));
          this.tabla.destroy();
          this.prospectos = data.prospectos;
          this.tareas = data.tareas;
          document.getElementById('totalsum').innerHTML= '$'+data.total;
          //this.cotizaciones = data.cotizaciones;
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
      /*
      clickver(prospecto_id){
        var rol = {!! json_encode(auth()->user()->roles[0]->name) !!}; 
      
        if( rol == 'Administrador' ||  rol == 'Dirección'){
          window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+this.usuarioCargado+'/anio/'+this.anio;
        }
        else{
          var vend_id = {!! json_encode($disenador_id) !!};
          window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+vend_id +'/anio/'+this.anio;
        }
        
       
      },
      clickeditar(prospecto_id){
        var rol = {!! json_encode(auth()->user()->roles[0]->name) !!}; 
      
        if( rol == 'Administrador' ||  rol == 'Dirección'){
          window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+this.usuarioCargado+'/anio/'+this.anio+'/editar';
        }
        else{
          var vend_id = {!! json_encode($disenador_id) !!};
          window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+vend_id +'/anio/'+this.anio+'/editar';
        }
        
       
      },
      */
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
