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
                    <label class="control-label">Tiempo de Entrega</label>
                    <input type="text" name="entrega" class="form-control"
                      v-model="cotizacion.entrega" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Lugar de Entrega</label>
                    <input type="text" name="lugar" class="form-control"
                      v-model="cotizacion.lugar" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Moneda</label>
                    <select class="form-control" name="moneda" v-model="cotizacion.moneda" required>
                      <option value="Dorales">Dolares USD</option>
                      <option value="Pesos">Pesos MXN</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Condiciones De Pago</label>
                    <select class="form-control" name="condiciones" v-model='cotizacion.condicion.id' required>
                      <option v-for="condicion in condiciones" :value="condicion.id">@{{condicion.nombre}}</option>
                      <option value="0">Otra</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4" v-if="cotizacion.condicion.id==0">
                  <div class="form-group">
                    <label class="control-label">Especifique</label>
                    <input class="form-control" type="text" name="condiciones"
                      v-model="cotizacion.condicion.nombre" required />
                  </div>
                </div>
                <div class="col-md-4" v-else></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">IVA</label>
                    <select class="form-control" name="iva" v-model="cotizacion.iva" required>
                      <option value="0">No</option>
                      <option value="1">Si</option>
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
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label" style="display:block;">Foto</label>
                    <div class="kv-avatar">
                      <div class="file-loading">
                        <input id="foto" name="foto" type="file" ref="foto" @change="fijarFoto()" />
                      </div>
                    </div>
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
                        <td>@{{entrada.producto.composicion}}</td>
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
                            <span v-if="cotizacion.moneda=='Dolares'"> (USD)</span>
                            <span v-else> (MXN)</span>
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
                  <tinymce-editor name="name" v-model="cotizacion.observaciones"
                    v-model="texto" :init="init"
                  >
                  </tinymce-editor>
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
              <th>Composicón</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(prod, index) in productos">
              <td>@{{prod.id}}</td>
              <td>@{{prod.proveedor.empresa}}</td>
              <td>@{{prod.categoria.nombre}}</td>
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
    init: {
      language: 'es_MX',
      branding: false,
      menubar: false,
      plugins: "lists",
      toolbar: "undo, redo | cut, copy, paste | bold, italic | alignleft, aligncenter, alignright, alignjustify | numlist bullist | indent, outdent | styleselect"
    },
    prospecto: {!! json_encode($prospecto) !!},
    productos: {!! json_encode($productos) !!},
    condiciones: {!! json_encode($condiciones) !!},
    cotizacion: {
      prospecto_id: {{$prospecto->id}},
      condicion: {
        id: 0,
        nombre: ''
      },
      entrega: '',
      lugar: '',
      moneda: '',
      entradas: [],
      subtotal: 0,
      iva: 0,
      total: 0,
      notas: "",
      observaciones: ""
    },
    entrada: {
      producto: {},
      coleccion: "",
      diseno: "",
      color: "",
      cantidad: 0,
      medida: "",
      precio: 0,
      importe: 0,
      observacion: "",
      foto: "",
      foto_src: ""
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
      allowedFileExtensions: ["jpg", "jpeg", "png"]
    });
  },
  methods: {
    fijarFoto(){
      this.entrada.foto = this.$refs['foto'].files[0];
    },
    seleccionarProduco(prod){
      this.entrada.producto = prod;
      this.entrada.coleccion = prod.coleccion;
      this.entrada.diseno = prod.diseño;

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
        coleccion: "",
        diseno: "",
        color: "",
        cantidad: 0,
        medida: "",
        precio: 0,
        importe: 0,
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
          entrega: '',
          lugar: '',
          moneda: '',
          entradas: [],
          subtotal: 0,
          iva: 0,
          total: 0,
          notas: "",
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
  }
});
</script>
@stop
