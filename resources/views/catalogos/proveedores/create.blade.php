@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Proveedor | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Proveedors</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nuevo Proveedor {{ ($nacional)?'Nacional':'Internacional' }}</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Nombre Empresa</label>
                    <input type="text" class="form-control" name="empresa" v-model="proveedor.empresa" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Razon Social</label>
                    <input type="text" class="form-control" name="razon_social" v-model="proveedor.razon_social" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">{{ ($nacional)?'RFC':'EIN' }}:</label>
                    <input type="text" class="form-control" name="identidad_fiscal" v-model="proveedor.identidad_fiscal" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Telefono</label>
                    <vue-phone-number-input
                      :default-country-code="proveedor.codigo_pais"
                      no-use-browser-locale size="sm" :translations="translations"
                      no-validator-state v-model="proveedor.telefono"
                      @update="fijarCodigoPaisProveedor"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control" name="email" v-model="proveedor.email" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Banco</label>
                    <input type="text" class="form-control" name="email" v-model="proveedor.banco" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Número de Cuenta</label>
                    <input type="text" class="form-control" name="email" v-model="proveedor.numero_cuenta" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Clave Interbancaria</label>
                    <input type="text" class="form-control" name="calle" v-model="proveedor.clave_interbancaria" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Moneda</label>
                    <select class="form-control" name="moneda" v-model="proveedor.moneda">
                      <option value="Pesos">Pesos</option>
                      <option value="Dolares">Dolares</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Dias Credito</label>
                    <input type="number" min="0" step="1" class="form-control" name="dias_credito" v-model="proveedor.dias_credito" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Calle</label>
                    <input type="text" class="form-control" name="calle" v-model="proveedor.calle" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero</label>
                    <input type="text" class="form-control" name="numero" v-model="proveedor.numero" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Colonia</label>
                    <input type="text" class="form-control" name="colonia" v-model="proveedor.colonia" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">C. Postal</label>
                    <input type="text" class="form-control" name="cp" v-model="proveedor.cp" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Ciudad</label>
                    <input type="text" class="form-control" name="ciudad" v-model="proveedor.ciudad" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Estado</label>
                    <input type="text" class="form-control" name="estado" v-model="proveedor.estado" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Proveedor
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Contactos del Proveedor</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Teléfono 2</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(contacto, index) in proveedor.contactos">
                        <td>@{{contacto.nombre}}</td>
                        <td>@{{contacto.cargo}}</td>
                        <td>@{{contacto.email}}</td>
                        <td>@{{contacto.tipo_telefono}} @{{contacto.telefono}} Ext. @{{contacto.extencion_telefono}}</td>
                        <td>@{{contacto.tipo_telefono2}} @{{contacto.telefono2}} Ext. @{{contacto.extencion_telefono2}}</td>
                        <td class="text-right">
                          <button class="btn btn-success" data-toggle="tooltip" title="Editar"
                            @click="editarContacto(contacto, index)">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                            @click="borrarContacto(index)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <hr />
            <form class="" @submit.prevent="agregarContacto()">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="contacto.nombre" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Cargo</label>
                    <input type="text" class="form-control" name="cargo" v-model="contacto.cargo" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control" name="email" v-model="contacto.email" />
                  </div>
                </div>
              </div>
              <div class="row">
                <h4>Teléfono</h4>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero</label>
                    <vue-phone-number-input
                      :default-country-code="contacto.codigo_pais"
                      size="sm" :translations="translations"
                      no-use-browser-locale no-validator-state
                      v-model="contacto.telefono"
                      @update="fijarCodigoPaisContacto"
                    />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Extención</label>
                    <input type="text" class="form-control" v-model="contacto.extencion_telefono" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" v-model="contacto.tipo_telefono">
                      <option value="Oficina">Oficina</option>
                      <option value="Celular">Celular</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <h4>Teléfono 2</h4>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero</label>
                    <vue-phone-number-input
                      :default-country-code="contacto.codigo_pais2"
                      size="sm" :translations="translations"
                      no-use-browser-locale no-validator-state
                      v-model="contacto.telefono2"
                      @update="fijarCodigoPais2Contacto"
                    />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Extención</label>
                    <input type="text" class="form-control" v-model="contacto.extencion_telefono2" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" v-model="contacto.tipo_telefono2">
                      <option value="Oficina">Oficina</option>
                      <option value="Celular">Celular</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Contacto
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')

<script>
const app = new Vue({
    el: '#content',
    data: {
      translations: translationsES,
      proveedor: {
        empresa: '',
        nacional: {{ ($nacional)?"true":"false" }},
        razon_social: '',
        identificacion_fiscal: '{{ ($nacional)?"RFC":"EIN" }}',
        identidad_fiscal: '',
        telefono: '',
        codigo_pais: '',
        email: '',
        banco: '',
        numero_cuenta: '',
        clave_interbancaria: '',
        moneda: '',
        dias_credito: '',
        calle: '',
        numero: '',
        colonia: '',
        cp: '',
        ciudad: '',
        estado: '',
        contactos: []
      },
      contacto: {
        nombre: '',
        cargo: '',
        telefono: '',
        telefono2: '',
        codigo_pais: '',
        codigo_pais2: '',
        extencion_telefono: '',
        extencion_telefono2: '',
        tipo_telefono: '',
        tipo_telefono2: '',
        email: '',
      },
      cargando: false,
    },
    methods: {
      fijarCodigoPaisProveedor(payload) {
        this.proveedor.codigo_pais = payload.countryCode;
      },
      fijarCodigoPaisContacto(payload) {
        this.contacto.codigo_pais = payload.countryCode;
      },
      fijarCodigoPais2Contacto(payload) {
        this.contacto.codigo_pais2 = payload.countryCode;
      },
      agregarContacto(){
        if(this.contacto.nombre.trim()=="" || this.contacto.cargo.trim()==""){
          swal({
            title: "Atención",
            text: "El nombre y cargo del contacto son obligatorios",
            type: "warning"
          });
          return false;
        }

        this.proveedor.contactos.push(this.contacto);
        this.contacto = {
          nombre: '',
          cargo: '',
          telefono: '',
          telefono2: '',
          extencion_telefono: '',
          extencion_telefono2: '',
          tipo_telefono: '',
          tipo_telefono2: '',
          email: '',
        };
      },
      editarContacto(contacto, index){
        this.contacto = contacto;
        this.proveedor.contactos.splice(index, 1);
      },
      borrarContacto(index){
        this.proveedor.contactos.splice(index, 1);
      },
      guardar(){
        this.cargando = true;
        axios.post('/proveedores', this.proveedor)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Proveedor Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/proveedores";
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
      },//fin cargarPresupuesto
    }
});
</script>
@stop
