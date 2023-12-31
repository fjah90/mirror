@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Clientes | @parent
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
        <h1>Clientes</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <tabs>
            <tab title="Nacionales">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h3 class="panel-title">
                            <div class="p-10">
                                Clientes Nacionales
                                @role('Administrador')
                                de
                                <select class="form-control" @change="cargar()" v-model="usuarioCargado"
                                        style="width:auto;display:inline-block;">
                                    <option value="Todos">Todos</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                    @endforeach
                                </select>
                                @endrole
                                , tipo
                                <select class="form-control" @change="cargar()" v-model="tipoCargado"
                                        style="width:auto;display:inline-block;">
                                    <option value="Todos">Todos</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                    @endforeach
                                </select>
                                Categoría
                                <select class="form-control" @change="cargar()" v-model="categoriaCargado"
                                        style="width:auto;display:inline-block;">
                                    <option value="Todos">Todos</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                    @endforeach
                                </select><br>
                                <a href="{{route('clientes.createNacional')}}" class="btn btn-warning pull-right"
                                   style="color:#000; position: relative; top: -35px;">
                                    <i class="fas fa-plus"></i> Cliente Nacional
                                </a>
                            </div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tablaNac" class="table table-bordred" style="width:100%;"
                                   data-page-length="100">
                                <thead>
                                <tr style="background-color:#12160F;">
                                    <th class="color_text">#</th>
                                    <th class="color_text">Usuario</th>
                                    <th class="color_text">Tipo</th>
                                    <th class="color_text">Nombre</th>
                                    <th class="color_text">RFC</th>
                                    <th class="color_text">Razon Social</th>
                                    <th style="min-width:70px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(cliente, index) in clientesNacionales">
                                    <td>@{{index+1}}</td>
                                    <td>@{{cliente.usuario_nombre}}</td>
                                    <td>@{{cliente.tipo.nombre}}</td>
                                    <td>@{{cliente.nombre}}</td>
                                    <td>@{{cliente.rfc}}</td>
                                    <td>@{{cliente.razon_social}}</td>
                                    <td class="text-right">
                                        <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                                           :href="'/clientes/'+cliente.id">
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                                           :href="'/clientes/'+cliente.id+'/editar'" style="background: #fece58 !important;">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-xs btn-primary"  title="Historial de compra"
                                           href="{{route('historialCompra.index')}}">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                        @hasrole('Administrador')
                                        <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                                                @click="borrar(cliente, index)">
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
            </tab>

            <tab title="Extranjeros">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h3 class="panel-title">
                            <div class="p-10">
                                Clientes Extranjeros
                                @role('Administrador')
                                de
                                <select class="form-control" @change="cargar()" v-model="usuarioCargado"
                                        style="width:auto;display:inline-block;">
                                    <option value="Todos">Todos</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                    @endforeach
                                </select>
                                @endrole
                                , tipo
                                <select class="form-control" @change="cargar()" v-model="tipoCargado"
                                        style="width:auto;display:inline-block;">
                                    <option value="Todos">Todos</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                    @endforeach
                                </select>
                                Categoría
                                 <select class="form-control" @change="cargar()" v-model="categoriaCargado"
                                        style="width:auto;display:inline-block;">
                                    <option value="Todos">Todos</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                    @endforeach
                                </select>
                                <a href="{{route('clientes.createExtranjero')}}" class="btn btn-warning pull-right"
                                  style="color:#000;">
                                    <i class="fas fa-plus"></i> Cliente Extranjero
                                </a>
                            </div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tablaExt" class="table table-bordred" style="width:100%;"
                                   data-page-length="100">
                                <thead>
                                <tr style="background-color:#12160F">
                                    <th class="color_text">#</th>
                                    <th class="color_text">Usuario</th>
                                    <th class="color_text">Tipo</th>
                                    <th class="color_text">Nombre</th>
                                    <th class="color_text">RFC</th>
                                    <th class="color_text">Razon Social</th>
                                    <th style="min-width:70px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(cliente, index) in clientesExtranjeros">
                                    <td>@{{index+1}}</td>
                                    <td>@{{cliente.usuario_nombre}}</td>
                                    <td>@{{cliente.tipo.nombre}}</td>
                                    <td>@{{cliente.nombre}}</td>
                                    <td>@{{cliente.rfc}}</td>
                                    <td>@{{cliente.razon_social}}</td>
                                    <td class="text-right">
                                        <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                                           :href="'/clientes/'+cliente.id">
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                                           :href="'/clientes/'+cliente.id+'/editar'" style="background: #fece58 !important;">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-xs btn-primary"  title="Historial de compra"
                                           href="{{route('historialCompra.index')}}">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                        @hasrole('Administrador')
                                        <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                                                @click="borrar(cliente, index)">
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
            </tab>
        </tabs>
    </section>
    <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
    <script>
        const app = new Vue({
            el: '#content',
            data: {
                clientesNacionales: {!! json_encode($clientesNacionales) !!},
                clientesExtranjeros: {!! json_encode($clientesExtranjeros) !!},
                usuarioCargado: "Todos",
                tipoCargado: "Todos",
                categoriaCargado: "Todos",//
                tablaNac: {},
                tablaExt: {}
            },
            mounted() {
                this.tablaNac = $("#tablaNac").DataTable({"order": [[3, "asc"]]});
                this.tablaExt = $("#tablaExt").DataTable({"order": [[3, "asc"]]});
            },
            methods: {
                cargar() {
                    axios.post('/clientes/listado', {id: this.usuarioCargado, tipo: this.tipoCargado, categoria: this.categoriaCargado})//
                        .then(({data}) => {
                            this.tablaNac.destroy();
                            this.tablaExt.destroy();
                            this.clientesNacionales = data.clientesNacionales;
                            this.clientesExtranjeros = data.clientesExtranjeros;
                            swal({
                                title: "Exito",
                                text: "Datos Cargados",
                                type: "success"
                            }).then(() => {
                                this.tablaNac = $("#tablaNac").DataTable({"order": [[3, "asc"]]});
                                this.tablaExt = $("#tablaExt").DataTable({"order": [[3, "asc"]]});
                            });
                        })
                        .catch(({response}) => {
                            console.error(response);
                            swal({
                                title: "Error",
                                text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },
                borrar(cliente, index) {
                    swal({
                        title: 'Cuidado',
                        text: "Borrar el Cliente " + cliente.nombre + "?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Borrar',
                        cancelButtonText: 'No, Cancelar',
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('/clientes/' + cliente.id, {})
                                .then(({data}) => {
                                    this.clientes.splice(index, 1);
                                    swal({
                                        title: "Exito",
                                        text: "El cliente ha sido borrado",
                                        type: "success"
                                    });
                                })
                                .catch(({response}) => {
                                    console.error(response);
                                    swal({
                                        title: "Error",
                                        text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                                        type: "error"
                                    });
                                });
                        } //if confirmacion
                    });
                },//fin borrar
            }
        });
    </script>
@stop
