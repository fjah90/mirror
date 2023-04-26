@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Rol de Usuario | @parent
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
  <section class="content-header" style="background-color:#12160F; color:#B68911;">
    <h1>Ver Rol</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Rol
            @foreach($roles as $rol)
             {{$rol->name}}</h3>
             @endforeach
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Nombre</label>
                  @foreach ($roles as $rol)
                  <span class="form-control">{{$rol->name}}</span>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <a class="btn btn-default" href="{{route('usuarios.permisos')}}" style="color:#000; background-color:#B3B3B3;">
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
