@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nueva Categoria del Cliente | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Categoria del Clientes</h1>
  </section>
  <!-- Main content -->          
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nueva Categoria del Cliente</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="nombre" required />
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:25px;">
                <div class="col-md-12 text-right">
                  <a class="btn btn-default" href="{{route('categoriaCliente.index')}}" style="margin-right:20px;">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Categoria
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
      nombre: '',
      cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.post('/categoriaCliente', {
          nombre: this.nombre,
        })
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Categoria Guardada",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/categoriaCliente";
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
      },
    }
});
</script>
@stop
