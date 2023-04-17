@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Lista de Ordenes | @parent
@stop

@section('header_styles')
<style>
  .marg025 {margin: 0 25px;}
  #tabla_length{
    float: left !important;
  }

  .color_text{
    color:#FBAE08;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 style="font-weight: bolder;">Lista de Ordenes</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <!--
  <tabs>
      
      <tab title="Lista de Ordenes">
      -->
        <div class="row">
          <div class="col-lg-12">
            <div class="panel">
              <div class="panel-heading">
                <h3 class="panel-title">
                  <div class="p-10">
                    Lista de Proyectos
                    @role('Administrador')
                      de 
                      <select class="form-control" @change="cargar()" v-model="usuarioCargado" style="width:auto;display:inline-block;">
                        <option value="Todos">Todos</option>
                        @foreach($usuarios as $usuario)
                        <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                        @endforeach
                      </select>
                    @endrole
                  </div>
                  <div class="p-10">
                    Año  
                      <select class="form-control" @change="cargar()" v-model="anio" style="width:auto;display:inline-block;">
                        <option value="Todos">Todos</option>
                        <option value="2019-12-31">2019</option>
                        <option value="2020-12-31">2020</option>
                        <option value="2021-12-31">2021</option>
                        <option value="2022-12-31">2022</option>
                        <option value="2023-12-31">2023</option>
                      </select>
                  </div>
                </h3>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                  <table id="tabla" class="table table-bordred" style="width:100%;"
                    data-page-length="-1">
                    <thead>
                      <tr style="background-color:#12160F">
                        <th class="color_text"># Cotización</th>
                        <th class="color_text">Usuario</th>
                        <th class="color_text">Cliente</th>
                        <th class="color_text">Proyecto</th>
                        <th class="color_text">Fecha aprobación</th>
                        <th class="color_text">Proveedores</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(proyecto,index) in proyectos">
                        <td>@{{proyecto.cotizacion.numero}}</td>
                        <td>@{{proyecto.cotizacion.user.name}}</td>
                        <td>@{{proyecto.cliente_nombre}}</td>
                        <td>@{{proyecto.proyecto}}</td>
                        {{--<td>@{{proyecto.created_at|date}}</td>--}}
                        <td v-if="proyecto.cotizacion.cuenta_cobrar !== null && proyecto.cotizacion.cuenta_cobrar !== undefined && proyecto.cotizacion.cuenta_cobrar.fecha_comprobante !== undefined && proyecto.cotizacion.cuenta_cobrar.fecha_comprobante !== null">@{{proyecto.cotizacion.cuenta_cobrar.fecha_comprobante|date}}</td>
                        <td v-if="
                        proyecto.cotizacion.cuenta_cobrar === null ||
                        proyecto.cotizacion.cuenta_cobrar === undefined ||  proyecto.cotizacion.cuenta_cobrar.fecha_comprobante === undefined || proyecto.cotizacion.cuenta_cobrar.fecha_comprobante === null">@{{proyecto.created_at|date}}</td>

                        <td>
                          <span v-for="(orden, index) in proyecto.ordenes">
                            @{{index+1}}.- @{{orden.proveedor_empresa}} ,@{{orden.numero}} , @{{orden.status}}  <br/>
                          </span>
                        </td>
                        <td class="text-right">
                          <a class="btn btn-xs btn-info" title="Ver Cotización"
                            target="_blank" :href="proyecto.cotizacion.archivo">
                            <i class="far fa-file"></i>
                          </a>
                          <!--
                          <a class="btn btn-xs btn-info" title="Ver Proyecto"
                            target="_blank" :href="'/proyectos-aprobados/'+proyecto.id+'/show'">
                            <i class="far fa-eye"></i>
                          </a>
                        -->
                      
                          <a class="btn btn-xs btn-success" title="Ordenes Compra"
                            :href="'/proyectos-aprobados/'+proyecto.id+'/ordenes-compra'">
                            <i class="fas fa-file-invoice-dollar"></i>
                          </a>
                          <button class="btn btn-xs btn-danger" title="Borrar" @click="borrar(proyecto, index)">
                            <i class="fas fa-times"></i>
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
      <!--
      </tab>
    -->
      <!--
    <tab title="Ordenes en Proceso">
        <div class="row">
          <div class="col-lg-12">
            <div class="panel">
              <div class="panel-heading">
                <h3 class="panel-title">
                  <span class="p-10">Lista de Ordenes</span>
                  <div class="p-10">
                    Ejecutivo  
                    @role('Administrador')
                    <select class="form-control" @change="cargar2()" v-model="usuarioCargado2" style="width:auto;display:inline-block;">
                      <option value="Todos">Todos</option>
                      @foreach($usuarios as $usuario)
                      <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                      @endforeach
                    </select>
                    @endrole
                    
                  </div>
                  <div class="p-10">
                    Año  
                      <select class="form-control" @change="cargar2()" v-model="anio2" style="width:auto;display:inline-block;">
                        <option value="Todos">Todos</option>
                        <option value="2019-12-31">2019</option>
                        <option value="2020-12-31">2020</option>
                        <option value="2021-12-31">2021</option>
                        <option value="2022-12-31">2022</option>
                      </select>
                  </div>
                </h3>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                  <table id="tabla2" class="table table-bordred" style="width:100%;"
                    data-page-length="-1">
                    <thead>
                      <tr style="background-color:#907ff3">
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
                      <tr v-for="(orden,index) in ordenes">
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
                            :download="'INTERCORP-PO '+orden.numero+orden.orden_compra.cliente_nombre+orden.orden_compra.proyecto_nombre+'.pdf'">
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
      </tab>
    -->
<!--
  </tabs>
-->


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
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}" ></script>
<script>
const app = new Vue({
    el: '#content',
    data: {
      anio:'2023-12-31',
      anio2:'2023-12-31',
      proyectos: {!! json_encode($proyectos) !!},
      usuarioCargado: {{auth()->user()->id}},
      usuarioCargado2: {{auth()->user()->id}},
      tabla: {},
      tabla2: {},
      locale: localeES,
      ordenes: {!! json_encode($ordenes) !!},
      ordenHistorial: {},
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
      cargando: false,
      openHistorial: false,
      openEmbarcar: false,
      openFrontera: false,
      openAduana: false,
      openEntrega: false
    },
    mounted(){
      this.tabla = $("#tabla").DataTable({"order": [[ 4, "desc" ]]});
      this.tabla2 = $("#tabla2").DataTable({"order": [[ 1, "asc" ]]});
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
    },
      filters:{
      date(value){
  			return moment(value, 'YYYY-MM-DD  hh:mm:ss').format('YYYY/MM/DD');
      },
    },
    methods:{
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      cargar(){
        axios.post('/proyectos-aprobados/listado', {id: this.usuarioCargado, anio:this.anio})
        .then(({data}) => {
          this.tabla.destroy();
          this.proyectos = data.proyectos;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tabla = $("#tabla").DataTable({"order": [[4,"desc" ]]});
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
      borrar(proyecto, index){
        swal({
          title: 'Cuidado',
          text: "Desaprobar el proyecto "+proyecto.proyecto+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/proyectos-aprobados/'+proyecto.id, {})
            .then(({data}) => {
              this.proyectos.splice(index, 1);
              swal({
                title: "Exito",
                text: "El Proyecto ha sido desaprobado",
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

      //Metodos de ordenes en proceso
      cargar2(){
        axios.post('/ordenes-proceso/listado', {id: this.usuarioCargado2,anio:this.anio2})
        .then(({data}) => {
          this.tabla2.destroy();
          this.ordenes = data.ordenes;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tabla2 = $("#tabla2").DataTable();
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
    }
});
</script>
@stop
