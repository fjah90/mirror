@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Categorias Proyectos | @parent
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
    <h1>Categorias Proyectos</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Categorias Proyectos</span>
            <span style="visibility:hidden">.</span>
            @role('Administrador')
            <a href="{{route('proyectos.create')}}" class="btn btn-warning" style="color: #000;">
              <i class="fa fa-plus"></i> Nueva Categoria
            </a>
            @endrole
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#12160F">
                  <th class="color_text">#</th>
                  <th class="color_text">Nombre</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(proyecto, index) in proyectos">
                  <td>@{{index+1}}</td>
                  <td>@{{proyecto.nombre}}</td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/proyectos/'+proyecto.id">
                      <i class="far fa-eye"></i>
                    </a>
                    @role('Administrador')
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/proyectos/'+proyecto.id+'/editar'">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(proyecto, index)">
                      <i class="fas fa-times"></i>
                    </button>
                    @endrole
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}" ></script>
<script>
const app = new Vue({
    el: '#content',
    data: {
      proyectos: {!! json_encode($proyectos) !!},
    },
    mounted(){
      $("#tabla").DataTable();
    },
    methods: {
      borrar(proyecto, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Proyecto "+proyecto.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/proyectos/'+proyecto.id, {})
            .then(({data}) => {
              this.proyectos.splice(index, 1);
              swal({
                title: "Exito",
                text: "El proyecto ha sido borrado",
                type: "success"
              });
            })
            .catch(({response}) => {
              console.error(response);
              swal({
                title: "Error",
                text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                type: "error"
              });
            });
          } //if confirmacion
        });
      },//fin borrar
    }
});
</script>
@stop
