@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cotizar Proyecto | @parent
@stop

@section('header_styles')
<style>
  table td:first-child span.fa-grip-vertical:hover {
    cursor: move;
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
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Cotizar Proyecto</h3>
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
                  <label class="control-label">Nombre de Proyecto</label>
                  <span class="form-control text-uppercase">{{$prospecto->nombre}}</span>
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
                      <tr v-for="(cotizacion, index) in prospecto.cotizaciones">
                        <td>@{{cotizacion.numero}}</td>
                        <td>@{{cotizacion.fecha_formated}}</td>
                        <td>
                          <template v-for="(entrada, index) in cotizacion.entradas">
                            <span>@{{index+1}}.- @{{entrada.producto.nombre}}</span><br />
                          </template>
                        </td>
                        <td>@{{cotizacion.total | formatoMoneda}}</td>
                        <td class="text-right">
                          <button class="btn btn-default" title="Notas"
                            @click="notas.cotizacion_id=cotizacion.id;
                              notas.mensaje=cotizacion.notas2;
                              openNotas=true;"
                            >
                            <i class="far fa-sticky-note"></i>
                          </button>
                          <a class="btn btn-warning" title="PDF" :href="cotizacion.archivo"
                            :download="'C '+cotizacion.numero+' Intercorp '+prospecto.nombre+'.pdf'">
                            <i class="far fa-file-pdf"></i>
                          </a>
                          <button class="btn btn-info" title="Enviar"
                            @click="enviar.cotizacion_id=cotizacion.id; openEnviar=true;">
                            <i class="far fa-envelope"></i>
                          </button>
                          <button v-if="cotizacion.aceptada" class="btn text-primary" title="Aceptada">
                            <i class="fas fa-user-check"></i>
                          </button>
                          <template v-else>
                            <button class="btn btn-primary" title="Aceptar"
                              @click="aceptar.cotizacion_id=cotizacion.id; openAceptar=true;">
                              <i class="fas fa-user-check"></i>
                            </button>
                            <button class="btn btn-success" title="Editar"
                              @click="editar(index, cotizacion)">
                              <i class="far fa-edit"></i>
                            </button>
                          </template>
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
                    <label class="control-label">Numero Cotización</label>
                    <input type="number" step="1" min="1" name="numero" class="form-control"
                    v-model="cotizacion.numero" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Facturar A</label>
                    <input type="text" name="facturar" class="form-control"
                    v-model="cotizacion.facturar" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Tiempo de Entrega</label>
                    <input type="text" name="entrega" class="form-control"
                      v-model="cotizacion.entrega" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Lugar de Entrega</label>
                    <input type="text" name="lugar" class="form-control"
                      v-model="cotizacion.lugar" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Condiciones De Pago</label>
                    <select class="form-control" name="condiciones" v-model='cotizacion.condicion.id'
                      @change="condicionCambiada()" required>
                      <option v-for="condicion in condiciones" :value="condicion.id">@{{condicion.nombre}}</option>
                      <option value="0">Otra</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div v-if="cotizacion.condicion.id==0" class="form-group">
                    <label class="control-label">Especifique Otra</label>
                    <input class="form-control" type="text" name="condiciones"
                      v-model="cotizacion.condicion.nombre" required
                    />
                  </div>
                  <div v-else class="form-group">
                    <label class="control-label">Actualizar Condicion</label>
                    <div class="input-group">
                      <input class="form-control" type="text" name="condiciones"
                        v-model="cotizacion.condicion.nombre" required
                      />
                      <span class="input-group-btn">
                        <button class="btn btn-success" type="button" title="Actualizar"
                          @click="actualizarCondicion()">
                          <i class="far fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" type="button" title="Borrar"
                          @click="borrarCondicion()">
                          <i class="fas fa-times"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Moneda</label>
                    <select class="form-control" name="moneda" v-model="cotizacion.moneda" required>
                      <option value="Dolares">Dolares USD</option>
                      <option value="Pesos">Pesos MXN</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">IVA</label>
                    <select class="form-control" name="iva" v-model="cotizacion.iva" required>
                      <option value="0">No</option>
                      <option value="1">Si</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Idioma</label>
                    <select class="form-control" name="idioma" v-model="cotizacion.idioma" required>
                      <option value="español">Español</option>
                      <option value="ingles">Ingles</option>
                    </select>
                  </div>
                </div>
              </div>
              <hr />
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Producto</label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Producto"
                      v-model="entrada.producto.nombre" @click="openCatalogo=true"
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
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Cantidad</label>
                    <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control"
                      v-model="entrada.cantidad" required />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Unidad Medida</label>
                    <select class="form-control" name="medida" v-model="entrada.medida" required>
                      @foreach($unidades_medida as $unidad)
                      <option value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }}</option>
                      @endforeach
                    </select>
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
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordred">
                      <thead>
                        <tr>
                          <th colspan="3">Descripciones</th>
                        </tr>
                        <tr>
                          <th>Nombre</th>
                          <th>Name</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(descripcion, index) in entrada.descripciones">
                          <td>@{{descripcion.nombre}}</td>
                          <td>@{{descripcion.name}}</td>
                          <td>
                            <input type="text" class="form-control" v-model="descripcion.valor" />
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Observaciónes Producto</label>
                    <p v-for="observacion in observaciones_productos">
                      <i v-if="observacion.activa" class="glyphicon glyphicon-check" @click="quitarObservacionProducto(observacion)"></i>
                      <i v-else class="glyphicon glyphicon-unchecked" @click="agregarObservacionProducto(observacion)"></i>
                      @{{observacion.texto}}
                    </p>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Nueva Observación" v-model="nuevaObservacionProducto" />
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" @click="crearObservacionProducto()">
                          <i class="fas fa-plus"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" style="display:block;">Foto</label>
                    <div class="file-loading">
                      <input id="fotos" name="fotos[]" type="file" ref="fotos" multiple />
                    </div>
                    <div id="fotos-file-errors"></div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group" style="margin-top:25px;">
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
                  <table id="tablaEntradas" class="table table-bordred" style="width:100%;">
                    <thead>
                      <tr>
                        <th>Orden</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3"></td>
                        <td class="text-right"><strong>Subtotal</strong></td>
                        <td>@{{cotizacion.subtotal | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td class="text-right"><strong>IVA</strong></td>
                        <td v-if="cotizacion.iva=='0'">$0.00</td>
                        <td v-else>@{{cotizacion.subtotal * 0.16 | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td class="text-right">
                          <strong>Total
                            <span v-if="cotizacion.moneda=='Dolares'"> Dolares</span>
                            <span v-else> Pesos</span>
                          </strong>
                        </td>
                        <td v-if="cotizacion.iva=='0'">@{{cotizacion.subtotal | formatoMoneda}}</td>
                        <td v-else>@{{cotizacion.subtotal * 1.16 | formatoMoneda}}</td>
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
                  <label class="control-label text-danger">Notas</label>
                  <textarea class="form-control" name="notas" rows="3" cols="80" v-model="cotizacion.notas"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Observaciónes Cotización</label>
                  <p v-for="observacion in observaciones">
                    <i class="glyphicon glyphicon-check" v-if="observacion.activa" @click="quitarObservacion(observacion)"></i>
                    <i class="glyphicon glyphicon-unchecked" v-else @click="agregarObservacion(observacion)"></i>
                    @{{observacion.texto}}
                  </p>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nueva Observación" v-model="nuevaObservacion" />
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button" @click="crearObservacion()">
                        <i class="fas fa-plus"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <div class="form-group">
                  <button type="button" class="btn btn-primary"
                  @click="guardar()" :disabled="cargando">
                  <i v-if="!cargando" class="fas fa-save"></i>
                  <i v-else class="fas fa-refresh animation-rotate"></i>
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
        <table id="tablaProductos" class="table table-bordred">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Proveedor</th>
              <th>Categoria</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(prod, index) in productos">
              <td>@{{prod.id}}</td>
              <td>@{{prod.nombre}}</td>
              <td>@{{prod.proveedor.empresa}}</td>
              <td>@{{prod.categoria.nombre}}</td>
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
    <modal v-model="openNotas" :title="'Notas Cotización '+notas.cotizacion_id" :footer="false">
      <form class="" @submit.prevent="notasCotizacion()">
        <div class="form-group">
          <label class="control-label">Notas</label>
          <textarea name="mensaje" class="form-control" v-model="notas.mensaje" rows="8" cols="80">
          </textarea>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-primary" :disabled="cargando">Guardar</button>
          <button type="button" class="btn btn-default"
            @click="notas.cotizacion_id=0; notas.mensaje=''; openNotas=false;">
            Cancelar
          </button>
        </div>
      </form>
    </modal>
    <!-- /.Enviar Modal -->

    <!-- Enviar Modal -->
    <modal v-model="openEnviar" :title="'Enviar Cotizacion '+enviar.cotizacion_id" :footer="false">
      <form class="" @submit.prevent="enviarCotizacion()">
        <div class="form-group">
          <label class="control-label">Email(s)</label>
          <select2multags :options="enviar.emailOpciones" v-model="enviar.email" style="width:100%;" required>
          </select2multags>
        </div>
        <div class="form-group">
          <label class="control-label">Mensaje</label>
          <textarea name="mensaje" class="form-control" v-model="enviar.mensaje" rows="6" cols="80" required>
          </textarea>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-primary" :disabled="cargando">Enviar</button>
        </div>
      </form>
    </modal>
    <!-- /.Enviar Modal -->

    <!-- Aceptar Modal -->
    <modal v-model="openAceptar" :title="'Aceptar Cotizacion '+aceptar.cotizacion_id" :footer="false">
      <form class="" @submit.prevent="aceptarCotizacion()">
        <div class="form-group">
          <label class="control-label">Comprobante Confirmacion</label>
          <div class="file-loading">
            <input id="comprobante" name="comprobante" type="file" ref="comprobante"
              @change="fijarComprobante()" required />
          </div>
          <div id="comprobante-file-errors"></div>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
          <button type="button" class="btn btn-default"
            @click="aceptar.cotizacion_id=0; openAceptar=false;">
            Cancelar
          </button>
        </div>
      </form>
    </modal>
    <!-- /.Aceptar Modal -->

  </section>
  <!-- /.content -->

@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script type="text/javascript">
// Used for creating a new FileList in a round-about way
function FileListItem(a) {
  a = [].slice.call(Array.isArray(a) ? a : arguments)
  for (var c, b = c = a.length, d = !0; b-- && d;) d = a[b] instanceof File
  if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
  for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(a[c])
  return b.files
}
const app = new Vue({
  el: '#content',
  data: {
    locale: localeES,
    prospecto: {!! json_encode($prospecto) !!},
    productos: {!! json_encode($productos) !!},
    condiciones: {!! json_encode($condiciones) !!},
    observaciones: [
      {activa:false, texto:'Cotización válida por 30 días.'},
      {activa:false, texto:'Los pagos son en dólares, o en pesos al tipo de cambio bancario a la venta en Banorte del día de pago, previamente acordado entre el cliente e Intercorp.'},
      {activa:false, texto:'Se requiere de pisos púlidos y nivelados para una apropiada instalación.'},
      {activa:false, texto:'Si la cantidad de metros solicitados cambia antes de confirmar el pedido, el precio puede cambiar.'},
      {activa:false, texto:'Se consideran maniobras de descarga, a nivel de primer piso.'},
      {activa:false, texto:'No se consideran fianzas; de requerirlas séran con cargo al cliente.'},
    ],
    nuevaObservacion: "",
    observaciones_productos: [],
    nuevaObservacionProducto: "",
    cotizacion: {
      prospecto_id: {{$prospecto->id}},
      numero: "",
      condicion: {
        id: 0,
        nombre: ''
      },
      facturar: '{{$prospecto->cliente->razon_social}}',
      entrega: '',
      lugar: '',
      moneda: '',
      entradas: [],
      subtotal: 0,
      iva: 0,
      total: 0,
      idioma: "",
      notas: "",
      observaciones: []
    },
    entrada: {
      producto: {},
      orden: 0,
      cantidad: 0,
      medida: "",
      precio: 0,
      importe: 0,
      descripciones: [],
      observaciones: [],
      fotos: [],
    },
    enviar: {
      cotizacion_id: 0,
      email: ["{{$prospecto->cliente->email}}"],
      emailOpciones: [
        {id: "{{$prospecto->cliente->email}}", text:"{{$prospecto->cliente->email}}"}
      ],
      mensaje: "Buen día.\n\nLe envió cotización para su consideración.\n\n{{auth()->user()->name}}.\nAtención del Cliente\nIntercorp Contract Resources"
    },
    aceptar: {
      cotizacion_id: 0,
      comprobante: ""
    },
    notas: {
      cotizacion_id: 0,
      mensaje: ""
    },
    dataTableEntradas: {},
    openCatalogo: false,
    openEnviar: false,
    openAceptar: false,
    openNotas: false,
    cargando: false
  },
  filters:{
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$", 2);
    },
  },
  mounted(){
    $("#fotos").fileinput({
      language: 'es',
      overwriteInitial: true,
      maxFileSize: 5000,
      showCaption: false,
      showBrowse: false,
      showRemove: false,
      showUpload: false,
      browseOnZoneClick: true,
      defaultPreviewContent: '<img src="{{asset('images/camara.png')}}" style="width:200px; height:auto;" alt="foto"><h6>Click para seleccionar</h6>',
      allowedFileExtensions: ["jpg", "jpeg", "png"],
      elErrorContainer: '#fotos-file-errors'
    });
    $("#comprobante").fileinput({
      language: 'es',
      showPreview: false,
      showUpload: false,
      showRemove: false,
      allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
      elErrorContainer: '#comprobante-file-errors',
    });
    $("#tablaProductos").DataTable({dom: 'ft'});

    this.dataTableEntradas = $("#tablaEntradas").DataTable({
      data: [],
      searching: false,
      info: false,
      columnDefs: [
        { "orderable": true, "targets": 0 },
        { "orderable": false, "targets": '_all' }
      ],
      processing: false,
      paging: false,
      lengthChange: false,
      rowReorder: {
        selector: 'td:first-child span.fa-grip-vertical',
        snapX: true
      }
    });

    var vueInstance = this;
    //handler para reordenamiento
    this.dataTableEntradas.on( 'row-reorder', function ( e, diff, edit ) {
      // console.log(diff);
      // console.log(edit);
      var i = 0, j = diff.length;
      var nuevo_ordenamiento = 0;
      var indice_descripcion
      for (; i<j; i++) {
        nuevo_ordenamiento = diff[i].newPosition + 1; //+1 Por que empieza en 1
        // console.log(edit.nodes[i].cells[5].childNodes[0]); //Boton
        indice_entrada = $(edit.nodes[i].cells[5].childNodes[0]).data('index');
        vueInstance.cotizacion.entradas[indice_entrada].actualizar = true;
        vueInstance.cotizacion.entradas[indice_entrada].orden = nuevo_ordenamiento;
      }
    });

    //handler para botones de editar y borrar
    $("#tablaEntradas")
    .on('click', 'tr button.btn-success', function(){
      var index = $(this).data('index');
      vueInstance.editarEntrada(vueInstance.cotizacion.entradas[index], index);
    })
    .on('click', 'button.btn-danger', function(){
      var index = $(this).data('index');
      vueInstance.removerEntrada(vueInstance.cotizacion.entradas[index], index);
    });

    this.resetDataTables();
  },
  methods: {
    condicionCambiada(){
      if(this.cotizacion.condicion.id==0){
        this.cotizacion.condicion.nombre = "";
        return true;
      }

      this.condiciones.find(function(cond){
        if(cond.id == this.cotizacion.condicion.id){
          this.cotizacion.condicion.nombre = cond.nombre;
        }
      }, this);
    },
    actualizarCondicion(){
      this.cargando = true;
      axios.put('/condicionesCotizacion/'+this.cotizacion.condicion.id, {'nombre':this.cotizacion.condicion.nombre})
      .then(({data}) => {
        this.condiciones.find(function(cond){
          if(this.cotizacion.condicion.id == cond.id){
            cond.nombre = this.cotizacion.condicion.nombre;
          }
        }, this);
        this.cargando = false;
        swal({
          title: "Condicion Actualizada",
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
    },
    borrarCondicion(){
      this.cargando = true;
      axios.delete('/condicionesCotizacion/'+this.cotizacion.condicion.id, {})
      .then(({data}) => {
        var indexCondicion = this.condiciones.findIndex(function(cond){
          return this.cotizacion.condicion.id == cond.id;
        }, this);

        this.condiciones.splice(indexCondicion, 1);
        this.cotizacion.condicion = {id:0, nombre:''};

        this.cargando = false;
        swal({
          title: "Condicion Actualizada",
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
    },
    resetDataTables(){
      var rows = [], row = [];
      this.cotizacion.entradas.forEach(function(entrada, index){
        if(entrada.borrar==true) return true;
        row = [
          '<span class="fas fa-grip-vertical"></span> '+entrada.orden,
          entrada.producto.nombre,
          entrada.cantidad+" "+entrada.medida,
          accounting.formatMoney(entrada.precio, "$", 2),
          accounting.formatMoney(entrada.importe, "$", 2),
        ];
        row.push([
          '<button class="btn btn-success" title="Editar" data-index="'+index+'">',
            '<i class="fas fa-edit"></i>',
          '</button>',
          '<button class="btn btn-danger" title="Remover" data-index="'+index+'">',
            '<i class="fas fa-times"></i>',
          '</button>'
        ].join(''));
        rows.push(row);
      });

      this.dataTableEntradas.clear();
      this.dataTableEntradas.rows.add(rows);
      this.dataTableEntradas.draw();
    },
    cuentaEntradasNoBorradas(){
      var i = 0;
      this.cotizacion.entradas.forEach(function(entrada){
        if(entrada.borrar!=true) i++;
      });
      return i;
    },
    fijarComprobante(){
      this.aceptar.comprobante = this.$refs['comprobante'].files[0];
    },
    agregarObservacion(observacion){
      this.cotizacion.observaciones.push(observacion.texto);
      observacion.activa = true;
    },
    quitarObservacion(observacion){
      var index = this.cotizacion.observaciones.findIndex(function(obs){
        return observacion.texto == obs;
      });
      this.cotizacion.observaciones.splice(index, 1);
      observacion.activa = false;
    },
    crearObservacion(){
      if(this.nuevaObservacion=="") return false;
      this.observaciones.push({activa:false, texto: this.nuevaObservacion});
      this.agregarObservacion(this.observaciones[this.observaciones.length - 1]);
      this.nuevaObservacion = "";
    },
    agregarObservacionProducto(observacion){
      this.entrada.observaciones.push(observacion.texto);
      observacion.activa = true;
    },
    quitarObservacionProducto(observacion){
      var index = this.entrada.observaciones.findIndex(function(obs){
        return observacion.texto == obs;
      });
      this.entrada.observaciones.splice(index, 1);
      observacion.activa = false;
    },
    crearObservacionProducto(){
      if(this.nuevaObservacionProducto=="") return false;
      this.observaciones_productos.push({activa:false, texto: this.nuevaObservacionProducto});
      this.agregarObservacionProducto(this.observaciones_productos[this.observaciones_productos.length - 1]);
      this.nuevaObservacionProducto = "";
    },
    seleccionarProduco(prod){
      this.entrada.producto = prod;
      this.entrada.descripciones = [];
      prod.descripciones.forEach(function(desc){
        this.entrada.descripciones.push({
          nombre: desc.descripcion_nombre.nombre,
          name: desc.descripcion_nombre.name,
          valor: desc.valor,
        });
      }, this);

      if(prod.foto){
        $("button.fileinput-remove").click();
        $("div.file-default-preview img")[0].src = prod.foto;
      }

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

      if(this.$refs['fotos'].files.length){//hay fotos
        this.entrada.fotos = [];
        for (var i=0; i<this.$refs['fotos'].files.length; i++)
        this.entrada.fotos.push(this.$refs['fotos'].files[i]);
      }

      this.entrada.importe = this.entrada.cantidad * this.entrada.precio;
      this.cotizacion.subtotal+= this.entrada.importe;

      if(this.entrada.orden==0)
        this.entrada.orden = this.cuentaEntradasNoBorradas()+1;

      this.cotizacion.entradas.push(this.entrada);
      this.resetDataTables();
      this.entrada = {
        producto: {},
        orden: 0,
        cantidad: 0,
        medida: "",
        precio: 0,
        importe: 0,
        descripciones: [],
        observaciones: [],
        fotos: []
      };
      $("button.fileinput-remove").click();
      this.observaciones_productos.forEach(function(observacion){
        observacion.activa = false;
      });
    },
    editarEntrada(entrada, index){
      this.cotizacion.subtotal-= entrada.importe;
      this.cotizacion.entradas.splice(index, 1);
      entrada.actualizar = true;
      this.entrada = entrada;
      this.resetDataTables();

      $("button.fileinput-remove").click();
      if(this.entrada.fotos.length){//hay fotos
        if(typeof this.entrada.fotos[0] == "object"){
          this.$refs['fotos'].files =  FileListItem(this.entrada.fotos);
          this.$refs['fotos'].dispatchEvent(new Event('change', { 'bubbles': true }));
        }
        else if(typeof this.entrada.fotos[0] == "string"){
          $("div.file-default-preview").empty();
          this.entrada.fotos.forEach(function(foto){
            $("div.file-default-preview")
              .append('<img src="'+foto+'" style="width:200px; height:auto;" alt="foto">');
          });
          $("div.file-default-preview").append('<h6>Click para seleccionar</h6>');
        }
      }
      else if(this.entrada.producto.foto){
        $("div.file-default-preview img")[0].src = this.entrada.producto.foto;
      }

      this.observaciones_productos.forEach(function(observacion){
        var index = this.entrada.observaciones.findIndex(function(obs){
          return observacion.texto == obs;
        });
        if(index==-1) observacion.activa = false;
        else observacion.activa = true;
      }, this);
    },
    removerEntrada(entrada, index, undefined){
      this.cotizacion.subtotal-= entrada.importe;
      if(entrada.id==undefined) this.cotizacion.entradas.splice(index, 1);
      else entrada.borrar = true;
      $("button.fileinput-remove").click();

      //restar 1 al orden de todas las entradas con orden mayor
      //al de la entrada borrada
      var orden = entrada.orden;
      this.cotizacion.entradas.forEach(function(entrada){
        if(entrada.orden>orden && entrada.borrar==undefined){
          entrada.actualizar = true;
          entrada.orden--;
        }
      });

      this.resetDataTables();
    },
    editar(index, cotizacion){
      this.prospecto.cotizaciones.splice(index, 1);

      //reiniciar observaciones
      this.observaciones.forEach(function(observacion){
        observacion.activa = false;
      });

      //vaciar datos de cotizacion
      this.cotizacion = {
        prospecto_id: {{$prospecto->id}},
        cotizacion_id: cotizacion.id,
        numero: cotizacion.numero,
        condicion: {
          id: cotizacion.condicion_id,
          nombre: ''
        },
        facturar: '{{$prospecto->cliente->razon_social}}',
        entrega: cotizacion.entrega,
        lugar: cotizacion.lugar,
        moneda: cotizacion.moneda,
        entradas: cotizacion.entradas,
        subtotal: cotizacion.subtotal,
        iva: (cotizacion.iva==0)?0:1,
        total: cotizacion.total,
        idioma: cotizacion.idioma,
        notas: cotizacion.notas,
        observaciones: []
      };
      this.condicionCambiada();

      //re-seleccionar observaciones
      var observaciones = cotizacion.observaciones.match(/<li>([^<]+)+<\/li>+/g);
      if(observaciones==null) observaciones = [];
      var encontrada;
      observaciones.forEach(function(observacion){
        observacion = observacion.replace(/(<li>|<\/li>)/g, '');
        encontrada = this.observaciones.findIndex(function(obs){
          return observacion == obs.texto;
        });

        if(encontrada!=-1){
          this.observaciones[encontrada].activa = true;
        }
        else { //observacion diferente de las predefinidas
          this.observaciones.push({activa:true, texto: observacion});
        }
        this.cotizacion.observaciones.push(observacion);
      }, this);

      // agregar observaciones de entradas de productos
      cotizacion.entradas.forEach(function(entrada){
        observaciones = entrada.observaciones.match(/<li>([^<]+)+<\/li>+/g);
        entrada.observaciones = [];
        if(observaciones==null) return false;
        encontrada;
        observaciones.forEach(function(observacion){
          observacion = observacion.replace(/(<li>|<\/li>)/g, '');
          entrada.observaciones.push(observacion);

          encontrada = this.observaciones_productos.findIndex(function(obs){
            return observacion == obs.texto;
          });
          if(encontrada==-1)this.observaciones_productos.push({activa:false, texto: observacion});
        }, this);
      }, this);
      this.resetDataTables();
    },
    guardar(){
      var cotizacion = $.extend(true, {}, this.cotizacion);
      cotizacion.entradas.forEach(function(entrada){
        entrada.producto_id = entrada.producto.id;
        delete entrada.producto;
      });
      var formData = objectToFormData(cotizacion, {indices:true});
      var url = "";

      if(this.cotizacion.cotizacion_id){
        url = '/prospectos/{{$prospecto->id}}/cotizacion/'+this.cotizacion.cotizacion_id;
      }
      else url = '/prospectos/{{$prospecto->id}}/cotizacion';

      this.cargando = true;
      axios.post(url, formData, {
        headers: { 'Content-Type': 'multipart/form-data'}
      })
      .then(({data}) => {
        this.prospecto.cotizaciones.push(data.cotizacion);
        this.cotizacion = {
          prospecto_id: {{$prospecto->id}},
          numero: "",
          condicion: {
            id: 0,
            nombre: ''
          },
          facturar: '{{$prospecto->cliente->razon_social}}',
          entrega: '',
          lugar: '',
          moneda: '',
          entradas: [],
          subtotal: 0,
          iva: 0,
          total: 0,
          notas: "",
          idioma: "",
          observaciones: []
        };
        this.observaciones.forEach(function(observacion){
          observacion.activa = false;
        });
        this.resetDataTables();
        this.cargando = false;
        swal({
          title: "Cotizacion Guardada",
          text: "",
          type: "success"
        }).then(()=>{
          $('a[download="Cotizacion '+data.cotizacion.numero+' Intercorp '+this.prospecto.nombre+'.pdf"]')[0].click();
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
    enviarCotizacion(){
      this.cargando = true;
      axios.post('/prospectos/{{$prospecto->id}}/enviarCotizacion', this.enviar)
      .then(({data}) => {
        this.enviar = {
          cotizacion_id: 0,
          email: ["{{$prospecto->cliente->email}}"],
          emailOpciones: [
            {id: "{{$prospecto->cliente->email}}", text:"{{$prospecto->cliente->email}}"}
          ],
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
    aceptarCotizacion(){
      var formData = objectToFormData(this.aceptar, {indices:true});

      this.cargando = true;
      axios.post('/prospectos/{{$prospecto->id}}/aceptarCotizacion', formData, {
        headers: { 'Content-Type': 'multipart/form-data'}
      })
      .then(({data}) => {
        this.prospecto.cotizaciones.find(function(cotizacion){
          if(this.aceptar.cotizacion_id == cotizacion.id){
            cotizacion.aceptada = true;
            return true;
          }
        }, this);

        this.aceptar = {
          cotizacion_id: 0,
          comprobante: ""
        };
        $("#comprobante").fileinput('clear');
        this.openAceptar = false;
        this.cargando = false;
        swal({
          title: "Cotizacion Aceptada",
          text: "La cotización ha sido aceptada y se ha generado una cuenta por cobrar",
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
    },//fin aceptarCotizacion
    notasCotizacion(){
      this.cargando = true;
      axios.post('/prospectos/{{$prospecto->id}}/notasCotizacion', this.notas)
      .then(({data}) => {
        this.prospecto.cotizaciones.find(function(cotizacion){
          if(this.notas.cotizacion_id == cotizacion.id){
            cotizacion.notas2 = this.notas.mensaje;
            return true;
          }
        }, this);

        this.notas = {
          cotizacion_id: 0,
          mensaje: ""
        };
        $("#comprobante").fileinput('clear');
        this.openNotas = false;
        this.cargando = false;
        swal({
          title: "Notas Guardadas",
          text: "La notas de la cotización se han guardado correctamente",
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
    },//fin notasCotizacion
  }
});
</script>
@stop
