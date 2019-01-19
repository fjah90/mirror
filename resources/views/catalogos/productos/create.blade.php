@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Producto | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Productos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nuevo Producto</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Proveedor</label>
                    <select class="form-control" name="proveedor_id" v-model='producto.proveedor_id' required>
                      @foreach($proveedores as $proveedor)
                        <option value="{{$proveedor->id}}">{{$proveedor->empresa}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Categoria</label>
                    <select class="form-control" name="categoria_id" v-model='producto.categoria_id' required>
                      @foreach($categorias as $categoria)
                        <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Composicón</label>
                    <input type="text" class="form-control" name="composicion" v-model="producto.composicion" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-primary" :disabled="cargando">
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
const app = new Vue({
    el: '#content',
    data: {
      producto: {
        proveedor_id: '',
        categoria_id: '',
        composicion: '',
      },
      cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.post('/productos', this.producto)
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
      },//fin cargarPresupuesto
    }
});
</script>
@stop
