@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cotizaciones directas | @parent
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
    </style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1 style="font-weight: bolder;">Cotizaciones Directas</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <div class="p-10 text-right">
                            <a class="btn btn-warning btn-sm btn" href="{{ route('cotizacionesdirectas.create') }}"
                                style="color:#000;">
                                <i class="fas fa-address-book"></i> Nueva Cotizacion
                            </a>
                        </div>


                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordred">
                                <thead>
                                    <tr>
                                        <th class="color_text">Numero</th>
                                        <th class="color_text">Fecha</th>
                                        <th class="color_text">Productos Ofrecidos</th>
                                        <th class="color_text">Total</th>
                                        <th class="color_text">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(cotizacion, index) in cotizaciones">
                                        <td>@{{ cotizacion.numero }}</td>
                                        <td>@{{ cotizacion.fecha_formated }}</td>
                                        <td>
                                            <template v-for="(entrada, index) in cotizacion.entradas">
                                                {{-- <span>@{{ index + 1 }}.- @{{ entrada.producto.nombre }} - --}}
                                                @{{ entrada.producto.proveedor.empresa }}</span><br />
                                            </template>
                                        </td>
                                        <td>

                                            <table>
                                                <template
                                                    v-for="(total, proveedor) in cotizacion.entradas_proveedor_totales">
                                                    <tr>
                                                        <td class="text-right">@{{ proveedor }} |</td>
                                                        <td class="text-right">@{{ total * (cotizacion.iva == 0 ? 1 : 1.16) | formatoMoneda }}</td>
                                                    </tr>
                                                </template>
                                                <tr>
                                                    <th class="text-right">Total @{{ cotizacion.moneda }}|</th>
                                                    <th class="text-right">@{{ cotizacion.total | formatoMoneda }} </th>
                                                </tr>
                                            </table>
                                        </td>

                                        <td>
                                            <label class="label label-warning" v-if="cotizacion.aceptada == 0">
                                                Pendiente
                                            </label>

                                            <label class="label label-success" v-if="cotizacion.aceptada == 1">
                                                Aceptada
                                            </label>


                                        </td>
                                        <td class="text-right">
                                            {{-- <button class="btn btn-xs btn-default" title="Notas"
                                                @click="notas.cotizacion_id=cotizacion.id;notas.mensaje=cotizacion.notas2;openNotas=true;">
                                                <i class="far fa-sticky-note"></i>
                                            </button> --}}
                                            {{-- <button class="btn btn-xs btn-primary" title="Aceptar"
                                                        @click="aceptar.cotizacion_id=cotizacion.id; openAceptar=true;">
                                                        <i class="fas fa-user-check"></i>
                                                    </button> --}}
                                            <button class="btn btn-xs btn-info" title="Enviar"
                                                @click="enviar.cotizacion_id=cotizacion.id; enviar.numero=cotizacion.numero; openEnviar=true;">
                                                <i class="far fa-envelope"></i>
                                            </button> 
                                            <a class="btn btn-xs btn-success" title="PDF"
                                                :href="'storage/' + cotizacion.archivo"
                                                :download="'C ' + cotizacion.numero + ' Robinson' + ' ' + cotizacion.cliente
                                                    .nombre +
                                                    ' ' + cotizacion.nombre_proyecto + '.pdf'">
                                                <i class="far fa-file-pdf"></i>
                                            </a>
                                            <a class="btn btn-xs btn-warning" title="Editar"
                                                :href="'/cotizacionesdirectas/' + cotizacion.id + '/edit'"
                                                style="background: #fece58 !important;">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            @role('Administrador')
                                                <button class="btn btn-xs btn-danger" title="Eliminar"
                                                    @click="borrar(index, cotizacion)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endrole



                                        </td>
                                    </tr>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td class="text-right">Total Pesos <br /> Total Dolares</td>
                                        <td>@{{ totales_cotizaciones.pesos | formatoMoneda }} <br />
                                            @{{ totales_cotizaciones.dolares | formatoMoneda }}
                                        </td>
                                    </tr>
                                </tfoot>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




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
                    tabla: {},
                    locale: localeES,
                    cargando: false,
                    editando: false,
                },
                computed: {
                    totales_cotizaciones() {
                        var dolares = 0,
                            pesos = 0;
                        this.cotizaciones.forEach(function(cotizacion) {
                            if (cotizacion.moneda == "Pesos") pesos += cotizacion.total;
                            else dolares += cotizacion.total;
                        });
                        return {
                            "dolares": dolares,
                            "pesos": pesos
                        }
                    }
                },
                filters: {
                    formatoMoneda(numero) {
                        return accounting.formatMoney(numero, "$", 2);
                    },
                },
                mounted() {

                },
                watch: {

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
                    borrar(index, cotizacion) {
                        swal({
                            title: 'Cuidado',
                            text: "Borrar Cotizacion " + cotizacion.numero + "?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Borrar',
                            cancelButtonText: 'No, Cancelar',
                        }).then((result) => {
                            if (result.value) {
                                axios.delete('/prospectos/1/cotizacion/' + cotizacion
                                        .id, {})
                                    .then(({
                                        data
                                    }) => {
                                        this.cotizaciones.splice(index, 1);
                                        swal({
                                            title: "Exito",
                                            text: "La cotizacion ha sido borrado",
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
                    },
                }
            });
        </script>
    @stop
