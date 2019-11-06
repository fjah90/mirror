@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Tipos Porductos | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>TIPOS DE PRODUCTOS</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Tipos de Productos y Servicios</span>
            <span style="visibility:hidden">.</span>
            @hasrole('Administrador')
            <a href="{{route('categorias.create')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fa fa-plus"></i> Nuevo Tipo
            </a>
            @endhasrole
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
            data-page-length="100">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(categoria, index) in categorias">
                  <td>@{{index+1}}</td>
                  <td>@{{categoria.nombre}}</td>
                  <td class="text-right">
                    <a class="btn btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/categorias/'+categoria.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/categorias/'+categoria.id+'/editar'">
                      <i class="far fa-edit"></i>
                    </a>
                    @hasrole('Administrador')
                    <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(categoria, index)">
                      <i class="fas fa-times"></i>
                    </button>
                    @endhasrole
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
      categorias: {!! json_encode($categorias) !!},
    },
    mounted(){
      $("#tabla").DataTable({"order": [[ 1, "asc" ]]});
    },
    methods: {
      borrar(categoria, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Tipo "+categoria.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/categorias/'+categoria.id, {})
            .then(({data}) => {
              this.categorias.splice(index, 1);
              swal({
                title: "Exito",
                text: "La categoria ha sido borrado",
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
