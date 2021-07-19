<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-tabs align-with-title>
      <v-tabs-slider color="primary"></v-tabs-slider>

      <v-tab>
        Marca
      </v-tab>
      <v-tab>
        Modelo
      </v-tab>
      <v-tab>
        Version
      </v-tab>

      <v-tab-item>
        <marca></marca>
      </v-tab-item>
      <v-tab-item>
        <modelo></modelo>
      </v-tab-item>
      <v-tab-item>
        <version></version>
      </v-tab-item>
    </v-tabs>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";

import Marca from "./Tabs/Marca/Marca";
import Modelo from "./Tabs/Modelo/Modelo";
import Version from "./Tabs//Version/Version";

export default {
  components: {
    Marca,
    Modelo,
    Version
  },
  methods: {
    ...mapActions("marca", ["getMarcas"]),
    ...mapActions("modelo", ["getModelos"]),
    ...mapActions("version", ["getVersiones"])
  },
  created() {
    this.getMarcas();
    this.getModelos();
    this.getVersiones();
  }
};
</script>

<style>
</style>