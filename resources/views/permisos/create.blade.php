@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Rol | @parent
@stop

@section('header_styles')

<style>
#content {overflow: visible;}
.color_text{
    color:#B3B3B3;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1 style="font-weight: bolder;">Roles</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel ">
            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
              <h3 class="panel-title">Nuevo Rol</h3>
            </div>
            <div class="panel-body">
              <form class="" @submit.prevent="guardar()">
                <div class="row">
                  <div class="col-md-9">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label">Nombre</label>
                          <input type="text" class="form-control" name="name" v-model="permiso.name" required />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="row" style="margin-top:25px;">
                      <div class="col-sm-12 text-right">
                        <a href="{{route('usuarios.permisos')}}" class="btn btn-default" style="color:#000; background-color:#B3B3B3;">Regresar</a>
                        <button type="submit" class="btn btn-primary" :disabled="cargando">
                          <i class="fa fa-save"></i>
                          Guardar Rol
                        </button>
                      </div>
                    </div>
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
      permiso: {
        name: '',
      },
      cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.post('/permisos', {
          name: this.name,
        })
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Rol Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/permisos";
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
      },//
    },
});
</script>
@stop
