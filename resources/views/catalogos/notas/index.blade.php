@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Notas | @parent
@stop

@section('header_styles')
    <style>
        .color_text {
            color: #B3B3B3;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header" style="background-color:#12160F; color:#B68911;">
  <h1>PRODUCTOS</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title text-right">
            <span class="pull-left p-10">Lista de Productos</span>

              <button type="submit" class="btn btn-dark" style="background-color:#FFCE56; color:#12160F;">
                <a href="{{route('productos.index')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>ACTIVOS
                </a>
              </button>
              <button type="submit" class="btn btn-dark" style="background-color:#FFCE56; color:#12160F;">
                <a href="{{route('productos.inactivo')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>INACTIVOS
                </a>
              </button>
            <a href="{{route('productos.create2')}}" class="btn btn-warning" style="color: #000;">
              <i class="fas fa-plus"></i> Carga masiva
            </a>
            <a href="{{route('productos.create')}}" class="btn btn-warning" style="color: #000;">
              <i class="fas fa-plus"></i> Nuevo Producto
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred"  style="width:100%;"
            data-page-length="20">
              <thead>
                <tr style="background-color:#12160F">
                  <th class="color_text">#</th>
                  <th class="color_text">Código de Producto o Servicio</th>
                  <th class="color_text">Proveedor</th>
                  <th class="color_text">Categoria</th>
                  <th class="color_text">Tipo</th>
                  <th style="min-width:70px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(producto, index) in productos">
                  <td>@{{index+1}}</td>
                  <td>@{{producto.nombre}}</td>
                  <td>@{{producto.proveedor.empresa}}</td>
                  <td>@{{producto.subcategoria.nombre}}</td>
                  <td>@{{producto.categoria.nombre}}</td>
                  <td class="text-right col-md-3">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/productos/'+producto.id">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                      :href="'/productos/'+producto.id+'/editar'">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a v-if="producto.status =='ACTIVO'" :href="'/productos/'+producto.id+'/desactivar'" class="btn btn-xs label-default float-left" data-toggle="tooltip" data-placement="top" title="Desactivar">
                      <i class="fas fa-ban"></i>
                    </a>
                    <a v-else ="producto.status =='INACTIVO'" :href="'/productos/'+producto.id+'/activar'" class="btn btn-xs btn-success float-left" data-toggle="tooltip" data-placement="top" title="Activar">
                      <i class="fas fa-check"></i>
                    </a>

                    @hasrole('Administrador')
                    <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                      @click="borrar(producto, index)">
                      <i class="fas fa-times"></i>
                    </button>
                    @endhasrole
                  </td>
                </tr>
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
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Notas</div>

                    <div class="card-body">
                        <a href="{{ route('notas.create') }}" class="btn btn-primary mb-3">Crear nota</a>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Contenido</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notas as $nota)
                                    <tr>
                                        <td>{{ $nota->id }}</td>
                                        <td>{{ $nota->titulo }}</td>
                                        <td>{{ $nota->contenido }}</td>
                                        <td>{{ $nota->created_at }}</td>
                                        <td>{{ $nota->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('notas.show', $nota->id) }}"
                                                class="btn btn-sm btn-info">Ver</a>
                                            <a href="{{ route('notas.edit', $nota->id) }}"
                                                class="btn btn-sm btn-warning">Editar</a>
                                            <form action="{{ route('notas.destroy', $nota->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
