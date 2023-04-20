@extends('layouts/default')

{{-- Page title --}}
@section('title')
   Historial de Compra | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header" style="background-color:#12160F; color:#B68911;">
    <h1 style="font-weight: bolder;">Historial de Compra</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Detalle Historial de Compra</h3>
          </div>
          <div class="panel-body">
            <form class="">
            </form>
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

</script>
@stop
