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
/* esconder los pasos: */
.tab {
  display: none;
}

/* hacer los indicadores para los pasos */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

/* Marcar el paso actual: */
.step.active {
  opacity: 1;
}

/* Marcar los pasos validos */
.step.finish {
  background-color: #4CAF50;
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
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Nuevo Prospecto</h3>
          </div>
          {{-- <div>
            <h2>Wizard generated from HTML</h2>
			      <ul id="steps" class="step"></ul>
          </div> --}}
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()" id="formulario">
              <div class="tab">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">

                      <label class="control-label">Cliente <strong style="color: grey"> *</strong></label>
                      <select class="form-control" name="cliente_id" v-model='prospecto.cliente_id' id="clienteSelect" required tabindex="-1">
                        <option v-for="(cliente, index) in clientes" v-bind:value="cliente.id" >
                          @{{ cliente.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Registrar cliente</label>
                    <button type="button" id="show-modal" @click="modalCliente = true" class="btn btn-effect-ripple form-control" style="background-color:black; color:#B68911;">Nuevo Cliente</button>
                    <!--use the modal component, pass in the prop -->
                    <modal v-if="modalCliente" @close="modalCliente = false">
                  </div>
                </div>
              
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label class="control-label">Nombre de Proyecto <strong style="color: grey"> *</strong></label>
                      <input name="nombre" class="form-control" v-model="prospecto.nombre" required />
                    </div>
                  </div>
                  <div class="col-md-4">
                      <label class="control-label">Diseñador <strong style="color: grey"> *</strong></label>
                      <select name="vendedor_id" v-model="prospecto.vendedor_id"
                              class="form-control" required>
                          @foreach($vendedores as $vendedor)
                              <option value="{{$vendedor->id}}">{{$vendedor->nombre}}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label">Descripción del Proyecto CRM <strong style="color: grey"> *</strong></label>
                      <textarea name="descripcion" rows="3" cols="80" class="form-control" v-model="prospecto.descripcion" required></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                        <label for="prospecto.fecha_cierre" class="control-label">Fecha aproximada de cierre <strong style="color: grey"> *</strong></label>
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
                        <label class="control-label">Proyección de venta <br>en USD <strong style="color: grey"> *</strong></label>
                        <input type="text"  name="proyeccion_venta" class="form-control" v-model="prospecto.proyeccion_venta" required />
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Factibilidad <strong style="color: grey"> *</strong></label>
                        <select class="form-control" name="factibilidad" v-model="prospecto.factibilidad" required>
                          <option value="Alta">Alta</option>
                          <option value="Media">Media</option>
                          <option value="Baja">Baja</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Estatus <strong style="color: grey"> *</strong></label>
                        <select class="form-control" name="estatus" v-model="prospecto.estatus" required>
                          <option value="Activo">Activo</option>
                          <option value="Cancelado">Cancelado</option>
                        </select>
                      </div>
                    </div>
                </div>
              </div>
              <div class="tab">
                <div class="row">
                  <div class="col-sm-12">
                    <h4>Actividad</h4>
                    <hr />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="ultima_actividad.fecha" class="control-label">Fecha<strong style="color: grey"> *</strong></label>
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
                      <label class="control-label">Tipo <strong style="color: grey"> *</strong></label>
                        <select class="form-control" name="tipo" v-model="ultima_actividad.tipo_id" required>
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
                  <div class="col-md-4" v-if="ultima_actividad.tipo_id==0">
                    <div class="form-group">
                      <label class="control-label">Especifique</label>
                      <input class="form-control" type="text" name="tipo" v-model="ultima_actividad.tipo"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-8">
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
                  <div class="col-sm-2" style="padding-top: 25px;">
                    <button type="button" class="btn btn-primary" @click="modalProducto=true">
                      Registrar producto
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
              </div>
              <div class="tab">
                <div class="row">
                  <div class="col-sm-12">
                    <h4>Próxima Actividad</h4>
                    <hr />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="proxima_actividad.fecha" class="control-label">Fecha <strong style="color: grey"> *</strong></label>
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
                      <label class="control-label">Tipo <strong style="color: grey"> *</strong></label>
                       <select class="form-control" name="tipo" v-model="proxima_actividad.tipo_id" required>
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
                  <div class="col-md-4" v-if="proxima_actividad.tipo_id==0">
                    <div class="form-group">
                      <label class="control-label">Especifique</label>
                      <input class="form-control" type="text" name="tipo" v-model="proxima_actividad.tipo"/>
                    </div>
                  </div>
                </div>
                 <div class="row">
                  <div class="col-sm-12">
                    <label class="control-label">Descripción Actividad</label>
                    <textarea name="descripcion" rows="5" cols="80" class="form-control" v-model="proxima_actividad.descripcion"></textarea>
                  </div>
                </div>
                <div class="row" style="margin-top:25px;">
                  <div class="col-md-12 text-right">
                    <a href="{{url('/prospectos/prospectos/')}}" class="btn btn-default">
                      Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="cargando">
                      <i class="fas fa-save"></i>
                      Guardar Prospecto
                    </button>
                  </div>
                </div>
              </div>

              <!--controles wizard-->
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" class="btn btn-white rounded" id="prevBtn" @click="siguienteTab(-1)">< Anterior</button>
                  <button type="button" class="btn btn-white rounded" id="nextBtn" @click="siguienteTab(1)">Siguiente ></button>
                </div>
              </div>

              <!--indicadore de pasos en proceso-->
              <div style="text-align:center;margin-top:40px;">
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
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
    
    <!-- Nuevo Cliente Modal-->
    <modal v-model="modalCliente" title="Registrar Cliente" :footer="false">
      <iframe id="theFrame" src="../clientes/crearNacional?layout=iframe" style="width:100%; height:700px;" frameborder="0">
      </iframe>
    </modal>
    <!-- /.Nuevo Cliente Modal -->

    <!-- Nuevo Producto Modal-->
    <modal v-model="modalProducto" title="Registrar Producto" :footer="false">
      <iframe id="theFrame" src="../productos/crear?layout=iframe" style="width:100%; height:700px;" frameborder="0">
      </iframe>
    </modal>
    <!-- /.Nuevo Producto Modal -->
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
      tabActual: 0,
      modalCliente:false,
      modalProducto:false,
      tablaProductos:{},
      clienteSelect:null,
      locale: localeES,
      prospecto: {
        cliente_id: '',
        nombre: '',
        descripcion: '',
        es_prospecto: 'si',
        vendedor_id:'',
        fecha_cierre:'',
        proyeccion_venta:'',
        factibilidad:'',
        estatus:''
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
      clientes: {!! json_encode($clientes) !!},
      ofrecido: {nombre:''},
      openCatalogo: false,
      cargando: false,
    },
    mounted(){
      var vue=this;
      this.tablaProductos= $("#tablaProductos").DataTable({"order": [[ 0, "asc" ]]});
      this.clienteSelect= $('#clienteSelect').select2({ width: '100%'}).on('select2:select', function () {       
       	var value = $("#clienteSelect").select2('data');
        vue.prospecto.cliente_id = value[0].id
      });
      this.mostrarTab(this.tabActual);
      
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
            console.log(e);
            vue.tablaProductos.destroy();
            vue.productos.push(e.data.object);
            vue.ofrecido=e.data.object;    
            vue.modalProducto=false;
            Vue.nextTick(function() {vue.tablaProductos=$("#tablaProductos").DataTable({"order": [[ 0, "asc" ]]})});
            
          }
      },false);
    },
    methods: {
      mostrarTab(tab){
        var x = $(".tab");
        x.eq(tab).show();
        if (tab == 0) {
          $("#prevBtn").hide();
        } else {
          $("#prevBtn").css('display', 'inline');
        }
        if (tab == (x.length - 1)) {
          $("#nextBtn").hide();
        } else {
          $("#nextBtn").show();
        }
        this.arreglarIndicadores();
      },
      siguienteTab(n) {
        var x = $(".tab");
        if (n == 1 && !this.validarForm()) return false;
        x.eq(this.tabActual).hide();
        this.tabActual=this.tabActual+n;
        this.mostrarTab(this.tabActual);
      },
      validarForm() {
        var x, y, i, valido = true;
        x = $(".tab");
        y = x.eq(this.tabActual).find('.form-control');
        console.log(y)
        for (i = 0; i < y.length; i++) {
          if (! y.get(i).checkValidity()) {
            valido = false;
            y.get(i).reportValidity();
            break;
          }
        }
        if (valido) {
          $(".step").eq(this.tabActual).addClass("finish");
        }
        return valido; 
      },
      arreglarIndicadores() {
        var i;
        var x = $(".step");
        for (i = 0; i < x.length; i++) {
          x.eq(i).removeClass('active');
        }
       x.eq(this.tabActual).addClass("active");
      },
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
            if(this.prospecto.es_prospecto == 'si')
            {
                window.location = "/prospectos/prospectos";
            }
            else{
                window.location = "/prospectos/"+data.prospecto.id+"/cotizar";
            }
            
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
