@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Proyectos | @parent
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

        .orange {
            background-color: #eea232;
            border-color: #eea232;
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
        <h1 style="font-weight: bolder;">Cotizaciones en proceso</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h3 class="panel-title">
                            <div class="p-10" style="display:inline-block">
                                Usuario
                                @role('Administrador')
                                    <select class="form-control" @change="cargar()" v-model="usuarioCargado"
                                        style="width:auto;display:inline-block;">
                                        <option value="Todos">Todos</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                        @endforeach
                                    </select>
                                @endrole
                            </div>
                            <div class="p-10" style="display:inline-block;float: right;">
                                <button class="btn btn-sm btn-primary">
                                    <a href="{{ route('prospectos.create') }}" style="color:white;">
                                        <i class="fas fa-address-book"></i> Nuevo Proyecto
                                    </a>
                                </button>
                            </div>
                            <div class="p-10">
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
                                </select>
                            </div>
                            <div class="p-10" style="display:inline-block">
                                <dropdown id="fecha_ini_control" class="marg025">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <btn class="dropdown-toggle" style="background-color:#fff;">
                                                <i class="fas fa-calendar"></i>
                                            </btn>
                                        </div>
                                        <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                                            v-model="fecha_ini" readonly style="width:120px;" />
                                    </div>
                                    <template slot="dropdown">
                                        <li>
                                            <date-picker :locale="locale" :today-btn="false" format="dd/MM/yyyy"
                                                :date-parser="dateParser" v-model="fecha_ini" />
                                        </li>
                                    </template>
                                </dropdown>
                                <dropdown id="fecha_fin_control" class="marg025">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <btn class="dropdown-toggle" style="background-color:#fff;">
                                                <i class="fas fa-calendar"></i>
                                            </btn>
                                        </div>
                                        <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                                            v-model="fecha_fin" readonly style="width:120px;" />
                                    </div>
                                    <template slot="dropdown">
                                        <li>
                                            <date-picker :locale="locale" :today-btn="false" format="dd/MM/yyyy"
                                                :date-parser="dateParser" v-model="fecha_fin" />
                                        </li>
                                    </template>
                                </dropdown>

                            </div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!--
                  <div id="oculto" class="hide">
                  </div>
                -->
                        <div class="table-responsive">
                            <table id="tabla" class="table table-bordred" style="width:100%;" data-page-length="100">
                                <thead>
                                    <tr style="background-color:#12160F">
                                        <th class="hide">#</th>
                                        <th class="color_text">Usuario</th>
                                        <th class="color_text">Cliente</th>
                                        <th class="color_text">Nombre de Proyecto</th>
                                        <th class="color_text">Ultima Actividad</th>
                                        <th class="color_text">Tipo</th>
                                        <th class="color_text">Próxima Actividad</th>
                                        <th class="color_text">Tipo</th>
                                        <th style="min-width:105px;"></th>
                                        <th class="hide">Cotizacion id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(prospecto, index) in prospectos">
                                        <td class="hide">@{{ index + 1 }}</td>
                                        <template v-if="prospecto.user">
                                            <td>@{{ prospecto.user.name }}</td>
                                        </template>
                                        <template v-else>
                                            <td>@{{ prospecto.cliente.usuario_nombre }} </td>
                                        </template>
                                        <td>@{{ prospecto.cliente.nombre }}</td>
                                        <td>@{{ prospecto.nombre }}</td>
                                        <template v-if="prospecto.ultima_actividad">
                                            <td>@{{ prospecto.ultima_actividad.fecha_formated }}</td>
                                            <td>@{{ prospecto.ultima_actividad.tipo.nombre }}</td>
                                        </template>
                                        <template v-else>
                                            <td></td>
                                            <td></td>
                                        </template>
                                        <template v-if="prospecto.proxima_actividad">
                                            <td>@{{ prospecto.proxima_actividad.fecha_formated }}</td>
                                            <td>@{{ prospecto.proxima_actividad.tipo.nombre }}</td>
                                        </template>
                                        <template v-else>
                                            <td></td>
                                            <td></td>
                                        </template>
                                        <td class="text-right">
                                            <a class="btn btn-xs btn-info" title="Ver"
                                                :href="'/prospectos/' + prospecto.id">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <a class="btn btn-xs btn-warning" title="Editar"
                                                :href="'/prospectos/' + prospecto.id + '/editar'">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a class="btn btn-xs btn-success orange" title="Cotizar"
                                                :href="'/prospectos/' + prospecto.id + '/cotizar'">
                                                <i class="far fa-file-alt"></i>
                                            </a>
                                            <button class="btn btn-xs btn-danger" title="Borrar"
                                                @click="borrar(prospecto, index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="hide">
                                            <template v-for="(cotizacion, index) in prospecto.cotizaciones">
                                                <span>@{{ cotizacion.numero }}</span><br />
                                            </template>
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

{{-- footer_scripts --}}
@section('footer_scripts')
    <script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#content',
            data: {
                prospectos: {!! json_encode($prospectos) !!},
                usuarioCargado: {{ auth()->user()->id }},
                anio: '2023-12-31',
                tabla: {},
                locale: localeES,
                fecha_ini: '',
                fecha_fin: ''
            },
            mounted() {
                $.fn.dataTable.moment('DD/MM/YYYY');
                this.tabla = $("#tabla").DataTable({
                    "dom": 'f<"#fechas_container.pull-left">tlip',
                    "order": [
                        [4, "desc"]
                    ]
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
                        var diffDate = moment(fecha, "YYYY-MM-DD");
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
                }
            },
            methods: {
                dateParser(value) {
                    return moment(value, 'DD/MM/YYYY').toDate().getTime();
                },
                cargar() {
                    axios.post('/prospectos/listado', {
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
                borrar(prospecto, index) {
                    swal({
                        title: 'Cuidado',
                        text: "Borrar el Proyecto " + prospecto.nombre + "?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Borrar',
                        cancelButtonText: 'No, Cancelar',
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('/prospectos/' + prospecto.id, {})
                                .then(({
                                    data
                                }) => {
                                    this.prospectos.splice(index, 1);
                                    swal({
                                        title: "Exito",
                                        text: "El Proyecto ha sido borrado",
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
            }
        });
    </script>
@stop
