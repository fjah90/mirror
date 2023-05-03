@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Permisos | @parent
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
        <h1 style="font-weight: bolder;">Roles</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
              <div class="panel">
                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                  <h3 class="panel-title text-right" style="height:30px;">
                    <span class="pull-left p-10">Lista de Roles</span>
                  <a href="{{route('permisos.create')}}" class="btn btn-warning" style="color: #000;">
                      <i class="fa fa-plus"></i> Nuevo Rol
                    </a>
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="tabla" class="table table-bordred" \>
                      <thead>
                        <tr style="background-color:#12160F">
                          <th class="color_text">#</th>
                          <th class="color_text">Nombre</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($roles as $rol)
                        <tr>
                          <td>{{$rol->id}}</td>
                          <td>{{$rol->name}}</td>
                          <td class="text-right">
                            <a class="btn btn-xs btn-info" title="Ver"
                              href="{{route('permisos.show', ['rol'=>$rol->id])}}">
                              <i class="far fa-eye"></i>
                            </a>
                            <!--a class="btn btn-xs btn-success" title="Editar Permisos"
                              href="{{route('permisos.edit', ['rol'=>$rol->id])}}">
                              <i class="fas fa-pencil-alt"></i>
                            </a-->
                             <a class="btn btn-xs btn-success" title="Editar Rol"
                              href="{{route('permisos.edit', ['rol'=>$rol->id])}}">
                              <i class="fas fa-pencil-alt"></i>
                            </a>
                             @hasrole('Administrador')
                                <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                                        @click="borrar(permisos, permisos)">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endhasrole
                          </td>
                        </tr>
                        @endforeach
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
/*const app = new Vue({
    el: '#content',
    data: {
      roles: {!! json_encode($roles) !!},
    },
    mounted(){
      $("#tabla").DataTable({"order": [[ 1, "asc" ]]});
    },
    methods: {
      borrar(permiso, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Rol "+permiso.name+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/permiso/'+permiso.id, {})
            .then(({data}) => {
              this.categorias.splice(index, 1);
              swal({
                title: "Exito",
                text: "El Rol ha sido borrado",
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
});*/
</script> 
@stop
