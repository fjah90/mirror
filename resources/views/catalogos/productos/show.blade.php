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
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Proveedor</label>
                  <span class="form-control">{{$producto->proveedor->empresa}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Categoria</label>
                  <span class="form-control">{{$producto->subcategoria->nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Tipo de Producto o Servicio</label>
                  <span class="form-control">{{$producto->categoria->nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Producto o Servicio</label>
                  <span class="form-control">{{$producto->nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th colspan="3">Descripciones</th>
                      </tr>
                      <tr>
                        <th>Nombre</th>
                        <th>Name</th>
                        <th>Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($producto->descripciones as $descripcion)
                      <tr>
                        <td>{{$descripcion->descripcionNombre->nombre}}</td>
                        <td>{{$descripcion->descripcionNombre->name}}</td>
                        <td>{{$descripcion->valor}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
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
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label" style="display:block;">
                    Ficha Técnica
                    @if($producto->ficha_tecnica)
                    <a href="{{ $producto->ficha_tecnica }}" target="_blank"
                      class="btn btn-xs btn-info" style="cursor:pointer;">
                      <i class="fas fa-eye"></i>
                    </a>
                    @endif
                  </label>
                </div>
              </div>
              <div class="col-md-4 text-right">
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
