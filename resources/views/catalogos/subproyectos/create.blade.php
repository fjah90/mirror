@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Tipo Proyecto | @parent
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
  <section class="content-header" style="background-color:#12160F; color:#caa678;">
    <h1>Tipos Proyectos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
            <h3 class="panel-title">Nuevo Tipo Proyecto</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Categoría</label>
                    <select class="form-control" name="proyecto_id" v-model='subproyecto.proyecto_id' required>
                      @foreach($proyectos as $proyecto)
                        <option value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="subproyecto.nombre" required />
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:25px;">
                <div class="col-md-12 text-right">
                  <a class="btn btn-default" href="{{route('subproyectos.index')}}" style="margin-right:20px; color:#000; background-color:#B3B3B3;">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-primary" :disabled="cargando" style="background-color:#12160F; color:#caa678;">
                    <i class="fas fa-save"></i>
                    Guardar Tipo
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
      subproyecto: {
        proyecto_id: '',
        nombre: '',
      },
      cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.post('/subproyectos', this.subproyecto)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Tipo Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/subproyectos";
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
