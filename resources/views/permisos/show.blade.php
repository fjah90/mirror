@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Rol | @parent
@stop

@section('header_styles')

<style>
#content {overflow: visible;}
  .color_text{
    color:#B3B3B3;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#caa678;">
        <h1 style="font-weight: bolder;">Roles</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel">
            <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
              <h3 class="panel-title">Rol</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    @foreach($roles as $rol)
                      <span class="form-control">{{$rol->name}}</span>
                    @endforeach
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{route('permisos.usuarios')}}" class="btn btn-default" style="color:#000; background-color:#B3B3B3;">Regresar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
@stop

                       