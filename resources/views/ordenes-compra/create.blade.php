@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Crear Orden Compra | @parent
@stop

@section('header_styles')
<!-- <style>
</style> -->
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Ordenes de Compra</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Nueva Orden Proyecto {{$proyecto->proyecto}}</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="agregarEntrada()">
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Número Orden / Order</label>
                  <input type="number" step="1" min="1" class="form-control" name="numero"
                    v-model="orden.numero" required />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Número Proyecto / Project Number</label>
                  <input type="text" step="1" min="1" class="form-control" name="numero_proyecto"
                    v-model="orden.numero_proyecto" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Proveedor / To</label>
                  <select class="form-control" name="proveedor_id" v-model='orden.proveedor_id'
                    required :disabled="orden.entradas.length>0" @change="fijarProveedor()">
                    @foreach($proveedores as $proveedor)
                    <option value="{{$proveedor->id}}">{{$proveedor->empresa}}</option>
                    @endforeach
                  </select>
                </div>
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
                  <input type="number" class="form-control" name="carga"
                         v-model="orden.flete" @change="agregarFlete()"/>
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
                             v-model="orden.fecha_compra" placeholder="DD/MM/YYYY"
                             readonly
                      />
                    </div>
                    <template slot="dropdown">
                      <li>
                        <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                                     format="dd/MM/yyyy" :date-parser="dateParser" v-model="orden.fecha_compra"/>
                      </li>
                    </template>
                  </dropdown>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Moneda</label>
                  <input type="text" class="form-control" name="moneda"
                    v-model="orden.moneda" required disabled />
                </div>
                <div class="col-md-4">
                  <label class="control-label">IVA</label>
                  <select class="form-control" name="iva" v-model="orden.iva" required disabled>
                    <option value="0">No</option>
                    <option value="1">Si</option>
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
              <div class="col-md-12"><hr></div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Producto</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Producto"
                    v-model="entrada.producto.nombre" @click="abrirCatalogo()"
                    readonly required
                    />
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button" @click="abrirCatalogo()">
                        <i class="far fa-edit"></i>
                      </button>
                    </span>
                  </div>
                </div>
                <div class="col-md-2">
                  <label class="control-label">Cantidad</label>
                  <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control"
                    v-model="entrada.cantidad" required />
                </div>
                <div class="col-md-2">
                  <label class="control-label">Unidad Medida <span v-if="orden.moneda=='Dolares'"> / Unidad Medida dolares</span><span v-else></span></label>
                  <select class="form-control" name="medida" v-model="entrada.medida" required>
                    @foreach($unidades_medida as $unidad)
                        <option v-if="orden.moneda=='Dolares'" value="{{ !empty($unidad->simbolo_ingles) ? $unidad->simbolo_ingles : $unidad->simbolo }}">{{ $unidad->simbolo }}
                          / {{ $unidad->simbolo_ingles }}</option>
                        <option v-else value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="control-label">Precio Unitario</label>
                  <input type="number" step="0.01" min="0.01" name="precio" class="form-control"
                    v-model="entrada.precio" required />
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
              <div class="row form-group">
                <div class="col-md-12 text-right">
                  <button type="submit" class="btn btn-info">
                    <i class="fas fa-plus"></i>
                    Agregar Producto
                  </button>
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
                        <th>Comentarios</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(entrada, index) in orden.entradas">
                        <td>@{{entrada.producto.nombre}}</td>
                        <td>@{{entrada.comentarios}}</td>
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
                        <td>@{{orden.subtotal | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>Flete</strong></td>
                        <td>@{{orden.flete | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>IVA</strong></td>
                        <td v-if="orden.iva=='0'">$0.00</td>
                        <td v-else>@{{orden.subtotal * 0.16 | formatoMoneda}}</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
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
                  <button type="button" class="btn btn-primary"
                    @click="guardar()" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Orden
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
const app = new Vue({
  el: '#content',
  data: {
    proveedores: {!! json_encode($proveedores) !!},
    aduanas: {!! json_encode($aduanas) !!},
    productos: {!! json_encode($productos) !!},
    locale: localeES,
    modalProducto: false,
    flete2:0,
    orden: {
      proyecto_id: {{$proyecto->id}},
      proveedor_id: '',
      proveedor_contacto_id: '',
      proveedor_empresa: '',
      aduana_id: 0,
      aduana_compañia: '',
      numero: '',
      numero_proyecto: '',
      punto_entrega: '',
      fecha_compra: moment().format('DD/MM/YYYY'),
      carga: '',
      flete:'',
      tiempo: {
        id: '',
        valor: ''
      },
      moneda: '',
      entradas: [],
      subtotal: 0,
      iva: 0,
      total: 0,
    },
    entrada: {
      producto: {},
      cantidad: 0,
      medida: "",
      precio: 0,
      importe: 0,
      comentarios: '',
      descripciones:[]
    },
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
    $.fn.dataTableExt.afnFiltering.push(
      function( settings, data, dataIndex ) {
        var prov = data[1] || ""; // Our date column in the table
        return (vue.orden.proveedor_empresa == prov);
      }
    );
    this.tablaProductos = $("#tablaProductos").DataTable({dom: 'ftp'});

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
    abrirCatalogo(){
      if(this.orden.proveedor_id==0){
        swal({
          title: "Error",
          text: "Debe seleccionar proveedor primero",
          type: "error"
        });
      }
      else this.openCatalogo = true;
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
      this.orden.subtotal+= this.entrada.importe;
      this.orden.entradas.push(this.entrada);
      this.entrada = {
        producto: {},
        cantidad: 0,
        medida: "",
        precio: 0,
        importe: 0,
        comentarios: '',
      };
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
    editarEntrada(entrada, index){
      this.orden.subtotal-= entrada.importe;
      this.orden.entradas.splice(index, 1);
      this.entrada = entrada;
    },
    removerEntrada(entrada, index){
      this.orden.subtotal-= entrada.importe;
      this.orden.entradas.splice(index, 1);
    },
    guardar(){
      var orden = $.extend(true, {}, this.orden);
      orden.entradas.forEach(function(entrada){
        entrada.producto_id = entrada.producto.id;
        delete entrada.producto;
      });

      this.cargando = true;
      axios.post('/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra', orden)
      .then(({data}) => {
        swal({
          title: "Orden Guardada",
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
