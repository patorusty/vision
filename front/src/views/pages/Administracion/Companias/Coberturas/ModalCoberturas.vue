<template>
  <v-card>
    <v-card-title>
      <span>Cobertura</span>
    </v-card-title>
    <v-card-text class="pb-0">
      <v-container>
        <v-form ref="form">
          <v-row>
            <v-col class="py-0">
              <v-text-field
                v-uppercase
                label="Nombre"
                v-model="cobertura.nombre"
                :rules="[
                  rules.required,
                ]"
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="py-0">
              <v-switch
                v-model="cobertura.todo_riesgo"
                label="Todo Riesgo"
              ></v-switch>
            </v-col>
            <v-col class="py-0">
              <v-text-field
                v-uppercase
                label="Franquicia"
                v-model="cobertura.franquicia"
                :rules="[
                  rules.required,
                ]"
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="py-0">
              <v-text-field
                v-uppercase
                label="Antigüedad"
                v-model="cobertura.antiguedad"
                :rules="[
                  rules.required,
                ]"
              >
              </v-text-field>
            </v-col>
            <!-- <v-col class="py-0">
              <v-select
                label="Ajuste"
                v-model="cobertura.ajuste"
                :items="ajustes"
                :rules="[rules.required]"
              >
              </v-select>
            </v-col> -->
          </v-row>
          <v-row>
            <v-col class="py-0">
              <v-textarea
                rows="2"
                label="Detalle"
                v-model="cobertura.detalle"
              >
              </v-textarea>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="py-0">
              <v-switch
                v-model="cobertura.activo"
                label="Cobertura Activa?"
              ></v-switch>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-card-text>
    <v-card-actions class=" pt-0 d-flex justify-end">
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
import { helpers } from "../../../../../helpers";

export default {
  mixins: [helpers],
  data: () => ({
    rules: {
      required: value => !!value || "Este campo obligatorio"
    },
    ajustes: ["0 %", "10 %", "20 %", "30 %"]
  }),
  computed: {
    ...mapState("compania", ["compania"]),
    ...mapState("cobertura", ["cobertura"]),
    ...mapState("modal", ["modal3", "edicion3"])
  },
  methods: {
    ...mapActions("cobertura", [
      "resetCoberturaState",
      "updateCobertura",
      "createCobertura"
    ]),
    ...mapMutations("modal", ["HIDE_MODAL3"]),
    ...mapMutations("cobertura", ["RESET_COBERTURA"]),
    async create() {
      if (this.$refs.form.validate()) {
        this.cobertura.compania_id = this.compania.id;
        const createResult = await this.createCobertura(this.cobertura);
        if (createResult) {
          this.closeModal();
        }
      }
    },
    async update() {
      if (this.$refs.form.validate()) {
        const updateResult = await this.updateCobertura(this.cobertura);
        if (updateResult) {
          this.closeModal();
        }
      }
    },
    closeModal() {
      this.HIDE_MODAL3(false);
      this.RESET_COBERTURA();
      this.$refs.form.resetValidation();
      this.cobUsada = false;
    }
  },
  watch: {
    modal3() {
      if (!this.modal3) {
        this.closeModal();
      }
    }
  }
};
</script>

<style>
</style>