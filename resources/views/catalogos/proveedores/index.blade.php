@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Proveedores | @parent
@stop

@section('header_styles')
<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Proveedores</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Proveedores</span>
            <a href="{{route('proveedores.create')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fas fa-plus"></i> Nuevo Proveedor
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordred">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th>RFC</th>
                  <th>Banco</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(proveedor, index) in proveedores">
                  <td>@{{proveedor.id}}</td>
                  <td>@{{proveedor.empresa}}</td>
                  <td>@{{proveedor.telefono}}</td>
                  <td>@{{proveedor.email}}</td>
                  <td>@{{proveedor.rfc}}</td>
                  <td>@{{proveedor.banco}}</td>
                  <td class="text-right">
                    <a class="btn btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/proveedores/'+proveedor.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/proveedores/'+proveedor.id+'/editar'">
                      <i class="far fa-edit"></i>
                    </a>
                    <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(proveedor, index)">
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
      proveedores: {!! json_encode($proveedores) !!},
    },
    methods: {
      borrar(proveedor, index){
        swal({
          title: 'Cuidado',
          text: "Borrar el Proveedor "+proveedor.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/proveedores/'+proveedor.id, {})
            .then(({data}) => {
              this.proveedores.splice(index, 1);
              swal({
                title: "Exito",
                text: "El Proveedor ha sido borrado",
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
