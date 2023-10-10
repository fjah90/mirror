@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Unidades Medida | @parent
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
<section class="content-header" style="background-color:#12160F; color:#B68911;">
    <h1>Unidades de Medida</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Unidades de Medida</span>
            <span style="visibility:hidden">.</span>
            @hasrole('Administrador')
            <a href="{{route('unidadesMedida.create')}}" class="btn btn-warning" style="color: #000;">
              <i class="fa fa-plus"></i> Nueva Unidad
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
                  <th class="color_text">Unidad</th>
                  <th class="color_text">Unidad Ingles</th>
                  <th class="color_text">Conversiones</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(unidad, index) in unidades">
                  <td>@{{index+1}}</td>
                  <td>@{{unidad.simbolo}} - @{{unidad.nombre}}</td>
                  <td>@{{unidad.simbolo_ingles}} - @{{unidad.nombre_ingles}}</td>
                  <td>
                    <div v-for="(conversion, indice) in unidad.conversiones">
                      @{{indice+1}}. @{{conversion.unidad_conversion_simbolo}}: @{{conversion.factor_conversion}}
                    </div>
                  </td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/unidadesMedida/'+unidad.id">
                      <i class="far fa-eye"></i>
                    </a>
                    @role('Administrador')
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/unidadesMedida/'+unidad.id+'/editar'" style="background: #fece58 !important;">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button class="btn btn-xs btn-danger"  title="Borrar"
                      @click="borrar(unidad, index)">
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
      unidades: {!! json_encode($unidades) !!},
    },
    mounted(){
     $("#tabla").DataTable({"order": [[ 1, "asc" ]]});
},
methods: {
      borrar(unidad, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Unidad "+unidad.simbolo+" "+unidad.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
           axios.delete('/unidadesMedida/'+unidad.id, {})
            .then(({data}) => {
              this.unidades.splice(index, 1);
              swal({
                title: "Exito",
                text: "La unidad ha sido borrado",
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
