@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Producto | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Productos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Ver Producto {{$producto->nombre}}</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Proveedor</label>
                  <span class="form-control">{{$producto->proveedor->empresa}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Categoria</label>
                  <span class="form-control">{{$producto->categoria->nombre}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Composición</label>
                  <span class="form-control">{{$producto->composicion}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Diseño</label>
                  <span class="form-control">{{$producto->diseño}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Coleccion</label>
                  <span class="form-control">{{$producto->coleccion}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Descripción 1</label>
                  <span class="form-control">{{$producto->descripcion1}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Descripción 2</label>
                  <span class="form-control">{{$producto->descripcion2}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Descripción 3</label>
                  <span class="form-control">{{$producto->descripcion3}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Descripción 4</label>
                  <span class="form-control">{{$producto->descripcion4}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Descripción 5</label>
                  <span class="form-control">{{$producto->descripcion5}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Descripción 6</label>
                  <span class="form-control">{{$producto->descripcion6}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Foto</label>
                  @if($producto->foto)
                  <img class="img-responsive" src="{{$producto->foto}}" alt="Foto" />
                  @endif
                </div>
              </div>
              <div class="col-md-4 col-md-offset-4 text-right">
                <a style="margin-top:25px;" class="btn btn-info" href="{{route('productos.index')}}">
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
