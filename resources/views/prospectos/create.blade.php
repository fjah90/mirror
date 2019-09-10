@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Proyecto | @parent
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
            <h3 class="panel-title">Nuevo Proyecto</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Cliente</label>
                    <select class="form-control" name="cliente_id" v-model='prospecto.cliente_id' required>
                      @foreach($clientes as $cliente)
                        <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="control-label">Nombre de Proyecto</label>
                    <input name="nombre" class="form-control" v-model="prospecto.nombre" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Descripción del Proyecto CRM</label>
                    <textarea name="descripcion" rows="3" cols="80" class="form-control" v-model="prospecto.descripcion" required></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <h4>Actividad</h4>
                  <hr />
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="ultima_actividad.fecha" class="control-label">Fecha</label>
                    <br />
                    <dropdown>
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" name="fecha"
                          v-model="ultima_actividad.fecha" placeholder="DD/MM/YYYY" readonly
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser" v-model="ultima_actividad.fecha"/>
                        </li>
                      </template>
                    </dropdown>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" name="tipo" v-model='ultima_actividad.tipo_id' >
                      @foreach($tipos as $tipo)
                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                      @endforeach
                        <option value="0">Otro</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4" v-if="ultima_actividad.tipo_id==0">
                  <div class="form-group">
                    <label class="control-label">Especifique</label>
                    <input class="form-control" type="text" name="tipo" v-model="ultima_actividad.tipo"/>
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
                    <li style="margin-top:5px;" v-for="(ofrecido, index) in ultima_actividad.ofrecidos">
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
                  <textarea name="descripcion" rows="5" cols="80" class="form-control" v-model="ultima_actividad.descripcion"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <h4>Próxima Actividad</h4>
                  <hr />
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="proxima_actividad.fecha" class="control-label">Fecha</label>
                    <br />
                    <dropdown>
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" name="fecha"
                          v-model="proxima_actividad.fecha" placeholder="DD/MM/YYYY" readonly
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser" v-model="proxima_actividad.fecha"/>
                        </li>
                      </template>
                    </dropdown>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" name="tipo" v-model='proxima_actividad.tipo_id'>
                      @foreach($tipos as $tipo)
                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                      @endforeach
                        <option value="0">Otro</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4" v-if="proxima_actividad.tipo_id==0">
                  <div class="form-group">
                    <label class="control-label">Especifique</label>
                    <input class="form-control" type="text" name="tipo" v-model="proxima_actividad.tipo"/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Prospecto
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
              <th>Tipo</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="prod in productos">
              <td>@{{prod.id}}</td>
              <td>@{{prod.nombre}}</td>
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

<script>
const app = new Vue({
    el: '#content',
    data: {
      locale: localeES,
      prospecto: {
        cliente_id: '',
        nombre: '',
        descripcion: '',
      },
      ultima_actividad: {
        fecha: '',
        tipo_id: 1,
        tipo: '',
        ofrecidos: [],
        descripcion: ''
      },
      proxima_actividad: {
        fecha: '',
        tipo_id: 1,
        tipo: '',
      },
      productos: {!! json_encode($productos) !!},
      ofrecido: {nombre:''},
      openCatalogo: false,
      cargando: false,
    },
    mounted(){
      $("#tablaProductos").DataTable({dom: 'ft'});
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
        this.ultima_actividad.ofrecidos.push(this.ofrecido);
        this.ofrecido = {nombre:''};
      },
      removerProducto(index, ofrecido){
        this.ultima_actividad.ofrecidos.splice(index, 1);
      },
      guardar(){
        var prospecto = $.extend(true, {}, this.prospecto);
        if(this.ultima_actividad.fecha!="" ||
           this.ultima_actividad.descripcion!="" ||
           this.ultima_actividad.ofrecidos.length>0){
          prospecto.ultima_actividad = this.ultima_actividad;

          if(this.proxima_actividad.fecha!="")
            prospecto.proxima_actividad = this.proxima_actividad;
        }
        else if(this.proxima_actividad.fecha!=""){
          prospecto.ultima_actividad = this.proxima_actividad;
        }

        this.cargando = true;
        axios.post('/prospectos', prospecto)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Proyecto Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/prospectos";
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
