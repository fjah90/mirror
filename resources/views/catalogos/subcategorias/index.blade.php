@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Categorias Productos | @parent
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
    <h1>CATEGORIAS DE PRODUCTOS</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Categorias</span>
            <span style="visibility:hidden">.</span>
            @hasrole('Administrador')
            <a href="{{route('subcategorias.create')}}" class="btn btn-warning" style="color: #000;">
              <i class="fa fa-plus"></i> Nueva Categoria
            </a>
            @endhasrole
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
                <tr v-for="(subcategoria, index) in subcategorias">
                  <td>@{{index+1}}</td>
                  <td>@{{subcategoria.nombre}}</td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/subcategorias/'+subcategoria.id">
                      <i class="far fa-eye"></i>
                    </a>
                    @role('Administrador')
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/subcategorias/'+subcategoria.id+'/editar'">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(subcategoria, index)">
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
<script>
const app = new Vue({
    el: '#content',
    data: {
      subcategorias: {!! json_encode($subcategorias) !!},
    },
    mounted(){
      $("#tabla").DataTable({"order": [[ 1, "asc" ]]});
    },
    methods: {
      borrar(subcategoria, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Categoria "+subcategoria.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/subcategorias/'+subcategoria.id, {})
            .then(({data}) => {
              this.subcategorias.splice(index, 1);
              swal({
                title: "Exito",
                text: "La subcategoria ha sido borrado",
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
