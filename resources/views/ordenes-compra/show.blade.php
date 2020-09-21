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
                <label class="control-label">Número Orden / Order</label>
                <span class="form-control">{{$orden->numero}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">Número Proyecto / Project Number</label>
                <span class="form-control">{{$orden->numero_proyecto}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">Tiempo de Entrega / Delivery</label>
                <span class="form-control">{{$orden->tiempo_entrega}}</span>
              </div>
            </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Punto Entrega / D. Point</label>
                  <span class="form-control">{{$orden->punto_entrega}}</span>
                </div>
                <div class="col-md-4">
                  <label class="control-label">Carga Flete / Freight</label>
                  <span class="form-control">{{$orden->carga}}</span>
                </div>
              </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label">Cliente</label>
                <span class="form-control">{{$orden->cliente_nombre}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label">Proveedor / To</label>
                <span class="form-control">{{$orden->proveedor_empresa}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label">Agente Aduanal / Ship To</label>
                <span class="form-control">{{$orden->aduana_compañia}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label">Proveedor Contacto</label>
                <span class="form-control">{{$orden->contacto->nombre}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-4">
                <label class="control-label">Telefono</label>
                <span class="form-control">{{$orden->contacto->telefono}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">E-mail</label>
                <span class="form-control">{{$orden->contacto->email}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">Dias Credito</label>
                <span class="form-control">{{$orden->proveedor->dias_credito}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-4">
                <label class="control-label">Moneda</label>
                <span class="form-control">{{$orden->moneda}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">IVA</label>
                <span class="form-control">{{($orden->iva>0)?'Si':'No'}}</span>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Comentarios</th>
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
                        <td>{{$entrada->comentarios}}</td>
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

            <div class="row form-group">
              <div class="col-md-12">
                <h4>Archivos para autorización</h4>
                <ul>
                  <li v-for="(archivo, index) in archivos_autorizacion" style="margin-bottom:3px;">
                    <button class="btn btn-xxs btn-danger" @click="borrarArchivo(archivo, index)">
                      <i class="fas fa-times"></i>
                    </button>
                    <a :href="archivo.liga" target="_blank">@{{archivo.nombre}}</a>
                  </li>
                </ul>
              </div>
            </div>
            
            <form @submit.prevent="agregarArchivo()">
              <div class="row form-group">
                <div class="col-md-6">
                  <label class="control-label">Nuevo Archivo</label>
                  <div class="file-loading">
                    <input id="archivo" name="nuevo_archivo" type="file" ref="archivo" required/>
                  </div>
                  <div id="archivo-file-errors"></div>
                </div>
                <div class="col-md-4">
                  <label class="control-label">Nombre Archivo</label>
                  <input class="form-control" name="nombre_archivo" type="text" v-model="archivo.nombre_archivo" />
                </div>
                <div class="col-md-2 text-right" style="margin-top:25px;">
                  <button class="btn btn-primary" type="submit">Agregar</button>
                </div>
              </div>
            </form>  

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
                  <a class="btn btn-default"
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
    archivos_autorizacion: {!! json_encode($archivos_autorizacion) !!},
    archivo: {
      nombre_archivo: '',
      nuevo_archivo: ''
    }
  },
  mounted(){
    $("#archivo").fileinput({
      language: 'es',
      showPreview: false,
      showUpload: false,
      showRemove: false,
      allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
      elErrorContainer: '#archivo-file-errors',
    });
  },
  methods: {
    agregarArchivo(){
      this.cargando = true;
      this.archivo.nuevo_archivo = this.$refs['archivo'].files[0];
      var formData = objectToFormData(this.archivo, {indices:true});
      axios.post('/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra/{{$orden->id}}/agregarArchivo',
        formData, {headers: { 'Content-Type': 'multipart/form-data'}}
      ).then(({data}) => {
        this.archivos_autorizacion.push(data.archivo);
        this.archivo = {
          nombre_archivo: '',
          nuevo_archivo: ''
        };
        $("#archivo").fileinput('clear');
        this.cargando = false;
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
    },//fin agregarArchivo
    borrarArchivo(archivo, index){
      axios.post('/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra/{{$orden->id}}/borrarArchivo',{
        'archivo':archivo.nombre
      }).then(({data}) => {
        this.archivos_autorizacion.splice(index,1);
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
    comprar(){
      this.cargando = true;
      axios.post('/proyectos-aprobados/{{$proyecto->id}}/ordenes-compra/{{$orden->id}}/comprar',{})
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
