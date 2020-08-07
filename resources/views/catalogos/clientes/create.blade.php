@extends($layout==="iframe"?'layouts/iframe' : 'layouts/default')


{{-- Page title --}}
@section('title')
    Nuevo Cliente | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <h1>Nuevo Cliente {{ ($nacional)?"Nacional":"Extranjero" }}</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <form @submit.prevent=guardar()>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Datos Generales</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label class="control-label">Usuario</label>
                                    <select class="form-control" name="usuario_id" v-model='cliente.usuario_id'
                                            required>
                                        @foreach($usuarios as $id => $nombre)
                                            <option value="{{$id}}">{{$nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Usuario(s) adicional</label>
                                        <select2multags :options="user.userOptions" v-model="user.users" style="width:100%;">
                                        </select2multags>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Tipo</label>
                                    <select class="form-control" name="tipo_id" v-model='cliente.tipo_id' required>
                                        @foreach($tipos as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-8">
                                    <label class="control-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" v-model="cliente.nombre"
                                           required/>
                                </div>
                            </div>
                            <div class="row form-group">
                                {{--@if($nacional)
                                    <div class="col-md-4">
                                        <label class="control-label">RFC</label>
                                        <input type="text" class="form-control" name="rfc" v-model="cliente.rfc"/>
                                    </div>
                                @endif--}}
                                <div class="col-md-8">
                                    <label class="control-label">Razon Social</label>
                                    <input type="text" class="form-control" name="razon_social"
                                           v-model="cliente.razon_social"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel ">
                        <div class="panel-heading">
                            <h3 class="panel-title">Dirección</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="{{($nacional) ? 'col-md-4' : 'col-md-12'}}">
                                    <label class="control-label">{{($nacional) ? 'Calle' : 'Dirección'}}</label>
                                    <input type="text" class="form-control" name="calle" v-model="cliente.calle" maxlength="191"/>
                                </div>
                                @if($nacional)
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
                                    <label class="control-label">Colonia  {{($nacional) ? '' : '(opcional)'}}</label>
                                    <input type="text" class="form-control" name="colonia" v-model="cliente.colonia"/>
                                </div>
                                @if($nacional)
                                    <div class="col-md-4">
                                        <label class="control-label">Delegacion</label>
                                        <input type="text" class="form-control" name="delagacion"
                                               v-model="cliente.delegacion"/>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <label class="control-label">C. Postal</label>
                                    <input type="text" class="form-control cp" name="cp" v-model="cliente.cp" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label class="control-label">Ciudad</label>
                                    <input type="text" class="form-control municipio" name="ciudad" v-model="cliente.ciudad"/>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Estado</label>
                                    <input type="text" class="form-control estado" name="estado" v-model="cliente.estado"/>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">País</label>
                                    <input type="text" class="form-control" name="pais" v-model="cliente.pais"/>
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
                <div class="col-lg-12">
                    <div class="panel ">
                        <div class="panel-heading">
                            <h3 class="panel-title">Contactos del Cliente</h3>
                        </div>
                        <div class="panel-body">
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    @if($layout !=='iframe')
                        <a class="btn btn-default" href="{{route('clientes.index')}}" style="margin-right: 20px;">
                            Regresar
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary" :disabled="cargando">
                        <i class="fas fa-save"></i>
                        Guardar Cliente
                    </button>
                </div>
            </div>
        </form>

    </section>
    <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')

    <script>
        const app = new Vue({
            el: '#content',
            data: {
                is_iframe: {{$layout==="iframe"?'true' : 'false'}},
                translations: translationsES,
                cliente: {
                    usuario_id: '',
                    tipo_id: '',
                    nombre: '',
                    rfc: '',
                    razon_social: '',
                    calle: '',
                    nexterior: '',
                    ninterior: '',
                    colonia: '',
                    delegacion: '',
                    cp: '',
                    ciudad: '',
                    estado: '',
                    pais: '{{ (($nacional)?"México":"") }}',
                    nacional: {{ (($nacional)?"true":"false") }},
                    pagina_web: '',
                    adicionales: '',
                    contactos: [],
                    userIds: []
                },
                contacto: {
                    tipo: 'cliente',
                    cliente_id: 0,
                    nombre: '',
                    cargo: '',
                    emails: [],
                    telefonos: []
                },
                user: {
                    users: [],
                    userOptions: [
                        @foreach($usuarios as $id => $nombre)
                        {
                            id: "{{$id}}", text: "{{$nombre}}"
                        },
                        @endforeach
                    ],
                },
                cargando: false,
            },
            methods: {
                guardar() {
                    this.cargando = true;
                    this.cliente.contactos.push(this.contacto);
                    this.cliente.userIds = this.user.users;
                    console.log(this.contacto)
                    axios.post('/clientes', this.cliente)
                        .then(({data}) => {
                            this.cargando = false;
                            swal({
                                title: "Cliente Guardado",
                                text: "",
                                type: "success"
                            }).then(() => {
                                if (this.is_iframe) {
                                    parent.postMessage({message: "OK", tipo: "cliente", object: data.cliente}, "*")
                                    window.location = "/clientes/crearNacional?layout=iframe";
                                } else {
                                    window.location = "/clientes/" + data.cliente.id + "/editar?contactos";
                                }
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
