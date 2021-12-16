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
                    <h4 class="panel-title">Lista de Ordenes</h4>
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
                                    :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
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
                    <span class="p-10">Lista de Cuentas</span>
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
                              :href="'/cuentas-cobrar/'+cuenta.id">
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
                              :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
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
                ordenes: {!! json_encode($ordenes) !!},
                 cuentas: {!! json_encode($cuentas) !!},
                 ordenes_proceso: {!! json_encode($ordenes_proceso) !!},
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
        });


</script>
@stop