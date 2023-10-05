@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Diseñador | @parent
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
    <h1>Ver Diseñador</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading"  style="background-color:#12160F; color:#caa678;">
            <h3 class="panel-title">Diseñador {{$vendedor->nombre}}</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Nombre</label>
                  <span class="form-control">{{$vendedor->nombre}}</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Presupuesto Anual</label>
                  <span class="form-control">{{$vendedor->presupuesto_anual}}</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">% Comisión Base</label>
                  <span class="form-control">{{$vendedor->comision_base}}</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">¿Cuándo se realizará el pago de la comisión?</label>
                  <span class="form-control">{{$vendedor->nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <a class="btn btn-default" href="{{route('vendedores.index')}}" style="color:#000; background-color:#B3B3B3">
                  Regresar
                </a>
              </div>
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

<!-- <script>
</script> -->
@stop
