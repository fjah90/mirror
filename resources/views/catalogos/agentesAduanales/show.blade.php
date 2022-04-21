@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Agente Aduanal | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 style="font-weight: bolder;">Ver Agente Aduanal</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Agente Aduanal {{$agente->compañia}}</h3>
          </div>
          <div class="panel-body">
            <div class="row form-group">
              <div class="col-md-12">
                <label class="control-label">Direccion</label>
                <span class="form-control">{{$agente->direccion}}</span>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-4">
                <label class="control-label">Contacto</label>
                <span class="form-control">{{$agente->contacto}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">Telefono</label>
                <span class="form-control">{{$agente->telefono}}</span>
              </div>
              <div class="col-md-4">
                <label class="control-label">Email</label>
                <span class="form-control">{{$agente->email}}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <a class="btn btn-default" href="{{route('agentesAduanales.index')}}">
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
