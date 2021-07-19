<template>
  <v-card>
    <v-card-title>
      <span>Crear Riesgo</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row>
          <v-col class=" mt-0 py-0">
            <v-select
              v-model="tipo_riesgo_id"
              :items="tipo_riesgos"
              item-value="id"
              item-text="nombre"
              label="Elegir tipo de riesgo"
              class="mr-5"
            ></v-select>
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <!-- <v-btn
        class="mb-2"
        color="red"
        text
        @click="close"
      >Cerrar</v-btn> -->
      <v-btn
        class="mb-2"
        color="green"
        @click="create"
        text
        native-type="submit"
      >Crear</v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
import { mapState, mapMutations } from "vuex";
export default {
  data: () => ({
    tipo_riesgo_id: null
  }),
  computed: {
    ...mapState("poliza", ["tipo_riesgos"]),
    ...mapState("modal", ["modal", "modal_ra", "modal_or"])
  },
  methods: {
    ...mapMutations("modal", ["HIDE_MODAL", "SHOW_MODAL_RA", "SHOW_MODAL_OR"]),
    ...mapMutations("poliza", ["SET_RIESGO"]),
    create() {
      this.setTipoRiesgo();
      this.HIDE_MODAL(false);
      if (this.tipo_riesgo_id == 1) {
        this.SHOW_MODAL_RA(true);
      } else {
        this.SHOW_MODAL_OR(true);
      }
    },
    close() {},
    setTipoRiesgo() {
      var riesgo = this.tipo_riesgos.filter(i => i.id == this.tipo_riesgo_id);
      this.SET_RIESGO(riesgo[0]);
    }
  }
};
</script>

<style>
</style>