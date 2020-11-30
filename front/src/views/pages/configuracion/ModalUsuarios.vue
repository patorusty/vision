<template>
  <v-card>
    <v-card-title>
      <span v-if="!edicion" class="headline">Crear Usuario</span>
      <span v-else class="headline">Editar Usuario</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col cols="12" sm="6" md="6">
              <v-text-field
                label="Company"
                v-model="usuario.compania"
                :disabled="true"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="6">
              <v-text-field
                label="E-Mail"
                v-model="usuario.email"
                :rules="[
                  rules.required,
                  rules.email,
                  !mailUsado || 'Este email ya está en uso'
                ]"
                @keyup="checkEmail"
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field
                label="Nombre"
                v-model="usuario.nombre"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field
                label="Apellido"
                v-model="usuario.apellido"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4">
              <v-select
                :items="tipo_usuarios"
                label="Tipo de Usuario"
                item-text="nombre"
                item-value="id"
                v-model="usuario.tipo_usuario_id"
              >
              </v-select>
            </v-col>
            <v-col cols="12" sm="4">
              <v-switch
                class="ma-2"
                v-model="usuario.activo"
                label="Usuario Activo?"
              ></v-switch>
            </v-col>
            <v-col cols="12" sm="4">
              <v-avatar color="red">
                <span class="white--text headline">nombre</span>
              </v-avatar>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn class="mb-2" color="red" text @click="closeModal">Cerrar</v-btn>
      <v-btn
        class="mb-2"
        v-if="!edicion"
        color="green"
        @click="create"
        text
        native-type="submit"
        >Crear</v-btn
      >
      <v-btn class="mb-2" v-else @click="update" text color="green"
        >Guardar</v-btn
      >
    </v-card-actions>
  </v-card>
</template>
<script>
import http from "../../../http-request";
import { mapState, mapActions, mapMutations } from "vuex";
import { debounce } from "debounce";

export default {
  data: () => ({
    url: "tipousuario",
    dialog: false,
    mailOriginal: null,
    tipo_usuarios: [],
    tipo_usuario: "",
    mailUsado: false,
    rules: {
      required: value => !!value || "Este campo obligatorio",
      email: value => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(value) || "Igrese un email válido";
      }
    }
  }),
  computed: {
    ...mapState("usuarios", ["usuario"]),
    ...mapState("modal", ["modal", "edicion"])
  },
  methods: {
    ...mapActions("usuarios", [
      "getUsuario",
      "resetUsuarioState",
      "updateUsuario",
      "createUsuario"
    ]),
    ...mapMutations("modal", ["HIDE_MODAL"]),
    ...mapMutations("usuarios", ["RESET_USUARIO"]),
    update() {
      if (this.$refs.form.validate()) {
        this.updateUsuario(this.usuario);
        this.closeModal();
      }
    },
    create() {
      if (this.$refs.form.validate()) {
        this.usuario.password = "123";
        this.createUsuario(this.usuario);
        this.closeModal();
      }
    },
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_USUARIO();
      this.$refs.form.resetValidation();
        this.mailOriginal = null;
        this.mailUsado = false;
    },
    cargarTipo_Usuarios() {
      http.get(this.url).then(r => {
        this.tipo_usuarios = r.data.data;
      });
    },
    checkEmail: debounce(async function() {
      if (
        this.usuario.email.length >= 6 &&
        this.mailOriginal != this.usuario.email
      ) {
        const resp = await http.post("/usuario/busquedaMail", {
          email: this.usuario.email
        });
        this.mailUsado = resp.data.usado;
      }
    }, 700)
  },
  watch: {
    usuario: function() {
      if (this.edicion) {
        this.mailOriginal = this.usuario.email;
      }
    }
  },
  created() {
    this.cargarTipo_Usuarios();
  }
};
</script>
