@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Prospecto | @parent
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
    <h1>Prospectos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nuevo Prospecto</h3>
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
                    <label class="control-label">Nombre</label>
                    <input name="nombre" class="form-control" v-model="prospecto.nombre" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Descripción</label>
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
                    <label for="prospecto.ultima_actividad.fecha" class="control-label">Fecha</label>
                    <br />
                    <dropdown>
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" name="fecha"
                          v-model="prospecto.ultima_actividad.fecha" placeholder="DD/MM/YYYY" readonly
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser" v-model="prospecto.ultima_actividad.fecha"/>
                        </li>
                      </template>
                    </dropdown>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" name="tipo" v-model='prospecto.ultima_actividad.tipo_id' required>
                      @foreach($tipos as $tipo)
                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                      @endforeach
                        <option value="0">Otro</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4" v-if="prospecto.ultima_actividad.tipo_id==0">
                  <div class="form-group">
                    <label class="control-label">Especifique</label>
                    <input class="form-control" type="text" name="tipo" v-model="prospecto.ultima_actividad.tipo" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="row">
                    <div class="col-md-10">
                      <label class="control-label">Productos Ofrecidos</label>
                      <select class="form-control" name="producto" v-model='ofrecido'>
                        <option v-for="producto in productos" :value="producto.id">@{{producto.id}}: @{{producto.nombre}}</option>
                      </select>
                    </div>
                    <div class="col-md-2" style="padding-top: 25px;">
                      <button type="button" class="btn btn-primary" @click="agregarProducto()">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <ul style="list-style-type:none; padding:0;">
                        <li style="margin-top:5px;" v-for="(ofrecido, index) in prospecto.ultima_actividad.ofrecidos">
                          <button type="button" class="btn btn-xxs btn-danger" @click="removerProducto(index, ofrecido)">
                            <i class="fas fa-times"></i>
                          </button>
                          @{{ofrecido.id}}: @{{ofrecido.nombre}}
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <label class="control-label">Descripción Actividad</label>
                  <textarea name="descripcion" rows="5" cols="80" class="form-control" v-model="prospecto.ultima_actividad.descripcion" required></textarea>
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
                    <label for="prospecto.proxima_actividad.fecha" class="control-label">Fecha</label>
                    <br />
                    <dropdown>
                      <div class="input-group">
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" name="fecha"
                          v-model="prospecto.proxima_actividad.fecha" placeholder="DD/MM/YYYY" readonly
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser" v-model="prospecto.proxima_actividad.fecha"/>
                        </li>
                      </template>
                    </dropdown>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" name="tipo" v-model='prospecto.proxima_actividad.tipo_id' required>
                      @foreach($tipos as $tipo)
                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                      @endforeach
                        <option value="0">Otro</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4" v-if="prospecto.proxima_actividad.tipo_id==0">
                  <div class="form-group">
                    <label class="control-label">Especifique</label>
                    <input class="form-control" type="text" name="tipo" v-model="prospecto.proxima_actividad.tipo" required />
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
      },
      productos: {!! json_encode($productos) !!},
      ofrecido: '',
      cargando: false,
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
        var ofrecido = this.ofrecido;
        var index = this.productos.findIndex(function(producto){
          return ofrecido == producto.id;
        });
        if(index==-1) return false;

        var producto = this.productos[index];
        this.prospecto.ultima_actividad.ofrecidos.push(producto);
        this.productos.splice(index, 1);
      },
      removerProducto(index, ofrecido){
        this.prospecto.ultima_actividad.ofrecidos.splice(index, 1);
        this.productos.push(ofrecido);
      },
      guardar(){
        this.cargando = true;
        axios.post('/prospectos', this.prospecto)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Prospecto Guardado",
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
