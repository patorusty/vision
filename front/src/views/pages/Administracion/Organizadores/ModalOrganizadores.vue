<template>
  <v-card>
    <v-card-title>
      <span v-if="!edicion" class="headline">Crear Organizador</span>
      <span v-else class="headline">Editar Organizador</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col>
              <v-text-field
                v-model="organizador.nombre"
                label="Nombre"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
                v-model="organizador.apellido"
                label="Apellido"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field
                v-model="organizador.cuit"
                label="CUIT"
                @keyup="buscarCuit"
                :rules="[
                  rules.required,
                  !cuitUsado || 'Este CUIT ya está en uso',
                ]"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
                v-model="organizador.email"
                label="Email"
                :rules="[rules.email, rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field
                v-model="organizador.telefono_1"
                label="Telefono"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
                v-model="organizador.telefono_2"
                label="Telefono Alt."
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field
                v-model="organizador.matricula"
                label="Matricula"
                @keyup="buscarMatricula"
                :rules="[
                  rules.required,
                  !matriculaUsada || 'Esta matricula ya está en uso',
                ]"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-switch
                v-model="organizador.activo"
                label="Organizador Activo?"
              ></v-switch>
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
import { mapState, mapActions, mapMutations } from "vuex";
import http from "../../../../http-request";
import { debounce } from "debounce";

export default {
  data: () => ({
    cuitUsado: false,
    matriculaUsada: false,
    rules: {
      required: (value) => !!value || "Este campo obligatorio",
      email: (value) => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(value) || "Igrese un email válido";
      },
    },
  }),
  computed: {
    ...mapState("organizador", ["organizador"]),
    ...mapState("modal", ["modal", "edicion"]),
  },
  methods: {
    ...mapActions("organizador", [
      "resetOrganizadorState",
      "updateOrganizador",
      "createOrganizador",
    ]),
    ...mapMutations("modal", ["HIDE_MODAL"]),
    ...mapMutations("organizador", ["RESET_ORGANIZADOR"]),
    async create() {
      if (this.$refs.form.validate()) {
        const createResult = await this.createOrganizador(this.organizador);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateOrganizador(this.organizador);
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_ORGANIZADOR();
      this.$refs.form.resetValidation();
      this.cuitUsado = false;
      this.matriculaUsada = false;
    },
    buscarCuit: debounce(async function () {
      if (this.organizador.cuit.length >= 6  && this.organizador.cuit != this.organizador.cuitOriginal) {
        const resp = await http.post("/organizadores/busquedaCuit", {
          cuit: this.organizador.cuit,
        });
        this.cuitUsado = resp.data.usado;
      }
    }, 700),
    buscarMatricula: debounce(async function () {
      if (this.organizador.matricula.length >= 3 && this.organizador.matricula != this.organizador.matriculaOriginal) {
        const resp = await http.post("/organizadores/busquedaMatricula", {
          matricula: this.organizador.matricula,
        });
        this.matriculaUsada = resp.data.usado;
      }
    }, 700),
  },
  watch: {
    modal() {
      if (!this.modal) {
        this.closeModal();
      }
    },
  },
};
</script>

<style>
</style>