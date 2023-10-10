@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Agentes Aduanales | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 style="font-weight: bolder;">Agentes Aduanales</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Agentes Aduanales</span>
            <a href="{{route('agentesAduanales.create')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fa fa-plus"></i> Nuevo Agente
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#fa02a4">
                  <th>#</th>
                  <th>Compañia</th>
                  <th>Contacto</th>
                  <th>Telefono</th>
                  <th>Email</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(agente, index) in agentes">
                  <td>@{{index+1}}</td>
                  <td>@{{agente.compañia}}</td>
                  <td>@{{agente.contacto}}</td>
                  <td>@{{agente.telefono}}</td>
                  <td>@{{agente.email}}</td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/agentesAduanales/'+agente.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/agentesAduanales/'+agente.id+'/editar'" style="background: #fece58 !important;">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(agente, index)">
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
      agentes: {!! json_encode($agentes) !!},
    },
    mounted(){
      $("#tabla").DataTable();
    },
    methods: {
      borrar(agente, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Agente "+agente.compañia+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/agentesAduanales/'+agente.id, {})
            .then(({data}) => {
              this.agentes.splice(index, 1);
              swal({
                title: "Exito",
                text: "El agente ha sido borrado",
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
