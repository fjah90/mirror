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
          <h3 class="panel-title">Nuevo Producto</h3>
        </div>
        <div class="panel-body">
          <form class="" @submit.prevent="guardar()">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Proveedor</label>
                  <select class="form-control" name="proveedor_id" v-model='producto.proveedor_id' required>
                    <option value="0">Por Definir</option>
                    @foreach($proveedores as $proveedor)
                    <option value="{{$proveedor->id}}">{{$proveedor->empresa}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Categoria</label>
                  <select class="form-control" name="subcategoria_id" v-model='producto.subcategoria_id'>
                    <option value=""></option>
                    @foreach($subcategorias as $subcategoria)
                    <option value="{{$subcategoria->id}}">{{$subcategoria->nombre}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Tipo de Producto o Servicio</label>
                  <select class="form-control" name="categoria_id" v-model='producto.categoria_id' @change="cambiarDescripciones()" required>
                    @foreach($categorias as $categoria)
                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Código de Producto o Servicio</label>
                  <input type="text" class="form-control" name="nombre" v-model="producto.nombre" required />
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
                        <th>Valor Inglés</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(descripcion, index) in producto.descripciones">
                        <td>@{{descripcion.nombre}}</td>
                        <td>@{{descripcion.name}}</td>
                        <td>
                          <input v-if="!descripcion.no_alta_productos" type="text" class="form-control" v-model="descripcion.valor" />
                        </td>
                        <td>
                          <input v-if="!descripcion.no_alta_productos && descripcion.valor_ingles" type="text" class="form-control" v-model="descripcion.valor_ingles_valor" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label" style="display:block;">Foto</label>
                  <div class="kv-avatar">
                    <div class="file-loading">
                      <input id="foto" name="foto" type="file" ref="foto" @change="fijarArchivo('foto')" />
                    </div>
                  </div>
                  <div id="foto-file-errors"></div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label" style="display:block;">Ficha Técnica</label>
                  <div class="file-loading">
                    <input id="ficha_tecnica" name="ficha_tecnica" type="file" ref="ficha_tecnica" @change="fijarArchivo('ficha_tecnica')" />
                  </div>
                  <div id="ficha_tecnica-file-errors"></div>
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
      categorias: @json($categorias),
      cargando: false,
      is_iframe: {{$layout==="iframe"?'true' : 'false'}},
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
      $("#ficha_tecnica").fileinput({
        language: 'es',
        showPreview: false,
        showUpload: false,
        showRemove: false,
        allowedFileExtensions: ["pdf"],
        elErrorContainer: '#ficha_tecnica-file-errors'
      });
    },
    methods: {
      fijarArchivo(campo){
        this.producto[campo] = this.$refs[campo].files[0];
      },
      cambiarDescripciones(){
        this.categorias.some(function(categoria){
          if(categoria.id == this.producto.categoria_id){
            this.producto.descripciones = categoria.descripciones;
          }
        }, this);
      },
      guardar(){
        var formData = objectToFormData(this.producto, {indices:true});

        this.cargando = true;
        axios.post('/productos', formData, {
          headers: { 'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Producto Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            if(this.is_iframe){
              parent.postMessage({message:"OK", tipo:"producto", object: data.producto}, "*")
              window.location = "/productos/crear?layout=iframe";
            }else{
              window.location = "/productos";
            }     
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
      },//fin cargarPresupuesto
    }
});
/* beautify ignore:end */
</script>
@stop
