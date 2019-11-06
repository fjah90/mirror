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
  <tabs v-model="activeTab">
    <tab title="Nacionales">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title text-right">
                <span class="pull-left p-10">Lista de Proveedores Nacionales</span>
                <a href="{{route('proveedores.createNacional')}}" class="btn btn-primary" style="color: #fff;">
                  <i class="fas fa-plus"></i> Proveedor
                </a>
              </h3>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table id="tablaNacionales" class="table table-bordred" style="width:100%;"
                  data-page-length="100">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Tipo</th>
                      <th>Empresa</th>
                      <th>Numero Cliente</th>
                      <th>RFC</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(proveedor, index) in proveedoresNacionales">
                      <td>@{{index+1}}</td>
                      <td>@{{proveedor.tipo.nombre}}</td>
                      <td>@{{proveedor.empresa}}</td>
                      <td>@{{proveedor.numero_cliente}}</td>
                      <td>@{{proveedor.identidad_fiscal}}</td>
                      <td class="text-right">
                        <a class="btn btn-info" title="Ver" :href="'/proveedores/'+proveedor.id">
                          <i class="far fa-eye"></i>
                        </a>
                        <a class="btn btn-success" title="Editar" :href="'/proveedores/'+proveedor.id+'/editar'">
                          <i class="far fa-edit"></i>
                        </a>
                        <button class="btn btn-danger" title="Borrar" @click="borrar('Nacionales',proveedor, index)">
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
    </tab>

    <tab title="Extranjeros">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title text-right">
                <span class="pull-left p-10">Lista de Proveedores Extranjeros</span>
                <a href="{{route('proveedores.createInternacional')}}" class="btn btn-brown" style="color: #fff;">
                  <i class="fas fa-plus"></i> Proveedor
                </a>
              </h3>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table id="tablaExtranjeros" class="table table-bordred" style="width:100%;"
                  data-page-length="100">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Tipo</th>
                      <th>Empresa</th>
                      <th>Numero Cliente</th>
                      <th>TAX ID NO</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(proveedor, index) in proveedoresExtranjeros">
                      <td>@{{index+1}}</td>
                      <td>@{{proveedor.tipo.nombre}}</td>
                      <td>@{{proveedor.empresa}}</td>
                      <td>@{{proveedor.numero_cliente}}</td>
                      <td>@{{proveedor.identidad_fiscal}}</td>
                      <td class="text-right">
                        <a class="btn btn-info" title="Ver"
                          :href="'/proveedores/'+proveedor.id">
                          <i class="far fa-eye"></i>
                        </a>
                        <a class="btn btn-success" title="Editar"
                          :href="'/proveedores/'+proveedor.id+'/editar'">
                          <i class="far fa-edit"></i>
                        </a>
                        <button class="btn btn-danger" title="Borrar"
                          @click="borrar('Extranjeros', proveedor, index)">
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
      proveedoresNacionales: {!! json_encode($proveedoresNacionales) !!},
      proveedoresExtranjeros: {!! json_encode($proveedoresExtranjeros) !!},
      activeTab: {{$tab}},
    },
    mounted(){
      $("#tablaNacionales").DataTable({
        "order": [[ 1, "asc" ]],
        "columnDefs": [
          { "width": "200px", "targets": 4 }
        ]
      });
      $("#tablaExtranjeros").DataTable({
        "order": [[ 1, "asc" ]],
        "columnDefs": [
          { "width": "200px", "targets": 4 }
        ]
      });
    },
    methods: {
      borrar(tipo, proveedor, index){
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
              this['proveedores'+tipo].splice(index, 1);
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
