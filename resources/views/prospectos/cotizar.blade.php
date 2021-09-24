@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Cotizar Proyecto | @parent
@stop

@section('header_styles')
    <style>
        table td:first-child span.fa-grip-vertical:hover {
            cursor: move;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Proyecto {{$prospecto->nombre}}</h1> 
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Cotizar Proyecto</h3>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre de Proyecto</label>
                                    <span class="form-control text-uppercase">{{$prospecto->nombre}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <span class="form-control"
                                          style="min-height:68px;">{{$prospecto->descripcion}}</span>
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
                        <h4 class="panel-title">Nueva Cotización</h4>
                    </div>
                    <div class="panel-body">
                        <form class="" @submit.prevent="agregarEntrada()">
                            <div class="row">
                                @can('editar numero cotizacion')
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Numero Cotización</label>
                                            <input type="number" step="1" min="0" name="numero" class="form-control"
                                                   v-model="cotizacion.numero"/>
                                        </div>
                                    </div>
                                @endcan
                                <div class="col-md-offset-2 col-md-6">
                                    <label class="control-label">Cliente Contacto</label>
                                    <select name="cliente_contacto_id" v-model="cotizacion.cliente_contacto_id"
                                            class="form-control" required>
                                        @foreach($prospecto->cliente->contactos as $contacto)
                                            <option value="{{$contacto->id}}">{{$contacto->nombre}}</option>
                                        @endforeach
                                    </select>
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
                                            <option v-for="(rfc, index) in rfcs" :value="index">@{{rfc.rfc}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6" v-if="cotizacion.facturar!='0'">
                                  <label class="control-label">RFC</label>
                                  <input type="text" name="rfc" class="form-control" v-model="cotizacion.rfc" required />
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.facturar!='0'">
                                <div class="col-sm-12">
                                    <label class="control-label">Razon Social</label>
                                    <input type="text" name="razon_social" class="form-control text-uppercase"
                                           v-model="cotizacion.razon_social"/>
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.facturar!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">Calle</label>
                                    <input type="text" name="calle" class="form-control text-uppercase"
                                           v-model="cotizacion.calle"/>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Ext.</label>
                                    <input type="text" name="nexterior" class="form-control text-uppercase"
                                           v-model="cotizacion.nexterior"/>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Int.</label>
                                    <input type="text" name="ninterior" class="form-control text-uppercase"
                                           v-model="cotizacion.ninterior"/>
                                </div>
                                <div class="col-sm-4">

                                    <label class="control-label">Colonia</label>
                                    <!--
                                    <select class="form-control" name="colonia" v-model="cotizacion.colonia" text-uppercase required>
                                        <option v-for="(colonia,index) in colonias" v-bind:value="colonia">@{{colonia}}</option>
                                    </select>
                                    -->
                                    
                                    <input type="text" name="colonia" class="form-control text-uppercase"
                                           v-model="cotizacion.colonia"/>
                                    
                                </div>
                                
                            </div>
                            <div class="row form-group" v-if="cotizacion.facturar!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">CP</label>
                                    <input type="text" name="cp" class="form-control cp text-uppercase"
                                           @keyup="cp()" v-model="cotizacion.cp"/>
                                    
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Ciudad</label>
                                    <input type="text" name="ciudad" class="form-control ciudad text-uppercase"
                                           v-model="cotizacion.ciudad"/>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Estado</label>
                                    <input type="text" name="estado" class="form-control estado text-uppercase"
                                           v-model="cotizacion.estado"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Dirección de Entrega</label>
                                        <select class="form-control text-uppercase" name="direccion"
                                                v-model="cotizacion.direccion"
                                                @change="seleccionarDireccion()">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                            <option v-for="(direccion, index) in direcciones" :value="index">
                                                @{{index}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6" v-if="cotizacion.direccion!='0'">
                                    <label class="control-label">Enviar a:</label>
                                    <input type="text" name="enviar_a" class="form-control text-uppercase"
                                           v-model="cotizacion.enviar_a"/>
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">Calle</label>
                                    <input type="text" name="calle" class="form-control text-uppercase"
                                           v-model="cotizacion.dircalle"/>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Ext.</label>
                                    <input type="text" name="nexterior" class="form-control text-uppercase"
                                           v-model="cotizacion.dirnexterior"/>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">No. Int.</label>
                                    <input type="text" name="ninterior" class="form-control text-uppercase"
                                           v-model="cotizacion.dirninterior"/>
                                </div>
                                <div class="col-sm-4">

                                    <label class="control-label">Colonia</label>
                                    <!--
                                    <select class="form-control" name="colonia" v-model="cotizacion.dircolonia" text-uppercase required>
                                        <option v-for="(colonia,index) in colonias2" v-bind:value="colonia">@{{colonia}}</option>
                                    </select>
                                    -->
                                    <input type="text" name="colonia" class="form-control text-uppercase"
                                           v-model="cotizacion.dircolonia"/>

                                </div>
                                
                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">
                                
                                <div class="col-sm-4">

                                    <label class="control-label">CP</label>
                                    <input type="text" name="cp" class="form-control cp1 text-uppercase"
                                           @keyup="cp1()" v-model="cotizacion.dircp"/>
                                    
                                    
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Ciudad</label>
                                    <input type="text" name="ciudad" class="form-control ciudad1 text-uppercase"
                                           v-model="cotizacion.dirciudad"/>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Estado</label>
                                    <input type="text" name="estado" class="form-control estado1 text-uppercase"
                                           v-model="cotizacion.direstado"/>
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">
                                <div class="col-sm-12">
                                    <label class="control-label">Instrucciones Especiales:</label>
                                    <input type="text" name="instrucciones" class="form-control text-uppercase"
                                           v-model="cotizacion.instrucciones"/>
                                </div>
                            </div>
                            <div class="row form-group" v-if="cotizacion.direccion!='0'">
                                <div class="col-sm-4">
                                    <label class="control-label">Nombre de Contacto</label>
                                    <input type="text" name="cp" class="form-control "
                                           v-model="cotizacion.contacto_nombre"/>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Teléfono de Contacto</label>
                                    <input type="text" class="form-control" name="telefono"
                                           v-model="cotizacion.contacto_telefono"
                                           v-mask="['(##) ####-####','+#(##)####-####','+##(##)####-####']"
                                    />
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Correo de Contacto</label>
                                    <input type="email" name="estado" class="form-control "
                                           v-model="cotizacion.contacto_email"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Condiciones De Pago</label>
                                        <select class="form-control" name="condiciones"
                                                v-model='cotizacion.condicion.id'
                                                @change="condicionCambiada()" required>
                                            <option v-for="condicion in condiciones" :value="condicion.id">
                                                @{{condicion.nombre}}
                                            </option>
                                            <option value="0">Otra</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div v-if="cotizacion.condicion.id==0" class="form-group">
                                        <label class="control-label">Especifique Otra</label>
                                        <input class="form-control" type="text" name="condiciones"
                                               v-model="cotizacion.condicion.nombre" required
                                        />
                                    </div>
                                    <div v-else class="form-group">
                                        <label class="control-label">Actualizar Condicion</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="condiciones"
                                                   v-model="cotizacion.condicion.nombre" required
                                            />
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
                                        <label class="control-label">Tiempo de Entrega</label>
                                        <input type="text" name="entrega" class="form-control"
                                               v-model="cotizacion.entrega" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Fletes</label>
                                    <input class="form-control" type="text" name="fletes" v-model="cotizacion.fletes"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Moneda</label>
                                        <select class="form-control" name="moneda" v-model="cotizacion.moneda" required>
                                            <option value="Dolares">Dolares USD</option>
                                            <option value="Pesos">Pesos MXN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">IVA</label>
                                        <select class="form-control" name="iva" v-model="cotizacion.iva" required>
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Idioma</label>
                                        <select class="form-control" name="idioma" v-model="cotizacion.idioma" required>
                                            <option value="español">Español</option>
                                            <option value="ingles">Ingles</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr/>
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
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="text-right"><strong>Subtotal</strong></td>
                                                <td>@{{cotizacion.subtotal | formatoMoneda}}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="text-right"><strong>IVA</strong></td>
                                                <td v-if="cotizacion.iva=='0'">$0.00</td>
                                                <td v-else>@{{cotizacion.subtotal * 0.16 | formatoMoneda}}</td>
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
                                                <td v-if="cotizacion.iva=='0'">@{{cotizacion.subtotal |
                                                    formatoMoneda}}
                                                </td>
                                                <td v-else>@{{cotizacion.subtotal * 1.16 | formatoMoneda}}</td>
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
                                        <label class="control-label">Producto</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Producto"
                                                   v-model="entrada.producto.nombre" @click="openCatalogo=true"
                                                   readonly required
                                            />
                                            <span class="input-group-btn">
                        <button class="btn btn-default" type="button" @click="openCatalogo=true">
                          <i class="far fa-edit"></i>
                        </button>
                      </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Cantidad</label>
                                        <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control"
                                               v-model="entrada.cantidad" required/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Unidad Medida</label>
                                        <select class="form-control" name="medida" v-model="entrada.medida" required>
                                            @foreach($unidades_medida as $unidad)
                                                <option value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Precio</label>
                                        <input type="number" step="0.01" min="0.01" name="precio" class="form-control"
                                               v-model="entrada.precio" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" @click="modalProducto=true">
                                        Registrar producto
                                    </button>
                                </div>
                            </div>
                            <div class="row" style="padding-top: 10px;">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Precio de Compra</label>
                                        <input type="number" step="0.01" min="0.01" name="precio_compra"
                                               class="form-control"
                                               v-model="entrada.precio_compra"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Unidad Medida Compra</label>
                                        <select class="form-control" name="medida" v-model="entrada.medida_compra">
                                            @foreach($unidades_medida as $unidad)
                                                <option value="{{ $unidad->simbolo }}">{{ $unidad->simbolo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Fecha Precio</label>
                                        <br/>
                                        <dropdown>
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <btn class="dropdown-toggle" style="background-color:#fff;">
                                                        <i class="fas fa-calendar"></i>
                                                    </btn>
                                                </div>
                                                <input class="form-control" type="text" name="fecha"
                                                       v-model="entrada.fecha_precio_compra" placeholder="DD/MM/YYYY"
                                                       readonly
                                                />
                                            </div>
                                            <template slot="dropdown">
                                                <li>
                                                    <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                                                                 format="dd/MM/yyyy" :date-parser="dateParser"
                                                                 v-model="entrada.fecha_precio_compra"/>
                                                </li>
                                            </template>
                                        </dropdown>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Moneda Referencia</label>
                                        <select class="form-control" name="moneda" v-model="entrada.moneda_referencia">
                                            <option value=""></option>
                                            <option value="Dolares">Dolares USD</option>
                                            <option value="Pesos">Pesos MXN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Contacto Proveedor</label>
                                        <select class="form-control" name="medida"
                                                v-model="entrada.proveedor_contacto_id">
                                            <option v-for="contacto in entrada.producto.proveedor.contactos"
                                                    :value="contacto.id">@{{contacto.nombre}}
                                            </option>
                                        </select>
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
                                                <th>Valor Inglés</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(descripcion, index) in entrada.descripciones">
                                                <td>@{{descripcion.nombre}}</td>
                                                <td>@{{descripcion.name}}</td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                           v-model="descripcion.valor"/>
                                                </td>
                                                <td>
                                                    <input v-if="entrada.producto.descripciones[index].descripcion_nombre.valor_ingles"
                                                           type="text" class="form-control"
                                                           v-model="descripcion.valor_ingles"/>
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
                                            @{{observacion.texto}}
                                        </p>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Nueva Observación"
                                                   v-model="nuevaObservacionProducto"/>
                                            <span class="input-group-btn">
                        <button class="btn btn-default" type="button" @click="crearObservacionProducto()">
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
                                        <div class="btn btn-sm btn-danger" v-if="entrada.fotos.length" v-on:click="borrarfotos" title="BORRAR FOTOS">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                        <div class="file-loading">
                                            <input id="fotos" name="fotos[]" type="file" ref="fotos" multiple/>
                                        </div>
                                        <div id="fotos-file-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <div class="form-group" style="margin-top:25px;">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-plus"></i>
                                            Agregar Producto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- <div class="row">
                          <div class="col-md-12">
                            <div class="table-responsive">
                              <table id="tablaEntradas" class="table table-bordred" style="width:100%;">
                                <thead>
                                  <tr>
                                    <th>Orden</th>
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
                                  <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right"><strong>Subtotal</strong></td>
                                    <td>@{{cotizacion.subtotal | formatoMoneda}}</td>
                                    <td></td>
                                  </tr>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right"><strong>IVA</strong></td>
                                    <td v-if="cotizacion.iva=='0'">$0.00</td>
                                    <td v-else>@{{cotizacion.subtotal * 0.16 | formatoMoneda}}</td>
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
                                    <td v-if="cotizacion.iva=='0'">@{{cotizacion.subtotal | formatoMoneda}}</td>
                                    <td v-else>@{{cotizacion.subtotal * 1.16 | formatoMoneda}}</td>
                                    <td></td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label text-danger">Notas</label>
                                    <textarea class="form-control" name="notas" rows="3" cols="80"
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
                                        @{{observacion.texto}}
                                    </p>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Nueva Observación"
                                               v-model="nuevaObservacion"/>
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
                                    <a href="{{route('prospectos.index')}}" class="btn btn-default">
                                        Regresar
                                    </a>
                                    <button type="button" class="btn btn-primary"
                                            @click="guardar()" :disabled="cargando || edicionEntradaActiva">
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
                        <th>Nombre</th>
                        <th>Proveedor</th>
                        <th>Tipo</th>
                        <th>Ficha Técnica</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(prod, index) in productos">
                        <td>@{{prod.nombre}}</td>
                        <td>@{{prod.proveedor.empresa}}</td>
                        <td>@{{prod.categoria.nombre}}</td>
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

        <!-- Copiar Modal -->
        <modal v-model="openCopiar" :title="'Copiar Cotizacion'" :footer="false">
            <form class="" @submit.prevent="copiarCotizacion()">
                <div class="form-group">
                    <label class="control-label">Proyecto Destino</label>
                    <select name="proyecto_id" v-model="copiar_cotizacion.proyecto_id"
                            class="form-control" required id="proyecto-select" style="width: 300px;">
                        @foreach($proyectos as $proyecto)
                            <option value="{{$proyecto->id}}" @click="copiar3(index,{{$proyecto->id}});">{{$proyecto->nombre}}--{{$proyecto->cliente->nombre}}</option>
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

        <!-- Nuevo Producto Modal-->
        <modal v-model="modalProducto" title="Registrar Producto" :footer="false">
            <iframe id="theFrame" src="{{url("/")}}/productos/crear?layout=iframe" style="width:100%; height:700px;"
                    frameborder="0">
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
                colonias:[],
                colonias2:[],
                edicionEntradaActiva: false,
                locale: localeES,
                prospecto: {!! json_encode($prospecto) !!},
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
                    proyecto_id : '',
                    cotizacion_id :'',
                },
                cotizacion: {
                    prospecto_id: {{$prospecto->id}},
                    cliente_contacto_id: '',
                    numero: {{$numero_siguiente}},
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
                    instrucciones:'',
                    enviar_a:'',
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
                    fletes: '',
                    moneda: '{{ ($prospecto->cliente->nacional)?"Pesos":"Dolares" }}',
                    entradas: [],
                    subtotal: 0,
                    iva: '{{ ($prospecto->cliente->nacional)?"1":"0" }}',
                    total: 0,
                    idioma: '{{ ($prospecto->cliente->nacional)?"español":"ingles" }}',
                    notas: "",
                    observaciones: []
                },
                entrada: {
                    producto: {"proveedor": {"contactos": {}}},
                    orden: 0,
                    area: "",
                    cantidad: "",
                    medida: "",
                    precio: "",
                    precio_compra: "",
                    fecha_precio_compra: "",
                    medida_compra: "",
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
                notas: {
                    cotizacion_id: 0,
                    mensaje: ""
                },
                dataTableEntradas: {},
                openCatalogo: false,
                openEnviar: false,
                openAceptar: false,
                openNotas: false,
                openCopiar : false,
                cargando: false
            },
            computed: {
                totales_cotizaciones() {
                    var dolares = 0, pesos = 0;
                    this.prospecto.cotizaciones.forEach(function (cotizacion) {
                        if (cotizacion.moneda == "Pesos") pesos += cotizacion.total;
                        else dolares += cotizacion.total;
                    });
                    return {"dolares": dolares, "pesos": pesos}
                }
            },
            filters: {
                formatoMoneda(numero) {
                    return accounting.formatMoney(numero, "$", 2);
                },
            },
            mounted() {

                let self = this; // ámbito de vue

                // inicializas select2
                $('#proyecto-select')
                  .select2({ 
                    placeholder: 'Selecciona un proyecto',
                    //data: self.options, // cargas los datos en vez de usar el loop
                   })
                   // nos hookeamos en el evento tal y como puedes leer en su documentación
                   .on('select2:select', function () {       
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
                    defaultPreviewContent: '<img src="{{asset('images/camara.png')}}" style="width:200px; height:auto;" alt="foto"><h6>Click para seleccionar</h6>',
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
                this.tablaProductos = $("#tablaProductos").DataTable({dom: 'ftp'});

                this.dataTableEntradas = $("#tablaEntradas").DataTable({
                    data: [],
                    searching: false,
                    info: false,
                    columnDefs: [
                        {"orderable": true, "targets": 0},
                        {"orderable": false, "targets": '_all'}
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
                this.dataTableEntradas.on('row-reorder', function (e, diff, edit) {
                    var i = 0, j = diff.length;
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
                    .on('click', 'tr button.btn-success', function () {
                        var index = $(this).data('index');
                        console.log(index);
                        vueInstance.editarEntrada(vueInstance.cotizacion.entradas[index], index);
                    })
                    .on('click', 'button.btn-danger', function () {
                        var index = $(this).data('index');
                        vueInstance.removerEntrada(vueInstance.cotizacion.entradas[index], index);
                    });

                this.resetDataTables();

                //escuchar Iframe
                window.addEventListener('message', function (e) {
                    if (e.data.tipo == "producto") {
                        vueInstance.tablaProductos.destroy();
                        vueInstance.productos.push(e.data.object);
                        vueInstance.seleccionarProduco(e.data.object);
                        vueInstance.modalProducto = false;
                        Vue.nextTick(function () {
                            vueInstance.tablaProductos = $("#tablaProductos").DataTable({dom: 'ftp'});
                        });

                    }
                }, false);
            },
            methods: {
                cp() {
                    this.cargando = true;
                    if (this.cotizacion.cp && this.cotizacion.cp.length > 4) {
                        axios.get('http://sepomex.789.mx/' + this.cotizacion.cp, {})
                            .then(({data}) => {
                                
                                this.colonias = data.asentamientos;
                                this.cotizacion.estado = data.estados[0]
                                this.cotizacion.ciudad = data.municipios[0]
                                this.cargando = false;

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
                    }
                    axios.get('http://sepomex.789.mx/' + this.cotizacion.cp, {})
                        .then(({data}) => {
                            this.cotizacion.estado = data.estados[0]
                            this.cotizacion.ciudad = data.municipios[0]
                            this.cargando = false;

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

                borrarfotos(){
                    $("button.fileinput-remove").click();
                    this.entrada.fotos = [];
                },
                cp1() {
                    this.cargando = true;
                    if (this.cotizacion.dircp && this.cotizacion.dircp.length > 4) {
                        axios.get('http://sepomex.789.mx/' + this.cotizacion.dircp, {})
                            .then(({data}) => {
                                this.colonias2 = data.asentamientos;
                                this.cotizacion.dirciudad = data.municipios[0]
                                this.cotizacion.direstado = data.estados[0]
                                this.cargando = false;

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
                        this.cotizacion.contacto_nombre = this.direcciones[this.cotizacion.direccion].contacto_nombre;
                        this.cotizacion.contacto_email = this.direcciones[this.cotizacion.direccion].contacto_email;
                        this.cotizacion.contacto_telefono = this.direcciones[this.cotizacion.direccion].contacto_telefono;
                    }
                },
                condicionCambiada() {
                    if (this.cotizacion.condicion.id == 0) {
                        this.cotizacion.condicion.nombre = "";
                        return true;
                    }

                    this.condiciones.find(function (cond) {
                        if (cond.id == this.cotizacion.condicion.id) {
                            this.cotizacion.condicion.nombre = cond.nombre;
                        }
                    }, this);
                },
                actualizarCondicion() {
                    this.cargando = true;
                    axios.put('/condicionesCotizacion/' + this.cotizacion.condicion.id, {'nombre': this.cotizacion.condicion.nombre})
                        .then(({data}) => {
                            this.condiciones.find(function (cond) {
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
                borrarCondicion() {
                    this.cargando = true;
                    axios.delete('/condicionesCotizacion/' + this.cotizacion.condicion.id, {})
                        .then(({data}) => {
                            var indexCondicion = this.condiciones.findIndex(function (cond) {
                                return this.cotizacion.condicion.id == cond.id;
                            }, this);

                            this.condiciones.splice(indexCondicion, 1);
                            this.cotizacion.condicion = {id: 0, nombre: ''};

                            this.cargando = false;
                            swal({
                                title: "Condicion Actualizada",
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
                },
                resetDataTables() {
                    var rows = [], row = [];
                    this.cotizacion.entradas.forEach(function (entrada, index) {
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
                            '<button class="btn btn-xs btn-success" title="Editar" data-index="' + index + '">',
                            '<i class="fas fa-pencil-alt"></i>',
                            '</button>',
                            '<button class="btn btn-xs btn-danger" title="Remover" data-index="' + index + '">',
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
                    this.cotizacion.entradas.forEach(function (entrada) {
                        if (entrada.borrar != true) i++;
                    });
                    return i;
                },
                fijarComprobante() {
                    this.aceptar.comprobante = this.$refs['comprobante'].files[0];
                },
                agregarObservacion(observacion) {
                    this.cotizacion.observaciones.push(observacion.texto);
                    observacion.activa = true;
                },
                quitarObservacion(observacion) {
                    var index = this.cotizacion.observaciones.findIndex(function (obs) {
                        return observacion.texto == obs;
                    });
                    this.cotizacion.observaciones.splice(index, 1);
                    observacion.activa = false;
                },
                eliminarObservacion(observacion, index) {
                    axios.delete('/observacionesCotizacion/' + observacion.id, {})
                        .then(({data}) => {
                            this.quitarObservacion(observacion);
                            this.observaciones.splice(index, 1);
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
                crearObservacion() {
                    if (this.nuevaObservacion == "") return false;

                    axios.post('/observacionesCotizacion', {texto: this.nuevaObservacion})
                        .then(({data}) => {
                            this.observaciones.push(data.observacion);
                            this.agregarObservacion(this.observaciones[this.observaciones.length - 1]);
                            this.nuevaObservacion = "";
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
                agregarObservacionProducto(observacion) {
                    this.entrada.observaciones.push(observacion.texto);
                    observacion.activa = true;
                },
                quitarObservacionProducto(observacion) {
                    var index = this.entrada.observaciones.findIndex(function (obs) {
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
                    this.observaciones_productos.push({activa: false, texto: this.nuevaObservacionProducto});
                    this.agregarObservacionProducto(this.observaciones_productos[this.observaciones_productos.length - 1]);
                    this.nuevaObservacionProducto = "";
                },
                seleccionarProduco(prod) {
                    this.entrada.producto = prod;
                    this.entrada.descripciones = [];
                    prod.descripciones.forEach(function (desc) {
                        this.entrada.descripciones.push({
                            nombre: desc.descripcion_nombre.nombre,
                            name: desc.descripcion_nombre.name,
                            valor: desc.valor,
                            valor_ingles: desc.valor_ingles
                        });
                    }, this);

                    if (prod.foto) {
                        $("button.fileinput-remove").click();
                        $("div.file-default-preview img")[0].src = prod.foto;
                    }

                    this.openCatalogo = false;
                },
                agregarEntrada() {

                    var area  = '';
                    this.entrada.descripciones.forEach(function (descripcion) {
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

                    if (this.$refs['fotos'].files.length) {//hay fotos
                        this.entrada.fotos = [];
                        for (var i = 0; i < this.$refs['fotos'].files.length; i++)
                            this.entrada.fotos.push(this.$refs['fotos'].files[i]);
                    }


                    this.entrada.importe = this.entrada.cantidad * this.entrada.precio;
                    this.cotizacion.subtotal += this.entrada.importe;

                    if (this.entrada.orden == 0)
                        this.entrada.orden = this.cuentaEntradasNoBorradas() + 1;

                    this.cotizacion.entradas.push(this.entrada);
                    this.resetDataTables();
                    this.entrada = {
                        producto: {"proveedor": {"contactos": {}}},
                        orden: 0,
                        area:"",
                        cantidad: "",
                        medida: "",
                        medida_compra: "",
                        moneda_referencia: "",
                        precio: "",
                        precio_compra: "",
                        fecha_precio_compra: "",
                        importe: 0,
                        descripciones: [],
                        observaciones: [],
                        fotos: [],
                        proveedor_contacto_id: ""
                    };
                    this.edicionEntradaActiva = false;
                    $("button.fileinput-remove").click();
                    this.observaciones_productos.forEach(function (observacion) {
                        observacion.activa = false;
                    });
                },
                editarEntrada(entradaEdit, index) {
                    if (this.edicionEntradaActiva) return false;
                    entradaEdit.actualizar = true;
                    this.entrada = entradaEdit;
                    this.entrada.fecha_precio_compra = entradaEdit.fecha_precio_compra_formated;
                    this.cotizacion.subtotal = this.cotizacion.subtotal - entradaEdit.importe;
                    console.log(entradaEdit);
                    console.log(this.entrada);
                    this.cotizacion.entradas.splice(index, 1);

                    this.edicionEntradaActiva = true;
                    this.resetDataTables();

                    $("button.fileinput-remove").click();
                    if (this.entrada.fotos.length) {//hay fotos
                        if (typeof this.entrada.fotos[0] == "object") {
                            this.$refs['fotos'].files = FileListItem(this.entrada.fotos);
                            this.$refs['fotos'].dispatchEvent(new Event('change', {'bubbles': true}));
                        } else if (typeof this.entrada.fotos[0] == "string") {
                            $("div.file-default-preview").empty();
                            this.entrada.fotos.forEach(function (foto) {
                                $("div.file-default-preview")
                                    .append('<img src="' + foto + '" style="width:200px; height:auto;" alt="foto">');
                            });
                            $("div.file-default-preview").append('<h6>Click para seleccionar</h6>');
                        }
                    } else if (this.entrada.producto.foto) {
                        $("div.file-default-preview img")[0].src = this.entrada.producto.foto;
                    }

                    this.observaciones_productos.forEach(function (observacion) {
                        var index = this.entrada.observaciones.findIndex(function (obs) {
                            return observacion.texto == obs;
                        });
                        if (index == -1) observacion.activa = false;
                        else observacion.activa = true;
                    }, this);
                    return true;
                },
                removerEntrada(entrada, index, undefined) {
                    this.cotizacion.subtotal -= entrada.importe;
                    if (entrada.id == undefined) this.cotizacion.entradas.splice(index, 1);
                    else entrada.borrar = true;
                    $("button.fileinput-remove").click();

                    //restar 1 al orden de todas las entradas con orden mayor
                    //al de la entrada borrada
                    var orden = entrada.orden;
                    this.cotizacion.entradas.forEach(function (entrada) {
                        if (entrada.orden > orden && entrada.borrar == undefined) {
                            entrada.actualizar = true;
                            entrada.orden--;
                        }
                    });

                    this.resetDataTables();
                },
                copiar2(index,cotizacion){
                    this.copiar_cotizacion.cotizacion_id = cotizacion.id;
                },
                copiar(index, cotizacion) {
                    //reiniciar observaciones
                    this.observaciones.forEach(function (observacion) {
                        observacion.activa = false;
                    });
                    var numero = this.cotizacion.numero;
                    //vaciar datos de cotizacion
                    this.cotizacion = {
                        prospecto_id: {{$prospecto->id}},
                        cliente_contacto_id: cotizacion.cliente_contacto_id,
                        numero: numero,
                        condicion: {
                            id: cotizacion.condicion_id,
                            nombre: ''
                        },
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
                        direccion: (cotizacion.direccion) ? 1 : 0,
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
                        lugar: cotizacion.lugar,
                        fletes: cotizacion.fletes,
                        moneda: cotizacion.moneda,
                        entradas: cotizacion.entradas,
                        subtotal: cotizacion.subtotal,
                        iva: (cotizacion.iva == 0) ? 0 : 1,
                        total: cotizacion.total,
                        idioma: cotizacion.idioma,
                        notas: cotizacion.notas,
                        observaciones: []
                    };
                    this.condicionCambiada();

                    //re-seleccionar observaciones
                    var observaciones = cotizacion.observaciones.match(/<li>([^<]+)+<\/li>+/g);
                    if (observaciones == null) observaciones = [];
                    var encontrada;
                    observaciones.forEach(function (observacion) {
                        observacion = observacion.replace(/(<li>|<\/li>)/g, '');
                        encontrada = this.observaciones.findIndex(function (obs) {
                            return observacion == obs.texto;
                        });

                        if (encontrada != -1) {
                            this.observaciones[encontrada].activa = true;
                        } else { //observacion diferente de las predefinidas
                            this.observaciones.push({activa: true, texto: observacion});
                        }
                        this.cotizacion.observaciones.push(observacion);
                    }, this);

                    // agregar observaciones de entradas de productos
                    cotizacion.entradas.forEach(function (entrada) {
                        observaciones = entrada.observaciones.match(/<li>([^<]+)+<\/li>+/g);
                        entrada.observaciones = [];
                        if (observaciones == null) return false;
                        encontrada;
                        observaciones.forEach(function (observacion) {
                            observacion = observacion.replace(/(<li>|<\/li>)/g, '');
                            entrada.observaciones.push(observacion);

                            encontrada = this.observaciones_productos.findIndex(function (obs) {
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
                editar(index, cotizacion) {

                    this.prospecto.cotizaciones.splice(index, 1);

                    //reiniciar observaciones
                    this.observaciones.forEach(function (observacion) {
                        observacion.activa = false;
                    });

                    //vaciar datos de cotizacion
                    this.cotizacion = {
                        prospecto_id: {{$prospecto->id}},
                        cliente_contacto_id: cotizacion.cliente_contacto_id,
                        cotizacion_id: cotizacion.id,
                        numero: cotizacion.numero,
                        condicion: {
                            id: cotizacion.condicion_id,
                            nombre: ''
                        },
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
                        direccion: (cotizacion.direccion) ? 1 : 0,
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
                        lugar: cotizacion.lugar,
                        fletes: cotizacion.fletes,
                        moneda: cotizacion.moneda,
                        entradas: cotizacion.entradas,
                        subtotal: cotizacion.subtotal,
                        iva: (cotizacion.iva == 0) ? 0 : 1,
                        total: cotizacion.total,
                        idioma: cotizacion.idioma,
                        notas: cotizacion.notas,
                        observaciones: []
                    };
                    this.condicionCambiada();

                    //re-seleccionar observaciones
                    var observaciones = cotizacion.observaciones.match(/<li>([^<]+)+<\/li>+/g);
                    if (observaciones == null) observaciones = [];
                    var encontrada;
                    observaciones.forEach(function (observacion) {
                        observacion = observacion.replace(/(<li>|<\/li>)/g, '');
                        encontrada = this.observaciones.findIndex(function (obs) {
                            return observacion == obs.texto;
                        });

                        if (encontrada != -1) {
                            this.observaciones[encontrada].activa = true;
                        } else { //observacion diferente de las predefinidas
                            this.observaciones.push({activa: true, texto: observacion});
                        }
                        this.cotizacion.observaciones.push(observacion);
                    }, this);

                    // agregar observaciones de entradas de productos
                    cotizacion.entradas.forEach(function (entrada) {
                        observaciones = entrada.observaciones.match(/<li>([^<]+)+<\/li>+/g);
                        entrada.observaciones = [];
                        if (observaciones == null) return false;
                        encontrada;
                        observaciones.forEach(function (observacion) {
                            observacion = observacion.replace(/(<li>|<\/li>)/g, '');
                            entrada.observaciones.push(observacion);

                            encontrada = this.observaciones_productos.findIndex(function (obs) {
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
                    var cotizacion = $.extend(true, {}, this.cotizacion);
                    //console.log(cotizacion.subtotal);
                    var totalf = 0;
                    cotizacion.entradas.forEach(function (entrada) {
                        totalf += entrada.importe ;
                    });

                    totalcotizacion = cotizacion.subtotal.toFixed(2);

                    console.log(totalcotizacion);
                    console.log(totalf);
                    console.log(totalcotizacion - totalf);

                    var dif = totalcotizacion - totalf;

                    
                    if(dif > 0.05){
                        alert('OCURRIO UN ERROR INESPERADO EL SUBTOTAL NO COINCIDE FAVOR DE RECARGAR LA PAGINA');
                    }
                    else{
                    

                    
                        cotizacion.entradas.forEach(function (entrada) {
                            entrada.producto_id = entrada.producto.id;
                            delete entrada.producto;
                        });
                        var formData = objectToFormData(cotizacion, {indices: true});
                        var url = "", numero_siguiente = false;

                        if (this.cotizacion.cotizacion_id) {
                            url = '/prospectos/{{$prospecto->id}}/cotizacion/' + this.cotizacion.cotizacion_id;
                            numero_siguiente = {{$numero_siguiente}};
                        } else url = '/prospectos/{{$prospecto->id}}/cotizacion';

                        this.cargando = true;
                        axios.post(url, formData, {
                            headers: {'Content-Type': 'multipart/form-data'}
                        })
                            .then(({data}) => {
                                this.prospecto.cotizaciones.push(data.cotizacion);
                                this.cotizacion = {
                                    prospecto_id: {{$prospecto->id}},
                                    cliente_contacto_id: '',
                                    @can('editar numero cotizacion')
                                    numero: (numero_siguiente) ? numero_siguiente : data.cotizacion.id + 1,
                                    @else
                                    numero: 0,
                                    @endcan
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
                                    contacto_nombre: '',
                                    contacto_telefono: '',
                                    contacto_email: '',
                                    dirciudad: '',
                                    direstado: '',
                                    entrega: '',
                                    lugar: '',
                                    fletes: '',
                                    moneda: '{{ ($prospecto->cliente->nacional)?"Pesos":"Dolares" }}',
                                    entradas: [],
                                    subtotal: 0,
                                    iva: '{{ ($prospecto->cliente->nacional)?"1":"0" }}',
                                    total: 0,
                                    notas: "",
                                    idioma: '{{ ($prospecto->cliente->nacional)?"español":"ingles" }}',
                                    observaciones: []
                                };
                                this.observaciones.forEach(function (observacion) {
                                    observacion.activa = false;
                                });
                                this.resetDataTables();
                                this.cargando = false;
                                swal({
                                    title: "Cotizacion Guardada",
                                    text: "",
                                    type: "success"
                                }).then(() => {
                                    $('a[download="C ' + data.cotizacion.numero + ' Intercorp ' + this.prospecto.nombre + '.pdf"]')[0].click();
                                    window.location.reload(true);
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
                    }
                },//fin guardar
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
            }
        });
    </script>
@stop
