@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Proveedor | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Editar Proveedor</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <tabs v-model="activeTab">
      <tab title="Datos">
        <form class="" @submit.prevent="guardar()">
          <div class="row">
            <div class="col-lg-12">
              <div class="panel ">
                <div class="panel-heading">
                  <h3 class="panel-title">Datos Generales</h3>
                </div>
                <div class="panel-body">
                  <div class="row form-group">
                    <div class="col-md-6">
                      <label class="control-label">Tipo</label>
                      <select name="tipo" v-model="proveedor.tipo_id" class="form-control">
                        @foreach($tipos as $tipo)
                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-8">
                      <label class="control-label">Nombre Empresa</label>
                      <input type="text" class="form-control" name="empresa" v-model="proveedor.empresa" required />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Numero Cliente</label>
                      <input type="text" class="form-control" name="numero_cliente" v-model="proveedor.numero_cliente" />
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-8">
                      <label class="control-label">Razon Social</label>
                      <input type="text" class="form-control" name="razon_social" v-model="proveedor.razon_social" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">{{ ($proveedor->nacional)?'RFC':'TAX ID NO' }}:</label>
                      <input type="text" class="form-control" name="identidad_fiscal" v-model="proveedor.identidad_fiscal" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="panel ">
                <div class="panel-heading">
                  <h3 class="panel-title">Dirección</h3>
                </div>
                <div class="panel-body">
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Calle</label>
                      <input type="text" class="form-control" name="calle" v-model="proveedor.calle" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Numero</label>
                      <input type="text" class="form-control" name="numero" v-model="proveedor.numero" />
                    </div>
                  </div>
                  @if($proveedor->nacional)
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Colonia</label>
                      <input type="text" class="form-control" name="colonia" v-model="proveedor.colonia" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Delegación</label>
                      <input type="text" class="form-control" name="delegacion" v-model="proveedor.delegacion" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">C. Postal</label>
                      <input type="text" class="form-control cp" name="colonia" v-model="proveedor.cp" />
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Ciudad</label>
                      <input type="text" class="form-control municipio" name="ciudad" v-model="proveedor.ciudad" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Estado</label>
                      <input type="text" class="form-control estado" name="estado" v-model="proveedor.estado" />
                    </div>
                    {{-- <div class="col-md-4">
                      <label class="control-label">Pais</label>
                      <input type="text" class="form-control" name="pais" v-model="proveedor.pais" />
                    </div> --}}
                  </div>
                  @else
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Ciudad</label>
                      <input type="text" class="form-control" name="ciudad" v-model="proveedor.ciudad" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Estado</label>
                      <input type="text" class="form-control" name="estado" v-model="proveedor.estado" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">ZIP Code</label>
                      <input type="text" class="form-control" name="colonia" v-model="proveedor.cp" />
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Pais</label>
                      <input type="text" class="form-control" name="pais" v-model="proveedor.pais" />
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Otros</h3>
            </div>
            <div class="panel-body">
              <div class="row form-group">
                <div class="col-md-12">
                  <label class="control-label">Pagina Web</label>
                  <input type="text" class="form-control" name="pagina_web" v-model="proveedor.pagina_web" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-12">
                  <label class="control-label">Datos Adicionales</label>
                  <input type="text" class="form-control" name="adicionales" v-model="proveedor.adicionales" />
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="panel ">
                <div class="panel-heading">
                  <h3 class="panel-title">Datos Bancarios</h3>
                </div>
                <div class="panel-body">
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Moneda</label>
                      <select class="form-control" name="moneda" v-model="proveedor.moneda">
                        <option value="Pesos">Pesos</option>
                        <option value="Dolares">Dolares</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Limite Credito</label>
                      <input type="number" min="0" step="0.01" class="form-control" name="limite_credito" v-model="proveedor.limite_credito" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Dias Credito</label>
                      <input type="number" min="0" step="1" class="form-control" name="dias_credito" v-model="proveedor.dias_credito" />
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Banco</label>
                      <input type="text" class="form-control" name="banco" v-model="proveedor.banco" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Número de Cuenta</label>
                      <input type="text" class="form-control" name="numero_cuenta" v-model="proveedor.numero_cuenta" />
                    </div>
                    {{-- <div class="col-md-4">
                      <label class="control-label">No Cuenta (Intercorp/Cliente)</label>
                      <input type="text" class="form-control" name="cuenta_interna" v-model="proveedor.cuenta_interna" />
                    </div> --}}
                    @if($proveedor->nacional)
                    <div class="col-md-4">
                      <label class="control-label">Clave Interbancaria</label>
                      <input type="text" class="form-control" name="clave_interbancaria" v-model="proveedor.clave_interbancaria" />
                    </div>
                    @else
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">SWIF</label>
                        <input type="text" class="form-control" name="swift" v-model="proveedor.swift" />
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">ABA</label>
                        <input type="text" class="form-control" name="aba" v-model="proveedor.aba" />
                      </div>
                    </div>
                    @endif
                  </div>
                  @if($proveedor->nacional)
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Colonia</label>
                      <input type="text" class="form-control" name="banco_colonia" v-model="proveedor.banco_colonia" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Delegación</label>
                      <input type="text" class="form-control" name="banco_delegacion" v-model="proveedor.banco_delegacion" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">C. Postal</label>
                      <input type="text" class="form-control cp1" name="banco_cp" v-model="proveedor.banco_cp" />
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Ciudad</label>
                      <input type="text" class="form-control municipio1" name="banco_ciudad" v-model="proveedor.banco_ciudad" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Estado</label>
                      <input type="text" class="form-control estado1" name="banco_estado" v-model="proveedor.banco_estado" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Pais</label>
                      <input type="text" class="form-control" name="banco_pais" v-model="proveedor.banco_pais" />
                    </div>
                  </div>
                  @else
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Ciudad</label>
                      <input type="text" class="form-control" name="banco_ciudad" v-model="proveedor.banco_ciudad" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Estado</label>
                      <input type="text" class="form-control" name="banco_estado" v-model="proveedor.banco_estado" />
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">ZIP Code</label>
                      <input type="text" class="form-control" name="banco_cp" v-model="proveedor.banco_cp" />
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-md-4">
                      <label class="control-label">Pais</label>
                      <input type="text" class="form-control" name="banco_pais" v-model="proveedor.banco_pais" />
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
                  
          <div class="row">
            <div class="col-md-12 text-center">
              <a class="btn btn-default" href="{{route('proveedores.index')}}" style="margin-right:20px;">
                Regresar
              </a>
              <button type="submit" class="btn btn-success" :disabled="cargando">
                <i class="fas fa-save"></i>
                Actualizar Proveedor
              </button>
            </div>
          </div>
        </form>
      </tab>

      <tab title="Contactos">
        <div class="row">
          <div class="col-lg-12">
            <div class="panel ">
              <div class="panel-heading">
                <h3 class="panel-title">Contactos del Proveedor</h3>
              </div>
              <div class="panel-body">
                <form class="" @submit.prevent="agregarContacto()">
                  <div class="row form-group">
                    <div class="col-md-6">
                      <label class="control-label">Nombre</label>
                      <input type="text" class="form-control" name="nombre" v-model="contacto.nombre" required />
                    </div>
                    <div class="col-md-6">
                      <label class="control-label">Cargo</label>
                      <input type="text" class="form-control" name="cargo" v-model="contacto.cargo" required />
                    </div>
                  </div>
                  <contacto-emails :emails="contacto.emails" 
                    :contacto_id="(contacto.id)?contacto.id:0"
                    contacto_type="ProveedorContacto">
                  </contacto-emails>
                  <contacto-telefonos :telefonos="contacto.telefonos" 
                    :contacto_id="(contacto.id)?contacto.id:0"
                    contacto_type="ProveedorContacto"
                    :nacional="proveedor.nacional">
                  </contacto-telefonos>
                  <div class="row form-group">
                    <div class="col-md-6">
                      <label class="control-label">Fax</label>
                      <input type="text" class="form-control" name="fax" v-model="contacto.fax" required/>
                    </div>
                  </div>
                  <div class="row" style="margin-top:40px;">
                    <div class="col-md-12 text-center">
                      <button v-if="contacto.id" type="submit" class="btn btn-success" :disabled="cargando">
                        Actualizar Contacto
                      </button>
                      <button v-else type="submit" class="btn btn-primary" :disabled="cargando">
                        Guardar Contacto
                      </button>
                    </div>
                  </div>
                </form>
                <hr />
                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Emails</th>
                            <th>Teléfonos</th>
                            <th>Fax</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(contacto, index) in proveedor.contactos">
                            <td>@{{contacto.nombre}}</td>
                            <td>@{{contacto.cargo}}</td>
                            <td>
                              <div v-for="(email, i) in contacto.emails">
                                @{{i+1}}.- @{{email.tipo}}: @{{email.email}}
                              </div>
                            </td>
                            <td>
                              <div v-for="(telefono, i) in contacto.telefonos">
                                @{{i+1}}.- @{{telefono.tipo}}: @{{telefono.telefono}} @{{telefono.extencion}}
                              </div>
                            </td>
                            <td>
                              @{{contacto.fax}}
                            </td>
                            <td class="text-right">
                              <button class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                                @click="editarContacto(contacto, index)">
                                <i class="fas fa-pencil-alt"></i>
                              </button>
                              <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                                @click="borrarContacto(contacto, index)">
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
          </div>
        </div>
      </tab>
    </tabs>
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
      activeTab: {{$tab}},
      proveedor: {!! json_encode($proveedor) !!},
      contacto: {
        tipo: 'proveedor',
        proveedor_id: {{$proveedor->id}},
        nombre: '',
        cargo: '',
        emails: [],
        telefonos: [],
        fax : ''
      },
      cargando: false,
    },
    methods: {
      agregarContacto(){
        if(this.contacto.id) this.actualizarContacto();
        else this.guardarContacto();
      },
      guardarContacto(){
        this.cargando = true;
        axios.post('/contactos', this.contacto)
        .then(({data}) => {
          this.proveedor.contactos.push(data.contacto);
          this.contacto = {
            tipo: 'proveedor',
            proveedor_id: {{$proveedor->id}},
            nombre: '',
            cargo: '',
            emails: [],
            telefonos: [],
            fax : ''
          };
          this.cargando = false;
          swal({
            title: "Contacto Guardado",
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
      }, //guardarContacto
      actualizarContacto(){
        this.cargando = true;
        axios.put('/contactos/'+this.contacto.id, 
        {'tipo':'proveedor',nombre:this.contacto.nombre,cargo:this.contacto.cargo})
        .then(({data}) => {
          this.proveedor.contactos.push(this.contacto);
          this.contacto = {
            tipo: 'proveedor',
            proveedor_id: {{$proveedor->id}},
            nombre: '',
            cargo: '',
            emails: [],
            telefonos: [],
            fax : ''
          };
          this.cargando = false;
          swal({
            title: "Contacto Actualizado",
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
      editarContacto(contacto, index){
        this.contacto = contacto;
        this.proveedor.contactos.splice(index, 1);
      },
      borrarContacto(contacto, index){
        if(contacto.id == undefined){
          this.proveedor.contactos.splice(index, 1);
          return true;
        }

        this.cargando = true;
        axios.delete('/contactos/'+contacto.id, {params: {tipo:'proveedor'}})
        .then(({data}) => {
          this.proveedor.contactos.splice(index, 1);
          this.cargando = false;
          swal({
            title: "Contacto Borrado",
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
      guardar(){
        this.cargando = true;
        axios.put('/proveedores/{{$proveedor->id}}', this.proveedor)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Proveedor Actualizado",
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

$('.cp').on('keyup', function (e) {
  var cp = $(this).val();
  if(cp.length >= 5) {
    $.get('http://sepomex.789.mx/' + cp, function (data) {
      if(data.estados.length >= 1) {
        $('.estado').val(data.estados[0]);
      }
      if(data.municipios.length >= 1) {
        $('.municipio').val(data.municipios[0]);
      }
    });
  }
});

$('.cp1').on('keyup', function (e) {
  var cp = $(this).val();
  if(cp.length >= 5) {
    $.get('http://sepomex.789.mx/' + cp, function (data) {
      if(data.estados.length >= 1) {
        $('.estado1').val(data.estados[0]);
      }
      if(data.municipios.length >= 1) {
        $('.municipio1').val(data.municipios[0]);
      }
    });
  }
});
</script>
@stop
