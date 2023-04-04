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
        <h1 style="font-weight:bolder;">Historial de Compra</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
         <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">lista de Historial de Compra</span>
            <a href="#" class="btn btn-primary" style="color: #fff;">
              <i class="fa fa-plus"></i> 
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="100">
              <thead>
                <tr style="background-color:#fa02a4">
                 
                </tr>
              </thead>
              <tbody>
                
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
      
    </script>
@stop