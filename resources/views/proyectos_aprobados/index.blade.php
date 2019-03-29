@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Proyectos Aprobados | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Proyectos Aprobados</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            <span class="p-10">Lista de Proyectos</span>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordred">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Cliente</th>
                  <th>Proyecto</th>
                  <th>Proveedores</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="proyecto in proyectos">
                  <td>@{{proyecto.id}}</td>
                  <td>@{{proyecto.cliente_nombre}}</td>
                  <td>@{{proyecto.proyecto}}</td>
                  <td>
                    <span v-for="(proveedor, index) in proyecto.proveedores">
                      @{{index+1}}.- @{{proveedor}} <br />
                    </span>
                  </td>
                  <td class="text-right">
                    <a class="btn btn-info" title="Ver Cotización"
                      target="_blank" :href="proyecto.cotizacion.archivo">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" title="Ordenes Compra"
                      :href="'/proyectos-aprobados/'+proyecto.id+'/ordenes-compra'"
                      style="font-size:20px; padding:2px 12px;">
                      <i class="fas fa-file-invoice-dollar"></i>
                    </a>
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
      proyectos: {!! json_encode($proyectos) !!},
    },
});
</script>
@stop
