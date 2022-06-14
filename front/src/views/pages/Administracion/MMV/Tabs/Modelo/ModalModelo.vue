<template>
  <v-card>
    <v-card-title>
      <span
        v-if="!edicion2"
        class="headline"
      >Crear Modelo</span>
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
              <v-text-field
                v-uppercase
                v-model="modelo.nombre"
                label="Nombre"
                @keyup="buscarModelo"
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
        v-if="!edicion2"
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
    ...mapState("modelo", ["modelo", "marca_id"]),
    ...mapState("modal", ["modal2", "edicion2"])
  },
  methods: {
    ...mapActions("modelo", ["createModelo", "updateModelo"]),
    ...mapMutations("modal", ["HIDE_MODAL2"]),
    ...mapMutations("modelo", ["RESET_MODELO"]),
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateModelo(this.modelo);
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    async create() {
      if (this.$refs.form.validate()) {
        var modelo = { ...this.modelo, automotor_marca_id: this.marca_id };
        const createResult = await this.createModelo(modelo);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL2(false);
      this.RESET_MODELO();
      this.$refs.form.resetValidation();
      this.nombreUsado = false;
    },
    buscarModelo: debounce(async function() {
      if (
        this.modelo.nombre.length >= 2 &&
        this.modelo.nombre != this.modelo.nombreOriginal
      ) {
        const resp = await http.post("/modelos/busquedaModelo", {
          nombre: this.modelo.nombre,
          id: this.marca_id
        });
        this.nombreUsado = resp.data.usado;
      }
    }, 700)
  }
};
</script>

<style>
</style>