@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cotizar Prospecto | @parent
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
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Cotizar Prospecto</h3>
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
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Descripción</label>
                  <span class="form-control" style="min-height:68px;">{{$prospecto->descripcion}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <h4>Cotizaciones Realizadas</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Numero</th>
                        <th>Fecha</th>
                        <th>Productos Ofrecidos</th>
                        <th>Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="cotizacion in prospecto.cotizaciones">
                        <td>@{{cotizacion.id}}</td>
                        <td>@{{cotizacion.fecha_formated}}</td>
                        <td>
                          <template v-for="(entrada, index) in cotizacion.entradas">
                            <span>@{{index+1}}.- @{{entrada.producto.composicion}}</span><br />
                          </template>
                        </td>
                        <td>@{{cotizacion.total | formatoMoneda}}</td>
                        <td class="text-right">
                          <a class="btn btn-success" title="PDF" :href="cotizacion.archivo"
                            :download="'cotizacion '+cotizacion.id+'.pdf'">
                            <i class="far fa-file-pdf"></i>
                          </a>
                          <button class="btn btn-info" title="Enviar"
                            @click="enviar.cotizacion_id=cotizacion.id; openEnviar=true;">
                            <i class="far fa-envelope"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <form class="" @submit.prevent="agregarEntrada()">
              <div class="row">
                <div class="col-sm-12">
                  <h4>Nueva Cotización</h4>
                  <hr />
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Producto</label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Producto"
                      v-model="entrada.producto.composicion" @click="openCatalogo=true"
                      readonly required
                      />
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" @click="openCatalogo=true">
                          <i class="far fa-edit"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Cantidad</label>
                    <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control"
                      v-model="entrada.cantidad" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Precio</label>
                    <input type="number" step="0.01" min="0.01" name="precio" class="form-control"
                      v-model="entrada.precio" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Colection</label>
                    <input type="text" class="form-control" v-model="entrada.coleccion" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Desing</label>
                    <input type="text" class="form-control" v-model="entrada.diseno" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Color</label>
                    <input type="text" class="form-control" v-model="entrada.color" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Observación</label>
                    <textarea name="name" rows="1" cols="80" v-model="entrada.observacion" class="form-control">
                    </textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <div class="form-group">
                    <button type="submit" class="btn btn-info">
                      <i class="fas fa-plus"></i>
                      Agregar Producto
                    </button>
                  </div>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(entrada, index) in cotizacion.entradas">
                        <td>@{{entrada.producto.composicion}}</td>
                        <td>@{{entrada.cantidad}}</td>
                        <td>@{{entrada.precio | formatoMoneda}}</td>
                        <td>@{{entrada.importe | formatoMoneda}}</td>
                        <td class="text-right">
                          <button class="btn btn-success" title="Editar"
                            @click="editarEntrada(entrada, index)">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-danger" title="Remover"
                            @click="removerEntrada(entrada, index)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>Subtotal</strong></td>
                        <td>@{{cotizacion.subtotal | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>IVA</strong></td>
                        <td>@{{cotizacion.iva | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>Total</strong></td>
                        <td>@{{cotizacion.total | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Observaciónes</label>
                  <textarea name="name" rows="3" cols="80" v-model="cotizacion.observaciones" class="form-control">
                  </textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <div class="form-group">
                  <button type="button" class="btn btn-primary"
                  @click="guardar()" :disabled="cargando">
                  <i class="fas fa-save"></i>
                  Guardar Cotización
                </button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Catalogo Productos Modal -->
    <modal v-model="openCatalogo" title="Productos" :footer="false">
      <div class="table-responsive">
        <table class="table table-bordred">
          <thead>
            <tr>
              <th>ID</th>
              <th>Proveedor</th>
              <th>Material</th>
              <th>Composicón</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(prod, index) in productos">
              <td>@{{prod.id}}</td>
              <td>@{{prod.proveedor.empresa}}</td>
              <td>@{{prod.material.nombre}}</td>
              <td>@{{prod.composicion}}</td>
              <td class="text-right">
                <button class="btn btn-primary" title="Seleccionar"
                @click="seleccionarProduco(prod, index)">
                <i class="fas fa-check"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    </modal>
    <!-- /.Catalogo Productos Modal -->

    <!-- Enviar Modal -->
    <modal v-model="openEnviar" :title="'Enviar Cotizacion '+enviar.cotizacion_id" :footer="false">
      <form class="" @submit.prevent="enviarCotizacion()">
        <div class="form-group">
          <label class="control-label">Email</label>
          <input type="text" class="form-control" name="email" v-model="enviar.email" required />
        </div>
        <div class="form-group">
          <label class="control-label">Mensaje</label>
          <textarea name="mensaje" class="form-control" v-model="enviar.mensaje" rows="6" cols="80" required>
          </textarea>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>
    </modal>
  <!-- /.Enviar Modal -->
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
    cotizacion: {
      prospecto_id: {{$prospecto->id}},
      entradas: [],
      subtotal: 0,
      iva: 0,
      total: 0,
      observaciones: ""
    },
    entrada: {
      producto: {},
      coleccion: "",
      diseno: "",
      color: "",
      cantidad: 0,
      precio: 0,
      importe: 0,
      observacion: ""
    },
    enviar: {
      cotizacion_id: 0,
      email: "{{$prospecto->cliente->email}}",
      mensaje: "Buen día.\n\nLe envió cotización para su consideración.\n\nCarla Aguilar.\nAtención del Cliente\nIntercorp Contract Resources"
    },
    openCatalogo: false,
    openEnviar: false,
    cargando: false
  },
  filters:{
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$", 2);
    },
  },
  methods: {
    seleccionarProduco(prod){
      this.entrada.producto = prod;
      this.openCatalogo = false;
    },
    agregarEntrada(){
      if(this.entrada.producto.id==undefined){
        swal({
          title: "Error",
          text: "Debe seleccionar un producto",
          type: "error"
        });
        return false;
      }

      this.entrada.importe = this.entrada.cantidad * this.entrada.precio;
      this.cotizacion.subtotal+= this.entrada.importe;
      this.cotizacion.iva = this.cotizacion.subtotal * 0.16;
      this.cotizacion.total = this.cotizacion.subtotal * 1.16;
      this.cotizacion.entradas.push(this.entrada);
      this.entrada = {
        producto: {},
        cantidad: 0,
        precio: 0,
        importe: 0,
        observacion: ""
      };
    },
    editarEntrada(entrada, index){
      this.cotizacion.subtotal-= entrada.importe;
      this.cotizacion.iva = this.cotizacion.subtotal * 0.16;
      this.cotizacion.total = this.cotizacion.subtotal * 1.16;
      this.cotizacion.entradas.splice(index, 1);
      this.entrada = entrada;
    },
    removerEntrada(entrada, index){
      this.cotizacion.subtotal-= entrada.importe;
      this.cotizacion.iva = this.cotizacion.subtotal * 0.16;
      this.cotizacion.total = this.cotizacion.subtotal * 1.16;
      this.cotizacion.entradas.splice(index, 1);
    },
    enviarCotizacion(){
      this.cargando = true;
      axios.post('/prospectos/{{$prospecto->id}}/enviarCotizacion', this.enviar)
      .then(({data}) => {
        this.enviar = {
          cotizacion_id: 0,
          email: "{{$prospecto->cliente->email}}",
          mensaje: "Buen día.\n\nLe envió cotización para su consideración.\n\nCarla Aguilar.\nAtención del Cliente\nIntercorp Contract Resources"
        };
        this.openEnviar = false;
        this.cargando = false;
        swal({
          title: "Cotizacion Enviada",
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
    },//fin enviarCotizacion
    guardar(){
      this.cargando = true;
      axios.post('/prospectos/{{$prospecto->id}}/cotizacion', this.cotizacion)
      .then(({data}) => {
        this.prospecto.cotizaciones.push(data.cotizacion);
        this.cotizacion = {
          prospecto_id: {{$prospecto->id}},
          entradas: [],
          subtotal: 0,
          iva: 0,
          total: 0,
          observaciones: ""
        };
        this.cargando = false;
        swal({
          title: "Cotizacion Guardada",
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
    },//fin guardar
  }
});
</script>
@stop
