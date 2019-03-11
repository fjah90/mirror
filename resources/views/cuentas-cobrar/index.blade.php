@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Cuentas por Cobrar | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Cuentas por Cobrar</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="p-10">Lista de Cuentas</span>
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
                  <th>Condiciones Pago</th>
                  <th>Moneda</th>
                  <th>Total</th>
                  <th>Facturado</th>
                  <th>Pagado</th>
                  <th>Pendiente</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cuenta, index) in cuentas">
                  <td>@{{cuenta.id}}</td>
                  <td>@{{cuenta.cliente}}</td>
                  <td>@{{cuenta.proyecto}}</td>
                  <td>@{{cuenta.condiciones}}</td>
                  <td>@{{cuenta.moneda}}</td>
                  <td>@{{cuenta.total | formatoMoneda}}</td>
                  <td>@{{cuenta.facturado | formatoMoneda}}</td>
                  <td>@{{cuenta.pagado | formatoMoneda}}</td>
                  <td>@{{cuenta.pendiente | formatoMoneda}}</td>
                  <td class="text-right">
                    <a class="btn btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/cuentas-cobrar/'+cuenta.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-success" data-toggle="tooltip" title="Facturas"
                      :href="'/cuentas-cobrar/'+cuenta.id+'/editar'"
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
      cuentas: {!! json_encode($cuentas) !!},
    },
    filters: {
      formatoMoneda(numero){
        return accounting.formatMoney(numero, "$", 2);
      }
    }
});
</script>
@stop
