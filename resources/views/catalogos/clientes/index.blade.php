@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Clientes | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Clientes</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Clientes</span>
            <a href="{{route('clientes.create')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fas fa-plus"></i> Nuevo Cliente
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tipo</th>
                  <th>Nombre</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th>RFC</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cliente, index) in clientes">
                  <td>@{{cliente.id}}</td>
                  <td>@{{cliente.tipo.nombre}}</td>
                  <td>@{{cliente.nombre}}</td>
                  <td>@{{cliente.telefono}}</td>
                  <td>@{{cliente.email}}</td>
                  <td>@{{cliente.rfc}}</td>
                  <td class="text-right">
                    <a class="btn btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/clientes/'+cliente.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/clientes/'+cliente.id+'/editar'">
                      <i class="far fa-edit"></i>
                    </a>
                    <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(cliente, index)">
                      <i class="fas fa-times"></i>
                    </button>
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
      clientes: {!! json_encode($clientes) !!},
    },
    mounted(){
      $("#tabla").DataTable({
        dom: 'lfrtip',
      });
    },
    methods: {
      borrar(cliente, index){
        swal({
          title: 'Cuidado',
          text: "Borrar el Cliente "+cliente.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/clientes/'+cliente.id, {})
            .then(({data}) => {
              this.clientes.splice(index, 1);
              swal({
                title: "Exito",
                text: "El cliente ha sido borrado",
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
