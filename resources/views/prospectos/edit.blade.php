@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Proyecto | @parent
@stop

@section('header_styles')
<style>
.btn-xxs {
  padding: 0 4px;
  font-size: 10px;
  cursor: pointer;
}
</style>
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header" style="background-color:#12160F; color:#B68911;">
    <h1 style="font-weight: bolder;">Proyectos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Editar Proyecto</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Cliente</label>
                  <span class="form-control">{{$prospecto->cliente->nombre}}</span>
                </div>
              </div>
            </div>
            <form class="" @submit.prevent="actualizar()">
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="control-label">Nombre de Proyecto</label>
                    <input type="text" class="form-control" name="nombre"
                      v-model="prospecto.nombre" required
                    />
                  </div>
                </div>
                <div class="col-md-4">
                      <label class="control-label">Diseñador</label>
                      <select name="vendedor_id" v-model="prospecto.vendedor_id"
                              class="form-control" required>
                          @foreach($vendedores as $vendedor)
                              <option value="{{$vendedor->id}}" >{{$vendedor->nombre}}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Descripción del Proyecto CRM</label>
                    <textarea name="descripcion" rows="3" cols="80" class="form-control"
                      v-model="prospecto.descripcion" required>
                    </textarea>
                  </div>
                </div>
              </div>
              @if($prospecto->es_prospecto == 'si')
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                        <label for="prospecto.fecha_cierre" class="control-label">Fecha aproximada de cierre</label>
                        <br />
                        <dropdown>
                          <div class="input-group">
                            <div class="input-group-btn">
                              <btn class="dropdown-toggle" style="background-color:#fff;">
                                <i class="fas fa-calendar"></i>
                              </btn>
                            </div>
                            <input class="form-control" type="text" name="fecha"
                              v-model="prospecto.fecha_cierre" placeholder="dd/MM/YYYY"
                            />
                          </div>
                          <template slot="dropdown">
                            <li>
                              <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                              format="dd/MM/yyyy" :date-parser="dateParser" v-model="prospecto.fecha_cierre"/>
                            </li>
                          </template>
                        </dropdown>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Proyección de venta en USD</label>
                        <input type="number" step="0.1" name="proyeccion_venta" class="form-control" v-model="prospecto.proyeccion_venta" required />
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Factibilidad</label>
                         <select class="form-control" name="factibilidad" v-model="prospecto.factibilidad" required>
                          <option value="Alta">Alta</option>
                          <option value="Media">Media</option>
                          <option value="Baja">Baja</option>
                        </select>
                      </div>
                    </div>
                     <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Estatus</label>
                          <select class="form-control" name="estatus" v-model="prospecto.estatus" required>
                          <option value="Activo">Activo</option>
                          <option value="Cancelado">Cancelado</option>
                        </select>
                      </div>
                    </div>
              </div>
              @endif
              <div class="row">
                <div class="col-sm-12 text-right">
                  <a href="{{route('prospectos.index')}}" class="btn btn-default" style="color:#000; background-color:#B3B3B3;">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-DARK" :disabled="cargando" style="background-color:#12160F; color:#B68911;">
                    <i class="fas fa-save"></i>
                    Actualizar Datos
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Actividades Realizadas</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Productos Ofrecidos</th>
                        <th>Descripción</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="actividad in prospecto.actividades" v-if="actividad.realizada">
                        <td>@{{actividad.fecha_formated}}</td>
                        <td>@{{actividad.tipo.nombre}}</td>
                        <td>
                          <template v-for="(ofrecido, index) in actividad.productos_ofrecidos">
                            <span>@{{index+1}}.- @{{ofrecido.nombre}}</span><br />
                          </template>
                        </td>
                        <td v-if="actividad.tipo.id==4"><!-- Cotización enviada -->
                          <a class="btn btn-xs btn-warning" title="PDF" :href="actividad.descripcion" target="_blank">
                            <i class="far fa-file-pdf"></i>
                          </a>
                        </td>
                        <td v-else>@{{actividad.descripcion}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <form class="" @submit.prevent="guardar()">
              <template v-if="prospecto.proxima_actividad">
                <div class="row">
                  <div class="col-sm-12">
                    <h4>Próxima Actividad</h4>
                    <hr />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">Fecha</label>
                      <span class="form-control">@{{prospecto.proxima_actividad.fecha_formated}}</span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">Tipo</label>
                      <span class="form-control">@{{prospecto.proxima_actividad.tipo.nombre}}</span>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-sm-10">
                    <label class="control-label">Productos Ofrecidos</label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Producto"
                        v-model="ofrecido.nombre" @click="openCatalogo=true"
                        readonly
                      />
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" @click="openCatalogo=true">
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                
                  <div class="col-sm-2" style="padding-top: 25px;">
                    <button type="button" class="btn btn-primary" @click="agregarProducto()" style="color:#B68911; background-color:#12160F;">
                      Agregar
                    </button>
                  </div>
                  <!--
                  <div class="col-sm-2" style="padding-top: 25px;">
                    <button type="button" class="btn btn-primary" @click="modalProducto=true">
                      Registrar producto
                    </button>
                  </div>
                   -->
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <ul style="list-style-type:none; padding:0;">
                      <li style="margin-top:5px;" v-for="(ofrecido, index) in prospecto.proxima_actividad.productos_ofrecidos">
                        <button type="button" class="btn btn-xxs btn-danger" @click="removerProducto(index)">
                          <i class="fas fa-times"></i>
                        </button>
                        @{{ofrecido.nombre}}
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="control-label">Descripción Actividad</label>
                    <textarea name="descripcion" rows="5" cols="80" class="form-control" v-model="prospecto.proxima_actividad.descripcion" required></textarea>
                  </div>
                </div>
              </template>
              <div class="row">
                <div class="col-sm-12">
                  <h4>Nueva Próxima Actividad</h4>
                  <hr />
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="prospecto.nueva_proxima_actividad.fecha" class="control-label">Fecha</label>
                    <br />
                    <dropdown>
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#000; color:#FFF;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" name="fecha"
                          v-model="prospecto.nueva_proxima_actividad.fecha" placeholder="DD/MM/YYYY"
                          readonly
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser" v-model="prospecto.nueva_proxima_actividad.fecha"/>
                        </li>
                      </template>
                    </dropdown>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                     <select class="form-control" name="tipo" v-model="prospecto.nueva_proxima_actividad.tipo_id" required>
                        <option value="1">Llamada</option>
                        <option value="12">Videollamada</option>
                        <option value="2">Cita Presencial</option>
                        <option value="13">Cita Showroom</option>
                        <option value="3">Email</option>
                        <option value="14">Propuesta</option>
                        <option value="5">Enviar Cotizacion</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-4" v-if="prospecto.nueva_proxima_actividad.tipo_id==0">
                  <div class="form-group">
                    <label class="control-label">Especifique</label>
                    <input class="form-control" type="text" name="tipo" v-model="prospecto.nueva_proxima_actividad.tipo" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{route('prospectos.index')}}" class="btn btn-default" style="color:#000; background-color:#B3B3B3;">
                    Regresar
                  </a>  
                  <button type="submit" class="btn btn-DARK" :disabled="cargando" style="background-color:#12160F; color:#B68911;">
                    <i class="fas fa-save"></i>
                    Guardar Actividades
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Catalogo Productos Modal -->
    <modal v-model="openCatalogo" title="Productos" :footer="false">
      <div class="table-responsive">
        <table id="tablaProductos" class="table table-bordred">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Marca</th>
              <th>Tipo</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="prod in productos">
              <td>@{{prod.id}}</td>
              <td>@{{prod.nombre}}</td>
              <td>@{{prod.marca}}</td>
              <td>@{{prod.categoria.nombre}}</td>
              <td class="text-right">
                <button class="btn btn-sm btn-primary" title="Seleccionar"
                @click="ofrecido=prod; openCatalogo=false;">
                  <i class="fas fa-check"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </modal>
    <!-- /.Catalogo Productos Modal -->

    <!-- Nuevo Producto Modal-->
    <modal v-model="modalProducto" title="Registrar Producto" :footer="false">
    <iframe id="theFrame" src="{{url("/")}}/productos/crear?layout=iframe" style="width:100%; height:700px;"
                    frameborder="0">
            </iframe>
    </modal>
    <!-- /.Nuevo Producto Modal -->

  </section>
  <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script type="text/javascript">
