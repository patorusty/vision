<template>
  <v-card>
    <v-card-title>
      <span>Crear Otros Riesgos</span>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row>
          <v-col class=" mt-0 py-0">
            <div>Otros</div>
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
    ...mapState("modal", ["modal_or"])
  },
  methods: {
    ...mapMutations("modal", ["HIDE_MODAL_OR"]),
    create() {},
    close() {}
  }
};
</script>

<style>
</style>