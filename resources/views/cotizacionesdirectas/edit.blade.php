@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cotizar Directo | @parent
@stop

@section('header_styles')
    <style>
        table td:first-child span.fa-grip-vertical:hover {
            cursor: move;
        }

        .color_text {
            color: #B3B3B3;
        }

        @media (min-width: 768px) {
            .modal-dialog {
                width: 680px;
                margin: 30px auto
            }
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1 style="font-weight: bolder;"></h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">

        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h4 class="panel-title">Editar Cotización</h4>
                    </div>
                    <div class="panel-body">
                        <form class="" @submit.prevent="agregarEntrada()">
                            <div class="row">
                                @can('editar numero cotizacion')
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Numero Cotización</label>
                                            <input type="number" step="1" min="0" name="numero"
                                                class="form-control" v-model="cotizacion.numero" />
                                        </div>
                                    </div>
                                @endcan
                                <div class="col-md-4">
                                    <label class="control-label">Proyecto Nombre *</label>
                                    <input class="form-control" type="text" name="prospecto_nombre"
                                        v-model="cotizacion.nombre_proyecto" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Cliente</label>
                                    <select name="cliente_id" id="cliente_id" v-model="cotizacion.cliente_id"
                                        class="form-control" required @change="contactosCliente()">
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">
                                                {{ $cliente->nombre }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Cliente Contacto *</label>
                                    <select name="cliente_contacto_id" v-model="cotizacion.cliente_contacto_id"
                                        class="form-control" required>
                                        <option v-for="(contacto, index) in contactos" :value="contacto.id">
                                            @{{ contacto.nombre }}</option>

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Diseñador *</label>
                                    <select name="vendedor_id" v-model="cotizacion.vendedor_id" class="form-control"
                                        required>
                                        @foreach ($vendedores as $vendedor)
                                            <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Fecha</label>
                                    <br />
                                    <label id="fechaActual" class="control-label"></label>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Folio</label>
                                    <br />
                                    <label id="folio" class="control-label"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Facturar</label>
                                        <select class="form-control" name="facturar" v-model="cotizacion.facturar"
                                            @change="seleccionarRFC()">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                            <option v-for="(rfc, index) in rfcs" :value="index">
                                                @{{ rfc.rfc }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6" v-if="cotizacion.facturar!='0'">
                                    <label class="control-label">RFC *</label>
                                    <input type="text" name="rfc" class="form-control" v-model="cotizacion.rfc"
                                        required />
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.facturar!='0'">
                                <div class="col-sm-12">
                                    <label class="control-label">Razon Social</label>
                                    <input type="text" name="razon_social" class="form-control text-uppercase"
                                        v-model="cotizacion.razon_social" />
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.facturar!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">Calle</label>
                                    <input type="text" name="calle" class="form-control text-uppercase"
                                        v-model="cotizacion.calle" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Ext.</label>
                                    <input type="text" name="nexterior" class="form-control text-uppercase"
                                        v-model="cotizacion.nexterior" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Int.</label>
                                    <input type="text" name="ninterior" class="form-control text-uppercase"
                                        v-model="cotizacion.ninterior" />
                                </div>
                                <div class="col-sm-4">

                                    <label class="control-label">Colonia</label>

                                    <input type="text" name="colonia" class="form-control text-uppercase"
                                        v-model="cotizacion.colonia" />

                                </div>

                            </div>
                            <div class="row form-group" v-if="cotizacion.facturar!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">CP</label>
                                    <input type="text" name="cp" class="form-control cp text-uppercase"
                                        @keyup="cp()" v-model="cotizacion.cp" />

                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Ciudad</label>
                                    <input type="text" name="ciudad" class="form-control ciudad text-uppercase"
                                        v-model="cotizacion.ciudad" />
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Estado</label>
                                    <input type="text" name="estado" class="form-control estado text-uppercase"
                                        v-model="cotizacion.estado" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Dirección de Entrega</label>
                                        <select class="form-control text-uppercase" name="direccion"
                                            v-model="cotizacion.direccion" @change="seleccionarDireccion()">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                            <option v-for="(direccion, index) in direcciones" :value="index">
                                                @{{ index }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6" v-if="cotizacion.direccion!='0'">
                                    <label class="control-label">Enviar a:</label>
                                    <input type="text" name="enviar_a" class="form-control text-uppercase"
                                        v-model="cotizacion.enviar_a" />
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">Calle</label>
                                    <input type="text" name="calle" class="form-control text-uppercase"
                                        v-model="cotizacion.dircalle" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Ext.</label>
                                    <input type="text" name="nexterior" class="form-control text-uppercase"
                                        v-model="cotizacion.dirnexterior" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Int.</label>
                                    <input type="text" name="ninterior" class="form-control text-uppercase"
                                        v-model="cotizacion.dirninterior" />
                                </div>
                                <div class="col-sm-4">

                                    <label class="control-label">Colonia</label>

                                    <input type="text" name="colonia" class="form-control text-uppercase"
                                        v-model="cotizacion.dircolonia" />

                                </div>

                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">

                                <div class="col-sm-4">

                                    <label class="control-label">CP</label>
                                    <input type="text" name="cp" class="form-control cp1 text-uppercase"
                                        @keyup="cp1()" v-model="cotizacion.dircp" />


                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Ciudad</label>
                                    <input type="text" name="ciudad" class="form-control ciudad1 text-uppercase"
                                        v-model="cotizacion.dirciudad" />
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Estado</label>
                                    <input type="text" name="estado" class="form-control estado1 text-uppercase"
                                        v-model="cotizacion.direstado" />
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">
                                <div class="col-sm-12">
                                    <label class="control-label">Instrucciones Especiales:</label>
                                    <input type="text" name="instrucciones" class="form-control text-uppercase"
                                        v-model="cotizacion.instrucciones" />
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">Nombre de Contacto</label>
                                    <input type="text" name="cp" class="form-control "
                                        v-model="cotizacion.contacto_nombre" />
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Teléfono de Contacto</label>
                                    <input type="text" class="form-control" name="telefono"
                                        v-model="cotizacion.contacto_telefono"
                                        v-mask="['(##) ####-####','+#(##)####-####','+##(##)####-####']" />
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Correo de Contacto</label>
                                    <input type="email" name="estado" class="form-control "
                                        v-model="cotizacion.contacto_email" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Condiciones de Pago *</label>
                                        <select class="form-control" name="condiciones" v-model='cotizacion.condicion.id'
                                            @change="condicionCambiada()" required>
                                            <option v-for="condicion in condiciones" :value="condicion.id">
                                                @{{ condicion.nombre }}
                                            </option>
                                            <option value="0">Otra</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div v-if="cotizacion.condicion.id==0" class="form-group">
                                        <label class="control-label">Especifique Otra *</label>
                                        <input class="form-control" type="text" name="condiciones"
                                            v-model="cotizacion.condicion.nombre" required />
                                    </div>
                                    <div v-else class="form-group">
                                        <label class="control-label">Actualizar Condición *</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="condiciones"
                                                v-model="cotizacion.condicion.nombre" required />
                                            <span class="input-group-btn">
                                                <button class="btn btn-success" type="button" title="Actualizar"
                                                    @click="actualizarCondicion()">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" title="Borrar"
                                                    @click="borrarCondicion()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tiempo de Entrega *</label>
                                        <input type="text" name="entrega" class="form-control"
                                            v-model="cotizacion.entrega" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Ubicación</label>
                                        <input class="form-control" type="text" name="ubicacion"
                                            v-model="cotizacion.ubicacion" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Flete</label>
                                        <input class="form-control" type="text" name="flete"
                                            v-model="cotizacion.flete" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <i :class="{
                                                'glyphicon glyphicon-unchecked': !cotizacion
                                                    .isfleteMenor,
                                                'glyphicon glyphicon-check': cotizacion.isfleteMenor
                                            }"
                                                @click="isfleteMenor()"></i>
                                            <label class="control-label" for="cotizacion.flete_menor">Flete menor</label>
                                        </div>
                                        <input class="form-control" type="text" name="flete"
                                            v-model="cotizacion.flete_menor" :disabled="!cotizacion.isfleteMenor" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Costo de Corte</label>
                                        <input class="form-control" type="text" name="costo_corte"
                                            v-model="cotizacion.costo_corte" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Costo Sobreproducción</label>
                                        <input class="form-control" type="text" name="costo_sobreproduccion"
                                            v-model="cotizacion.costo_sobreproduccion" />
                                    </div>
                                </div>
                            </div>
                            <!--Agregando campos nuevos-->
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Factibilidad de proyecto *</label>
                                    <select class="form-control" name="factibilidad" v-model="cotizacion.factibilidad"
                                        required>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                            </div><br>
                            <!--Agregando campos nuevos-->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Moneda *</label>
                                        <select class="form-control" name="moneda" v-model="cotizacion.moneda" required>
                                            <option value="Dolares">Dolares USD</option>
                                            <option value="Pesos">Pesos MXN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">IVA *</label>
                                        <select class="form-control" name="iva" v-model="cotizacion.iva" required>
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <i :class="{
                                                'glyphicon glyphicon-unchecked': !cotizacion.isTax,
                                                'glyphicon glyphicon-check': cotizacion.isTax
                                            }"
                                                @click="isTax()"></i>
                                            <label class="control-label" for="cotizacion.tax">TAX %</label>
                                        </div>
                                        <input class="form-control" type="text" name="tax"
                                            v-model="cotizacion.tax" :disabled="!cotizacion.isTax" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Idioma *</label>
                                        <select class="form-control" name="idioma" v-model="cotizacion.idioma" required>
                                            <option value="español">Español</option>
                                            <option value="ingles">Ingles</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 p-0 mt-1">
                                <div class="row">
                                    <div class="col-md-4" style="">
                                        <label class="control-label">Descuentos</label>
                                        <input class="form-control" type="text" name="Descuentos"
                                            v-model="cotizacion.descuentos" />
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Tipo de descuento</label>
                                        <select class="form-control" name="tipo_descuento"
                                            v-model="cotizacion.tipo_descuento" @change="seleccionarTipoDescuento()">
                                            <option value="0">Monto</option>
                                            <option value="1">%</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6"
                                        style="display: flex; justify-content: flex-end; align-items: flex-end; padding-top: 40px;">
                                        <button type="button" class="btn btn-dark" @click="sumaTotal()"
                                            style="background-color:#12160F; color:#B68911;">
                                            <i v-if="!cargando" class="fas fa-calculator"></i>
                                            <i v-else class="fas fa-refresh animation-rotate"></i>
                                            Recalcular
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tablaEntradas" class="table table-bordred" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Orden</th>
                                                    <th>Area</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Importe</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr v-if="cotizacion.fletes !='0' || cotizacion.fletes !=''">
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>Costo de Flete</strong></td>
                                                    <td v-if="cotizacion.fletes =='0'">$0.00</td>
                                                    <td v-if="cotizacion.fletes !='0'">@{{ (cotizacion.fletes) | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr v-if="cotizacion.flete_menor !='0' || cotizacion.flete_menor !=''">
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>Costo de Flete menor</strong></td>
                                                    <td v-if="cotizacion.flete_menor =='0'">$0.00</td>
                                                    <td v-if="cotizacion.flete_menor !='0'">@{{ (cotizacion.flete_menor) | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr
                                                    v-if="cotizacion.costo_sobreproduccion !='0' || cotizacion.costo_sobreproduccion !=''">
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>Costo Sobreproducción</strong></td>
                                                    <td v-if="cotizacion.costo_sobreproduccion =='0'">$0.00</td>
                                                    <td v-if="cotizacion.costo_sobreproduccion !='0'">
                                                        @{{ (cotizacion.costo_sobreproduccion) | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr v-if="cotizacion.costo_corte !='0'  || cotizacion.costo_corte !=''">
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>Costo de Corte</strong></td>
                                                    <td v-if="cotizacion.costo_corte =='0'">$0.00</td>
                                                    <td v-if="cotizacion.costo_corte !='0'">@{{ (cotizacion.costo_corte) | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>Subtotal</strong></td>
                                                    <td>@{{ cotizacion.subtotal | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr v-if="cotizacion.descuentos !='0' || cotizacion.descuentos !=''">
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>Descuentos</strong></td>
                                                    <td v-if="cotizacion.montoDescuento == 0">$0.00</td>
                                                    <td v-else>- @{{ cotizacion.montoDescuento | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr v-if="cotizacion.calIva !='0' || cotizacion.calIva !=''">
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>IVA</strong></td>
                                                    <td v-if="cotizacion.calIva == 0">$0.00</td>
                                                    <td v-else>@{{ cotizacion.calIva | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr v-if="cotizacion.calTax !='0' || cotizacion.calTax !=''">
                                                    <td colspan="3"></td>
                                                    <td class="text-right"><strong>TAX</strong></td>
                                                    <td v-if="cotizacion.calTax == 0">$0.00</td>
                                                    <td v-else>@{{ cotizacion.calTax | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="text-right">
                                                        <strong>Total
                                                            <span v-if="cotizacion.moneda=='Dolares'"> Dolares</span>
                                                            <span v-else> Pesos</span>
                                                        </strong>
                                                    </td>
                                                    <td v-if="cotizacion.total == 0">$0.00</td>
                                                    <td v-else>@{{ cotizacion.total | formatoMoneda }}</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Producto *</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Producto"
                                                v-model="entrada.producto.nombre" @click="openCatalogo=true" readonly
                                                required />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"
                                                    @click="openCatalogo=true">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Cantidad *</label>
                                        <input type="number" step="0.01" min="0.01" name="cantidad"
                                            class="form-control" v-model="entrada.cantidad" required />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Unidad Medida *</label>
                                        <select class="form-control" name="medida" v-model="entrada.medida" required>
                                            @foreach ($unidades_medida as $unidad)
                                                <option value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Precio *</label>
                                        <input type="number" step="0.01" min="0.01" name="precio"
                                            class="form-control" v-model="entrada.precio" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-dark" @click="modalProducto=true"
                                        style="color:#B68911; background-color:#12160F;">
                                        Registrar producto
                                    </button>
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
                                                    <th>Valor Inglés</th>
                                                    <th>Iconos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(descripcion, index) in entrada.descripciones">
                                                    <td>@{{ descripcion.nombre }}</td>
                                                    <td>@{{ descripcion.name }}</td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            v-model="descripcion.valor" />
                                                    </td>
                                                    <td>
                                                        <input v-if="descripcion.valor_ingles" type="text"
                                                            class="form-control" v-model="descripcion.valor_ingles" />
                                                    </td>
                                                    <td>
                                                        <div v-if="descripcion.nombre=='Flamabilidad'">
                                                            <img src="{{ asset('images/icon-fire.png') }}"
                                                                id="Flamabilidad" style="width:50px; height:50px;">
                                                        </div>
                                                        <div v-else-if="descripcion.nombre=='Abrasión'">
                                                            <img src="{{ asset('images/icon-abrasion.jpg') }}"
                                                                id="Abrasion" style="width:48px; height:48px;">
                                                        </div>
                                                        <div v-else-if="descripcion.nombre=='Decoloración'">
                                                            <img src="{{ asset('images/icon-lightfastness.png') }}"
                                                                id="Decoloracion_de_luz" style="width:50px; height:50px;">
                                                        </div>
                                                        <div v-else-if="descripcion.nombre=='Traspaso'">
                                                            <img src="{{ asset('images/icon-crocking.png') }}"
                                                                id="Traspaso_color" style="width:50px; height:50px;">
                                                        </div>
                                                        <div v-else-if="descripcion.nombre=='Peeling'">
                                                            <img src="{{ asset('images/icon-physical.png') }}"
                                                                id="Peeling" style="width:50px; height:50px;">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Observaciónes Producto</label>
                                        <p v-for="(observacion, index) in observaciones_productos">
                                            <button class="btn btn-xxs btn-danger" type="button" title="eliminar"
                                                @click="eliminarObservacionProducto(observacion, index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <i v-if="observacion.activa" class="glyphicon glyphicon-check"
                                                @click="quitarObservacionProducto(observacion)"></i>
                                            <i v-else class="glyphicon glyphicon-unchecked"
                                                @click="agregarObservacionProducto(observacion)"></i>
                                            @{{ observacion.texto }}
                                        </p>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Nueva Observación"
                                                v-model="nuevaObservacionProducto" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"
                                                    @click="crearObservacionProducto()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" style="display:block;">Foto</label>
                                        <div class="btn btn-sm btn-danger" v-if="entrada.fotos.length"
                                            v-on:click="borrarfotos" title="BORRAR FOTOS">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                        <div class="file-loading">
                                            <input id="fotos" name="fotos[]" type="file" ref="fotos"
                                                multiple />
                                        </div>
                                        <div id="fotos-file-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <div class="form-group" style="margin-top:25px;">
                                        <button type="submit" class="btn btn-dark"
                                            style="background-color:#12160F; color:#B68911;">
                                            <i class="fas fa-plus"></i>
                                            Agregar Producto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Selecione una Nota</label>
                                    <select name="notasPreCargadas_id" v-model="notasPreCargadas.cId"
                                        class="form-control" id="notas-select" style="width: 300px;"
                                        @change="cargarNota()">
                                        @foreach ($notasPreCargadas as $nota)
                                            <option value="{{ $nota->id }}">{{ $nota->titulo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label text-danger">Notas</label>
                                    <textarea id="notas" class="form-control" name="notas" rows="3" cols="80"
                                        v-model="cotizacion.notas"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Observaciónes Cotización</label>
                                    <p v-for="(observacion, index) in observaciones">
                                        @role('Administrador')
                                            <button class="btn btn-xxs btn-danger" type="button" title="eliminar"
                                                @click="eliminarObservacion(observacion, index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endrole
                                        <i class="glyphicon glyphicon-check" v-if="observacion.activa"
                                            @click="quitarObservacion(observacion, index)"></i>
                                        <i class="glyphicon glyphicon-unchecked" v-else
                                            @click="agregarObservacion(observacion)"></i>
                                        @{{ observacion.texto }}
                                    </p>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Nueva Observación"
                                            v-model="nuevaObservacion" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" @click="crearObservacion()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <a href="{{ route('prospectos.index') }}" class="btn btn-default"
                                        style="color:#000; background-color:#B3B3B3">
                                        Regresar
                                    </a>
                                    <button type="button" class="btn btn-dark" @click="guardar()"
                                        :disabled="cargando || edicionEntradaActiva"
                                        style="background-color:#12160F; color:#B68911;">
                                        <i v-if="!cargando" class="fas fa-save"></i>
                                        <i v-else class="fas fa-refresh animation-rotate"></i>
                                        Guardar Cotización
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catalogo Productos Modal -->
        <modal v-model="openCatalogo" title="Productos" :footer="false">
            <div class="table-responsive">
                <table id="tablaProductos" class="table table-bordred">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre Material</th>
                            <th>Color</th>
                            <th>Proveedor</th>
                            <th>Tipo</th>
                            <th>Ficha Técnica</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(prod, index) in productos">
                            <td>@{{ prod.nombre }}</td>
                            <td>@{{ prod.nombre_material }}</td>
                            <td>@{{ prod.color }}</td>
                            <td>@{{ prod.proveedor.empresa }}</td>
                            <td>@{{ prod.categoria.nombre }}</td>
                            <td>
                                <a v-if="prod.ficha_tecnica" :href="prod.ficha_tecnica" target="_blank"
                                    class="btn btn-success" style="cursor:pointer;">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </td>
                            <td class="text-right">
                                <button class="btn btn-primary" title="Seleccionar"
                                    @click="seleccionarProduco(prod, index)">
                                    <i class="fas fa-check"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </modal>
        <!-- /.Catalogo Productos Modal -->

        <!-- Enviar Modal -->
        <modal v-model="openNotas" :title="'Notas Cotización ' + notas.cotizacion_id" :footer="false">
            <form class="" @submit.prevent="notasCotizacion()">
                <div class="form-group">
                    <label class="control-label">Notas</label>
                    <textarea name="mensaje" class="form-control" v-model="notas.mensaje" rows="8" cols="80">
          </textarea>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :disabled="cargando">Guardar</button>
                    <button type="button" class="btn btn-default"
                        @click="notas.cotizacion_id=0; notas.mensaje=''; openNotas=false;"
                        style="color:#000; background-color:#B3B3B3;">
                        Cancelar
                    </button>
                </div>
            </form>
        </modal>
        <!-- /.Enviar Modal -->

        <!-- Copiar Modal -->
        <modal v-model="openCopiar" :title="'Copiar Cotizacion'" :footer="false">
            <form class="" @submit.prevent="copiarCotizacion()">
                <div class="form-group">
                    <label class="control-label">Proyecto Destino *</label>
                    <select name="proyecto_id" v-model="copiar_cotizacion.proyecto_id" class="form-control" required
                        id="proyecto-select" style="width: 300px;">
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" @click="copiar3(index,{{ $proyecto->id }});">
                                {{ $proyecto->nombre }}--{{ $proyecto->cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :disabled="cargando">Guardar</button>
                    <button type="button" class="btn btn-default" @click="openCopiar=false;"
                        style="color:#000; background-color:#B3B3B3;">
                        Cancelar
                    </button>
                </div>
            </form>
        </modal>
        <!-- /.Copiar Modal -->

        <!-- Enviar Modal -->
        <modal v-model="openEnviar" :title="'Enviar Cotizacion ' + enviar.cotizacion_id" :footer="false">
            <form class="" @submit.prevent="enviarCotizacion()">
                <div class="form-group">
                    <label class="control-label">Email(s) *</label>
                    <select2multags :options="enviar.emailOpciones" v-model="enviar.email" style="width:100%;" required>
                    </select2multags>
                </div>
                <div class="form-group">
                    <label class="control-label">Mensaje *</label>
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
        <modal style="color:#B68911, background-color:#12160F;" v-model="openAceptar"
            :title="'Aceptar Cotizacion ' + aceptar.cotizacion_id" :footer="false">
            <form class="" @submit.prevent="aceptarCotizacion()">
                <div class="form-group">
                    <label class="control-label">Comprobante Confirmación *</label>
                    <div class="file-loading">
                        <input id="comprobante" name="comprobante" type="file" ref="comprobante"
                            @change="fijarComprobante()" required />
                    </div>
                    <div id="comprobante-file-errors"></div>
                </div>
                <div class="form-group">
                    <label class="control-label">Fecha Aceptación</label>
                    <br />
                    <dropdown style="width:100%;">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <btn class="dropdown-toggle" style="background-color:#000; color:#FFF;">
                                    <i class="fas fa-calendar"></i>
                                </btn>
                            </div>
                            <input class="form-control" type="text" name="fecha_comprobante" placeholder="DD/MM/YYYY"
                                v-model="aceptar.fecha_comprobante" readonly />
                        </div>
                        <template slot="dropdown">
                            <li>
                                <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                                    format="dd/MM/yyyy" :date-parser="dateParser" v-model="aceptar.fecha_comprobante" />
                            </li>
                        </template>
                    </dropdown>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
                    <button type="button" class="btn btn-default" @click="aceptar.cotizacion_id=0; openAceptar=false;"
                        style="color:#000; background-color:#B3B3B3">
                        Cancelar
                    </button>
                </div>
            </form>
        </modal>
        <!-- /.Aceptar Modal -->

        <!-- Nuevo Producto Modal-->
        <modal style="color:#B68911; background-color:#12160F;" v-model="modalProducto" title="Registrar Producto"
            :footer="false">
            <iframe id="theFrame" src="{{ url('/') }}/productos/crear?layout=iframe"
                style="width:100%; height:700px;" frameborder="0">
            </iframe>
        </modal>
        <!-- /.Nuevo Producto Modal -->

    </section>
    <!-- /.content -->

@stop

{{-- footer_scripts --}}
@section('footer_scripts')
    <script type="text/javascript">
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
                cotizacionEnviar: 0,
                tipo_cliente: 0,
                contactos: [],
                'notasPreCargadas': {!! json_encode($notasPreCargadas) !!},
                fechaActual: new Date().toLocaleDateString(),
                folio: '',
                colonias: [],
                colonias2: [],
                clientes: {!! json_encode($clientes) !!},
                contactos: {!! json_encode($contactos) !!},
                edicionEntradaActiva: false,
                locale: localeES,
                productos: {!! json_encode($productos) !!},
                condiciones: {!! json_encode($condiciones) !!},
                rfcs: {!! json_encode($rfcs) !!},
                direcciones: {!! json_encode($direcciones) !!},
                observaciones: {!! json_encode($observaciones) !!},
                nuevaObservacion: "",
                observaciones_productos: [],
                nuevaObservacionProducto: "",
                tablaProductos: {},
                modalProducto: false,
                contactos_proveedor: "",
                copiar_cotizacion: {
                    proyecto_id: '',
                    cotizacion_id: '',
                },
                cotizacion: {
                    prospecto_id: '',
                    proyecto_nombre: '',
                    cliente_id: '',
                    cliente_contacto_id: '',
                    vendedor_id: '',
                    numero: {{ $numero_siguiente }},
                    condicion: {
                        id: 0,
                        nombre: ''
                    },
                    facturar: 0,
                    rfc: '',
                    razon_social: '',
                    calle: '',
                    nexterior: '',
                    ninterior: '',
                    colonia: '',
                    cp: '',
                    ciudad: '',
                    estado: '',
                    direccion: 0,
                    dircalle: '',
                    instrucciones: '',
                    enviar_a: '',
                    dirnexterior: '',
                    dirninterior: '',
                    dircolonia: '',
                    dircp: '',
                    dirciudad: '',
                    direstado: '',
                    contacto_nombre: '',
                    contacto_telefono: '',
                    contacto_email: '',
                    entrega: '',
                    lugar: '',
                    fletes: 0,
                    isfleteMenor: false,
                    flete_menor: 0,
                    costo_corte: 0,
                    costo_sobreproduccion: 0,
                    descuentos: 0,
                    montoDescuento: 0,
                    tipo_descuento: 0,
                    // planos: '',
                    factibilidad: '',
                    moneda: 'Dolares',
                    entradas: [],
                    subtotal: 0,
                    calIva: 0,
                    iva: 1,
                    total: 0,
                    idioma: '',
                    notas: "",
                    observaciones: []
                },
                entrada: {
                    producto: {
                        "proveedor": {
                            "contactos": {}
                        }
                    },
                    orden: 0,
                    area: "",
                    cantidad: "",
                    medida: "",
                    precio: "",
                    // precio_compra: "",
                    // fecha_precio_compra: "",
                    // medida_compra: "",
                    moneda_referencia: "",
                    importe: 0,
                    descripciones: [],
                    observaciones: [],
                    fotos: [],
                    proveedor_contacto_id: ""
                },
                enviar: {
                    cotizacion_id: 0,
                    numero: 0,
                    email: [],
                    emailOpciones: [

                    ],
                    mensaje: "Buenas tardes  .\n\nAnexo a la presente encontrarán la cotización solicitada de  para  .\n\nEsperamos esta información les sea de utilidad y quedamos a sus órdenes para cualquier duda o comentario.\n\nSaludos,\n\n{{ auth()->user()->name }}.\n{{ auth()->user()->email }}\nRobinson Contract Resources"
                },
                aceptar: {
                    cotizacion_id: 0,
                    comprobante: "",
                    fecha_comprobante: ""
                },
                notas: {
                    cotizacion_id: 0,
                    mensaje: ""
                },
                dataTableEntradas: {},
                openCatalogo: false,
                openEnviar: false,
                openAceptar: false,
                openNotas: false,
                openCopiar: false,
                cargando: false,
            },
            computed: {

            },
            filters: {
                formatoMoneda(numero) {
                    return accounting.formatMoney(numero, "$", 2);
                },
            },
            mounted() {
                this.$refs.fechaActual = document.querySelector('#fechaActual');
                this.actualizarFechaActual();
                let self = this; // ámbito de vue

                // inicializas select2
                $('#proyecto-select')
                    .select2({
                        placeholder: 'Selecciona un proyecto',
                        //data: self.options, // cargas los datos en vez de usar el loop
                    })
                    // nos hookeamos en el evento tal y como puedes leer en su documentación
                    .on('select2:select', function() {
                        var value = $("#proyecto-select").select2('data');

                        // nos devuelve un array

                        // ahora simplemente asignamos el valor a tu variable selected de VUE
                        self.copiar_cotizacion.proyecto_id = value[0].id

                    });

                $("#fotos").fileinput({
                    language: 'es',
                    overwriteInitial: true,
                    maxFileSize: 5000,
                    showCaption: false,
                    showBrowse: false,
                    showRemove: false,
                    showUpload: false,
                    browseOnZoneClick: true,
                    defaultPreviewContent: '<img src="{{ asset('images/camara.png') }}" style="width:200px; height:auto;" alt="foto"><h6>Click para seleccionar</h6>',
                    allowedFileExtensions: ["jpg", "jpeg", "png"],
                    elErrorContainer: '#fotos-file-errors'
                });
                $("#comprobante").fileinput({
                    language: 'es',
                    showPreview: false,
                    showUpload: false,
                    showRemove: false,
                    allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
                    elErrorContainer: '#comprobante-file-errors',
                });

                this.tablaProductos = $("#tablaProductos").DataTable({
                    dom: 'ftp'
                });

                this.dataTableEntradas = $("#tablaEntradas").DataTable({
                    data: [],
                    searching: false,
                    info: false,
                    columnDefs: [{
                            "orderable": true,
                            "targets": 0
                        },
                        {
                            "orderable": false,
                            "targets": '_all'
                        }
                    ],
                    processing: false,
                    paging: false,
                    lengthChange: false,
                    rowReorder: {
                        selector: 'td:first-child span.fa-grip-vertical',
                        snapX: true
                    }
                });

                var vueInstance = this;
                //handler para reordenamiento
                this.dataTableEntradas.on('row-reorder', function(e, diff, edit) {
                    var i = 0,
                        j = diff.length;
                    var nuevo_ordenamiento = 0;
                    var indice_descripcion
                    for (; i < j; i++) {
                        nuevo_ordenamiento = diff[i].newPosition + 1; //+1 Por que empieza en 1
                        //console.log('nuevo_ordenamiento',nuevo_ordenamiento);
                        //console.log('index_entrada',$(edit.nodes[i].cells[6].childNodes[0]).data('index')); //Boton
                        indice_entrada = $(edit.nodes[i].cells[6].childNodes[0]).data('index');
                        //console.log(indice_entrada);
                        vueInstance.cotizacion.entradas[indice_entrada].actualizar = true;
                        vueInstance.cotizacion.entradas[indice_entrada].orden = nuevo_ordenamiento;
                    }
                });

                //handler para botones de editar y borrar
                $("#tablaEntradas")
                    .on('click', 'tr button.btn-success', function() {
                        var index = $(this).data('index');
                        console.log(index);
                        vueInstance.editarEntrada(vueInstance.cotizacion.entradas[index], index);
                    })
                    .on('click', 'button.btn-danger', function() {
                        var index = $(this).data('index');
                        vueInstance.removerEntrada(vueInstance.cotizacion.entradas[index], index);
                    });

                this.resetDataTables();

                //escuchar Iframe
                window.addEventListener('message', function(e) {
                    if (e.data.tipo == "producto") {
                        vueInstance.tablaProductos.destroy();
                        vueInstance.productos.push(e.data.object);
                        vueInstance.seleccionarProduco(e.data.object);
                        vueInstance.modalProducto = false;
                        Vue.nextTick(function() {
                            vueInstance.tablaProductos = $("#tablaProductos").DataTable({
                                dom: 'ftp'
                            });
                        });

                    }
                }, false);

                this.editar({!! json_encode($cotizacion) !!});
            },
            methods: {
                cp() {
                    this.cargando = true;
                    if (this.cotizacion.cp && this.cotizacion.cp.length > 4) {
                        axios.get('http://sepomex.789.mx/' + this.cotizacion.cp, {})
                            .then(({
                                data
                            }) => {

                                this.colonias = data.asentamientos;
                                this.cotizacion.estado = data.estados[0]
                                this.cotizacion.ciudad = data.municipios[0]
                                this.cargando = false;

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
                    axios.get('http://sepomex.789.mx/' + this.cotizacion.cp, {})
                        .then(({
                            data
                        }) => {
                            this.cotizacion.estado = data.estados[0]
                            this.cotizacion.ciudad = data.municipios[0]
                            this.cargando = false;

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
                },
                borrarfotos() {
                    $("button.fileinput-remove").click();
                    this.entrada.fotos = [];
                },
                cp1() {
                    this.cargando = true;
                    if (this.cotizacion.dircp && this.cotizacion.dircp.length > 4) {
                        axios.get('http://sepomex.789.mx/' + this.cotizacion.dircp, {})
                            .then(({
                                data
                            }) => {
                                this.colonias2 = data.asentamientos;
                                this.cotizacion.dirciudad = data.municipios[0]
                                this.cotizacion.direstado = data.estados[0]
                                this.cargando = false;

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
                dateParser(value) {
                    return moment(value, 'DD/MM/YYYY').toDate().getTime();
                },
                seleccionarRFC() {
                    if (this.cotizacion.facturar != "0" && this.cotizacion.facturar != "1") {
                        this.colonias = [this.rfcs[this.cotizacion.facturar].colonia];

                        this.cotizacion.rfc = this.rfcs[this.cotizacion.facturar].rfc;
                        this.cotizacion.razon_social = this.rfcs[this.cotizacion.facturar].razon_social;
                        this.cotizacion.calle = this.rfcs[this.cotizacion.facturar].calle;
                        this.cotizacion.nexterior = this.rfcs[this.cotizacion.facturar].nexterior;
                        this.cotizacion.ninterior = this.rfcs[this.cotizacion.facturar].ninterior;
                        this.cotizacion.colonia = this.rfcs[this.cotizacion.facturar].colonia;
                        this.cotizacion.cp = this.rfcs[this.cotizacion.facturar].cp;
                        this.cotizacion.ciudad = this.rfcs[this.cotizacion.facturar].ciudad;
                        this.cotizacion.estado = this.rfcs[this.cotizacion.facturar].estado;
                    }
                },
                contactosCliente() {
                    const id_cliente = Number(this.cotizacion.cliente_id);
                    const cliente = this.clientes.find(({
                        id
                    }) => id === id_cliente);
                    this.contactos = cliente.contactos;
                },
                seleccionarTipoDescuento() {
                    console.log(this.cotizacion.tipo_descuento)
                },
                seleccionarDireccion() {
                    if (this.cotizacion.direccion != "0" && this.cotizacion.direccion != "1") {
                        this.colonias2 = [this.direcciones[this.cotizacion.direccion].dircolonia];
                        this.cotizacion.dircalle = this.direcciones[this.cotizacion.direccion].dircalle;
                        this.cotizacion.dirnexterior = this.direcciones[this.cotizacion.direccion].dirnexterior;
                        this.cotizacion.dirninterior = this.direcciones[this.cotizacion.direccion].dirninterior;
                        this.cotizacion.dircolonia = this.direcciones[this.cotizacion.direccion].dircolonia;
                        this.cotizacion.dircp = this.direcciones[this.cotizacion.direccion].dircp;
                        this.cotizacion.dirciudad = this.direcciones[this.cotizacion.direccion].dirciudad;
                        this.cotizacion.direstado = this.direcciones[this.cotizacion.direccion].direstado;
                        this.cotizacion.contacto_nombre = this.direcciones[this.cotizacion.direccion]
                            .contacto_nombre;
                        this.cotizacion.contacto_email = this.direcciones[this.cotizacion.direccion].contacto_email;
                        this.cotizacion.contacto_telefono = this.direcciones[this.cotizacion.direccion]
                            .contacto_telefono;
                    }
                },
                condicionCambiada() {
                    if (this.cotizacion.condicion.id == 0) {
                        this.cotizacion.condicion.nombre = "";
                        return true;
                    }

                    this.condiciones.find(function(cond) {
                        if (cond.id == this.cotizacion.condicion.id) {
                            this.cotizacion.condicion.nombre = cond.nombre;
                        }
                    }, this);
                },
                actualizarCondicion() {
                    this.cargando = true;
                    axios.put('/condicionesCotizacion/' + this.cotizacion.condicion.id, {
                            'nombre': this.cotizacion.condicion.nombre
                        })
                        .then(({
                            data
                        }) => {
                            this.condiciones.find(function(cond) {
                                if (this.cotizacion.condicion.id == cond.id) {
                                    cond.nombre = this.cotizacion.condicion.nombre;
                                }
                            }, this);
                            this.cargando = false;
                            swal({
                                title: "Condicion Actualizada",
                                text: "",
                                type: "success"
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
                },
                borrarCondicion() {
                    this.cargando = true;
                    axios.delete('/condicionesCotizacion/' + this.cotizacion.condicion.id, {})
                        .then(({
                            data
                        }) => {
                            var indexCondicion = this.condiciones.findIndex(function(cond) {
                                return this.cotizacion.condicion.id == cond.id;
                            }, this);

                            this.condiciones.splice(indexCondicion, 1);
                            this.cotizacion.condicion = {
                                id: 0,
                                nombre: ''
                            };

                            this.cargando = false;
                            swal({
                                title: "Condicion Actualizada",
                                text: "",
                                type: "success"
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
                },
                resetDataTables() {
                    var rows = [],
                        row = [];
                    this.cotizacion.entradas.forEach(function(entrada, index) {
                        if (entrada.borrar == true) return true;
                        row = [
                            '<span class="fas fa-grip-vertical"></span> ' + entrada.orden,
                            entrada.area,
                            entrada.producto.nombre,
                            entrada.cantidad + " " + entrada.medida,
                            accounting.formatMoney(entrada.precio, "$", 2),
                            accounting.formatMoney(entrada.importe, "$", 2),
                        ];
                        row.push([
                            '<button class="btn btn-xs btn-success" title="Editar" data-index="' +
                            index + '">',
                            '<i class="fas fa-pencil-alt"></i>',
                            '</button>',
                            '<button class="btn btn-xs btn-danger" title="Remover" data-index="' +
                            index + '">',
                            '<i class="fas fa-times"></i>',
                            '</button>'
                        ].join(''));
                        rows.push(row);
                    });

                    this.dataTableEntradas.clear();
                    this.dataTableEntradas.rows.add(rows);
                    this.dataTableEntradas.draw();
                },
                cuentaEntradasNoBorradas() {
                    var i = 0;
                    this.cotizacion.entradas.forEach(function(entrada) {
                        if (entrada.borrar != true) i++;
                    });
                    return i;
                },
                fijarComprobante() {
                    this.aceptar.comprobante = this.$refs['comprobante'].files[0];
                },
                isfleteMenor() {
                    this.cotizacion.isfleteMenor = this.cotizacion.isfleteMenor ? false : true;
                },
                isTax() {
                    this.cotizacion.isTax = this.cotizacion.isTax ? false : true;
                    this.cotizacion.tax = this.cotizacion.isTax ? 0 : this.cotizacion.tax;
                },
                agregarObservacion(observacion) {
                    this.cotizacion.observaciones.push(observacion.texto);
                    observacion.activa = true;
                },
                quitarObservacion(observacion) {
                    var index = this.cotizacion.observaciones.findIndex(function(obs) {
                        return observacion.texto == obs;
                    });
                    this.cotizacion.observaciones.splice(index, 1);
                    observacion.activa = false;
                },
                eliminarObservacion(observacion, index) {
                    axios.delete('/observacionesCotizacion/' + observacion.id, {})
                        .then(({
                            data
                        }) => {
                            this.quitarObservacion(observacion);
                            this.observaciones.splice(index, 1);
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
                crearObservacion() {
                    if (this.nuevaObservacion == "") return false;

                    axios.post('/observacionesCotizacion', {
                            texto: this.nuevaObservacion
                        })
                        .then(({
                            data
                        }) => {
                            this.observaciones.push(data.observacion);
                            this.agregarObservacion(this.observaciones[this.observaciones.length - 1]);
                            this.nuevaObservacion = "";
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
                },
                agregarObservacionProducto(observacion) {
                    this.entrada.observaciones.push(observacion.texto);
                    observacion.activa = true;
                },
                quitarObservacionProducto(observacion) {
                    var index = this.entrada.observaciones.findIndex(function(obs) {
                        return observacion.texto == obs;
                    });
                    this.entrada.observaciones.splice(index, 1);
                    observacion.activa = false;
                },
                eliminarObservacionProducto(observacion, index) {
                    this.quitarObservacionProducto(observacion);
                    this.observaciones_productos.splice(index, 1);
                },
                crearObservacionProducto() {
                    if (this.nuevaObservacionProducto == "") return false;
                    this.observaciones_productos.push({
                        activa: false,
                        texto: this.nuevaObservacionProducto
                    });
                    this.agregarObservacionProducto(this.observaciones_productos[this.observaciones_productos
                        .length - 1]);
                    this.nuevaObservacionProducto = "";
                },
                validarCliente() {
                    // Obtener el elemento select
                    const select = document.querySelector("#cliente_id");

                    // Comprobar si el select tiene una opción seleccionada
                    if (select.value === "") {

                        // Mostrar un mensaje de error
                        swal({
                            title: "Error",
                            text: "Por favor, seleccione un cliente",
                            type: "error"
                        });
                        select.focus();

                        return false;
                    } else {
                        // El select tiene una opción seleccionada

                        // Limpiar el mensaje de error
                        select.setCustomValidity("");
                    }
                },
                seleccionarProduco(prod) {
                    this.validarCliente();
                    this.entrada.producto = prod;
                    console.log(this.tipo_cliente)
                    switch (this.tipo_cliente) {
                        case 1:
                            this.entrada.precio = this.entrada.producto.precio_residencial;
                            break;
                        case 2:
                            this.entrada.precio = this.entrada.producto.precio_comercial;
                            break;
                        case 3:
                            this.entrada.precio = this.entrada.producto.precio_distribuidor;
                            break;
                    }
                    this.entrada.descripciones = [];
                    prod.descripciones.forEach(function(desc) {
                        this.entrada.descripciones.push({
                            nombre: desc.descripcion_nombre.nombre,
                            name: desc.descripcion_nombre.name,
                            valor: desc.valor,
                            valor_ingles: desc.valor_ingles
                        });
                    }, this);

                    if (prod.foto) {
                        $("button.fileinput-remove").click();
                        $("#foto-producto")[0].src = prod.foto;
                    }

                    this.openCatalogo = false;
                },
                sumaImporte() {
                    let sumaImporte = 0;
                    for (const entrada of this.cotizacion.entradas) {
                        if (!entrada.borrar)
                            sumaImporte += entrada.importe;
                    };

                    return sumaImporte;

                },
                setDescuentosFinal() {
                    this.cotizacion.descuentos = this.cotizacion.tipo_descuento != '0' ?
                        (Number(this.cotizacion.subtotal) * Number(this.cotizacion.descuentos)) / 100 :
                        this.cotizacion.descuentos;
                },
                sumaTotal() {
                    // let subTotal = Number(this.cotizacion.subtotal);

                    let suma = Number(this.cotizacion.fletes) +
                        Number(this.cotizacion.flete_menor) +
                        Number(this.cotizacion.costo_corte) +
                        Number(this.cotizacion.costo_sobreproduccion);

                    this.cotizacion.subtotal = this.sumaImporte() + suma;
                    this.cotizacion.extras = suma;

                    //Calcula Los descuentos
                    if (this.cotizacion.descuentos != '0') {
                        this.cotizacion.montoDescuento = this.cotizacion.tipo_descuento ?
                            (Number(this.cotizacion.subtotal) * Number(this.cotizacion.descuentos)) / 100 :
                            this.cotizacion.descuentos;
                    } else {
                        this.cotizacion.descuentos;
                    }

                    //Calcula el IVA
                    if (this.cotizacion.iva == "1") {
                        this.cotizacion.calIva = this.cotizacion.montoDescuento != '0' ?
                            (Number(this.cotizacion.subtotal) - Number(this.cotizacion.montoDescuento)) * 0.16 :
                            Number(this.cotizacion.subtotal) * 0.16;
                    } else {
                        this.cotizacion.calIva = 0;
                    }

                    //Calcula el TAX
                    if (this.cotizacion.isTax) {
                        this.cotizacion.calTax = this.cotizacion.montoDescuento != '0' ?
                            ((Number(this.cotizacion.subtotal) - Number(this.cotizacion.montoDescuento)) * this
                                .cotizacion.tax) / 100 :
                            (Number(this.cotizacion.subtotal) * this.cotizacion.tax) / 100;
                    } else {
                        this.cotizacion.tax = 0;
                        this.cotizacion.calTax = 0;
                    }

                    //Suma total
                    this.cotizacion.total = this.cotizacion.montoDescuento != '0' ?
                        (Number(this.cotizacion.subtotal) - Number(this.cotizacion.montoDescuento)) +
                        Number(this.cotizacion.calIva) + Number(this.cotizacion.calTax) :
                        Number(this.cotizacion.subtotal) + Number(this.cotizacion.calIva) + Number(this.cotizacion
                            .calTax);

                    // console.log("suma total ", this.cotizacion.total)
                },
                resetValores() {
                    this.cotizacion.montoDescuento = 0;
                    this.cotizacion.calTax = 0;
                    this.cotizacion.calIva = 0;
                    this.cotizacion.subtotal = 0;
                    this.cotizacion.total = 0;
                },
                agregarEntrada() {
                    var area = '';
                    this.entrada.descripciones.forEach(function(descripcion) {
                        if (descripcion.name == 'Area') {
                            area = descripcion.valor;
                        }
                    });

                    this.entrada.area = area;
                    if (this.entrada.producto.id == undefined) {
                        swal({
                            title: "Error",
                            text: "Debe seleccionar un producto",
                            type: "error"
                        });
                        return false;
                    }

                    if (this.$refs['fotos'].files.length) { //hay fotos
                        this.entrada.fotos = [];
                        for (var i = 0; i < this.$refs['fotos'].files.length; i++)
                            this.entrada.fotos.push(this.$refs['fotos'].files[i]);
                    }
                    console.log(this.cliente)
                    console.log(this.factor_porcentual)

                    // let factorPorcentual = this.factor_porcentual > 0 ? (this.entrada.precio * this
                    //         .factor_porcentual) / 100 :
                    //     0;
                    this.entrada.importe = this.entrada.cantidad * this.entrada.precio;
                    // this.entrada.importe = this.entrada.cantidad * (this.entrada.precio - factorPorcentual);
                    console.log(this.entrada.importe)
                    // this.cotizacion.subtotal += this.entrada.importe;
                    if (this.entrada.orden == 0)
                        this.entrada.orden = this.cuentaEntradasNoBorradas() + 1;

                    this.cotizacion.entradas.push(this.entrada);
                    this.sumaTotal()
                    this.resetDataTables();
                    this.entrada = {
                        producto: {
                            "proveedor": {
                                "contactos": {}
                            }
                        },
                        orden: 0,
                        area: "",
                        cantidad: "",
                        medida: "",
                        // medida_compra: "",
                        moneda_referencia: "",
                        precio: "",
                        // precio_compra: "",
                        // fecha_precio_compra: "",
                        importe: 0,
                        descripciones: [],
                        observaciones: [],
                        fotos: [],
                        proveedor_contacto_id: ""
                    };
                    this.edicionEntradaActiva = false;
                    $("button.fileinput-remove").click();
                    this.observaciones_productos.forEach(function(observacion) {
                        observacion.activa = false;
                    });
                },
                editarEntrada(entradaEdit, index) {
                    if (this.edicionEntradaActiva) return false;
                    entradaEdit.actualizar = true;
                    this.entrada = entradaEdit;
                    // this.entrada.fecha_precio_compra = entradaEdit.fecha_precio_compra_formated;

                    this.cotizacion.subtotal -= entradaEdit.importe;
                    this.cotizacion.subtotal = this.cotizacion.subtotal < 0 ? 0 : this.cotizacion.subtotal;
                    this.cotizacion.entradas.splice(index, 1);
                    this.edicionEntradaActiva = true;

                    $("button.fileinput-remove").click();
                    if (this.entrada.fotos.length) { //hay fotos
                        if (typeof this.entrada.fotos[0] == "object") {
                            this.$refs['fotos'].files = FileListItem(this.entrada.fotos);
                            this.$refs['fotos'].dispatchEvent(new Event('change', {
                                'bubbles': true
                            }));
                        } else if (typeof this.entrada.fotos[0] == "string") {
                            $("div.file-default-preview").empty();
                            this.entrada.fotos.forEach(function(foto) {
                                $("div.file-default-preview")
                                    .append('<img src="' + foto +
                                        '" style="width:200px; height:auto;" alt="foto">');
                            });
                            $("div.file-default-preview").append('<h6>Click para seleccionar</h6>');
                        }
                    } else if (this.entrada.producto.foto) {
                        $("div.file-default-preview img")[0].src = this.entrada.producto.foto;
                    }

                    this.observaciones_productos.forEach(function(observacion) {
                        var index = this.entrada.observaciones.findIndex(function(obs) {
                            return observacion.texto == obs;
                        });
                        if (index == -1) observacion.activa = false;
                        else observacion.activa = true;
                    }, this);
                    this.resetDataTables();
                    return true;
                },
                removerEntrada(entrada, index, undefined) {
                    if (entrada.id == undefined) this.cotizacion.entradas.splice(index, 1);
                    else entrada.borrar = true;
                    $("button.fileinput-remove").click();

                    //restar 1 al orden de todas las entradas con orden mayor
                    //al de la entrada borrada
                    var orden = entrada.orden;
                    this.cotizacion.entradas.forEach(function(entrada) {
                        if (entrada.orden > orden && entrada.borrar == undefined) {
                            entrada.actualizar = true;
                            entrada.orden--;
                        }
                    });

                    this.resetDataTables();
                },
                editar(cotizacion) {
                    //reiniciar observaciones
                    this.observaciones.forEach(function(observacion) {
                        observacion.activa = false;
                    });
                    const id_cliente = Number(cotizacion.cliente_id);
                    const cliente = this.clientes.find(({
                        id
                    }) => id === id_cliente);
                    console.log(cliente)
                    this.tipo_cliente = cliente.tipo_id;
                    console.log(cotizacion.entradas)
                    //vaciar datos de cotizacion
                    this.cotizacion = {
                        cliente_id: cotizacion.cliente_id,
                        cliente_contacto_id: cotizacion.cliente_contacto_id,
                        nombre_proyecto: cotizacion.nombre_proyecto,
                        vendedor_id: cotizacion.vendedor_id,
                        cotizacion_id: cotizacion.id,
                        numero: cotizacion.numero,
                        condicion: {
                            id: cotizacion.condicion_id,
                            nombre: ''
                        },
                        ubicacion: cotizacion.ubicacion,
                        facturar: (cotizacion.facturar) ? 1 : 0,
                        rfc: cotizacion.rfc,
                        razon_social: cotizacion.razon_social,
                        calle: cotizacion.calle,
                        nexterior: cotizacion.nexterior,
                        ninterior: cotizacion.ninterior,
                        colonia: cotizacion.colonia,
                        cp: cotizacion.cp,
                        ciudad: cotizacion.ciudad,
                        estado: cotizacion.estado,
                        direccion: (cotizacion.direccion) == 0 ? 0 : 1,
                        dircalle: cotizacion.dircalle,
                        instrucciones: cotizacion.instrucciones,
                        enviar_a: cotizacion.enviar_a,
                        dirnexterior: cotizacion.dirnexterior,
                        dirninterior: cotizacion.dirninterior,
                        dircolonia: cotizacion.dircolonia,
                        dircp: cotizacion.dircp,
                        contacto_nombre: cotizacion.contacto_nombre,
                        contacto_telefono: cotizacion.contacto_telefono,
                        contacto_email: cotizacion.contacto_email,
                        dirciudad: cotizacion.dirciudad,
                        direstado: cotizacion.direstado,
                        entrega: cotizacion.entrega,
                        montoDescuento: cotizacion.montoDescuento,
                        lugar: cotizacion.lugar,
                        fletes: cotizacion.fletes,
                        flete_menor: cotizacion.flete_menor,
                        isfleteMenor: cotizacion.flete_menor && cotizacion.flete_menor > 0 ? true : false,
                        costo_corte: cotizacion.costo_corte,
                        costo_sobreproduccion: cotizacion.costo_corte,
                        extras: Number(cotizacion.fletes) + Number(cotizacion.flete_menor) + Number(cotizacion
                            .costo_corte) + Number(cotizacion.costo_sobreproduccion),
                        descuentos: cotizacion.descuentos,
                        tipo_descuento: cotizacion.tipo_descuento,
                        // planos: cotizacion.planos,
                        factibilidad: cotizacion.factibilidad,
                        moneda: cotizacion.moneda,
                        entradas: cotizacion.entradas,
                        subtotal: cotizacion.subtotal,
                        calIva: cotizacion.calIva,
                        iva: (cotizacion.iva > 0 ? 1: 0,
                        tax: cotizacion.tax,
                        isTax: cotizacion.tax > 0 ? 1 : 0,
                        total: cotizacion.total,
                        idioma: cotizacion.idioma,
                        notas: cotizacion.notas,
                        observaciones: []
                    };

                    this.sumaTotal();
                    this.condicionCambiada();

                    //re-seleccionar observaciones
                    var observaciones = cotizacion.observaciones.match(/<li>([^<]+)+<\/li>+/g);
                    if (observaciones == null) observaciones = [];
                    var encontrada;
                    observaciones.forEach(function(observacion) {
                        observacion = observacion.replace(/(<li>|<\/li>)/g, '');
                        encontrada = this.observaciones.findIndex(function(obs) {
                            return observacion == obs.texto;
                        });

                        if (encontrada != -1) {
                            this.observaciones[encontrada].activa = true;
                        } else { //observacion diferente de las predefinidas
                            this.observaciones.push({
                                activa: true,
                                texto: observacion
                            });
                        }
                        this.cotizacion.observaciones.push(observacion);
                    }, this);

                    // agregar observaciones de entradas de productos
                    cotizacion.entradas.forEach(function(entrada) {
                        observaciones = entrada.observaciones.match(/<li>([^<]+)+<\/li>+/g);
                        entrada.observaciones = [];
                        if (observaciones == null) return false;
                        encontrada;
                        observaciones.forEach(function(observacion) {
                            observacion = observacion.replace(/(<li>|<\/li>)/g, '');
                            entrada.observaciones.push(observacion);

                            encontrada = this.observaciones_productos.findIndex(function(obs) {
                                return observacion == obs.texto;
                            });
                            if (encontrada == -1) this.observaciones_productos.push({
                                activa: false,
                                texto: observacion
                            });
                        }, this);
                    }, this);
                    this.resetDataTables();
                },
                guardar() {
                    this.sumaTotal();
                    if (this.entrada.producto.id == undefined) {

                    } else {
                        this.agregarEntrada();
                    }


                    var cotizacion = $.extend(true, {}, this.cotizacion);
                    //console.log(cotizacion.subtotal);
                    var totalf = 0;
                    cotizacion.entradas.forEach(function(entrada) {
                        totalf += entrada.importe;
                    });

                    totalcotizacion = cotizacion.subtotal.toFixed(2);
                    totalf += Number(this.cotizacion.fletes) +
                        Number(this.cotizacion.flete_menor) +
                        Number(this.cotizacion.costo_corte) +
                        Number(this.cotizacion.costo_sobreproduccion);
                    console.log(totalcotizacion);
                    console.log(totalf);
                    console.log(totalcotizacion - totalf);

                    var dif = totalcotizacion - totalf;


                    if (dif > 0.05) {
                        alert('OCURRIO UN ERROR INESPERADO EL SUBTOTAL NO COINCIDE FAVOR DE RECARGAR LA PAGINA');
                    } else {
                        this.setDescuentosFinal()
                        cotizacion.entradas.forEach(function(entrada) {
                            entrada.producto_id = entrada.producto.id;
                            delete entrada.producto;
                        });
                        var formData = objectToFormData(cotizacion, {
                            indices: true
                        });
                        var url = "",
                            numero_siguiente = false;

                        url = '/cotizacionesdirectas/' + cotizacion.cotizacion_id + '/update';

                        this.cargando = true;
                        axios.post(url, formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(({
                                data
                            }) => {
                                swal({
                                    title: "Cotizacion Guardada",
                                    text: "",
                                    type: "success",
                                    confirmButtonText: "Enviar Cotización",
                                    showCancelButton: true,
                                    cancelButtonText: "Ok",
                                }).then((result) => {
                                    console.log(result)

                                    if (result.dismiss === "cancel") {
                                        // Cierra la modal
                                        window.location.href = "/cotizacionesdirectas";
                                        swal.close();
                                    } else if (result.value) {
                                        // Ejecuta el código
                                        this.openEnviar = true;
                                        this.cargando = false;
                                        console.log(data)
                                        this.enviar.cotizacion_id = data.cotizacion.id;
                                    }
                                });
                            })
                            .catch(({
                                response
                            }) => {
                                console.error(response);
                                console.error(response.data.message);
                                this.cargando = false;
                                swal({
                                    title: "Error",
                                    text: response.data.message ||
                                        "Ocurrio un error inesperado, intente mas tarde",
                                    type: "error"
                                });
                            });
                    }
                }, //fin guardar
                enviarCotizacion() {
                    this.cargando = true;
                    console.log(this.contactos)
                    axios.post('/prospectos/0/enviarCotizacion', this.enviar)
                        .then(({
                            data
                        }) => {
                            this.enviar = {
                                cotizacion_id: 0,
                                numero: 0,
                                email: [],
                                emailOpciones: [

                                ],
                                mensaje: "Buen día.\n\nLe envió cotización para su consideración.\n\n{{ auth()->user()->name }}.\nAtención del Cliente\nRobinson Contract Resources"
                            };
                            this.openEnviar = false;
                            this.cargando = false;
                            swal({
                                title: "Cotizacion Enviada",
                                text: "",
                                type: "success"
                            }).then((result) => {
                                window.location.href = "/cotizacionesdirectas";
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
                }, //fin enviarCotizacion
                notasCotizacion() {
                    this.cargando = true;
                    axios.post('/prospectos/0/notasCotizacion', this.notas)
                        .then(({
                            data
                        }) => {
                            this.prospecto.cotizaciones.find(function(cotizacion) {
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
                }, //fin notasCotizacion
                copiarCotizacion() {
                    this.cargando = true;

                    axios.post('/prospectos/0/copiarCotizacion', this.copiar_cotizacion)
                        .then(({
                            data
                        }) => {
                            this.openCopiar = false;
                            this.cargando = false;
                            swal({
                                title: "Copia Guardada",
                                text: "La cotizaciones de ha copiado correctamente",
                                type: "success"
                            });

                            window.location.href = "/prospectos/" + this.copiar_cotizacion.proyecto_id +
                                "/cotizar";
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
                }, //fin CopiarCotizacion
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
                            axios.delete('/prospectos/0/cotizacion/' + cotizacion
                                    .id, {})
                                .then(({
                                    data
                                }) => {

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
                actualizarFechaActual() {
                    const fecha = new Date().toLocaleDateString();
                    this.$refs.fechaActual.innerHTML = fecha;
                    console.log(fecha)
                },
                cargarNota() {
                    const notaId = this.notasPreCargadas.cId;

                    for (const nota of this.notasPreCargadas) {
                        if (nota.id.toString() === notaId) {
                            this.notasPreCargadas.contenido = nota.contenido;
                            break;
                        }
                    }

                    this.cotizacion.notas = this.notasPreCargadas.contenido;

                }
            }
        });
    </script>
@stop
