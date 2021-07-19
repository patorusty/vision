<template>
  <v-card>
    <v-card-title>
      <span
        v-if="!edicion"
        class="headline"
      >Crear Marca</span>
      <span
        v-else
        class="headline"
      >Editar Marca</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-uppercase
                v-model="marca.nombre"
                label="Nombre"
                @keyup="buscarMarca"
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
        v-if="!edicion"
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
    ...mapState("marca", ["marca"]),
    ...mapState("modal", ["modal", "edicion"])
  },
  methods: {
    ...mapActions("marca", ["createMarca", "updateMarca"]),
    ...mapMutations("modal", ["HIDE_MODAL"]),
    ...mapMutations("marca", ["RESET_MARCA"]),
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateMarca(this.marca);
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    async create() {
      if (this.$refs.form.validate()) {
        const createResult = await this.createMarca(this.marca);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL(false);
      this.RESET_MARCA();
      this.$refs.form.resetValidation();
      this.nombreUsado = false;
    },
    buscarMarca: debounce(async function() {
      if (
        this.marca.nombre.length >= 2 &&
        this.marca.nombre != this.marca.nombreOriginal
      ) {
        const resp = await http.post("/marcas/busquedaMarca", {
          nombre: this.marca.nombre
        });
        this.nombreUsado = resp.data.usado;
      }
    }, 700)
  }
};
</script>

<style>
</style>