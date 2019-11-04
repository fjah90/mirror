@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Tipo Proveedor | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Tipos de Proveedores</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Editar Tipo de Proveedor</h3>
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
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-success" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Actualizar Tipo
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
      nombre: '{{$tipo->nombre}}',
      cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.put('/tiposProveedores/{{$tipo->id}}', {
          nombre: this.nombre,
        })
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Tipo Actualizado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/tiposProveedores";
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
