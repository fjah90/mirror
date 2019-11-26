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
                  <label class="control-label">Numero Orden</label>
                  <input type="number" step="1" min="1" class="form-control" name="numero"
                    v-model="orden.numero" required />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Numero Proyecto</label>
                  <input type="number" step="1" min="1" class="form-control" name="numero_proyecto"
                    v-model="orden.numero_proyecto" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Proveedor</label>
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
                  <label class="control-label">Agente Aduanal</label>
                  <select class="form-control" name="aduana_id" v-model='orden.aduana_id' @change="fijarAduana()">
                    @foreach($aduanas as $aduana)
                    <option value="{{$aduana->id}}">{{$aduana->compañia}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="control-label">Tiempo de Entrega</label>
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
                  <label class="control-label">Contacto Proveedor</label>
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
                  <label class="control-label">Unidad Medida</label>
                  <select class="form-control" name="medida" v-model="entrada.medida" required>
                    @foreach($unidades_medida as $unidad)
                    <option value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="control-label">Precio Unitario</label>
                  <input type="number" step="0.01" min="0.01" name="precio" class="form-control"
                    v-model="entrada.precio" required />
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
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(entrada, index) in orden.entradas">
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
                        <td>@{{orden.subtotal | formatoMoneda}}</td>
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
    orden: {
      proyecto_id: {{$proyecto->id}},
      proveedor_id: '',
      proveedor_contacto_id: '',
      proveedor_empresa: '',
      aduana_id: 0,
      aduana_compañia: '',
      numero: '',
      numero_proyecto: '',
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
      importe: 0
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
  },
  methods: {
    fijarProveedor(){
      this.proveedores.find(function(proveedor){
        if(proveedor.id == this.orden.proveedor_id){
          this.orden.proveedor_empresa = proveedor.empresa;
          this.orden.proveedor_contacto_id = '';
          this.orden.moneda = proveedor.moneda;
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
        importe: 0
      };
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
