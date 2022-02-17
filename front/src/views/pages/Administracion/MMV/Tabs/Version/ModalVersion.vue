<template>
  <v-card>
    <v-card-title>
      <span
        v-if="!edicion3"
        class="headline"
      >Crear Version</span>
      <span
        v-else
        class="headline"
      >Editar Modelo</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-autocomplete
                class="mt-6"
                v-model="marca_id"
                :items="marcas"
                item-text="nombre"
                item-value="id"
                label="Marca"
                disabled
              ></v-autocomplete>
              <v-autocomplete
                class="mt-6"
                v-model="modelo_id"
                :items="modelos"
                item-text="nombre"
                item-value="id"
                label="Modelo"
                disabled
              ></v-autocomplete>
              <v-text-field
                v-uppercase
                v-model="version.nombre"
                label="Nombre"
                @keyup="buscarVersion"
                :rules="[
                  rules.required,
                  !nombreUsado || 'Este nombre ya está en uso',
                ]"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions class="pt-0 pb-0 d-flex justify-end">
      <v-btn
        class="mb-2"
        color="red"
        text
        @click="closeModal"
      >Cerrar</v-btn>
      <v-btn
        class="mb-2"
        v-if="!edicion3"
        color="green"
        @click="create"
        text
        native-type="submit"
      >Crear</v-btn>
      <v-btn
        class="mb-2"
        v-else
        @click="update"
        text
        color="green"
      >Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import http from "../../../../../../http-request";
import { debounce } from "debounce";

export default {
  data: () => ({
    rules: {
      required: value => !!value || "Este campo obligatorio"
    },
    nombreUsado: false
  }),
  computed: {
    ...mapState("marca", ["marcas"]),
    ...mapState("modelo", ["modelo", "modelos", "marca_id"]),
    ...mapState("version", ["version", "modelo_id"]),
    ...mapState("modal", ["modal3", "edicion3"])
  },
  methods: {
    ...mapActions("version", ["createVersion", "updateVersion"]),
    ...mapMutations("modal", ["HIDE_MODAL3"]),
    ...mapMutations("version", ["RESET_VERSION"]),
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateVersion(this.version);
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    async create() {
      if (this.$refs.form.validate()) {
        const createResult = await this.createVersion(this.version);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL3(false);
      this.RESET_VERSION();
      this.$refs.form.resetValidation();
      this.nombreUsado = false;
    },
    buscarVersion: debounce(async function() {
      if (
        this.version.nombre.length >= 2 &&
        this.version.nombre != this.version.nombreOriginal
      ) {
        const resp = await http.post("/versiones/busquedaVersion", {
          nombre: this.version.nombre,
          id: this.modelo_id
        });
        this.nombreUsado = resp.data.usado;
      }
    }, 700)
  }
};
</script>

<style>
</style>