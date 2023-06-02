@extends($layout==="iframe"?'layouts/iframe' : 'layouts/default')

{{-- Page title --}}
@section('title')
Nuevo Producto | @parent
@stop

@section('header_styles')
<style>
  .color_text{
    color:#B3B3B3;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header" style="background-color:#12160F; color:#B68911;">
  <h1>Productos</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel ">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title">Cargar Productos</h3>
        </div>
        <div class="panel-body">
          <form class="" @submit.prevent="guardar()">
            <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                <label class="control-label" style="display:block;">Archivo de muestra</label>
                <a class="btn btn-xs btn-success" title="archivo" href="/archivos/formato_carga_masiva_productos.xlsx">
                    <i class="far fa-file"></i>
                </a>
                </div>
            </div>
            
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label" style="display:block;">Archivo CSV</label>
                  <div class="file-loading">
                    <input id="archivo" name="archivo" type="file" ref="archivo" @change="fijarArchivo('archivo')" />
                  </div>
                  <div id="archivo-file-errors"></div>
                </div>
              </div>
            </div>
            <div class="row" style="margin-top:25px;">
              <div class="col-md-12 text-right">
                @if($layout !=='iframe')
                  <a class="btn btn-default" href="{{route('productos.index')}}" style="margin-right: 20px; color:#000; background-color:#B3B3B3">
                    Regresar
                  </a>
                @endif
                <button type="submit" class="btn btn-primary" :disabled="cargando" @click="actualizarlista()">
                  <i class="fas fa-save"></i>
                  Guardar Producto
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')

<script>
  /* beautify ignore:start */
const app = new Vue({
    el: '#content',
    data: {
      producto: {
        proveedor_id: '',
        categoria_id: '',
        subcategoria_id: '',
        nombre: '',
        foto: '',
        ficha_tecnica: '',
        descripciones: []
      },
      cargando: false,
      is_iframe: {{$layout==="iframe"?'true' : 'false'}},
    },
    mounted(){
      $("#archivo").fileinput({
        language: 'es',
        showPreview: false,
        showUpload: false,
        showRemove: false,
        allowedFileExtensions: ["csv"],
        elErrorContainer: '#archivo-file-errors'
      });
    },
    methods: {
      fijarArchivo(campo){
        this.producto[campo] = this.$refs[campo].files[0];
      },
      guardar(){
        var formData = objectToFormData(this.producto, {indices:true});
        this.cargando = true;
        swal({
          text: '¿Desea guardar los datos?".',
          button: {
            text: "Guardar!",
            closeModal: false,
          },
        })
        .then(results => {
          swal.showLoading();
          axios.post('/productosguardar', formData, {
            headers: { 'Content-Type': 'multipart/form-data'}
          })
          .then(({data}) => {
            
            this.cargando = false;
            swal({
              title: "Producto Guardado",
              text: "",
              type: "success"
            }).then(()=>{
                window.location = "/productos";
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

        })
          
      },//fin cargarPresupuesto
    }
});
/* beautify ignore:end */
</script>
@stop
