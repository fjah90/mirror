@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Cuentas por Pagar | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Cuentas por Pagar</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            <span class="p-10">Lista de Cuentas</span>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordred">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Proveedor</th>
                  <th>Proyecto</th>
                  <th>Dias Credito</th>
                  <th>Moneda</th>
                  <th>Total</th>
                  <th>Facturado</th>
                  <th>Pagado</th>
                  <th>Pendiente</th>
                  <th style="min-width:70px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cuenta, index) in cuentas">
                  <td>@{{index+1}}</td>
                  <td>@{{cuenta.proveedor_empresa}}</td>
                  <td>@{{cuenta.proyecto_nombre}}</td>
                  <td>@{{cuenta.dias_credito}}</td>
                  <td>@{{cuenta.moneda}}</td>
                  <td>@{{cuenta.total | formatoMoneda}}</td>
                  <td>@{{cuenta.facturado | formatoMoneda}}</td>
                  <td>@{{cuenta.pagado | formatoMoneda}}</td>
                  <td>@{{cuenta.pendiente | formatoMoneda}}</td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/cuentas-pagar/'+cuenta.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Facturas"
                      :href="'/cuentas-pagar/'+cuenta.id+'/editar'">
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
