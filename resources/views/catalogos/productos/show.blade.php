@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Producto | @parent
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
        <h1>Productos</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel ">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h3 class="panel-title">Ver Producto {{ $producto->nombre }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Proveedor</label>
                                    <span class="form-control">{{ $producto->proveedor->empresa }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Categoria</label>
                                    <span class="form-control">{{ $producto->subcategoria->nombre }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Producto o Servicio</label>
                                    <span class="form-control">{{ $producto->categoria->nombre }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Código de Producto o Servicio</label>
                                    <span class="form-control">{{ $producto->nombre }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre del material</label>
                                    <span class="form-control">{{ $producto->nombre_material }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Color</label>
                                    <span class="form-control">{{ $producto->color }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Precio Compra</label>
                                    <span class="form-control">${{ $producto->precio_unitario }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Precio Residencial</label>
                                    <span class="form-control">${{ $producto->precio_residencial }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Precio Comercial</label>
                                    <span class="form-control">${{ $producto->precio_comercial }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Precio Distribuidor</label>
                                    <span class="form-control">${{ $producto->precio_distribuidor }}</span>
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
                                                <th>Código</th>
                                                <th>Name</th>
                                                <th>Valor</th>
                                                <th>Valor Ingles</th>
                                                <th>Iconos</th>
                                                <th>Icono Visible</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($producto->descripciones as $descripcion)
                                                <tr>
                                                    <td>{{ $descripcion->descripcionNombre->nombre }}</td>
                                                    <td>{{ $descripcion->descripcionNombre->name }}</td>
                                                    <td>{{ $descripcion->valor }}</td>
                                                    <td>{{ $descripcion->valor_ingles }}</td>
                                                    <td>
                                                        <table>
                                                            @if ($descripcion->descripcionNombre->nombre == 'Flamabilidad')
                                                                <td><img src="{{ asset('images/icon-fire.png') }}"
                                                                        id="Flamabilidad" style="width:50px; height:50px;">
                                                                </td>
                                                            @elseif($descripcion->descripcionNombre->nombre == 'Abrasión')
                                                                <td><img src="{{ asset('images/icon-physical.png') }}"
                                                                        id="Abrasion" style="width:50px; height:50px;">
                                                                </td>
                                                            @elseif($descripcion->descripcionNombre->nombre == 'Decoloracion a la luz')
                                                                <td><img src="{{ asset('images/icon-lightfastness.png') }}"
                                                                        id="Decoloracion_de_luz"
                                                                        style="width:50px; height:50px;"></td>
                                                            @elseif($descripcion->descripcionNombre->nombre == 'Traspaso de color')
                                                                <td><img src="{{ asset('images/icon-crocking.png') }}"
                                                                        id="Traspaso_de_color"
                                                                        style="width:50px; height:50px;"></td>
                                                            @elseif($descripcion->descripcionNombre->nombre == 'Peeling')
                                                                <td><img src="{{ asset('images/icon-physical.png') }}"
                                                                        id="Peeling" style="width:50px; height:50px;">
                                                                </td>
                                                            @endif
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <table>
                                                            @if ($descripcion->descripcionNombre->nombre == 'Flamabilidad' ||
                                                            $descripcion->descripcionNombre->nombre == 'Abrasión' ||
                                                            $descripcion->descripcionNombre->nombre == 'Decoloración a la luz'||
                                                            $descripcion->descripcionNombre->nombre == 'Traspaso de color'||
                                                            $descripcion->descripcionNombre->nombre == 'Peeling'
                                                            )
                                                                <td>
                                                                    <div class="form-check form-switch">
                                                                        @if ($descripcion->icono_visible)
                                                                            <i class="glyphicon glyphicon-check"
                                                                                disabled></i>
                                                                        @else
                                                                            <i class="glyphicon glyphicon-unchecked"
                                                                                disabled></i>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            @endif
                                                        </table>
                                                    </td>
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
                                    @if ($producto->foto)
                                        <img class="img-responsive" src="{{ $producto->foto }}" alt="Foto" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" style="display:block;">
                                        Ficha Técnica
                                        @if ($producto->ficha_tecnica)
                                            <a href="{{ $producto->ficha_tecnica }}" target="_blank"
                                                class="btn btn-md btn-dark"
                                                style="cursor:pointer; color:#B68911; background-color:#000">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <a style="margin-top:25px; color:#000; background-color:#B3B3B3" class="btn btn-default"
                                    href="{{ route('productos.index') }}">
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
    <script></script>
@stop
