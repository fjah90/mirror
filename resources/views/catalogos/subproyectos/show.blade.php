@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Tipo Proyecto | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Tipos Proyectos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Ver Tipo {{$subproyecto->nombre}}</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Categoría</label>
                  <span class="form-control">{{$subproyecto->proyecto->nombre}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Nombre</label>
                  <span class="form-control">{{$subproyecto->nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <a class="btn btn-info" href="{{route('subproyectos.index')}}">
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
@stop
