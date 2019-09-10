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
  <tabs>
    <tab title="Nacionales">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Clientes Nacionales</span>
            <a href="{{route('clientes.createNacional')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fas fa-plus"></i> Cliente Nacional
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tablaNac" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr>
                  <th>Usuario</th>
                  <th>Tipo</th>
                  <th>Nombre</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cliente, index) in clientesNacionales">
                  <td>@{{cliente.usuario_nombre}}</td>
                  <td>@{{cliente.tipo.nombre}}</td>
                  <td>@{{cliente.nombre}}</td>
                  <td>@{{cliente.telefono}}</td>
                  <td>@{{cliente.email}}</td>
                  <td class="text-right">
                    <a class="btn btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/clientes/'+cliente.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/clientes/'+cliente.id+'/editar'">
                      <i class="far fa-edit"></i>
                    </a>
                    @hasrole('Administrador')
                    <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(cliente, index)">
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
    </tab>

    <tab title="Extranjeros">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Clientes Extranjeros</span>
            <a href="{{route('clientes.createInternacional')}}" class="btn btn-brown" style="color: #fff;">
              <i class="fas fa-plus"></i> Cliente Extranjero
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tablaExt" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr>
                  <th>Usuario</th>
                  <th>Tipo</th>
                  <th>Nombre</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cliente, index) in clientesExtranjeros">
                  <td>@{{cliente.usuario_nombre}}</td>
                  <td>@{{cliente.tipo.nombre}}</td>
                  <td>@{{cliente.nombre}}</td>
                  <td>@{{cliente.telefono}}</td>
                  <td>@{{cliente.email}}</td>
                  <td class="text-right">
                    <a class="btn btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/clientes/'+cliente.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/clientes/'+cliente.id+'/editar'">
                      <i class="far fa-edit"></i>
                    </a>
                    @hasrole('Administrador')
                    <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(cliente, index)">
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
    </tab>
  </tabs>
</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script>
const app = new Vue({
    el: '#content',
    data: {
      clientesNacionales: {!! json_encode($clientesNacionales) !!},
      clientesExtranjeros: {!! json_encode($clientesExtranjeros) !!},
    },
    mounted(){
      $("#tablaNac").DataTable();
      $("#tablaExt").DataTable();
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
