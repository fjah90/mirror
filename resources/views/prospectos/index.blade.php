@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Proyectos | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Proyectos</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            <div class="p-10">
              Lista de Proyectos
              @role('Administrador')
                de 
                <select class="form-control" @change="cargar()" v-model="usuarioCargado" style="width:auto;display:inline-block;">
                  <option value="Todos">Todos</option>
                  @foreach($usuarios as $usuario)
                  <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                  @endforeach
                </select>
              @endrole
            </div>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Usuario</th>
                  <th>Cliente</th>
                  <th>Nombre de Proyecto</th>
                  <th>Ultima Actividad</th>
                  <th>Tipo</th>
                  <th>Próxima Actividad</th>
                  <th>Tipo</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(prospecto, index) in prospectos">
                  <td>@{{index+1}}</td>
                  <td>@{{prospecto.cliente.usuario_nombre}}</td>
                  <td>@{{prospecto.cliente.nombre}}</td>
                  <td>@{{prospecto.nombre}}</td>
                  <template v-if="prospecto.ultima_actividad">
                    <td>@{{prospecto.ultima_actividad.fecha}}</td>
                    <td>@{{prospecto.ultima_actividad.tipo.nombre}}</td>
                  </template>
                  <template v-else>
                    <td></td>
                    <td></td>
                  </template>
                  <template v-if="prospecto.proxima_actividad">
                    <td>@{{prospecto.proxima_actividad.fecha_formated}}</td>
                    <td>@{{prospecto.proxima_actividad.tipo.nombre}}</td>
                  </template>
                  <template v-else>
                    <td></td>
                    <td></td>
                  </template>
                  <td class="text-right">
                    <a class="btn btn-info" title="Ver" :href="'/prospectos/'+prospecto.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-warning" title="Editar" :href="'/prospectos/'+prospecto.id+'/editar'">
                      <i class="far fa-edit"></i>
                    </a>
                    <a class="btn btn-success" title="Cotizar" :href="'/prospectos/'+prospecto.id+'/cotizar'">
                      <i class="far fa-file-alt"></i>
                    </a>
                    <button class="btn btn-danger" title="Borrar" @click="borrar(prospecto, index)">
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
      usuarioCargado: {{auth()->user()->id}},
      tabla: {}
    },
    mounted(){
      this.tabla = $("#tabla").DataTable({"order": [[ 3, "desc" ]]});
    },
    methods: {
      cargar(){
        axios.post('/prospectos/listado', {id: this.usuarioCargado})
        .then(({data}) => {
          this.tabla.destroy();
          this.prospectos = data.prospectos;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tabla = $("#tabla").DataTable({"order": [[ 3, "desc" ]]});
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
      },
      borrar(prospecto, index){
        swal({
          title: 'Cuidado',
          text: "Borrar el Proyecto "+prospecto.nombre+"?",
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
                text: "El Proyecto ha sido borrado",
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
