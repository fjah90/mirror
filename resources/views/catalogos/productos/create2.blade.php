@extends($layout === 'iframe' ? 'layouts/iframe' : 'layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Producto | @parent
@stop

@section('header_styles')
    <style>
        .color_text {
            color: #B3B3B3;
        }

        #loading {
            position: fixed;
            inset: 0;
            background: #0009;
            display: grid;
            place-items: center;
            font-size: 4rem;
            z-index: 2;
            color: white;
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" style="display:block;">Archivo de muestra de telas</label>
                                        <a class="btn btn-xs btn-success" title="archivo"
                                            href="/archivos/formato_carga_masiva_productos.xlsx">
                                            <i class="far fa-file"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" style="display:block;">Archivo de muestra de
                                            tapices</label>
                                        <a class="btn btn-xs btn-success" title="archivo"
                                            href="/archivos/formato_carga_masiva_productos_tapices.xlsx">
                                            <i class="far fa-file"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label" style="display:block;">Archivo CSV</label>
                                        <div class="file-loading">
                                            <input id="archivo" name="archivo" type="file" ref="archivo"
                                                @change="fijarArchivo('archivo')" />
                                        </div>
                                        <div id="archivo-file-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:25px;">
                                <div class="col-md-12 text-right">
                                    @if ($layout !== 'iframe')
                                        <a class="btn btn-default" href="{{ route('productos.index') }}"
                                            style="margin-right: 20px; color:#000; background-color:#B3B3B3">
                                            Regresar
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary" :disabled="cargando">
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
    <section id='loading' style="display:none">Loading...</section>
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
            nombre_material:'',
            color:'',
            precio_unitario: '',
            precio_residencial: '',
            precio_comercial: '',
            precio_distribuidor: '',
            foto: '',
            ficha_tecnica: '',
            descripciones: []
          },
          cargando: false,
          is_iframe: {{ $layout === 'iframe' ? 'true' : 'false' }},
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
            var load = document.querySelector("#loading");
           
            var formData = objectToFormData(this.producto, {indices:true});
            load.style.display = "grid";
            this.cargando = true;
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
              load.style.display = "none";
              
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
          },//fin cargarPresupuesto
        }
    });
    /* beautify ignore:end */
    </script>
@stop
