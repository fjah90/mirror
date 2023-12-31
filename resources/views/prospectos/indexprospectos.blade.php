@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Prospectos | @parent
@stop

@section('header_styles')
    <style>
        .marg025 {
            margin: 0 25px;
        }

        #tabla_length {
            float: left !important;
        }

        .color_text {
            color: #B3B3B3;
        }

        .btn-primary {
            color: #000;
        }

        h4.fU:first-letter,
        p.fU:first-letter {
            text-transform: uppercase;
        }

        .orange {
            background-color: #ffba55;
            border-color: #ffba55;
        }

        .btn-success.orange.active,
        .btn-success.orange:active,
        .btn-success.orange:hover,
        .open>.btn-success.orange.dropdown-toggle {
            color: #fff;
            background-color: #FF9800;
            border-color: #FF9800;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1 style="font-weight: bolder;">Prospectos</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">

                        <h3 class="panel-title">
                            <div class="p-10" style="display:inline-block">
                                Diseñador
                                @role('Administrador|Dirección')
                                    <select class="form-control" v-model="valor_disenadores"
                                        style="width:auto;display:inline-block;">
                                        <option value="">Todos</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->nombre }}">{{ $usuario->nombre }}</option>
                                        @endforeach
                                    </select>
                                    Clientes
                                    <select class="p-10 form-control" v-model="valor_clientes"
                                        style="width:155px;display:inline-block;">
                                        <option value="">Todos</option>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->nombre }}" style="width:auto;display:inline-block;">
                                                {{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                    Factibilidad
                                    <select class="p-10 form-control" v-model="valor_factibilidad"
                                        style="width:auto;display:inline-block;">
                                        <option value="">Todos</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                @endrole
                                <!--
                          @role('Administrador|Dirección')
                                        <select class="form-control" @change="cargar()" v-model="usuarioCargado" style="width:auto;display:inline-block;">
                                          <option value="Todos">Todos</option>
                                          @foreach ($usuarios as $usuario)
        <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
        @endforeach
                                        </select>
                          @endrole
            -->
                            </div>
                            {{-- <div class="p-10 " style="display:inline-block;float: right;">
              @can('Prospectos nuevo')
                <button @click="modalNuevaCotizacion=true;" class="btn btn-warning btn-sm btn">
                    <a href="#modalNuevaCotizacion" style="color:#000;">
                      <i class="far fa-file-alt"></i> Nueva Cotización
                    </a>
                </button>
              @endcan
            </div> --}}
                            <br><br><br>
                            <div class="p-10 " style="display:inline-block;float: right;">
                                <a href="#myModal" role="button" class="btn btn-warning btn-sm btn" data-toggle="modal"
                                    style="color:#000">
                                    <i class="fas fa-calendar"></i> </a>
                                <button class="btn btn-warning btn-sm btn">
                                    @can('Prospectos nuevo')
                                        <a href="{{ route('prospectos.create2') }}" style="color:#000;">
                                            <i class="fas fa-address-book"></i> Nuevo Prospecto
                                        </a>
                                    @endcan
                                </button>
                            </div>

                            <div class="p-10">
                                <button style="background-color:#B08C62; color:#12160F;" class="btn btn-sm btn-primary"
                                    @click="modalTareas=true">
                                    <i class="fas fa-star"></i> Tareas
                                </button>
                                <button style="background-color:#B08C62; color:#12160F;" class="btn btn-sm btn-primary"
                                    @click="modalNotificaciones=true">
                                    <i class="fas fa-bell"></i>
                                    <div style="color: red; background-color:red;border-radius:50%;color:white;width:20px;height:20px;position: absolute ;margin-top: -34px;margin-left: 12px;"
                                        v-html="cant_notificaciones"></div>
                                </button>
                            </div>
                            <!--Botones para apartados de proyectos activos o cancelados-->
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-dark"
                                        style="background-color:#B08C62; color:#12160F;">
                                        <a href="{{ route('prospectos.indexprospectos') }}" style="color:#000;"
                                            id="">
                                            <i class="fas fa-user-book"></i>TODOS
                                        </a>
                                    </button>
                                    <button type="submit" class="btn btn-dark"
                                        style="background-color:#B08C62; color:#12160F;">
                                        <a href="{{ url('/prospectos/Activo/indexprospectos') }}" style="color:#000;">
                                            <i class="fas fa-user-book"></i>ACTIVOS
                                        </a>
                                    </button>
                                    <button type="submit" class="btn btn-dark"
                                        style="background-color:#B08C62; color:#12160F;">
                                        <a href="{{ url('/prospectos/Cancelado/indexprospectos') }}" style="color:#000;">
                                            <i class="fas fa-user-book"></i>CANCELADOS
                                        </a>
                                    </button>
                                </div>
                            </div>
                            <div class="p-10" style="display:inline-block">
                                Año
                                <select class="form-control" @change="cargar()" v-model="anio"
                                    style="width:auto;display:inline-block;">
                                    <option value="Todos">Todos</option>
                                    {{-- <option value="2019-12-31">2019</option>
                                    <option value="2020-12-31">2020</option>
                                    <option value="2021-12-31">2021</option>
                                    <option value="2022-12-31">2022</option> --}}
                                    <option value="2023-12-31">2023</option>
                                    <option value="2024-12-31">2024</option>
                                </select>
                            </div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tabla" class="table table-bordred" style="width:100%;" data-page-length="100">
                                <thead>
                                    <tr style="background-color:#12160F">
                                        <th class="hide">#</th>
                                        <th class="color_text">Cliente</th>
                                        <th class="color_text">Nombre de Proyecto</th>
                                        <th class="color_text">Diseñador</th>
                                        <th class="color_text">Proyección de venta en USD</th>
                                        <th class="color_text">Factibilidad</th>
                                        <th class="color_text">Próxima actividad</th>
                                        <th class="color_text">Fecha próxima actividad</th>
                                        <th class="color_text">Estatus</th>
                                        <th style="min-width:105px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(prospecto, index) in prospectos">
                                        <td class="hide">@{{ index + 1 }}</td>
                                        <template>
                                            <td>@{{ prospecto.cliente }}</td>
                                        </template>
                                        <td>@{{ prospecto.nombre }}</td>
                                        <td>@{{ prospecto.vendedor }}</td>
                                        <td id="proyeccion_venta">@{{ prospecto.proyeccion_venta | formatoMoneda }}</td>
                                        <td>@{{ prospecto.factibilidad }}</td>
                                        <td>@{{ prospecto.actividad }}</td>
                                        <td>@{{ format_date(prospecto.fecha) }}</td>
                                        <td>@{{ prospecto.estatus }}</td>
                                        <td class="text-right">
                                            @can('Prospectos ver')
                                                <!--
                                          <button class="btn btn-xs btn-info" title="Ver" @click="clickver(prospecto.id)"
                                          ><i class="far fa-eye"></i></button>
                                           -->
                                                <a class="btn btn-xs btn-info" title="Ver"
                                                    :href="'/prospectos/' + prospecto.id">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('Prospectos editar')
                                                <!--
                                          <button class="btn btn-xs btn-warning" title="Editar" @click="clickeditar(prospecto.id)"
                                          ><i class="fas fa-pencil-alt"></i></button>
                                           -->
                                                <a class="btn btn-xs btn-warning" title="Editar"
                                                    :href="'/prospectos/' + prospecto.id + '/editar'" style="background: #fece58 !important;">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                            @can('Prospectos convertir')
                                                <button class="btn btn-xs btn-success" title="Convertir el Proyecto"
                                                    @click="convertirenproyecto(prospecto, index)">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            @endcan
                                            <a class="btn btn-xs btn-success orange" title="Cotizar"
                                                :href="'/prospectos/' + prospecto.id + '/cotizar'">
                                                <i class="far fa-file-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align:right;">Total:</th>
                                        <th colspan="6" id='totalsum'>
                                            ${{ number_format($proyectos->sum('proyeccion_venta'), 2, '.', ',') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aceptar Modal -->
        <modal v-if="modalNuevaCotizacion" v-model="modalNuevaCotizacion" :title="'Nueva Cotización'"
            :footer="false">
            <div class="form-group">
                <label class="control-label">Seleccione un proyecto *</label>
                <select name="proyecto_id" v-model="proyecto_id" class="form-control" required id="proyecto-select"
                    style="width: 300px;">
                    @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}">{{ $proyecto->nombre }}--{{ $proyecto->cliente }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-warning btn-sm btn float-left">
                    <a href="{{ route('prospectos.create') }}" style="color:white;">
                        <i class="fas fa-address-book"></i> Nuevo Proyecto
                    </a>
                </button>
                <button type="submit" class="" :disabled="cargando" @click='cotizacionueva()'>Aceptar</button>
                <button type="button" class="btn btn-default" @click="proyecto_id=0; modalNuevaCotizacion=false;">
                    Cancelar
                </button>
            </div>
        </modal>


        <!-- Tareas Modal -->
        <modal id='modal_tareas' v-model="modalTareas" :title="'Tareas'" :footer="false" size="lg">
            <tabs v-model="activeTab">
                <tab title="Pendientes">
                    <table id="tablatareaspendientes" class="table table-bordred" data-page-length="15">
                        <thead>
                            <tr style="background-color:#12160F">
                                <th class="hide">#</th>
                                <th class="color_text">Tarea</th>
                                <th class="color_text">Status</th>
                                <th class="color_text">Diseñador</th>
                                <th class="color_text">Director</th>
                                <th class="color_text">Fecha de creación</th>
                                <th class="color_text">Fecha de edición</th>
                                <th style="min-width:105px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(t, index) in tareaspendientes">
                                <td class="hide">@{{ index + 1 }}</td>
                                <td>@{{ t.tarea }}</td>
                                <td>@{{ t.status }}</td>
                                <td v-if="t.vendedor_id != null">@{{ t.vendedor.nombre }}</td>
                                <td v-else></td>
                                <td v-if="t.director_id != null">@{{ t.director.name }}</td>
                                <td v-else></td>
                                <td>@{{ t.created_at }}</td>
                                <td>@{{ t.updated_at }}</td>
                                <td>
                                    <button class="btn btn-xs btn-success" title="Editar tarea"
                                        @click="editartarea(t, index)" :disabled="editando">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button class="btn btn-xs btn-warning" title="Comentarios de tarea"
                                        @click="comentariostarea(t, index)" :disabled="comentarioscargando" style="background: #fece58 !important;">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <!--
                            <button class="btn btn-xs btn-warning" title="Historial de tarea" @click="historialtarea(t, index)" :disabled="historialcargando">
                              <i class="fas fa-list"></i>
                            </button>
                            -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </tab>
                <tab title="En proceso">
                    <table id="tablatareasproceso" class="table table-bordred" data-page-length="15">
                        <thead>
                            <tr style="background-color:#12160F">
                                <th class="hide">#</th>
                                <th class="color_text">Tarea</th>
                                <th class="color_text">Status</th>
                                <th class="color_text">Diseñador</th>
                                <th class="color_text">Director</th>
                                <th class="color_text">Fecha de creación</th>
                                <th class="color_text">Fecha de edición</th>
                                <th style="min-width:105px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(t, index) in tareasproceso">
                                <td class="hide">@{{ index + 1 }}</td>
                                <td>@{{ t.tarea }}</td>
                                <td>@{{ t.status }}</td>
                                <td v-if="t.vendedor_id != null">@{{ t.vendedor.nombre }}</td>
                                <td v-else></td>
                                <td v-if="t.director_id != null">@{{ t.director.name }}</td>
                                <td v-else></td>
                                <td>@{{ t.created_at }}</td>
                                <td>@{{ t.updated_at }}</td>
                                <td>
                                    <button class="btn btn-xs btn-success" title="Editar tarea"
                                        @click="editartarea(t, index)" :disabled="editando">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button class="btn btn-xs btn-warning" title="Comentarios de tarea"
                                        @click="comentariostarea(t, index)" :disabled="comentarioscargando">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <!--
                            <button class="btn btn-xs btn-warning" title="Historial de tarea" @click="historialtarea(t, index)" :disabled="historialcargando">
                              <i class="fas fa-list"></i>
                            </button>
                            -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </tab>
                <tab title="Terminadas">
                    <table id="tablatareasterminadas" class="table table-bordred" data-page-length="15">
                        <thead>
                            <tr style="background-color:#12160F">
                                <th class="hide">#</th>
                                <th class="color_text">Tarea</th>
                                <th class="color_text">Status</th>
                                <th class="color_text">Diseñador</th>
                                <th class="color_text">Director</th>
                                <th class="color_text">Fecha de creación</th>
                                <th class="color_text">Fecha de edición</th>
                                <th style="min-width:105px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(t, index) in tareasterminadas">
                                <td class="hide">@{{ index + 1 }}</td>
                                <td>@{{ t.tarea }}</td>
                                <td>@{{ t.status }}</td>
                                <td v-if="t.vendedor_id != null">@{{ t.vendedor.nombre }}</td>
                                <td v-else></td>
                                <td v-if="t.director_id != null">@{{ t.director.name }}</td>
                                <td v-else></td>
                                <td>@{{ t.created_at }}</td>
                                <td>@{{ t.updated_at }}</td>
                                <td>

                                    <button class="btn btn-xs btn-warning" title="Comentarios de tarea"
                                        @click="comentariostarea(t, index)" :disabled="comentarioscargando">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <!--
                            <button class="btn btn-xs btn-warning" title="Historial de tarea" @click="historialtarea(t, index)" :disabled="historialcargando">
                              <i class="fas fa-list"></i>
                            </button>
                            -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </tab>
            </tabs>
            <input type="hidden" name="tarea_id" class="form-control" v-model="tarea.id" />
            @role('Administrador|Dirección')
                <div class="form-group">
                    <label class="control-label text-danger">Tarea</label>
                    <textarea class="form-control" name="tarea" rows="3" cols="80" v-model="tarea.tarea" requered></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Diseñador</label>
                    <select class="form-control" v-model="tarea.vendedor_id" style="width: 300px;" readonly>
                        @foreach ($vendedores as $vendedor)
                            <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                        @endforeach
                    </select>
                    <label class="control-label">Status *</label>
                    <select name="status" v-model="tarea.status" class="form-control" required id="proyecto-select"
                        style="width: 300px;">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Terminada">Terminada</option>
                    </select>
                </div>
            @endrole
            @role('Diseñadores')
                <div class="form-group">
                    <label class="control-label text-danger">Tarea</label>
                    <textarea class="form-control" name="tarea" rows="3" cols="80" v-model="tarea.tarea"></textarea>
                </div>
                <div class="form-group">
                    <label style="display:none;" class="control-label">Diseñador</label>
                    <select style="display:none;" class="form-control" v-model="tarea.vendedor_id" style="width: 300px;"
                        disabled>
                        @foreach ($vendedores as $vendedor)
                            <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                        @endforeach
                    </select>
                    <label id="directores_title" class="control-label">Directores</label>
                    <select id="directores_select" class="form-control" v-model="tarea.director_id" style="width: 300px;">
                        @foreach ($directores as $director)
                            <option value="{{ $director->id }}">{{ $director->name }}</option>
                        @endforeach
                    </select>
                    <label class="control-label">Status *</label>
                    <select name="status" v-model="tarea.status" class="form-control" required id="proyecto-select"
                        style="width: 300px;">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Terminada">Terminada</option>
                    </select>
                </div>

            @endrole
            <div class="form-group text-right">
                <button type="submit" class="btn btn-default" :disabled="cargando"
                    @click='guardartarea()'>Guardar</button>
                <button type="button" class="btn btn-default" @click="cancelartarea(); modalTareas=false;">
                    Cancelar
                </button>
            </div>
        </modal>

        <!-- Historial Tareas Modal -->
        <modal id='modal_notificaciones' v-model="modalNotificaciones" :title="'Notificaciones'" :footer="false"
            size="lg">

            <table id="tablanotificaciones" class="table table-bordred" data-page-length="15" style="width:100%;">
                <thead>
                    <tr style="background-color:#12160F">
                        <th class="hide">#</th>
                        <th class="color_text">Usuario</th>
                        <th class="color_text">Texto</th>
                        <th class="color_text">Fecha</th>
                        <th class="color_text">Marcar como leida</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(notificacion, index) in notificaciones">
                        <td class="hide">@{{ index + 1 }}</td>
                        <td>@{{ notificacion.usercreo.name }}</td>
                        <td>@{{ notificacion.texto }}</td>
                        <td>@{{ notificacion.created_at }}</td>
                        <td>
                            <button class="btn btn-xs btn-warning" title="Marcar como leida"
                                @click="marcarleida(notificacion, index)" :disabled="marcandoleida">
                                <i class="fas fa-check"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group text-right">
                <button type="button" class="btn btn-default" @click="modalNotificaciones=false;">
                    Cancelar
                </button>
            </div>
        </modal>

        <!-- Historial Tareas Modal
                <modal id='modal_historial' v-model="modalHistorial" :title="'Historial de Tareas'" :footer="false"  size="lg">

                  <table id="tablahistorial" class="table table-bordred"
                          data-page-length="15" style="width:100%;">
                          <thead>
                            <tr style="background-color:#12160F">
                              <th class="hide">#</th>
                              <th class="color_text">Usuario</th>
                              <th class="color_text">Valor Anterior</th>
                              <th class="color_text">Valor Nuevo</th>
                              <th class="color_text">Fecha de edición</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(h, index) in historial">
                             <td class="hide">@{{ index + 1 }}</td>
                             <td>@{{ h.usuario }}</td>
                             <td>@{{ h.anterior }}</td>
                             <td>@{{ h.nuevo }}</td>
                             <td>@{{ h.fecha }}</td>
                            </tr>
                          </tbody>
                        </table>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-default"
                                    @click="cancelarhistorial(); modalHistorial=false;">
                                Cancelar
                            </button>
                        </div>
                </modal>
                 -->
        <!-- Modal eventos -->
        <modal v-model="modalEventos" :title="'Actividad'" :footer="false" size="md">
            <div class="modal-header">
                <h4 id="descripcion_cliente" class="fU">Modal body text goes here.</h4>
                <p class="modal-title fU" id="titulo_evento">Modal title</h4>
            </div>
            <div class="modal-body">
                <p id="descripcion_evento" class="fU">Modal body text goes here.</p>
                <p id="horario_texto"></p>
                <p id="descripcion_texto" class="fU">Modal body text goes here.</p>
            </div>
            <a class="btn btn-xs btn-warning" title="Editar" href="" id="liga_evento" style="background: #fece58 !important;">
                <i class="fas fa-pencil-alt"></i>
            </a>
            <div class="form-group text-right">
                <button type="button" class="btn btn-default" @click="modalEventos=false;">
                    Cancelar
                </button>
            </div>
        </modal>

        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Próximas Actividades</h3>
                    </div>
                    <div class="modal-body">
                        @role('Administrador|Dirección')
                            <label>Vendedores</label>
                            <select class="form-control" id="selector" style="width: 200px;">
                                <option value="all">Todos</option>
                                @foreach ($vendedores as $vendedor)
                                    <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                                @endforeach
                            </select>
                        @endrole
                        @role('Diseñadores')
                            <select class="form-control" id="selector" style="display:none">
                                <option value="all">Todos</option>
                                @foreach ($vendedores as $vendedor)
                                    <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                                @endforeach
                            </select>
                        @endrole
                        <div id="calendar"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comentarios Tareas Modal -->
        <modal id='modal_comentarios' v-model="modalComentarios" :title="'Comentarios de Tareas'" :footer="false"
            size="lg">

            <table id="tablacomentarios" class="table table-bordred" data-page-length="15" style="width:100%;">
                <thead>
                    <tr style="background-color:#12160F">
                        <th class="hide">#</th>
                        <th class="color_text">Usuario</th>
                        <th class="color_text">Comentario</th>
                        <th class="color_text">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(c, index) in comentarios">
                        <td class="hide">@{{ index + 1 }}</td>
                        <td>@{{ c.usuario.name }}</td>
                        <td>@{{ c.comentario }}</td>
                        <td>@{{ c.created_at }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <label class="control-label text-danger">Comentario</label>
                <textarea class="form-control" name="tarea" rows="3" cols="80" v-model="tarea.comentario"></textarea>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-default" :disabled="comentarioscargando"
                    @click='guardarcomentario()'>Guardar</button>
                <button type="button" class="btn btn-default" @click="cancelarcomentario(); modalComentarios=false;">
                    Cancelar
                </button>
            </div>
        </modal>

    </section>




    <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
    <script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#content',
            data: {
                activeTab: 'Pendientes',
                cotizaciones: {!! json_encode($cotizaciones) !!},
                prospectos: {!! json_encode($proyectos) !!},
                usuarioCargado: {!! json_encode($disenador_id) !!},
                vendedores: {!! json_encode($vendedores) !!},
                anio: {!! json_encode($anio2) !!},
                tabla: {},
                tabla2: {},
                tablahistorial: {},
                tareaspendientes: {!! json_encode($tareaspendiente) !!},
                tareasproceso: {!! json_encode($tareasproceso) !!},
                tareasterminadas: {!! json_encode($tareasterminadas) !!},
                historial: [],
                cant_notificaciones: {!! count($notificaciones) !!},
                notificaciones: {!! json_encode($notificaciones) !!},
                comentarios: [],
                tarea: {
                    id: '',
                    tarea: '',
                    status: '',
                    vendedor_id: '',
                    director_id: '',
                    comentario: ''
                },
                modalTareas: true,
                modalNotificaciones: false,
                modalHistorial: false,
                modalComentarios: false,
                modalEventos: false,
                modalCalendario: false,
                locale: localeES,
                modalNuevaCotizacion: false,
                fecha_ini: '',
                fecha_fin: '',
                proyecto_id: '',
                cargando: false,
                marcandoleida: false,
                editando: false,
                historialcargando: false,
                comentarioscargando: false,
                select_disenadores: [],
                valor_disenadores: 'Diseñadores',
                select_clientes: [],
                valor_clientes: 'Clientes',
                select_factibilidad: [],
                valor_factibilidad: 'Factibilidad',
            },
            filters: {
                formatoMoneda(numero) {
                    return accounting.formatMoney(numero, "$", 2);
                },
            },
            mounted() {
                var vue = this;
                $.fn.dataTable.moment('DD/MM/YYYY');
                this.tabla = $("#tabla").DataTable({
                    stateSave: true,
                    "dom": 'f<"#fechas_container.pull-left">tlip',
                    //Aqui lo que hace es que cambia los datos del footer siempre que haya un filtrado
                    "footerCallback": function(row, data, start, end, display) {
                        //tomamos los datos de nuestra tabla
                        var api = this.api(),
                            data;
                        //como las cantidades vienen en formato les quitamos el formato y dejamo solo los valores numericos
                        var formato = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        //datos de la tabla con filtros aplicados
                        var datos = api.columns([4], {
                            search: 'applied'
                        }).data();
                        var totalMxn = 0;
                        //suma de montos
                        datos[0].forEach(function(element, index) {
                            totalMxn += formato(element)
                        });
                        // Actualizar el campo
                        var nCells = row.getElementsByTagName('th');
                        nCells[1].innerHTML = accounting.formatMoney(totalMxn, "$", 2);
                    }
                });

                this.tablahistorial = $("#tablahistorial").DataTable({});

                document.addEventListener('DOMContentLoaded', function() {
                    let selector = document.querySelector("#selector");
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        height: 650,
                        aspectRatio: 2,
                        initialView: 'dayGridMonth',
                        eventColor: '#800080',
                        eventClick: function(info) {
                            console.log(info)
                            document.getElementById("titulo_evento").innerHTML = info.event
                                .title;
                            document.getElementById("descripcion_cliente").innerHTML = info
                                .event.extendedProps.nombreCliente;
                            document.getElementById("descripcion_evento").innerHTML = info.event
                                .extendedProps.description;
                            document.getElementById("descripcion_texto").innerHTML = info.event
                                .extendedProps.texto;
                            document.getElementById("horario_texto").innerHTML = info.event
                                .extendedProps.horario != null ? "Horario: " + info.event
                                .extendedProps.horario : "";
                            console.log(info.event.extendedProps.horario)
                            document.getElementById("liga_evento").href = info.event
                                .extendedProps.liga;
                            vue.modalEventos = true;
                        },
                        eventDidMount: function(arg) {
                            let val = selector.value;
                            if (!(val == arg.event.extendedProps.userId || val == "all")) {
                                arg.el.style.display = "none";
                            }
                        },
                        events: function(fetchInfo, successCallback, failureCallback) {
                            successCallback({!! json_encode($proximas_actividades) !!});
                        }
                    });
                    $('#myModal').on('shown.bs.modal', function() {
                        calendar.render();
                    });

                    selector.addEventListener('change', function() {
                        calendar.refetchEvents();
                    });
                });

                //$("#fechas_container").append($("#fecha_ini_control"));
                //$("#fechas_container").append($("#fecha_fin_control"));
                var vue = this;
                $.fn.dataTableExt.afnFiltering.push(
                    function(settings, data, dataIndex) {
                        var min = vue.fecha_ini;
                        var max = vue.fecha_fin;
                        var fecha = data[4] || 0; // Our date column in the table

                        var startDate = moment(min, "DD/MM/YYYY");
                        var endDate = moment(max, "DD/MM/YYYY");
                        var diffDate = moment(fecha,
                            "DD/MM/YYYY"); /***Ajustando la fecha en la vista de prospectos***/
                        // console.log(min=="",max=="",diffDate.isSameOrAfter(startDate),diffDate.isSameOrBefore(endDate),diffDate.isBetween(startDate, endDate));
                        if (min == "" && max == "") return true;
                        if (max == "" && diffDate.isSameOrAfter(startDate)) return true;
                        if (min == "" && diffDate.isSameOrBefore(endDate)) return true;
                        if (diffDate.isBetween(startDate, endDate, null, '[]')) return true;
                        return false;
                    }
                );
            },
            watch: {
                fecha_ini: function(val) {
                    this.tabla.draw();
                },
                fecha_fin: function(val) {
                    this.tabla.draw();
                },
                //filtramos por el disenador seleccionado
                valor_disenadores: function(val) {
                    this.tabla.columns(3).search(this.valor_disenadores).draw();
                },
                valor_clientes: function(val) {
                    this.tabla.columns(1).search(this.valor_clientes).draw();
                },

                valor_factibilidad: function(val) {
                    this.tabla.columns(5).search(this.valor_factibilidad).draw();
                },
            },
            methods: {
                dateParser(value) {
                    return moment(value, 'DD/MM/YYYY').toDate().getTime();
                },

                format_date(value) {
                    if (value) {
                        return moment(String(value)).format('DD/MM/YYYY')
                    }
                },
                editartarea(tarea, index) {
                    var rol = {!! json_encode(auth()->user()->roles[0]->name) !!};
                    if (rol == 'Diseñadores') {
                        $('#directores_select').css('display', 'none');
                        $('#directores_title').css('display', 'none');

                    } else {
                        $('#directores_select').css('display', 'block');
                        $('#directores_title').css('display', 'block');
                    }
                    this.editando = true;
                    this.tarea.id = tarea.id;
                    this.tarea.tarea = tarea.tarea;
                    this.tarea.vendedor_id = tarea.vendedor_id;
                    this.tarea.status = tarea.status;
                    this.tarea.director_id = tarea.director_id;
                    //this.tarea == tarea;
                },
                historialtarea(tarea, index) {
                    this.historialcargando = true;
                    axios.get('/gethistorialtarea/' + tarea.id, {})
                        .then(({
                            data
                        }) => {
                            $('#tablahistorial').DataTable().destroy();
                            //this.tablahistorial.destroy();
                            console.log(data.historial);
                            this.historial = data.historial;
                            this.historialcargando = false;
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

                    this.modalHistorial = true;
                    $('#modal_tareas').css('z-index', '1039');
                    $('#modal_historial').css('z-index', '1071');
                },
                cancelarhistorial() {
                    $('#modal_tareas').css('z-index', '1071');
                    $('#modal_historial').css('z-index', '1039');
                    this.historialcargando = false;
                },
                comentariostarea(tarea, index) {
                    $('#tablacomentarios').DataTable().destroy();
                    this.modalComentarios = true;
                    this.tarea.id = tarea.id;
                    this.comentarios = tarea.comentarios;
                    $('#modal_tareas').css('z-index', '1039');
                    $('#modal_comentarios').css('z-index', '1071');

                },
                cancelarcomentario() {
                    this.tarea.id = '';
                    this.tarea.comentario = '';
                    $('#modal_tareas').css('z-index', '1091');
                    $('#modal_comentarios').css('z-index', '1039');
                    this.comentarioscargando = false;
                },
                cancelartarea() {
                    this.tarea.tarea = null;
                    this.tarea.id = null;
                    this.tarea.vendedor_id = null;
                    this.tarea.director_id = null;
                    this.tarea.status = 'Pendiente';
                    this.cargando = false;
                    this.editando = false;
                    this.historialcargando = false;
                    var rol = {!! json_encode(auth()->user()->roles[0]->name) !!};
                    if (rol == 'Diseñadores') {
                        $('#directores_select').css('display', 'block');
                        $('#directores_title').css('display', 'block');
                    } else {
                        $('#directores_select').css('display', 'none');
                        $('#directores_title').css('display', 'none');
                    }
                },
                marcarleida(notificacion, index) {
                    var formData = objectToFormData(notificacion, {
                        indices: true
                    });
                    this.marcandoleida = true;
                    axios.post('/marcarleida', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(({
                            data
                        }) => {
                            this.marcandoleida = false;
                            $('#tablanotificaciones').DataTable().destroy();
                            this.notificaciones = data.notificaciones;
                            this.cant_notificaciones = data.cant_notificaciones;
                        })
                        .catch(({
                            response
                        }) => {
                            console.error(response);
                            this.marcandoleida = false;
                            swal({
                                title: "Error",
                                text: response.data.message ||
                                    "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },
                guardarcomentario() {
                    var formData = objectToFormData(this.tarea, {
                        indices: true
                    });
                    this.comentarioscargando = true;
                    axios.post('/comentarios', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(({
                            data
                        }) => {
                            $('#tablacomentarios').DataTable().destroy();
                            this.comentarios = data.comentarios;
                            this.tarea.id = '';
                            this.tarea.comentario = '';
                        })
                        .catch(({
                            response
                        }) => {
                            console.error(response);
                            this.comentarioscargando = false;
                            swal({
                                title: "Error",
                                text: response.data.message ||
                                    "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },
                guardartarea() {
                    var formData = objectToFormData(this.tarea, {
                        indices: true
                    });
                    this.cargando = true;
                    if (this.tarea.id == '') {
                        axios.post('/tareas', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(({
                                data
                            }) => {
                                this.tarea.tarea = '';
                                this.tarea.id = '';
                                this.tareaspendientes.push(data.tarea);
                                swal({
                                    title: "Exito",
                                    text: "La tarea ha sido guardada",
                                    type: "success"
                                });
                                this.cargando = false;
                                this.modalTareas = false;
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
                    } else {
                        //console.log(this.tarea);
                        axios.post('/tareasactualizar', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(({
                                data
                            }) => {
                                swal({
                                    title: "Exito",
                                    text: "La tarea ha sido actualizada",
                                    type: "success"
                                });
                                this.tarea.tarea = '';
                                this.tarea.id = '';
                                this.tarea.vendedor_id = '';
                                this.tarea.director_id = '';
                                this.tarea.status = 'Pendiente';
                                this.cargando = false;
                                this.editando = false;
                                $('#tablatareaspendientes').DataTable().destroy();
                                this.tareaspendientes = data.tareaspendiente;
                                $('#tablatareasproceso').DataTable().destroy();
                                this.tareasproceso = data.tareasproceso;
                                $('#tablatareasterminadas').DataTable().destroy();
                                this.tareasterminadas = data.tareasterminadas;
                                this.modalTareas = false;
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
                    }
                },
                cargar() {
                    this.tarea.vendedor_id = this.usuarioCargado;
                    axios.post('/prospectos/listadoprospectos', {
                            id: this.usuarioCargado,
                            anio: this.anio
                        })
                        .then(({
                            data
                        }) => {
                            //$("#oculto").append($("#fecha_ini_control"));
                            //$("#oculto").append($("#fecha_fin_control"));
                            this.tabla.destroy();
                            this.prospectos = data.prospectos;
                            this.tareas = data.tareas;
                            document.getElementById('totalsum').innerHTML = '$' + data.total;
                            //this.cotizaciones = data.cotizaciones;
                            swal({
                                title: "Exito",
                                text: "Datos Cargados",
                                type: "success"
                            }).then(() => {
                                this.tabla = $("#tabla").DataTable({
                                    "dom": 'f<"#fechas_container.pull-left">ltip',
                                    "order": [
                                        [4, "desc"]
                                    ]
                                });
                                //$("#fechas_container").append($("#fecha_ini_control"));
                                //$("#fechas_container").append($("#fecha_fin_control"));
                            });
                        })
                        .catch(({
                            response
                        }) => {
                            console.error(response);
                            swal({
                                title: "Error",
                                text: response.data.message ||
                                    "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },
                /*
                clickver(prospecto_id){
                  var rol = {!! json_encode(auth()->user()->roles[0]->name) !!};
                  if( rol == 'Administrador' ||  rol == 'Dirección'){
                    window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+this.usuarioCargado+'/anio/'+this.anio;
                  }
                  else{
                    var vend_id = {!! json_encode($disenador_id) !!};
                    window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+vend_id +'/anio/'+this.anio;
                  }
                },
                clickeditar(prospecto_id)
                  var rol = {!! json_encode(auth()->user()->roles[0]->name) !!};
                  if( rol == 'Administrador' ||  rol == 'Dirección'){
                    window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+this.usuarioCargado+'/anio/'+this.anio+'/editar';
                  }
                  else{
                    var vend_id = {!! json_encode($disenador_id) !!};
                    window.location.href = '/prospectos/'+prospecto_id+'/disenador/'+vend_id +'/anio/'+this.anio+'/editar';
                  }
                },
                */
                convertirenproyecto(prospecto, index) {
                    swal({
                        title: 'Cuidado',
                        text: "El Prospecto se convertirar en Proyecto " + prospecto.nombre + "?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No, Cancelar',
                    }).then((result) => {
                        if (result.value) {
                            axios.post('/prospectos/' + prospecto.id + '/convertir', {})
                                .then(({
                                    data
                                }) => {
                                    this.prospectos.splice(index, 1);
                                    swal({
                                        title: "Exito",
                                        text: "El Prospectos ha sido convertido en proyecto",
                                        type: "success"
                                    });
                                })
                                .catch(({
                                    response
                                }) => {
                                    console.error(response);
                                    swal({
                                        title: "Error",
                                        text: response.data.message ||
                                            "Ocurrio un error inesperado, intente mas tarde",
                                        type: "error"
                                    });
                                });
                        } //if confirmacion
                    });
                }, //fin borrar
                cotizacionueva() {
                    if (this.proyecto_id == 0) {
                        swal({
                            title: "Error",
                            text: "Debe de seleccionar un proyecto o crear uno nuevo para continuar.",
                            type: "error"
                        });
                    } else {
                        window.location.href = "/prospectos/" + this.proyecto_id + "/cotizar";
                    }
                },
            }
        });
    </script>
@stop
