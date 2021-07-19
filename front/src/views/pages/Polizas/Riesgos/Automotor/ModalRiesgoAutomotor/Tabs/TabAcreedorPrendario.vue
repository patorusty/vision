<template>
  <v-card flat>
    <v-card-subtitle class="d-flex flex-row align-center"><span>Tiene Acreedor Prendario?</span>
      <v-checkbox
        v-model="riesgo_automotor.acreedor_prendario"
        class="ms-2"
      ></v-checkbox>
    </v-card-subtitle>
    <v-card-text>
      <v-row>
        <v-col>
          <v-text-field
            v-model="riesgo_automotor.acreedor_rs"
            :disabled="riesgo_automotor.acreedor_prendario == false"
            label="Razon Social"
          ></v-text-field>
        </v-col>
        <v-col>
          <v-text-field
            v-model="riesgo_automotor.acreedor_cuit"
            :disabled="riesgo_automotor.acreedor_prendario == false"
            label="CUIT"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapState } from "vuex";
import { helpers } from "../../../../../../../helpers";
export default {
  mixins: [helpers],
  data: () => ({
    calendarioVencimientoOblea: false
  }),
  computed: {
    ...mapState("riesgo", ["riesgo_automotor"])
  }
};
</script>

<style>
</style>