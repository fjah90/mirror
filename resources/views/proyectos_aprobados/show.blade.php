@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Proyectos Aprobados | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
  <h1>Proyecto {{$proyecto->cotizacion->prospecto->nombre}}</h1>
</section>
<!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Datos del Proyecto</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Cliente</label>
                                    <span class="form-control">{{$proyecto->cliente->nombre}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre de Proyecto</label>
                                    <span class="form-control text-uppercase">{{$proyecto->cotizacion->prospecto->nombre}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <span class="form-control"
                                          style="min-height:68px;">{{$proyecto->cotizacion->prospecto->descripcion}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">Cotizaciones Realizadas</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordred">
                                        <thead>
                                        <tr>
                                            <th>Numero</th>
                                            <th>Fecha</th>
                                            <th>Productos Ofrecidos</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(cotizacion, index) in prospecto.cotizaciones">
                                            <td>@{{cotizacion.numero}}</td>
                                            <td>@{{cotizacion.fecha_formated}}</td>
                                            <td>
                                                <template v-for="(entrada, index) in cotizacion.entradas">
                                                    <span>@{{index+1}}.- @{{entrada.producto.nombre}} - @{{entrada.producto.proveedor.empresa}} - Area:@{{entrada.area}}</span><br/>
                                                </template>
                                            </td>
                                            <td>
                                                {{$prospecto->en}}
                                                <table>
                                                    <template v-for="(total, proveedor) in cotizacion.entradas_proveedor_totales">
                                                        <tr>
                                                            <td class="text-right">@{{ proveedor }} |</td>
                                                            <td class="text-right">@{{total * (cotizacion.iva == 0 ? 1 : 1.16) | formatoMoneda}}</td>
                                                        </tr>
                                                    </template>
                                                    <tr>
                                                        <th class="text-right">Total @{{ cotizacion.moneda }}|</th>
                                                        <th class="text-right">@{{cotizacion.total | formatoMoneda}} </th>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="text-right">
                                                <button class="btn btn-xs btn-default" title="Notas"
                                                        @click="notas.cotizacion_id=cotizacion.id;notas.mensaje=cotizacion.notas2;openNotas=true;">
                                                    <i class="far fa-sticky-note"></i>
                                                </button>
                                                <a class="btn btn-xs btn-success" title="PDF" :href="cotizacion.archivo"
                                                   :download="'C '+cotizacion.numero+' Intercorp '+prospecto.nombre+'.pdf'">
                                                    <i class="far fa-file-pdf"></i>
                                                </a>
                                                <button class="btn btn-xs btn-info" title="Enviar"
                                                        @click="enviar.cotizacion_id=cotizacion.id; enviar.numero=cotizacion.numero; openEnviar=true;">
                                                    <i class="far fa-envelope"></i>
                                                </button>
                                                <a v-if="cotizacion.aceptada" class="btn btn-xs text-primary"
                                                   title="Comprobante Confirmación"
                                                   :href="cotizacion.comprobante_confirmacion"
                                                   target="_blank">
                                                    <i class="fas fa-user-check"></i>
                                                </a>
                                                
                                                <a v-if="cotizacion.aceptada" class="btn btn-xs text-warning"
                                               title="Orden Compra"
                                               :href="'/proyectos-aprobados/'+ cotizacion.proyecto_aprobado.id + '/ordenes-compra'"
                                               target="_blank">
                                                <i class="fas fa-arrow-up"></i>
                                                </a>
                                                
                                                <template v-else>
                                                    <button class="btn btn-xs btn-warning" title="Editar"
                                                            @click="editar(index, cotizacion)">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-primary" title="Aceptar"
                                                            @click="aceptar.cotizacion_id=cotizacion.id; openAceptar=true;">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                    @role('Administrador')
                                                    <button class="btn btn-xs btn-danger" title="Eliminar"
                                                            @click="borrar(index, cotizacion)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    @endrole
                                                </template>
                                                <button class="btn btn-xs btn-white" title="Copiar"
                                                        @click="copiar(index, cotizacion)">
                                                    <i class="far fa-copy"></i>
                                                </button>
                                                <button class="btn btn-xs btn-green" title="Copiar a otro proyecto"
                                                        @click="copiar2(index, cotizacion); openCopiar=true ">
                                                    <i class="far fa-copy"></i>
                                                </button>

                                            </td>
                                        </tr>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-right">Total Pesos <br/> Total Dolares</td>
                                            <td>@{{totales_cotizaciones.pesos | formatoMoneda}} <br/>
                                                @{{totales_cotizaciones.dolares | formatoMoneda}}
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
            </div>
        </div>
    </section>



@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}" ></script>
<script>

  Vue.config.devtools = true;
        const app = new Vue({
            el: '#content',
            data: {
                prospecto: {!! json_encode($prospecto) !!},
            }
        });


</script>
@stop