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
                      <a class="btn btn-info"
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
                    <button v-if="orden.status=='En fabricación'" class="btn btn-brown"
                      title="Embarcar" @click="embarcar.orden_id=orden.id; openEmbarcar=true;">
                      <i class="fas fa-dolly-flatbed"></i>
                    </button>
                    <button v-if="orden.status=='Embarcado de fabrica'" class="btn btn-warning"
                      title="Aduana" @click="aduana.orden_id=orden.id; openAduana=true;">
                      <i class="fas fa-warehouse"></i>
                    </button>
                    <button v-if="orden.status=='Aduana'" class="btn btn-unique"
                      title="Importación" @click="importarOrden(orden)">
                      <i class="fas fa-ship"></i>
                    </button>
                    <button v-if="orden.status=='Proceso de Importación'" class="btn btn-success"
                      title="Liberadar Aduana" @click="liberarOrden(orden)">
                      <i class="fas fa-lock-open"></i>
                    </button>
                    <button v-if="orden.status=='Liberado de Aduana'" class="btn btn-elegant"
                      title="Embarque final" @click="embarqueFinal(orden)">
                      <i class="fas fa-shipping-fast"></i>
                    </button>
                    <button v-if="orden.status=='Embarque al destino Final'" class="btn btn-purple"
                      title="Descarga" @click="descargarOrden(orden)">
                      <i class="fas fa-dolly"></i>
                    </button>
                    <button v-if="orden.status=='Descarga'" class="btn btn-default"
                      title="Entrega" @click="entregarOrden(orden)">
                      <i class="fas fa-box"></i>
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
              @change="fijarDocumentoEmbarque('certificado')" required />
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

  <!-- Embarcar Modal -->
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
      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
        <button type="button" class="btn btn-default"
          @click="aduana.orden_id=0; openAduana=false;">
          Cancelar
        </button>
      </div>
    </form>
  </modal>
  <!-- /.Embarcar Modal -->

</section>
<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script>

const app = new Vue({
    el: '#content',
    data: {
      ordenes: {!! json_encode($ordenes) !!},
      embarcar: {
        orden_id: 0,
        factura: '',
        packing: '',
        bl: '',
        certificado: ''
      },
      aduana: {
        orden_id: 0,
        gastos: '',
        pago: '',
      },
      cargando: false,
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
      fijarDocumentoEmbarque(documento){
        this.embarcar[documento] = this.$refs[documento].files[0];
      },
      fijarDocumentoAduana(documento){
        this.aduana[documento] = this.$refs[documento].files[0];
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
      importarOrden(orden){
        swal({
          title: 'Atención',
          text: "Importar la orden "+orden.id+"?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Importar',
          cancelButtonText: 'No, dejar como esta',
        }).then((result) => {
          if (result.value) {
            axios.post('/ordenes-proceso/'+orden.id+'/importar', {})
            .then(({data}) => {
              orden.status = data.orden.status;
              swal({
                title: "Exito",
                text: "La orden se ha pasado a "+orden.status,
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
      },//importarOrden
      liberarOrden(orden){
        swal({
          title: 'Atención',
          text: "Liberar de aduana la orden "+orden.id+"?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Liberar',
          cancelButtonText: 'No, dejar como esta',
        }).then((result) => {
          if (result.value) {
            axios.post('/ordenes-proceso/'+orden.id+'/liberar', {})
            .then(({data}) => {
              orden.status = data.orden.status;
              swal({
                title: "Exito",
                text: "La orden se ha pasado a "+orden.status,
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
      },//liberarOrden
      embarqueFinal(orden){
        swal({
          title: 'Atención',
          text: "Embarcar a destino final la orden "+orden.id+"?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Embarcar',
          cancelButtonText: 'No, dejar como esta',
        }).then((result) => {
          if (result.value) {
            axios.post('/ordenes-proceso/'+orden.id+'/embarqueFinal', {})
            .then(({data}) => {
              orden.status = data.orden.status;
              swal({
                title: "Exito",
                text: "La orden se ha pasado a "+orden.status,
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
      },//embarqueFinal
      descargarOrden(orden){
        swal({
          title: 'Atención',
          text: "Descargar la orden "+orden.id+"?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Descargar',
          cancelButtonText: 'No, dejar como esta',
        }).then((result) => {
          if (result.value) {
            axios.post('/ordenes-proceso/'+orden.id+'/descargar', {})
            .then(({data}) => {
              orden.status = data.orden.status;
              swal({
                title: "Exito",
                text: "La orden se ha pasado a "+orden.status,
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
      },//descargarOrden
      entregarOrden(orden){
        swal({
          title: 'Atención',
          text: "Entregar la orden "+orden.id+"?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Entregar',
          cancelButtonText: 'No, dejar como esta',
        }).then((result) => {
          if (result.value) {
            axios.post('/ordenes-proceso/'+orden.id+'/entregar', {})
            .then(({data}) => {
              orden.status = data.orden.status;
              swal({
                title: "Exito",
                text: "La orden se ha pasado a "+orden.status,
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
      },//entregarOrden

    }

});
</script>
@stop
