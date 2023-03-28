@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Categoria Clientes | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Categorias Clientes</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Categoria del Cliente</span>
            <span style="visibility:hidden">.</span>
            @hasrole('Administrador')
            <a href="{{route('categoriaClientes.create')}}" class="btn btn-primary" style="color: #fff;">
              <i class="fa fa-plus"></i> Nuevo Tipo
            </a>
            @endhasrole
          </h3>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<!--script>
const app = new Vue({
    el: '#content',
    data: {
      tipos: {!! json_encode($tipos) !!},
    },
    mounted(){
      $("#tabla").DataTable({"order": [[ 1, "asc" ]]});
    },
    methods: {
      borrar(tipo, index){
        swal({
          title: 'Cuidado',
          text: "Borrar Tipo "+tipo.nombre+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/tiposClientes/'+tipo.id, {})
            .then(({data}) => {
              this.tipos.splice(index, 1);
              swal({
                title: "Exito",
                text: "El tipo ha sido borrado",
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
</script-->
@stop
