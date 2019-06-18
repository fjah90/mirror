@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nueva Categoria | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Categorias Productos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nueva Categoria</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="categoria.nombre" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Name</label>
                    <input type="text" class="form-control" name="name" v-model="categoria.name" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Categoria
                  </button>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-12">
                <h4>Agregar Descripciones</h4>
                <hr />
              </div>
            </div>
            <form class="" @submit.prevent="agregarDescripcion()">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="descripcion.nombre" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Name</label>
                    <input type="text" class="form-control" name="name" v-model="descripcion.name" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" style="cursor:pointer;"
                      @click="descripcion.no_alta_productos=!descripcion.no_alta_productos">
                      <i class="text-info far"
                        :class="(descripcion.no_alta_productos)?'fa-check-square':'fa-square'">
                      </i>
                      No Alta Productos
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-info">
                    <i class="fas fa-save"></i>
                    Agregar Descripcion
                  </button>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Name</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(descripcion, index) in categoria.descripciones">
                        <td>@{{descripcion.nombre}}</td>
                        <td>@{{descripcion.name}}</td>
                        <td class="text-right">
                          <button class="btn btn-success" data-toggle="tooltip" title="Editar"
                            @click="editarDescripcion(descripcion, index)">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                            @click="borrarDescripcion(descripcion, index)">
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
      categoria:{
        nombre: '',
        name: '',
        descripciones: []
      },
      descripcion:{
        nombre: '',
        name: '',
        no_alta_productos:false
      },
      cargando: false,
    },
    methods: {
      agregarDescripcion(){
        if(this.descripcion.nombre=="" && this.descripcion.name==""){
          swal({
            title: "Atención",
            text: "Debe llenar el nombre o name",
            type: "warning"
          });
          return false;
        }

        this.categoria.descripciones.push(this.descripcion);
        this.descripcion = {nombre: '', name: '', no_alta_productos:false};
      },
      editarDescripcion(descripcion, index){
        this.descripcion = descripcion;
        this.categoria.descripciones.splice(index, 1);
      },
      borrarDescripcion(descripcion, index){
        this.categoria.descripciones.splice(index, 1);
      },
      guardar(){
        this.cargando = true;
        axios.post('/categorias', this.categoria)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Categoria Guardada",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/categorias";
          });
        })
        .catch(({response}) => {
          console.error(response);
          this.cargando = false;
          swal({
            title: "Error",
            text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
            type: "error"
          });
        });
      },//fin cargarPresupuesto
    }
});
</script>
@stop
