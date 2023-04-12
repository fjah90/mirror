@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Historial de Compra | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Historial de Compra</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Detalle de Historial de Compra</span>
            <span style="visibility:hidden">.</span>
          </h3>
        </div>           
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#06A1CE">
                  <th>#</th>
                  <th>Usuario</th>
                  <th class="text-center">Nombre del Cliente</th>
                  <th class="text-center">Razón Social</th>
                  <th class="text-center">Proyecto</th>
                  <th class="text-center">Cantidad de Proyecto</th>
                  <th class="text-center">Proveedor</th>
                  <th class="text-center">Productos o Servicio</th>
                  <th class="text-center">Tiempo de Entrega</th>
                  <th class="text-center">Fecha de Compra</th>
                  <th class="text-center">Moneda</th>
                  <th class="text-center">Monto del producto</th>
                  <th class="text-center">Monto gastados</th>
                  <th class="text-center">Estatus</th>
                </tr>
              </thead>
              <tbody>
                @foreach($clientes as $key=> $cliente)
                <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$cliente->Cliente->usuario_nombre}}</td>
                  <td>{{$cliente->cliente_nombre}}</td>
                  <td>{{$cliente->Cliente->razon_social}}</td>
                  <td>{{$cliente->proyecto_nombre}}</td>
                  <td>{{$cliente->numero_proyecto}}</td>
                  <td>{{$cliente->proveedor_empresa}}</td>
                  <td></td>
                  <td>{{$cliente->tiempo_entrega}}</td>
                  <td>{{$cliente->fecha_compra}}</td>
                  <td>{{$cliente->moneda}}</td>
                  <td>{{$cliente->monto_total_producto}}</td>
                  <td>{{$cliente->monto_total_pagar}}</td>
                  <td>{{$cliente->status}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
   <div class="row">
      <div class="col-md-12 text-right">
        <a class="btn btn-default" href="{{route('clientes.index')}}">
          Regresar
        </a>
      </div>
    </div>
</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script>

</script>
@stop
