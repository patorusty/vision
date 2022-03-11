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
              <v-card-text class="subtitle-1 font-weight-light"> Polizas Vigentes: {{c.polizas_vigentes.length}}</v-card-text>
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
  data: () => ({}),
  computed: {
    ...mapState("compania", ["companias_activas", "loading"]),
    totalPolizas() {
      var total = 0;
      this.companias_activas.forEach(c => {
        total = total + c.polizas_vigentes.length;
      });
      return total;
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