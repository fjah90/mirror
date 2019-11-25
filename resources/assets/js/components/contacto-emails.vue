<template>
  <div>
    <form @submit.prevent="agregar()">
      <div class="row form-group">
        <div class="col-md-12">
          <h4>Emails</h4>
        </div>
        <div class="col-md-4">
          <label class="control-label">Tipo</label>
          <select class="form-control" name="tipo" v-model=email.tipo>
            <option value="Empresarial">Empresarial</option>
            <option value="Personal">Personal</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="control-label">Email</label>
          <input type="text" class="form-control" name="email" v-model="email.email" required />
        </div>
        <div class="col-md-2 text-right" style="margin-top:25px;">
          <button class="btn btn-sm btn-primary" type="submit" title="Agregar" :disabled="cargando">
            Agregar
          </button>
        </div>
      </div>
    </form>
    <div class="row" v-for="(email, index) in emails">
      <div class="col-md-4">{{email.tipo}}</div>
      <div class="col-md-6">{{email.email}}</div>
      <div class="col-md-2 text-right">
        <button class="btn btn-xs btn-success" type="button" title="Editar" @click="editar(email, index)">
          <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-xs btn-danger" type="button" title="Borrar" @click="borrar(email, index)" :disabled="cargando">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    'contacto_id': Number,
    'contacto_type': String,
    'emails': Array
  },
  data(){
    return {
      cargando: false,
      email: {tipo: "", email:""}
    }
  },
  methods: {
    agregar(){
      if(this.contacto_id == 0){
        this.emails.push(this.email);
        this.email = {tipo: "", email:""};
        return true;
      }

      if(this.email.id) this.actualizar();
      else this.guardar();
    },
    guardar(){
      this.cargando = true;
      axios.post('/emails', {contacto_id: this.contacto_id, contacto_type:this.contacto_type,
      tipo:this.email.tipo, email:this.email.email})
      .then(({data}) => {
        this.emails.push(data.email);
        this.email = {tipo: "", email:""};
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
    }, //guardarContacto
    actualizar(){
      this.cargando = true;
      axios.put('/emails/'+this.email.id, this.email)
      .then(({data}) => {
        this.emails.push(this.email);
        this.email = {tipo: "", email:""};
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
    editar(email, index){
      this.email = email;
      this.emails.splice(index, 1);
    },
    borrar(email, index){
      if(email.id == undefined){
        this.emails.splice(index, 1);
        return true;
      }

      this.cargando = true;
      axios.delete('/emails/'+email.id, {})
      .then(({data}) => {
        this.emails.splice(index, 1);
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
  }
}
</script>