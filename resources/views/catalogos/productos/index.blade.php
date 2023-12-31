@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Productos | @parent
@stop

@section('header_styles')
<style>
  .marg025 {margin: 0 25px;}
  #tabla_length{
    float: right !important;
  }
  .color_text{
    color:#B3B3B3;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header" style="background-color:#12160F; color:#B68911;">
  <h1>PRODUCTOS</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Productos</span>

              <button type="submit" class="btn btn-dark" style="background-color:#B08C62; color:#12160F;">
                <a href="{{route('productos.index')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>ACTIVOS
                </a>
              </button>
              <button type="submit" class="btn btn-dark" style="background-color:#B08C62; color:#12160F;">
                <a href="{{route('productos.inactivo')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>INACTIVOS
                </a>
              </button>
            <a href="{{route('productos.create2')}}" class="btn btn-warning" style="color: #000;">
              <i class="fas fa-plus"></i> Carga masiva
            </a>
            <a href="{{route('productos.create')}}" class="btn btn-warning" style="color: #000;">
              <i class="fas fa-plus"></i> Nuevo Producto
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred"  style="width:100%;"
            data-page-length="20">
              <thead>
                <tr style="background-color:#12160F">
                  <th class="color_text">#</th>
                  <th class="color_text">Código de Producto o Servicio</th>
                  <th class="color_text">Proveedor</th>
                  <th class="color_text">Categoria</th>
                  <th class="color_text">Tipo</th>
                  <th style="min-width:70px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(producto, index) in productos">
                  <td>@{{index+1}}</td>
                  <td>@{{producto.nombre}}</td>
                  <td>@{{producto.proveedor.empresa}}</td>
                  <td>@{{producto.subcategoria.nombre}}</td>
                  <td>@{{producto.categoria.nombre}}</td>
                  <td class="text-right col-md-3">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/productos/'+producto.id">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/productos/'+producto.id+'/editar'" style="background: #fece58 !important;">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a v-if="producto.status =='ACTIVO'" :href="'/productos/'+producto.id+'/desactivar'" class="btn btn-xs label-default float-left" data-toggle="tooltip" data-placement="top" title="Desactivar">
                      <i class="fas fa-ban"></i>
                    </a>
                    <a v-else ="producto.status =='INACTIVO'" :href="'/productos/'+producto.id+'/activar'" class="btn btn-xs btn-success float-left" data-toggle="tooltip" data-placement="top" title="Activar">
                      <i class="fas fa-check"></i>
                    </a>

                    @hasrole('Administrador')
                    <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(producto, index)">
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
      productos: {!! json_encode($productos) !!},
    },
    mounted(){
      $("#tabla").DataTable({
        "order": [[ 1, "asc" ]],
        "paging": true,
        "columnDefs": [
          { "width": "120px", "targets": 4 }
        ]
      });
    },
    methods: {
      borrar(producto, index){
        swal({
          title: 'Cuidado',
          text: "Borrar este producto '"+producto.nombre+"' puede afectar cotizaciones, ¿Desea continuar?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/productos/'+producto.id, {})
            .then(({data}) => {
              this.productos.splice(index, 1);
              swal({
                title: "Exito",
                text: "El producto ha sido borrado",
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
