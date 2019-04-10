@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cotizar Prospecto | @parent
@stop

@section('header_styles')
<!-- <style>
</style> -->
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
                  <label class="control-label">Nombre de Proyecto</label>
                  <span class="form-control">{{$prospecto->nombre}}</span>
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
                            :download="'cotizacion '+cotizacion.id+'.pdf'">
                            <i class="far fa-file-pdf"></i>
                          </a>
                          <button class="btn btn-info" title="Enviar"
                            @click="enviar.cotizacion_id=cotizacion.id; openEnviar=true;">
                            <i class="far fa-envelope"></i>
                          </button>
                          <button v-if="!cotizacion.aceptada" class="btn btn-success" title="Aceptar"
                            @click="aceptar.cotizacion_id=cotizacion.id; openAceptar=true;">
                            <i class="fas fa-user-check"></i>
                          </button>
                          <button v-else class="btn text-primary" title="Aceptada">
                            <i class="fas fa-user-check"></i>
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
                    <select class="form-control" name="condiciones" v-model='cotizacion.condicion.id' required>
                      <option v-for="condicion in condiciones" :value="condicion.id">@{{condicion.nombre}}</option>
                      <option value="0">Otra</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6" v-if="">
                  <div class="form-group">
                    <label class="control-label">Especifique Otra</label>
                    <input class="form-control" type="text" name="condiciones"
                      v-model="cotizacion.condicion.nombre"
                      :disabled="cotizacion.condicion.id!=0"
                      :required="cotizacion.condicion.id==0" />
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
                      <option value="M">M</option>
                      <option value="M2">M2</option>
                      <option value="M3">M3</option>
                      <option value="Yarda">Yarda</option>
                      <option value="Pies">Pies</option>
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
                    <label class="control-label">Observación</label>
                    <textarea name="name" rows="1" cols="80" v-model="entrada.observacion" class="form-control">
                    </textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label" style="display:block;">Foto</label>
                    <div class="kv-avatar">
                      <div class="file-loading">
                        <input id="foto" name="foto" type="file" ref="foto" @change="fijarFoto()" />
                      </div>
                    </div>
                    <div id="foto-file-errors"></div>
                  </div>
                </div>
                <div class="col-md-offset-4 col-md-4 text-right">
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
                        <td>@{{entrada.producto.nombre}}</td>
                        <td>@{{entrada.cantidad}} @{{entrada.medida}}</td>
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
                        <td v-if="cotizacion.iva=='0'">$0.00</td>
                        <td v-else>@{{cotizacion.subtotal * 0.16 | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
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
                  <label class="control-label">Observaciónes</label>
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
              <th>Categoria</th>
              <th>Nombre</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(prod, index) in productos">
              <td>@{{prod.id}}</td>
              <td>@{{prod.proveedor.empresa}}</td>
              <td>@{{prod.categoria.nombre}}</td>
              <td>@{{prod.nombre}}</td>
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
    cotizacion: {
      prospecto_id: {{$prospecto->id}},
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
      cantidad: 0,
      medida: "",
      precio: 0,
      importe: 0,
      descripciones: [],
      observacion: "",
      foto: "",
      foto_src: ""
    },
    enviar: {
      cotizacion_id: 0,
      email: ["{{$prospecto->cliente->email}}"],
      emailOpciones: [
        {id: "{{$prospecto->cliente->email}}", text:"{{$prospecto->cliente->email}}"}
      ],
      mensaje: "Buen día.\n\nLe envió cotización para su consideración.\n\nCarla Aguilar.\nAtención del Cliente\nIntercorp Contract Resources"
    },
    aceptar: {
      cotizacion_id: 0,
      comprobante: ""
    },
    notas: {
      cotizacion_id: 0,
      mensaje: ""
    },
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
    $("#foto").fileinput({
      overwriteInitial: true,
      maxFileSize: 5000,
      showClose: false,
      showCaption: false,
      showBrowse: false,
      browseOnZoneClick: true,
      removeLabel: '',
      removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
      removeTitle: 'Quitar Foto',
      defaultPreviewContent: '<img src="{{asset('images/camara.png')}}" alt="foto"><h6 class="text-muted">Click para seleccionar</h6>',
      layoutTemplates: {main2: '{preview} {remove} {browse}'},
      allowedFileExtensions: ["jpg", "jpeg", "png"],
      elErrorContainer: '#foto-file-errors'
    });
    $("#comprobante").fileinput({
      language: 'es',
      showPreview: false,
      showUpload: false,
      showRemove: false,
      allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
      elErrorContainer: '#comprobante-file-errors',
    });
  },
  methods: {
    fijarFoto(){
      this.entrada.foto = this.$refs['foto'].files[0];
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
        $("#foto").siblings('button').click();
        this.entrada.foto_src = prod.foto;
        $("div.file-default-preview img")[0].src = this.entrada.foto_src;
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

      var fotoPrev = $("img.file-preview-image");
      if(fotoPrev[0]) this.entrada.foto_src = fotoPrev[0].src;
      this.entrada.importe = this.entrada.cantidad * this.entrada.precio;
      this.cotizacion.subtotal+= this.entrada.importe;
      this.cotizacion.entradas.push(this.entrada);
      this.entrada = {
        producto: {},
        cantidad: 0,
        medida: "",
        precio: 0,
        importe: 0,
        descripciones: [],
        observacion: "",
        foto: "",
        foto_src: ""
      };
      $("#foto").siblings('button').click();
    },
    editarEntrada(entrada, index){
      this.cotizacion.subtotal-= entrada.importe;
      this.cotizacion.entradas.splice(index, 1);
      this.entrada = entrada;

      $("#foto").siblings('button').click();
      if(this.entrada.foto_src!="")
        $("div.file-default-preview img")[0].src = this.entrada.foto_src;
    },
    removerEntrada(entrada, index){
      this.cotizacion.subtotal-= entrada.importe;
      this.cotizacion.entradas.splice(index, 1);
      $("#foto").siblings('button').click();
    },
    guardar(){
      var cotizacion = $.extend(true, {}, this.cotizacion);
      cotizacion.entradas.forEach(function(entrada){
        entrada.producto_id = entrada.producto.id;
        delete entrada.producto;
        if(entrada.foto_src=="") delete entrada.foto;
        delete entrada.foto_src;
      });
      var formData = objectToFormData(cotizacion, {indices:true});

      this.cargando = true;
      axios.post('/prospectos/{{$prospecto->id}}/cotizacion', formData, {
        headers: { 'Content-Type': 'multipart/form-data'}
      })
      .then(({data}) => {
        this.prospecto.cotizaciones.push(data.cotizacion);
        this.cotizacion = {
          prospecto_id: {{$prospecto->id}},
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
        this.cargando = false;
        swal({
          title: "Cotizacion Guardada",
          text: "",
          type: "success"
        }).then(()=>{
          $('a[download="cotizacion '+data.cotizacion.id+'.pdf"]')[0].click();
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
