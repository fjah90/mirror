@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Categoría de Cliente | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Categoría  de Cliente</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Categoría de Cliente</span>
            <span style="visibility:hidden">.</span>
            @hasrole('Administrador')
            <a href="{{route('categoriaClientes.create')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fa fa-plus"></i> Nueva Categoría
            </a>
            @endhasrole
          </h3>
        </div>           
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#f5bf4c">
                  <th>#</th>
                  <th>Categoría</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(categoria, index) in categorias">
                  <td>@{{index+1}}</td>
                  <td>@{{categoria.nombre}}</td><!--categoria definir en el controlador-->
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/categoriaClientes/'+categoria.id">
                      <i class="far fa-eye"></i>
                    </a>
                    @role('Administrador')
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/categoriaClientes/'+categoria.id+'/editar'">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(categoria, index)">
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
      categorias: {!! json_encode($categorias) !!},
    },
    mounted(){
      $("#tabla").DataTable({"order": [[ 1, "asc" ]]});
    },
    methods: {
      borrar(categoria, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Categoría "+categoria.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/categoriaClientes/'+categoria.id, {})
            .then(({data}) => {
              this.categorias.splice(index, 1);
              swal({
                title: "Exito",
                text: "La Categoría ha sido borrada",
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
