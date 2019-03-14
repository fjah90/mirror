@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Usuario | @parent
@stop

@section('header_styles')

<style>
#content {overflow: visible;}
</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Usuarios</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel ">
            <div class="panel-heading">
              <h3 class="panel-title">Usuario {{$usuario->name}}</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Email</label>
                    <span class="form-control">{{$usuario->email}}</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <span class="form-control">{{$usuario->tipo}}</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Firma</label>
                    @if($usuario->firma)
                    <img class="img-responsive" src="{{$usuario->firma}}" alt="Firma" />
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <a href="{{route('usuarios.index')}}" class="btn btn-info">Regresar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
@stop
