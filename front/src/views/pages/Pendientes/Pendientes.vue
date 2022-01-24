<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-tabs v-model="tab">
      <v-tab>Polizas Pendientes</v-tab>
      <v-tab>Polizas a Renovar</v-tab>
      <v-tab>Siniestros</v-tab>
      <v-tab>Endosos</v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <v-tab-item>
        <polizas-pendientes />
      </v-tab-item>
      <v-tab-item>
        <polizas-a-renovar />
      </v-tab-item>
      <v-tab-item>
        <siniestros />
      </v-tab-item>
      <v-tab-item>
        <endosos />
      </v-tab-item>
    </v-tabs-items>
  </v-card>
</template>

<script>
import Siniestros from "./Siniestros/Siniestros.vue";
import Endosos from "./Endosos/Endosos.vue";
import PolizasPendientes from "./Polizas/PolizasPendientes.vue";
import PolizasARenovar from "./Polizas/PolizasARenovar.vue";

export default {
  components: { Siniestros, Endosos, PolizasPendientes, PolizasARenovar },
  data() {
    return {
      tab: null
    };
  }
};
</script>

<style>
</style>