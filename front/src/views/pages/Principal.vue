<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-row>
      <div
        v-for="c in companias"
        :key="c.id"
      >
        <v-col>
          <base-material-card
            v-if="c.polizas_vigentes.length > 0"
            width="210"
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