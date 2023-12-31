@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Facturas Cuenta por Cobrar | @parent
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
  <!-- Content Header (Page header) -->
  <section class="content-header"  style="background-color:#12160F; color:#B68911;">
    <h1 style="font-weight: bolder;">Cuentas por Cobrar</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel">
          <div class="panel-heading"  style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Facturas Cuenta {{$cuenta->id}}: {{$cuenta->proyecto}}</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Cliente</label>
                  <span class="form-control">{{$cuenta->cliente}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Condiciones de Pago</label>
                  <span class="form-control">{{$cuenta->condiciones}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Total @{{cuenta.moneda}}</label>
                  <span class="form-control">@{{cuenta.total | formatoMoneda}}</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Facturado</label>
                  <span class="form-control">@{{cuenta.facturado | formatoMoneda}}</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Pagado</label>
                  <span class="form-control">@{{cuenta.pagado | formatoMoneda}}</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Monto Pendiente</label>
                  <span class="form-control">@{{cuenta.pendiente | formatoMoneda}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <h4>Facturas en Cuenta</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Documento</th>
                        <th>Monto Total</th>
                        <th>Pagos</th>
                        <th>Monto Pendiente</th>
                        <th>Fecha Vencimiento</th>
                        <th>Fecha Emisión</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(factura, index) in cuenta.facturas">
                        <td>@{{factura.documento}}</td>
                        <td>@{{factura.monto | formatoMoneda}}</td>
                        <td>
                          <template v-for="(pago, index) in factura.pagos">
                            <span>@{{index+1}}.- @{{pago.monto | formatoMoneda}} .- @{{pago.fecha}}</span>
                            <a class="btn btn-xs btn-info"
                              target="_blank" :href="pago.comprobante">
                              <i class="far fa-eye"></i>
                            </a>
                            <br />
                          </template>
                          <span>Total: @{{factura.pagado | formatoMoneda}}</span><br />
                        </td>
                        <td>@{{factura.pendiente | formatoMoneda}}</td>
                        <td>@{{factura.vencimiento_formated}}</td>
                        <td>@{{factura.emision_formated}}</td>
                        <td class="text-right">
                          <a class="btn btn-xs btn-warning" title="PDF" :href="factura.pdf"
                            :download="'factura '+factura.documento+'.pdf'">
                            <i class="far fa-file-pdf"></i>
                          </a>
                          <a class="btn btn-xs btn-default" title="XML" :href="factura.xml"
                            :download="'factura '+factura.documento+'.xml'">
                            <i class="far fa-file-excel"></i>
                          </a>
                          <button v-if="!factura.pagada" class="btn btn-xs btn-success" title="Pagar"
                            @click="agregarPago(factura)">
                            <i class="fas fa-hand-holding-usd"></i>
                          </button>
                          <button v-if="!factura.pagada" class="btn btn-xs btn-success" title="Editar"
                            @click="editarFactura(factura, index)">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button v-if="!factura.pagada" class="btn btn-xs btn-danger" title="Remover"
                            @click="removerFactura(factura, index)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <form  class="" @submit.prevent="agregarFactura()">
              <div class="row">
                <div class="col-sm-12">
                  <h4>Nueva Factura</h4>
                  <hr />
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Numero Documento</label>
                    <input type="text" name="documento" class="form-control"
                      v-model="factura.documento" required
                     />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Porcentaje Factura</label>
                    <input type="number" name="porcentaje" class="form-control"
                      min="0.01" max="100" step="0.01" v-model="factura.porcentaje"
                      @change="fijarMontoFactura()"
                     />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Monto Factura</label>
                    <input type="number" name="monto" class="form-control"
                      min="0.01" step="0.01" v-model="factura.monto" required
                     />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Fecha Vencimiento</label>
                    <br />
                    <dropdown style="width:100%;">
                      <div class="input-group" >
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" name="vencimiento"
                          v-model="factura.vencimiento" placeholder="DD/MM/YYYY"
                          readonly
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser" v-model="factura.vencimiento"/>
                        </li>
                      </template>
                    </dropdown>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Fecha Emisión</label>
                    <br />
                    <dropdown style="width:100%;">
                      <div class="input-group" >
                        <div class="input-group-btn">
                          <btn class="dropdown-toggle" style="background-color:#fff;">
                            <i class="fas fa-calendar"></i>
                          </btn>
                        </div>
                        <input class="form-control" type="text" name="emision"
                          v-model="factura.emision" placeholder="DD/MM/YYYY"
                          readonly
                        />
                      </div>
                      <template slot="dropdown">
                        <li>
                          <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                          format="dd/MM/yyyy" :date-parser="dateParser" v-model="factura.emision"/>
                        </li>
                      </template>
                    </dropdown>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Factura PDF</label>
                    <div class="file-loading">
                      <input id="pdf" name="pdf" type="file" ref="pdf" @change="fijarArchivo('pdf')" required />
                    </div>
                    <div id="pdf-file-errors"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Factura XML</label>
                    <div class="file-loading">
                      <input id="xml" name="xml" type="file" ref="xml" @change="fijarArchivo('xml')" required />
                    </div>
                    <div id="xml-file-errors"></div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <div class="form-group" style="margin-top:25px;">
                    <button type="submit" class="btn btn-primary">
                      Agregar Factura
                    </button>
                  </div>
                </div>
              </div>
            </form>

            <div class="row">
              <div class="col-md-12 text-right">
                <a href="{{ route('cuentas-cobrar.index') }}" class="btn btn-default" style="color:#000; background-color:#B3B3B3">
                  Regresar
                </a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Aceptar Modal -->
    <modal v-model="openPagar" :title="'Pagar Factura '+pago.documento" :footer="false">
      <form class="" @submit.prevent="pagarFactura()">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Fecha de Pago</label>
              <br />
              <dropdown style="width:100%;">
                <div class="input-group" >
                  <div class="input-group-btn">
                    <btn class="dropdown-toggle" style="background-color:#fff;">
                      <i class="fas fa-calendar"></i>
                    </btn>
                  </div>
                  <input class="form-control" type="text" name="fecha"
                  v-model="pago.fecha" placeholder="DD/MM/YYYY"
                  readonly
                  />
                </div>
                <template slot="dropdown">
                  <li>
                    <date-picker :locale="locale" :today-btn="false" :clear-btn="false"
                    format="dd/MM/yyyy" :date-parser="dateParser" v-model="pago.fecha"/>
                  </li>
                </template>
              </dropdown>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Monto Pago</label>
              <input type="number" name="monto" class="form-control"
              min="0.01" step="0.01" v-model="pago.monto" required
              />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Referencia</label>
              <input type="text" name="referencia" class="form-control" v-model="pago.referencia"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Comprobante de Pago</label>
              <div class="file-loading">
                <input id="comprobante" name="comprobante" type="file" ref="comprobante"
                @change="fijarArchivo('comprobante')" />
              </div>
              <div id="comprobante-file-errors"></div>
            </div>
          </div>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-primary">Aceptar</button>
          <button type="button" class="btn btn-default" @click="cancelarPago()">
            Cancelar
          </button>
        </div>
      </form>
    </modal>
  <!-- /.Aceptar Modal -->

  </section>
  <!-- /.content -->

@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script type="text/javascript">
const app = new Vue({
  el: '#content',
  data: {
    locale: localeES,
    cuenta: {!! json_encode($cuenta) !!},
    factura: {
      id:'',
      cuenta_id: {{$cuenta->id}},
      documento: '',
      porcentaje: '',
      monto: '',
      vencimiento: '',
      emision: '',
      pdf: '',
      xml: ''
    },
    pago: {
      factura_id: 0,
      documento: "",
      maximo: "",
      fecha: "",
      monto: "",
      referencia: "",
      comprobante: ""
    },
    openPagar: false,
    cargando: false
  },
  filters:{
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$", 2);
    },
  },
  mounted(){
    $("#pdf").fileinput({
      language: 'es',
      showPreview: false,
      showUpload: false,
      showRemove: false,
      allowedFileExtensions: ["pdf"],
      elErrorContainer: '#pdf-file-errors',
    });
    $("#xml").fileinput({
      language: 'es',
      showPreview: false,
      showUpload: false,
      showRemove: false,
      allowedFileExtensions: ["xml"],
      elErrorContainer: '#xml-file-errors',
    });
    $("#comprobante").fileinput({
      language: 'es',
      showPreview: false,
      showUpload: false,
      showRemove: false,
      browseLabel: "",
      allowedFileExtensions: ["jpg","jpeg",'png',"pdf"],
      elErrorContainer: '#comprobante-file-errors',
    });
  },
  methods: {
    dateParser(value){
			return moment(value, 'DD/MM/YYYY').toDate().getTime();
		},
    fijarArchivo(campo){
      if(campo=='comprobante') this.pago.comprobante = this.$refs['comprobante'].files[0];
      else this.factura[campo] = this.$refs[campo].files[0];
    },
    fijarMontoFactura(){
      this.factura.monto = (this.cuenta.total * (this.factura.porcentaje/100)).toFixed(2);;
    },
    agregarFactura(){
      var formData = objectToFormData(this.factura, {indices:true});

      this.cargando = true;
      axios.post('/cuentas-cobrar/{{$cuenta->id}}/facturar', formData, {
        headers: { 'Content-Type': 'multipart/form-data'}
      })
      .then(({data}) => {
        this.cuenta.facturas.push(data.factura);
        this.cuenta.facturado = data.cuenta.facturado;
        this.cuenta.total = data.cuenta.total;
        this.cuenta.pendiente = data.cuenta.pendiente;
        this.factura = {
          cuenta_id: {{$cuenta->id}},
          documento: '',
          monto: '',
          vencimiento: '',
          pdf: '',
          xml: ''
        };
        $("#pdf").fileinput('clear');
        $("#xml").fileinput('clear');

        this.cargando = false;
        swal({
          title: "Factura Guardada",
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
    },//fin guardar
    editarFactura(factura, index){
      
      this.cuenta.facturas.splice(index, 1);      
      this.factura = factura; 
      this.factura.vencimiento = factura.vencimiento_formated;
      this.factura.emision = factura.emision_formated;     
      
    },
    removerFactura(factura, index){
      this.cuenta.facturas.splice(index, 1);
      if(factura.id) {
        var formData = objectToFormData(factura, {indices:true});

        this.cargando = true;
        axios.post('/cuentas-cobrar/{{$cuenta->id}}/deletefactura', formData, {
          headers: { 'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          this.cuenta.facturado = data.cuenta.facturado;
          this.factura = {
            cuenta_id: {{$cuenta->id}},
            documento: '',
            monto: '',
            vencimiento: '',
            pdf: '',
            xml: ''
          };
          $("#pdf").fileinput('clear');
          $("#xml").fileinput('clear');

          this.cargando = false;
          swal({
            title: "Factura Eliminada",
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


      }
    },
    agregarPago(factura){
      this.pago.factura_id = factura.id;
      this.pago.documento = factura.documento;
      this.pago.monto = factura.pendiente;
      this.pago.maximo = factura.pendiente;
      this.openPagar = true;
    },
    cancelarPago(){
      this.pago = {
        factura_id: 0,
        documento: "",
        maximo: "",
        fecha: "",
        monto: "",
        referencia: "",
        comprobante: ""
      };
      this.openPagar = false;
    },
    pagarFactura(){
      var formData = objectToFormData(this.pago, {indices:true});

      this.cargando = true;
      axios.post('/cuentas-cobrar/{{$cuenta->id}}/pagar', formData, {
        headers: { 'Content-Type': 'multipart/form-data'}
      })
      .then(({data}) => {
        this.cuenta.facturas.find(function(factura){
          if(data.pago.factura_id == factura.id){
            factura.pagado+= data.pago.monto;
            factura.pendiente-= data.pago.monto;
            if(factura.pendiente<=0) factura.pagada = true;
            factura.pagos.push(data.pago);

            this.cuenta.pagado+= data.pago.monto;
            this.cuenta.pendiente-= data.pago.monto;
            if(this.cuenta.pendiente<=0) this.cuenta.pagada = true;

            return true;
          }
        }, this);

        this.pago = {
          factura_id: 0,
          documento: "",
          maximo: "",
          fecha: "",
          monto: "",
          referencia: "",
          comprobante: ""
        };
        $("#comprobante").fileinput('clear');
        this.openPagar = false;
        this.cargando = false;
        swal({
          title: "Pago Realizado",
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
    },//fin pagarFactura
  }
});
</script>
@stop
