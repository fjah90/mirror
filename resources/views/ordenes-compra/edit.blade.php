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
    <h1>Ordenes de Compra</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Editar Orden Proyecto {{$proyecto->proyecto}}</h3>
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
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Proveedor</label>
                  <span class="form-control">{{$orden->proveedor_empresa}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Moneda</label>
                  <span class="form-control">{{$orden->moneda}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">IVA</label>
                  <select class="form-control" name="iva" v-model="orden.iva" required>
                    <option value="0">No</option>
                    <option value="1">Si</option>
                  </select>
                </div>
              </div>
            </div>
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
                      v-model="entrada.cantidad" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Unidad Medida</label>
                    <select class="form-control" name="medida" v-model="entrada.medida" required
                      @change="reiniciarConversion()">
                      <option value="M">M</option>
                      <option value="M2">M2</option>
                      <option value="M3">M3</option>
                      <option value="Yarda">Yarda</option>
                      <option value="Yarda2">Yarda2</option>
                      <option value="Pies">Pies</option>
                      <option value="Pies2">Pies2</option>
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
                      <option value="" disabled>Seleccionar</option>
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
                    <span class="form-control">@{{entrada.cantidad_convertida}}</span>
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
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Conversión</th>
                        <th>Cant. en conversión</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(entrada, index) in orden.entradas" v-if="entrada.borrar!=true">
                        <td>@{{entrada.producto.nombre}}</td>
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

    <!-- Catalogo Productos Modal -->
    <modal v-model="openCatalogo" title="Productos" :footer="false">
      <div class="table-responsive">
        <table class="table table-bordred">
          <thead>
            <tr>
              <th>ID</th>
              <th>Categoria</th>
              <th>Nombre</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(prod, index) in productos" v-if="prod.proveedor_id==orden.proveedor_id">
              <td>@{{prod.id}}</td>
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

  </section>
  <!-- /.content -->

@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script type="text/javascript">
const app = new Vue({
  el: '#content',
  data: {
    productos: {!! json_encode($productos) !!},
    orden: {!! json_encode($orden) !!},
    entrada: {
      producto: {},
      cantidad: 0,
      medida: "",
      conversion: "",
      cantidad_convertida: "",
      precio: 0,
      importe: 0
    },
    conversiones:{
      'M': {'Yarda':1.0936, 'Pies':3.2808},
      'Yarda': {'M':0.9144, 'Pies':3},
      'Pies': {'M':0.3048, 'Yarda':0.3333},
      'M2': {'Yarda2':1.196, 'Pies2':10.7584},
      'Yarda2': {'M2':0.8361, 'Pies2':9},
      'Pies2': {'M2':0.0929, 'Yarda2':0.1110},
    },
    openCatalogo: false,
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
    reiniciarConversion(){
      this.entrada.conversion = "";
      this.entrada.cantidad_convertida = "";
    },
    convertirCantidad(){
      this.entrada.cantidad_convertida =
        (this.entrada.cantidad * this.conversiones[this.entrada.medida][this.entrada.conversion])
        .toFixed(2);
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
      if(this.entrada.cantidad_convertida!="")
        this.entrada.importe = this.entrada.cantidad_convertida * this.entrada.precio;
      else this.entrada.importe = this.entrada.cantidad * this.entrada.precio;

      this.orden.subtotal+= this.entrada.importe;
      this.orden.entradas.push(this.entrada);
      this.entrada = {
        producto: {},
        cantidad: 0,
        medida: "",
        conversion: "",
        cantidad_convertida: "",
        precio: 0,
        importe: 0
      };
    },
    editarEntrada(entrada, index){
      this.orden.subtotal-= entrada.importe;
      this.orden.entradas.splice(index, 1);
      if(entrada.conversion==undefined){
        entrada.conversion = "";
        entrada.cantidad_convertida = "";
      }
      this.entrada = entrada;
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
      orden.entradas.forEach(function(entrada){
        entrada.producto_id = entrada.producto.id;
        delete entrada.producto;
      });

      this.cargando = true;
      axios.put('/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra/{{$orden->id}}', orden)
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
