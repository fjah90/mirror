@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Orden Compra | @parent
@stop

@section('header_styles')
<!-- <style>
</style> -->
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 style="font-weight: bolder;">Ordenes de Compra</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Editar Orden Proyecto {{$proyecto->proyecto}} // {{$cotizacion->numero}}</h3>
          </div>
          <div class="panel-body">
            @if($orden->status=='Rechazada')
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label text-danger" style="font-weight:bold">Orden Rechazada</label>
                  <textarea class="form-control" disabled style="border:1px solid #FF7A7A;">{{$orden->motivo_rechazo}}
                  </textarea>
                </div>
              </div>
            </div>
            @endif
            <div class="row form-group">
              <div class="col-md-3">
                <label class="control-label">Número Orden /<br> Order</label>
                <input type="number" step="1" min="1" class="form-control" name="numero"
                  v-model="orden.numero" required />
              </div>
              <div class="col-md-3">
                <label class="control-label">Número Proyecto / Project Number</label>
                <input type="text" step="1" min="1" class="form-control" name="numero_proyecto"
                  v-model="orden.numero_proyecto" />
              </div>
              @if($orden->proveedor_id)
              <div class="col-md-3">
                <label class="control-label">Proveedor /<br> To</label>
                <span class="form-control">{{$orden->proveedor_empresa}}</span>
              </div>
              <div class="col-md-3">
                <label class="control-label">Número de cliente / Customer number</label>
                <input class="form-control" name="numero_cliente"
                  v-model="orden.numero_cliente" />
              </div>
              @else
              <div class="col-md-3">
                <label class="control-label">Proveedor /<br> To</label>
                <select class="form-control" name="proveedor_id" v-model='orden.proveedor_id'
                  required @change="fijarProveedor()">
                  @foreach($proveedores as $proveedor)
                    <option value="{{$proveedor->id}}">{{$proveedor->empresa}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="control-label">Número de cliente / Customer number</label>
                <input class="form-control" name="numero_cliente"
                  v-model="orden.numero_cliente" />
              </div>
              @endif
            </div>
            <div class="row form-group">
              <div class="col-md-4">
                <label class="control-label">Agente Aduanal / Ship To</label>
                <select class="form-control" name="aduana_id" v-model='orden.aduana_id' @change="fijarAduana()">
                  @foreach($aduanas as $aduana)
                    <option value="{{$aduana->id}}">{{$aduana->compañia}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="control-label">Tiempo de Entrega / Delivery</label>
                <select class="form-control" name="tiempo.id" v-model='orden.tiempo.id'>
                  @foreach($tiempos_entrega as $tiempo)
                  <option value="{{$tiempo->id}}">{{$tiempo->valor}}</option>
                  @endforeach
                  <option value="0">Otro</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="control-label">Especifique Tiempo</label>
                <input type="text" class="form-control" name="tiempo.valor"
                  v-model="orden.tiempo.valor" :disabled="orden.tiempo.id!='0'" />
              </div>
            </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Punto Entrega / D. Point</label>
                  <input type="text" class="form-control" name="punto_entrega"
                         v-model="orden.punto_entrega" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Carga Flete / Freight</label>
                  <input type="text" class="form-control" name="carga"
                         v-model="orden.carga" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Costo Flete</label>
                  <input type="number" class="form-control" name="carga" min='0'
                         v-model="orden.flete" @change="agregarFlete()" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Fecha de Orden de Compra</label>
                  <br />
                  <dropdown style="width:100%;">
                    <div class="input-group" >
                      <div class="input-group-btn">
                        <btn class="dropdown-toggle" style="background-color:#fff;">
                          <i class="fas fa-calendar"></i>
                        </btn>
                      </div>
                      <input class="form-control" type="text" name="fecha_compra"
                             v-model="orden.fecha_compra_formated" placeholder="DD/MM/YYYY"
                             readonly
                      />
                    </div>
                    <template slot="dropdown">
                      <li>
                        <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                                     format="dd/MM/yyyy" :date-parser="dateParser" v-model="orden.fecha_compra_formated"/>
                      </li>
                    </template>
                  </dropdown>
                </div>
              </div>
            <div class="row form-group">
              <div class="col-md-4">
                <label class="control-label">Moneda</label>
                <span class="form-control">@{{orden.moneda}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">IVA</label>
                <select class="form-control" name="iva" v-model="orden.iva" required>
                  <option value="0">No</option>
                  <option value="1" >Si</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="control-label">Contacto Proveedor / ATTN</label>
                <select class="form-control" name="proveedor_contacto_id" v-model='orden.proveedor_contacto_id'
                  required>
                  @foreach($contactos as $contacto)
                  <option v-if="orden.proveedor_id=={{$contacto->proveedor_id}}" value="{{$contacto->id}}">
                    {{$contacto->nombre}}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
            @if(!$orden->proveedor->nacional)
            <div class="row form-group">
              <div class="col-md-6">
                <label class="control-label">Dirección de Entrega / Ship to</label>
                <textarea rows="4" class="form-control" v-model="orden.delivery"></textarea>
              </div>
            </div>
            @endif

              <div class="col-md-12"><hr></div>
            <form class="" @submit.prevent="agregarEntrada()">
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
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Cantidad</label>
                    <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control"
                      v-model="entrada.cantidad" @change="convertir1()" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Unidad Medida {{$orden->moneda == 'Dolares' ? '/ Unidad Medida Ingles' : '' }}</label>
                    <select class="form-control" name="medida" v-model="entrada.medida" required
                      @change="reiniciarConversion()">
                      @foreach($unidades_medida as $unidad)
                        @if($orden->moneda == 'Dolares')
                          <option value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }} / {{ $unidad->simbolo_ingles }}</option>
                        @else
                          <option value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Conversión</label>
                    <select class="form-control" name="conversion" v-model="entrada.conversion"
                      @change="convertirCantidad()">
                      <option value="">Seleccionar</option>
                      <option v-for="(multiplo, unidad) in conversiones[entrada.medida]"
                        :value="unidad">
                        @{{unidad}}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Cant. en conversión</label>
                    <input type="text" class="form-control" name="cantidad_conversion"
                      v-model="entrada.cantidad_convertida" @change="convertir2()"  />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Precio Unitario</label>
                    <input type="number" step="0.01" min="0.01" name="precio" class="form-control"
                    v-model="entrada.precio" required />
                  </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-md-2">
                      <button type="button" class="btn btn-primary" @click="modalProducto=true">
                          Registrar producto
                      </button>
                  </div>
              </div>

              <div class="row form-group">
                <div class="col-md-6">
                  <label class="control-label">Comentarios</label>
                  <textarea rows="2" class="form-control" v-model="entrada.comentarios"></textarea>
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
                        <th>Valor Inglés</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="(descripcion, index) in entrada.descripciones">
                        <td>@{{descripcion.nombre}}</td>
                        <td>@{{descripcion.name}}</td>
                        <td>
                          <input type="text" class="form-control"
                                 v-model="descripcion.valor"/>
                        </td>
                        <td>
                          <input v-if="entrada.producto.descripciones[index].descripcion_nombre.valor_ingles"
                                 type="text" class="form-control"
                                 v-model="descripcion.valor_ingles"/>
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
                        <label class="control-label" style="display:block;">Foto</label>
                        <div class="btn btn-sm btn-danger" v-if="entrada.fotos.length" v-on:click="borrarfotos" title="BORRAR FOTOS">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="file-loading">
                            <input id="fotos" name="fotos[]" type="file" ref="fotos" multiple/>
                        </div>
                        <div id="fotos-file-errors"></div>
                    </div>
                </div>
            </div>
              <div class="row">
                <div class="col-md-12 text-right">
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
                  <table class="table table-bordred" id="tablaEntradas">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Área</th>
                        <th>Comentarios</th>
                        <th>Cantidad</th>
                        <th>Conversión</th>
                        <th>Cant. en conversión</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(entrada, index) in orden.entradas" v-if="entrada.borrar!=true"
                        :class="(entrada.conversion)?'bg-warning':''">
                        <td>@{{entrada.producto.nombre}}</td>
                        <td>@{{entrada.area}}</td>
                        <td>@{{entrada.comentarios}}</td>
                        <td>@{{entrada.cantidad}} @{{entrada.medida}}</td>
                        <td>@{{entrada.conversion}}</td>
                        <td>@{{entrada.cantidad_convertida}}</td>
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
                        <td colspan="4"></td>
                        <td class="text-right"><strong>Subtotal</strong></td>
                        <td>@{{orden.subtotal | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="4"></td>
                        <td class="text-right"><strong>Flete</strong></td>
                        <td>@{{orden.flete | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="4"></td>
                        <td class="text-right"><strong>IVA</strong></td>
                        <td v-if="orden.iva=='0'">$0.00</td>
                        <td v-else>@{{orden.subtotal * 0.16 | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="4"></td>
                        <td class="text-right">
                          <strong>Total
                            <span v-if="orden.moneda=='Dolares'"> Dolares</span>
                            <span v-else> Pesos</span>
                          </strong>
                        </td>
                        <td v-if="orden.iva=='0'">@{{orden.subtotal | formatoMoneda}}</td>
                        <td v-else>@{{orden.subtotal * 1.16 | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <div class="form-group">
                  <a class="btn btn-default"
                    href="{{route('proyectos-aprobados.ordenes-compra.index', $proyecto->id)}}">
                    Regresar
                  </a>
                  <button type="button" class="btn btn-success"
                    @click="guardar()" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Actualizar Orden
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Precios Aprobados</h3>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-bordred">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Fecha de Precio</th>
                    <th>Precio de Compra</th>
                    <th>contacto de proveedor</th>
                    <th>Unidad de Medida Compra</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(entrada, index) in proyecto.cotizacion.entradas" v-if="entrada.precio_compra!=null">
                    <td>@{{entrada.producto.nombre}}</td>
                    <td>@{{entrada.fecha_precio_compra}}</td>
                    <td>@{{entrada.precio_compra | formatoMoneda}}</td>
                    <td>@{{entrada.contacto && entrada.contacto.nombre}}</td>
                    <td>@{{entrada.medida_compra}}</td>
                  </tr>
                </tbody>
              </table>
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
              <th>Nombre</th>
              <th>Proveedor</th>
              <th>Tipo</th>
              <th>Ficha Técnica</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(prod, index) in productos">
              <td>@{{prod.nombre}}</td>
              <td>@{{prod.proveedor.empresa}}</td>
              <td>@{{prod.categoria.nombre}}</td>
              <td>
                <a v-if="prod.ficha_tecnica" :href="prod.ficha_tecnica" target="_blank"
                  class="btn btn-success" style="cursor:pointer;">
                  <i class="far fa-file-pdf"></i>
                </a>
              </td>
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
Vue.config.devtools = true;

 function buildFormData (formData, data, parentKey){
  console.log(data);
  if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
    Object.keys(data).forEach(key => {
    this.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
    });
  } else {
    const value = data == null ? '' : data;
  
    formData.append(parentKey, value);
  }
}

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
    proveedores: {!! json_encode($proveedores) !!},
    aduanas: {!! json_encode($aduanas) !!},
    productos: {!! json_encode($productos) !!},
    orden: {!! json_encode($orden) !!},
    proyecto: {!! json_encode($proyecto) !!},
    locale: localeES,
    modalProducto: false,
    flete2 : {!! json_encode($orden->flete) !!},
    entrada: {
      producto: {},
      area: "",
      cantidad: 0,
      medida: "",
      conversion: "",
      cantidad_convertida: "",
      precio: 0,
      importe: 0,
      comentarios: '',
      descripciones:[],
      fotos: []
    },
    conversiones:{
      @foreach($unidades_medida as $unidad)
        '{{$unidad->simbolo}}': {
        @foreach($unidad->conversiones as $conversion)
          '{{ $conversion->unidad_conversion_simbolo }}':{{ $conversion->factor_conversion }},
        @endforeach
        },
      @endforeach
    },
    tablaProductos: {},
    dataTableEntradas: {},
    openCatalogo: false,
    cargando: false
  },
  filters:{
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$", 2);
    },
  },
  mounted(){
    var vue = this;
    //console.log(vue.orden.iva)
    vue.orden.iva = vue.orden.proveedor.nacional? 1:0;
    //console.log(vue.orden.iva)
    $.fn.dataTableExt.afnFiltering.push(
      function( settings, data, dataIndex ) {
        var prov = data[1] || ""; // Our date column in the table
        return (vue.orden.proveedor.empresa == prov);
      }
    );

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

    this.tablaProductos = $("#tablaProductos").DataTable({dom: 'ftp'});

    //tabla reordenable
    /*
    this.dataTableEntradas = $("#tablaEntradas").DataTable({
          searching: false,
          info: false,
          columnDefs: [
              {"orderable": true, "targets": 0},
              {"orderable": false, "targets": '_all'}
          ],
          processing: false,
          paging: false,
          lengthChange: false,
          rowReorder: {
              selector: 'td:first-child span.fa-grip-vertical',
              snapX: true
          }
      });
    

      //handler para reordenamiento
      this.dataTableEntradas.on('row-reorder', function (e, diff, edit) {
          // console.log(diff);
          //console.log(edit);
          var i = 0, j = diff.length;
          var nuevo_ordenamiento = 0;
          var indice_descripcion
          for (; i < j; i++) {
              nuevo_ordenamiento = diff[i].newPosition + 1; //+1 Por que empieza en 1
               console.log(edit.nodes[i].cells[5].childNodes[0]); //Boton
              indice_entrada = $(edit.nodes[i].cells[4].childNodes[0]).data('index');
              console.log(indice_entrada);
              vueInstance.entrada[indice_entrada].actualizar = true;
              vueInstance.entrada[indice_entrada].orden = nuevo_ordenamiento;
          }
      });
    */

    var vueInstance = this;
    //escuchar Iframe
    window.addEventListener('message', function (e) {
      if (e.data.tipo == "producto") {
        vueInstance.tablaProductos.destroy();
        vueInstance.productos.push(e.data.object);
        vueInstance.seleccionarProduco(e.data.object);
        vueInstance.modalProducto = false;
        Vue.nextTick(function () {
          vueInstance.tablaProductos = $("#tablaProductos").DataTable({dom: 'ftp'});
        });
      }
    }, false);
  },
  methods: {
    dateParser(value){
      return moment(value, 'DD/MM/YYYY').toDate().getTime();
    },
    borrarfotos(){
        $("button.fileinput-remove").click();
        this.entrada.fotos = [];
    },
    fijarProveedor(){
      this.proveedores.find(function(proveedor){
        if(proveedor.id == this.orden.proveedor_id){
          this.orden.proveedor_empresa = proveedor.empresa;
          this.orden.proveedor_contacto_id = '';
          this.orden.moneda = proveedor.moneda;
          this.entrada.descripciones = [];
          if(proveedor.moneda=='Dolares') this.orden.iva = 0;
          else this.orden.iva = 1;
          return true;
        }
      }, this);

      this.entrada.producto = {};//por si ya estaba seleccionado uno
      this.tablaProductos.draw();
    },
    fijarAduana(){
      this.aduanas.find(function(aduana){
        if(aduana.id == this.orden.aduana_id){
          this.orden.aduana_compañia = aduana.compañia;
          return true;
        }
      }, this);
    },
    agregarFlete(){
      var f = parseFloat(this.orden.flete);   
      if (typeof (f) != 'number' || isNaN(f)) {
        this.orden.flete = 0;
        if (isNaN(f)) {
          var f = parseFloat(this.orden.flete);   
          var f2 = parseFloat(this.flete2);   
          var o = parseFloat(this.orden.subtotal);   
          o-=f2;
          o+=f;
          this.flete2 = this.orden.flete;
          this.orden.subtotal = o;  
        }
      }
      else{
        
        var f = parseFloat(this.orden.flete);   
        var f2 = parseFloat(this.flete2);   
        var o = parseFloat(this.orden.subtotal);   
        o-=f2;
        o+=f;
        this.flete2 = this.orden.flete;
        this.orden.subtotal = o;  
      }
      

    },
    seleccionarProduco(prod){
      this.entrada.producto = prod;
      this.entrada.descripciones = [];
      prod.descripciones.forEach(function (desc) {
        this.entrada.descripciones.push({
          nombre: desc.descripcion_nombre.nombre,
          name: desc.descripcion_nombre.name,
          valor: desc.valor,
          valor_ingles: desc.valor_ingles
        });
      }, this);

      if (prod.foto) {
        $("button.fileinput-remove").click();
        $("div.file-default-preview img")[0].src = prod.foto;
      }
      this.openCatalogo = false;
    },
    actualizarlista(){
      console.log('entre');
    },
    reiniciarConversion(){
      this.entrada.conversion = "";
      this.entrada.cantidad_convertida = "";
    },
    convertir1(){
      console.log('prueba');
      if (this.entrada.conversion != "") {
        this.entrada.cantidad_convertida =
        (this.entrada.cantidad * this.conversiones[this.entrada.medida][this.entrada.conversion])
        .toFixed(2);
      }
    },
    convertir2(){
      
      if (this.entrada.conversion != "") {
        this.entrada.cantidad =
        (this.entrada.cantidad_convertida * this.conversiones[this.entrada.conversion][this.entrada.medida])
        .toFixed(2);
      }
    },
    convertirCantidad(){
      console.log(this.entrada.conversion);
      if(this.entrada.conversion=="" || this.entrada.conversion==undefined){
        console.log(this.entrada.conversion);
        this.entrada.cantidad_convertida="";
      }else{
        this.entrada.cantidad_convertida =
        (this.entrada.cantidad * this.conversiones[this.entrada.medida][this.entrada.conversion])
        .toFixed(2);
      }
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
      
      if(this.entrada.cantidad_convertida!="" && this.entrada.cantidad_convertida!=null && this.entrada.cantidad_convertida!=undefined){
        this.entrada.importe = this.entrada.cantidad_convertida * this.entrada.precio;
      }
      else{
       this.entrada.importe = this.entrada.cantidad * this.entrada.precio;
      }

      if (this.$refs['fotos'].files.length) {//hay fotos

          this.entrada.fotos = [];
          for (var i = 0; i < this.$refs['fotos'].files.length; i++)
              this.entrada.fotos.push(this.$refs['fotos'].files[i]);
      }
      $("button.fileinput-remove").click();

      this.orden.subtotal+= this.entrada.importe;
      this.orden.entradas.push(this.entrada);
      this.entrada = {
        producto: {},
        cantidad: 0,
        medida: "",
        conversion: "",
        cantidad_convertida: "",
        precio: 0,
        importe: 0,
        comentarios: '',
        fotos:[]
      };
    },
    editarEntrada(entrada, index){
      this.orden.subtotal-= entrada.importe;
      //this.orden.fecha_compra = this.orden.fecha_compra_formated;
      this.orden.entradas.splice(index, 1);
      

      if(entrada.conversion==undefined || entrada.conversion==null){
        entrada.conversion = "";
        entrada.cantidad_convertida = "";
      }
      if (entrada.conversion == entrada.medida) {
        entrada.conversion = "";
        entrada.cantidad_convertida = ""; 
      }

      $("button.fileinput-remove").click();
      if (entrada.fotos.length) {//hay fotos
          if (typeof entrada.fotos[0] == "object") {
              this.$refs['fotos'].files = FileListItem(entrada.fotos);
              this.$refs['fotos'].dispatchEvent(new Event('change', {'bubbles': true}));
          } else if (typeof entrada.fotos[0] == "string") {
              $("div.file-default-preview").empty();
              entrada.fotos.forEach(function (foto) {
                  $("div.file-default-preview")
                      .append('<img src="' + foto + '" style="width:200px; height:auto;" alt="foto">');
              });
              $("div.file-default-preview").append('<h6>Click para seleccionar</h6>');
          }
      }

      
      this.entrada = entrada;


      @foreach($unidades_medida as $unidad)

        if(entrada.medida == 'YD2'){
          entrada.medida = 'SQYD';
        }

        if ('{{$unidad->simbolo_ingles}}' == entrada.medida) {
            this.entrada.medida = '{{$unidad->simbolo}}';    
        }
        if ('{{$unidad->simbolo}}' == entrada.medida) {
            this.entrada.medida = '{{$unidad->simbolo}}';     
        }
      @endforeach


      @foreach($proyecto->cotizacion->entradas as $entrada)

        if ('{{$entrada->producto_id}}' == this.entrada.producto_id) {

          if(this.conversiones[this.entrada.medida]['{{$entrada->medida_compra}}'] != undefined || this.conversiones[this.entrada.medida]['{{$entrada->medida_compra}}'] != null){
            console.log('entre');
            this.entrada.conversion = '{{$entrada->medida_compra}}';
            this.convertirCantidad();
          }

        }
        
      @endforeach
      

      var descripciones = [];
      entrada.descripciones.forEach(function (desc) {
        descripciones.push({
          descripcion_nombre: {
            nombre: desc.nombre,
            name: desc.name,
            valor_ingles: desc.valor_ingles
          },
          valor: desc.valor,
        });
      }, this);
      this.entrada.producto.descripciones = descripciones;

      
    },
    removerEntrada(entrada, index){
      this.orden.subtotal-= entrada.importe;
      this.orden.entradas.splice(index, 1);
      if(entrada.id) {
        entrada.borrar = true;
        this.orden.entradas.push(entrada);
      }
    },
    guardar(){
      var orden = $.extend(true, {}, this.orden);

      /*
      var totalf = 0;
      orden.entradas.forEach(function (entrada) {
          totalf += entrada.importe ;
      });

      totalcotizacion = orden.subtotal.toFixed(2);

      orden.subtotal = totalf + orden.flete;

      var dif = totalcotizacion - totalf;
      */

      orden.entradas.forEach(function(entrada){
        entrada.producto_id = entrada.producto.id;
        delete entrada.producto;
      });

      delete orden.proveedor;

      var formData = objectToFormData(orden, {indices: true});

      this.cargando = true;
      axios.post('/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra/{{$orden->id}}/actualizar', formData,{headers: {'Content-Type': 'multipart/form-data'}
      })
      .then(({data}) => {
        swal({
          title: "Orden Actualizada",
          text: "",
          type: "success"
        }).then(()=>{
          window.location = "/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra";
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
