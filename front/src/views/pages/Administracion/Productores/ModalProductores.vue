<template>
  <v-card>
    <v-card-title>
      <span v-if="!edicion" class="headline">Crear Productor</span>
      <span v-else class="headline">Editar Productor</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col>
              <v-text-field
              v-uppercase
                v-model="productor.nombre"
                label="Nombre"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
              v-uppercase
                v-model="productor.apellido"
                label="Apellido"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field
              v-uppercase
                v-model="productor.cuit"
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
              v-uppercase
                v-model="productor.email"
                label="Email"
                :rules="[rules.email, rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field
              v-uppercase
                v-model="productor.telefono_1"
                label="Telefono"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
              v-uppercase
                v-model="productor.telefono_2"
                label="Telefono Alt."
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field
              v-uppercase
                v-model="productor.matricula"
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
                v-model="productor.activo"
                label="Productor Activo?"
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
    ...mapState("productor", ["productor"]),
    ...mapState("modal", ["modal", "edicion"]),
  },
  methods: {
    ...mapActions("productor", [
      "getProductor",
      "resetProductorState",
      "updateProductor",
      "createProductor",
    ]),
    ...mapMutations("modal", ["HIDE_MODAL"]),
    ...mapMutations("productor", ["RESET_PRODUCTOR"]),
    async create() {
      if (this.$refs.form.validate()) {
        const createResult = await this.createProductor(this.productor);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateProductor(this.productor);
        if (updateResult) {
          this.closeModal();
          
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_PRODUCTOR();
      this.$refs.form.resetValidation();
      this.cuitUsado = false;
      this.matriculaUsada = false;
    },
    buscarCuit: debounce(async function () {
      if (this.productor.cuit.length >= 6 && this.productor.cuit != this.productor.cuitOriginal) {
        const resp = await http.post("/productores/busquedaCuit", {
          cuit: this.productor.cuit,
        });
        this.cuitUsado = resp.data.usado;
      }
    }, 700),
    buscarMatricula: debounce(async function () {
      if (this.productor.matricula.length >= 3 && this.productor.matricula != this.productor.matriculaOriginal) {
        const resp = await http.post("/productores/busquedaMatricula", {
          matricula: this.productor.matricula,
        });
        this.matriculaUsada = resp.data.usado;
      }
    }, 700),
  },
  watch: {
    modal() {
      if(!this.modal) {
        this.closeModal();
      }
  }
  }
};
</script>

<style>
</style>