@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Prospectos | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Prospectos</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Prospectos</span>
            <a href="{{route('prospectos.create')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fas fa-plus"></i> Nuevo Prospecto
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordred">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Cliente</th>
                  <th>Nombre</th>
                  <th>Ultima Actividad</th>
                  <th>Tipo</th>
                  <th>Próxima Actividad</th>
                  <th>Tipo</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(prospecto, index) in prospectos">
                  <td>@{{prospecto.id}}</td>
                  <td>@{{prospecto.nombre}}</td>
                  <td>@{{prospecto.cliente.nombre}}</td>
                  <td>@{{prospecto.ultima_actividad.fecha_formated}}</td>
                  <td>@{{prospecto.ultima_actividad.tipo.nombre}}</td>
                  <td>@{{prospecto.proxima_actividad.fecha_formated}}</td>
                  <td>@{{prospecto.proxima_actividad.tipo.nombre}}</td>
                  <td class="text-right">
                    <a class="btn btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/prospectos/'+prospecto.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/prospectos/'+prospecto.id+'/editar'">
                      <i class="far fa-edit"></i>
                    </a>
                    <a class="btn btn-warning" data-toggle="tooltip" title="Cotizar"
                      :href="'/prospectos/'+prospecto.id+'/cotizar'">
                      <i class="fas fa-balance-scale"></i>
                    </a>
                    <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(prospecto, index)">
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
      prospectos: {!! json_encode($prospectos) !!},
    },
    methods: {
      borrar(prospecto, index){
        swal({
          title: 'Cuidado',
          text: "Borrar el Prospecto "+prospecto.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/prospectos/'+prospecto.id, {})
            .then(({data}) => {
              this.prospectos.splice(index, 1);
              swal({
                title: "Exito",
                text: "El prospecto ha sido borrado",
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
