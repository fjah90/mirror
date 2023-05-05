@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nueva Rol | @parent
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
  <section class="content-header"  style="background-color:#12160F; color:#B68911;">
    <h1>Roles</h1>
  </section>
  <!-- Main content -->          
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading"  style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Nueva Rol</h3>
          </div>
          <div class="panel-body">
            {!!Form::open(['route'=>'permisos.store','method'=>'POST','id'=>'formroles']) !!}
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" required />
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:25px;">
                <div class="col-md-12 text-right">
                  <a class="btn btn-default" href="{{route('usuarios.permisos')}}" style="margin-right:20px; color:#000; background-color:#B3B3B3">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-primary" :disabled="cargando" name="Guardar" value="Guardar">
                    <i class="fas fa-save"></i>
                    Guardar Rol
                  </button>
                </div>
              </div>
          {{Form::close()}}
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
<<<<<<< HEAD
const app = new Vue({
    el: '#content',
    data: {
      name: ''
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
    }
});
</script>
@stop
