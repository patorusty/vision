<template>
  <div>
    <v-card
      class="mt-0 mx-4 pa-3"
      v-if="!loading"
    >
      <v-row>
        <div
          v-for="c in companias_activas"
          :key="c.id"
        >
          <v-col>
            <base-material-card
              v-if="c.polizas_vigentes.length > 0"
              width="240"
              inline
              :color="c.color"
            >
              <template v-slot:heading>
                <div class="display-2 font-weight-light">
                  {{c.nombre}}
                </div>
              </template>
              <v-card-text class="subtitle-1 font-weight-light"> Polizas Vigentes: {{getCount(c.polizas_vigentes)}}</v-card-text>
            </base-material-card>
          </v-col>
        </div>
        <v-col>
          <base-material-card
            width="240"
            inline
            color="primary"
          >
            <template v-slot:heading>
              <div class="display-2 font-weight-light">
                TOTAL
              </div>
            </template>
            <v-card-text class="subtitle-1 font-weight-light"> Total Polizas Vigentes: {{totalPolizas}}</v-card-text>
          </base-material-card>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  computed: {
    ...mapState("compania", ["companias_activas", "loading"]),
    totalPolizas() {
      var total = 0;
      this.companias_activas.forEach(c => {
        if (c.polizas_vigentes.length > 0)
          total = total + this.getCount(c.polizas_vigentes);
      });
      return total;
    }
  },
  methods: {
    ...mapActions("compania", ["getPolizasVigentes"]),
    getCount(polizas) {
      var total = 0;
      polizas.forEach(p => {
        if (p.riesgo_automotor.length > 0)
          total = total + p.riesgo_automotor.length;
      });
      return total;
    }
  },
  created() {
    this.getPolizasVigentes();
  }
};
</script>

<style>
</style>