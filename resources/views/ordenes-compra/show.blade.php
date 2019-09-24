@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Orden Compra | @parent
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
            <h3 class="panel-title">Orden Compra Proyecto {{$proyecto->proyecto}}</h3>
          </div>
          <div class="panel-body">
            @if($orden->status=='Rechazada')
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label text-danger" style="font-weight:bold">Orden Rechazada</label>
                <textarea class="form-control" disabled style="border:1px solid #FF7A7A;">{{$orden->motivo_rechazo}}
                </textarea>
              </div>
            </div>
            @endif
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
                <label class="control-label">Tiempo de Entrega</label>
                <span class="form-control">{{$orden->tiempo_entrega}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label">Cliente</label>
                <span class="form-control">{{$orden->cliente_nombre}}</span>
              </div>
            </div>
            <div class="row form-group">
              @if($orden->proveedor_id)
              <div class="col-md-12">
                <label class="control-label">Proveedor</label>
                <span class="form-control">{{$orden->proveedor_empresa}}</span>
              </div>
              @else
              <div class="col-md-12">
                <label class="control-label">Proveedor</label>
                <select class="form-control" name="proveedor_id" v-model='orden.proveedor_id'
                  required @change="fijarProveedor()">
                  @foreach($proveedores as $proveedor)
                    <option value="{{$proveedor->id}}">{{$proveedor->empresa}}</option>
                  @endforeach
                </select>
              </div>
              @endif
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label">Agente Aduanal</label>
                <span class="form-control">{{$orden->aduana_compañia}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-4">
                <label class="control-label">Telefono</label>
                <span class="form-control">{{$orden->proveedor->telefono}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">E-mail</label>
                <span class="form-control">{{$orden->proveedor->email}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">Dias Credito</label>
                <span class="form-control">{{$orden->proveedor->dias_credito}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-4">
                <label class="control-label">Moneda</label>
                <span class="form-control">{{$orden->proveedor->moneda}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">IVA</label>
                @if($orden->iva > 0)
                <span class="form-control">Si</span>
                @else
                <span class="form-control">No</span>
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Conversión</th>
                        <th>Cant. en Conversión</th>
                        <th>Precio</th>
                        <th>Importe</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($orden->entradas as $entrada)
                      <tr>
                        <td>{{$entrada->producto->nombre}}</td>
                        <td>{{$entrada->cantidad}} {{$entrada->medida}}</td>
                        <td>{{$entrada->conversion}}</td>
                        <td>{{$entrada->cantidad_convertida}}</td>
                        <td>@format_money($entrada->precio)</td>
                        <td>@format_money($entrada->importe)</td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="4"></td>
                        <td class="text-right"><strong>Subtotal</strong></td>
                        <td>@format_money($orden->subtotal)</td>
                      </tr>
                      <tr>
                        <td colspan="4"></td>
                        <td class="text-right"><strong>IVA</strong></td>
                        <td>@format_money($orden->iva)</td>
                      </tr>
                      <tr>
                        <td colspan="4"></td>
                        <td class="text-right">
                          <strong>Total {{$orden->moneda}}</strong>
                        </td>
                        <td>@format_money($orden->total)</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center">
                <div class="form-group">
                  @if($orden->status=='Pendiente')
                  <button type="button" class="btn btn-warning"
                    @click="comprar()" :disabled="cargando">
                    <i class="fas fa-cash-register"></i>
                    Comprar Orden
                  </button>
                  @endif
                  <a class="btn btn-info"
                    href="{{route('proyectos-aprobados.ordenes-compra.index', $proyecto->id)}}">
                    Regresar
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->

@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script type="text/javascript">
const app = new Vue({
  el: '#content',
  data: {
    cargando: false,
    proveedores: {!! json_encode($proveedores) !!},
    orden:{
      numero: {{$orden->numero}},
      numero_proyecto: {{$orden->numero_proyecto}},
      proveedor_id: '{{$orden->proveedor_id}}',
      proveedor_empresa: '{{$orden->proveedor_empresa}}',
      moneda: '{{$orden->moneda}}'
    },
  },
  methods: {
    fijarProveedor(){
      this.proveedores.find(function(proveedor){
        if(proveedor.id == this.orden.proveedor_id){
          this.orden.proveedor_empresa = proveedor.empresa;
          this.orden.moneda = proveedor.moneda;
          return true;
        }
      }, this);
    },
    comprar(){
      this.cargando = true;
      axios.post('/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra/{{$orden->id}}/comprar',this.orden)
      .then(({data}) => {
        swal({
          title: "Orden Comprada",
          text: "La orden a pasado a estatus 'Por Autorizar'",
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
    },//fin comprar
  }
});
</script>
@stop
