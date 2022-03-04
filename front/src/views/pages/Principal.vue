<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-row>
      <v-col
        v-for="c in companias"
        :key="c.id"
      >
        <v-card
          v-if="c.polizas_vigentes.length > 0"
          width="210"
          dark
          :color="c.color"
        >
          <v-card-title class="text-h6 font-weight-light">{{c.nombre}}</v-card-title>
          <v-card-text class="text-h5 font-weight-bold white--text"> Polizas Vigentes: {{c.polizas_vigentes.length}}</v-card-text>
        </v-card>
      </v-col>
    </v-row>

  </v-card>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  data: () => ({}),
  computed: {
    ...mapState("compania", ["companias"]),
    allianz() {
      return this.companias.filter(p => p.compania_id == 4);
    }
  },
  methods: {
    ...mapActions("compania", ["getPolizasVigentes"])
  },
  created() {
    this.getPolizasVigentes();
  }
};
</script>

<style>
</style>