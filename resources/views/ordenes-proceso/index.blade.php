@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Ordenes en Proceso | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Ordenes en Proceso</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            <span class="p-10">Lista de Ordenes</span>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordred">
              <thead>
                <tr>
                  <th>Cliente</th>
                  <th>Proyecto</th>
                  <th>Proveedor</th>
                  <th>Orden Numero</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="orden in ordenes">
                  <td>@{{orden.orden_compra.cliente_nombre}}</td>
                  <td>@{{orden.orden_compra.proyecto_nombre}}</td>
                  <td>@{{orden.orden_compra.proveedor_empresa}}</td>
                  <td>@{{orden.id}}</td>
                  <td>@{{orden.status}}</td>
                  <td class="text-right">
                    {{-- <a class="btn btn-info" title="Ver"
                      :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
                      <i class="far fa-eye"></i>
                    </a> --}}
                    <button class="btn btn-info"
                      title="Historial" @click="ordenHistorial=orden; openHistorial=true;">
                      <i class="fas fa-history"></i>
                    </button>
                    {{-- Descarga de archivos --}}
                    <a v-if="orden.orden_compra.archivo" class="btn btn-warning"
                      title="PDF" :href="orden.orden_compra.archivo"
                      :download="'orden-compra '+orden.orden_compra.id+'.pdf'">
                      <i class="far fa-file-pdf"></i>
                    </a>
                    <template v-if="orden.factura" >
                      <a class="btn btn-info"
                        title="Factura" :href="orden.factura"
                        :download="'Factura orden proceso '+orden.id">
                        <i class="fas fa-file-invoice-dollar"></i>
                      </a>
                      <a class="btn btn-info"
                        title="Packing List" :href="orden.packing"
                        :download="'Packing list orden proceso '+orden.id">
                        <i class="fas fa-list-ol"></i>
                      </a>
                      <a class="btn btn-info"
                        title="BL" :href="orden.bl"
                        :download="'BL orden proceso '+orden.id">
                        <i class="fas fa-file"></i>
                      </a>
                      <a v-if="orden.certificado" class="btn btn-info"
                        title="Certificado" :href="orden.certificado"
                        :download="'Certificado orden proceso '+orden.id">
                        <i class="fas fa-file-contract"></i>
                      </a>
                    </template>
                    <template v-if="orden.gastos" >
                      <a class="btn btn-info"
                        title="Cuenta de gastos" :href="orden.gastos"
                        :download="'Cuenta gastos orden proceso '+orden.id">
                        <i class="fas fa-file-invoice"></i>
                      </a>
                      <a class="btn btn-info"
                        title="Pago" :href="orden.pago"
                        :download="'Pago orden proceso '+orden.id">
                        <i class="fas fa-money-check-alt"></i>
                      </a>
                    </template>
                    {{-- Botones de acciones --}}
                    <button v-if="orden.status=='En fabricación' && !orden.fecha_estimada_fabricacion"
                      class="btn btn-info" title="Fabricación"
                      @click="updateStatus(orden)">
                      <i class="fas fa-industry"></i>
                    </button>
                    <button v-if="orden.status=='En fabricación' && orden.fecha_estimada_fabricacion"
                      class="btn btn-brown" title="Embarcar"
                      @click="embarcar.orden_id=orden.id; openEmbarcar=true;">
                      <i class="fas fa-dolly-flatbed"></i>
                    </button>
                    <button v-if="orden.status=='Embarcado de fabrica'" class="btn btn-warning"
                      title="Aduana" @click="aduana.orden_id=orden.id; openAduana=true;">
                      <i class="fas fa-warehouse"></i>
                    </button>
                    <button v-if="orden.status=='Aduana'" class="btn btn-unique"
                      title="Importación" @click="updateStatus(orden)">
                      <i class="fas fa-ship"></i>
                    </button>
                    <button v-if="orden.status=='Proceso de Importación'" class="btn btn-success"
                      title="Liberadar Aduana" @click="updateStatus(orden)">
                      <i class="fas fa-lock-open"></i>
                    </button>
                    <button v-if="orden.status=='Liberado de Aduana'" class="btn btn-elegant"
                      title="Embarque final" @click="updateStatus(orden)">
                      <i class="fas fa-shipping-fast"></i>
                    </button>
                    <button v-if="orden.status=='Embarque al destino Final'" class="btn btn-purple"
                      title="Descarga" @click="updateStatus(orden)">
                      <i class="fas fa-dolly"></i>
                    </button>
                    <button v-if="orden.status=='Descarga'" class="btn btn-default"
                      title="Entrega" @click="updateStatus(orden)">
                      <i class="fas fa-box"></i>
                    </button>
                    <button v-if="orden.status=='Entregado' && !orden.fecha_estimada_instalacion"
                      class="btn btn-info" title="Instalación"
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
              <tr>
                <td>En fabricación</td>
                <td>@{{ ordenHistorial.fecha_estimada_fabricacion }}</td>
                <td>@{{ ordenHistorial.fecha_real_fabricacion }}</td>
              </tr>
              <tr>
                <td>Embarcado de fabrica</td>
                <td>@{{ ordenHistorial.fecha_estimada_embarque }}</td>
                <td>@{{ ordenHistorial.fecha_real_embarque }}</td>
              </tr>
              <tr>
                <td>Aduana</td>
                <td>@{{ ordenHistorial.fecha_estimada_aduana }}</td>
                <td>@{{ ordenHistorial.fecha_real_aduana }}</td>
              </tr>
              <tr>
                <td>Proceso de Importación</td>
                <td>@{{ ordenHistorial.fecha_estimada_importacion }}</td>
                <td>@{{ ordenHistorial.fecha_real_importacion }}</td>
              </tr>
              <tr>
                <td>Liberado de Aduana</td>
                <td>@{{ ordenHistorial.fecha_estimada_liberado_aduana }}</td>
                <td>@{{ ordenHistorial.fecha_real_liberado_aduana }}</td>
              </tr>
              <tr>
                <td>Embarque al destino Final</td>
                <td>@{{ ordenHistorial.fecha_estimada_embarque_final }}</td>
                <td>@{{ ordenHistorial.fecha_real_embarque_final }}</td>
              </tr>
              <tr>
                <td>Descarga</td>
                <td>@{{ ordenHistorial.fecha_estimada_descarga }}</td>
                <td>@{{ ordenHistorial.fecha_real_descarga }}</td>
              </tr>
              <tr>
                <td>Entregado</td>
                <td>@{{ ordenHistorial.fecha_estimada_entrega }}</td>
                <td>@{{ ordenHistorial.fecha_real_entrega }}</td>
              </tr>
              <tr>
                <td>Instalacion</td>
                <td>@{{ ordenHistorial.fecha_estimada_instalacion }}</td>
                <td>@{{ ordenHistorial.fecha_real_instalacion }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="form-group text-right">
      <button type="button" class="btn btn-default" @click="openHistorial=false;">Aceptar</button>
    </div>
  </modal>
  <!-- /.Historial Modal -->

  <!-- Estimados Modal -->
  <modal v-model="openEstimados" :header="false" :footer="false" :backdrop="false"
    :keyboard="false" size="sm">
    <h4 class="text-center">
      Seleccione la fecha estimada para completar la
      <span v-show="estimados.status=='En fabricación'">Fabricación</span>
      <span v-show="estimados.status=='Aduana'">Importación</span>
      <span v-show="estimados.status=='Proceso de Importación'">Liberacion de Aduana</span>
      <span v-show="estimados.status=='Liberado de Aduana'">Embarcación Final</span>
      <span v-show="estimados.status=='Embarque al destino Final'">Descarga</span>
      <span v-show="estimados.status=='Descarga'">Entrega</span>
      <span v-show="estimados.status=='Entrega'">Instalacion</span>
    </h4>
    <div class="form-group">
      <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
        format="dd/MM/yyyy" :date-parser="dateParser"
        v-model="estimados.fecha_estimada"
      />
    </div>
    <div class="text-right">
      <button id="aceptarEstimado" type="button" class="btn btn-primary" :disabled="cargando">
        Aceptar
      </button>
      <button id="cancelarEstimado" type="button" class="btn btn-default">
        Cancelar
      </button>
    </div>
  </modal>
  <!-- /.Estimados Modal -->

  <!-- Embarcar Modal -->
  <modal v-model="openEmbarcar" :title="'Embarcar Orden '+embarcar.orden_id" :footer="false">
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
              @change="fijarDocumentoEmbarque('bl')" required />
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
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Feche estimada para completar embarque</label>
            <br />
            <dropdown>
              <div class="input-group">
                <div class="input-group-btn">
                  <btn class="dropdown-toggle" style="background-color:#fff;">
                    <i class="fas fa-calendar"></i>
                  </btn>
                </div>
                <input class="form-control" type="text" name="fecha"
                  v-model="embarcar.fecha_estimada" placeholder="DD/MM/YYYY"
                  readonly
                />
              </div>
              <template slot="dropdown">
                <li>
                  <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                  format="dd/MM/yyyy" :date-parser="dateParser" v-model="embarcar.fecha_estimada"/>
                </li>
              </template>
            </dropdown>
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

  <!-- Aduana Modal -->
  <modal v-model="openAduana" :title="'Mandar orden '+aduana.orden_id+' a Aduana'" :footer="false">
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
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Feche estimada para completar aduana</label>
            <br />
            <dropdown>
              <div class="input-group">
                <div class="input-group-btn">
                  <btn class="dropdown-toggle" style="background-color:#fff;">
                    <i class="fas fa-calendar"></i>
                  </btn>
                </div>
                <input class="form-control" type="text" name="fecha"
                  v-model="aduana.fecha_estimada" placeholder="DD/MM/YYYY"
                  readonly
                />
              </div>
              <template slot="dropdown">
                <li>
                  <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                  format="dd/MM/yyyy" :date-parser="dateParser" v-model="aduana.fecha_estimada"/>
                </li>
              </template>
            </dropdown>
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

</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script>

const app = new Vue({
    el: '#content',
    data: {
      locale: localeES,
      ordenes: {!! json_encode($ordenes) !!},
      ordenHistorial: {},
      estimados: {
        status: '',
        fecha_estimada: ''
      },
      embarcar: {
        orden_id: 0,
        factura: '',
        packing: '',
        bl: '',
        certificado: '',
        fecha_estimada: ''
      },
      aduana: {
        orden_id: 0,
        gastos: '',
        pago: '',
        fecha_estimada: ''
      },
      cargando: false,
      openHistorial: false,
      openEstimados: false,
      openEmbarcar: false,
      openAduana: false
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
    },
    methods:{
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
  		},
      fijarDocumentoEmbarque(documento){
        this.embarcar[documento] = this.$refs[documento].files[0];
      },
      fijarDocumentoAduana(documento){
        this.aduana[documento] = this.$refs[documento].files[0];
      },
      lanzarModalEstimados(orden){
        this.estimados.orden_id = orden.id;
        this.estimados.status = orden.status;
        this.openEstimados = true;

        var promise = new Promise(function(resolve, reject) {
          $("#aceptarEstimado").one('click', function(){ resolve();});
          $("#cancelarEstimado").one('click', function(){ reject();});
        });

        return promise;
      },
      updateStatus(orden){
        this.lanzarModalEstimados(orden).then(() => {
          if(this.estimados.fecha_estimada){
            this.cargando = true;
            axios.post('/ordenes-proceso/'+this.estimados.orden_id+'/updateStatus',
            this.estimados)
            .then(({data}) => {
              this.ordenes.find(function(orden){
                if(this.estimados.orden_id == orden.id){
                  for (propiedad in data.actualizados){
                    orden[propiedad] = data.actualizados[propiedad];
                  }
                  return true;
                }
              }, this);

              this.estimados = {
                orden_id: 0,
                status: '',
                fecha_estimada: ''
              };
              this.openEstimados = false;
              this.cargando = false;
              swal({
                title: "Orden Actualizada",
                text: 'Se ha actualizado la orden',
                type: "success"
              });
            })
            .catch(({response}) => {
              console.error(response);
              this.estimados = {
                orden_id: 0,
                status: '',
                fecha_estimada: ''
              };
              this.openEstimados = false;
              this.cargando = false;
              swal({
                title: "Error",
                text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                type: "error"
              });
            });
          }
        })
        .catch(() => {
          this.estimados = {
            orden_id: 0,
            status: '',
            fecha_estimada: ''
          };
          this.openEstimados = false;
        });
      },
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
              orden.fecha_estimada_embarque = data.orden.fecha_estimada_embarque;
              return true;
            }
          }, this);

          this.embarcar = {
            orden_id: 0,
            factura: '',
            packing: '',
            bl: '',
            certificado: '',
            fecha_estimada: ''
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
              orden.fecha_real_embarque = data.orden.fecha_real_embarque;
              orden.fecha_estimada_aduana = data.orden.fecha_estimada_aduana;
              return true;
            }
          }, this);

          this.aduana = {
            orden_id: 0,
            gastos: '',
            pago: '',
            fecha_estimada: ''
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
    }

});
</script>
@stop