const app = new Vue({
  el: '#content',
  data: {
    locale: localeES,
    prospecto: {!! json_encode($prospecto) !!},
    productos: {!! json_encode($productos) !!},
    tipos: {!! json_encode($tipos) !!},
    ofrecido: {nombre:''},
    openCatalogo: false,
    cargando: false,
    modalProducto:false,
  },
  mounted(){
    $("#tablaProductos").DataTable({dom: 'ftp'});

    //escuchar Iframe
      window.addEventListener('message',function(e) {
          if(e.data.tipo=="cliente"){
            vue.clientes.push({id:e.data.object.id, nombre:e.data.object.nombre});
            vue.prospecto.cliente_id=e.data.object.id;
            vue.modalCliente=false;
            vue.clienteSelect.select2('destroy');
            vue.clienteSelect.select2({ width: '100%'});
          }
          if(e.data.tipo=="producto"){
            vue.tablaProductos.destroy();
            vue.productos.push(e.data.object);
            vue.ofrecido=e.data.object;    
            vue.modalProducto=false;
            Vue.nextTick(function() {vue.tablaProductos=$("#tablaProductos").DataTable({"order": [[ 0, "asc" ]]})});
            
          }
      },false);
  },
  methods: {
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$ ", 2);
    },
    formatoPorcentaje(numero){
      return accounting.formatMoney(numero, { symbol: "%",  format: "%v %s" });
    },
    dateParser(value){
      return moment(value, 'DD/MM/YYYY').toDate().getTime();
    },
    agregarProducto(){
      if(this.ofrecido.id==undefined) return false;
      this.prospecto.proxima_actividad.productos_ofrecidos.push(this.ofrecido);
      this.ofrecido = {nombre:''};
    },
    removerProducto(index){
      this.prospecto.proxima_actividad.productos_ofrecidos.splice(index, 1);
    },
    actualizar(){
      this.cargando = true;
      axios.put('/prospectos/{{$prospecto->id}}', {
        nombre: this.prospecto.nombre,
        descripcion: this.prospecto.descripcion,
        vendedor_id: this.prospecto.vendedor_id,
        fecha_cierre: this.prospecto.fecha_cierre,
        proyeccion_venta: this.prospecto.proyeccion_venta,
        factibilidad: this.prospecto.factibilidad,////////////////
        estatus: this.prospecto.estatus
      })
      .then(({data}) => {
        this.cargando = false;
        swal({
          title: "Datos Actualizados",
          text: "",
          type: "success"
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
    },//fin actualizar
    guardar(){
      this.cargando = true;
      axios.post('/prospectos/{{$prospecto->id}}/guardarActividades', {
        proxima: this.prospecto.proxima_actividad,
        nueva: this.prospecto.nueva_proxima_actividad,
      })
      .then(({data}) => {
        this.tipos = data.tipos;
        if(data.proxima) this.prospecto.actividades.push(data.proxima);
        this.prospecto.proxima_actividad = data.nueva;
        this.prospecto.nueva_proxima_actividad = {
          fecha: '',
          tipo_id: 1,
          tipo: ''
        };
        this.cargando = false;
        swal({
          title: "Actividades Guardadas",
          text: "",
          type: "success"
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
    },//fin cargarPresupuesto
  }
});
</script>
@stop
