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
  <section class="content-header">
    <h1>Proyectos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
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
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Nombre de Proyecto</label>
                    <input type="text" class="form-control" name="nombre"
                      v-model="prospecto.nombre" required
                    />
                  </div>
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
              <div class="row">
                <div class="col-sm-12 text-right">
                  <a href="{{route('prospectos.index')}}" class="btn btn-info">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-success" :disabled="cargando">
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
          <div class="panel-heading">
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
                          <a class="btn btn-warning" title="PDF" :href="actividad.descripcion" target="_blank">
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
                          <i class="far fa-edit"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-2" style="padding-top: 25px;">
                    <button type="button" class="btn btn-primary" @click="agregarProducto()">
                      Agregar
                    </button>
                  </div>
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
                          <btn class="dropdown-toggle" style="background-color:#fff;">
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
                    <select class="form-control" name="tipo" v-model='prospecto.nueva_proxima_actividad.tipo_id' required>
                      <option v-for="tipo in tipos" :value="tipo.id">@{{tipo.nombre}}</option>
                      <option value="0">Otro</option>
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
                  <button type="submit" class="btn btn-success" :disabled="cargando">
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
                <button class="btn btn-primary" title="Seleccionar"
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
  },
  mounted(){
    $("#tablaProductos").DataTable({dom: 'ftp'});
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
        descripcion: this.prospecto.descripcion
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
