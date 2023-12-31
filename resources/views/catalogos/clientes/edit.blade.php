@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Cliente | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1>Clientes</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <tabs v-model="activeTab">
            <tab title="Datos">
                <form @submit.prevent=guardar()>
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="panel">
                                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                                    <h3 class="panel-title">Datos Generales</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Usuario Default</label>
                                            <select class="form-control" name="usuario_id" v-model='cliente.usuario_id' required>
                                                @foreach($usuarios as $id => $nombre)
                                                    <option value="{{$id}}">{{$nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Usuario(s) adicional</label>
                                                <select2multags :options="user.userOptions" v-model="user.users" style="width:100%;">
                                                </select2multags>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Tipo</label>
                                            <select class="form-control" name="tipo_id" v-model='cliente.tipo_id'
                                                    required>
                                                @foreach($tipos as $tipo)
                                                    <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Diseñador</label>
                                            <select class="form-control" name="vendedor_id" v-model='cliente.vendedor_id' required>
                                                @foreach($vendedores as $id => $nombre)
                                                    <option value="{{$id}}">{{$nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Categoría de Cliente</label>
                                            <select class="form-control" name="categoria_cliente_id"  required v-model=cliente.categoria_cliente_id required>
                                                @foreach($categorias as $categoria)
                                                <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Preferencias y necesidades</label>
                                            <input type="text" class="form-control" name="preferencias" v-model="cliente.preferencias">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Nombre</label>
                                            <input type="text" class="form-control" name="nombre"
                                                   v-model="cliente.nombre" required/>
                                        </div>
                                         <div class="col-md-6">
                                            <label class="control-label">Razon Social</label>
                                            <input type="text" class="form-control" name="razon_social"
                                                   v-model="cliente.razon_social"/>
                                        </div>
                                    </div>
                                    <!--div class="row form-group">
                                        {{--@if($cliente->nacional)
                                            <div class="col-md-4">
                                                <label class="control-label">RFC</label>
                                                <input type="text" class="form-control" name="rfc"
                                                       v-model="cliente.rfc"/>
                                            </div>
                                        @endif--}}
                                       
                                    </div-->
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Telefono</label>
                                            <input type="text" class="form-control" name="telefono" v-model="cliente.telefono"/>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Email</label>
                                            <input type="text" class="form-control" name="email" v-model="cliente.email"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel ">
                                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                                    <h3 class="panel-title">Dirección</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row form-group">
                                        <div class="{{($cliente->nacional) ? 'col-md-4' : 'col-md-12'}}">
                                            <label class="control-label">{{($cliente->nacional) ? 'Calle' : 'Dirección'}}</label>
                                            <input type="text" class="form-control" name="calle" v-model="cliente.calle"
                                                   maxlength="191"/>
                                        </div>
                                        @if($cliente->nacional)
                                            <div class="col-md-4">
                                                <label class="control-label">Numero Exterior</label>
                                                <input type="text" class="form-control" name="nexterior"
                                                       v-model="cliente.nexterior"/>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Numero Interior</label>
                                                <input type="text" class="form-control" name="ninterior"
                                                       v-model="cliente.ninterior"/>
                                            </div>
                                        @endif
                                        {{--</div>
                                        <div class="row form-group">--}}
                                        <div class="col-md-4">
                                            <label class="control-label">Colonia</label>
                                            <input type="text" class="form-control" name="colonia"
                                                   v-model="cliente.colonia"/>
                                        </div>
                                        @if($cliente->nacional)
                                            <div class="col-md-4">
                                                <label class="control-label">Delegacion</label>
                                                <input type="text" class="form-control" name="delagacion"
                                                       v-model="cliente.delegacion"/>
                                            </div>
                                        @endif
                                        <div class="col-md-4">
                                            <label class="control-label">C. Postal</label>
                                            <input type="text" class="form-control cp" name="cp" v-model="cliente.cp"/>
                                        </div>
                                        {{--</div>
                                        <div class="row form-group">--}}
                                        <div class="col-md-4">
                                            <label class="control-label">Ciudad</label>
                                            <input type="text" class="form-control municipio" name="ciudad"
                                                   v-model="cliente.ciudad"/>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Estado</label>
                                            <input type="text" class="form-control estado" name="estado"
                                                   v-model="cliente.estado"/>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">País</label>
                                            <input type="text" class="form-control" name="pais" v-model="cliente.pais"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel">
                                <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                                    <h3 class="panel-title">Otros</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label class="control-label">Pagina Web</label>
                                            <input type="text" class="form-control" name="pagina_web"
                                                   v-model="cliente.pagina_web"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label class="control-label">Datos Adicionales</label>
                                            <input type="text" class="form-control" name="adicionales"
                                                   v-model="cliente.adicionales"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a class="btn btn-default" href="{{route('clientes.index')}}" style="margin-right: 20px; color:#000; background-color:#B3B3B3;">
                                Regresar
                            </a>
                            <button type="submit" class="btn btn-dark" :disabled="cargando" style="background-color:#12160F; color:#B68911;">
                                <i class="fas fa-save"></i>
                                Actualizar Cliente
                            </button>
                        </div>
                    </div>
                </form>
            </tab>

            <tab title="Contactos">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel ">
                            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                                <h3 class="panel-title">Contactos del Cliente</h3>
                            </div>
                            <div class="panel-body">
                                <form class="" @submit.prevent="agregarContacto()">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Nombre</label>
                                            <input type="text" class="form-control" name="nombre"
                                                   v-model="contacto.nombre" required/>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Cargo</label>
                                            <input type="text" class="form-control" name="cargo"
                                                   v-model="contacto.cargo" required/>
                                        </div>
                                    </div>
                                    <contacto-emails :emails="contacto.emails"
                                                     :contacto_id="(contacto.id)?contacto.id:0"
                                                     contacto_type="ClienteContacto">
                                    </contacto-emails>
                                    <contacto-telefonos :telefonos="contacto.telefonos"
                                                        :contacto_id="(contacto.id)?contacto.id:0"
                                                        contacto_type="ClienteContacto"
                                                        :nacional="cliente.nacional">
                                    </contacto-telefonos>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Fax</label>
                                            <input type="text" class="form-control" name="fax" v-model="contacto.fax">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:40px;">
                                        <div class="col-md-12 text-center">
                                            <button v-if="contacto.id" type="submit" class="btn btn-success"
                                                    :disabled="cargando">
                                                Actualizar Contacto
                                            </button>
                                            <button v-else type="submit" class="btn btn-primary" :disabled="cargando">
                                                Guardar Contacto
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <hr/>
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
                                                <tr v-for="(contacto, index) in cliente.contactos">
                                                    <td>@{{contacto.nombre}}</td>
                                                    <td>@{{contacto.cargo}}</td>
                                                    <td>
                                                        <div v-for="(email, i) in contacto.emails">
                                                            @{{i+1}}.- @{{email.tipo}}: @{{email.email}}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div v-for="(telefono, i) in contacto.telefonos">
                                                            @{{i+1}}.- @{{telefono.tipo}}: @{{telefono.telefono}}
                                                            @{{telefono.extencion}}
                                                        </div>
                                                    </td>
                                                    <td>@{{contacto.fax}}</td>
                                                    <td class="text-right">
                                                        <button class="btn btn-xs btn-success" data-toggle="tooltip"
                                                                title="Editar"
                                                                @click="editarContacto(contacto, index)" style="background: #fece58 !important;">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <button class="btn btn-xs btn-danger" data-toggle="tooltip"
                                                                title="Borrar"
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


            <tab title="Datos Facturacion">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel ">
                            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                                <h3 class="panel-title">Datos de Facturacion</h3>
                            </div>
                            <div class="panel-body">
                                <form class="" @submit.prevent="agregarDatoFacturacion()">

                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">RFC</label>
                                            <input type="text" name="rfc" required
                                                   class="form-control text-uppercase"
                                                   v-model="datoFacturacion.rfc"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label class="control-label">Razon Social</label>
                                            <input type="text" name="razon_social" required class="form-control"
                                                   v-model="datoFacturacion.razon_social"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label class="control-label">Calle</label>
                                            <input type="text" name="calle" class="form-control"
                                                   v-model="datoFacturacion.calle"/>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">No. Ext.</label>
                                            <input type="text" name="nexterior" class="form-control"
                                                   v-model="datoFacturacion.nexterior"/>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">No. Int.</label>
                                            <input type="text" name="ninterior" class="form-control"
                                                   v-model="datoFacturacion.ninterior"/>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Colonia {{($cliente->nacional) ? '' : '(opcional)'}}</label>
                                            <input type="text" name="colonia" class="form-control"
                                                   v-model="datoFacturacion.colonia"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <label class="control-label">CP</label>
                                            <input type="text" name="cp" class="form-control"
                                                   v-model="datoFacturacion.cp"/>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Ciudad</label>
                                            <input type="text" name="ciudad" class="form-control"
                                                   v-model="datoFacturacion.ciudad"/>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Estado</label>
                                            <input type="text" name="estado" class="form-control"
                                                   v-model="datoFacturacion.estado"/>
                                        </div>
                                    </div>
                                    <!-- select new-->
                                    <div class="row">
                                        <div class="col-md-4">
                                           <label>Regimen</label> 
                                           {!! Form::select('cat_regimen_id',$catregimen,null,['class'=>'form-control','id'=>'cat_regimen_id_edit','placeholder'=>'Seleccione un Regimen']) !!}
                                        </div>
                                        <div class="col-md-4">
                                           <label>Uso de CFDI</label> 
                                            {!! Form::select('cat_cfdi_id',$catcfdi,null,['class'=>'form-control','id'=>'cat_cfdi_id','placeholder'=>'Seleccione un CFDI']) !!}
                                        </div>
                                        <div class="col-md-4">
                                           <label>Forma de Pago</label> 
                                           {!! Form::select('cat_forma_pago_id',$catformapago,null,['class'=>'form-control','id'=>'cat_forma_pago_id','placeholder'=>'Seleccione una Forma de Pago']) !!}   
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:40px;">
                                        <div class="col-md-12 text-center">
                                            <button v-if="datoFacturacion.id" type="submit" class="btn btn-success"
                                                    :disabled="cargando">
                                                Actualizar Dato de Facturacion
                                            </button>
                                            <button v-else type="submit" class="btn btn-primary" :disabled="cargando">
                                                Guardar Dato de Facturacion
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>RFC</th>
                                                    <th>Razon social</th>
                                                    <th>Direccion</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(datoFacturacion, index) in cliente.datos_facturacion">
                                                    <td>@{{datoFacturacion.rfc}}</td>
                                                    <td>@{{datoFacturacion.razon_social}}</td>
                                                    <td>
                                                        @{{datoFacturacion.direccion}}
                                                    </td>

                                                    <td class="text-right">
                                                        <button class="btn btn-xs btn-success" data-toggle="tooltip"
                                                                title="Editar"
                                                                @click="editarDatoFacturacion(datoFacturacion, index)" style="background: #fece58 !important;">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <button class="btn btn-xs btn-danger" data-toggle="tooltip"
                                                                title="Borrar"
                                                                @click="borrarDatoFacturacion(datoFacturacion, index)">
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
                activeTab: {{$tab}},
                translations: translationsES,
                cliente: {!! json_encode($cliente) !!},
                contacto: {
                    tipo: 'cliente',
                    cliente_id: {{$cliente->id}},
                    nombre: '',
                    cargo: '',
                    emails: [],
                    telefonos: [],
                    fax: ''
                },
                user: {
                    users: [
                            @foreach($cliente->users as $user)
                                {{$user->id}},
                            @endforeach
                    ],
                    userOptions: [
                            @foreach($usuarios as $id => $nombre)
                                {
                                    id: "{{$id}}", text: "{{$nombre}}"
                                },
                            @endforeach
                    ],
                },
                datoFacturacion: {
                    rfc: '',
                    razon_social: '',
                    calle: '',
                    nexterior: '',
                    ninterior: '',
                    colonia: '',
                    cp: '',
                    ciudad: '',
                    estado: '',
                    cat_regimen_id: '',
                    cat_forma_pago_id: '',
                    cat_cfdi_id: '',
                    cliente_id: {{$cliente->id}},
                },
                cargando: false,
            },
            methods: {
                agregarContacto() {
                    if (this.contacto.id) this.actualizarContacto();
                    else this.guardarContacto();
                },
                guardarContacto() {
                    this.cargando = true;
                    axios.post('/contactos', this.contacto)
                        .then(({data}) => {
                            this.cliente.contactos.push(data.contacto);
                            this.contacto = {
                                tipo: 'cliente',
                                cliente_id: {{$cliente->id}},
                                nombre: '',
                                cargo: '',
                                emails: [],
                                telefonos: [],
                                fax: ''
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

                actualizarContacto() {
                    this.cargando = true;
                    axios.put('/contactos/' + this.contacto.id,
                        {'tipo': 'cliente', nombre: this.contacto.nombre, cargo: this.contacto.cargo, fax: this.contacto.fax})//
                        .then(({data}) => {
                            this.cliente.contactos.push(this.contacto);
                            this.contacto = {
                                tipo: 'cliente',
                                cliente_id: {{$cliente->id}},
                                nombre: '',
                                cargo: '',
                                emails: [],
                                telefonos: [],
                                fax: ''
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
                editarContacto(contacto, index) {
                    this.contacto = contacto;
                    this.cliente.contactos.splice(index, 1);
                },
                borrarContacto(contacto, index) {
                    if (contacto.id == undefined) {
                        this.cliente.contactos.splice(index, 1);
                        return true;
                    }
                    this.cargando = true;
                    axios.delete('/contactos/' + contacto.id, {params: {tipo: 'cliente'}})
                        .then(({data}) => {
                            this.cliente.contactos.splice(index, 1);
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


                //-----------------------------------------DATOS DE FACTURACION---------------------
                agregarDatoFacturacion() {
                    if (this.datoFacturacion.id) this.actualizarDatoFacturacion();
                    else this.guardarDatoFacturacion();
                },
                guardarDatoFacturacion() {
                    this.cargando = true;
                    axios.post('/datosFacturacion', this.datoFacturacion)
                        .then(({data}) => {
                            this.cliente.datos_facturacion.push(data.dato);
                            this.datoFacturacion = {
                                rfc: '',
                                razon_social: '',
                                calle: '',
                                nexterior: '',
                                ninterior: '',
                                colonia: '',
                                cp: '',
                                ciudad: '',
                                estado: '',
                                cat_regimen_id: '',
                                cat_forma_pago_id: '',
                                cat_cfdi_id: '',
                                cliente_id: {{$cliente->id}},
                            };
                            this.cargando = false;
                            swal({
                                title: "Dato de Facturacion Guardado",
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
                actualizarDatoFacturacion() {
                    this.cargando = true;
                    axios.put('/datosFacturacion/' + this.datoFacturacion.id,
                        this.datoFacturacion)
                        .then(({data}) => {
                            this.cliente.datos_facturacion.push(this.datoFacturacion);
                            this.datoFacturacion = {
                                rfc: '',
                                razon_social: '',
                                calle: '',
                                nexterior: '',
                                ninterior: '',
                                colonia: '',
                                cp: '',
                                ciudad: '',
                                estado: '',
                                cat_regimen_id: '',
                                cat_forma_pago_id: '',
                                cat_cfdi_id: '',
                                cliente_id: {{$cliente->id}},
                            };
                            this.cargando = false;
                            swal({
                                title: "Dato de Facturacion Actualizado",
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
                editarDatoFacturacion(datoFacturacion, index) {
                    this.datoFacturacion = datoFacturacion;
                    this.cliente.datos_facturacion.splice(index, 1);
                },
                borrarDatoFacturacion(datoFacturacion, index) {
                    if (datoFacturacion.id == undefined) {
                        this.cliente.datos_facturacion.splice(index, 1);
                        return true;
                    }
                    this.cargando = true;
                    axios.delete('/datosFacturacion/' + datoFacturacion.id)
                        .then(({data}) => {
                            this.cliente.datos_facturacion.splice(index, 1);
                            this.cargando = false;
                            swal({
                                title: "Dato de Facturacion Borrado",
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


                guardar() {
                    this.cargando = true;
                    this.cliente['userIds'] = this.user.users;
                    axios.put('/clientes/{{$cliente->id}}', this.cliente)
                        .then(({data}) => {
                            this.cargando = false;
                            swal({
                                title: "Cliente Actualizado",
                                text: "",
                                type: "success"
                            }).then(() => {
                                window.location = "/clientes";
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
    </script>
@stop
