@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Ordenes Compra | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Ordenes De Compra Proyecto {{$ordenes->first()->proyecto_nombre}}</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="p-10 pull-left">Lista de Ordenes</span>
            <a href="{{route('proyectos-aprobados.ordenes-compra.create', $ordenes->first()->proyecto_id)}}"
              class="btn btn-primary" style="color: #fff;">
              <i class="fas fa-plus"></i> Nueva Orden
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordred">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Proveedor</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Estatus</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="orden in ordenes">
                  <td>@{{orden.id}}</td>
                  <td>@{{orden.proveedor_empresa}}</td>
                  <td>
                    <span v-for="(entrada, index) in orden.entradas">
                      @{{index+1}}.- @{{entrada.producto.nombre}} <br />
                    </span>
                  </td>
                  <td>
                    <span v-for="(entrada, index) in orden.entradas">
                      @{{index+1}}.-
                        <span v-if="entrada.conversion">@{{entrada.cantidad_convertida}} @{{entrada.conversion}}</span>
                        <span v-else>@{{entrada.cantidad}} @{{entrada.medida}}</span>
                      <br />
                    </span>
                  </td>
                  <td>@{{orden.status}}</td>
                  <td class="text-right">
                    <a v-if="orden.status!='Pendiente' && orden.status!='Cancelada'"
                      class="btn btn-info" title="Ver"
                      :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a v-if="orden.status=='Pendiente'"
                      class="btn btn-warning" title="Comprar"
                      :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
                      <i class="fas fa-cash-register"></i>
                    </a>
                    <a v-if="orden.status=='Pendiente' || orden.status=='Rechazada'"
                      class="btn btn-success" title="Editar"
                      :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id+'/editar'">
                      <i class="fas fa-edit"></i>
                    </a>
                    @role('Inventado')
                    <button v-if="orden.status=='Por Autorizar'" class="btn btn-primary"
                      title="Aprobar" @click="aprobar.orden_id=orden.id; openAprobar=true;">
                      <i class="far fa-thumbs-up"></i>
                    </button>
                    <button v-if="orden.status=='Por Autorizar'" class="btn btn-danger"
                      title="Rechazar" @click="rechazar.orden_id=orden.id; openRechazar=true;">
                      <i class="far fa-thumbs-down"></i>
                    </button>
                    <button v-if="orden.status!='Aprobada' && orden.status!='Cancelada'" class="btn btn-danger"
                      title="Cancelar" @click="cancelar(orden)">
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
      ordenes: {!! json_encode($ordenes) !!},
      aceptar: {
        orden_id: 0
      },
      rechazar: {
        orden_id: 0,
        motivo:''
      },
      openAceptar: false,
      openRechazar: false
    },
});
</script>
@stop
