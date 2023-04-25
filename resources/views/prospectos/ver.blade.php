@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Proyectos Aprobados | @parent
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

<section class="content-header" style="background-color:#12160F; color:#B68911;">
  <h1 style="font-weight: bolder;">Proyecto {{$prospecto->nombre}}</h1>
</section>
<!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h3 class="panel-title">Datos del Proyecto</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Cliente</label>
                                    <span class="form-control">{{$prospecto->cliente->nombre}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Nombre de Proyecto</label>
                                    <span class="form-control">{{$prospecto->nombre}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Diseñador</label>
                                    <span class="form-control">
                                      @if($prospecto->vendedor_id)
                                      {{$prospecto->vendedor->nombre}}
                                      @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <span class="form-control" style="min-height:68px;">{{$prospecto->descripcion}}</span>
                                </div>
                            </div>
                        </div>
                       @if($prospecto->es_prospecto == "si")
                        <div class="row" >
                          <div class="col-md-3">
                              <div class="form-group">
                                <label for="prospecto.fecha_cierre" class="control-label">Fecha aproximada de cierre</label>
                                <br />
                                <span class="form-control">{{$prospecto->fecha_cierre_formated}}</span>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="control-label">Proyección de venta en USD</label>
                                <span class="form-control" >{{$prospecto->proyeccion_venta}}</span>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="control-label">Factibilidad</label>
                                <span class="form-control" >{{$prospecto->factibilidad}}</span>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <span class="form-control" >{{$prospecto->estatus}}</span>
                              </div>
                            </div>

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <h4>Actividades</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordred">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Productos Ofrecidos</th>
                    <th>Descripción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($prospecto->actividades as $actividad)
                  <tr>
                    <td>{{$actividad->fecha_formated}}</td>
                    <td>{{$actividad->tipo->nombre}}</td>
                    <td>
                      @foreach($actividad->productos_ofrecidos as $index => $ofrecido)
                      <span>{{$index+1}}.- {{$ofrecido->nombre}}</span><br />
                      @endforeach
                    </td>
                    @if($actividad->tipo->id==4) <!-- Cotización enviada -->
                    <td>
                      <a class="btn btn-warning" title="PDF" href="{{$actividad->descripcion}}" target="_blank">
                        <i class="far fa-file-pdf"></i>
                      </a>
                    </td>
                    @else
                    <td>{{$actividad->descripcion}}</td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        

        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
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
                                                <a class="btn btn-xs btn-success" title="PDF" :href="'/storage/'+cotizacion.archivo"
                                                   :download="'C '+cotizacion.numero+' Robinson '+prospecto.nombre+'.pdf'">
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
                                                
                                                <template v-else>
                                                    <!--
                                                    <button class="btn btn-xs btn-warning" title="Editar"
                                                            @click="editar(index, cotizacion)">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    -->
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
                                                <!--
                                                <button class="btn btn-xs btn-white" title="Copiar"
                                                        @click="copiar(index, cotizacion)">
                                                    <i class="far fa-copy"></i>
                                                </button>
                                                -->
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

        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h4 class="panel-title">Cotizaciones Aprobadas</h4>
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
                                        <tr v-for="(cotizacion, index) in prospecto.cotizaciones_aprobadas">
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
                                                <a class="btn btn-xs btn-success" title="PDF" :href="'/storage/'+cotizacion.archivo"
                                                   :download="'C '+cotizacion.numero+' Robinson '+prospecto.nombre+'.pdf'">
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
                                                
                                                <template v-else>
                                                    <!--
                                                    <button class="btn btn-xs btn-warning" title="Editar"
                                                            @click="editar(index, cotizacion)">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    -->
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
                                                <!--
                                                <button class="btn btn-xs btn-white" title="Copiar"
                                                        @click="copiar(index, cotizacion)">
                                                    <i class="far fa-copy"></i>
                                                </button>
                                                -->
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
                                            <td>@{{totales_cotizaciones2.pesos | formatoMoneda}} <br/>
                                                @{{totales_cotizaciones2.dolares | formatoMoneda}}
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

        <div class="row">
            <div class="col-lg-12">
              <div class="panel">
                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                    <h4 class="panel-title">Lista de Ordenes de Compra</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                    </div>
                    <div class="row">
                      <div class="table-responsive">
                        <table id="tabla" class="table table-bordred">
                          <thead>
                            <tr style="background-color:#12160F; color:#B68911;">
                              <th class="color_text">#</th>
                              <th class="color_text">Numero</th>
                              <th class="color_text">Proveedor</th>
                              <th class="color_text">Producto</th>
                              <th class="color_text">Cantidad</th>
                              <th class="color_text">Estatus</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(orden,index) in ordenes">
                              <td>@{{index+1}}</td>
                              <td>@{{orden.numero}}</td>
                              <td>@{{orden.proveedor_empresa}}</td>
                              <td>
                                <span v-for="(entrada, index) in orden.entradas">
                                  @{{index+1}}.- @{{entrada.producto.nombre}} <br />
                                </span>
                              </td>
                              <td>
                                <span v-for="(entrada, index) in orden.entradas">
                                  @{{index+1}}.-
                                    <span v-if="entrada.conversion">@{{entrada.cantidad_convertida}} @{{entrada.conversion}}</span>
                                    <span v-else>@{{entrada.cantidad}} @{{entrada.medida}}</span>
                                  <br />
                                </span>
                              </td>
                              <td>@{{orden.status}}</td>
                              <td class="text-right">
                                <template v-if="orden.status!='Pendiente' && orden.status!='Cancelada'">
                                  <a class="btn btn-xs btn-info" title="Ver"
                                    :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id" target="_blank">
                                    <i class="far fa-eye"></i>
                                  </a>
                                  <a v-if="orden.archivo" class="btn btn-xs btn-warning" title="PDF" :href="orden.archivo"
                                    :download="'ROBINSON-PO '+orden.numero+' '+orden.proyecto_nombre+'.pdf'">
                                    <i class="far fa-file-pdf"></i>
                                  </a>
                                </template>
                                <a v-if="orden.status=='Pendiente' || orden.status=='Rechazada'"
                                  class="btn btn-xs btn-success" title="Editar"
                                  :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id+'/editar'" target="
                                  _blank">
                                  <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a v-if="orden.status=='Pendiente'"
                                  class="btn btn-xs btn-warning" title="Comprar"
                                  :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id" target="_blank">
                                  <i class="fas fa-cash-register"></i>
                                </a>
                                
                                @role('Administrador')
                                <button v-if="orden.status=='Por Autorizar'" class="btn btn-xs btn-primary"
                                  title="Aprobar" @click="ordenModal=orden; openAprobar=true;">
                                  <i class="far fa-thumbs-up"></i>
                                </button>
                                
                                <button v-if="orden.status=='Aprobada'" class="btn btn-xs btn-purple"
                                  title="Confirmar" @click="ordenModal=orden; openConfirmar=true; ordenModal.monto_total_flete=orden.flete;ordenModal.monto_total_producto=orden.subtotal;ordenModal.tax=orden.iva;sumartot()">
                                  <i class="fas fa-clipboard-check"></i>
                                </button>
                                @endrole
                                <button v-if="orden.status=='Por Autorizar'" class="btn btn-xs btn-danger"
                                  title="Rechazar" @click="ordenModal=orden; openRechazar=true;">
                                  <i class="far fa-thumbs-down"></i>
                                </button>
                                <button v-if="orden.status!='Aprobada' && orden.status!='Confirmada' && orden.status!='Cancelada'" 
                                  class="btn btn-xs btn-danger" title="Cancelar" @click="cancelarOrden(orden)">
                                  <i class="fas fa-times"></i>
                                </button>
                                <a v-if="orden.status=='Confirmada'" class="btn btn-xs text-primary" title="Confirmación Fabrica" 
                                  :href="orden.confirmacion_fabrica"
                                  target="_blank">
                                  <i class="fas fa-clipboard-check"></i>
                                </a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                     </div>
                    </div>
                </div>

              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
              <div class="panel">
                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                  <h3 class="panel-title">
                    <span class="p-10">Lista de Cuentas por Cobrar</span>
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordred" id="tabla_cuentas">
                      <thead>
                        <tr style="background-color:#12160F; color:#B68911;">
                          <th class="color_text">#</th>
                          <th class="color_text"># Cotizacion</th>
                          <th class="color_text">Ejecutivo</th>
                          <th class="color_text">Cliente</th>
                          <th class="color_text">Proyecto</th>
                          <th class="color_text">Condiciones Pago</th>
                          <th class="color_text">Moneda</th>
                          <th class="color_text">Total</th>
                          <th class="color_text">Facturado</th>
                          <th class="color_text">Pagado</th>
                          <th class="color_text">Pendiente</th>
                          <th style="min-width:70px;"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(cuenta, index) in cuentas">
                          <td>@{{index+1}}</td>
                          <td>@{{cuenta.cotizacion.numero}}</td>
                          <td>@{{cuenta.cotizacion.user.name}}</td>
                          <td>@{{cuenta.cliente}}</td>
                          <td>@{{cuenta.proyecto}}</td>
                          <td>@{{cuenta.condiciones}}</td>
                          <td >@{{cuenta.moneda}}</td>
                          <td >@{{cuenta.total | formatoMoneda}}</td>
                          <td >@{{cuenta.facturado | formatoMoneda}}</td>
                          <td >@{{cuenta.pagado | formatoMoneda}}</td>
                          <td >@{{cuenta.pendiente | formatoMoneda}}</td>
                          <td class="text-right">
                            <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                              :href="'/cuentas-cobrar/'+cuenta.id" target="_blank">
                              <i class="far fa-eye"></i>
                            </a>
                            <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Facturas"
                              :href="'/cuentas-cobrar/'+cuenta.id+'/editar'">
                              <i class="fas fa-file-invoice-dollar"></i>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                          <tr>
                              <th colspan="11" style="text-align:right">Total MXN:</th>
                              <th></th>
                          </tr>
                          <tr>
                              <th colspan="11" style="text-align:right">Total USD:</th>
                              <th></th>
                          </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
              <div class="panel">
                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                  <h3 class="panel-title">
                    <span class="p-10">Lista de Ordenes en Proceso</span>
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="tabla_proceso" class="table table-bordred" style="width:100%;"
                      data-page-length="-1">
                      <thead>
                        <tr style="background-color:#12160F; color:#B68911;">
                          <th class="color_text">Orden Numero</th>
                          <th class="color_text">#</th>
                          <th class="color_text">Cliente</th>
                          <th class="color_text">Ejecutivo</th>
                          <th class="color_text">Proyecto</th>
                          <th class="color_text">Proveedor</th>
                          <th class="color_text">Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(orden,index) in ordenes_proceso">
                          <td>@{{orden.numero}}</td>
                          <td>@{{index+1}}</td>
                          <td>@{{orden.orden_compra.cliente_nombre}}</td>
                          <td>@{{orden.orden_compra.proyecto.cotizacion.user.name}}</td>
                          <td>@{{orden.orden_compra.proyecto_nombre}}</td>
                          <td>@{{orden.orden_compra.proveedor_empresa}}</td>
                          <td>@{{orden.status}}</td>
                          <td class="text-right">
                            {{-- <a class="btn btn-xs btn-info" title="Ver"
                              :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id" target="_blank">
                              <i class="far fa-eye"></i>
                            </a> --}}
                            <button class="btn btn-xs btn-unique"
                              title="Historial" @click="ordenHistorial=orden; openHistorial=true;">
                              <i class="fas fa-history"></i>
                            </button>
                            {{-- Descarga de archivos --}}
                            <a v-if="orden.orden_compra.archivo" class="btn btn-xs btn-warning"
                              title="PDF" :href="orden.orden_compra.archivo"
                              :download="'ROBINSON-PO'+orden.numero+orden.orden_compra.proyecto_nombre+'.pdf'">
                              <i class="far fa-file-pdf"></i>
                            </a>
                            <template v-if="orden.factura" >
                              <a class="btn btn-xs btn-info"
                                title="Factura" :href="orden.factura"
                                :download="'Factura orden proceso '+orden.numero">
                                <i class="fas fa-file-invoice-dollar"></i>
                              </a>
                              <a class="btn btn-xs btn-info"
                                title="Packing List" :href="orden.packing"
                                :download="'Packing list orden proceso '+orden.numero">
                                <i class="fas fa-list-ol"></i>
                              </a>
                              <a v-if="orden.bl" class="btn btn-xs btn-info"
                                title="BL" :href="orden.bl"
                                :download="'BL orden proceso '+orden.numero">
                                <i class="fas fa-file"></i>
                              </a>
                              <a v-if="orden.certificado" class="btn btn-xs btn-info"
                                title="Certificado" :href="orden.certificado"
                                :download="'Certificado orden proceso '+orden.numero">
                                <i class="fas fa-file-contract"></i>
                              </a>
                            </template>
                            <a v-if="orden.deposito_warehouse" class="btn btn-xs btn-info"
                              title="Deposito Warehouse" :href="orden.deposito_warehouse"
                              :download="'Deposito Warehouse orden '+orden.numero">
                              <i class="far fa-file-word"></i>
                            </a>
                            <template v-if="orden.gastos" >
                              <a class="btn btn-xs btn-info"
                                title="Cuenta de gastos" :href="orden.gastos"
                                :download="'Cuenta gastos orden proceso '+orden.numero">
                                <i class="fas fa-file-invoice"></i>
                              </a>
                              <a class="btn btn-xs btn-info"
                                title="Pago" :href="orden.pago"
                                :download="'Pago orden proceso '+orden.numero">
                                <i class="fas fa-money-check-alt"></i>
                              </a>
                            </template>
                            <a v-if="orden.carta_entrega" class="btn btn-xs btn-info"
                              title="Carta de Entrega" :href="orden.carta_entrega"
                              :download="'Carta de Entrega orden '+orden.numero">
                              <i class="fas fa-people-carry"></i>
                            </a>
                            {{-- Botones de acciones --}}
                            <button v-if="orden.status=='En fabricación'"
                              class="btn btn-xs btn-brown" title="Embarcar"
                              @click="embarcarModal(orden)">
                              <i class="fas fa-dolly-flatbed"></i>
                            </button>
                            <button v-if="orden.status=='Embarcado de fabrica'" class="btn btn-xs btn-success"
                              title="Frontera" @click="fronteraModal(orden)">
                              <i class="fas fa-flag"></i>
                            </button>
                            <button v-if="orden.status=='En frontera'" class="btn btn-xs btn-warning"
                              title="Aduana" @click="aduanaModal(orden)">
                              <i class="fas fa-warehouse"></i>
                            </button>
                            <button v-if="orden.status=='Aduana'" class="btn btn-xs btn-unique"
                              title="Importación" @click="updateStatus(orden)">
                              <i class="fas fa-ship"></i>
                            </button>
                            <button v-if="orden.status=='Proceso de Importación'" class="btn btn-xs btn-success"
                              title="Liberadar Aduana" @click="updateStatus(orden)">
                              <i class="fas fa-lock-open"></i>
                            </button>
                            <button v-if="orden.status=='Liberado de Aduana'" class="btn btn-xs btn-elegant"
                              title="Embarque final" @click="updateStatus(orden)">
                              <i class="fas fa-shipping-fast"></i>
                            </button>
                            <button v-if="orden.status=='Embarque al destino Final'" class="btn btn-xs btn-purple"
                              title="Descarga" @click="updateStatus(orden)">
                              <i class="fas fa-dolly"></i>
                            </button>
                            <button v-if="orden.status=='Descarga'" class="btn btn-xs btn-default"
                              title="Entrega" @click="entregaModal(orden)">
                              <i class="fas fa-box"></i>
                            </button>
                            <button v-if="orden.status=='Entregado' && !orden.fecha_real_entrega"
                              class="btn btn-xs btn-info" title="Instalación"
                              @click="updateStatus(orden)">
                              <i class="fas fa-tools"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
              <div class="col-md-12 text-right">
                <a href="{{route('prospectos.index')}}" style="margin-top:25px; color:#000; background-color:#B3B3B3" class="btn btn-default">
                  Regresar
                </a>
              </div>
            </div>


          <!-- Enviar Modal -->
        <modal v-model="openNotas" :title="'Notas Cotización '+notas.cotizacion_id" :footer="false">
            <form class="" @submit.prevent="notasCotizacion()">
                <div class="form-group">
                    <label class="control-label">Notas</label>
                    <textarea name="mensaje" class="form-control" v-model="notas.mensaje" rows="8" cols="80">
          </textarea>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :disabled="cargando">Guardar</button>
                    <button type="button" class="btn btn-default"
                            @click="notas.cotizacion_id=0; notas.mensaje=''; openNotas=false;">
                        Cancelar
                    </button>
                </div>
            </form>
        </modal>
        <!-- /.Enviar Modal -->

        <!-- Enviar Modal -->
        <modal v-model="openEnviar" :title="'Enviar Cotizacion '+enviar.numero" :footer="false">
            <form class="" @submit.prevent="enviarCotizacion()">
                <div class="form-group">
                    <label class="control-label">Email(s)</label>
                    <select2multags :options="enviar.emailOpciones" v-model="enviar.email" style="width:100%;" required>
                    </select2multags>
                </div>
                <div class="form-group">
                    <label class="control-label">Mensaje</label>
                    <textarea name="mensaje" class="form-control" v-model="enviar.mensaje" rows="6" cols="80" required>
          </textarea>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :disabled="cargando">Enviar</button>
                </div>
            </form>
        </modal>
        <!-- /.Enviar Modal -->
        <!-- Aceptar Modal -->
        <modal v-model="openAceptar" :title="'Aceptar Cotizacion '+aceptar.cotizacion_id" :footer="false">
            <form class="" @submit.prevent="aceptarCotizacion()">
                <div class="form-group">
                    <label class="control-label">Comprobante Confirmacion</label>
                    <div class="file-loading">
                        <input id="comprobante" name="comprobante" type="file" ref="comprobante"
                               @change="fijarComprobante()" required/>
                    </div>
                    <div id="comprobante-file-errors"></div>
                </div>
                <div class="form-group">
                    <label class="control-label">Fecha Aceptación</label>
                    <br />
                    <dropdown style="width:100%;">
                        <div class="input-group" >
                            <div class="input-group-btn">
                                <btn class="dropdown-toggle" style="background-color:#fff;">
                                    <i class="fas fa-calendar"></i>
                                </btn>
                            </div>
                            <input class="form-control" type="text" name="fecha_comprobante" placeholder="DD/MM/YYYY" v-model="aceptar.fecha_comprobante" readonly/>
                        </div>
                        <template slot="dropdown">
                            <li>
                                <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                                             format="dd/MM/yyyy" :date-parser="dateParser" v-model="aceptar.fecha_comprobante"/>
                            </li>
                        </template>
                    </dropdown>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                    <button type="button" class="btn btn-default"
                            @click="aceptar.cotizacion_id=0; openAceptar=false;">
                        Cancelar
                    </button>
                </div>
            </form>
        </modal>
        <!-- /.Aceptar Modal -->
        <!-- Copiar Modal -->
        <modal v-model="openCopiar" :title="'Copiar Cotizacion'" :footer="false">
            <form class="" @submit.prevent="copiarCotizacion()">
                <div class="form-group">
                    <label class="control-label">Proyecto Destino</label>
                    <select name="proyecto_id" v-model="copiar_cotizacion.proyecto_id"
                            class="form-control" required id="proyecto-select" style="width: 300px;">
                        @foreach($proyectos as $proyecto)
                            <option value="{{$proyecto->id}}" >{{$proyecto->nombre}}--{{$proyecto->cliente->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :disabled="cargando">Guardar</button>
                    <button type="button" class="btn btn-default"
                            @click="openCopiar=false;">
                        Cancelar
                    </button>
                </div>
            </form>
        </modal>
        <!-- /.Copiar Modal -->

        <!-- Confirmar Modal -->
          <modal v-model="openConfirmar" :title="'Confirmar orden '+ordenModal.numero" :footer="false">
            <form class="" @submit.prevent="confirmarOrden()">
              <div class="form-group">
                <label class="control-label">Confirmación Fabrica</label>
                <div class="file-loading">
                  <input id="confirmacion" name="confirmacion" type="file" ref="confirmacion"
                    @change="fijarConfirmacion()" required />
                </div>
                <div id="confirmacion-file-errors"></div>
                <div class="col-md-4">
                  <label class="control-label">Monto total del Producto</label>
                  <input type="number" step=0.01 class="form-control" v-model="ordenModal.monto_total_producto" min="0.0" @change="sumartotal('monto_producto')"
                     />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Monto total del Flete</label>
                  <input type="number" step=0.01 class="form-control" v-model="ordenModal.monto_total_flete" min="0.0" @change="sumartotal('monto_flete')"
                     />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Posibles Aumentos</label>
                  <input type="number" step=0.01 class="form-control" v-model="ordenModal.posibles_aumentos" min="0.0" @change="sumartotal('posibles')"
                     />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Tax</label>
                  <input type="number" step=0.01 class="form-control" v-model="ordenModal.tax" min="0.0" @change="sumartotal('tax')"
                     />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Monto total a Pagar</label>
                  <input type="number" step=0.01 class="form-control" v-model="ordenModal.monto_total_pagar" min="0.0" readonly 
                     />
                </div>
              </div>
              <div class="form-group text-right">
                <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                <button type="button" class="btn btn-default"
                  @click="ordenModal={}; openConfirmar=false;">
                  Cancelar
                </button>
              </div>
            </form>
          </modal>
          <!-- /.Confirmar Modal -->


          <!-- Historial Modal -->
          <modal v-model="openHistorial" title="Historial de cambios de status" :footer="false">
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <th>Status</th>
                      <th>Fecha estimada</th>
                      <th>Fecha real</th>
                    </thead>
                    <tbody>
                      <tr v-for="status in statuses">
                        <td>@{{ status.status }}</td>
                        <td v-if="ordenHistorial[status.propiedad_estimada]">@{{ ordenHistorial[status.propiedad_estimada] }}</td>
                        <td v-else>
                          <dropdown>
                            <div class="input-group">
                              <div class="input-group-btn">
                                <btn class="dropdown-toggle" style="background-color:#fff;">
                                  <i class="fas fa-calendar"></i>
                                </btn>
                              </div>
                              <input class="form-control" type="text" placeholder="DD/MM/YYYY"
                                v-model="ordenHistorial[status.propiedad_estimada]" readonly
                                style="width:120px;"
                              />
                            </div>
                            <template slot="dropdown">
                              <li>
                                <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                                format="dd/MM/yyyy" :date-parser="dateParser"
                                v-model="ordenHistorial[status.propiedad_estimada]"/>
                              </li>
                            </template>
                          </dropdown>
                        </td>
                        <td>@{{ ordenHistorial[status.propiedad_real] }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="form-group text-right">
              <button type="button" class="btn btn-unique" @click="fijarFechasEstimadas()">Fijar fechas estimadas</button>
              <button type="button" class="btn btn-default" @click="openHistorial=false;">Aceptar</button>
            </div>
          </modal>
          <!-- /.Historial Modal -->

          <!-- Embarcar Modal -->
          <modal v-model="openEmbarcar" :title="'Embarcar Orden '+embarcar.numero" :footer="false">
            <h4>Por favor proporcione los siguientes documentos:</h4>
            <form class="" @submit.prevent="embarcarOrden">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Factura</label>
                    <div class="file-loading">
                      <input id="factura" name="factura" type="file" ref="factura"
                      @change="fijarDocumentoEmbarque('factura')" required />
                    </div>
                    <div id="factura-file-errors"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Packing List</label>
                    <div class="file-loading">
                      <input id="packing" name="packing" type="file" ref="packing"
                      @change="fijarDocumentoEmbarque('packing')" required />
                    </div>
                    <div id="packing-file-errors"></div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">BL</label>
                    <div class="file-loading">
                      <input id="bl" name="bl" type="file" ref="bl"
                      @change="fijarDocumentoEmbarque('bl')" />
                    </div>
                    <div id="bl-file-errors"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Certificado de Origen</label>
                    <div class="file-loading">
                      <input id="certificado" name="certificado" type="file" ref="certificado"
                      @change="fijarDocumentoEmbarque('certificado')" />
                    </div>
                    <div id="certificado-file-errors"></div>
                  </div>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                <button type="button" class="btn btn-default"
                  @click="embarcar.orden_id=0; openEmbarcar=false;">
                  Cancelar
                </button>
              </div>
            </form>
          </modal>
          <!-- /.Embarcar Modal -->

          <!-- Frontera Modal -->
          <modal v-model="openFrontera" :title="'Poner orden '+frontera.numero+' en frontera'" :footer="false">
            <h4>Por favor proporcione los siguientes documentos:</h4>
            <form class="" @submit.prevent="fronteraOrden">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Deposito de warehouse</label>
                    <div class="file-loading">
                      <input id="warehouse" name="deposito_warehouse" type="file"
                      ref="deposito_warehouse" @change="fijarDocumentoFrontera('deposito_warehouse')"
                      required />
                    </div>
                    <div id="warehouse-file-errors"></div>
                  </div>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                <button type="button" class="btn btn-default"
                  @click="frontera.orden_id=0; openFrontera=false;">
                  Cancelar
                </button>
              </div>
            </form>
          </modal>
          <!-- /.Frontera Modal -->

          <!-- Aduana Modal -->
          <modal v-model="openAduana" :title="'Mandar orden '+aduana.numero+' a Aduana'" :footer="false">
            <h4>Por favor proporcione los siguientes documentos:</h4>
            <form class="" @submit.prevent="aduanaOrden">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Cuenta de Gastos</label>
                    <div class="file-loading">
                      <input id="gastos" name="gastos" type="file" ref="gastos"
                      @change="fijarDocumentoAduana('gastos')" required />
                    </div>
                    <div id="gastos-file-errors"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Pago</label>
                    <div class="file-loading">
                      <input id="pago" name="pago" type="file" ref="pago"
                      @change="fijarDocumentoAduana('pago')" required />
                    </div>
                    <div id="pago-file-errors"></div>
                  </div>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                <button type="button" class="btn btn-default"
                  @click="aduana.orden_id=0; openAduana=false;">
                  Cancelar
                </button>
              </div>
            </form>
          </modal>
          <!-- /.Aduana Modal -->

          <!-- Frontera Modal -->
          <modal v-model="openEntrega" :title="'Poner orden '+entrega.numero+' en entrega'" :footer="false">
            <h4>Por favor proporcione los siguientes documentos:</h4>
            <form class="" @submit.prevent="entregaOrden">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Carta de entrega</label>
                    <div class="file-loading">
                      <input id="carta" name="carta_entrega" type="file"
                      ref="carta_entrega" @change="fijarDocumentoEntrega('carta_entrega')"
                      required />
                    </div>
                    <div id="carta-file-errors"></div>
                  </div>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                <button type="button" class="btn btn-default"
                  @click="entrega.orden_id=0; openEntrega=false;">
                  Cancelar
                </button>
              </div>
            </form>
          </modal>
          <!-- /.Frontera Modal -->

        
    </section>



@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}" ></script>
<script>

    // Used for creating a new FileList in a round-about way
        function FileListItem(a) {
            a = [].slice.call(Array.isArray(a) ? a : arguments)
            for (var c, b = c = a.length, d = !0; b-- && d;) d = a[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(a[c])
            return b.files
        }

  Vue.config.devtools = true;
        const app = new Vue({
            el: '#content',
            data: {
                statuses: [
                {
                  status: 'En fabricación',
                  propiedad_estimada: 'fecha_estimada_fabricacion',
                  propiedad_real: 'fecha_real_fabricacion'
                },
                {
                  status: 'Embarcado de fabrica',
                  propiedad_estimada: 'fecha_estimada_embarque',
                  propiedad_real: 'fecha_real_embarque'
                },
                {
                  status: 'En frontera',
                  propiedad_estimada: 'fecha_estimada_frontera',
                  propiedad_real: 'fecha_real_frontera'
                },
                {
                  status: 'Aduana',
                  propiedad_estimada: 'fecha_estimada_aduana',
                  propiedad_real: 'fecha_real_aduana'
                },
                {
                  status: 'Proceso de Importación',
                  propiedad_estimada: 'fecha_estimada_importacion',
                  propiedad_real: 'fecha_real_importacion'
                },
                {
                  status: 'Liberado de Aduana',
                  propiedad_estimada: 'fecha_estimada_liberado_aduana',
                  propiedad_real: 'fecha_real_liberado_aduana'
                },
                {
                  status: 'Embarque al destino Final',
                  propiedad_estimada: 'fecha_estimada_embarque_final',
                  propiedad_real: 'fecha_real_embarque_final'
                },
                {
                  status: 'Descarga',
                  propiedad_estimada: 'fecha_estimada_descarga',
                  propiedad_real: 'fecha_real_descarga'
                },
                {
                  status: 'Entrega',
                  propiedad_estimada: 'fecha_estimada_entrega',
                  propiedad_real: 'fecha_real_entrega'
                },
                {
                  status: 'Instalacion',
                  propiedad_estimada: 'fecha_estimada_instalacion',
                  propiedad_real: 'fecha_real_instalacion'
                },
                {{-- {
                  status: '',
                  propiedad_estimada: fecha_estimada_,
                  propiedad_real: fecha_real_
                }, --}}
              ],
                ordenHistorial: {},
                ordenModal : {},
                locale: localeES,
                prospecto: {!! json_encode($prospecto) !!},
                ordenes: {!! json_encode($ordenes) !!},
                 cuentas: {!! json_encode($cuentas) !!},
                 ordenes_proceso: {!! json_encode($ordenes_proceso) !!},
                 notas: {
                    cotizacion_id: 0,
                    mensaje: ""
                },
                enviar_a:'',
                enviar: {
                    cotizacion_id: 0,
                    numero: 0,
                    email: [],
                    emailOpciones: [
                            @foreach($prospecto->cliente->contactos as $contacto)
                            @foreach($contacto->emails as $email)
                        {
                            id: "{{$email->email}}", text: "{{$email->email}}"
                        },
                        @endforeach
                        @endforeach
                    ],
                    mensaje: "Buenas tardes  .\n\nAnexo a la presente encontrarán la cotización solicitada de {{$prospecto->descripcion}}  para {{$prospecto->nombre}} .\n\nEsperamos esta información les sea de utilidad y quedamos a sus órdenes para cualquier duda o comentario.\n\nSaludos,\n\n{{auth()->user()->name}}.\n{{auth()->user()->email}}\nRobinson Contract Resources"
                },
                aceptar: {
                    cotizacion_id: 0,
                    comprobante: "",
                    fecha_comprobante: ""
                },
                copiar_cotizacion: {
                    proyecto_id : '',
                    cotizacion_id :'',
                },
                embarcar: {
                    orden_id: 0,
                    numero: 0,
                    factura: '',
                    packing: '',
                    bl: '',
                    certificado: ''
                  },
                  frontera: {
                    orden_id: 0,
                    numero: 0,
                    deposito_warehouse: ''
                  },
                  aduana: {
                    orden_id: 0,
                    numero: 0,
                    gastos: '',
                    pago: ''
                  },
                  entrega: {
                    orden_id: 0,
                    numero: 0,
                    carta_entrega: ''
                  },
                openNotas: false,
                openAceptar: false,
                openEnviar: false,
                openCopiar : false,
                openConfirmar: false,
                cargando: false,
                openHistorial: false,
                openEmbarcar: false,
                openFrontera: false,
                openAduana: false,
                openEntrega: false
            },
            
            filters: {
              formatoMoneda(numero){
                return accounting.formatMoney(numero, "$", 2);
              }
            },
            computed: {
                totales_cotizaciones() {
                    var dolares = 0, pesos = 0;
                    this.prospecto.cotizaciones.forEach(function (cotizacion) {
                        if (cotizacion.moneda == "Pesos") pesos += cotizacion.total;
                        else dolares += cotizacion.total;
                    });
                    return {"dolares": dolares, "pesos": pesos}
                },
                totales_cotizaciones2() {
                    var dolares = 0, pesos = 0;
                    this.prospecto.cotizaciones_aprobadas.forEach(function (cotizacion) {
                        if (cotizacion.moneda == "Pesos") pesos += cotizacion.total;
                        else dolares += cotizacion.total;
                    });
                    return {"dolares": dolares, "pesos": pesos}
                }
            },
            mounted(){

                $("#factura").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#factura-file-errors',
              });
              $("#packing").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#packing-file-errors',
              });
              $("#bl").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#bl-file-errors',
              });
              $("#certificado").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#certificado-file-errors',
              });
              $("#warehouse").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#warehouse-file-errors',
              });
              $("#gastos").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#gastos-file-errors',
              });
              $("#pago").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#certificado-file-errors',
              });
              $("#carta").fileinput({
                language: 'es',
                showPreview: false,
                showUpload: false,
                showRemove: false,
                browseLabel: '',
                allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                elErrorContainer: '#carta-file-errors',
              });

                $("#confirmacion").fileinput({
                    language: 'es',
                    showPreview: false,
                    showUpload: false,
                    showRemove: false,
                    allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                    elErrorContainer: '#confirmacion-file-errors',
                  });

                $("#comprobante").fileinput({
                    language: 'es',
                    showPreview: false,
                    showUpload: false,
                    showRemove: false,
                    allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                    elErrorContainer: '#comprobante-file-errors',
                });

              this.tabla = $("#tabla_cuentas").DataTable({
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    var formato = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    //datos de la tabla con filtros aplicados
                    var datos= api.columns([7,6], {search: 'applied'}).data();
                    var totalMxn = 0;
                    var totalUsd = 0;
                    //suma de montos
                    datos[0].forEach(function(element, index){
                        if(datos[1][index]=="Dolares"){
                            totalUsd+=formato(element)
                        }else{
                            totalMxn+=formato(element)
                        }
                    });
         
                    // Actualizar
                    var nCells = row.getElementsByTagName('th');
                    nCells[1].innerHTML = accounting.formatMoney(totalMxn, "$", 2);

                    var secondRow = $(row).next()[0]; 
                    var nCells = secondRow.getElementsByTagName('th');
                    nCells[1].innerHTML = accounting.formatMoney(totalUsd, "$", 2);
                }

              });
            },
            methods :{
              embarcarModal(orden){
                this.embarcar.orden_id=orden.id;
                this.embarcar.numero=orden.numero;
                this.openEmbarcar=true;
              },
              fronteraModal(orden){
                this.frontera.orden_id=orden.id;
                this.frontera.numero=orden.numero;
                this.openFrontera=true;
              },
              aduanaModal(orden){
                this.aduana.orden_id=orden.id;
                this.aduana.numero=orden.numero;
                this.openAduana=true;
              },
              entregaModal(orden){
                this.entrega.orden_id=orden.id;
                this.entrega.numero=orden.numero;
                this.openEntrega=true;
              },
              fijarDocumentoEmbarque(documento){
                this.embarcar[documento] = this.$refs[documento].files[0];
              },
              fijarDocumentoFrontera(documento){
                this.frontera[documento] = this.$refs[documento].files[0];
              },
              fijarDocumentoAduana(documento){
                this.aduana[documento] = this.$refs[documento].files[0];
              },
              fijarDocumentoEntrega(documento){
                this.entrega[documento] = this.$refs[documento].files[0];
              },
              updateStatus(orden){
                this.cargando = true;
                axios.post('/ordenes-proceso/'+orden.id+'/updateStatus',{status:orden.status})
                .then(({data}) => {
                  for (propiedad in data.actualizados){
                    orden[propiedad] = data.actualizados[propiedad];
                  }

                  this.cargando = false;
                  swal({
                    title: "Orden Actualizada",
                    text: 'Se ha actualizado la orden',
                    type: "success"
                  });
                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });
              },
              fijarFechasEstimadas(){
                this.cargando = true;
                axios.post(
                  '/ordenes-proceso/'+this.ordenHistorial.id+'/fijarFechasEstimadas',
                  this.ordenHistorial
                )
                .then(({data}) => {
                  this.ordenes.find(function(orden){
                    if(this.ordenHistorial.id == orden.id){
                      for (propiedad in data.actualizados){
                        orden[propiedad] = data.actualizados[propiedad];
                      }
                      return true;
                    }
                  }, this);
                  this.cargando = false;
                  this.openHistorial = false;
                  this.ordenHistorial = {};
                  swal({
                    title: "Fechas Actualizadas",
                    text: 'Se han actualizado las fechas estimadas de la orden',
                    type: "success"
                  });
                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });
              },//fin fijarFechasEstimadas
              embarcarOrden(){
                var formData = objectToFormData(this.embarcar, {indices:true});

                this.cargando = true;
                axios.post('/ordenes-proceso/'+this.embarcar.orden_id+'/embarcar', formData, {
                  headers: { 'Content-Type': 'multipart/form-data'}
                })
                .then(({data}) => {
                  this.ordenes.find(function(orden){
                    if(this.embarcar.orden_id == orden.id){
                      orden.status = data.orden.status;
                      orden.factura = data.orden.factura;
                      orden.packing = data.orden.packing;
                      orden.bl = data.orden.bl;
                      orden.certificado = data.orden.certificado;
                      orden.fecha_real_fabricacion = data.orden.fecha_real_fabricacion;
                      return true;
                    }
                  }, this);

                  this.embarcar = {
                    orden_id: 0,
                    factura: '',
                    packing: '',
                    bl: '',
                    certificado: ''
                  };
                  $("#factura").fileinput('clear');
                  $("#packing").fileinput('clear');
                  $("#bl").fileinput('clear');
                  $("#certificado").fileinput('clear');
                  this.openEmbarcar = false;
                  this.cargando = false;
                  swal({
                    title: "Orden Embarcada",
                    text: 'La orden ha pasado al status "Embarcado de fabrica"',
                    type: "success"
                  });
                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });
              },//fin embarcarOrden
              fronteraOrden(){
                var formData = objectToFormData(this.frontera, {indices:true});

                this.cargando = true;
                axios.post('/ordenes-proceso/'+this.frontera.orden_id+'/frontera', formData, {
                  headers: { 'Content-Type': 'multipart/form-data'}
                })
                .then(({data}) => {
                  this.ordenes.find(function(orden){
                    if(this.frontera.orden_id == orden.id){
                      orden.status = data.orden.status;
                      orden.deposito_warehouse = data.orden.deposito_warehouse;
                      orden.fecha_real_embarque = data.orden.fecha_real_embarque;
                      return true;
                    }
                  }, this);

                  this.frontera = {
                    orden_id: 0,
                    deposito_warehouse: '',
                  };
                  $("#warehouse").fileinput('clear');
                  this.openFrontera = false;
                  this.cargando = false;
                  swal({
                    title: "Orden en frontera",
                    text: 'La orden ha pasado al status "En frontera"',
                    type: "success"
                  });
                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });
              },//fin fronteraOrden
              aduanaOrden(){
                var formData = objectToFormData(this.aduana, {indices:true});

                this.cargando = true;
                axios.post('/ordenes-proceso/'+this.aduana.orden_id+'/aduana', formData, {
                  headers: { 'Content-Type': 'multipart/form-data'}
                })
                .then(({data}) => {
                  this.ordenes.find(function(orden){
                    if(this.aduana.orden_id == orden.id){
                      orden.status = data.orden.status;
                      orden.gastos = data.orden.gastos;
                      orden.pago = data.orden.pago;
                      orden.fecha_real_frontera = data.orden.fecha_real_frontera;
                      return true;
                    }
                  }, this);

                  this.aduana = {
                    orden_id: 0,
                    gastos: '',
                    pago: '',
                  };
                  $("#gastos").fileinput('clear');
                  $("#pago").fileinput('clear');
                  this.openAduana = false;
                  this.cargando = false;
                  swal({
                    title: "Orden a Aduana",
                    text: 'La orden ha pasado al status "Aduana"',
                    type: "success"
                  });
                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });
              },//fin aduanaOrden
              entregaOrden(){
                var formData = objectToFormData(this.entrega, {indices:true});

                this.cargando = true;
                axios.post('/ordenes-proceso/'+this.entrega.orden_id+'/entrega', formData, {
                  headers: { 'Content-Type': 'multipart/form-data'}
                })
                .then(({data}) => {
                  this.ordenes.find(function(orden){
                    if(this.entrega.orden_id == orden.id){
                      orden.status = data.orden.status;
                      orden.carta_entrega = data.orden.carta_entrega;
                      orden.fecha_real_descarga = data.orden.fecha_real_descarga;
                      return true;
                    }
                  }, this);

                  this.entrega = {
                    orden_id: 0,
                    carta_entrega: '',
                  };
                  $("#carta").fileinput('clear');
                  this.openEntrega = false;
                  this.cargando = false;
                  swal({
                    title: "Orden Entregada",
                    text: 'La orden ha pasado al status "Entregado"',
                    type: "success"
                  });
                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });
              },//fin entregaOrden

                confirmarOrden(){

                var data = {};
                data.confirmacion_fabrica = this.ordenModal.confirmacion_fabrica;
                data.monto_total_producto = this.ordenModal.monto_total_producto;
                data.monto_total_pagar = this.ordenModal.monto_total_pagar;
                data.monto_total_flete = this.ordenModal.monto_total_flete;
                data.tax = this.ordenModal.tax;
                data.posibles_aumentos = this.ordenModal.posibles_aumentos;
                console.log(data);


                var formData = objectToFormData(data, {indices:true});

                this.cargando = true;
                axios.post('/proyectos-aprobados/'+this.ordenModal.proyecto_id+'/ordenes-compra/'+this.ordenModal.id+'/confirmar', 
                formData, { headers: { 'Content-Type': 'multipart/form-data'}
              })
                .then(({data}) => {
                  this.ordenModal.status = 'Confirmada';
                  this.ordenModal.confirmacion_fabrica = data.confirmacion;
                  
                  $("#confirmacion").fileinput('clear');

                  this.ordenModal.monto_total_producto = 0.0;
                  this.ordenModal.monto_total_pagar= 0.0;
                  this.ordenModal.monto_total_flete= 0.0;
                  this.ordenModal.tax= 0.0;
                  this.ordenModal.posibles_aumentos= 0.0;

                  this.openConfirmar = false;
                  this.cargando = false;
                  swal({
                    title: "Exito",
                    text: "La orden ha sido confirmada",
                    type: "success"
                  });

                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });


              },//fin confirmarOrden
                fijarConfirmacion(){
                    this.ordenModal.confirmacion_fabrica = this.$refs['confirmacion'].files[0];
                  },
                sumartot(){
                    this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_flete) + parseFloat(this.ordenModal.monto_total_producto);
                  },
                sumartotal(valor){
       
                    if (valor == 'monto_producto') {
                      if (this.ordenModal.monto_total_producto != null || this.ordenModal.monto_total_producto != "" ) {
                          this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_pagar) + parseFloat(this.ordenModal.monto_total_producto); 
                      }  
                    }
                    if (valor == 'monto_flete') {
                        if (this.ordenModal.monto_total_flete != null || this.ordenModal.monto_total_flete != "" ) {
                            this.ordenModal.monto_total_pagar =parseFloat(this.ordenModal.monto_total_pagar) +  parseFloat(this.ordenModal.monto_total_flete);  
                        }
                    }

                    if (valor == 'tax') {
                        if (this.ordenModal.tax != null || this.ordenModal.tax != "" ) {
                          this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_pagar) +  parseFloat(this.ordenModal.tax);  
                      }
                    }

                    if (valor == 'posibles') {
                        if (this.ordenModal.posibles_aumentos != null || this.ordenModal.posibles_aumentos != "" ) {
                          this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_pagar) +  parseFloat(this.ordenModal.posibles_aumentos);  
                      }

                    }
                    
                    
                    
                    
                    
                  },
                cancelarOrden(orden){
                    swal({
                      title: 'Cuidado',
                      text: "Cancelar la orden "+orden.id+"?",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Si, Cancelar',
                      cancelButtonText: 'No, dejar sin cancelar',
                    }).then((result) => {
                      if (result.value) {
                        axios.delete('/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id, {})
                        .then(({data}) => {
                          orden.status = 'Cancelada';
                          swal({
                            title: "Exito",
                            text: "La orden se ha cancelado",
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
                  },//cancelar
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
                            axios.delete('/prospectos/{{$prospecto->id}}/cotizacion/' + cotizacion.id, {})
                                .then(({data}) => {
                                    this.prospecto.cotizaciones.splice(index, 1);
                                    swal({
                                        title: "Exito",
                                        text: "La cotizacion ha sido borrado",
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
                },
                copiarCotizacion() {
                    this.cargando = true;
                    
                    axios.post('/prospectos/{{$prospecto->id}}/copiarCotizacion', this.copiar_cotizacion)
                        .then(({data}) => {
                            this.openCopiar = false;
                            this.cargando = false;
                            swal({
                                title: "Copia Guardada",
                                text: "La cotizaciones de ha copiado correctamente",
                                type: "success"
                            });
                            
                            window.location.href = "/prospectos/"+this.copiar_cotizacion.proyecto_id+"/cotizar";
                        })
                        .catch(({response}) => {
                            console.error(response);
                            this.cargando = false;
                            swal({
                                title: "Error",
                                text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },//fin CopiarCotizacion
                copiar2(index,cotizacion){
                    this.copiar_cotizacion.cotizacion_id = cotizacion.id;
                },
                fijarComprobante() {
                    this.aceptar.comprobante = this.$refs['comprobante'].files[0];
                },
                dateParser(value) {
                    return moment(value, 'DD/MM/YYYY').toDate().getTime();
                },
                notasCotizacion() {
                    this.cargando = true;
                    axios.post('/prospectos/{{$prospecto->id}}/notasCotizacion', this.notas)
                        .then(({data}) => {
                            this.prospecto.cotizaciones.find(function (cotizacion) {
                                if (this.notas.cotizacion_id == cotizacion.id) {
                                    cotizacion.notas2 = this.notas.mensaje;
                                    return true;
                                }
                            }, this);

                            this.notas = {
                                cotizacion_id: 0,
                                mensaje: ""
                            };
                            $("#comprobante").fileinput('clear');
                            this.openNotas = false;
                            this.cargando = false;
                            swal({
                                title: "Notas Guardadas",
                                text: "La notas de la cotización se han guardado correctamente",
                                type: "success"
                            });
                        })
                        .catch(({response}) => {
                            console.error(response);
                            this.cargando = false;
                            swal({
                                title: "Error",
                                text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },//fin notasCotizacion
                enviarCotizacion() {
                    this.cargando = true;
                    axios.post('/prospectos/{{$prospecto->id}}/enviarCotizacion', this.enviar)
                        .then(({data}) => {
                            this.enviar = {
                                cotizacion_id: 0,
                                numero: 0,
                                email: [],
                                emailOpciones: [
                                        @foreach($prospecto->cliente->contactos as $contacto)
                                        @foreach($contacto->emails as $email)
                                    {
                                        id: "{{$email->email}}", text: "{{$email->email}}"
                                    },
                                    @endforeach
                                    @endforeach
                                ],
                                mensaje: "Buen día.\n\nLe envió cotización para su consideración.\n\n{{auth()->user()->name}}.\nAtención del Cliente\nRobinson Contract Resources"
                            };
                            this.openEnviar = false;
                            this.cargando = false;
                            swal({
                                title: "Cotizacion Enviada",
                                text: "",
                                type: "success"
                            });
                        })
                        .catch(({response}) => {
                            console.error(response);
                            this.cargando = false;
                            swal({
                                title: "Error",
                                text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },//fin enviarCotizacion
                aceptarCotizacion() {
                    var formData = objectToFormData(this.aceptar, {indices: true});

                    this.cargando = true;
                    axios.post('/prospectos/{{$prospecto->id}}/aceptarCotizacion', formData, {
                        headers: {'Content-Type': 'multipart/form-data'}
                    })
                        .then(({data}) => {
                            this.prospecto.cotizaciones.find(function (cotizacion) {
                                if (this.aceptar.cotizacion_id == cotizacion.id) {
                                    cotizacion.proyecto_aprobado = data.proyecto_aprobado;
                                    cotizacion.aceptada = true;
                                    return true;
                                }
                            }, this);

                            this.aceptar = {
                                cotizacion_id: 0,
                                comprobante: "",
                                fecha_comprobante: ""
                            };
                            $("#comprobante").fileinput('clear');
                            this.openAceptar = false;
                            this.cargando = false;
                            swal({
                                title: "Cotizacion Aceptada",
                                text: "La cotización ha sido aceptada y se ha generado una cuenta por cobrar",
                                type: "success"
                            });
                        })
                        .catch(({response}) => {
                            console.error(response);
                            this.cargando = false;
                            swal({
                                title: "Error",
                                text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },//fin aceptarCotizacion
            }
        });


</script>
@stop
