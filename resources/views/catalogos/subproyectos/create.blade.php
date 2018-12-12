@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Sub Proyecto | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Sub Proyectos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nuevo Sub Proyecto</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Proyecto</label>
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
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Sub Proyecto
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
            title: "Sub Proyecto Guardado",
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
