@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Producto | @parent
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
                        <h3 class="panel-title">Editar Productos</h3>
                    </div>
                    <div class="panel-body">
                        <form class="" @submit.prevent="guardar()">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Proveedor<strong style="color: grey">
                                                *</strong></label>
                                        <select class="form-control" name="proveedor_id" v-model='producto.proveedor_id'
                                            required>
                                            <option value="0">Por Definir</option>
                                            @foreach ($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}">{{ $proveedor->empresa }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Categoria</label>
                                        <select class="form-control" name="subcategoria_id"
                                            v-model='producto.subcategoria_id'>
                                            <option value=""></option>
                                            @foreach ($subcategorias as $subcategoria)
                                                <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Tipo de Producto o Servicio<strong style="color: grey">
                                                *</strong></label>
                                        <select class="form-control" name="categoria_id" v-model='producto.categoria_id'
                                            @change="cambiarDescripciones()" required>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Código de Producto o Servicio<strong
                                                style="color: grey"> *</strong></label>
                                        <input type="text" class="form-control" name="nombre" v-model="producto.nombre"
                                            required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Nombre del material<strong style="color: grey">
                                                *</strong></label>
                                        <input type="text" class="form-control" name="nombre"
                                            v-model="producto.nombre_material" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Color<strong style="color: grey">
                                                *</strong></label>
                                        <input type="text" class="form-control" name="color" v-model="producto.color"
                                            required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Precio Compra <strong style="color: grey">
                                                *</strong></label>
                                        <input type="text" class="form-control" name="precio_unitario"
                                            v-model="producto.precio_unitario" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Precio Residencial <strong style="color: grey">
                                                *</strong></label>
                                        <input type="text" class="form-control" name="precio_residencial"
                                            v-model="producto.precio_residencial" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Precio Comercial <strong style="color: grey">
                                                *</strong></label>
                                        <input type="text" class="form-control" name="precio_comercial"
                                            v-model="producto.precio_comercial" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Precio Distribuidor <strong style="color: grey">
                                                *</strong></label>
                                        <input type="text" class="form-control" name="precio_distribuidor"
                                            v-model="producto.precio_distribuidor" required />
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
                                                    <th>Valor Ingles</th>
                                                    <th>Iconos</th>
                                                    <th>Icono Visible</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(descripcion, index) in producto.descripciones">
                                                    <td>@{{ descripcion.nombre }}</td>
                                                    <td>@{{ descripcion.name }}</td>
                                                    <td>
                                                        <input v-if="!descripcion.no_alta_productos" type="text"
                                                            class="form-control" v-model="descripcion.valor" />
                                                    </td>
                                                    <td>
                                                        <input v-if="descripcion.descripcion_nombre.valor_ingles"
                                                            type="text" class="form-control"
                                                            v-model="descripcion.valor_ingles" />
                                                    </td>
                                                    <td>
                                                        <div v-if="descripcion.descripcion_nombre.nombre=='Flamabilidad'">
                                                            <img src="{{ asset('images/icon-fire.png') }}"
                                                                id="Flamabilidad" style="width:50px; height:50px;">
                                                        </div>
                                                        <div v-else-if="descripcion.descripcion_nombre.nombre=='Abrasión'">
                                                            <img src="{{ asset('images/icon-abrasion.jpg') }}"
                                                                id="Abrasion" style="width:48px; height:48px;">
                                                        </div>
                                                        <div
                                                            v-else-if="descripcion.descripcion_nombre.nombre=='Decoloración a la luz'">
                                                            <img src="{{ asset('images/icon-lightfastness.png') }}"
                                                                id="Decoloracion_de_luz" style="width:50px; height:50px;">
                                                        </div>
                                                        <div
                                                            v-else-if="descripcion.descripcion_nombre.nombre=='Traspaso de color'">
                                                            <img src="{{ asset('images/icon-crocking.png') }}"
                                                                id="Traspaso de color_color"
                                                                style="width:50px; height:50px;">
                                                        </div>
                                                        <div v-else-if="descripcion.descripcion_nombre.nombre=='Peeling'">
                                                            <img src="{{ asset('images/icon-physical.png') }}"
                                                                id="Peeling" style="width:50px; height:50px;">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div v-if="descripcion.descripcion_nombre.nombre == 'Flamabilidad' ||
                                                                    descripcion.descripcion_nombre.nombre== 'Abrasión' ||
                                                                    descripcion.descripcion_nombre.nombre== 'Decoloración a la luz' ||
                                                                    descripcion.descripcion_nombre.nombre== 'Traspaso de color' ||
                                                                    descripcion.descripcion_nombre.nombre== 'Peeling'"
                                                            class="form-check form-switch">
                                                            <i v-if="descripcion.icono_visible == 1"
                                                                class="glyphicon glyphicon-check"
                                                                @click="chageVisibility(descripcion)"></i>
                                                            </i>
                                                            <i v-else 
                                                                class="glyphicon glyphicon-unchecked"
                                                                @click="chageVisibility(descripcion)"></i>
                                                            </i>
                                                            <input class="form-control" type="hidden"
                                                                name="icono_visible"
                                                                v-model="descripcion.icono_visible" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" style="display:block;">Foto</label>
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input id="foto" name="foto" type="file" ref="foto"
                                                    @change="fijarArchivo('foto')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label" style="display:block;">
                                            Ficha Técnica
                                            <a v-if="producto.ficha_ori" :href="producto.ficha_ori" target="_blank"
                                                class="btn btn-md btn-dark"
                                                style="cursor:pointer; background-color:#12160F; color:#B68911;">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </label>
                                        <div class="file-loading">
                                            <input id="ficha_tecnica" name="ficha_tecnica" type="file"
                                                ref="ficha_tecnica" @change="fijarArchivo('ficha_tecnica')" />
                                        </div>
                                        <div id="ficha_tecnica-file-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-default" href="{{ route('productos.index') }}"
                                        style="margin-right: 20px; color:#000; background-color:#B3B3B3;">
                                        Regresar
                                    </a>
                                    <button type="submit" class="btn btn-dark" :disabled="cargando"
                                        style="background-color:#12160F; color:#B68911;">
                                        <i class="fas fa-save"></i>
                                        Actualizar Producto
                                    </button>
                                </div>
                            </div>
                        </form>
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
        Vue.config.devtools = true;

        const app = new Vue({
            el: '#content',
            data: {
                producto: {
                    proveedor_id: '{{ $producto->proveedor_id ?? 0 }}',
                    categoria_id: '{{ $producto->categoria_id }}',
                    subcategoria_id: '{{ $producto->subcategoria->id }}',
                    nombre: '{{ $producto->nombre }}',
                    nombre_material: '{{ $producto->nombre_material }}',
                    color: '{{ $producto->color }}',
                    precio_unitario: '{{ $producto->precio_unitario }}',
                    precio_residencial: '{{ $producto->precio_residencial }}',
                    precio_comercial: '{{ $producto->precio_comercial }}',
                    precio_distribuidor: '{{ $producto->precio_distribuidor }}',
                    descripciones: {!! $producto->descripciones !!},
                    foto_ori: '{{ $producto->foto }}',
                    ficha_ori: '{{ $producto->ficha_tecnica }}',
                    foto: '',
                    ficha_tecnica: ''
                },
                categorias: {!! $categorias !!},
                cargando: false,
            },
            mounted() {
                if (this.producto.foto_ori) {
                    var preview = '<img src="' + this.producto.foto_ori +
                        '" alt="logo"><h6 class="text-muted">Click para seleccionar</h6>';
                } else {
                    var preview =
                        '<img src="{{ asset('images/camara.png') }}" alt="foto"><h6 class="text-muted">Click para seleccionar</h6>';
                }

                $("#foto").fileinput({
                    overwriteInitial: true,
                    maxFileSize: 5000,
                    showClose: false,
                    showCaption: false,
                    showBrowse: false,
                    browseOnZoneClick: true,
                    removeLabel: '',
                    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
                    removeTitle: 'Quitar Foto',
                    defaultPreviewContent: preview,
                    layoutTemplates: {
                        main2: '{preview} {remove} {browse}'
                    },
                    allowedFileExtensions: ["jpg", "jpeg", "png"],
                    elErrorContainer: '#foto-file-errors'
                });
                $("#ficha_tecnica").fileinput({
                    language: 'es',
                    showPreview: false,
                    showUpload: false,
                    showRemove: false,
                    allowedFileExtensions: ["pdf"],
                    elErrorContainer: '#ficha_tecnica-file-errors'
                });
                console.log(this.producto)
            },
            methods: {
                chageVisibility(descripcion) {
                  
                    descripcion.icono_visible = !descripcion.icono_visible == 1 ? 1 : 0;
                    descripcion.isVisible = !descripcion.icono_visible ? false : true;
                    
                     var formData = objectToFormData(descripcion, {
                        indices: true
                    });

                    this.cargando = true;
                    axios.post('/productos/'+descripcion.id+'/updateVisibilidad', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(({
                            data
                        }) => {
                            console.log(data);

                            this.cargando = false;

                        })
                        .catch(({
                            response
                        }) => {
                            console.error(response);
                            this.cargando = false;
                        });
                },
                fijarArchivo(campo) {
                    this.producto[campo] = this.$refs[campo].files[0];
                },
                cambiarDescripciones() {
                    this.categorias.some(function(categoria) {
                        if (categoria.id == this.producto.categoria_id) {
                            this.producto.descripciones = categoria.descripciones;
                        }
                    }, this);
                },
                guardar() {
                    var formData = objectToFormData(this.producto, {
                        indices: true
                    });

                    this.cargando = true;
                    console.log(formData)
                    axios.post('/productos/{{ $producto->id }}', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(({
                            data
                        }) => {
                            this.cargando = false;
                            swal({
                                title: "Producto Actualizado",
                                text: "",
                                type: "success"
                            }).then(() => {
                                window.location = "/productos";
                            });
                        })
                        .catch(({
                            response
                        }) => {
                            console.error(response);
                            this.cargando = false;
                            swal({
                                title: "Error",
                                text: response.data.message ||
                                    "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                }, //fin cargarPresupuesto
            }
        });
    </script>
@stop
