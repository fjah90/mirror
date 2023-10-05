@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Historial de Compra | @parent
@stop

@section('header_styles')
<style>
  .color_text{
    color:#B3B3B3;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header" style="background-color:#12160F; color:#caa678;">
    <h1>Historial de Compra</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Detalle de Historial de Compra</span>
            <span style="visibility:hidden">.</span>
          </h3>
        </div> <br>          
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#12160F">
                  <th class="color_text">#</th>
                  <th class="color_text">Usuario</th>
                  <th class="text-center color_text">Nombre del Cliente</th>
                  <th class="text-center color_text">Razón Social</th>
                  <th class="text-center color_text">Proyecto</th>
                  <th class="text-center color_text">Cantidad de Proyecto</th>
                  <th class="text-center color_text">Proveedor</th>
                  <th class="text-center color_text">Productos o Servicio</th>
                  <th class="text-center color_text">Tiempo de Entrega</th>
                  <th class="text-center color_text">Fecha de Compra</th>
                  <th class="text-center color_text">Moneda</th>
                  <th class="text-center color_text">Monto del producto</th>
                  <th class="text-center color_text">Monto gastados</th>
                  <th class="text-center color_text">Estatus</th>
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
                  <td>{{$cliente->nombre_producto}}</td>
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
        <a class="btn btn-default" href="{{route('clientes.index')}}" style="color:#000; background-color:#B3B3B3;">
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
