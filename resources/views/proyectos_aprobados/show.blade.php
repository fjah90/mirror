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
                    <div class="panel-heading">
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
                <div class="panel-heading">
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
                            <tr>
                              <th>#</th>
                              <th>Numero</th>
                              <th>Proveedor</th>
                              <th>Producto</th>
                              <th>Cantidad</th>
                              <th>Estatus</th>
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
                                    :download="'INTERCORP-PO '+orden.numero+' '+orden.proyecto_nombre+'.pdf'">
                                    <i class="far fa-file-pdf"></i>
                                  </a>
                                </template>
                                <a v-if="orden.status=='Pendiente' || orden.status=='Rechazada'"
                                  class="btn btn-xs btn-success" title="Editar"
                                  :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id+'/editar'">
                                  <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a v-if="orden.status=='Pendiente'"
                                  class="btn btn-xs btn-warning" title="Comprar"
                                  :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
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
                <div class="panel-heading">
                  <h3 class="panel-title">
                    <span class="p-10">Lista de Cuentas por Cobrar</span>
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordred" id="tabla_cuentas">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th># Cotizacion</th>
                          <th>Ejecutivo</th>
                          <th>Cliente</th>
                          <th>Proyecto</th>
                          <th>Condiciones Pago</th>
                          <th>Moneda</th>
                          <th>Total</th>
                          <th>Facturado</th>
                          <th>Pagado</th>
                          <th>Pendiente</th>
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
                          <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#2bd32ba1'} : {'color':'#3ecbe7c4'}]">@{{cuenta.moneda}}</td>
                          <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#2bd32ba1'} : {'color':'#3ecbe7c4'}]">@{{cuenta.total | formatoMoneda}}</td>
                          <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#2bd32ba1'} : {'color':'#3ecbe7c4'}]">@{{cuenta.facturado | formatoMoneda}}</td>
                          <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#2bd32ba1'} : {'color':'#3ecbe7c4'}]">@{{cuenta.pagado | formatoMoneda}}</td>
                          <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#2bd32ba1'} : {'color':'#3ecbe7c4'}]">@{{cuenta.pendiente | formatoMoneda}}</td>
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
                <div class="panel-heading">
                  <h3 class="panel-title">
                    <span class="p-10">Lista de Ordenes en Proceso</span>
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="tabla_proceso" class="table table-bordred" style="width:100%;"
                      data-page-length="-1">
                      <thead>
                        <tr>
                          <th>Orden Numero</th>
                          <th>#</th>
                          <th>Cliente</th>
                          <th>Ejecutivo</th>
                          <th>Proyecto</th>
                          <th>Proveedor</th>
                          <th>Status</th>
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
                              :download="'INTERCORP-PO '+orden.numero+orden.orden_compra.proyecto_nombre+'.pdf'">
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
                    mensaje: "Buenas tardes  .\n\nAnexo a la presente encontrarán la cotización solicitada de {{$prospecto->descripcion}}  para {{$prospecto->nombre}} .\n\nEsperamos esta información les sea de utilidad y quedamos a sus órdenes para cualquier duda o comentario.\n\nSaludos,\n\n{{auth()->user()->name}}.\n{{auth()->user()->email}}\nIntercorp Contract Resources"
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
                openNotas: false,
                openAceptar: false,
                openEnviar: false,
                openCopiar : false,
                cargando: false
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
                                mensaje: "Buen día.\n\nLe envió cotización para su consideración.\n\n{{auth()->user()->name}}.\nAtención del Cliente\nIntercorp Contract Resources"
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